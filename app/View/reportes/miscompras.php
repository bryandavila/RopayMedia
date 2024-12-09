<?php
include_once '../layout.php';

include_once '../../Controller/ReporteController/reporteController.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


$nombreUsuario = isset($_SESSION['nombre']) ? $_SESSION['nombre'] : '';
$reporteController = new reporteController();
$reportes = $reporteController->obtenerComprasUsuario($_SESSION['id_usuario']);

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
    <h1 class="text-center mb-4">Mis Compras</h1>

    <?php if (!empty($mensaje)): ?>
        <div class="alert alert-<?php echo $tipo; ?>" role="alert">
            <?php echo $mensaje; ?>
        </div>
    <?php endif; ?>
    
    <!-- Tabla de reportes -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Compras realizadas</h4>
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Numero de Pedido</th>
                        <th>Productos</th>
                        <th>Total</th>
                        <th>Fecha de reporte</th>
                     
                    </tr>
                </thead>
                <tbody>
                <?php if (!empty($reportes)): ?>
                    <?php foreach ($reportes as $index => $reporte): ?>
                        <tr>
                            <td><?php echo $index + 1; ?></td>
                            
                            <td>
                                <?php 
                                echo isset($reporte['id_pedido']) 
                                    ? htmlspecialchars($reporte['id_pedido']) 
                                    : 'Pedido no disponible'; 
                                ?>
                            </td>
                            <td>
                                <?php if (!empty($reporte['productos']) && is_iterable($reporte['productos'])): ?>
                                    <ul>
                                        <?php foreach ($reporte['productos'] as $producto): ?>
                                            <li>
                                                <?php echo htmlspecialchars($producto['nombre']); ?>
                                                <strong>  Cantidad:</strong> <?php echo htmlspecialchars($producto['cantidad']); ?><br>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php else: ?>
                                    No hay productos disponibles.
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php 
                                echo isset($reporte['total']) 
                                    ? htmlspecialchars(number_format($reporte['total'], 2)) 
                                    : 'Total no disponible'; 
                                ?>
                            </td>
                            <td>
                                <?php 
                               if (!empty($reporte['fecha_emision'])) {
                                $fecha = DateTime::createFromFormat('Y-m-d H:i:s', $reporte['fecha_emision']);
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
                           
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">No hay reportes registradas.</td>
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

</body>

</html>
