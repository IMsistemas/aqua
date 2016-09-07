<div ng-controller="solicitudController">
	<div class="container" style="margin-top: 2%;">
	
		<div class="col-xs-4">
			<div class="form-group has-feedback">
				<input type="text" class="form-control input-sm" id="search-list-trans" placeholder="BUSCAR..." ng-model="busqueda">
	            <span class="glyphicon glyphicon-search form-control-feedback" aria-hidden="true"></span>
	         </div> 
	     </div>
			<form class="form-inline">
			<button type="button" id="btnNuevaSol" class="btn btn-info" style="float: right;" ng-click="">Nueva</button>
			 <button type="button" id="btnNuevaSolCli" class="btn btn-info" style="float: right;" ng-click="">Nueva sol. cliente</button>
				<div class="form-group">
				    <label for="comboYear">Año</label>
				    <select class="form-control" id="comboYear"  >
				    	<option value="">Seleccione año</option>
				    	<option  value="" ></option>
				    </select>
				</div>
				<div class="form-group">
				    <label for="comboMes">Mes</label>
				    <select class="form-control" id="comboMes" >
				    	<option value="">Seleccione mes</option>
				    	<option  value="" ></option>
				    </select>
				</div>
				<div class="form-group">
				    <label for="comboEstado">Estado:</label>
				    <select class="form-control" id="comboEstado" >
				    	<option value="">Estado:</option>
				    	<option  value="" ></option>
				    </select>
				</div>
				
			</form>
			
			<div class="cos-xs-12">
				<table class="table table-responsive table-striped table-hover table-condensed">
					<thead class="bg-primary">
						<tr>
							<th>
								<a href="#" style="text-decoration:none; color:white;" >Nro. Solicitud</a>
							</th>
							<th>
								<a href="#" style="text-decoration:none; color:white;" >Fecha</a>
							</th>
							<th>
								<a href="#" style="text-decoration:none; color:white;" >Cliente</a>
							</th>
							<th>
								<a href="#" style="text-decoration:none; color:white;" >Dirección</a>
							</th>
							<th>
								<a href="#" style="text-decoration:none; color:white;" >Teléfono</a>
							</th>
							<th>
								<a href="#" style="text-decoration:none; color:white;" >Estado</a>
							</th>
							<th>
								<a href="#" style="text-decoration:none; color:white;" >Acciones</a>
							</th>
						</tr>
					</thead>
					<tbody>
						<tr ng-repeat="solicitud in solicitudes" ng-init="estaProcesada(solicitud.idsolicitud);">
							<td>{{solicitud.idsolicitud}}</td>
							<td>{{solicitud.fechasolicitud}}</td>
							<td>{{solicitud.cliente.apellido+" "+solicitud.cliente.nombre}}</td>
							<td>{{solicitud.direccionsuministro}}</td>
							<td>{{solicitud.telefonosuministro}}</td>
							<td>{{estoyProcesada}}</td>
							<td >
								<a href="#" class="btn btn-warning" >Editar</a>
								<a href="#" class="btn btn-danger" >Eliminar</a>
                                <a id="estaProcesada" href="#" class="btn btn-info" ><i class=""></i>{{procesado}}</a>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
	</div>


	<!--================Modal Nueva Solicitud=================================================================--> 

	 <div class="modal fade" id="nueva-solicitud" tabindex="-1" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header  modal-header-primary">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="myModalLabel"Nueva solicitud</h4>
						Período: {{ahora | date:'MMM yyyy'}}
					</div>

					<div class="modal-body">
						<form name="formularioOtrosRubros" class="form-horizonal" novalidate="">
							<fieldset>
								<legend>Datos suministro</legend>
								<div class="col-xs-12">
		                                <span style="font-weight: bold">No. suministro: </span>{{cuenta.suministro.numerosuministro}} 
		                        </div>
		                        <div class="col-xs-12">
		                                <span style="font-weight: bold">Cliente: </span>{{cuenta.suministro.cliente.apellido+" "+suministro.cliente.nombre}} 
		                        </div>
		                        <div class="col-xs-12">
		                                <span style="font-weight: bold">Barrio: </span>{{cuenta.suministro.calle.barrio}} 
		                        </div>
		                        <div class="col-xs-12">
		                                <span style="font-weight: bold">Dirección: </span>{{cuenta.suministro.direccionsuministro}} 
		                        </div>
							</fieldset>
							<br>
						</form>
					</div>
					<div class="modal-footer">
						<button class="btn btn-primary">Guardar</button>
					</div>
				</div>
			</div>			
		</div>

	

</div>