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
$usuarios = $usuarioModel->listarUsuarios();

?>
<!DOCTYPE html>
<html>
<?php 
HeadCSS();

?>

<style>
    .usuariolist{
        background-color: #ffffffff;
    }


</style>
<body class="d-flex flex-column min-vh-100">

<?php 
MostrarNav();
MostrarMenu();
?>

<div class="flex-grow-2 mb-5">
    <div class="header bg-primary pb-6">
        <div class="container-fluid">
            <div class="header-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12">
                        <h1 class="display-4 text-white">Lista de Usuarios</h1>
                        
                        <p class="text-white">Todos los usuarios disponibles.</p>

                         <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#agregarUsuario">Agregar Usuario</button>
                        <br><br><br>
                    </div>
                </div>
                <div class="usuariolist">                                          
                        <?php if (!empty($usuarios)): ?>
                            <div class="table-responsive">
                            <table class="table table-striped table-hover custom-table text-center">
                                <thead>
                                    <tr>
                                       <th scope="col">ID</th>
                                        <th scope="col">Nombre</th>
                                        <th scope="col">Apellido</th>
                                        <th scope="col">Teléfono</th>
                                        <th scope="col">Correo</th>
                                        <th scope="col">Contraseña</th>
                                        <th scope="col">Rol</th>
                                        <th scope="col">Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($usuarios as $usuario): ?> 
                                        <tr>
                                        <td><?= htmlspecialchars($usuario['_id']); ?></td>
                                            <td><?= htmlspecialchars($usuario['nombre']); ?></td>
                                            <td><?= htmlspecialchars($usuario['apellido']); ?></td>
                                            <td><?= htmlspecialchars($usuario['telefono']); ?></td>
                                            <td><?= htmlspecialchars($usuario['correo']); ?></td>
                                            <td><?= htmlspecialchars($usuario['contrasena']); ?></td>
                                            <td><?= htmlspecialchars($usuario['rol_nombre']); ?></td>
                                            <td>
                                                <a href="editar.php?id=<?= $usuario['_id']; ?>" class="btn btn-warning btn-sm">Editar</a>
                                                <a href="#" class="btn btn-danger btn-sm eliminarBtn" id="<?= $usuario['_id']; ?>">Eliminar</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            </div>
                        <?php else: ?>
                            <div class="col-lg-12">
                                <p class="text-white">No hay usuarios disponibles.</p>
                            </div>
                        <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal" tabindex="-1"  id="agregarUsuario">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-center w-100" >Agregar Usuario</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <form role="form" method="POST" id="registrarUsuario">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required>
                
            </div>
            
            <div class="mb-3">
                <label for="apellido" class="form-label">Apellido</label>
                <input type="text" class="form-control" id="apellido" name="apellido" >
                
            </div>
            <div class="mb-3">
                <label for="telefono" class="form-label">Teléfono</label>
                <input type="text" class="form-control" id="telefono" name="telefono" >
                
            </div>
            <div class="mb-3">
                <label for="correo" class="form-label">Correo</label>
                <input type="email" class="form-control" id="correo" name="correo" aria-describedby="emailHelp" required>
                
            </div>
            <div class="mb-3">
                <label for="contrasena" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="contrasena" name="contrasena" required>
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="rol" name="rol" value="1">
                <label class="form-check-label" for="rol" >Es admin</label>
            </div>
         
       
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" id="saveBtn" class="btn btn-primary ">Agregar Usuario</button>
      </div>
      </form>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="eliminarModal" tabindex="-1" aria-labelledby="eliminarModalLabel" aria-hidden="true">

  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-center w-100" >Eliminar Usuario</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
            
            <div class="mb-3">
                <label for="telefono" class="form-label">Estas eliminando al usuario</label>
                <input type="text" class="form-control"  value="<?= $usuario['correo'] ?>">
                
            </div>
                  
       
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-danger" id="confirmarEliminarBtn">Eliminar</button>
      </div>
      
      </div>
    </div>
  </div>
</div>

<?php MostrarFooter();?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../plugins/jquery/jquery.min.js"></script>
    <script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../dist/js/adminlte.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
        $(document).ready(function() {
            $('#saveBtn').on('click', function(e) {
                e.preventDefault();
                

                var formData = new FormData($('#registrarUsuario')[0]);
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
                    url: '/RopayMedia/app/Controller/UsuarioController/usuarioController.php?action=registrarUsuario',
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

document.querySelectorAll('.eliminarBtn').forEach(function(btn) {
    btn.addEventListener('click', function(e) {
      e.preventDefault();
      var usuarioId = btn.getAttribute('id');
      
      $('#eliminarModal').modal('show');
      
      document.getElementById('confirmarEliminarBtn').setAttribute('id', usuarioId);
    });
  });
  document.getElementById('confirmarEliminarBtn').addEventListener('click', function() {
    var usuarioId = this.getAttribute('id');
    
    $.ajax({
      url: '/RopayMedia/app/Controller/UsuarioController/usuarioController.php?action=eliminarUsuario',
      type: 'POST',
      data: { id: usuarioId },
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
    </script>
</body>

</html>