<?php include_once '../layout.php';?>

<?php
    $nombreUsuario = isset($_SESSION['nombre']) ? $_SESSION['nombre'] : '';
?>

<!DOCTYPE html>
<html>

<?php HeadCSS(); ?>

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
                        <h1 class="display-4 text-white">Bienvenido <?php echo htmlspecialchars($nombreUsuario); ?>!</h1>
                        <p class="text-white">Accede a las diferentes secciones de la tienda Ropa y 1/2</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card">
                    <div class="card-body text-center">
                        <h3 class="card-title">Ver los productos por categoria</h3>
                        <p class="card-text">Podras ver nuestros productos por categorias.</p>
                        <a href="../categorias/categorias.php" class="btn btn-primary">Ir a productos por categoria</a>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card">
                    <div class="card-body text-center">
                        <h3 class="card-title">Ver todos los productos</h3>
                        <p class="card-text">Podras ver todos los productos de nuestra tienda.</p>
                        <a href="../productos/productos.php" class="btn btn-primary">Ir a todos los productos</a>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card">
                    <div class="card-body text-center">
                        <h3 class="card-title">Agregar y actualizar productos</h3>
                        <p class="card-text">Podras agregar y actualizar productos en la tienda.</p>
                        <a href="../productos/productosCrud.php" class="btn btn-primary">Ir a gestión de productos</a>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card">
                    <div class="card-body text-center">
                        <h3 class="card-title">Visualizar mi carrito</h3>
                        <p class="card-text">Puedes ver como van los productos en tu carrito.</p>
                        <a href="pago.php" class="btn btn-primary">Ir a mi carrito</a>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card">
                    <div class="card-body text-center">
                        <h3 class="card-title">Visualizar mis compras</h3>
                        <p class="card-text">Puedes ver las compras que has realizado en la tienda.</p>
                        <a href="miscompras.php" class="btn btn-primary">Ir a mis compras</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php MostrarFooter(); ?>

<script src="assets/vendor/jquery/dist/jquery.min.js"></script>
<script src="assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/js-cookie/js.cookie.js"></script>
<script src="assets/vendor/jquery.scrollbar/jquery.scrollbar.min.js"></script>
<script src="assets/vendor/jquery-scroll-lock/dist/jquery-scrollLock.min.js"></script>
<script src="assets/vendor/chart.js/dist/Chart.min.js"></script>
<script src="assets/vendor/chart.js/dist/Chart.extension.js"></script>
<script src="assets/js/argon.js?v=1.2.0"></script>
</body>

</html>
