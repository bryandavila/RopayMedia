<?php 
    session_start();
    require_once $_SERVER['DOCUMENT_ROOT'] . "/RopayMedia/app/Controller/PedidoController/pedidoController.php";
    include_once '../layout.php';
    $pedidoController = new pedidoController();
    $pedidos = $pedidoController->listarPedidosEntregados();
?>

<!DOCTYPE html>
<html>

    <?php HeadCSS(); ?>

    <body class="d-flex flex-column min-vh-100">
        <?php MostrarNav(); MostrarMenu(); ?>

        <div class="container mt-5">
            <h1 class="text-center mb-4">Pedidos en la Tienda</h1>
            <div class="card-body">
                <table id="tablaPedidos" class="table table-hover table-striped table-bordered text-center align-middle">
                    <thead class="table-success">
                        <tr>
                            <th>ID Pedido</th>
                            <th>Fecha</th>
                            <th>Cliente</th>
                            <th>Método de Retiro</th>
                            <th>Ubicación</th>
                            <th>Total</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pedidos as $pedido): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($pedido['id_pedido']); ?></td>
                                <td><?php echo htmlspecialchars($pedido['fecha']); ?></td>
                                <td><?php echo htmlspecialchars($pedido['cliente']); ?></td>
                                <td><?php echo htmlspecialchars($pedido['metodo_retiro']); ?></td>
                                <td><?php echo htmlspecialchars($pedido['ubicacion_pedido']); ?></td>
                                <td><?php echo htmlspecialchars(number_format($pedido['total'], 2)); ?></td>
                                <td class="d-flex gap-2 justify-content-center">
                                    <a href="verInfoPedido.php?id_pedido=<?php echo urlencode($pedido['id_pedido']); ?>" 
                                        class="btn btn-outline-primary btn-sm d-flex align-items-center justify-content-center text-sm ">
                                        <i class="fa fa-eye me-2"></i> Ver
                                    </a>
                                    <a href="actualizarPedido.php?id_pedido=<?php echo urlencode($pedido['id_pedido']); ?>" 
                                        class="btn btn-outline-primary btn-sm d-flex align-items-center justify-content-center text-sm ">
                                        <i class="fa fa-pencil-alt me-2"></i> Actualizar
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Scripts -->
        <script src="../plugins/jquery/jquery.min.js"></script>
        <script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="../dist/js/adminlte.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

        <script>
            $(document).ready(function() {
                // Inicializar DataTable
                $('#tablaPedidos').DataTable({
                    language: {
                        url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                    }
                });
                <?php if (isset($_SESSION['mensaje'])): ?>
                    Swal.fire({
                        icon: 'success',
                        title: 'Éxito',
                        text: '<?php echo $_SESSION['mensaje']; ?>',
                    });
                    <?php unset($_SESSION['mensaje']); ?>
                <?php endif; ?>
            });
        </script>
    </body>
</html>