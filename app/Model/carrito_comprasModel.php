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
        


        public function acceptarPedido($id_pedido) {
            try {
                $db = $this->conexion->conectar(); 
                if ($db === null) {
                    return "Error al conectar a la base de datos.";
                }
                $pedidosCollection = $db->pedidos; 
                $facturasCollection = $db->facturas; 
                $pedido = $pedidosCollection->findOne(['id_pedido' => $id_pedido]);
                if (!$pedido) {
                    return "Pedido no encontrado.";
                }
                $pedidosCollection->updateOne(
                    ['id_pedido' => $id_pedido],
                    ['$set' => ['estado' => 'Aprobado']]
                );
                $id_usuario = $pedido['id_cliente'];
                $productos = $pedido['productos'];
                $total = $pedido['total'];
                $id_factura = time();
                $nuevaFactura = [
                    'id_factura' => $id_factura,
                    'id_cliente' => $id_usuario,
                    'id_pedido' => $id_pedido,
                    'productos' => $productos,
                    'total' => $total,
                    'fecha_emision' => (new DateTime())->format('Y-m-d H:i:s'),
                    'detalle' => 'Esta factura se generó tras la aceptación de un pedido.',
                ];
                $resultado = $facturasCollection->insertOne($nuevaFactura);
                return $resultado;
            } catch (Exception $e) {
                return "Error: " . $e->getMessage();
            }
        }

        public function rechasarPedido($id_pedido) {
            try {
                $db = $this->conexion->conectar(); 
                if ($db === null) {
                    return "Error al conectar a la base de datos.";
                }
                $pedidosCollection = $db->pedidos;  
                
                $resultado = 0;
                return $resultado;
            } catch (Exception $e) {
                return "Error: " . $e->getMessage();
            }
        }

        public function cambiarEstadoPedido($id_pedido) {
            try {
                $db = $this->conexion->conectar(); 
                if ($db === null) {
                    return "Error al conectar a la base de datos.";
                }
                $pedidosCollection = $db->pedidos;  
                
                $resultado = 0;
                return $resultado;
            } catch (Exception $e) {
                return "Error: " . $e->getMessage();
            }
        }
        
    }
?>
