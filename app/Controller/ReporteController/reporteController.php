<?php
include_once '../../Model/reporteModel.php';



  
class reporteController {
    private $reporteModel;

    public function __construct() {
        $this->reporteModel = new reporteModel();
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function gananciasmesuales() {
        return $this->reporteModel->gananciasmesuales();
    }
    
    public function obtenerComprasUsuario($id) {
        return $this->reporteModel->obtenerComprasUsuario($id);
    }

    public function top10clientes() {
        return $this->reporteModel->top10clientes();
    }
    public function top10productos() {
        return $this->reporteModel->top10productos();
    }
   

   



        

}
?>
