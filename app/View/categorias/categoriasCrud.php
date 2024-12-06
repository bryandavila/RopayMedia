<?php
session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . "/RopayMedia/app/Controller/CategoriaController/categoriaController.php";
include_once '../layout.php';

$categoriaController = new CategoriaController();
$categoriaController->manejarAcciones(); // Manejar acciones de crear, actualizar o eliminar
$categorias = $categoriaController->listarCategorias(); // Listar las categorías existentes

$mensaje = isset($_SESSION['mensaje']) ? $_SESSION['mensaje'] : '';
$tipo = isset($_SESSION['tipo']) ? $_SESSION['tipo'] : 'success';
unset($_SESSION['mensaje'], $_SESSION['tipo']); // Limpiar el mensaje de la sesión después de mostrarlo
?>

<!DOCTYPE html>
<html>

<?php 
HeadCSS();
?>

<body class="d-flex flex-column min-vh-100">

<?php 
MostrarNav();
MostrarMenu();
?>

<div class="container mt-5">
    <h1 class="text-center mb-4">Administrar categorías</h1>
    
    <!-- Mostrar el mensaje de éxito o error -->
    <?php if (!empty($mensaje)): ?>
        <div class="alert alert-<?php echo $tipo; ?> alert-dismissible fade show" role="alert">
            <?php echo $mensaje; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    
    <!-- Formulario para crear o actualizar categorías -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Formulario de categorías</h4>
        </div>
        <div class="card-body">
            <form method="POST" action="categoriasCrud.php">
                <div class="mb-3">
                    <label for="id_categoria" class="form-label">Seleccione la categoría (solo para actualizar):</label>
                    <select name="id_categoria" id="id_categoria" class="form-control">
                        <option value="">Seleccione una categoría</option>
                        <?php foreach ($categorias as $categoria): ?>
                            <option value="<?php echo $categoria['id_categoria']; ?>">
                                <?php echo htmlspecialchars($categoria['nombre_categoria']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="nombre_categoria" class="form-label">Nombre de la categoría:</label>
                    <input type="text" name="nombre_categoria" id="nombre_categoria" class="form-control" placeholder="Ingrese el nombre de la categoría" required>
                </div>
                <button type="submit" name="accion" value="Crear" class="btn btn-success">Crear categoría</button>
                <button type="submit" name="accion" value="Actualizar" class="btn btn-warning">Actualizar categoría</button>
            </form>
        </div>
    </div>
</div>

<?php MostrarFooter(); ?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    <?php if (!empty($mensaje)): ?>
        Swal.fire({
            icon: '<?php echo $tipo; ?>',
            title: '<?php echo $mensaje; ?>',
            showConfirmButton: false,
            timer: 2000
        });
    <?php endif; ?>
</script>

<script src="assets/vendor/jquery/dist/jquery.min.js"></script>
<script src="assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/js-cookie/js.cookie.js"></script>
<script src="assets/vendor/jquery.scrollbar/jquery.scrollbar.min.js"></script>
<script src="assets/vendor/jquery-scroll-lock/dist/jquery-scrollLock.min.js"></script>
<script src="assets/js/argon.js?v=1.2.0"></script>
</body>

</html>
