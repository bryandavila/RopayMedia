<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/RopayMedia/app/Model/baseDatosModel.php";

class CategoriaModel {
    private $conexion;

    public function __construct() {
        $this->conexion = new Conexion();  // Instanciar la conexión a la base de datos
    }

    // Método para obtener todas las categorías
    public function obtenerCategorias() {
        try {
            $db = $this->conexion->conectar();
            if ($db === null) {
                return [];
            }

            $categoriasCollection = $db->categorias;  // Seleccionar la colección de categorías
            $categorias = $categoriasCollection->find();  // Obtener todas las categorías

            // Convertir el cursor de MongoDB a un array asociativo
            $listaCategorias = [];
            foreach ($categorias as $categoria) {
                $listaCategorias[] = [
                    'id_categoria' => (string)$categoria['_id'], // Convertir el ID de MongoDB a string
                    'nombre_categoria' => $categoria['nombre_categoria']
                ];
            }

            return $listaCategorias;
        } catch (\Exception $e) {
            return [];
        }
    }

     // Método para obtener una categoría por id_categoria
     public function obtenerCategoriaPorId($idCategoria) {
        try {
            $db = $this->conexion->conectar();
            if ($db === null) {
                return null;
            }
    
            $categoriasCollection = $db->categorias; // Seleccionar la colección de categorías
            $categoria = $categoriasCollection->findOne(['_id' => $idCategoria]); // Buscar por _id
    
            if ($categoria) {
                return [
                    'id_categoria' => (string)$categoria['_id'], // Convertir a string para la respuesta
                    'nombre_categoria' => $categoria['nombre_categoria']
                ];
            }
    
            return null; // Retorna null si no encuentra la categoría
        } catch (\Exception $e) {
            return null; // Manejo de errores
        }
    }

    // Método para crear una nueva categoría
    public function crearCategoria($categoria) {
        try {
            $db = $this->conexion->conectar();
            if ($db === null) {
                return false;
            }
    
            $categoriasCollection = $db->categorias;  // Seleccionar la colección de categorías
            $resultado = $categoriasCollection->insertOne($categoria); // Insertar la nueva categoría
            return $resultado->getInsertedCount() > 0;
        } catch (\Exception $e) {
            return false;
        }
    }

    // Método para actualizar una categoría existente
    public function actualizarCategoria($idCategoria, $nombreCategoria) {
        try {
            $db = $this->conexion->conectar();
            if ($db === null) {
                return false;
            }

            $categoriasCollection = $db->categorias;  // Seleccionar la colección de categorías
            $resultado = $categoriasCollection->updateOne(
                ['_id' => new MongoDB\BSON\ObjectId($idCategoria)], // Filtro por ID
                ['$set' => ['nombre_categoria' => $nombreCategoria]] // Valores a actualizar
            );

            return $resultado->getModifiedCount() > 0;
        } catch (\Exception $e) {
            return false;
        }
    }

    // Método para eliminar una categoría
    public function eliminarCategoria($idCategoria) {
        try {
            $db = $this->conexion->conectar();
            if ($db === null) {
                error_log("Error: conexión a la base de datos fallida.");
                return false;
            }
    
            $categoriasCollection = $db->categorias;
            $resultado = $categoriasCollection->deleteOne(['_id' => $idCategoria]);
    
            // Log para verificar cuántos documentos se eliminaron
            error_log("Documentos eliminados: " . $resultado->getDeletedCount());
    
            return $resultado->getDeletedCount() > 0;
        } catch (\Exception $e) {
            error_log("Error al eliminar la categoría: " . $e->getMessage());
            return false;
        }
    }
        
}
?>

