<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/RopayMedia/app/Model/facturaModel.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/RopayMedia/app/Model/clienteModel.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/RopayMedia/app/Model/pedidoModel.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/RopayMedia/app/Controller/ProductoController/productoController.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/RopayMedia/app/Model/carrito_comprasModel.php";

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

    public function crearFactura($idUsuario, $idPedido, $productos, $total,$detalle) {
        $idPedido = $this->crearPedido($idUsuario, $productos, $total, $detalle);
        $fechaEmision = date("Y-m-d H:i:s");
        $productosFactura = [];
        foreach ($productos as $producto) {
            $productosFactura[] = [
                'id_producto' => $producto['id_producto'],
                'nombre' => $producto['nombre'],
                'cantidad' => (int)$producto['cantidad'],
                'precio' => (float)$producto['precio'],
            ];
        }

        $factura = [
            'id_factura' => time(),
            'id_usuario' => (int)$idUsuario,
            'id_pedido' => (int)$idPedido,
            'productos' => $productosFactura, 
            'total' => (float)$total,
            'fecha_emision' => $fechaEmision,
            'detalle' => $detalle,
        ];

        if ($this->facturaModel->crearFactura($factura)) {
            $this->actualizarStockDeProductos($productosFactura);
            
            $_SESSION['mensaje'] = "Factura creada exitosamente.";
            return true;
        } else {
            $_SESSION['mensaje'] = "Error al crear la factura.";
            return false;
        }
    }

    public function actualizarFactura($idFactura, $idUsuario, $idPedido, $productos, $total, $fechaEmision,$detalle) {
        $facturaActualizada = [
            'id_usuario' => (int)$idUsuario,
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
    
    public function crearPedido($idUsuario, $productos, $total, $detalle) {
        $db = (new Conexion())->conectar(); 
        if ($db === null) {
            $_SESSION['mensaje'] = "Error al conectar a la base de datos.";
            return;
        }
        $pedidosCollection = $db->pedidos;
        $id_pedido = time();  
        $nuevoPedido = [
            'id_pedido' => $id_pedido,
            'fecha' => (new DateTime())->format('Y-m-d H:i:s'),
            'id_cliente' => $idUsuario,
            'productos' => $productos,
            'total' => $total,
            'estado' => 'Entregado',
            'detalle' => $detalle,
        ];
    
        // Insertar el pedido en la colección de pedidos
        $resultadoPedido = $pedidosCollection->insertOne($nuevoPedido);
        if ($resultadoPedido->getInsertedCount() > 0) {
            $_SESSION['mensaje'] = "Pedido creado y agregado exitosamente.";
            return $id_pedido;  // Retorna el id_pedido para usarlo en la factura
        } else {
            $_SESSION['mensaje'] = "Error al crear el pedido.";
            return null;  // Retorna null si hubo un error
        } 
    }
    
    private function actualizarStockDeProductos($productos) {
        $db = (new Conexion())->conectar();            
        if ($db === null) {
            $_SESSION['mensaje'] = "Error al conectar a la base de datos.";
            return;
        }
        $productosCollection = $db->productos;
        foreach ($productos as $producto) {
            $productoId = (int)$producto['id_producto'];
            $cantidadVendida = (int)$producto['cantidad'];
            $productoData = $productosCollection->findOne(['id_producto' => $productoId]);
            if (!$productoData) {
                $_SESSION['mensaje'] = "Producto con ID $productoId no encontrado.";
                return;
            }
            if ($productoData['stock'] < $cantidadVendida) {
                $_SESSION['mensaje'] = "No hay suficiente stock para el producto " . $producto['nombre'];
                return;  
            }

            $productosCollection->updateOne(
                ['id_producto' => $productoId],
                ['$inc' => ['stock' => -$cantidadVendida]] 
            );

            error_log("Stock actualizado para producto ID: $productoId, nuevo stock: " . ($productoData['stock'] - $cantidadVendida));
        }
    }

    public function manejarAcciones() {
        $accion = isset($_POST['accion']) ? $_POST['accion'] : '';
        $idFactura = isset($_POST['id_factura']) ? $_POST['id_factura'] : null;
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $productos = isset($_POST['productos']) ? json_decode($_POST['productos'], true) : [];
                if ($accion === 'Crear') {
                    $this->crearFactura(
                        $_POST['id_usuario'], 
                        $_POST['id_pedido'], 
                        $productos, 
                        $_POST['total'],
                        $_POST['detalle']
                    );
                } elseif ($accion === 'Actualizar') {
                    $this->actualizarFactura(
                        $idFactura, 
                        $_POST['id_usuario'], 
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
    }
}
    
    
?>