<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/RopayMedia/app/Model/baseDatosModel.php";
use MongoDB\BSON\ObjectId;


class ProductoModel {
    private $conexion;

    public function __construct() {
        $this->conexion = new Conexion(); // Instanciar la conexión a la base de datos
    }

    // Obtener todos los productos
    public function obtenerProductos() {
        try {
            $db = $this->conexion->conectar();
            if ($db === null) {
                return [];
            }

            $productosCollection = $db->productos; // Seleccionar la colección de productos
            $productos = $productosCollection->find(); // Obtener todos los productos

            // Convertir el cursor de MongoDB a un array asociativo
            $listaProductos = [];
            foreach ($productos as $producto) {
                $listaProductos[] = [
                    'id_producto' => $producto['id_producto'], // Convertir a entero
                    'nombre_producto' => $producto['nombre_producto'],
                    'descripcion' => $producto['descripcion'],
                    'precio' => $producto['precio'], // Convertir a flotante
                    'stock' => $producto['stock'], // Convertir a entero
                    'id_categoria' => $producto['id_categoria'], // Convertir a entero
                    'ruta_imagen' => $producto['ruta_imagen']
                ];
            }

            return $listaProductos;
        } catch (\Exception $e) {
            return [];
        }
    }

    // Crear un nuevo producto
    public function crearProducto($producto) {
        try {
            $db = $this->conexion->conectar();
            if ($db === null) {
                return false;
            }
    
            // Convertir id_categoria a ObjectId antes de guardar
            if (isset($producto['id_categoria'])) {
                $producto['id_categoria'] = new ObjectId($producto['id_categoria']);
            }
    
            $productosCollection = $db->productos;
            $productosCollection->insertOne($producto); // Insertar el producto en la colección
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    // Actualizar un producto existente
    public function actualizarProducto($idProducto, $productoActualizado) {
        try {
            $db = $this->conexion->conectar();
            if ($db === null) {
                return false;
            }

            $productosCollection = $db->productos;

            // Usar ObjectId para el filtro
            $productosCollection->updateOne(
                ['id_producto' => (int)$idProducto], // No convertir a int, mantén el ObjectId
                ['$set' => $productoActualizado] // Datos a actualizar
            );
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    // Obtener un producto por ID
    public function obtenerProductoPorId($idProducto) {
        try {
            $db = $this->conexion->conectar();
            if ($db === null) {
                return null;
            }

            $productosCollection = $db->productos;
            $producto = $productosCollection->findOne(['id_producto' => (int)$idProducto]); // Buscar por ObjectId

            if ($producto) {
                return [
                    'id_producto' => $producto['id_producto'],
                    'nombre_producto' => $producto['nombre_producto'],
                    'descripcion' => $producto['descripcion'],
                    'precio' => $producto['precio'],
                    'stock' => $producto['stock'],
                    'id_categoria' => $producto['id_categoria'],
                    'ruta_imagen' => $producto['ruta_imagen']
                ];
            }

            return null; // Si no se encuentra el producto
        } catch (\Exception $e) {
            return null; // Manejo de errores
        }
    }


    // Eliminar un producto
    public function eliminarProducto($idProducto) {
        try {
            $db = $this->conexion->conectar();
            if ($db === null) {
                return false;
            }
    
            $productosCollection = $db->productos;
    
            // Usar ObjectId para eliminar
            $productosCollection->deleteOne(['id_producto' => (int)$idProducto]); // Eliminar por ObjectId
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function obtenerProductosPorCategoria($idCategoria) {
        try {
            $db = $this->conexion->conectar();
            if ($db === null) {
                return [];
            }
    
            $productosCollection = $db->productos;
            $productos = $productosCollection->find(['id_categoria' => new ObjectId($idCategoria)]); // Filtrar por categoría
    
            // Convertir el cursor de MongoDB a un array asociativo
            $listaProductos = [];
            foreach ($productos as $producto) {
                $listaProductos[] = [
                    'id_producto' => $producto['id_producto'],
                    'nombre_producto' => $producto['nombre_producto'],
                    'descripcion' => $producto['descripcion'],
                    'precio' => $producto['precio'],
                    'stock' => $producto['stock'],
                    'id_categoria' => (string) $producto['id_categoria'],
                    'ruta_imagen' => $producto['ruta_imagen']
                ];
            }
    
            return $listaProductos;
        } catch (\Exception $e) {
            return [];
        }
    }
}
?>
