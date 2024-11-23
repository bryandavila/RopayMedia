<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/RopayMedia/app/Model/facturaModel.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/RopayMedia/app/Model/clienteModel.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/RopayMedia/app/Model/pedidoModel.php";


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

    public function crearFactura($idCliente, $idProducto,$idPedido, $total, $fechaFactura) {
        $factura = [
            'id_factura' => time(),
            'id_cliente' => (int)$idCliente,
            'id_pedido' => (int)$idPedido,
            'productos' => (int)$idProducto, 
            'total' => (float)$total,
            'fecha_factura' => $fechaFactura

        ];

        if ($this->facturaModel->crearFactura($factura)) {
            $_SESSION['mensaje'] = "Factura creada exitosamente.";
        } else {
            $_SESSION['mensaje'] = "Error al crear la factura.";
        }
    }

    public function actualizarFactura($idFactura, $idPedido, $productos, $total, $fechaFactura) {
        $facturaActualizada = [
            'id_pedido' => (int)$idPedido, // Agregado id_pedido
            'productos' => $productos,
            'total' => (float)$total,
            'fecha_factura' => $fechaFactura
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

    public function manejarAcciones() {
        $accion = isset($_POST['accion']) ? $_POST['accion'] : '';
        $idFactura = isset($_POST['id_factura']) ? $_POST['id_factura'] : null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($accion === 'Crear') {
                $this->crearFactura($_POST['id_cliente'], $_POST['id_pedido'], $_POST['productos'], $_POST['total'], $_POST['fecha_factura']);
            } elseif ($accion === 'Actualizar') {
                $this->actualizarFactura($idFactura, $_POST['id_pedido'], $_POST['productos'], $_POST['total'], $_POST['fecha_factura']);
            } elseif ($accion === 'Eliminar') {
                $this->eliminarFactura($idFactura);
            }

            header("Location: listaFacturas.php");
            exit();
        }
    }
}
?>