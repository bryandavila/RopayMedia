<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/RopayMedia/vendor/autoload.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/RopayMedia/Model/baseDatosModel.php";

class RegistroModel {
    private $conexion;

    public function __construct() {
        $this->conexion = new Conexion();  //Crear la conexión
    }

    //Función para registrar un nuevo usuario
    public function registrarUsuario($nombre, $apellido, $correo, $telefono, $id_rol) {
        try {
            $db = $this->conexion->conectar();  //Obtener la base de datos
            if ($db === null) {
                return "Error al conectar a la base de datos.";
            }
            
            $usuariosCollection = $db->usuarios;  //Obtener la colección de usuarios

            //Verificar si el correo ya existe en la base de datos
            $usuarioExistente = $usuariosCollection->findOne(['correo' => $correo]);
            if ($usuarioExistente) {
                return "El correo ya está registrado.";
            }

            //Preparar los datos para insertar
            $nuevoUsuario = [
                'nombre' => $nombre,
                'apellido' => $apellido,
                'correo' => $correo,
                'telefono' => $telefono,
                'id_rol' => $id_rol
            ];

            //Insertar el nuevo usuario en la colección de usuarios
            $resultado = $usuariosCollection->insertOne($nuevoUsuario);

            //Verificar si se insertó correctamente
            if ($resultado->getInsertedCount() == 1) {
                return "Usuario registrado exitosamente.";
            } else {
                return "Error al registrar el usuario.";
            }
        } catch (Exception $e) {
            return "Error: " . $e->getMessage();
        }
    }
}
?>
