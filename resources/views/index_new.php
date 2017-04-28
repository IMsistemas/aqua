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
                                    <li><a href = "#" ng-click="toModuloProveedores();">Gestión de Proveedores</a></li>
                                    <li><a href = "#" ng-click="toModuloTransportistas();">Gestión de Transportistas</a></li>
                                    <li role="separator" class="divider"></li>

                                    <li><a href = "#" ng-click="toModuloPlanCuentas();">Plan de Cuenta</a></li>
                                    <li role="separator" class="divider"></li>
                                    <li class="dropdown-submenu">
                                        <a tabindex="-1" href="#" class="dropdown-toggle" data-toggle="dropdown">Inventario</a>
                                        <ul class="dropdown-menu">
                                            <li><a tabindex="-1" href="#" ng-click="toModuloCrearBodegas();">Bodega</a></li>
                                            <li><a href="#" ng-click="toModuloCatalogoProductos();">Catálogo Item</a></li>
                                            <li><a href="#" ng-click="toModuloPortafolioProductos();">Portafolio</a></li>
                                            <li><a href="#" ng-click="toModuloInventario();">Registro Inventario y Kardex</a></li>
                                        </ul>
                                    </li>

                                    <li role="separator" class="divider"></li>
                                    <li class="dropdown-submenu">
                                        <a tabindex="-1" href="#" class="dropdown-toggle" data-toggle="dropdown">Proceso Compras</a>
                                        <ul class="dropdown-menu">
                                            <li><a tabindex="-1" href="#" ng-click="toModuloCompras();">Facturación de Compras</a></li>
                                            <li><a href="#">Retención Compras</a></li>
                                            <li><a href="#">Comprobante Egreso</a></li>
                                            <li><a href="#">Cuentas por Pagar</a></li>
                                        </ul>
                                    </li>

                                    <li role="separator" class="divider"></li>
                                    <li class="dropdown-submenu">
                                        <a tabindex="-1" href="#" class="dropdown-toggle" data-toggle="dropdown">Proceso Ventas</a>
                                        <ul class="dropdown-menu">
                                            <li><a tabindex="-1" href="#" ng-click="toModuloPuntoVenta()">Puntos de Ventas</a></li>
                                            <li><a href="#" ng-click="toModuloVentas();">Facturación de Ventas</a></li>
                                            <!--<li><a href="#">Retención Ventas</a></li>
                                            <li><a href="#">Comprobante Ingreso</a></li>
                                            <li><a href="#">Cuentas por Cobrar</a></li>
                                            <li><a href="#" ng-click="toModuloGuiaRemision();">Guía de Remisión</a></li>-->
                                        </ul>
                                    </li>

                                    <!--<li role="separator" class="divider"></li>
                                    <li><a href = "#">Nota de Crédito</a></li>-->

                                    <!--<li role="separator" class="divider"></li>
                                        <li><a href = "#">Activos Fijos</a></li>-->
                                </ul>
                            </li>

                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    Recursos Humanos <b class = "caret"></b>
                                </a>
                                <ul class = "dropdown-menu">
                                    <li><a href = "#" ng-click="toModuloDepartamento();">Departamentos</a></li>
                                    <li><a href = "#" ng-click="toModuloCargo();">Cargos</a></li>
                                    <li><a href = "#" ng-click="toModuloEmpleado();">Personal</a></li>
                                    <!--<li><a href = "#" ng-click="">Nómina</a></li>-->
                                </ul>
                            </li>

                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    Configuración <b class = "caret"></b>
                                </a>
                                <ul class = "dropdown-menu">
                                    <li><a href = "#" ng-click="toModuloConfiguracion();">Configuración del Sistema</a></li>
                                    <li><a href = "#" ng-click="toModuloNomenclador();">Gestión de Nomencladores</a></li>

                                    <li role="separator" class="divider"></li>
                                    <li class="dropdown-submenu">
                                        <a tabindex="-1" href="#" class="dropdown-toggle" data-toggle="dropdown">Sectorización</a>
                                        <ul class="dropdown-menu">
                                            <li><a tabindex="-1" href="#" ng-click="toModuloBarrio();">Zonas</a></li>
                                            <li><a href="#" ng-click="toModuloCalle();">Transversales</a></li>
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
                                    <li><a href="#" ng-click="toModuloRol()">Roles</a></li>
                                    <li><a href="#" ng-click="">Gestión de Usuarios</a></li>
                                    <li role="separator" class="divider"></li>
                                    <li><a href="#" ng-click="toLogout();"><i class="fa fa-sign-out fa-lg" ></i> Salir</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div><!-- /.navbar-collapse -->
                </div>
            </nav>
        </div>
    </header>


    <div class="container-fixed" style="">


        <div class="col-xs-12" style="padding: 0; margin-top: 5%;" ng-include="toModulo">

        </div>


    </div>

    <div class="modal fade" tabindex="-1" role="dialog" id="modalConfirmLogout">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header modal-header-info">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Confirmación</h4>
                </div>
                <div class="modal-body">
                    <span>¿Realmente desea cerrar sesión y salir del Sistema?</span>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        Cancelar <span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span>
                    </button>
                    <button type="button" class="btn btn-primary" id="btn-save" ng-click="logoutSystem()">
                        Aceptar <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                    </button>
                </div>
            </div>
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
    <script src="<?= asset('app/controllers/venta.js') ?>"></script>
    <script src="<?= asset('app/controllers/retencionComprasIndexController.js') ?>"></script>
    <script src="<?= asset('app/controllers/retencionCompraController.js') ?>"></script>
    <script src="<?= asset('app/controllers/configuracionSystemController.js') ?>"></script>
    <script src="<?= asset('app/controllers/transportistaController.js') ?>"></script>
    <script src="<?= asset('app/controllers/EstadosFinancieros.js') ?>"></script>
    <script src="<?= asset('app/controllers/nomencladorController.js') ?>"></script>
    <script src="<?= asset('app/controllers/guiaremisionController.js') ?>"></script>
    <script src="<?= asset('app/controllers/puntoventaController.js') ?>"></script>
    <script src="<?= asset('app/controllers/InvetarioItemKardex.js') ?>"></script>
    <script src="<?= asset('app/controllers/departamentosController.js') ?>"></script>
    <script src="<?= asset('app/controllers/rolController.js') ?>"></script>


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

