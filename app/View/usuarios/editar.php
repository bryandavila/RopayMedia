<?php 
include_once '../layout.php';
include_once '../../Model/usuarioModel.php';
include_once '../../Controller/UsuarioController/usuarioController.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

#if (!isset($_SESSION['id'])) {
#    header("Location: login.php");
#    exit();
#}

$nombreUsuario = isset($_SESSION['nombre']) ? $_SESSION['nombre'] : '';

$usuarioModel = new UsuarioModel();
$usuario = $usuarioModel->editarUsuario($_GET['id']);


?>
<!DOCTYPE html>
<html>
<?php 
HeadCSS();

?>
<style>
     #container{
        background-color: coral;
    }
    
</style>
<body class="d-flex flex-column min-vh-100">
<?php 
MostrarNav();
MostrarMenu();
?>
<div class="container mt-4">
    <form role="form" method="POST" id="editarUsuario">
        <div class="row justify-content-center">
            <input type="hidden" name="id" value="<?= $_GET['id']; ?>">
            <div class="col-md-8 col-lg-6">
               
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" value="<?= $usuario['nombre']; ?>" >
                </div>

                <div class="mb-3">
                    <label for="apellido" class="form-label">Apellido</label>
                    <input type="text" class="form-control" id="apellido" name="apellido" value="<?= $usuario['apellido'] ?>">
                </div>

                <div class="mb-3">
                    <label for="telefono" class="form-label">Teléfono</label>
                    <input type="text" class="form-control" id="telefono" name="telefono" value="<?= $usuario['telefono'] ?>">
                </div>

                <div class="mb-3">
                    <label for="correo" class="form-label">Correo</label>
                    <input type="email" class="form-control" id="correo" name="correo" aria-describedby="emailHelp" value="<?= $usuario['correo'] ?>" >
                </div>

                <div class="mb-3">
                    <label for="contrasena" class="form-label">Contraseña</label>
                    <input type="password" class="form-control" id="contrasena" name="contrasena" value="<?= $usuario['contrasena'] ?>" >
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="rol" name="rol" value="1" <?php if ($usuario['id_rol'] == 1) echo 'checked'; ?>>
                    <label class="form-check-label" for="rol">Es admin</label>
                </div>

                <div class="modal-footer">
                <button type="button" id="cancelBtn" class="btn btn-secondary">Cancelar</button>
                <button type="submit" id="saveBtn" class="btn btn-primary">Guardar cambios</button>
                </div>
            </div>
        </div>
    </form>
</div>
      <?php MostrarFooter();?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="../plugins/jquery/jquery.min.js"></script>
    <script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../dist/js/adminlte.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>

document.getElementById('cancelBtn').addEventListener('click', function() {
     window.location.href = 'listarUsuarios.php'; 
});

$(document).ready(function() {
            $('#saveBtn').on('click', function(e) {
                e.preventDefault();
                

                var formData = new FormData($('#editarUsuario')[0]);
                var nombre = $('#nombre').val();
                var correo = $('#correo').val();
                var contrasena = $('#contrasena').val();

            if (nombre === "" ) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'EL nombre no puede quedar en blanco.'
                });
                return; 
            }if(correo === "" ){
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'EL correo no puede quedar en blanco.'
                });
                return; 

            }if( !/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/.test(correo)){
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'EL espacio correo debe tener un formato correcto.'
                });
                return; 

            }if(contrasena === ""){
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'La contraseña no puede quedar en blanco.'
                });
                return; 

            }

          
                
                $.ajax({
                    url: '/RopayMedia/app/Controller/UsuarioController/usuarioController.php?action=actualizarUsuario',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        try {
                            var data = JSON.parse(response);
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Éxito',
                                    text: data.message,
                                }).then(() => {
                                    window.location.href = 'listarUsuarios.php'; 
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: data.message,
                                });
                            }
                        } catch (e) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error de respuesta',
                                text: 'La respuesta no es un JSON válido: ' + response,
                            });
                            console.error("Respuesta del servidor:", response);
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Hubo un error en la solicitud.',
                        });
                    }
                });
              });
        });
</script>
</body>

</html>