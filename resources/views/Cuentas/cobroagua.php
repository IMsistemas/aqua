<!DOCTYPE html>
<html lang="es-ES" ng-app="softver-aqua">
<head>
	<title>Aqua-Recaudación</title>
	<link href="<?= asset('css/bootstrap.min.css') ?>" rel="stylesheet">
</head>
<body>
	<div class="container" ng-controller="recaudacionController">
		<h2>Recaudación</h2>

		<input type="text" ng-pagination-search="cuentas" ng-pagination-size="2">
		<table class="table">
			<thead>
				<tr>
					<th>Período</th>
					<th>Nro. Suministro</th>
					<th>Cliente</th>
					<th>Tarifa</th>
					<th>Ubicación</th>
					<th>Dirección</th>
					<th>Teléfono</th>
					<th>Acciones</th>
				</tr>
			</thead>
			<tbody>
				<tr ng-pagination="cuenta in cuentas">
					<td>abril-2016</td>
					<td>{{cuenta.suministro.numerosuministro}}</td>
					<td>{{cuenta.suministro.cliente.apellido+" "+cuenta.suministro.cliente.nombre}}</td>
					<td>{{cuenta.suministro.tarifa.nombretarifa}}</td>
					<td>{{cuenta.suministro.calle.nombrecalle}}</td>
					<td>{{cuenta.suministro.direccionsuministro}}</td>
					<td>{{cuenta.suministro.telefonosuministro}}</td>
					<td>
						<button class="btn btn-success btn-xs btn-delete" ng-click="modalIngresoOtrosRubros(cuenta.suministro.numerosuministro)">Otros Rubros</button>
					</td>
				</tr>
			</tbody>
		</table>
		<ng-pagination-control pagination-id="cuentas"></ng-pagination-control>

		 <div class="modal fade" id="ingresar-otros-rubros" tabindex="-1" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title" id="myModalLabel">Ingreso otros rubros</h4>
						<h5>Fecha:{{"22/04/2016"}}</h5>
					</div>
					<div class="modal-body">
						<form name="formularioOtrosRubros" class="form-horizonal" novalidate="">
							<label>Período</label> <br>
							<label>Año</label>
							<select>
								<option>2016</option>
							</select>
							<label>mes</label>
							<select>
								<option>Agosto</option>
							</select> <br>
							<label>Datos Suministros</label><br>
							<label>Suministro:</label>{{cuenta.suministro.numerosuministro}}<br>
							<label>Cliente:</label>{{cuenta.suministro.cliente.apellido+" "+suministro.cliente.nombre}}<br>
							<label>Barrio:</label> {{""}}<br>
							<label>Direccion:</label>{{cuenta.suministro.direccionsuministro}}<br>
							<label>Rubros:</label>
							<table class="table">
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
										costo: {{costo}}	
									</tr>

									<tr ng-repeat="rubroFijo in rubrosFijos">
										<td>{{rubroFijo.nombrerubrofijo}}</td>
										<td><input type="text" name=""></td>	
									</tr>

								</tbody>
							</table>

							<button class="btn btn-success">Guardar</button>


						</form>
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