<?php 
    session_start();
    require_once $_SERVER['DOCUMENT_ROOT'] . "/RopayMedia/app/Controller/PedidoController/pedidoController.php";
    include_once '../layout.php';

    if (!isset($_GET['id_pedido'])) {
        header("Location: ../listaPedidosEnValidacion.php");
        exit;
    }

    $pedidoController = new pedidoController();
    $pedido = $pedidoController->mostrarPedido($_GET['id_pedido']);

    if (!$pedido) {
        echo "<p class='text-center text-danger'>El pedido no existe o no se pudo cargar.</p>";
        exit;
    }
?>

<!DOCTYPE html>
<html>

    <?php HeadCSS(); ?>

    <body class="d-flex flex-column min-vh-100">
        <?php MostrarNav(); MostrarMenu(); ?>

        <div class="container mt-5 mb-8">
            <h1 class="text-center mb-4">Pedido #<?php echo htmlspecialchars($pedido['id_pedido']); ?></h1>

            <!-- Información del cliente -->
            <div class="card mb-4 shadow-sm">
                <div class="card-body">
                    <h3 class="card-title">Información del Cliente</h3>
                    <p><strong>Nombre:</strong> <?php echo htmlspecialchars($pedido['nombre_completo']); ?></p>
                    <p><strong>Teléfono:</strong> <?php echo htmlspecialchars($pedido['telefono']); ?></p>
                    <p><strong>Correo:</strong> <?php echo htmlspecialchars($pedido['correo']); ?></p>
                </div>
            </div>

            <!-- Productos del pedido -->
            <div class="card mb-4 shadow-sm">
                <div class="card-body">
                    <h3 class="card-title">Productos</h3>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Imagen</th>
                                <th>Producto</th>
                                <th>Precio</th>
                                <th>Cantidad</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pedido['productos'] as $producto): ?>
                                <tr>
                                    <td>
                                        <img src="<?php echo htmlspecialchars($producto['img']); ?>" 
                                            alt="<?php echo htmlspecialchars($producto['nombre']); ?>" 
                                            style="width: 50px; height: 50px; object-fit: cover;">
                                    </td>
                                    <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                                    <td>₡<?php echo number_format($producto['precio'], 2); ?></td>
                                    <td><?php echo htmlspecialchars($producto['cantidad']); ?></td>
                                    <td>₡<?php echo number_format($producto['sub_total'], 2); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Detalles del pedido -->
            <div class="card mb-4 shadow-sm">
                <div class="card-body">
                    <h3 class="card-title">Detalles del Pedido</h3>
                    <p><strong>Fecha:</strong> <?php echo htmlspecialchars($pedido['fecha']); ?></p>
                    <p><strong>Método de Retiro:</strong> <?php echo htmlspecialchars($pedido['metodo_retiro']); ?></p>
                    <p><strong>Dirección:</strong> <?php echo htmlspecialchars($pedido['direccion']); ?></p>
                    <p><strong>Total:</strong> ₡<?php echo number_format($pedido['total'], 2); ?></p>
                </div>
            </div>


            <!-- Sinpe Img -->
            <div class="card mb-4 shadow-sm">
                <div class="card-body">
                <p class="mt-2 text-center">Imagen Sipe: <img src="/RopayMedia/app/View/uploaded_img/sinpes/<?php echo htmlspecialchars($pedido['img_sinpe']); ?>"  alt="sinpe" style="width: 300px; height: 400px;"></p>
                </div>
            </div>


            <!-- Botones de acción -->
            <div class="text-center">
                <button type="button" 
                        class="btn btn-success btn-lg mx-2" 
                        onclick="confirmacionAceptar( <?php echo htmlspecialchars($pedido['id_pedido']); ?>)">
                    <i class="fa fa-check"></i> Aceptar Pedido
                </button>
                <button type="button" 
                        class="btn btn-danger btn-lg mx-2 mb-10" 
                        onclick="confirmacionRechazar(<?php echo htmlspecialchars($pedido['id_pedido']); ?>)">
                    <i class="fa fa-times"></i> Rechazar Pedido
                </button>
            </div>
        </div>

        <!-- Scripts -->
        <script src="../plugins/jquery/jquery.min.js"></script>
        <script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
        function confirmacionAceptar(id_pedido) {
            Swal.fire({
                title: '¿Estás seguro de que deseas aceptar el pedido?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, aceptar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '../../Controller/PedidoController/pedidoController.php',
                        type: 'POST',
                        data: {
                            action: 'aceptar',
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
                                    window.location.href = '/RopayMedia/app/View/pedidos/listaPedidosEnTienda.php';
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

<script>
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
                                    window.location.href = '/RopayMedia/app/View/pedidos/listaPedidosEnValidacion.php';
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
