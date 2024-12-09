<?php
    include_once $_SERVER['DOCUMENT_ROOT'] . "/RopayMedia/app/Model/usuarioModel.php";        

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!empty($_POST["btnIniciarSesion"])) {
        $correo = $_POST['correo'];
        $contrasena = $_POST['contrasena'];
    
        $usuario = new usuarioModel();
        $login_result = $usuario->login($correo, $contrasena);

        if ($login_result !== false) {
            $_SESSION['id_usuario'] = $login_result['_id'];
            $_SESSION['id_rol'] = $login_result['id_rol'];
            $_SESSION['nombre'] = $login_result['nombre'];
            $_SESSION['apellido'] = $login_result['apellido'];
            $_SESSION['telefono'] = $login_result['telefono'];
            $_SESSION['correo'] = $login_result['correo'];
            header("location: ../home/home.php");
            exit;
        } else {
            $_SESSION['error_mensaje'] = "Correo o contraseña incorrectos.";
        }
    }
?>

