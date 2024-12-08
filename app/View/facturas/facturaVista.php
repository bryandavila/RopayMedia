<?php
session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . "/RopayMedia/app/Model/facturaModel.php";
include_once '../layout.php';

$facturaModel = new FacturaModel();

// Verificar si el parámetro id_factura está presente en la URL
if (isset($_GET['id_factura'])) {
    $id_factura = $_GET['id_factura'];
    // Obtener los detalles de la factura
    $factura = $facturaModel->obtenerFacturaPorId($id_factura);
} else {
    // Si no se proporciona el id, redirigir al listado de facturas
    header('Location: listadoFacturas.php');
    exit();
}

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
    <h1 class="text-center mb-4">Detalles de la Factura</h1>

    <?php if (!empty($mensaje)): ?>
        <div class="alert alert-<?php echo $tipo; ?>" role="alert">
            <?php echo $mensaje; ?>
        </div>
    <?php endif; ?>
    
    <!-- Detalles de la Factura -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Factura #<?php echo htmlspecialchars($factura['id_factura']); ?></h4>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <tbody>
                <tr>
                        <th scope="row">Pedido</th>
                        <td>
                            <?php echo isset($factura['id_pedido']) ? htmlspecialchars((string) $factura['id_pedido']) : 'Pedido no disponible'; ?>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Cliente</th>
                        <td>
                            <?php echo isset($factura['id_usuario']) ? htmlspecialchars((string) $factura['id_usuario']) : 'Cliente no disponible'; ?>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Total</th>
                        <td>
                            <?php echo isset($factura['total']) ? htmlspecialchars(number_format($factura['total'], 2)) : 'Total no disponible'; ?>
                        </td>
                    </tr>
                    <tr>
                    <th scope="row">Detalle</th>
                    <td>
                        <?php echo isset($factura['detalle']) ? htmlspecialchars($factura['detalle']) : 'Detalles no disponibles'; ?>
                     </td>
                     </tr>
                    <tr>
                        <th scope="row">Fecha de Emisión</th>
                        <td>
                            <?php 
                            if (!empty($factura['fecha_emision'])) {
                                $fecha = DateTime::createFromFormat('Y-m-d H:i:s', $factura['fecha_emision']);
                                if ($fecha) {
                                    echo htmlspecialchars($fecha->format('d-m-Y H:i:s'));
                                } else {
                                    echo 'Formato de fecha inválido';
                                }
                            } else {
                                echo 'Fecha no disponible';
                            }
                            ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card mb-4">
    <div class="card-header bg-secondary text-white">
        <h4 class="mb-0">Productos</h4>
    </div>
    <div class="card-body">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php if (isset($factura['productos']) && is_array($factura['productos'])): ?>
                    <?php foreach ($factura['productos'] as $producto): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($producto['cantidad']); ?></td>
                            <td>₡<?php echo number_format($producto['cantidad'] * $producto['precio'], 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3">No hay productos disponibles</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
            <a href="listaFacturas.php" class="btn btn-secondary btn-sm mt-4">Volver al listado</a>

        </div>
    </div>
</>

<?php MostrarFooter(); ?>

<script src="assets/vendor/jquery/dist/jquery.min.js"></script>
<script src="assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/js-cookie/js.cookie.js"></script>
<script src="assets/vendor/jquery.scrollbar/jquery.scrollbar.min.js"></script>
<script src="assets/vendor/jquery-scroll-lock/dist/jquery-scrollLock.min.js"></script>
<script src="assets/js/argon.js?v=1.2.0"></script>
</body>

</html>
