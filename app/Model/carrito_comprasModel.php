<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . "/RopayMedia/vendor/autoload.php";
    require_once $_SERVER['DOCUMENT_ROOT'] . "/RopayMedia/app/Model/baseDatosModel.php";

    class carrito_comprasModel {
        private $conexion;
        

        public function __construct() {
            $this->conexion = new Conexion();  
        }

        public function crearPedido($id_usuario, $metodo_retiro, $direccion, $productos, $total, $img_sinpe) {
            try {
                $db = $this->conexion->conectar(); 
                if ($db === null) {
                    return "Error al conectar a la base de datos.";
                }
                $pedidosCollection = $db->pedidos; 
                $productosCollection = $db->productos; 
                $id_pedido = time();
                $nuevoPedido = [
                    'id_pedido' => $id_pedido,
                    'fecha' => (new DateTime())->format('Y-m-d H:i:s'),
                    'id_cliente' => $id_usuario,
                    'metodo_retiro' => $metodo_retiro,
                    'direccion' => $direccion,
                    'ubicacion_pedido' => 'En la tienda',
                    'productos' => $productos,
                    'total' => $total,
                    'estado' => 'En validacion',
                    'img_sinpe' => $img_sinpe
                ];
                $resultadoPedido = $pedidosCollection->insertOne($nuevoPedido);
                if ($resultadoPedido->getInsertedCount() > 0) {
                    foreach ($productos as $producto) {
                        $productoId = $producto['id_producto'];
                        $cantidadPedido = $producto['cantidad'];
                        $resultadoStock = $productosCollection->updateOne(
                            ['id_producto' => $productoId],
                            ['$inc' => ['stock' => -$cantidadPedido]]
                        );
                        if ($resultadoStock->getModifiedCount() == 0) {
                            return "Error al actualizar el stock para el producto con ID: $productoId";
                        }
                    }
                    return $resultadoPedido;
                } else {
                    return "Error al insertar el pedido.";
                }
            } catch (Exception $e) {
                return "Error: " . $e->getMessage();
            }
        }
        

        public function crearFactura($id_usuario, $id_pedido, $productos, $total)
        {
            try {
                $db = $this->conexion->conectar(); 
                if ($db === null) {
                    throw new Exception("Error al conectar a la base de datos.");
                }
                $facturasCollection = $db->facturas; 

                $id_factura = time();
                $nuevaFactura = [
                    'id_factura' => $id_factura,
                    'id_usuario' => $id_usuario,
                    'id_pedido' => $id_pedido,
                    'productos' => $productos,
                    'total' => $total,
                    'fecha_emision' => (new DateTime())->format('Y-m-d H:i:s'),
                    'detalle' => 'Factura generada tras la aceptación de un pedido.',
                ];

              
                $resultado = $facturasCollection->insertOne($nuevaFactura);

                if ($resultado->getInsertedCount() > 0) {
                    return $id_factura;
                } else {
                    throw new Exception("Error al generar la factura.");
                }
            } catch (Exception $e) {
                return "Error: " . $e->getMessage();
            }
        }
    }
?>
        