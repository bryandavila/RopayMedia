<?php 
    session_start();
    require_once $_SERVER['DOCUMENT_ROOT'] . "/RopayMedia/app/Controller/PedidoController/pedidoController.php";
    include_once '../layout.php';
    $pedidoController = new pedidoController();
    $pedidos = $pedidoController->listarPedidosEnValidacion();
?>

<!DOCTYPE html>
<html>

    <?php HeadCSS(); ?>

    <body class="d-flex flex-column min-vh-100">
        <?php MostrarNav(); MostrarMenu(); ?>

        <div class="container mt-5">
            <h1 class="text-center mb-4">Pedidos en Validación</h1>
            <div class="card-body">
            <table id="tablaPedidos" class="table table-hover table-striped table-bordered text-center align-middle">
    <thead class="table-dark">
        <tr>
            <th>ID Pedido</th>
            <th>Fecha</th>
            <th>Cliente</th>
            <th>Método de Retiro</th>
            <th>Ubicación</th>
            <th>Total</th>
            <th>Estado</th>
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
                <td>
                    <span class="text-sm badge bg-<?php echo $pedido['estado'] === 'En validacion' ? 'warning' : 'success'; ?>">
                        <?php echo htmlspecialchars($pedido['estado']); ?>
                    </span>
                </td>
                <td class="d-flex gap-2 justify-content-center">
                    <a href="detallePedido.php?id_pedido=<?php echo urlencode($pedido['id_pedido']); ?>" 
                       class="btn btn-outline-primary btn-sm d-flex align-items-center justify-content-center text-sm ">
                        <i class="fa fa-eye me-2"></i> Ver
                    </a>
                    <button type="button" 
                            class="btn btn-outline-danger btn-sm d-flex align-items-center justify-content-center text-sm "
                            onclick="confirmacionRechazar(<?php echo htmlspecialchars($pedido['id_pedido']); ?>)">
                        <i class="fa fa-trash"></i> Rechazar
                    </button>
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
            });

            function confirmacionRechazar(id_pedido) {
                Swal.fire({
                    title: '¿Estás seguro de que deseas rechazar el pedido?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, rechazar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '../../Controller/PedidoController/pedidoController.php',
                            type: 'POST',
                            data: {
                                action: 'rechazar',
                                id_pedido: id_pedido
                            },
                            
                            success: function(response) {
                                console.log(response); 
                                var result = JSON.parse(response);
                                console.log(result); 
                                if (result.success) {
                                    Swal.fire({
                                        title: 'Éxito',
                                        text: result.message,
                                        icon: 'success'
                                    }).then(() => {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        title: 'Error',
                                        text: result.message,
                                        icon: 'error'
                                    });
                                }
                            }
                        });
                    }
                });
            }

        </script>
    </body>
</html>