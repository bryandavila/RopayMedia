<?php
include_once '../../Model/reporteModel.php';



  
class reporteController {
    public static function obtenerComprasUsuario(){
        $id_usuario = $_SESSION['id_usuario'];
        print_r($_SESSION);

        if($id_usuario!=null){
            $reporteModel = new reporteModel();          
            $facturas = reporteModel->obtenerComprasUsuario($id_usuario);
        }
        if (isset($facturas['error_message'])) {
        $error_message = $facturas['error_message'];
        $facturas = [];

        }}
//foreach ($facturas as &$factura) {
 //   $productos = obtenerProductosFactura($factura['ID_FACTURA']);
 //   if (isset($productos['error_message'])) {
 //       $error_message = $productos['error_message'];
 //       $factura['productos'] = [];
  //  } else {
 //       $factura['productos'] = $productos;
 //   }
//}
}
?>
