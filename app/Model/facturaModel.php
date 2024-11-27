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
                                'id_factura' => isset($factura['id_factura']) ? (int)$factura['id_factura'] : null,
                                'id_pedido' => isset($factura['id_pedido']) ? (int)$factura['id_pedido'] : null, // Verificar existencia
                                'id_cliente' => isset($factura['id_cliente']) ? (int)$factura['id_cliente'] : null, // Verificar existencia
                                'productos' => isset($factura['productos']) ? $factura['productos'] : [], // Solo IDs de productos
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
            
            if (isset($factura['productos']) && is_array($factura['productos'])) {
                $factura['productos'] = array_map(function($producto) {
                    return (int)$producto; // Convertir los IDs a enteros
                }, $factura['productos']);
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
            $factura = $facturasCollection->findOne(['id_factura' => (int)$idFactura]); // Buscar por ID
    
            if ($factura) {
                return [
                    'id_factura' => isset($factura['id_factura']) ? (int)$factura['id_factura'] : null, // Verificar existencia
                    'id_cliente' => isset($factura['id_cliente']) ? $factura['id_cliente'] : null,
                    'id_pedido' => isset($factura['id_pedido']) ? $factura['id_pedido'] : null, // Verificar existencia
                    'productos' => isset($factura['productos']) ? $factura['productos'] : [], // Verificar existencia
                    'fecha_emision' => isset($factura['fecha_emision']) && $factura['fecha_emision'] instanceof MongoDB\BSON\UTCDateTime
                        ? $factura['fecha_emision']->toDateTime()->format('Y-m-d H:i:s') // Convertir a cadena legible
                        : $factura['fecha_emision'], // Dejar tal cual si no es UTCDateTime
                    'total' => isset($factura['total']) ? $factura['total'] : null, // Verificar existencia
                   'detalle' => isset($factura['detalle']) ? $factura['detalle'] : null // Verificar existencia
            ];
            }
            return null; // Si no se encuentra la factura
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
        
 