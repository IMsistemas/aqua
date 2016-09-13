<!DOCTYPE html>
<html lang="es-ES" ng-app="softver-aqua">
<head>
	<meta charset="utf-8">
  <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">

	<title>Aqua - Inicio</title>
	<link href="<?= asset('css/bootstrap.min.css') ?>" rel="stylesheet">
	<link href="<?= asset('css/font-awesome.min.css') ?>" rel="stylesheet">
	<link href="<?= asset('css/index.css') ?>" rel="stylesheet">
  <link href="<?= asset('css/style_generic_app.css') ?>" rel="stylesheet">
  
</head>
<body ng-controller="mainController">
  <header>
  <hr>
  <div class="barraNavegacion">
   <a href=""> <i class="fa fa-arrow-left fa-2x" aria-hidden="true"></i></a>
   <a href=""><i class="fa fa-arrow-right fa-2x" aria-hidden="true"></i></a>
   <a href=""><i class="fa fa-refresh fa-2x" aria-hidden="true" ng-click="reloadRoute();"></i></a>
   <!-- <a href=""><i class="fa fa-times fa-2x" aria-hidden="true"></i></a> -->
  </div>
    <div class="titulo"><span style="font-weight: bold;" ng-bind="titulo | uppercase"></span></div>
    
    
    <nav>
    <div class="brandLogo">
      <img ng-src="img/logotipo-interno.png">
    </div>
      <ul>
        <li><a href="#"><i class="fa fa-home" aria-hidden="true"></i>Inicio</a></li>
        <li class="padre"><a href="#"><i class="fa fa-tint" aria-hidden="true"></i>Recaudación<i class="der fa fa-chevron-down" aria-hidden="true"></i></a>
          <ul class="hijos">
            <li><a href="#" ng-click="toModuloRecaudacion();">Cobro Agua</a></li>
            <li><a href="#" ng-click="">Lecturas</a></li>
          </ul>
        </li>
        <li class="padre"><a href="#"><i class="fa fa-tachometer" aria-hidden="true"></i></i>Suministros<i class="der fa fa-chevron-down" aria-hidden="true"></i></a>
          <ul class="hijos">
            <li><a href="#" ng-click="toModuloSuministro();">Suministros</a></li>
            <li><a href="#" ng-click="toModuloProvincia();">Sectores</a></li>            
          </ul>
        </li>
        <li class="padre"><a href="#"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>Solicitudes<i class=" der fa fa-chevron-down" aria-hidden="true"></i></a>
          <ul class="hijos">
            <li><a href="#" ng-click="toModuloSolicitud();">Solicitudes</a></li>
            <li><a href="#" ng-click="">Solicitudes en espera</a></li>
          </ul>
        </li>
        <li class="padre"><a href="#"><i class="fa fa-user" aria-hidden="true"></i>Clientes<i class="der fa fa-chevron-down" aria-hidden="true"></i></a>
          <ul class="hijos">
            <li><a href="#" ng-click="toModuloCliente();">Clientes</a></li>
            <li><a href="#" ng-click="toModuloCliente();">Cuentas por cobrar clientes</a></li>
            <li><a href="#" ng-click="toModuloCliente();">Cuentas por pagar clientes</a></li>
          </ul>
        </li>
        <li class="padre"><a href="#"><i class="fa fa-male" aria-hidden="true"></i>Personal<i class="der fa fa-chevron-down" aria-hidden="true"></i></a>
          <ul class="hijos">
            <li><a href="#" ng-click="toModuloCargo();">Cargos</a></li>
            <li><a href="#" ng-click="toModuloEmpleado();">Colaboradores</a></li>
          </ul>
        </li>
        <li class="padre"><a href="#"><i class="fa fa-user-plus" aria-hidden="true"></i>Perfil<i class="der fa fa-chevron-down" aria-hidden="true"></i></a>
          <ul class="hijos">
            <li><a href="perfil" ng-click="">Editar Perfil</a></li>
          </ul>
        </li>
        <li class="padre"><a href="#"><i class="fa fa-users" aria-hidden="true"></i>Usuarios<i class="der fa fa-chevron-down" aria-hidden="true"></i></a>
          <ul class="hijos">
            <li><a href="#" ng-click="">Usuarios</a></li>
            <li><a href="#" ng-click="">Roles</a></li>
            
          </ul>
        </li>
        <li class="padre"><a href="#"><i class="fa fa-cog fa-spin" aria-hidden="true"></i>Configuración<i class="der fa fa-chevron-down" aria-hidden="true"></i></a>
          <ul class="hijos">
            <li><a href="#">Configuración del sistema</a></li>
          </ul>
        </li>
      </ul>
    </nav>
  </header>

  <section ng-include="toModulo">

  </section>

  <footer>
    powered by IMMPACT MEDIA
  </footer>
       
        
        <script src="<?= asset('app/lib/angular/angular.min.js') ?>"></script>
        <script src="<?= asset('app/lib/angular/angular-route.min.js') ?>"></script>
        <script src="<?= asset('js/jquery.min.js') ?>"></script>
        <script src="<?= asset('js/bootstrap.min.js') ?>"></script>
        <script src="<?= asset('js/menuLateral.js') ?>"></script>
        
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

