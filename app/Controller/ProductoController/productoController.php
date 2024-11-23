<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/RopayMedia/app/Model/productoModel.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/RopayMedia/app/Model/categoriaModel.php";

class ProductoController {
    private $productoModel;

    public function __construct() {
        $this->productoModel = new ProductoModel();
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function listarProductos() {
        return $this->productoModel->obtenerProductos();
    }

    public function crearProducto($nombre_producto, $descripcion, $precio, $stock, $idCategoria, $rutaImagen) {
        $producto = [
            'id_producto' => time(),
            'nombre_producto' => $nombre_producto,
            'descripcion' => $descripcion,
            'precio' => (float)$precio,
            'stock' => (int)$stock,
            'id_categoria' => (int)$idCategoria,
            'ruta_imagen' => $rutaImagen
        ];
    
        if ($this->productoModel->crearProducto($producto)) {
            $_SESSION['mensaje'] = "Producto agregado exitosamente.";
        } else {
            $_SESSION['mensaje'] = "Error al agregar el producto.";
        }
    }

    public function actualizarProducto($idProducto, $nombre, $descripcion, $precio, $stock, $idCategoria, $rutaImagen) {
        $productoActualizado = [
            'nombre_producto' => $nombre,
            'descripcion' => $descripcion,
            'precio' => (float)$precio,
            'stock' => (int)$stock,
            'id_categoria' => (int)$idCategoria,
            'ruta_imagen' => $rutaImagen
        ];

        if ($this->productoModel->actualizarProducto($idProducto, $productoActualizado)) {
            $_SESSION['mensaje'] = "Producto actualizado exitosamente.";
        } else {
            $_SESSION['mensaje'] = "Error al actualizar el producto.";
        }
    }

    public function eliminarProducto($idProducto) {
        if (empty($idProducto)) {
            $_SESSION['mensaje'] = "Error: No se recibió el ID del producto.";
            return;
        }

        if ($this->productoModel->eliminarProducto($idProducto)) {
            $_SESSION['mensaje'] = "Producto eliminado exitosamente.";
        } else {
            $_SESSION['mensaje'] = "Error al eliminar el producto.";
        }
    }

    public function manejarAcciones() {
        $accion = isset($_POST['accion']) ? $_POST['accion'] : '';
        $idProducto = isset($_POST['id_producto']) ? $_POST['id_producto'] : null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($accion === 'Crear') {
                $this->crearProducto($_POST['nombre_producto'], $_POST['descripcion'], $_POST['precio'], $_POST['stock'], $_POST['id_categoria'], $_POST['ruta_imagen']);
            } elseif ($accion === 'Actualizar') {
                $this->actualizarProducto($idProducto, $_POST['nombre_producto'], $_POST['descripcion'], $_POST['precio'], $_POST['stock'], $_POST['id_categoria'], $_POST['ruta_imagen']);
            } elseif ($accion === 'Eliminar') {
                $this->eliminarProducto($idProducto);
            }

            header("Location: listaProductos.php");
            exit();
        }
    }
}
