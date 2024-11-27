<?php
session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . "/RopayMedia/app/Controller/FacturaController/facturas_controller.php";
include_once '../layout.php';
$facturaController = new FacturaController();
$facturaController->manejarAcciones(); 
$productos = $facturaController->obtenerProductos();
$mensaje = isset($_SESSION['mensaje']) ? $_SESSION['mensaje'] : '';
$tipo = isset($_SESSION['tipo']) ? $_SESSION['tipo'] : 'success';
unset($_SESSION['mensaje'], $_SESSION['tipo']); 

// Verificar si el formulario ha sido enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if ($_SESSION['mensaje'] = "Faltan datos para crear la factura.") {

    } else {
        $_SESSION['mensaje'] = "Faltan datos para crear la factura.";
        $_SESSION['tipo'] = "error";
    }
}

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
    <h1 class="text-center mb-4">Crear Factura</h1>
    
    <!-- Formulario para crear  facturas -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Formulario de Creación de Factura</h4>
        </div>
        <div class="card-body">
            <form method="POST" action="facturasCrud.php">
                <div class="mb-3">
                    <label for="id_cliente" class="form-label">Cliente</label>
                    <input type="number" name="id_cliente" id="id_cliente" class="form-control" placeholder="Ingrese el ID del cliente" required>
                </div>
                <div class="mb-3">
                    <label for="id_pedido" class="form-label">Numero de Pedido</label>
                    <input type="number" name="id_pedido" id="id_pedido" class="form-control" placeholder="Ingrese el ID del pedido" required>
                </div>
                <div class="mb-3">
                    <label for="productos" class="form-label">Productos</label>
                    <select name="productos[]" id="productos" class="form-control" multiple="multiple" required>
    <?php foreach ($productos as $producto): ?>
        <option value="<?php echo $producto['id_producto']; ?>" 
                data-producto="<?php echo $producto['nombre_producto']; ?>"
                data-precio="<?php echo $producto['precio']; ?>">
            <?php echo isset($producto['nombre_producto']) ? $producto['nombre_producto'] : 'Producto no disponible'; ?>
        </option>
    <?php endforeach; ?>
</select>
                </div>
                <div class="mb-3">
    <label for="detalle" class="form-label">Detalle</label>
    <textarea name="detalle" id="detalle" class="form-control" rows="3" placeholder="Ingrese los detalles de la factura (opcional)"></textarea>
</div>
                <div class="mb-3">
                    <label for="total" class="form-label">Total</label>
                    <input type="number" step="0.01" name="total" id="total" class="form-control" placeholder="Ingrese el total" required readonly>
                </div>

                <div class="mb-3">
                    <label for="fecha_factura" class="form-label">Fecha de la factura</label>
                    <input type="date" name="fecha_factura" id="fecha_factura" class="form-control" required>
                </div>
                <button type="submit" name="accion" value="Crear" class="btn btn-success">Crear Factura</button>
             </form>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Añadir evento al seleccionar productos
    document.getElementById('productos').addEventListener('change', function(e) {
        // Iterar sobre todas las opciones seleccionadas
        Array.from(e.target.selectedOptions).forEach(function(option) {
            const productoId = option.value;
            const productoNombre = option.getAttribute('data-producto');
            const precioProducto = parseFloat(option.getAttribute('data-precio'));
            
            // Mostrar la alerta de confirmación
            Swal.fire({
                title: `¿Desea agregar ${productoNombre} a la factura?`,
                showCancelButton: true,
                confirmButtonText: 'Sí',
                cancelButtonText: 'No',
                icon: 'question'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire('Producto agregado a la factura', '', 'success');
                    // Actualizar el total
                    let total = parseFloat(document.getElementById('total').value) || 0;
                    total += precioProducto;
                    document.getElementById('total').value = total.toFixed(2);
                } else {
                    option.selected = false;
                }
            });
        });
    });
</script>

<script>
    // Al enviar el formulario, actualizar el campo 'total' con el valor calculado
    document.querySelector('#facturaForm').addEventListener('submit', function(e) {
        const totalField = document.getElementById('total');
        let total = 0;

        // Iterar sobre todas las opciones seleccionadas
        Array.from(document.getElementById('productos').selectedOptions).forEach(function(option) {
            const precio = parseFloat(option.getAttribute('data-precio'));
            if (!isNaN(precio)) {
                total += precio;
            }
        });
        totalField.value = total.toFixed(2);
    });
</script>


<script src="assets/vendor/jquery/dist/jquery.min.js"></script>
<script src="assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/js-cookie/js.cookie.js"></script>
<script src="assets/vendor/jquery.scrollbar/jquery.scrollbar.min.js"></script>
<script src="assets/vendor/jquery-scroll-lock/dist/jquery-scrollLock.min.js"></script>
<script src="assets/js/argon.js?v=1.2.0"></script>
</body>
</html>
</body>
</html>