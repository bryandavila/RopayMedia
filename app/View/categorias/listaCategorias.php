<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/RopayMedia/app/Controller/CategoriaController/categoriaController.php";
include_once '../layout.php';

$categoriaController = new CategoriaController();
$categorias = $categoriaController->listarCategorias(); // Listar las categorías existentes
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
    <h1 class="text-center mb-4">Listado de categorías</h1>
    
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Listado de categorías</h4>
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($categorias)): ?>
                        <?php foreach ($categorias as $categoria): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($categoria['id_categoria']); ?></td>
                                <td><?php echo htmlspecialchars($categoria['nombre_categoria']); ?></td>
                                <td>
                                    <form method="POST" action="categoriasCrud.php" class="d-inline">
                                        <input type="hidden" name="id_categoria" value="<?php echo $categoria['id_categoria']; ?>">
                                        <button type="submit" name="accion" value="Eliminar" class="btn btn-danger btn-sm">Eliminar</button>
                                    </form>
                                    <form method="GET" action="categoriasCrud.php" class="d-inline">
                                        <input type="hidden" name="id_categoria" value="<?php echo $categoria['id_categoria']; ?>">
                                        <button type="submit" name="accion" value="Actualizar" class="btn btn-warning btn-sm">Actualizar</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="text-center">No hay categorías disponibles.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php MostrarFooter(); ?>

<script src="assets/vendor/jquery/dist/jquery.min.js"></script>
<script src="assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/js-cookie/js.cookie.js"></script>
<script src="assets/vendor/jquery.scrollbar/jquery.scrollbar.min.js"></script>
<script src="assets/vendor/jquery-scroll-lock/dist/jquery.scrollLock.min.js"></script>
<script src="assets/js/argon.js?v=1.2.0"></script>
</body>

</html>
