<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/RopayMedia/vendor/autoload.php";

class Conexion {
    public function conectar() {
        try {
            $servidor = "127.0.0.1";
            
            $usuario = "";
            $password = "";
            //Estasa son tus credenciales jouse
            //$usuario = "root";
            //$password = "132456";

            $baseDatos = "RopayMedia";
            $puerto = "27017";

            $cadenaConexion = "mongodb://127.0.0.1:27017/" . $baseDatos;
            //$cadenaConexion = "mongodb://127.0.0.1:27017/" . $baseDatos;

            $cliente = new MongoDB\Client($cadenaConexion);
            $db = $cliente->selectDatabase($baseDatos);

            return $db;  //retornar la base de datos conectada

        } catch (\Throwable $th) {
            echo "Error: " . $th->getMessage();
            return null;  //retornar null en caso de error
        }
    }
}
?>
