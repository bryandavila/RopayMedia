<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . "/RopayMedia/vendor/autoload.php";
    require_once $_SERVER['DOCUMENT_ROOT'] . "/RopayMedia/app/Model/baseDatosModel.php";
    require_once $_SERVER['DOCUMENT_ROOT'] . "/RopayMedia/app/Model/productoModel.php";


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

            $facturasCollection = $db->facturas; // Seleccionar la colección de facturas
            $facturas = $facturasCollection->find(); // Obtener todas las facturas

            // Convertir el cursor de MongoDB a un array asociativo
            $listaFacturas = [];
            foreach ($facturas as $factura) {
                $listaFacturas[] = [
                    
                    'id_factura' => $factura['id_factura'], // Convertir a entero
                    'id_cliente' => $factura['id_cliente'], // Convertir a entero
                    'id_producto' => $factura['id_producto'], // Convertir a entero
                    'fecha_emision' => $factura['fecha_emision'],
                    'total' => $factura['total'], // Convertir a flotante
                    'detalles' => $factura['detalles'] // Asumimos que 'detalles' es un array o documento embebido
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
            $facturasCollection->insertOne($factura); // Insertar la factura en la colección
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    // Actualizar una factura existente
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
                    'id_factura' => $factura['id_factura'],
                    'id_cliente' => $factura['id_cliente'],
                    'id_producto' => $factura['id_producto'],
                    'fecha_emision' => $factura['fecha_emision'],
                    'total' => $factura['total'],
                    'detalles' => $factura['detalles']
                ];
            }

            return null; // Si no se encuentra la factura
        } catch (\Exception $e) {
            return null; // Manejo de errores
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
    }
}
?>
        
    

