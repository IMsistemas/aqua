<!DOCTYPE html>
<html lang="es-ES" ng-app="softver-aqua">
	<head>

		<title>Aqua-Inicio Sesión</title>
		<link href="<?= asset('css/bootstrap.min.css') ?>" rel="stylesheet">
		<link href="<?= asset('css/font-awesome.min.css') ?>" rel="stylesheet">
		<link href="<?= asset('css/login.css') ?>" rel="stylesheet">

	</head>
	<body ng-controller="loginController">

		<div class="container" style="border-bottom: solid 1px #9e9e9e ;">
			<img src="img/logotipo-interno.png">
		</div>

        <div class="container">

            <div class="col-md-4 col-xs-12"></div>
            <div class="col-md-4 col-xs-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="col-xs-12" style="padding: 0; margin-top: 20px;">

                            <div class="col-xs-12">
                                <input type="text" id="t_usuario" ng-model="t_usuario" class="form-control" placeholder="Usuario">
                            </div>
                            <div class="col-xs-12" style="margin-top: 5px;">
                                <input type="password" id="t_password" ng-model="t_password" class="form-control" placeholder="Contraseña">
                            </div>
                            <div class="col-xs-12" style="margin-top: 20px;">
                                <button class="btn btn-primary btn-lg btn-block" ng-click="verifyLogin()">ENTRAR</button>
                            </div>

                            <div class="col-xs-12" id="view-failed-login" style="margin-top: 10px; display: none;">
                                <div class="alert alert-danger" style="font-weight: bold;" role="alert">{{text_failed}}</div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-xs-12"></div>

        </div>


		<!--<div class="container" style="border-bottom: solid 1px #9e9e9e ; padding-bottom: 4%;">

			<div class="col-xs-12" style="margin-top: 30px;">
				<div class="col-xs-12 col-sm-4"></div>
				<div class="col-xs-12 col-sm-4 text-center">
					<span style="color: #01579b; font-weight: bold; font-size: 14px; margin-bottom: 5px;">INICIO DE SESION</span><br>
					<span>Por favor, ingrese nombre de usuario y clave</span>
				</div>
				<div class="col-xs-12 col-sm-4"></div>
			</div>

			<div class="col-xs-12" style="margin-top: 15px;">
				<div class="col-xs-12 col-sm-4"></div>
				<div class="col-xs-12 col-sm-4">
					<div class="form-group has-feedback has-feedback-left">
						<i class="fa fa-user fa-lg form-control-feedback"></i>
						<input type="text" name="nombreUsuario" id="nombreUsuario" class="form-control" placeholder="Usuario" value="secretaria">
					</div>
				</div>
				<div class="col-xs-12 col-sm-4"></div>
			</div>

			<div class="col-xs-12">
				<div class="col-xs-12 col-sm-4"></div>
				<div class="col-xs-12 col-sm-4">
					<div class="form-group has-feedback has-feedback-left">
						<i class="fa fa-unlock-alt fa-lg form-control-feedback"></i>
						<input type="password" name="passUsuario" id="passUsuario" class="form-control" placeholder="Clave">
					</div>
				</div>
				<div class="col-xs-12 col-sm-4"></div>
			</div>

			<div class="col-xs-12" style="display: none;">
				<div class="col-xs-12 col-sm-4"></div>
				<div class="col-xs-12 col-sm-4 text-center">
					<span>Código de Verificación</span>
				</div>
				<div class="col-xs-12 col-sm-4"></div>
			</div>

			<div class="col-xs-12" style="margin-top: 15px;" style="display: none;">
				<div class="col-xs-12 col-sm-4"></div>
				<div class="col-xs-12 col-sm-4">
					<div class="form-group has-feedback has-feedback-left" style="display: none;">
						<i class="fa fa-unlock-alt fa-lg form-control-feedback"></i>
						<input type="text" class="form-control" placeholder="Código de Verificación" >
					</div>
				</div>
				<div class="col-xs-12 col-sm-4"></div>
			</div>

			<div class="col-xs-12">
				<div class="col-xs-12 col-sm-4"></div>
				<div class="col-xs-12 col-sm-4">
					<button type="button" class="btn btn-block btn-info" onclick="pantallaCompleta();">Ingresar</button>
				</div>
				<div class="col-xs-12 col-sm-4"></div>
			</div>

		</div>

        -->

		<footer>
			<a href="https://www.imnegocios.com/" target="_blank"><img src="img/logotipo-imnegocios.png" style="width: 180px;"></a>
		</footer>

        <script src="<?= asset('app/lib/angular/angular.min.js') ?>"></script>
        <script src="<?= asset('app/lib/angular/angular-route.min.js') ?>"></script>

        <script src="<?= asset('js/jquery.min.js') ?>"></script>
        <script src="<?= asset('js/bootstrap.min.js') ?>"></script>

        <script src="<?= asset('app/lib/angular/ng-file-upload-shim.min.js') ?>"></script>
        <script src="<?= asset('app/lib/angular/ng-file-upload.min.js') ?>"></script>

        <script src="<?= asset('app/lib/angular/dirPagination.js') ?>"></script>

        <script src="<?= asset('app/lib/angular/angucomplete-alt.min.js') ?>"></script>

        <script src="<?= asset('app/app.js') ?>"></script>

        <script src="<?= asset('app/controllers/loginController.js') ?>"></script>

	</body>
</html>