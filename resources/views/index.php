<!DOCTYPE html>
<html ng-app="softver-aqua">
<head>
	<meta charset="utf-8">
  <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">

	<title>Aqua - Inicio</title>
	<link href="<?= asset('css/bootstrap.min.css') ?>" rel="stylesheet">
	<link href="<?= asset('css/font-awesome.min.css') ?>" rel="stylesheet">
	<link href="<?= asset('css/index.css') ?>" rel="stylesheet">
</head>
<body ng-controller="inicioController">
  <header>
  <hr>
    <div class="titulo">Titulo</div>
    
    <nav>
    <div class="brandLogo">
      <img src="img/logotipo-interno.png">
    </div>
      <ul>
        <li><a href="#"><i class="fa fa-home" aria-hidden="true"></i>Inicio</a></li>
        <li class="padre"><a href="#"><i class="fa fa-tint" aria-hidden="true"></i>Recaudación<i class="der fa fa-chevron-down" aria-hidden="true"></i></a>
          <ul class="hijos">
            <li><a href="recaudacion">Cobro Agua</a></li>
            <li><a href="lecturas">Lecturas</a></li>
          </ul>
        </li>
        <li class="padre"><a href="#"><i class="fa fa-tachometer" aria-hidden="true"></i></i>Suministros<i class="der fa fa-chevron-down" aria-hidden="true"></i></a>
          <ul class="hijos">
            <li><a href="suminsitros">Suministros</a></li>
            <li><a href="sectores">Sectores</a></li>
          </ul>
        </li>
        <li class="padre"><a href="#"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>Solicitudes<i class=" der fa fa-chevron-down" aria-hidden="true"></i></a>
          <ul class="hijos">
            <li><a href="solicitudes">Solicitudes</a></li>
            <li><a href="">Solicitudes en espera</a></li>
          </ul>
        </li>
        <li class="padre"><a href="#"><i class="fa fa-user" aria-hidden="true"></i>Clientes<i class="der fa fa-chevron-down" aria-hidden="true"></i></a>
          <ul class="hijos">
            <li><a href="clientes">Clientes</a></li>
          </ul>
        </li>
        <li class="padre"><a href="#"><i class="fa fa-user-plus" aria-hidden="true"></i>Perfil<i class="der fa fa-chevron-down" aria-hidden="true"></i></a>
          <ul class="hijos">
            <li><a href="perfil">Editar Perfil</a></li>
          </ul>
        </li>
        <li class="padre"><a href="#"><i class="fa fa-users" aria-hidden="true"></i>Usuarios<i class="der fa fa-chevron-down" aria-hidden="true"></i></a>
          <ul class="hijos">
            <li><a href="usuarios">Usuarios</a></li>
            <li><a href="usuarios/roles">Roles</a></li>
          </ul>
        </li>
        <li class="padre"><a href="#"><i class="fa fa-cog fa-spin" aria-hidden="true"></i>Configuración<i class="der fa fa-chevron-down" aria-hidden="true"></i></a>
          <ul class="hijos">
            <li><a href="">Configuración del sistema</a></li>
          </ul>
        </li>
      </ul>
    </nav>
  </header>

  <section>
   
  </section>

  <footer>
    powered by IMMPACT MEDIA
  </footer>
        <script src="<?= asset('app/lib/angular/angular.min.js') ?>"></script>
        <script src="<?= asset('app/app.js') ?>"></script>
        <script src="<?= asset('app/controllers/inicioController.js') ?>"></script>
        <script src="<?= asset('js/jquery.min.js') ?>"></script>
        <script src="<?= asset('js/bootstrap.min.js') ?>"></script>
        <script src="<?= asset('js/menuLateral.js') ?>"></script>
</body>
</html>