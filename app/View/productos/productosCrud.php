<?php 
require_once $_SERVER['DOCUMENT_ROOT'] . "/RopayMedia/app/Controller/ProductoController/productoController.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/RopayMedia/app/Controller/CategoriaController/categoriaController.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/RopayMedia/app/Model/productoModel.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/RopayMedia/app/Model/categoriaModel.php";
include_once '../layout.php';

$productoController = new ProductoController();
$categoriaController = new CategoriaController();

if (isset($_GET['id_producto']) && !empty($_GET['id_producto'])) {
    $productoId = $_GET['id_producto'];
    $producto = $productoController->buscarProductoPorId($productoId);
} else {
    $producto = null; // Si no hay ID, no buscar el producto
}

$productoController->manejarAcciones(); // Manejar acciones de crear, actualizar o eliminar productos

$productos = $productoController->listarProductos(); // Listar los productos existentes
$categorias = $categoriaController->listarCategorias(); // Listar las categorías existentes

$mensaje = isset($_SESSION['mensaje']) ? $_SESSION['mensaje'] : '';
$tipo = isset($_SESSION['tipo']) ? $_SESSION['tipo'] : 'success';
unset($_SESSION['mensaje'], $_SESSION['tipo']); // Limpiar el mensaje de la sesión después de mostrarlo
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
    <h1 class="text-center mb-4">Administrar productos</h1>
    
    <!-- Formulario para crear o actualizar productos -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Formulario de productos</h4>
        </div>
        <div class="card-body">
            <form method="POST" action="productosCrud.php" id="productoForm">
                <div class="mb-3">
                    <label for="id_producto" class="form-label">Seleccione el producto (solo para actualizar):</label>
                    <select name="id_producto" id="id_producto" class="form-control">
                        <option value="">Seleccione un producto</option>
                        <?php foreach ($productos as $producto): ?>
                            <option value="<?php echo $producto['id_producto']; ?>">
                                <?php echo htmlspecialchars($producto['nombre_producto']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="nombre_producto" class="form-label">Nombre del producto:</label>
                    <input type="text" name="nombre_producto" id="nombre_producto" class="form-control" placeholder="Ingrese el nombre del producto" required>
                </div>
                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción:</label>
                    <textarea name="descripcion" id="descripcion" class="form-control" rows="3" placeholder="Ingrese la descripción del producto" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="precio" class="form-label">Precio:</label>
                    <input type="number" step="0.01" name="precio" id="precio" class="form-control" placeholder="Ingrese el precio" required>
                </div>
                <div class="mb-3">
                    <label for="stock" class="form-label">Stock:</label>
                    <input type="number" name="stock" id="stock" class="form-control" placeholder="Ingrese la cantidad en stock" required>
                </div>
                <div class="mb-3">
                    <label for="id_categoria" class="form-label">Categoría:</label>
                    <select name="id_categoria" id="id_categoria" class="form-control" required>
                        <option value="">Seleccione una categoría</option>
                        <?php foreach ($categorias as $categoria): ?>
                            <option value="<?php echo $categoria['id_categoria']; ?>">
                                <?php echo htmlspecialchars($categoria['nombre_categoria']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="ruta_imagen" class="form-label">URL de la imagen:</label>
                    <input type="url" name="ruta_imagen" id="ruta_imagen" class="form-control" placeholder="Ingrese la URL de la imagen" required>
                </div>
                <button type="submit" name="accion" value="Crear" class="btn btn-success">Crear producto</button>
                <button type="submit" name="accion" value="Actualizar" class="btn btn-warning">Actualizar producto</button>
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

<script src="assets/vendor/jquery/dist/jquery.min.js"></script>
<script src="assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/js-cookie/js.cookie.js"></script>
<script src="assets/vendor/jquery.scrollbar/jquery.scrollbar.min.js"></script>
<script src="assets/vendor/jquery-scroll-lock/dist/jquery-scrollLock.min.js"></script>
<script src="assets/js/argon.js?v=1.2.0"></script>

<script>
    $(document).ready(function() {
        $('#id_producto').change(function() {
            var productoId = $(this).val();
            if (productoId) {
                $.ajax({
                    type: 'GET',
                    url: 'productosCrud.php',
                    data: { id_producto: productoId },
                    success: function(response) {
                        try {
                            var producto = JSON.parse(response);
                            if (producto && producto.id_producto) {
                                $('#nombre_producto').val(producto.nombre_producto);
                                $('#descripcion').val(producto.descripcion);
                                $('#precio').val(producto.precio);
                                $('#stock').val(producto.stock);
                                $('#id_categoria').val(producto.id_categoria);
                                $('#ruta_imagen').val(producto.ruta_imagen);
                            } else {
                                alert('Producto no encontrado');
                            }
                        } catch (e) {
                            console.error('Error al procesar la respuesta del servidor:', e);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error en la solicitud:', error);
                    }
                });
            } else {
                $('#productoForm')[0].reset();
            }
        });
    });
</script>

</body>
</html>
