<?php
    include_once $_SERVER['DOCUMENT_ROOT'] . "/RopayMedia/app/Model/carrito_comprasModel.php";

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['carrito'])) {
        $_SESSION['carrito'] = [];
    }

    class pago_controller
    {
        public static function finalizarCompra()
        {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $id_usuario = $_SESSION['id_usuario'];
                $metodo_retiro = $_POST['metodo_retiro'];
                $direccion = $_POST['direccion'];
                $img_sinpe = null;
                
                if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
                    $temp_name = $_FILES['image']['tmp_name'];
                    $file_name = basename($_FILES['image']['name']);
                    $file_name = preg_replace('/[^a-zA-Z0-9_\-\.]/', '_', $file_name); 
                    $upload_dir = $_SERVER['DOCUMENT_ROOT'] . '/RopayMedia/app/View/uploaded_img/sinpes/';
                    $file_type = mime_content_type($temp_name);
                    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
                    if (!is_dir($upload_dir)) {
                        mkdir($upload_dir, 0777, true);
                    }
                    if (!in_array($file_type, $allowed_types)) {
                        echo json_encode(['success' => false, 'message' => 'Tipo de archivo de img no permitido.']);
                        return;
                    }
                    $target_file = $upload_dir . $file_name;
                    if (move_uploaded_file($temp_name, $target_file)) {
                        $img_sinpe = $file_name;
                    } else {
                        echo json_encode(['success' => false, 'message' => 'Error al subir la imagen.']);
                        return;
                    }
                }

                $productos = [];
                $total = 0;

                if (!empty($_SESSION['carrito']) && is_array($_SESSION['carrito'])) {
                    foreach ($_SESSION['carrito'] as $producto) {
                        $productos[] = $producto;
                        $total += $producto['sub_total']; 
                    }
                } else {
                    echo json_encode(['success' => false, 'message' => 'El carrito está vacío.']);
                    return;
                }
        
                $pedido = new carrito_comprasModel();
                $resultado = $pedido->crearPedido($id_usuario, $metodo_retiro, $direccion, $productos, $total, $img_sinpe);
                if ($resultado) {
                    echo json_encode(['success' => true, 'message' => 'Pedido realizado exitosamente.']);
                } 
               
            }
        }
    }

    if (isset($_REQUEST['action']) && $_REQUEST['action'] === 'finalizarCompra') {
        pago_controller::finalizarCompra();
        exit(); 
    }     

?>