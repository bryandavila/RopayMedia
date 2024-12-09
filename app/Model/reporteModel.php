<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . "/RopayMedia/vendor/autoload.php";
    require_once $_SERVER['DOCUMENT_ROOT'] . "/RopayMedia/app/Model/baseDatosModel.php";

    class reporteModel {
        private $conexion;

        public function __construct() {
            $this->conexion = new Conexion();  //Crear la conexión
        }

        public function obtenerComprasUsuario($id_usuario) {
            try {
                $db = $this->conexion->conectar();
                if ($db === null) {
                    return [];
                }
        
                $facturasCollection = $db->facturas;
                $facturas = $facturasCollection->find(['id_usuario' => (int)$id_usuario]);
        
                $listaFacturas = [];
                foreach ($facturas as $factura) {
                    $listaFacturas[] = [
                        'id_factura' => $factura['id_factura'] ?? null,
                        'id_pedido' => $factura['id_pedido'] ?? null,
                        'id_cliente' => $factura['id_usuario'] ?? null,
                        'productos' => $factura['productos'] ?? [],
                        'total' => $factura['total'] ?? 0,
                        'fecha_emision' => $factura['fecha_emision'] ?? null,
                        'detalle' => $factura['detalle'] ?? null,
                    ];
                }
        
                return $listaFacturas;
        
            } catch (\Exception $e) {
                return [];
            }
        }
        

        public function gananciasmesuales(){
            try{
                $db = $this->conexion->conectar();
                if ($db === null) {
                    return [];
                }
                
                $facturasCollection = $db->facturas; 
                $facturas = $facturasCollection->find(); 
               
                
                $listames = [];
                foreach ($facturas as $factura) {
                    $fechaEmision = null;
                    if (isset($factura['fecha_emision']) && !empty($factura['fecha_emision'])) {
                        $fechaEmision = new DateTime($factura['fecha_emision']);
                        $anomes = $fechaEmision->format('Y-m');
                        
                        
                    } else {
                        $fechaEmision = null; 
                    }   
                               

                    
                     if (array_key_exists($anomes, $listames)){
                        $listames [$anomes] += (float)$factura['total'];

                     }else{
                        
                        $listames [$anomes]= (float)$factura['total'];
                        
                     }
                     
                                           
                }
            
                return $listames;

            }catch(\Exception $e){
                return [];
            }
        }


        public function top10productos(){
            try{
                $db = $this->conexion->conectar();
                if ($db === null) {
                    return [];
                }
               
                $facturasCollection = $db->facturas; 
                $facturas = $facturasCollection->find();
                               
                $productosDetalles = [];   
                
                foreach ($facturas as $factura) {
                    $pedidosCollection = $db->pedidos; 
                    $pedidos = $pedidosCollection->find(['id_pedido' => $factura['id_pedido']]);
                   
                    foreach ($pedidos as $pedido) {
                    
                        foreach ($pedido['productos'] as $producto) {
                          $Producton= $producto->nombre . PHP_EOL;
                          $cantidadn= $producto->cantidad . PHP_EOL;
                           
                            if (array_key_exists($Producton, $productosDetalles)){
                                $productosDetalles [$Producton] += $cantidadn;
        
                             }else{
                                
                                $productosDetalles [$Producton]= $cantidadn;                            
                             }
                        }
                       
                    }
                }
              
              $productosDetalles = array_slice($productosDetalles, 0, 10);  
            return $productosDetalles;

            }catch(\Exception $e){
                return [];
            }
        }


        public function top10clientes(){
            try{
                $db = $this->conexion->conectar();
                if ($db === null) {
                    return [];
                }
               
                $facturasCollection = $db->facturas; 
                $facturas = $facturasCollection->find();
                                
                $clienteDetalles = [];
                foreach ($facturas as $factura) {         
                    $usuariosCollection = $db->usuarios; 
                    $usuarios = $usuariosCollection->find(['_id' => $factura['id_usuario']]); 
                    foreach ($usuarios as $usuario) {  

                            if (array_key_exists($usuario['correo'], $clienteDetalles)){
                                $clienteDetalles [$usuario['correo']] += $factura['total'];
        
                             }else{
                                
                                $clienteDetalles [$usuario['correo']] = $factura['total'];                          
                             }
                        
                       
                            }
                }
              
              $clienteDetalles = array_slice($clienteDetalles, 0, 10);  
            return $clienteDetalles;

            }catch(\Exception $e){
                return [];
            }
        }

    }
?>
