<!DOCTYPE html>
<html>
	<head>

		<title>Aqua-Inicio Sesión</title>
		<link href="<?= asset('css/bootstrap.min.css') ?>" rel="stylesheet">
		<link href="<?= asset('css/font-awesome.min.css') ?>" rel="stylesheet">
		<link href="<?= asset('css/login.css') ?>" rel="stylesheet">

	</head>
	<body>

		<div class="container" style="border-bottom: solid 1px #9e9e9e ;">
			<img src="img/logotipo-interno.png">
		</div>

		<div class="container" style="border-bottom: solid 1px #9e9e9e ; padding-bottom: 4%;">

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

		<footer>
			<a href="https://www.imnegocios.com/" target="_blank"><img src="img/logotipo-imnegocios.png" style="width: 180px;"></a>
		</footer>


		<script type="text/javascript">
			pantallaCompleta = function(){
				javascript:window.open("http://localhost:88/aqua/public/inicio", "_blank", "resizable=yes,scrollbars=yes,status=no, directories=0,titlebar=0,toolbar=0,location=0,status=0,menubar=0,scrollbars=yes")
				window.close();
			}
		</script>

	</body>
</html>