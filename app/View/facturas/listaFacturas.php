<?php
session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . "/RopayMedia/app/Controller/FacturasController/facturas_controller.php";
include_once '../layout.php';

$facturaController = new FacturaController();
$facturas = $facturaController->listarFacturas(); // Suponiendo que 'obtenerFacturas' es un método para obtener todas las facturas

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
    <h1 class="text-center mb-4">Lista de Facturas</h1>

    <?php if (!empty($mensaje)): ?>
        <div class="alert alert-<?php echo $tipo; ?>" role="alert">
            <?php echo $mensaje; ?>
        </div>
    <?php endif; ?>
    
    <!-- Tabla de Facturas -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Facturas Creadas</h4>
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Cliente</th>
                        <th>Numero de Pedido</th>
                        <th>Total</th>
                        <th>Fecha de Factura</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($facturas as $factura): ?>
                        <tr>
                            <td><?php echo $factura['id']; ?></td>
                            <td><?php echo $factura['id_cliente']; ?></td>
                            <td><?php echo $factura['id_pedido']; ?></td>
                            <td><?php echo number_format($factura['total'], 2); ?></td>
                            <td><?php echo $factura['fecha_factura']; ?></td>
                            <td>
                                <a href="detalleFactura.php?id=<?php echo $factura['id']; ?>" class="btn btn-info btn-sm">Ver Detalle</a>
                                <form method="POST" action="facturasCrud.php" style="display:inline;">
                                    <input type="hidden" name="id_factura" value="<?php echo $factura['id']; ?>">
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
