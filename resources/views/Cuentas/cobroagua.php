<!DOCTYPE html>
<html lang="es-ES" ng-app="softver-aqua">
<head>
	<title>Aqua-Recaudación</title>
	<link href="<?= asset('css/bootstrap.min.css') ?>" rel="stylesheet">
	<link href="<?= asset('css/modal.css') ?>" rel="stylesheet">
</head>
<body>
	<div class="container" ng-controller="recaudacionController">
		<h2>Recaudación</h2>

		<input type="text" ng-pagination-search="cuentas" >
		<table class="table table-bordered table-hover">
			<thead class="">
				<tr>
					<th>Período</th>
					<th>Nro. Suministro</th>
					<th>Cliente</th>
					<th>Tarifa</th>
					<th>Ubicación</th>
					<th>Dirección</th>
					<th>Teléfono</th>
					<th>Consumo m3</th>
					<th>Total a pagar</th>
					<th>Acciones</th>
				</tr>
			</thead>
			<tbody>
				<tr ng-pagination="cuenta in cuentas" ng-pagination-size="2">
					<td>{{cuenta.fechaperiodo | date:'MMM yyyy'}}</td>
					<td>{{cuenta.suministro.numerosuministro}}</td>
					<td>{{cuenta.suministro.cliente.apellido+" "+cuenta.suministro.cliente.nombre}}</td>
					<td>{{cuenta.suministro.tarifa.nombretarifa}}</td>
					<td>{{cuenta.suministro.calle.nombrecalle}}</td>
					<td>{{cuenta.suministro.direccionsuministro}}</td>
					<td>{{cuenta.suministro.telefonosuministro}}</td>
					<td>{{cuenta.consumo}}</td>
					<td>{{cuenta.total}}</td>
					
					<td>
						<button class="btn btn-success btn-xs btn-delete" ng-click="modalIngresoOtrosRubros(cuenta.suministro.numerosuministro)">Ver cuenta</button>
						<button class="btn btn-success btn-xs btn-delete" ng-click="modalIngresoOtrosRubros(cuenta.suministro.numerosuministro)">Otros Rubros</button>
					</td>
				</tr>
			</tbody>
		</table>
		<ng-pagination-control pagination-id="cuentas"></ng-pagination-control>

		 <div class="modal fade" id="ingresar-otros-rubros" tabindex="-1" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header  before">
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