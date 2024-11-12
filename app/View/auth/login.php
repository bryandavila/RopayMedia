<?php
    include_once '../../Controller/AuthController/loginController.php';
    include_once '../layout.php';
?>

<!DOCTYPE html>
<html lang="es">

<?php HeadAuth(); ?>

<body class="bg-default">
    <?php navbarLoginRegistro(); ?>
    <div class="main-content">
        <div class="header bg-gradient-primary py-7 py-lg-8 pt-lg-9">
            <div class="container">
                <div class="header-body text-center mb-7">
                    <div class="row justify-content-center">
                        <div class="col-xl-5 col-lg-6 col-md-8 px-5">
                            <h1 class="text-white">Iniciar Sesión</h1>
                            <p class="text-lead text-white">Iniciar sesión para realizar tus compras en Ropa y 1/2</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="separator separator-bottom separator-skew zindex-100">
                <svg x="0" y="0" viewBox="0 0 2560 100" preserveAspectRatio="none" version="1.1"
                    xmlns="http://www.w3.org/2000/svg">
                    <polygon class="fill-default" points="2560 0 2560 100 0 100"></polygon>
                </svg>
            </div>
        </div>

        <div class="container mt--8 pb-5">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-8">
                    <div class="card bg-secondary border-0">
                        <div class="card-header bg-transparent pb-5">
                            <div class="text-muted text-center mt-2 mb-3">
                                <h1 class="text-black">Iniciar Sesión</h1>
                            </div>
                        </div>
                        <div class="card-body px-lg-5 py-lg-5">
                            <form action="" method="POST">
                                <div class="form-group">
                                    <div class="input-group input-group-merge input-group-alternative mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="ni ni-email-83"></i></span>
                                        </div>
                                        <input class="form-control" placeholder="Correo Electrónico" type="email" name="correo" id="correo" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="input-group input-group-merge input-group-alternative mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="ni ni-password-83"></i></span>
                                        </div>
                                        <input class="form-control" placeholder="Contraseña" type="password" name="contrasena" id="contrasena" required>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <button type="submit" value="Login" name="btnIniciarSesion" class="btn btn-primary mt-4">Ingresar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php MostrarFooter(); ?>

    
    <script src="../plugins/jquery/jquery.min.js"></script>
    <script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../dist/js/adminlte.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            <?php if (isset($_SESSION['error_mensaje'])): ?>
                Swal.fire({
                    title: 'Error',
                    text: '<?php echo $_SESSION['error_mensaje']; ?>',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                <?php unset($_SESSION['error_mensaje']); ?>
            <?php endif; ?>
        });
    </script>
</body>

</html>
