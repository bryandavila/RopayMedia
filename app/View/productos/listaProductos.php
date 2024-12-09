<?php 
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . "/RopayMedia/app/Controller/ProductoController/productoController.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/RopayMedia/app/Model/productoModel.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/RopayMedia/app/Model/categoriaModel.php";
include_once '../layout.php';

$productoController = new ProductoController();
$productoController->manejarAcciones();

$productos = $productoController->listarProductos();
$mensaje = isset($_SESSION['mensaje']) ? $_SESSION['mensaje'] : '';
unset($_SESSION['mensaje']);
?>

<!DOCTYPE html>
<html>

<?php HeadCSS(); ?>

<body class="d-flex flex-column min-vh-100">
<?php MostrarNav(); MostrarMenu(); ?>

<div class="container mt-5">
    <h1 class="text-center mb-4">Lista de productos</h1>
    <?php if (!empty($mensaje)): ?>
        <div class="alert alert-info"><?php echo htmlspecialchars($mensaje); ?></div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header bg-secondary text-white">
            <h4 class="mb-0">Lista de productos</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Img</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Precio</th>
                            <th>Stock</th>
                            <th>Categoría</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($productos as $producto): ?>
                            <tr>
                                <td>
                                    <img src="<?php echo htmlspecialchars($producto['ruta_imagen']); ?>" 
                                            alt="<?php echo htmlspecialchars($producto['nombre_producto']); ?>" 
                                            style="width: 50px; height: 50px; object-fit: cover;"/>
                                </td>
                                <td><?php echo htmlspecialchars($producto['nombre_producto']); ?></td>
                                <td><?php echo htmlspecialchars($producto['descripcion']); ?></td>
                                <td><?php echo htmlspecialchars($producto['precio']); ?></td>
                                <td><?php echo htmlspecialchars($producto['stock']); ?></td>
                                <td><?php echo htmlspecialchars($producto['id_categoria']); ?></td>
                                <td>
                                    <a href="productosCrud.php?id_producto=<?php echo $producto['id_producto']; ?>" class="btn btn-primary btn-sm">Editar</a>
                                    <form method="POST" action="listaProductos.php" class="d-inline">
                                        <input type="hidden" name="id_producto" value="<?php echo $producto['id_producto']; ?>">
                                        <button type="submit" name="accion" value="Eliminar" class="btn btn-danger btn-sm">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<?php MostrarFooter(); ?>

<script src="assets/vendor/jquery/dist/jquery.min.js"></script>
<script src="assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/argon.js?v=1.2.0"></script>
</body>
</html>
