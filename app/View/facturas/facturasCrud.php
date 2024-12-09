<?php
    session_start();

    require_once $_SERVER['DOCUMENT_ROOT'] . "/RopayMedia/app/Controller/FacturaController/facturas_controller.php";
    require_once $_SERVER['DOCUMENT_ROOT'] . "/RopayMedia/app/Model/usuarioModel.php";
    include_once '../layout.php';
    $facturaController = new FacturaController();
    $usuarioModel = new usuarioModel();
    $facturaController->manejarAcciones(); 
    $productos = $facturaController->obtenerProductos();
    $usuarios = $usuarioModel->listarUsuarios();

    $mensaje = isset($_SESSION['mensaje']) ? $_SESSION['mensaje'] : '';
    $tipo = isset($_SESSION['tipo']) ? $_SESSION['tipo'] : 'success';
    unset($_SESSION['mensaje'], $_SESSION['tipo']); 

    $idPedido = isset($_SESSION['id_pedido']) ? $_SESSION['id_pedido'] : '';
    if (isset($_POST['productos'])) {
        $productos = json_decode($_POST['productos'], true); // Decodificar a un array asociativo
        if (is_array($productos)) {
            foreach ($productos as $producto) {
                // Procesar cada producto
                error_log("Producto: " . print_r($producto, true));
            }
        } else {
            error_log("El campo 'productos' no contiene un JSON válido.");
        }
    } else {
        error_log("El campo 'productos' no fue enviado en el formulario.");
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

    <div class="container mt-5">
        <h1 class="text-center mb-4">Crear Factura</h1>
        
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Formulario de Creación de Factura</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="facturasCrud.php">
                <input type="hidden" name="id_pedido" value="<?= $idPedido ?>">
                <input type="hidden" id="productos" name="productos" value="[]">
                    <div class="mb-3">
                    <label for="id_usuario" class="form-label">Usuario</label>
                    <select name="id_usuario" id="id_usuario" class="form-control select2" required>
                        <option value="" disabled selected>Seleccione un usuario</option>
                        <?php if (!empty($usuarios) && is_array($usuarios)): ?>
                            <?php foreach ($usuarios as $usuario): ?>
                                <option value="<?= $usuario['_id'] ?>">
                                    <?= $usuario['_id'] ?> - <?= $usuario['nombre'] ?>
                                </option>
                                <?php endforeach; ?>
                                <?php else: ?>
                                    <option value="" disabled>No se encontraron usuarios</option>
                                    <?php endif; ?>
                                </select>
                            </div>                      
                    <div class="mb-3">
                        <label for="producto" class="form-label">Producto</label>
                        <select id="producto" class="form-control" required>
                            <option value="" disabled selected>Seleccione un producto</option>
                            <?php foreach ($productos as $producto): ?>
                                <option value="<?= $producto['id_producto'] ?>" data-nombre="<?= $producto['nombre_producto'] ?>" data-precio="<?= $producto['precio'] ?>">
                                    <?= $producto['nombre_producto'] ?> - $<?= number_format($producto['precio'], 2) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <button type="button" id="agregar-producto" class="btn btn-secondary">Agregar Producto</button>
                    </div>

                    <div class="mb-3">
                        <label for="total" class="form-label">Total</label>
                        <input type="number" step="0.01" name="total" id="total" class="form-control" placeholder="Total" required readonly value="0">
                    </div>

                    <div class="mb-3">
                        <label for="detalle" class="form-label">Detalle</label>
                        <textarea name="detalle" id="detalle" class="form-control" rows="3" placeholder="Ingrese los detalles de la factura (opcional)"></textarea>
                    </div>
                    <button type="submit" name="accion" value="Crear" class="btn btn-success">Crear Factura</button>
                </form>
            </div>
        </div>
    </div>

    <?php MostrarFooter(); ?>
    <script>
    document.getElementById('agregar-producto').addEventListener('click', function() {
        const productoSelect = document.getElementById('producto');
        const productosInput = document.getElementById('productos');
        const totalInput = document.getElementById('total');

        const selectedOption = productoSelect.options[productoSelect.selectedIndex];
        const idProducto = selectedOption.value;
        const nombreProducto = selectedOption.dataset.nombre;
        const precioProducto = parseFloat(selectedOption.dataset.precio);

        Swal.fire({
            title: 'Cantidad de Producto',
            input: 'number',
            inputAttributes: {
                min: 1,
                step: 1
            },
            text: 'Ingrese la cantidad:',
            showCancelButton: true,
            confirmButtonText: 'Agregar',
            cancelButtonText: 'Cancelar',
            preConfirm: (cantidad) => {
                if (!cantidad || cantidad <= 0) {
                    Swal.showValidationMessage('Debe ingresar una cantidad válida');
                    return false;
                }
                return cantidad;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const cantidad = parseInt(result.value, 10);
                const totalProducto = precioProducto * cantidad;
                let productos = [];
                try {
                    productos = JSON.parse(productosInput.value) || [];
                } catch (e) {
                    console.error("Error al parsear productos:", e);
                    productos = [];
                }
                productos.push({ 
                    id_producto: idProducto, 
                    nombre: nombreProducto, 
                    cantidad, 
                    precio: precioProducto 
                });

                // Convertir el array actualizado a JSON y actualizar el input oculto
                productosInput.value = JSON.stringify(productos);

                // Actualizar el total
                const totalActual = parseFloat(totalInput.value) || 0;
                totalInput.value = (totalActual + totalProducto).toFixed(2);
                console.log("Productos para enviar:", productos);
            }
        });
    });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="assets/vendor/jquery/dist/jquery.min.js"></script>
    <script src="assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/js-cookie/js.cookie.js"></script>
    <script src="assets/vendor/jquery.scrollbar/jquery.scrollbar.min.js"></script>
    <script src="assets/vendor/jquery-scroll-lock/dist/jquery-scrollLock.min.js"></script>
    <script src="assets/js/argon.js?v=1.2.0"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>