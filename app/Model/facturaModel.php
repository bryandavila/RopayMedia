<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . "/RopayMedia/vendor/autoload.php";
    require_once $_SERVER['DOCUMENT_ROOT'] . "/RopayMedia/app/Model/baseDatosModel.php";

    class facturaModel {
        private $conexion;

        public function __construct() {
            $this->conexion = new Conexion();  //Crear la conexión
        }

                 // Obtener todas las facturas
                 public function obtenerFacturas() {
                    try {
                        $db = $this->conexion->conectar();
                        if ($db === null) {
                            return [];
                        }
                
                        $facturasCollection = $db->facturas; 
                        $facturas = $facturasCollection->find(); 
                
                        $listaFacturas = [];
                        foreach ($facturas as $factura) {
                            $fechaEmision = null;
                            if (isset($factura['fecha_emision']) && !empty($factura['fecha_emision'])) {
                                $fechaEmision = $factura['fecha_emision']; // Mantén el formato como string
                            } else {
                                $fechaEmision = null; // O algún valor predeterminado
                            }

                            $listaFacturas[] = [
                                'id_factura' => isset($factura['id_factura']) && $factura['id_factura'] instanceof MongoDB\BSON\ObjectId ? (string)$factura['id_factura'] : (int)$factura['id_factura'],
                                'id_pedido' => isset($factura['id_pedido']) && $factura['id_pedido'] instanceof MongoDB\BSON\ObjectId
                                ? (string)$factura['id_pedido'] : (int)$factura['id_pedido'], 
                                'id_usuario' => isset($factura['id_usuario']) ? (int)$factura['id_usuario'] : null, // Verificar existencia
                                'productos' => $factura['productos'], // Detalles de productos
                                'total' => isset($factura['total']) ? (float)$factura['total'] : null, // Verificar existencia
                                'fecha_emision' => $fechaEmision,
                                'detalle' => isset($factura['detalle']) ? $factura['detalle'] : null // Verificar existencia
                            ];
                        }
                        return $listaFacturas;
                        
                
                    } catch (\Exception $e) {
                        return [];
                    }
                }

    // Crear una nueva factura
    public function crearFactura($factura) {
        try {
            $db = $this->conexion->conectar();
            if ($db === null) {
                return false;
            }
        
            $facturasCollection = $db->facturas;
            $facturasCollection->insertOne($factura);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function actualizarFactura($idFactura, $facturaActualizada) {
        try {
            $db = $this->conexion->conectar();
            if ($db === null) {
                return false;
            }

            $facturasCollection = $db->facturas;
            $facturasCollection->updateOne(
                ['id_factura' => (int)$idFactura], // Filtro
                ['$set' => $facturaActualizada] // Datos a actualizar
            );
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    // Obtener una factura por ID
    public function obtenerFacturaPorId($idFactura) {
        try {
            $db = $this->conexion->conectar();
            if ($db === null) {
                return null;
            }
    
            $facturasCollection = $db->facturas;
            $factura = $facturasCollection->findOne(['id_factura' => (int)$idFactura]);
    
            if ($factura) {
               $productos = isset($factura['productos']) && $factura['productos'] instanceof MongoDB\Model\BSONArray
               ? iterator_to_array($factura['productos']) 
               : [];
               // Mapear los productos
               $productos = array_map(function($producto) {
                return [
                'id_producto' => isset($producto['id_producto']) ? $producto['id_producto'] : null,
                'nombre' => isset($producto['nombre']) ? $producto['nombre'] : null,
                'cantidad' => isset($producto['cantidad']) ? $producto['cantidad'] : 1,  // Valor predeterminado si no existe cantidad
                'precio' => isset($producto['precio']) ? $producto['precio'] : 0.0, // Valor predeterminado si no existe precio
                ];
            }, $productos);
                return [
                    'id_factura' => isset($factura['id_factura']) ? (int)$factura['id_factura'] : null, 
                    'id_usuario' => isset($factura['id_usuario']) ? $factura['id_usuario'] : null,
                    'id_pedido' => isset($factura['id_pedido']) ? 
                    (is_object($factura['id_pedido']) && $factura['id_pedido'] instanceof MongoDB\BSON\ObjectId 
                        ? (string)$factura['id_pedido']  // Convertir ObjectId a string
                        : $factura['id_pedido']) 
                    : null, 
                    'productos' => $productos, 
                    'fecha_emision' => isset($factura['fecha_emision']) && $factura['fecha_emision'] instanceof MongoDB\BSON\UTCDateTime
                        ? $factura['fecha_emision']->toDateTime()->format('Y-m-d H:i:s') // Convertir a cadena legible
                        : $factura['fecha_emision'], 
                    'total' => isset($factura['total']) ? $factura['total'] : null, 
                   'detalle' => isset($factura['detalle']) ? $factura['detalle'] : null 
            ];
            }
            return null; 
        } catch (Exception $e) {
            // Manejo de errores
            error_log("Error al obtener la factura: " . $e->getMessage());
            return null;
        }
    }

    
    // Eliminar una factura
    public function eliminarFactura($idFactura) {
        try {
            $db = $this->conexion->conectar();
            if ($db === null) {
                return false;
            }

            $facturasCollection = $db->facturas;
            $facturasCollection->deleteOne(['id_factura' => (int)$idFactura]); // Eliminar por ID
            return true;
        } catch (\Exception $e) {
            return false;
        }
   
}}

?>
        
 
