<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . "/RopayMedia/vendor/autoload.php";
    require_once $_SERVER['DOCUMENT_ROOT'] . "/RopayMedia/app/Model/baseDatosModel.php";

    class pedidoModel {
        private $conexion;
        

        public function __construct() {
            $this->conexion = new Conexion();  
        }
        
        public function listaPedidos($estado){
            try {
                $db = $this->conexion->conectar();
                if ($db === null) {
                    return [];
                }
                $pedidosCollection = $db->pedidos; 
                $clientesCollection = $db->usuarios;
                $pedidos = $pedidosCollection->find(['estado' => $estado]); 

                $listaPedidos = [];
                foreach ($pedidos as $pedido) {
                    $cliente = $clientesCollection->findOne(['_id' => $pedido['id_cliente']]);
                    $listaPedidos[] = [
                        'id_pedido' => $pedido['id_pedido'], 
                        'fecha' => $pedido['fecha'],
                        'metodo_retiro' => $pedido['metodo_retiro'],
                        'cliente' => $cliente ? $cliente['nombre'] . ' ' . $cliente['apellido'] : 'Cliente desconocido',
                        'ubicacion_pedido' => $pedido['ubicacion_pedido'],
                        'total' => $pedido['total'],
                        'estado' => $pedido['estado'],
                    ];
                }
                return $listaPedidos;
            } catch (\Exception $e) {
                return [];
            }
        }


        public function listaPedidosXUbicacion($ubicacion, $estado){
            try {
                $db = $this->conexion->conectar();
                if ($db === null) {
                    return [];
                }
                $pedidosCollection = $db->pedidos; 
                $clientesCollection = $db->usuarios;
                $pedidos = $pedidosCollection->find(['ubicacion_pedido' => $ubicacion, 'estado' => $estado]); 

                $listaPedidos = [];
                foreach ($pedidos as $pedido) {
                    $cliente = $clientesCollection->findOne(['_id' => $pedido['id_cliente']]);
                    $listaPedidos[] = [
                        'id_pedido' => $pedido['id_pedido'], 
                        'fecha' => $pedido['fecha'],
                        'metodo_retiro' => $pedido['metodo_retiro'],
                        'cliente' => $cliente ? $cliente['nombre'] . ' ' . $cliente['apellido'] : 'Cliente desconocido',
                        'ubicacion_pedido' => $pedido['ubicacion_pedido'],
                        'total' => $pedido['total'],
                        'estado' => $pedido['estado'],
                    ];
                }
                return $listaPedidos;
            } catch (\Exception $e) {
                return [];
            }
        }


        public function infoPedido($id_pedido){
            try {
                $db = $this->conexion->conectar();
                if ($db === null) {
                    return [];
                }
        
                $pedidosCollection = $db->pedidos; 
                $usuariosCollection = $db->usuarios;
                $pedido = $pedidosCollection->findOne(['id_pedido' => (int)$id_pedido]); 
        
                if ($pedido !== null) {
                    $cliente = $usuariosCollection->findOne(['_id' => $pedido['id_cliente']]);
                    return [
                        'id_pedido' => $pedido['id_pedido'],
                        'fecha' => $pedido['fecha'],
                        'nombre_completo' =>  $cliente ? $cliente['nombre'] . ' ' . $cliente['apellido'] : 'Cliente desconocido',
                        'telefono' =>  $cliente ? $cliente['telefono'] : 'Telefono desconocido',
                        'correo' =>  $cliente ? $cliente['correo'] : 'Correo desconocido',
                        'metodo_retiro' => $pedido['metodo_retiro'],
                        'direccion' => $pedido['direccion'],
                        'ubicacion_pedido' => ['ubicacion_pedido'],
                        'productos' => $pedido['productos'],
                        'total' => $pedido['total'],
                        'estado' => $pedido['estado'],
                        'img_sinpe' => $pedido['img_sinpe']
                    ];
                }
                return [];
            } catch (\Exception $e) {
                return [];
            }
        }

        public function aceptarPedido($id_pedido) {
            try {
                $db = $this->conexion->conectar(); 
                if ($db === null) {
                    return "Error al conectar a la base de datos.";
                }
                $pedidosCollection = $db->pedidos; 
                $facturasCollection = $db->facturas; 
                $pedido = $pedidosCollection->findOne(['id_pedido' => (int)$id_pedido]);
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
                    'id_usuario' => $id_usuario,
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

        public function rechazarPedido($id_pedido) {
            try {
                $db = $this->conexion->conectar(); 
                if ($db === null) {
                    return "Error al conectar a la base de datos.";
                }
                $pedidosCollection = $db->pedidos;  
                $productosCollection = $db->productos;
                $pedido = $pedidosCollection->findOne(['id_pedido' => (int)$id_pedido]);
        
                if (!$pedido) {
                    return "Pedido no encontrado.";
                }

                $productos = $pedido['productos'];
        
                foreach ($productos as $producto) {
                    $productoId = $producto['id_producto'];
                    $cantidad = $producto['cantidad'];

                    $resultadoStock = $productosCollection->updateOne(
                        ['id_producto' => $productoId],
                        ['$inc' => ['stock' => $cantidad]]
                    );
        
                    if ($resultadoStock->getModifiedCount() == 0) {
                        return "Error al actualizar el stock para el producto con ID: $productoId";
                    }
                }
                $resultadoPedido = $pedidosCollection->updateOne(
                    ['id_pedido' => (int)$id_pedido],
                    ['$set' => ['estado' => 'Rechazado']]
                );
        
                if ($resultadoPedido->getModifiedCount() > 0) {
                    return "El pedido ha sido rechazado y el stock ha sido restaurado.";
                } else {
                    return "Error al actualizar el estado del pedido.";
                }
            } catch (Exception $e) {
                return "Error: " . $e->getMessage();
            }
        }
        

        public function cambiarUbicacionPedido($id_pedido, $ubicacion_pedido) {
            try {
                $db = $this->conexion->conectar(); 
                if ($db === null) {
                    return "Error al conectar a la base de datos.";
                }
                $pedidosCollection = $db->pedidos;  
                $resultado = $pedidosCollection->updateOne(
                    ['id_pedido' => (int)$id_pedido],
                    ['$set' => ['ubicacion_pedido' => $ubicacion_pedido]]
                );
                return $resultado;
            } catch (Exception $e) {
                return "Error: " . $e->getMessage();
            }
        }
        
        
    }
?>
        