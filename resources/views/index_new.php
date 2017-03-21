<!DOCTYPE html>
<html lang="es-ES" ng-app="softver-aqua">

    <head>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">

        <title>Aqua - Inicio</title>

        <link href="<?= asset('css/bootstrap.min.css') ?>" rel="stylesheet">
        <link href="<?= asset('css/font-awesome.min.css') ?>" rel="stylesheet">
        <link href="<?= asset('css/index.css') ?>" rel="stylesheet">
        <link href="<?= asset('css/bootstrap-datetimepicker.min.css') ?>" rel="stylesheet">
        <link href="<?= asset('css/style_generic_app.css') ?>" rel="stylesheet">
        <link href="<?= asset('css/angucomplete-alt.css') ?>" rel="stylesheet">

        <style>
            .dataclient{
                font-weight: bold;
            }
        </style>

    </head>
    <body ng-controller="mainController">

    <header>
        <div class="container-fluid">
            <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
                <div class="container-fluid">
                    <!-- Brand and toggle get grouped for better mobile display -->
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a id="view-home" class="navbar-brand" href="#" data-toggle="tooltip" data-placement="bottom" title="Ir a Inicio">
                            <img src="img/logotipo-interno.png" alt="Brand">
                        </a>
                    </div>

                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                        <ul class="nav navbar-nav navbar-left">

                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    Recaudación <b class = "caret"></b>
                                </a>
                                <ul class = "dropdown-menu">
                                    <li><a href="#" ng-click="toModuloCliente();">Gestión de Clientes</a></li>
                                    <li><a href="#" ng-click="toModuloSolicitud();">Solicitud</a></li>
                                    <li><a href = "#" ng-click="toModuloSuministro();">Suministro</a></li>
                                    <li><a href = "#" ng-click="toModuloRecaudacion();">Registro Cobro Agua y Servicios</a></li>
                                    <li><a href = "#" ng-click="toModuloLectura();">Registro de Lecturas</a></li>
                                </ul>
                            </li>

                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    SRI <b class = "caret"></b>
                                </a>
                                <ul class = "dropdown-menu">
                                    <li><a href = "#">Anexo Transaccional Simplificado (ATS)</a></li>
                                    <li><a href = "#">Registro Facturación Electrónica</a></li>
                                </ul>
                            </li>

                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    Contabilidad <b class = "caret"></b>
                                </a>
                                <ul class = "dropdown-menu">
                                    <li><a href = "#">Gestión de Proveedores</a></li>
                                    <li><a href = "#">Gestión de Transportistas</a></li>
                                    <li role="separator" class="divider"></li>

                                    <li><a href = "#">Plan de Cuenta</a></li>
                                    <li role="separator" class="divider"></li>
                                    <li class="dropdown-submenu">
                                        <a tabindex="-1" href="#" class="dropdown-toggle" data-toggle="dropdown">Inventario</a>
                                        <ul class="dropdown-menu">
                                            <li><a tabindex="-1" href="#">Bodega</a></li>
                                            <li><a href="#">Catálogo Item</a></li>
                                            <li><a href="#">Portafolio</a></li>
                                            <li><a href="#">Registro Inventario</a></li>
                                        </ul>
                                    </li>

                                    <li role="separator" class="divider"></li>
                                    <li class="dropdown-submenu">
                                        <a tabindex="-1" href="#" class="dropdown-toggle" data-toggle="dropdown">Proceso Compras</a>
                                        <ul class="dropdown-menu">
                                            <li><a tabindex="-1" href="#">Facturación de Compras</a></li>
                                            <li><a href="#">Retención Compras</a></li>
                                            <li><a href="#">Comprobante Egreso</a></li>
                                            <li><a href="#">Cuentas por Pagar</a></li>
                                        </ul>
                                    </li>

                                    <li role="separator" class="divider"></li>
                                    <li class="dropdown-submenu">
                                        <a tabindex="-1" href="#" class="dropdown-toggle" data-toggle="dropdown">Proceso Ventas</a>
                                        <ul class="dropdown-menu">
                                            <li><a tabindex="-1" href="#">Facturación de Ventas</a></li>
                                            <li><a href="#">Retención Ventas</a></li>
                                            <li><a href="#">Comprobante Ingreso</a></li>
                                            <li><a href="#">Cuentas por Cobrar</a></li>
                                            <li><a href="#">Guía de Remisión</a></li>
                                        </ul>
                                    </li>

                                    <li role="separator" class="divider"></li>
                                    <li><a href = "#">Nota de Crédito</a></li>

                                    <li role="separator" class="divider"></li>
                                    <li><a href = "#">Activos Fijos</a></li>
                                </ul>
                            </li>

                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    Recursos Humanos <b class = "caret"></b>
                                </a>
                                <ul class = "dropdown-menu">
                                    <li><a href = "#">Cargos</a></li>
                                    <li><a href = "#">Personal</a></li>
                                    <li><a href = "#">Nómina</a></li>
                                </ul>
                            </li>

                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    Configuración <b class = "caret"></b>
                                </a>
                                <ul class = "dropdown-menu">
                                    <li><a href = "#">Configuración del Sistema</a></li>
                                    <li><a href = "#">Gestión de Nomencladores</a></li>
                                    <li role="separator" class="divider"></li>
                                    <li class="dropdown-submenu">
                                        <a tabindex="-1" href="#" class="dropdown-toggle" data-toggle="dropdown">Sectorización</a>
                                        <ul class="dropdown-menu">
                                            <li><a tabindex="-1" href="#">Zonas</a></li>
                                            <li><a href="#">Transversales</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                        <ul class="nav navbar-nav navbar-right">
                            <li class="dropdown" >
                                <a class="menu1" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-user fa-lg" ></i> <span id="active-user"></span> <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a href="#profile"><i class="fa fa-pencil-square-o fa-lg" ></i> Perfil</a></li>
                                    <li role="separator" class="divider"></li>
                                    <li><a href="#"><i class="fa fa-sign-out fa-lg" ></i> Salir</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div><!-- /.navbar-collapse -->
                </div>
            </nav>
        </div>
    </header>


    <div class="container-fixed" style="margin-top: 5%;">


        <div class="col-xs-12" style="padding: 0; overflow-y: auto;" ng-include="toModulo">

        </div>


    </div>

    <script src="<?= asset('app/lib/angular/angular.min.js') ?>"></script>
    <script src="<?= asset('app/lib/angular/angular-route.min.js') ?>"></script>

    <script src="<?= asset('app/lib/angular/ng-file-upload-shim.min.js') ?>"></script>
    <script src="<?= asset('app/lib/angular/ng-file-upload.min.js') ?>"></script>

    <script src="<?= asset('app/lib/angular/dirPagination.js') ?>"></script>

    <script src="<?= asset('js/jquery.min.js') ?>"></script>
    <script src="<?= asset('js/bootstrap.min.js') ?>"></script>
    <script src="<?= asset('js/menuLateral.js') ?>"></script>
    <script src="<?= asset('js/moment.min.js') ?>"></script>
    <script src="<?= asset('js/es.js') ?>"></script>
    <script src="<?= asset('js/bootstrap-datetimepicker.min.js') ?>"></script>

    <script src="<?= asset('app/lib/angular/angucomplete-alt.min.js') ?>"></script>


    <script src="<?= asset('app/app.js') ?>"></script>

    <script src="<?= asset('app/controllers/mainController.js') ?>"></script>
    <script src="<?= asset('app/controllers/clientesController.js') ?>"></script>
    <script src="<?= asset('app/controllers/cargosController.js') ?>"></script>
    <script src="<?= asset('app/controllers/empleadosController.js') ?>"></script>
    <script src="<?= asset('app/controllers/facturaController.js') ?>"></script>
    <script src="<?= asset('app/controllers/provinciasController.js') ?>"></script>
    <script src="<?= asset('app/controllers/cantonesController.js') ?>"></script>
    <script src="<?= asset('app/controllers/parroquiasController.js') ?>"></script>
    <script src="<?= asset('app/controllers/barriosController.js') ?>"></script>
    <script src="<?= asset('app/controllers/callesController.js') ?>"></script>
    <script src="<?= asset('app/controllers/solicitudServicioController.js') ?>"></script>
    <script src="<?= asset('app/controllers/suministrosController.js') ?>"></script>
    <script src="<?= asset('app/controllers/esperaController.js') ?>"></script>
    <script src="<?= asset('app/controllers/viewLecturaController.js') ?>"></script>
    <script src="<?= asset('app/controllers/ccClienteController.js') ?>"></script>
    <script src="<?= asset('app/controllers/cpClienteController.js') ?>"></script>
    <script src="<?= asset('app/controllers/comprasproductoController.js') ?>"></script>
    <script src="<?= asset('app/controllers/comprasproductoIngresoController.js') ?>"></script>
    <script src="<?= asset('app/controllers/comprasImprimirController.js') ?>"></script>
    <script src="<?= asset('app/controllers/categoriasController.js') ?>"></script>
    <script src="<?= asset('app/controllers/catalogoproductosController.js') ?>"></script>
    <script src="<?= asset('app/controllers/categoriasController.js') ?>"></script>
    <script src="<?= asset('app/controllers/bodegasController.js') ?>"></script>
    <script src="<?= asset('app/controllers/proveedoresController.js') ?>"></script>
    <script src="<?= asset('app/controllers/facturacionventa.js') ?>"></script>
    <script src="<?= asset('app/controllers/retencionComprasIndexController.js') ?>"></script>
    <script src="<?= asset('app/controllers/retencionCompraController.js') ?>"></script>



    


    

    


    <script type="text/javascript">
        /* $(function() {
         $(document).keydown(function(e){
         var code = (e.keyCode ? e.keyCode : e.which);
         if(code == 116) {
         e.preventDefault();
         alert('no puedes we');
         }
         });
         });*/
    </script>

    </body>
</html>

