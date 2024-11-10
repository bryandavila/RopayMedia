<?php
//Incluir los archivos de configuración y base de datos
include_once $_SERVER['DOCUMENT_ROOT'] . "/RopayMedia/Model/baseDatosModel.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/RopayMedia/Model/registroModel.php";

//Verificar si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //Obtener los datos del formulario
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];
    $rol = 2;  //El rol por defecto para el usuario registrado

    //Validar los datos
    if (empty($nombre) || empty($apellido) || empty($correo) || empty($telefono)) {
        $error_registro = "Todos los campos son requeridos.";
    } else {
        //Crear el objeto usuario y registrar
        $usuario = new RegistroModel();
        $mensaje_registro = $usuario->registrarUsuario($nombre, $apellido, $correo, $telefono, $rol);

        //Asignar mensaje de éxito o error basado en la respuesta del modelo
        if ($mensaje_registro === "Usuario registrado exitosamente.") {
            $registro_exitoso = "Cuenta creada exitosamente. Puedes iniciar sesión.";
        } else {
            $error_registro = $mensaje_registro;
        }
    }
}
?>
