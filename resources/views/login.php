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
		<a href="">Ingresar</a>
		
		
	</form>
</section>
<hr>
<footer>
	<a href="https://www.imnegocios.com/"><img src="img/logotipo-imnegocios.png"></a>
</footer>
</body>
</html>