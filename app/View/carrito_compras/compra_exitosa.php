<?php 
    include_once '../layout.php';
    $_SESSION['carrito'] = [];
    $_SESSION['carrito_cantidad'] = 0;
?>

<!DOCTYPE html>
<html>
<?php HeadCSS(); ?>

<body class="d-flex flex-column min-vh-100">

    <?php 
        MostrarNav();
        MostrarMenu();
    ?>

    <div class="container mt-4 d-flex justify-content-center">
        <div class="card text-center" style="width: 24rem;">
            <div class="card-body">
                <img src="../assets/img/brand/imagen3.png" class="card-img-top mb-3" alt="Imagen de confirmación">
                <i class="fas fa-check-circle fa-3x text-success"></i>
                <h2 class="card-title mt-3">Gracias por su compra en nuestra tienda</h2>
                <p class="card-text">Su pedido ha sido procesado y se encuentra en validación, proto sera notificado.</p>
                <a href="../productos/productos.php" class="btn btn-primary mt-3">
                    <i class="fa fa-arrow-left"></i> Volver a la tienda
                </a>
            </div>
        </div>
    </div>
    <?php MostrarFooter(); ?>
</body>
</html>
