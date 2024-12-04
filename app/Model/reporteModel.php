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
                $facturas = $facturasCollection->find(['id_cliente'=>$id_usuario]); 
                
                $listaFacturas = [];
                foreach ($facturas as $factura) {
                    $fechaEmision = null;
                    if (isset($factura['fecha_emision']) && !empty($factura['fecha_emision'])) {
                        $fechaEmision = $factura['fecha_emision']; // Mantén el formato como string
                    } else {
                        $fechaEmision = null; 
                    }
                   

                    $productosDetalles = [];

                if (isset($factura['productos']) && $factura['productos'] instanceof MongoDB\Model\BSONArray) {
                   
                    $productosIDs = $factura['productos']->getArrayCopy();

                    $productosCollection = $db->productos; 
                    $productos = $productosCollection->find(['id_producto' => ['$in' => $productosIDs]]);
                    
                    foreach ($productos as $producto) {
                        $productosDetalles[] = [
                            '' => $producto['nombre_producto'] ?? 'Sin nombre',
                        ];
                    }
                
                     $producto = json_encode($productosDetalles);
                     $producto = preg_replace( ['/:/','/\[|\]/', '/\{/', '/\}/', '/","/', '/"/', '/, /'], 
                     ['','', '', '', "\n", '', ', '],$producto);
                     $listaFacturas[] = [
                        'id_factura' => isset($factura['id_factura']) ? (int)$factura['id_factura'] : null,
                        'id_pedido' => isset($factura['id_pedido']) ? (int)$factura['id_pedido'] : null, // Verificar existencia
                        'id_cliente' => isset($factura['id_cliente']) ? (int)$factura['id_cliente'] : null, // Verificar existencia
                       'productos' =>  nl2br($producto),
                        'total' => isset($factura['total']) ? (float)$factura['total'] : null, // Verificar existencia
                        'fecha_emision' => $fechaEmision,
                        'detalle' => isset($factura['detalle']) ? $factura['detalle'] : null // Verificar existencia
                    ];
                }
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
                    $usuarios = $usuariosCollection->find(['_id' => $factura['id_cliente']]); 
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
