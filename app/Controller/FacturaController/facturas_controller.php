<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/RopayMedia/app/Model/facturaModel.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/RopayMedia/app/Model/clienteModel.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/RopayMedia/app/Model/pedidoModel.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/RopayMedia/app/Controller/ProductoController/productoController.php";

class FacturaController {
    private $facturaModel;
  



    public function __construct() {
        $this->facturaModel = new FacturaModel();
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function listarFacturas() {
        return $this->facturaModel->obtenerFacturas();
        
    }

   
    public function crearFactura($idCliente, $idPedido, $productos, $total,$detalle) {
        $fechaEmision = date("Y-m-d H:i:s");
        error_log("Datos recibidos en 'productos': " . print_r($productos, true));

        // Validar el arreglo
        if (isset($productos) && is_array($productos)) {
            $productos = array_filter($productos, function($producto) {
                return !is_null($producto) && is_numeric($producto);
            });
        } else {
            $productos = []; 
        }
        
        // Crear el arreglo de la factura
        $factura = [
            'id_factura' => time(),
            'id_cliente' => (int)$idCliente,
            'id_pedido' => (int)$idPedido,
            'productos' => $productos,
            'total' => (float)$total,
            'fecha_emision' => $fechaEmision,
            'detalle' => $detalle,
        ];
    
        error_log("Factura procesada: " . print_r($factura, true));
    
        // Insertar la factura en la base de datos
        if ($this->facturaModel->crearFactura($factura)) {
            $_SESSION['mensaje'] = "Factura creada exitosamente.";
        } else {
            $_SESSION['mensaje'] = "Error al crear la factura.";
        }
    }
    
    public function actualizarFactura($idFactura, $idCliente, $idPedido, $productos, $total, $fechaEmision,$detalle) {
        $facturaActualizada = [
            'id_cliente' => (int)$idCliente,
            'id_pedido' => (int)$idPedido,
            'id_producto' => (int)$productos,
            'total' => (float)$total,
           'fecha_emision' => $fechaEmision, // Almacenar como una cadena
           'detalle' => $detalle 
    ];
    
        if ($this->facturaModel->actualizarFactura($idFactura, $facturaActualizada)) {
            $_SESSION['mensaje'] = "Factura actualizada exitosamente.";
        } else {
            $_SESSION['mensaje'] = "Error al actualizar la factura.";
        }
    }

    public function eliminarFactura($idFactura) {
        if (empty($idFactura)) {
            $_SESSION['mensaje'] = "Error: No se recibió el ID de la factura.";
            return;
        }

        if ($this->facturaModel->eliminarFactura($idFactura)) {
            $_SESSION['mensaje'] = "Factura eliminada exitosamente.";
        } else {
            $_SESSION['mensaje'] = "Error al eliminar la factura.";
        }
    }
    public function obtenerProductos() {
        try {
            $productosController = new productoController(); 
            $productos = $productosController->listarProductos(); 
            
            return $productos;  
        } catch (\Exception $e) {
            return []; 
        }
    }

    public function manejarAcciones() {
        $accion = isset($_POST['accion']) ? $_POST['accion'] : '';
        $idFactura = isset($_POST['id_factura']) ? $_POST['id_factura'] : null;
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                if ($accion === 'Crear') {
                    $this->crearFactura(
                        $_POST['id_cliente'], 
                        $_POST['id_pedido'], 
                        $_POST['productos'], 
                        $_POST['total'],
                        $_POST['detalle']
                    );
                } elseif ($accion === 'Actualizar') {
                    $this->actualizarFactura(
                        $idFactura, 
                        $_POST['id_cliente'], 
                        $_POST['id_pedido'], 
                        $_POST['productos'], 
                        $_POST['total'], 
                        $_POST['fecha_emision'],
                        $_POST['detalle']
                    );
                } elseif ($accion === 'Eliminar') {
                    $this->eliminarFactura($idFactura);
                }
    
                // Redirigir tras completar la acción
                header("Location: listaFacturas.php");
                exit();
            } catch (Exception $e) {
                // Manejo de excepciones
                $_SESSION['mensaje'] = "Error: " . $e->getMessage();
                header("Location: listaFacturas.php");
                exit();
            }
        }
    }}
    
?>