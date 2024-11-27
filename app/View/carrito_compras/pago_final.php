<?php 
    include_once '../layout.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . "/RopayMedia/app/Controller/CarritoComprasController/pago_controller.php";

    if (!isset($_SESSION['carrito'])) {
        $_SESSION['carrito'] = [];
    }

    if (!isset($_SESSION['nombre'])) {
        header("Location: ../auth/login.php");
        exit();
    }

    $productosCarrito = $_SESSION['carrito'];

    $total = 0; 
    foreach ($_SESSION['carrito'] as $producto) {
        $total += $producto['sub_total']; 
    }
?>

<!DOCTYPE html>
<html>
<?php HeadCSS(); ?>

<body class="d-flex flex-column min-vh-100">

    <?php 
        MostrarNav();
        MostrarMenu();
    ?>

    <div class="container mt-4">
        <a href="../carrito_compras/pago.php" class="btn btn-light mb-4">
            <i class="fa fa-arrow-left"></i> Regresar
        </a>
        
        <h3>Confirmación de compra</h3>
        <table class="table">
            <thead class="text-center">
                <tr>
                    <th>Img</th>
                    <th>Producto</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Subtotal</th>
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
                            <span class="mx-1"><?php echo $producto['cantidad']; ?></span>
                        </td>
                        <td>₡<?php echo number_format($producto['sub_total'], 2); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <h1 class="mt-2">Total: <?php echo($total)?></h1>
    </div> 
    <form id="finalizarCompra" enctype="multipart/form-data" method="post" 
        class="mt-4 p-4 rounded shadow bg-light mx-auto" style="max-width: 600px;">
        <h4 class="text-center mb-4">Detalles de tu pedido</h4>
        <div class="mb-3">
            <label for="metodo_retiro" class="form-label">Método de retiro del pedido:</label>
            <select id="metodo_retiro" name="metodo_retiro" class="form-select" required>
                <option value="" disabled selected>Seleccione una opción</option>
                <option value="domicilio">A domicilio</option>
                <option value="tienda">En la tienda</option>
            </select>
        </div>
        <div id="direccion_div" class="mb-3" style="display: none;">
            <label for="direccion" class="form-label">Dirección completa:</label>
            <textarea id="direccion" name="direccion" class="form-control" rows="3" placeholder="Provincia, Cantón, Distrito, Número de casa..."></textarea>
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">Comprobante del SINPE:</label>
            <input type="file" id="image" name="image" class="form-control" required>
            <small class="text-muted">Sube un archivo que respalde tu pago.</small>
        </div>
        <div class="d-grid">
            <button type="button" class="btn btn-primary btn-lg" id="saveBtn">
                <i class="fa fa-shopping-cart"></i> Realizar pedido
            </button>
        </div>
    </form>


    <?php MostrarFooter(); ?>

    <script src="../plugins/jquery/jquery.min.js"></script>
    <script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../dist/js/adminlte.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            $('#saveBtn').on('click', function(e) {
                e.preventDefault();

                var formData = new FormData($('#finalizarCompra')[0]);

                $.ajax({
                    url: '/RopayMedia/app/Controller/CarritoComprasController/pago_controller.php?action=finalizarCompra',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        try {
                            var data = JSON.parse(response);
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Éxito',
                                    text: data.message,
                                }).then(() => {
                                    window.location.href = 'compra_exitosa.php'; 
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: data.message,
                                });
                            }
                        } catch (e) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error de respuesta',
                                text: 'La respuesta no es un JSON válido: ' + response,
                            });
                            console.error("Respuesta del servidor:", response);
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Hubo un error en la solicitud.',
                        });
                    }
                });
            });
        });
    </script>

    <script>
        document.getElementById('metodo_retiro').addEventListener('change', function() {
            const metodo = this.value;
            const direccionDiv = document.getElementById('direccion_div');
            if (metodo === 'domicilio') {
                direccionDiv.style.display = 'block'; 
                document.getElementById('direccion').required = true; 
            } else if (metodo === 'tienda') {
                direccionDiv.style.display = 'none'; 
                document.getElementById('direccion').required = false; 
            }
        });
    </script>


</body>

</html>
