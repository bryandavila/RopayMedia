<?php

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['carrito'])) {
        $_SESSION['carrito'] = [];
    }

    if (!isset($_SESSION['carrito'])) {
        $_SESSION['carrito_cantidad'] = 0;
    }

    class carrito_controller
    {
        public static function agregarAlCarrito()
        {
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_producto'], $_POST['img'], $_POST['nombre'], $_POST['precio'], $_POST['stock'])) {
                $id_producto = intval($_POST['id_producto']);
                $img = htmlspecialchars($_POST['img']);
                $nombre = htmlspecialchars($_POST['nombre']);
                $precio = floatval($_POST['precio']);
                $sub_total = $precio;
                $stock = intval($_POST['stock']);
                $cantidad = isset($_POST['cantidad']) ? intval($_POST['cantidad']) : 1;

                foreach ($_SESSION['carrito'] as &$producto) {
                    if ($producto['id_producto'] === $id_producto) {
                        if ($producto['cantidad'] + $cantidad <= $stock) {
                            $producto['cantidad'] += $cantidad;
                            $producto['sub_total'] = $precio * $producto['cantidad'];
                            $_SESSION['carrito_cantidad'] += 1;
                        } else {
                            $producto['cantidad'] = $stock; 
                            $_SESSION['mensaje'] = "Lo sentimos la cantidad que deseas comprar supera nuestro stock, puedes comprar otros productos o finalizar la compra";
                        }
                        return; 
                    }
                }
                $_SESSION['carrito'][] = [
                    'id_producto' => $id_producto,
                    'img' => $img,
                    'nombre' => $nombre,
                    'precio' => $precio,
                    'cantidad' => $cantidad,
                    'sub_total' => $sub_total,
                    'stock' => $stock
                ];
                $_SESSION['carrito_cantidad'] = array_sum(array_column($_SESSION['carrito'], 'cantidad'));
            }
        }

        public static function sumarCantidad($id_producto)
        {
            foreach ($_SESSION['carrito'] as &$producto) {
                if ($producto['id_producto'] === $id_producto) {
                    if ($producto['cantidad'] < $producto['stock']) {
                        $producto['cantidad']++;
                        $producto['sub_total'] = $producto['precio'] * $producto['cantidad'];
                        $_SESSION['carrito_cantidad'] += 1;
                    } else {
                        $_SESSION['mensaje'] = "Lo sentimos la cantidad que deseas comprar supera nuestro stock, puedes comprar otros productos o finalizar la compra.";
                    }
                    return;
                }
            }
        }

        public static function restarCantidad($id_producto)
        {
            foreach ($_SESSION['carrito'] as $index => &$producto) {
                if ($producto['id_producto'] === $id_producto) {
                    if ($producto['cantidad'] > 1) {
                        $producto['cantidad']--;
                        $producto['sub_total'] = $producto['precio'] * $producto['cantidad'];
                        $_SESSION['carrito_cantidad'] -= 1;
                    } else {
                        unset($_SESSION['carrito'][$index]);
                    }
                    return;
                }
            }
            $_SESSION['carrito'] = array_values($_SESSION['carrito']);
        }

        public static function eliminarDelCarrito($id_producto)
        {
            $response = ['success' => false, 'message' => 'Error al eliminar el Producto.'];
            
            foreach ($_SESSION['carrito'] as $index => &$producto) {
                if ($producto['id_producto'] === $id_producto) {
                    unset($_SESSION['carrito'][$index]);
                    $response = ['success' => true, 'message' => 'Producto eliminado del carrito.'];
                    break; 
                }
            }
            
            $_SESSION['carrito'] = array_values($_SESSION['carrito']);
            $_SESSION['carrito_cantidad'] = array_sum(array_column($_SESSION['carrito'], 'cantidad'));
            echo json_encode($response);
            exit();
        }
        
    }

    if (isset($_REQUEST['action'])) {
        $action = $_REQUEST['action'];
        switch ($action) {
            case 'agregarAlCarrito':
                carrito_controller::agregarAlCarrito();
                break;
            case 'sumar':
                if (isset($_POST['id_producto'])) {
                    carrito_controller::sumarCantidad(intval($_POST['id_producto']));
                }
                break;
            case 'restar':
                if (isset($_POST['id_producto'])) {
                    carrito_controller::restarCantidad(intval($_POST['id_producto']));
                }
                break;
            case 'elimarProducto':
                if (isset($_POST['id_producto'])) {
                    carrito_controller::eliminarDelCarrito(intval($_POST['id_producto']));
                }
                break;
            default:
                $_SESSION['error'] = 'Acción no válida.';
                break;
        }
        header("Location: /RopayMedia/app/View/carrito_compras/pago.php");
        exit();
    }
    

?>
