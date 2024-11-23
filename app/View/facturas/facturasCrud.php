<?php
session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . "/RopayMedia/app/Controller/FacturasController/facturas_controller.php";
include_once '../layout.php';




$facturaController = new FacturaController();
$facturaController->manejarAcciones(); 

$mensaje = isset($_SESSION['mensaje']) ? $_SESSION['mensaje'] : '';
$tipo = isset($_SESSION['tipo']) ? $_SESSION['tipo'] : 'success';
unset($_SESSION['mensaje'], $_SESSION['tipo']); 
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
    <h1 class="text-center mb-4">Crear Factura</h1>
    
    <!-- Formulario para crear o actualizar facturas -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Formulario de Creación de Factura</h4>
        </div>
        <div class="card-body">
            <form method="POST" action="facturasCrud.php">
                <div class="mb-3">
                    <label for="id_cliente" class="form-label">Cliente:</label>
                    <input type="number" name="id_cliente" id="id_cliente" class="form-control" placeholder="Ingrese el ID del cliente" required>
                </div>
                <div class="mb-3">
                    <label for="id_pedido" class="form-label">Numero de Pedido:</label>
                    <input type="number" name="id_pedido" id="id_pedido" class="form-control" placeholder="Ingrese el ID del pedido" required>
                </div>
                <div class="mb-3">
                    <label for="productos" class="form-label">Productos (ID):</label>
                    <input type="text" name="productos" id="productos" class="form-control" placeholder="Ingrese los IDs de productos, separados por coma" required>
                </div>
                <div class="mb-3">
                    <label for="total" class="form-label">Total:</label>
                    <input type="number" step="0.01" name="total" id="total" class="form-control" placeholder="Ingrese el total" required>
                </div>
                <div class="mb-3">
                    <label for="fecha_factura" class="form-label">Fecha de la factura:</label>
                    <input type="date" name="fecha_factura" id="fecha_factura" class="form-control" required>
                </div>
                <button type="submit" name="accion" value="Crear" class="btn btn-success">Crear Factura</button>
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
