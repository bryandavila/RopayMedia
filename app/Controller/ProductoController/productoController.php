<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/RopayMedia/app/Model/productoModel.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/RopayMedia/app/Model/categoriaModel.php";
use MongoDB\BSON\ObjectId;

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

    public function listarProductosPorCategoria($idCategoria) {
        try {
            $objectId = new ObjectId($idCategoria); // Convertir el ID a ObjectId
            return $this->productoModel->obtenerProductosPorCategoria($objectId);
        } catch (Exception $e) {
            $_SESSION['mensaje'] = "Error: ID de categoría inválido.";
            return [];
        }
    }

    public function buscarProductoPorId($idProducto) {
        try {
            return $this->productoModel->obtenerProductoPorId($idProducto);
        } catch (Exception $e) {
            $_SESSION['mensaje'] = "Error al buscar el producto: " . $e->getMessage();
            return null;
        }
    }
    
    

    public function crearProducto($nombre_producto, $descripcion, $precio, $stock, $idCategoria, $rutaImagen) {
        try {
            $producto = [
                'id_producto' => time(),
                'nombre_producto' => $nombre_producto,
                'descripcion' => $descripcion,
                'precio' => (float)$precio,
                'stock' => (int)$stock,
                'id_categoria' => new ObjectId($idCategoria), // Asegurarse de que el ID sea un ObjectId
                'ruta_imagen' => $rutaImagen
            ];

            if ($this->productoModel->crearProducto($producto)) {
                $_SESSION['mensaje'] = "Producto agregado exitosamente.";
            } else {
                $_SESSION['mensaje'] = "Error al agregar el producto.";
            }
        } catch (Exception $e) {
            $_SESSION['mensaje'] = "Error: " . $e->getMessage();
        }
    }

    public function actualizarProducto($idProducto, $nombre, $descripcion, $precio, $stock, $idCategoria, $rutaImagen) {
        try {
            $productoActualizado = [
                'nombre_producto' => $nombre,
                'descripcion' => $descripcion,
                'precio' => (float)$precio,
                'stock' => (int)$stock,
                'id_categoria' => new ObjectId($idCategoria),
                'ruta_imagen' => $rutaImagen
            ];
    
            if ($this->productoModel->actualizarProducto($idProducto, $productoActualizado)) {
                $_SESSION['mensaje'] = "Producto actualizado exitosamente.";
            } else {
                $_SESSION['mensaje'] = "Error al actualizar el producto.";
            }
        } catch (Exception $e) {
            $_SESSION['mensaje'] = "Error: " . $e->getMessage();
        }
    }    

    public function eliminarProducto($idProducto) {
        try {
            if (empty($idProducto)) {
                $_SESSION['mensaje'] = "Error: No se recibió el ID del producto.";
                return;
            }
    
            // Pasar ObjectId a eliminar el producto
            if ($this->productoModel->eliminarProducto($idProducto)) {
                $_SESSION['mensaje'] = "Producto eliminado exitosamente.";
            } else {
                $_SESSION['mensaje'] = "Error al eliminar el producto.";
            }
        } catch (Exception $e) {
            $_SESSION['mensaje'] = "Error: " . $e->getMessage();
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
?>
