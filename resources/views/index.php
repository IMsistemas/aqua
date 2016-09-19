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

        <style>
            .dataclient{
                font-weight: bold;
            }
        </style>

    </head>
    <body ng-controller="mainController">

    <div class="container-fixed">

        <div class="sidebar">
            <div class="nav-side-menu">
                <div class="brand">
                    <img id="logo-img" ng-src="img/logotipo-interno.png">
                </div>
                <i class="fa fa-bars fa-2x toggle-btn" data-toggle="collapse" data-target="#menu-content"></i>

                <div class="menu-list">

                    <ul id="menu-content" class="menu-content collapse out">
                        <li>
                            <a href="#">
                                <i class="fa fa-home fa-lg"></i> Inicio
                            </a>
                        </li>
                        <li  data-toggle="collapse" data-target="#recaudacion" class="collapsed">
                            <a href="#"><i class="fa fa-tint fa-lg"></i> Recaudación <span class="arrow"></span></a>
                        </li>
                        <ul class="sub-menu collapse" id="recaudacion">
                            <li><a href="#" ng-click="toModuloRecaudacion();">Cobro Agua</a></li>
                            <li><a href="#" ng-click="toModuloLectura();">Lecturas</a></li>
                        </ul>
                        <li data-toggle="collapse" data-target="#suministro" class="collapsed">
                            <a href="#"><i class="fa fa-tachometer fa-lg"></i> Suministros <span class="arrow"></span></a>
                        </li>
                        <ul class="sub-menu collapse" id="suministro">
                            <li><a href="#" ng-click="toModuloSuministro();">Suministros</a></li>
                            <li><a href="#" ng-click="toModuloBarrio();">Sectores</a></li>
                        </ul>
                        <li data-toggle="collapse" data-target="#solicitud" class="collapsed">
                            <a href="#"><i class="fa fa-pencil-square-o fa-lg"></i> Solicitudes <span class="arrow"></span></a>
                        </li>
                        <ul class="sub-menu collapse" id="solicitud">
                            <li><a href="#" ng-click="toModuloSolicitud();">Solicitudes</a></li>
                            <li><a href="#" ng-click="toModuloSolicitudEspera();">Solicitudes en espera</a></li>
                        </ul>
                        <li data-toggle="collapse" data-target="#cliente" class="collapsed">
                            <a href="#"><i class="fa fa-user fa-lg"></i> Clientes <span class="arrow"></span></a>
                        </li>
                        <ul class="sub-menu collapse" id="cliente">
                            <li><a href="#" ng-click="toModuloCliente();">Clientes</a></li>
                            <li ng-show='false'><a href="#" ng-click="">Cuentas por cobrar clientes</a></li>
                            <li ng-show='false'><a href="#" ng-click="">Cuentas por pagar clientes</a></li>
                        </ul>
                        <li data-toggle="collapse" data-target="#personal" class="collapsed">
                            <a href="#"><i class="fa fa-male fa-lg"></i> Personal <span class="arrow"></span></a>
                        </li>
                        <ul class="sub-menu collapse" id="personal">
                            <li><a href="#" ng-click="toModuloCargo();">Cargos</a></li>
                            <li><a href="#" ng-click="toModuloEmpleado();">Colaboradores</a></li>
                        </ul>
                        <li data-toggle="collapse" data-target="#perfil" class="collapsed" ng-show='false'>
                            <a href="#"><i class="fa fa-user-plus fa-lg"></i> Perfil <span class="arrow"></span></a>
                        </li>
                        <ul class="sub-menu collapse" id="perfil">
                            <li><a href="perfil" ng-click="">Editar Perfil</a></li>
                        </ul>
                        <li data-toggle="collapse" data-target="#usuarios" class="collapsed" ng-show='false'>
                            <a href="#"><i class="fa fa-users fa-lg"></i> Usuarios <span class="arrow"></span></a>
                        </li>
                        <ul class="sub-menu collapse" id="usuarios">
                            <li><a href="#" ng-click="">Usuarios</a></li>
                            <li><a href="#" ng-click="">Roles</a></li>
                        </ul>
                        <li data-toggle="collapse" data-target="#configuracion" class="collapsed" ng-show='false'>
                            <a href="#"><i class="fa fa-cog fa-spin fa-lg"></i> Configuración <span class="arrow"></span></a>
                        </li>
                        <ul class="sub-menu collapse" id="configuracion">
                            <li><a href="#">Configuración del sistema</a></li>
                        </ul>

                    </ul>
                </div>
            </div>
        </div>

        <div class="main">
            <div class="col-xs-12" style="border-bottom: 2px solid #039be5; padding-right: 2px;">
                <ul class="nav nav-pills" style="float: right;">
                    <li role="presentation" class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                            <img src="img/usuario.png" alt=""> <span style="font-size: 16px; font-weight: bold; color: black !important;" ng-cloak>{{username}}</span>
                            <span class="glyphicon glyphicon-menu-down" style="font-size: 16px;" aria-hidden="true"></span>
                        </a>
                        <ul class="dropdown-menu pull-right" style="float: right !important;">
                            <li role="separator" class="divider"></li>
                            <li><a href="#"  onclick="window.close();"><i class="fa fa-sign-out fa-lg" ></i> Salir</a></li>
                        </ul>
                    </li>
                </ul>
            </div>

            <div class="col-xs-12" style="padding: 0; height: 35px;">
                <ol class="breadcrumb" id="list_breadcrumb"></ol>
            </div>

            <div class="col-xs-12 bg-primary" style="height: 35px; padding-top: 5px;">
                <span style="font-weight: bold; font-size: 16px;" ng-bind="titulo | uppercase"></span>
            </div>

            <div class="col-xs-12" style="padding: 0; overflow-y: auto;" ng-include="toModulo">
                
            </div>
        </div>

    </div>

    <script src="<?= asset('app/lib/angular/angular.min.js') ?>"></script>
    <script src="<?= asset('app/lib/angular/angular-route.min.js') ?>"></script>
    <script src="<?= asset('js/jquery.min.js') ?>"></script>
    <script src="<?= asset('js/bootstrap.min.js') ?>"></script>
    <script src="<?= asset('js/menuLateral.js') ?>"></script>
    <script src="<?= asset('js/moment.min.js') ?>"></script>
    <script src="<?= asset('js/es.js') ?>"></script>
    <script src="<?= asset('js/bootstrap-datetimepicker.min.js') ?>"></script>

    <script src="<?= asset('app/app.js') ?>"></script>

    <script src="<?= asset('app/controllers/mainController.js') ?>"></script>
    <script src="<?= asset('app/controllers/clientesController.js') ?>"></script>
    <script src="<?= asset('app/controllers/cargosController.js') ?>"></script>
    <script src="<?= asset('app/controllers/empleadosController.js') ?>"></script>
    <script src="<?= asset('app/controllers/recaudacionController.js') ?>"></script>
    <script src="<?= asset('app/controllers/provinciasController.js') ?>"></script>
    <script src="<?= asset('app/controllers/cantonesController.js') ?>"></script>
    <script src="<?= asset('app/controllers/parroquiasController.js') ?>"></script>
    <script src="<?= asset('app/controllers/barriosController.js') ?>"></script>
    <script src="<?= asset('app/controllers/callesController.js') ?>"></script>
    <script src="<?= asset('app/controllers/solicitudController.js') ?>"></script>
    <script src="<?= asset('app/controllers/suministrosController.js') ?>"></script>
    <script src="<?= asset('app/controllers/esperaController.js') ?>"></script>
    <script src="<?= asset('app/controllers/viewLecturaController.js') ?>"></script>

    

    


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

