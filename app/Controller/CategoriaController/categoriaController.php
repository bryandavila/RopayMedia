<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/RopayMedia/app/Model/categoriaModel.php";

class CategoriaController {
    private $categoriaModel;

    public function __construct() {
        $this->categoriaModel = new CategoriaModel(); // Instanciar el modelo de categorías
    }

    // Método para obtener las categorías y enviarlas a la vista
    public function listarCategorias() {
        $categorias = $this->categoriaModel->obtenerCategorias(); // Obtener categorías desde el modelo
        return $categorias; // Retornar las categorías a la vista
    }

    // Método para crear una nueva categoría
    public function crearCategoria($nombreCategoria) {
        if (!empty($nombreCategoria)) {
            $this->categoriaModel->crearCategoria($nombreCategoria); // Llamar al modelo para crear la categoría
        }
    }

    // Método para actualizar una categoría existente
    public function actualizarCategoria($idCategoria, $nombreCategoria) {
        if (!empty($idCategoria) && !empty($nombreCategoria)) {
            $this->categoriaModel->actualizarCategoria($idCategoria, $nombreCategoria); // Llamar al modelo para actualizar la categoría
        }
    }

    // Método para eliminar una categoría
    public function eliminarCategoria($idCategoria) {
        if (!empty($idCategoria)) {
            $this->categoriaModel->eliminarCategoria($idCategoria); // Llamar al modelo para eliminar la categoría
        }
    }

    // Manejar acciones basadas en datos enviados desde el formulario
    public function manejarAcciones() {
        $nombreCategoria = isset($_POST['nombre_categoria']) ? $_POST['nombre_categoria'] : '';
        $accion = isset($_POST['accion']) ? $_POST['accion'] : '';
        $idCategoria = isset($_POST['id_categoria']) ? $_POST['id_categoria'] : '';
    
        $mensaje = ""; // Inicializar el mensaje vacío
        $tipo = "success"; // Tipo por defecto de alerta
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                if ($accion === 'Crear') {
                    $this->crearCategoria($nombreCategoria);
                    $mensaje = "¡Categoría creada con éxito!";
                } elseif ($accion === 'Actualizar') {
                    $this->actualizarCategoria($idCategoria, $nombreCategoria);
                    $mensaje = "¡Categoría actualizada con éxito!";
                } elseif ($accion === 'Eliminar') {
                    $this->eliminarCategoria($idCategoria);
                    $mensaje = "¡Categoría eliminada con éxito!";
                }
            } catch (\Exception $e) {
                $mensaje = "Error: " . $e->getMessage();
                $tipo = "error"; // Cambiar tipo a error si ocurre una excepción
            }
    
            // Guardar el mensaje y el tipo en la sesión para la vista
            session_start();
            $_SESSION['mensaje'] = $mensaje;
            $_SESSION['tipo'] = $tipo;
    
            header("Location: categoriasCrud.php"); // Refrescar la página
            exit();
        }
    }
    
}
?>

