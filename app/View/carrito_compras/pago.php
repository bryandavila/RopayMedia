<?php 
    include_once '../layout.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . "/RopayMedia/app/Controller/CarritoComprasController/carrito_controller.php";

    if (!isset($_SESSION['carrito'])) {
        $_SESSION['carrito'] = [];
    }

    $productosCarrito = $_SESSION['carrito'];

    $total = 0; 
    foreach ($_SESSION['carrito'] as $producto) {
        $total += $producto['sub_total']; 
    }
?>

<!DOCTYPE html>
<html>
    <?php HeadCSS();?>

    <body class="d-flex flex-column min-vh-100">

    <?php 
        MostrarNav();
        MostrarMenu();
    ?>

    <div class="container mt-4">
        <a href="../home/home.php" class="btn btn-light mb-4">
            <i class="fa fa-arrow-left"></i> Seguir comprando
        </a>
        <h3>Carrito de Compras</h3>
        <table class="table">
            <thead class="text-center">
                <tr>
                    <th>Img</th>
                    <th>Producto</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Subtotal</th>
                    <th>Eliminar</th>
                </tr>
            </thead>
            <tbody class="text-center">
                <?php foreach ($productosCarrito as $producto): ?>
                    <tr>
                        <td>
                            <img src="<?php echo htmlspecialchars($producto['img']); ?>" 
                                alt="<?php echo htmlspecialchars($producto['nombre']); ?>" 
                                style="width: 50px; height: 50px; object-fit: cover;">
                        </td>
                        <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                        <td>₡<?php echo number_format($producto['precio'], 2); ?></td>
                        <td>
                            <div class="d-flex align-items-center justify-content-between">
                                <form method="post" action="/RopayMedia/app/Controller/CarritoComprasController/carrito_controller.php?action=restar">
                                    <input type="hidden" name="id_producto" value="<?php echo $producto['id_producto']; ?>">
                                    <button type="submit" class="btn btn-sm btn-primary w-100">-</button>
                                </form> 
                                <span class="mx-1"><?php echo $producto['cantidad']; ?></span>
                                <form method="post" action="/RopayMedia/app/Controller/CarritoComprasController/carrito_controller.php?action=sumar">
                                        <input type="hidden" name="id_producto" value="<?php echo $producto['id_producto']; ?>">
                                    <button type="submit" class="btn btn-sm btn-success w-100">+</button>
                                </form> 
                            </div>
                        </td>
                        <td>₡<?php echo number_format($producto['sub_total'], 2); ?></td>
                        <td>
                            <button type="button" class="btn btn-sm btn-danger"
                                onclick="confirmarEliminacion(<?php echo urlencode($producto['id_producto']); ?>)">
                                <i class="fa fa-trash"></i>
                            </button>
                    </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <h2>Total: <?php echo($total)?></h2>
        <div class="d-flex justify-content-between mt-3">
            <?php if ($total > 0): ?>
                <a href="../carrito_compras/pago_final.php" class="btn btn-primary">Confirmar Compra</a>
            <?php else: ?>
                <a href="#" 
                class="btn btn-primary" 
                onclick="event.preventDefault(); mostrarMensaje();">Confirmar Compra</a>
            <?php endif; ?>
        </div>
    </div>

    <?php MostrarFooter(); ?>

    <script src="../plugins/jquery/jquery.min.js"></script>
    <script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../dist/js/adminlte.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            <?php if (isset($_SESSION['mensaje'])): ?>
                Swal.fire({
                    title: 'Error',
                    text: '<?php echo $_SESSION['mensaje']; ?>',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                <?php unset($_SESSION['mensaje']); ?>
            <?php endif; ?>
        });
    </script>

    <script>
        function mostrarMensaje() {
            Swal.fire({
                title: 'Carrito vacío',
                text: 'No puedes confirmar la compra porque tu carrito está vacío.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    </script>

    <script>
        function confirmarEliminacion(id_producto) {
            Swal.fire({
                title: '¿Estás seguro de que deseas eliminar este producto del carrito?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '../../Controller/CarritoComprasController/carrito_controller.php',
                        type: 'POST',
                        data: {
                            action: 'elimarProducto',
                            id_producto: id_producto
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
