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
                    '_id' => time(),
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
        public function listarUsuarios() {
            try {
                $db = $this->conexion->conectar(); 
                if ($db === null) {
                    return "Error al conectar a la base de datos.";
                }
        
                $usuariosCollection = $db->usuarios;
                $rolesCollection = $db->roles; 
        
                $usuarios = $usuariosCollection->find();
                $usuariosArray = [];
        
                foreach ($usuarios as $usuario) {
                    $rol = $rolesCollection->findOne(['id_rol' => $usuario['id_rol']]);
                    if ($rol) {
                        $usuario['rol_nombre'] = $rol['nombre_rol']; 
                    } else {
                        $usuario['rol_nombre'] = 'Desconocido'; 
                    }
                    $usuariosArray[] = $usuario;
                }
        
                return $usuariosArray;
            } catch (Exception $e) {
                return false;
            }
        }


        public function editarUsuario($id) {
            try {
                $db = $this->conexion->conectar();
                if ($db === null) {
                    return "Error al conectar a la base de datos.";
                }
                
                $id = (int) $id;
                $usuariosCollection = $db->usuarios;
                $rolesCollection = $db->roles;
        
                $usuario = $usuariosCollection->findOne(['_id' => $id]);
                if (!$usuario) {
                    return "No se encontró ningún usuario con el ID proporcionado.";
                }
                $rol = $rolesCollection->findOne(['id_rol' => $usuario['id_rol']]);
                if ($rol) {
                    $usuario['rol_nombre'] = $rol['nombre_rol'];
                } else {
                    $usuario['rol_nombre'] = 'Desconocido';
                }
                return $usuario;
            } catch (Exception $e) {
                return "Error: " . $e->getMessage();
            }
        }

        public function actualizarUsuario($id, $nombre, $apellido, $telefono, $correo, $contrasena, $id_rol) {
            try {
                $db = $this->conexion->conectar();
                if ($db === null) {
                    return "Error al conectar a la base de datos.";
                }
                
                $id = (int) $id;
                $usuariosCollection = $db->usuarios;
               if (strlen ($contrasena)>40){
                $password_hash=$contrasena;
               }else{
                $password_hash = password_hash($contrasena, PASSWORD_DEFAULT); 
               }
                        
                $actualizarUsuario = [
                    'nombre' => $nombre,
                    'apellido' => $apellido,
                    'telefono' => $telefono,
                    'correo' => $correo,
                    'contrasena' => $password_hash, 
                    'id_rol' => $id_rol
                ];
        
                $resultado = $usuariosCollection->updateOne(
                    ['_id' => $id], 
                    ['$set' => $actualizarUsuario] 
                );
        
                if ($resultado->getModifiedCount() > 0) {
                    return "Usuario actualizado correctamente.";
                } else {
                    return "No se realizaron cambios en el usuario.";
                }
            } catch (Exception $e) {
                return "Error: " . $e->getMessage();
            }
        }

        public function eliminarUsuario($id) {
            try {
                $db = $this->conexion->conectar(); 
                if ($db === null) {
                    return "Error al conectar a la base de datos.";
                }
                
                $id = (int) $id;
                $usuariosCollection = $db->usuarios;
        
                $resultado = $usuariosCollection->deleteOne(['_id' => $id]);
        
                if ($resultado->getDeletedCount() > 0) {
                    return true; 
                } else {
                    return false; 
                }
            } catch (Exception $e) {
                return "Error: " . $e->getMessage();
            }
        }
    }
?>
