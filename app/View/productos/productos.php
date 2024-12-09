<?php 
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . "/RopayMedia/app/Controller/ProductoController/productoController.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/RopayMedia/app/Controller/CategoriaController/categoriaController.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/RopayMedia/app/Model/productoModel.php";
include_once '../layout.php';

$productoController = new ProductoController();
$categoriaController = new CategoriaController();

$idCategoria = isset($_GET['categoria']) ? $_GET['categoria'] : null;

// Verificar si se seleccionó una categoría
if ($idCategoria !== null) {
    // Obtener productos filtrados por categoría
    $productos = $productoController->listarProductosPorCategoria($idCategoria);
} else {
    // Obtener todos los productos si no se seleccionó una categoría
    $productos = $productoController->listarProductos();
}

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
    <div class="carrito-contenedor mt-2">
        <a href="../carrito_compras/pago.php">
            <img src="https://cdn-icons-png.flaticon.com/512/4012/4012571.png" alt="carrito" class="carrito-icono">
            <div class="carrito-circulo">
                <?php echo($_SESSION['carrito_cantidad']); ?>
            </div>
        </a>
    </div>
    <div class="container mt-5">
        <h1 class="text-center mb-4">
            <?php echo $idCategoria ? "Productos por categoría" : "Todos los productos"; ?>
        </h1>

        <?php if (empty($productos)): ?>
            <div class="alert alert-warning text-center">
                <strong>No hay productos disponibles en este momento.</strong>
            </div>
        <?php else: ?>
            <div class="row">
                <?php foreach ($productos as $producto): ?>
                    <?php if ($producto['stock'] > 0): ?>
                        <div class="col-lg-4 col-md-6 mb-4 d-flex align-items-stretch">
                            <div class="card h-100" style="width: 18rem; position: relative;">
                                <img src="<?php echo htmlspecialchars($producto['ruta_imagen']); ?>" 
                                    class="card-img-top" 
                                    alt="<?php echo htmlspecialchars($producto['nombre_producto']); ?>" 
                                    style="object-fit: cover; height: 250px;">
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title"><?php echo htmlspecialchars($producto['nombre_producto']); ?></h5>
                                    <p class="card-text mt-auto">
                                        <strong>Precio:</strong> ₡<?php echo number_format($producto['precio'], 2); ?><br>
                                        <strong>Stock:</strong> <?php echo $producto['stock']; ?>
                                    </p>
                                </div>
                                <div class="card-footer">
                                    <form method="POST" action="/RopayMedia/app/Controller/CarritoComprasController/carrito_controller.php?action=agregarAlCarrito">
                                        <input type="hidden" name="id_producto" value="<?php echo $producto['id_producto']; ?>">
                                        <input type="hidden" name="img" value="<?php echo htmlspecialchars($producto['ruta_imagen']); ?>">
                                        <input type="hidden" name="nombre" value="<?php echo htmlspecialchars($producto['nombre_producto']); ?>">
                                        <input type="hidden" name="precio" value="<?php echo number_format($producto['precio'], 2, '.', ''); ?>">
                                        <input type="hidden" name="stock" value="<?php echo $producto['stock']; ?>">
                                        <button type="submit" class="btn btn-success btn-block">Añadir al carrito</button>
                                    </form>
                                    <button type="button" class="btn btn-info btn-block mt-2" data-toggle="collapse" 
                                            data-target="#detalle-<?php echo $producto['id_producto']; ?>" 
                                            aria-expanded="false" 
                                            aria-controls="detalle-<?php echo $producto['id_producto']; ?>">
                                        Ver más
                                    </button>
                                </div>
                                <div id="detalle-<?php echo $producto['id_producto']; ?>" 
                                    class="collapse position-absolute w-100" 
                                    style="z-index: 10; background-color: white; border: 1px solid #ddd; padding: 15px;">
                                    <h5>Descripción del producto</h5>
                                    <p><?php echo htmlspecialchars($producto['descripcion']); ?></p>
                                    <button type="button" class="btn btn-secondary btn-block" data-toggle="collapse" 
                                            data-target="#detalle-<?php echo $producto['id_producto']; ?>">
                                        Cerrar
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <?php MostrarFooter(); ?>

    <!-- Scripts -->
    <script src="assets/vendor/jquery/dist/jquery.min.js"></script>
    <script src="assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/js-cookie/js.cookie.js"></script>
    <script src="assets/vendor/jquery.scrollbar/jquery.scrollbar.min.js"></script>
    <script src="assets/vendor/jquery-scroll-lock/dist/jquery-scrollLock.min.js"></script>
    <script src="assets/js/argon.js?v=1.2.0"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>