<!DOCTYPE html>
<html lang="es-ES" ng-app="softver-aqua">
<head>
	<title>Aqua-Recaudación</title>
	<link href="<?= asset('css/bootstrap.min.css') ?>" rel="stylesheet">
	<link href="<?= asset('css/style_generic_app.css') ?>" rel="stylesheet">
</head>
<body>
	<div class="container" ng-controller="recaudacionController" style="margin-top: 2%;">
		 <legend style="padding-bottom: 10px;">
                    <span style="font-weight: bold;">RECAUDACIÓN</span>
          </legend>

		<div class="col-xs-6">
            <div class="form-group has-feedback">
                <input type="text" class="form-control input-sm" id="search-list-trans" placeholder="BUSCAR..." ng-model="busqueda">
                <span class="glyphicon glyphicon-search form-control-feedback" aria-hidden="true"></span>
            </div>
         </div>
		<div class="col-xs-12">
			<table class="table table-responsive table-striped table-hover table-condensed">
				<thead class="bg-primary">
					<tr>
						<th style="width:8%"><a href="#" ng-click="columna = 'cuenta.fechaperiodo'; reversa = !reversa;">Período</a></th>
						<th style="width:8%"><a href="#" ng-click="columna = 'cuenta.suministro.numerosuministro'; reversa = !reversa;">N.Sum</a></th>
						<th style="width:25%"><a href="#" ng-click="columna = 'cuenta.suministro.cliente.apellido'; reversa = !reversa">Cliente</a></th>
						<th><a href="#" ng-click="columna = 'cuenta.suministro.tarifa.nombretarifa'; reversa = !reversa;">Tarifa</a></th>
						<th><a href="#" ng-click="columna = ''; reversa = !reversa;">Ubicación</a></th>
						<th style= "width:25%">Dirección</th>
						<th>Telf.</th>
						<th style="width:5%" ng-click="columna = ''; reversa = !reversa;"><a href="#">m<sup>3</sup></a></th>
						<th><a href="#" ng-click="columna = 'cuenta.consumo'; reversa = !reversa;">Total</a></th>
						<th style="width: 25%;" >Acciones</th>
					</tr>
				</thead>
				<tbody>
					<tr ng-repeat="cuenta in cuentas | filter: busqueda | orderBy:columna:reversa" >
						<td>{{cuenta.fechaperiodo | date:'MMM yyyy'}}</td>
						<td>{{cuenta.suministro.numerosuministro}}</td>
						<td>{{cuenta.suministro.cliente.apellido+" "+cuenta.suministro.cliente.nombre}}</td>
						<td>{{cuenta.suministro.tarifa.nombretarifa}}</td>
						<td>{{cuenta.suministro.calle.nombrecalle}}</td>
						<td>{{cuenta.suministro.direccionsuministro}}</td>
						<td>{{cuenta.suministro.telefonosuministro}}</td>
						<td>{{cuenta.consumo}}</td>
						<td>{{25.00 | currency}}</td>
						
						<td>
							<a href="#" class="btn btn-primary" ng-click="modalIngresoOtrosRubros(cuenta.suministro.numerosuministro)">Otros rubros</a>
                            <a href="#" class="btn btn-info" ng-click="modalIngresoOtrosRubros(cuenta.suministro.numerosuministro)">Ver cuenta</a>
						</td>
					</tr>
				</tbody>
			</table>
		</div>

		 <div class="modal fade" id="ingresar-otros-rubros" tabindex="-1" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header  modal-header-primary">
						<h4 class="modal-title" id="myModalLabel">Ingreso otros rubros</h4>
						Período: {{cuenta.fechaperiodo | date:'MMM yyyy'}}
					</div>

					<div class="modal-body">
						<form name="formularioOtrosRubros" class="form-horizonal" novalidate="">
							
						<div class="seccion">
						  Datos Suministros
						</div>
							
							Nro. Suministro: {{cuenta.suministro.numerosuministro}}<br>
							Cliente: {{cuenta.suministro.cliente.apellido+" "+suministro.cliente.nombre}}<br>
							Barrio: {{cuenta.suministro.calla.barrio}}<br>
							Direccion: {{cuenta.suministro.direccionsuministro}}<br>
							Rubros <hr>
							<table class="table table-bordered table-hover">
								<thead>
									<tr>
										<th>Rubro</th>
										<th>Valor</th>
									</tr>
								</thead>
								<tbody>
									<tr ng-repeat="rubroVariable in rubrosVariables">
										<td>{{rubroVariable.nombrerubrovariable}}</td>
										<td><input type="text" name="" ng-model="costo"></td>	
									</tr>
									<tr ng-repeat="rubroFijo in rubrosFijos">
										<td>{{rubroFijo.nombrerubrofijo}}</td>
										<td><input type="text" name=""></td>	
									</tr>
								</tbody>
							</table>
						</form>
					</div>
					<div class="modal-footer">
						<button class="btn btn-primary">Guardar</button>
						<button class="btn btn-default" data-dismiss="modal" >Cerrar</button>
					</div>
				</div>
			</div>			
		</div>

		
	</div>

	 	<script src="<?= asset('app/lib/angular/angular.min.js') ?>"></script>
	 	<script src="<?= asset('app/lib/angular/angular-pagination.js') ?>"></script>
        <script src="<?= asset('js/jquery.min.js') ?>"></script>
        <script src="<?= asset('js/bootstrap.min.js') ?>"></script>
        
        <!-- AngularJS Application Scripts -->
        <script src="<?= asset('app/app.js') ?>"></script>
        <script src="<?= asset('app/controllers/recaudacionController.js') ?>"></script>
	
</body>
</html>