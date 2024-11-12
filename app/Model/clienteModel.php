<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . "/RopayMedia/vendor/autoload.php";
    require_once $_SERVER['DOCUMENT_ROOT'] . "/RopayMedia/app/Model/baseDatosModel.php";

    class clienteModel {
        private $conexion;

        public function __construct() {
            $this->conexion = new Conexion();  //Crear la conexión
        }

        //Restos de codigo aqui
        
    }
?>
