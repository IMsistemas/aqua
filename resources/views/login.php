<!DOCTYPE html>
<html>
<head>
	<title>Aqua-Inicio Sesión</title>
	<link href="<?= asset('css/bootstrap.min.css') ?>" rel="stylesheet">
	<link href="<?= asset('css/font-awesome.min.css') ?>" rel="stylesheet">
	<link href="<?= asset('css/login.css') ?>" rel="stylesheet">
</head>
<body>

<header>
	<img src="img/logotipo-interno.png">
</header>
<hr>
<section>
	<form>
		<label>INICIO DE SESIÓN</label>
		<br>
		Por favor ingresar nombre de usuario y contraseña
		<br>
		<input type="text" name="nombreUsuario" id="nombreUsuario" placeholder="usuario" class=user""><br> 
		
		<input type="text" name="passUsuario" id="passUsuario" placeholder="clave">
		<br>
		<a href="" onclick="pantallaCompleta();">Ingresar</a>
		
		
	</form>
</section>

<footer>
<hr>

	<a href="https://www.imnegocios.com/"><img src="img/logotipo-imnegocios.png"></a>
</footer>
<script type="text/javascript">
	pantallaCompleta = function(){
		javascript:window.open("http://localhost:88/aqua/public/inicio", "_blank", "resizable=yes,scrollbars=yes,status=no, directories=0,titlebar=0,toolbar=0,location=0,status=0,menubar=0,scrollbars=yes")

		
		window.close();
	}
	
</script>
</body>
</html>