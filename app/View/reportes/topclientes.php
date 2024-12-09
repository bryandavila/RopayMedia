<?php
include_once '../layout.php';
include_once '../../Model/reporteModel.php';
include_once '../../Controller/ReporteController/reporteController.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}



$reporteController = new reporteController();
$reportes = $reporteController->top10clientes();
$cont=1;
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
    <h1 class="text-center mb-4">Reporte de los 10 clientes con más compras</h1>

    <?php if (!empty($mensaje)): ?>
        <div class="alert alert-<?php echo $tipo; ?>" role="alert">
            <?php echo $mensaje; ?>
        </div>
    <?php endif; ?>
    
    <!-- Tabla de reportes -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Top 10</h4>
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Usuario</th>
                        <th>Total Comprado</th>
                       
                    </tr>
                </thead>
                <tbody>
                <?php if (!empty($reportes)): ?>
                    <?php foreach ($reportes as $index => $reporte): ?>
                        <tr>
                        <td><?php echo $cont++; ?></td>
                            <td>
                            <?php  
                                                   
                              echo isset($index) 
                              ? htmlspecialchars($index) 
                              : 'Pedido no disponible'; 
                             
                            ?>
                            </td>
                            <td>
                                <?php 
                                echo isset($reporte) 
                                    ? htmlspecialchars($reporte) 
                                    : 'Pedido no disponible'; 
                                   
                                ?>
                            </td>
                           
                           
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">No hay registros.</td>
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
