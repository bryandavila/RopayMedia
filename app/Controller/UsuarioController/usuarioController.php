<?php
    include_once $_SERVER['DOCUMENT_ROOT'] . "/RopayMedia/app/Model/usuarioModel.php";

    class usuarioController
    {
       
      
        public static function registrarUsuario()
        {
           
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $nombre = $_POST['nombre'];
                $apellido = $_POST['apellido'];
                $telefono = $_POST['telefono'];
                $correo = $_POST['correo'];
                $contrasena = $_POST['contrasena'];
               
                if (isset($_POST['rol']) && $_POST['rol'] == '1') {
                    $rol = 1; 
                } else {
                    $rol = 2; 
                }               

                $usuario = new usuarioModel();              
                $resultado = $usuario->validarCorreo($correo);

                if ($resultado) {
                    echo json_encode(['success' => false, 'message' => 'Error: El correo ya se encuentra registrado en la aplicación']);
                
                } else {
                    
                    $registrar = $usuario->registrarUsuario($nombre, $apellido, $telefono, $correo, $contrasena, $rol);
                    if ($registrar) {
                        echo json_encode(['success' => true, 'message' => 'Cuenta registrada correctamente.']);
                    } else {
                        echo json_encode(['success' => false, 'message' => 'Error al registrar la cuenta.']);
                    }
                }
            }
        }

        public static function actualizarUsuario()
        {            
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {     
                $id=$_POST['id'];          
                $nombre = $_POST['nombre'];
                $apellido = $_POST['apellido'];
                $telefono = $_POST['telefono'];
                $correo = $_POST['correo'];
                $contrasena = $_POST['contrasena'];
                if (isset($_POST['rol']) && $_POST['rol'] == '1') {
                    $rol = 1; 
                } else {
                    $rol = 2; 
                }              

                $usuario = new usuarioModel();              
                
                    $registrar = $usuario->actualizarUsuario($id,$nombre, $apellido, $telefono, $correo, $contrasena, $rol);
                    if ($registrar) {
                        echo json_encode(['success' => true, 'message' => 'Cuenta actualizada correctamente.']);
                    } else {
                        echo json_encode(['success' => false, 'message' => 'Error al actualizar la cuenta.']);
                    }
                
            }
        }

        public static function eliminarUsuario() {
            if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
                $id = $_POST['id'];
                
                $usuario = new usuarioModel(); 
                $resultado = $usuario->eliminarUsuario($id);
        
                if ($resultado) {
                    echo json_encode(['success' => true, 'message' => 'Usuario eliminado correctamente.']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Error al eliminar el usuario.']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'ID de usuario no proporcionado.']);
            }
        }
    }

    if (isset($_GET['action']) && $_GET['action'] === 'registrarUsuario') {
        usuarioController::registrarUsuario();
    }

    if (isset($_GET['action']) && $_GET['action'] === 'actualizarUsuario') {
        usuarioController::actualizarUsuario();
    }
    if (isset($_GET['action']) && $_GET['action'] === 'eliminarUsuario') {
        usuarioController::eliminarUsuario();
    }   

?>