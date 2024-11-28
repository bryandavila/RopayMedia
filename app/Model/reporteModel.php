<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . "/RopayMedia/vendor/autoload.php";
    require_once $_SERVER['DOCUMENT_ROOT'] . "/RopayMedia/app/Model/baseDatosModel.php";

    class reporteModel {
        private $conexion;

        public function __construct() {
            $this->conexion = new Conexion();  //Crear la conexión
        }

        public function obtenerComprasUsuario($id_usuario) {
            try {
                $db = $this->conexion->conectar();
                if ($db === null) {
                    return [];
                }
                
                $facturasCollection = $db->facturas; 
                $facturas = $facturasCollection->find(['id_cliente'=>$id_usuario]); 
        
                $listaFacturas = [];
                foreach ($facturas as $factura) {
                    $fechaEmision = null;
                    if (isset($factura['fecha_emision']) && !empty($factura['fecha_emision'])) {
                        $fechaEmision = $factura['fecha_emision']; // Mantén el formato como string
                    } else {
                        $fechaEmision = null; 
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

    }
?>
