<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$rol = isset($_SESSION['id_rol']) ? $_SESSION['id_rol'] : 3;

function MostrarMenu()
{
    echo '<div class="main-content" id="panel">
    <nav class="navbar navbar-top navbar-expand navbar-dark bg-primary border-bottom">
      <div class="container-fluid">
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav align-items-center ml-md-auto">
            <li class="nav-item dropdown">
              <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <div class="media align-items-center">';
                if (isset($_SESSION['nombre'])) {
                    echo '
                    <div class="media-body ml-2 d-none d-lg-block" style="display: flex; align-items: center;">
                        <span class="mb-0 text-sm font-weight-bold mr-2">' . htmlspecialchars($_SESSION['nombre']) . '</span>
                    </div>
                        <a href="../auth/logout.php">
                        <img alt="logout" src="https://cdn-icons-png.flaticon.com/512/1432/1432552.png" style="max-width: 30px; height: 30px;">
                    </a>';
                }
                else {
                    echo '
                    <div class="media-body ml-2 d-none d-lg-block">
                        <a href="../auth/login.php" class="nav-link">
                            <span class="nav-link-inner--text">Iniciar Sesión</span>
                        </a>
                    </div>';  
                }
                echo'
                </div>
              </a>
            </li>
          </ul>
        </div>
      </div>
    </nav>';
}

function MostrarNav()
{
    global $rol;
   if($rol == 1 || $rol == 2) {
    echo '
        <nav class="sidenav navbar navbar-vertical fixed-left navbar-expand-xs navbar-light bg-white" id="sidenav-main">
            <div class="scrollbar-inner">
                <div class="sidenav-header align-items-center">
                    <a  href="../home/home.php">
                        <img src="../assets/img/brand/imagen3.png"  style="max-width: 20%; height: auto;"> 
                    </a>
                </div>

                <div class="navbar-inner">
                    <div class="collapse navbar-collapse" id="sidenav-collapse-main">
                        <hr class="my-3">
                        <h6 class="navbar-heading p-0 text-muted">
                            <span class="docs-normal">Productos</span>
                        </h6>
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link" href="../categorias/categorias.php">
                                    <i class="ni ni-bullet-list-67 text-primary"></i>
                                    <span class="nav-link-text">Productos por categorías</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="../productos/productos.php">
                                    <i class="ni ni-box-2 text-primary"></i>
                                    <span class="nav-link-text">Todos los productos</span>
                                </a>
                            </li>';
                            if ($rol == 1) {
                                echo '
                                <li class="nav-item">
                                    <a class="nav-link" href="../productos/productosCrud.php">
                                        <i class="ni ni-box-2 text-primary"></i>
                                        <span class="nav-link-text">Agregar un producto</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="../productos/listaProductos.php">
                                        <i class="ni ni-box-2 text-primary"></i>
                                        <span class="nav-link-text">Lista de productos</span>
                                    </a>
                                </li>';
                            }
                            echo'
                        </ul>';
                        if ($rol == 1) {
                            echo '
                            <h6 class="navbar-heading p-0 text-muted">
                                <span class="docs-normal">Categorías</span>
                            </h6>
                            <ul class="navbar-nav">
                                <li class="nav-item">
                                    <a class="nav-link" href="../categorias/categoriasCrud.php">
                                        <i class="ni ni-bullet-list-67 text-primary"></i>
                                        <span class="nav-link-text">Crear categoría</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="../categorias/listaCategorias.php">
                                        <i class="ni ni-bullet-list-67 text-primary"></i>
                                        <span class="nav-link-text">Lista de categoías</span>
                                    </a>
                                </li>
                            </ul>
                            <h6 class="navbar-heading p-0 text-muted">
                                <span class="docs-normal">Usuarios</span>
                            </h6>
                            <ul class="navbar-nav">
                                <li class="nav-item">
                                    <a class="nav-link" href="/RopayMedia/app/View/Usuarios/listarUsuarios.php" data-bs-toggle="modal" data-bs-target="#agregarUsuario">
                                        <i class="ni ni-bullet-list-67 text-primary"></i>
                                        <span class="nav-link-text">Agregar Usuario</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="/RopayMedia/app/View/Usuarios/listarUsuarios.php">
                                        <i class="ni ni-bullet-list-67 text-primary"></i>
                                        <span class="nav-link-text">Lista de Usuario</span>
                                    </a>
                                </li>
                            </ul>
                            <h6 class="navbar-heading p-0 text-muted">
                                <span class="docs-normal">Pedidos</span>
                            </h6>
                            <ul class="navbar-nav">
                                <li class="nav-item">
                                    <a class="nav-link" href="../pedidos/listaPedidosEnValidacion.php">
                                        <i class="ni ni-bullet-list-67 text-primary"></i>
                                        <span class="nav-link-text">En validación</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="../pedidos/listaPedidosEnTienda.php">
                                        <i class="ni ni-bullet-list-67 text-primary"></i>
                                        <span class="nav-link-text">En la tienda</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="../pedidos/listaPedidosEnProgreso.php">
                                        <i class="ni ni-bullet-list-67 text-primary"></i>
                                        <span class="nav-link-text">En progreso</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="../pedidos/listaPedidosEntregados.php">
                                        <i class="ni ni-bullet-list-67 text-primary"></i>
                                        <span class="nav-link-text">Entregados</span>
                                    </a>
                                </li>
                            </ul>
                            <h6 class="navbar-heading p-0 text-muted">
                                <span class="docs-normal">Facturas</span>
                            </h6>
                            <ul class="navbar-nav">
                                <li class="nav-item">
                                    <a class="nav-link" href="../facturas/facturasCrud.php">
                                        <i class="ni ni-bullet-list-67 text-primary"></i>
                                        <span class="nav-link-text">Crear factura</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="../facturas/listaFacturas.php">
                                        <i class="ni ni-bullet-list-67 text-primary"></i>
                                        <span class="nav-link-text">Lista de facturas</span>
                                    </a>
                                </li>
                            </ul>';
                        }
                        echo'
                        <h6 class="navbar-heading p-0 text-muted">
                            <span class="docs-normal">Reportes</span>
                        </h6>
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link" href="../reportes/miscompras.php">
                                    <i class="ni ni-bullet-list-67 text-primary"></i>
                                    <span class="nav-link-text">Ver mis compras</span>
                                </a>
                            </li>';
                            if ($rol == 1) {
                                echo '
                                <li class="nav-item">
                                    <a class="nav-link" href="../reportes/reporteeconomico.php">
                                        <i class="ni ni-box-2 text-primary"></i>
                                        <span class="nav-link-text">Reporte económico</span>
                                    </a>
                                </li>
                               <li class="nav-item">
                                    <a class="nav-link" href="../reportes/topproductos.php">
                                        <i class="ni ni-box-2 text-primary"></i>
                                        <span class="nav-link-text">Top 10 Productos</span>
                                    </a>
                                </li>
                            </li>
                            <li class="nav-item">
                                    <a class="nav-link" href="../reportes/topclientes.php">
                                        <i class="ni ni-box-2 text-primary"></i>
                                        <span class="nav-link-text">Top 10 Clientes</span>
                                    </a>
                                </li>';
                            }
                            echo'
                        </ul>
                    </div>
                </div>
            </div>
        </nav>';
    } else {
        echo '
        <nav class="sidenav navbar navbar-vertical fixed-left navbar-expand-xs navbar-light bg-white" id="sidenav-main">
            <div class="scrollbar-inner">
                <div class="sidenav-header align-items-center">
                    <a  href="../home/home.php">
                        <img src="../assets/img/brand/imagen3.png"  style="max-width: 20%; height: auto;"> 
                    </a>
                </div>
                <div class="navbar-inner">
                    <div class="collapse navbar-collapse" id="sidenav-collapse-main">
                        <hr class="my-3">
                        <h6 class="navbar-heading p-0 text-muted">
                            <span class="docs-normal">Productos</span>
                        </h6>
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link" href="../categorias/categorias.php">
                                    <i class="ni ni-bullet-list-67 text-primary"></i>
                                    <span class="nav-link-text">Productos por categorías</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="../productos/productos.php">
                                    <i class="ni ni-box-2 text-primary"></i>
                                    <span class="nav-link-text">Todos los productos</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>';
    }
}


function HeadCSS()
{
    echo '
    <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <meta name="description" content="Start your development with a Dashboard for Bootstrap 4.">
      <meta name="author" content="Creative Tim">

      <title>Ropa y 1/2</title>
      <link rel="icon" href="../assets/img/brand/favicon.png" type="image/png">
      <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700">
      <link rel="stylesheet" href="../assets/vendor/@fortawesome/fontawesome-free/css/all.min.css" type="text/css">
      <link rel="stylesheet" href="../assets/css/argon.css?v=1.2.0" type="text/css">
      <link rel="stylesheet" href="../assets/vendor/nucleo/css/nucleo.css" type="text/css">      
      <link rel="stylesheet" href="../dist/css/styles.css">   
    </head>';
}

function HeadAuth()
{
    echo '
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="Start your development with a Dashboard for Bootstrap 4.">
        <meta name="author" content="Creative Tim">
        <link rel="icon" href="../assets/img/brand/favicon.png" type="image/png">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700">
        <link rel="stylesheet" href="../assets/vendor/nucleo/css/nucleo.css" type="text/css">
        <link rel="stylesheet" href="../assets/vendor/@fortawesome/fontawesome-free/css/all.min.css" type="text/css">
        <link rel="stylesheet" href="../assets/css/argon.css?v=1.2.0" type="text/css">
    </head>';
}


function MostrarFooter()
{
    echo '
    <footer class="py-5 mt-auto" id="footer-main">
        <div class="container">
            <div class="row align-items-center justify-content-xl-between">
                <div class="col-xl-6">
                    <div class="copyright text-center text-xl-left text-muted">
                        &copy; 2024 <a class="font-weight-bold ml-1" target="_blank">Todos los derechos reservados</a>
                    </div>
                </div>
                <div class="col-xl-6">
                    <ul class="nav nav-footer justify-content-center justify-content-xl-end">
                        <li class="nav-item">
                            <a class="font-weight-bold ml-1" target="_blank">La Ropa 1/2</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>';
}



function navbarLoginRegistro()
{
    echo '
    <nav id="navbar-main" class="navbar navbar-horizontal navbar-transparent navbar-main navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="login.php">
                <img src="../assets/img/brand/imagen5.png" style="max-width: 20%; height: auto;">
            </a>
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a href="login.php" class="nav-link">
                        <span class="nav-link-inner--text">Iniciar Sesión</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="registro.php" class="nav-link">
                        <span class="nav-link-inner--text">Registrarse</span>
                    </a>
                </li>
            </ul>
            <hr class="d-lg-none" />
        </div>
    </nav>';
}
?>
