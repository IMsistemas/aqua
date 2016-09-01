<!DOCTYPE html>
<html lang="es-ES" ng-app="softver-aqua">
<head>
	<title>Aqua-Suministros</title>
	<link href="<?= asset('css/bootstrap.min.css') ?>" rel="stylesheet">
</head>
<body>
	<div class="container" ng-controller="suministrosController">
		<h2>Suministros</h2>
		<table class="table">
			<thead>
				<tr>
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
				<tr ng-repeat="suministro in suministros">
					<td>{{suministro.numerosuministro}}</td>
					<td>{{suministro.cliente.a 

					pellido+" "+suministro.cliente.nombre}}</td>
					<td>{{suministro.tarifa.nombretarifa}}</td>
					<td>{{suministro.calle.nombrecalle}}</td>
					<td>{{suministro.direccionsuministro}}</td>
					<td>{{suministro.telefonosuministro}}</td>
					<td>
						<button class="btn btn-success btn-xs btn-delete" ng-click="modalNuevaRecaudacion(suministro.numerosuministro)">Otros Rubros</button>
					</td>
				</tr>
			</tbody>
		</table>

		<div class="modal fade" id="nueva-recaudacion" tabindex="-1" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title" id="myModalLabel">Ingreso otros rubros</h4>
						<h5>Fecha:{{"22/04/2016"}}</h5>
					</div>
					<div class="modal-body">
						<form name="formularioRecaudacion" class="form-horizonal" novalidate="">
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
							<label>Suministro:</label>{{suministro.numerosuministro}}<br>
							<label>Cliente:</label>{{suministro.cliente.apellido+" "+suministro.cliente.nombre}}<br>
							<label>Barrio:</label> {{""}}<br>
							<label>Direccion:</label>{{suministro.direccionsuministro}}<br>
							<label>Rubros:</label>
							<table class="table">
								<thead>
									<tr>
										<th>Rubro</th>
										<th>Valor</th>
									</tr>
								</thead>
								<tbody>
									<td></td>
									<td></td>
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
        <script src="<?= asset('js/jquery.min.js') ?>"></script>
        <script src="<?= asset('js/bootstrap.min.js') ?>"></script>
        
        <!-- AngularJS Application Scripts -->
        <script src="<?= asset('app/app.js') ?>"></script>
        <script src="<?= asset('app/controllers/suministros.js') ?>"></script>
	
</body>
</html>