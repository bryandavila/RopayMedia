<?php
session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . "/RopayMedia/app/Controller/FacturaController/facturas_controller.php";
include_once '../layout.php';

$facturaController = new FacturaController();
$facturas = $facturaController->listarFacturas(); 

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
                <?php if (!empty($facturas)): ?>
                    <?php foreach ($facturas as $index => $factura): ?>
                        <tr>
                            <td><?php echo $index + 1; ?></td>
                            <td>
                                <?php 
                                echo isset($factura['id_usuario']) 
                                    ? htmlspecialchars($factura['id_usuario']) 
                                    : 'Cliente no disponible'; 
                                ?>
                            </td>
                            <td>
                                <?php 
                                echo isset($factura['id_pedido']) 
                                    ? htmlspecialchars($factura['id_pedido']) 
                                    : 'Pedido no disponible'; 
                                ?>
                            </td>
                            <td>
                                <?php 
                                echo isset($factura['total']) 
                                    ? htmlspecialchars(number_format($factura['total'], 2)) 
                                    : 'Total no disponible'; 
                                ?>
                            </td>
                            <td>
                                <?php 
                               if (!empty($factura['fecha_emision'])) {
                                $fecha = DateTime::createFromFormat('Y-m-d H:i:s', $factura['fecha_emision']);
                                if ($fecha) {
                                    echo htmlspecialchars($fecha->format('d-m-Y H:i:s')); // Formato deseado en la vista
                                } else {
                                    echo 'Formato de fecha inválido';
                                }
                            } else {
                                echo 'Fecha no disponible';
                            }
                            ?>
                               
                            </td>
                            <td>
                                <form method="POST" action="facturasCrud.php" class="d-inline">
                                    <input type="hidden" name="id_factura" value="<?php echo htmlspecialchars($factura['id_factura']); ?>">
                                    <button type="submit" name="accion" value="Eliminar" class="btn btn-danger btn-sm">Eliminar</button>
                                </form>
                                <a href="facturaVista.php?id_factura=<?php echo htmlspecialchars($factura['id_factura']); ?>" class="btn btn-warning btn-sm">Ver</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">No hay facturas registradas.</td>
                    </tr>
                <?php endif; ?>
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
