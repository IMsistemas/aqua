<!DOCTYPE html>
<html lang="es-ES" ng-app="softver-aqua">
	<head>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">

        <title>AQUA - Inicio Sesión</title>

        <link rel="shortcut icon" href="<?= asset('favicon.ico') ?>">

		<link href="<?= asset('css/bootstrap.min.css') ?>" rel="stylesheet">
		<link href="<?= asset('css/font-awesome.min.css') ?>" rel="stylesheet">
        <link href="<?= asset('css/style_generic_app.css') ?>" rel="stylesheet">
		<link href="<?= asset('css/login.css') ?>" rel="stylesheet">

	</head>
	<body ng-controller="loginController">

		<div class="container" style="">
			<img src="img/logotipo-interno.png">
		</div>

        <div class="container">

            <div class="col-md-4 col-xs-12"></div>

            <div class="col-md-4 col-xs-12">

                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="col-xs-12" style="padding: 0; margin-top: 10px;">

                            <div class="col-xs-12">
                                <input type="text" id="t_usuario" ng-model="t_usuario" class="form-control" placeholder="Usuario">
                            </div>
                            
                            <div class="col-xs-12" style="margin-top: 5px;">
                                <input type="password" id="t_password" ng-model="t_password" class="form-control" placeholder="Contraseña">
                            </div>

                            <div class="col-xs-12 text-center" style="margin-top: 10px;">

                                <a href="#" ng-click="showConfirm()">Recuperar Contraseña</a>
                                
                            </div>
                            
                            <div class="col-xs-12" style="margin-top: 10px;">
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


        <div class="modal fade" tabindex="-1" role="dialog" id="modalResetPassword">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header modal-header-primary">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Confirmación Cambio de Contraseña</h4>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal" name="formReset" novalidate="">

                            <div class="row">
                                <div class="col-xs-12 error">
                                    <div class="input-group">
                                        <span class="input-group-addon">Usuario: </span>
                                        <input type="text" class="form-control" name="user_reset" id="user_reset" ng-model="user_reset" placeholder="" ng-required="true">
                                    </div>
                                    <span class="help-block error"
                                          ng-show="formReset.user_reset.$invalid && formReset.user_reset.$touched">El Usuario es requerido</span>
                                </div>
                            </div>

                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            Cancelar <span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span>
                        </button>
                        <button type="button" class="btn btn-primary" ng-click="resetPassword()" ng-disabled="formReset.$invalid">
                            Enviar al Correo <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" tabindex="-1" role="dialog" id="modalMessage">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header modal-header-success">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Confirmación</h4>
                    </div>
                    <div class="modal-body">
                        <span>{{message}}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" tabindex="-1" role="dialog" id="modalMessageError" style="z-index: 99999;">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header modal-header-danger">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Información</h4>
                    </div>
                    <div class="modal-body">
                        <span>{{message_error}}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- MODAL PARA LA ACCION DE MOSTRAR MENSAJE DE CARGA -->
        <div class="modal fade" id="myModalTest" data-keyboard="false" data-backdrop="static">
            <div class="modal-dialog" style="margin-top: 200px;">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-xs-12">
                                <p style="font-size: 12px !important; font-weight: bold;">ESPERE POR FAVOR!...</p>
                            </div>
                            <div class="col-xs-12" style="margin-top: 5px;">
                                <div class="progress">
                                    <div id="bar" class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                                        <span id="text-bar-load-product" class=""></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->


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

            <div class="container">

                <div class="col-xs-12 col-sm-8 text-center" style="color: #9d1c30 !important;">
                    Copyright &copy; 2012 - <?= date('Y'); ?>

                    <a href="https://www.AquaPotable.org" target="_blank" style="color: #9d1c30 !important; font-weight: bold;">
                        www.AquaPotable.org
                    </a>
                    Digital-Fusion Cia. Ltda. Todos los derechos reservados

                </div>

                <div class="col-xs-6 col-sm-2" style="padding: 0; float: left; margin-top: -2.3%;">
                    <a href="https://www.imnegocios.com" target="_blank"><img src="img/logo-powered.png" style="width: 65%;">
                    </a>
                </div>

                <div class="col-xs-6 col-sm-1 text-left" style="padding: 0; float: left; margin-top: -2%;">
                    <a href="https://www.facebook.com/SoftverAqua/" target="_blank" style="padding: 0; float: left;">
                        <img src="img/facebook-logo.png" style="width: 30px;">
                    </a>
                </div>

            </div>







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