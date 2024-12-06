<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/RopayMedia/app/Model/categoriaModel.php";
use MongoDB\BSON\ObjectId;

class CategoriaController {
    private $categoriaModel;

    public function __construct() {
        $this->categoriaModel = new CategoriaModel();
    }

    public function listarCategorias() {
        return $this->categoriaModel->obtenerCategorias();
    }

    public function crearCategoria($nombreCategoria) {
        try {
            if (!empty($nombreCategoria)) {
                $nuevaCategoria = [
                    'nombre_categoria' => $nombreCategoria // Solo el nombre de la categoría
                ];
    
                $resultado = $this->categoriaModel->crearCategoria($nuevaCategoria); // Llamar al modelo
                if ($resultado) {
                    $_SESSION['mensaje'] = "La categoría '$nombreCategoria' ha sido creada exitosamente.";
                    $_SESSION['tipo'] = 'success'; // Tipo de mensaje (puede ser success, error, etc.)
                } else {
                    $_SESSION['mensaje'] = "Hubo un error al crear la categoría '$nombreCategoria'.";
                    $_SESSION['tipo'] = 'error';
                }
            }
        } catch (Exception $e) {
            $_SESSION['mensaje'] = "Error al crear la categoría: " . $e->getMessage();
            $_SESSION['tipo'] = 'error';
        }
    }
    
    public function actualizarCategoria($idCategoria, $nombreCategoria) {
        try {
            if (!empty($idCategoria) && !empty($nombreCategoria)) {
                $resultado = $this->categoriaModel->actualizarCategoria($idCategoria, $nombreCategoria);
                if ($resultado) {
                    $_SESSION['mensaje'] = "La categoría '$nombreCategoria' ha sido actualizada exitosamente.";
                    $_SESSION['tipo'] = 'success';
                } else {
                    $_SESSION['mensaje'] = "Hubo un error al actualizar la categoría '$nombreCategoria'.";
                    $_SESSION['tipo'] = 'error';
                }
            }
        } catch (Exception $e) {
            $_SESSION['mensaje'] = "Error al actualizar la categoría: " . $e->getMessage();
            $_SESSION['tipo'] = 'error';
        }
    }
    
    public function eliminarCategoria($idCategoria) {
        try {
            if (!empty($idCategoria)) {
                $objectId = new MongoDB\BSON\ObjectId($idCategoria); // Convertir a ObjectId
                $categoria = $this->categoriaModel->obtenerCategoriaPorId($objectId); // Buscar por _id
                if ($categoria) {
                    $resultado = $this->categoriaModel->eliminarCategoria($objectId);
                    if ($resultado) {
                        $_SESSION['mensaje'] = "La categoría '{$categoria['nombre_categoria']}' ha sido eliminada exitosamente.";
                        $_SESSION['tipo'] = 'success';
                    } else {
                        $_SESSION['mensaje'] = "Hubo un error al eliminar la categoría.";
                        $_SESSION['tipo'] = 'error';
                    }
                } else {
                    $_SESSION['mensaje'] = "La categoría no existe.";
                    $_SESSION['tipo'] = 'error';
                }
            } else {
                $_SESSION['mensaje'] = "ID de categoría no proporcionado.";
                $_SESSION['tipo'] = 'error';
            }
        } catch (Exception $e) {
            $_SESSION['mensaje'] = "Error al eliminar la categoría: " . $e->getMessage();
            $_SESSION['tipo'] = 'error';
        }
    }
        

    public function manejarAcciones() {
        $nombreCategoria = isset($_POST['nombre_categoria']) ? $_POST['nombre_categoria'] : '';
        $accion = isset($_POST['accion']) ? $_POST['accion'] : '';
        $idCategoria = isset($_POST['id_categoria']) ? $_POST['id_categoria'] : '';
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                if ($accion === 'Crear') {
                    $this->crearCategoria($nombreCategoria);
                } elseif ($accion === 'Actualizar') {
                    $this->actualizarCategoria($idCategoria, $nombreCategoria);
                } elseif ($accion === 'Eliminar') {
                    // Eliminar la categoría
                    $this->eliminarCategoria($idCategoria);
                }
    
                // Redirigir a categoriasCrud.php después de la acción
                header("Location: categoriasCrud.php");
                exit();
            } catch (Exception $e) {
                $_SESSION['mensaje'] = "Error: " . $e->getMessage();
                $_SESSION['tipo'] = 'error';
                header("Location: categoriasCrud.php");
                exit();
            }
        }
    }   
}
?>