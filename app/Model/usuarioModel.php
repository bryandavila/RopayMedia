<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . "/RopayMedia/vendor/autoload.php";
    require_once $_SERVER['DOCUMENT_ROOT'] . "/RopayMedia/app/Model/baseDatosModel.php";

    class usuarioModel {
        private $conexion;

        public function __construct() {
            $this->conexion = new Conexion();  
        }

        public function validarCorreo($correo){
            try {
                $db = $this->conexion->conectar(); 
                if ($db === null) {
                    return "Error al conectar a la base de datos.";
                }
                $usuariosCollection = $db->usuarios; 
                $usuarioExistente = $usuariosCollection->findOne(['correo' => $correo]);
                return $usuarioExistente;
            } catch (Exception $e) {
                return "Error: " . $e->getMessage();
            }
        }

        public function registrarUsuario($nombre, $apellido, $telefono, $correo, $contrasena, $id_rol) {
            try {
                $db = $this->conexion->conectar(); 
                if ($db === null) {
                    return "Error al conectar a la base de datos.";
                }
                $usuariosCollection = $db->usuarios; 
                $password_hash = password_hash($contrasena, PASSWORD_DEFAULT); 
                $nuevoUsuario = [
                    'id_usuario' => time(),
                    'nombre' => $nombre,
                    'apellido' => $apellido,
                    'telefono' => $telefono,
                    'correo' => $correo,
                    'contrasena' => $password_hash,
                    'id_rol' => $id_rol
                ];
                $resultado = $usuariosCollection->insertOne($nuevoUsuario);
                return $resultado;
            } catch (Exception $e) {
                return "Error: " . $e->getMessage();
            }
        }


        public function login($correo, $contrasena){
            try {
                $db = $this->conexion->conectar(); 
                if ($db === null) {
                    return "Error al conectar a la base de datos.";
                }
        
                $usuariosCollection = $db->usuarios; 
                $usuarioExistente = $usuariosCollection->findOne(['correo' => $correo]);
                if (!$usuarioExistente || !password_verify($contrasena, $usuarioExistente['contrasena'])) {
                    return false;
                }
                return $usuarioExistente;
            } catch (Exception $e) {
                return false;
            }
        }
    }
?>
