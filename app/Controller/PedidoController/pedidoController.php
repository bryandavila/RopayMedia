<?php
     include_once $_SERVER['DOCUMENT_ROOT'] . "/RopayMedia/app/Model/pedidoModel.php";

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    class pedidoController {
        private $pedidoModel;

        public function __construct() {
            $this->pedidoModel = new pedidoModel();
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
        }

        public function listarPedidosEnValidacion() {
            $estado = "En validacion";
            return $this->pedidoModel->listaPedidos($estado);
        }

        public function listarPedidosEnTienda() {
            $estado = "Aprobado";
            $ubicacion = "En la tienda";
            return $this->pedidoModel->listaPedidosXUbicacion($ubicacion, $estado);
        }

        public function listarPedidosEnProgreso() {
            $estado = "Aprobado";
            $ubicacion = "En correos de CR";
            return $this->pedidoModel->listaPedidosXUbicacion($ubicacion, $estado);
        }

        public function listarPedidosEntregados() {
            $estado = "Aprobado";
            $ubicacion = "Entregado al cliente";
            return $this->pedidoModel->listaPedidosXUbicacion($ubicacion, $estado);
        }

        public function mostrarPedido($id_pedido) {
            $pedido = $this->pedidoModel->infoPedido($id_pedido);
            if (empty($pedido)) {
                return null; 
            }
            return $pedido;
        }

        public function rechazarPedido($id_pedido) {
            $pedido = $this->pedidoModel->rechazarPedido($id_pedido);
            if ($pedido) {
                echo json_encode(['success' => true, 'message' => $pedido]);
            } else {
                echo json_encode(['success' => false, 'message' => $pedido]);
            }
        }

        public function aceptarPedido($id_pedido) {
            $pedido = $this->pedidoModel->aceptarPedido($id_pedido);
            if ($pedido) {
                echo json_encode(['success' => true, 'message' => "Pedido aceptado"]);
            } else {
                echo json_encode(['success' => false, 'message' => "Error al aceptar el pedido"]);
            }
        }

        public function actualizarPedido($id_pedido, $ubicacion){
            $pedido = $this->pedidoModel->cambiarUbicacionPedido($id_pedido, $ubicacion);
            if ($pedido) {
                $_SESSION['mensaje'] = "Pedido actualizado"; "Pedido actualizado";
            } else {
                $_SESSION['mensaje'] = "Error al actualizar el pedido";
            }
        }
        
        
    }

    $controller = new pedidoController();

    if (isset($_REQUEST['action'])) {
        $action = $_REQUEST['action'];
        switch ($action) {
            case 'rechazar':
                if (isset($_POST['id_pedido'])) {
                    $controller->rechazarPedido(intval($_POST['id_pedido']));
                } else {
                    echo json_encode(['success' => false, 'message' => 'ID del pedido no proporcionado.']);
                }
                break;
            case 'aceptar':
                if (isset($_POST['id_pedido'])) {
                    $controller->aceptarPedido(intval($_POST['id_pedido']));
                } else {
                    echo json_encode(['success' => false, 'message' => 'ID del pedido no proporcionado.']);
                }
                break;
            case 'Actualizar':
                if (isset($_POST['id_pedido'], $_POST['ubicacion'])) {
                    $controller->actualizarPedido(intval($_POST['id_pedido']), $_POST['ubicacion']);
                    header("Location: /RopayMedia/app/View/pedidos/listaPedidosEnTienda.php");
                    exit;
                } else {
                    echo "Error: ID del pedido o ubicación no proporcionados.";
                }
                break;
            default:
                echo json_encode(['success' => false, 'message' => 'Acción no reconocida.']);
        }
    }
?>
