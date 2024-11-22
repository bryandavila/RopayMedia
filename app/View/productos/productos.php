<?php 
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . "/RopayMedia/app/Controller/ProductoController/productoController.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/RopayMedia/app/Controller/CategoriaController/categoriaController.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/RopayMedia/app/Model/productoModel.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/RopayMedia/app/Model/categoriaModel.php";
include_once '../layout.php';

$productoController = new ProductoController();
$categoriaController = new CategoriaController();

// Obtener la lista de productos y categorías
$productos = $productoController->listarProductos();
$categorias = $categoriaController->listarCategorias();

// Depuración: Verificar los productos obtenidos
echo "<pre>";
var_dump($productos);
echo "</pre>";
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
    <h1 class="text-center mb-4">Todos los productos</h1>

    <?php if (empty($productos)): ?>
        <div class="alert alert-warning text-center">
            <strong>No hay productos disponibles en este momento.</strong>
        </div>
    <?php else: ?>
        <!-- Mostrar productos agrupados por categoría -->
        <?php foreach ($categorias as $categoria): ?>
            <div class="mb-5">
                <h2 class="text-primary"><?php echo htmlspecialchars($categoria['nombre_categoria']); ?></h2>
                <div class="row">
                    <?php 
                          
                                 
                    ?>

                    
                        <?php foreach ($productos as $producto): ?>
                            <div class="col-md-4 col-sm-6 mb-4">
                                <div class="card h-100">
                                    <img src="<?php echo htmlspecialchars($producto['ruta_imagen']); ?>" 
                                         class="card-img-top" 
                                         alt="<?php echo htmlspecialchars($producto['nombre_producto']); ?>">
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo htmlspecialchars($producto['nombre_producto']); ?></h5>
                                        <p class="card-text text-muted"><?php echo htmlspecialchars($producto['descripcion']); ?></p>
                                        <p class="card-text">
                                            <strong>Precio:</strong> ₡<?php echo number_format($producto['precio'], 2); ?><br>
                                            <strong>Stock:</strong> <?php echo $producto['stock']; ?>
                                        </p>
                                    </div>
                                    <div class="card-footer">
                                        <form method="POST" action="carrito.php">
                                            <input type="hidden" name="id_producto" value="<?php echo $producto['id_producto']; ?>">
                                            <button type="submit" class="btn btn-success btn-block">Añadir al carrito</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php MostrarFooter(); ?>

<script src="assets/vendor/jquery/dist/jquery.min.js"></script>
<script src="assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/js-cookie/js.cookie.js"></script>
<script src="assets/vendor/jquery.scrollbar/jquery.scrollbar.min.js"></script>
<script src="assets/vendor/jquery-scroll-lock/dist/jquery-scrollLock.min.js"></script>
<script src="assets/js/argon.js?v=1.2.0"></script>
</body>

</html>