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
			 <button type="button" id="btnNuevaSolCli" class="btn btn-info" style="float: right;" ng-click="modalNuevaSolicitudCliente(1);">Nueva sol. cliente</button>
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


	<!--================Modal Nueva Solicitud Cliente=================================================================--> 

	  <div class="modal fade" tabindex="-1" role="dialog" id="nueva-solicitud-cliente">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header modal-header-primary">
                        <div class="col-md-6 col-xs-12">
                            <h4 class="modal-title">Ingresar Solicitud Nro. 0001</h4>
                        </div>
                        <div class="col-md-6 col-xs-12">
                            <div class="form-group">
                                <label for="fechaingreso" class="col-sm-5 control-label">Fecha de Ingreso:</label>
                                <div class="col-sm-6" style="padding: 0;">
                                   {{ahora | date : fromat : 'fullDate'}}
                                </div>
                                <div class="col-sm-1 col-xs-12 text-right" style="padding: 0;">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-body">

                        <div class="row">
                            <form class="form-horizontal" name="formNuevaSolicitud" novalidate="">

                                <div class="col-xs-12">
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group error">
                                            <label class="col-sm-4 control-label">Documento:</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" name="documentoidentidadcliente" id="documentoidentidadempleado"
                                                       ng-model="documentoidentidadcliente" ng-required="true" ng-maxlength="32"  >
                                                       {{documentoidentidadcliente}}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Cliente:</label>
                                            <div class="col-sm-8">
                                                {{clienteActual.apellido+" "+clienteActual.nombre | uppercase}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12">
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group error">
                                            <label class="col-sm-4 control-label">Dirección:</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" name="direccion" id="direccion"
                                                       ng-model="apellido" ng-required="true" ng-maxlength="32" >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group error">
                                            <label class="col-sm-4 control-label">Teléfono:</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" name="telefono" id="telefono"
                                                       ng-model="telefono" ng-required="true" ng-maxlength="32"  >
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" id="btn-save" ng-click="" ng-disabled="">Guardar</button>
                    </div>
                </div>
            </div>
        </div>



        <!--================Modal Nueva Solicitud======================================================================--> 

	  <div class="modal fade" tabindex="-1" role="dialog" id="nueva-solicitud">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header modal-header-primary">
                        <div class="col-md-6 col-xs-12">
                            <h4 class="modal-title">Ingresar Solicitud Nro. 0001 </h4>
                        </div>
                        <div class="col-md-6 col-xs-12">
                            <div class="form-group">
                                <label for="fechaingreso" class="col-sm-5 control-label">Fecha de Ingreso:</label>
                                <div class="col-sm-6" style="padding: 0;">
                                   {{ahora | date : fromat : 'fullDate'}}
                                </div>
                                <div class="col-sm-1 col-xs-12 text-right" style="padding: 0;">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-body">

                        <div class="row">
                            <form class="form-horizontal" name="formNuevaSolicitud" novalidate="">

                                <div class="col-xs-12">
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group error">
                                            <label class="col-sm-4 control-label">Documento:</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" name="documentoidentidadcliente" id="documentoidentidadempleado"
                                                       ng-model="documentoidentidadcliente" ng-required="true" ng-maxlength="32"  >
                                                       {{documentoidentidadcliente}}
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="col-xs-12">
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group error">
                                            <label class="col-sm-4 control-label">Apellidos:</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" name="direccion" id="direccion"
                                                       ng-model="apellido" ng-required="true" ng-maxlength="32" >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group error">
                                            <label class="col-sm-4 control-label">Nombres:</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" name="telefono" id="telefono"
                                                       ng-model="telefono" ng-required="true" ng-maxlength="32"  >
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xs-12">
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group error">
                                            <label class="col-sm-4 control-label">Telf. Principal:</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" name="direccion" id="direccion"
                                                       ng-model="apellido" ng-required="true" ng-maxlength="32" >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group error">
                                            <label class="col-sm-4 control-label">Telf. Secundario:</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" name="telefono" id="telefono"
                                                       ng-model="telefono" ng-required="true" ng-maxlength="32"  >
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xs-12">
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group error">
                                            <label class="col-sm-4 control-label">Celular:</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" name="direccion" id="direccion"
                                                       ng-model="apellido" ng-required="true" ng-maxlength="32" >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group error">
                                            <label class="col-sm-4 control-label">Dirección:</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" name="telefono" id="telefono"
                                                       ng-model="telefono" ng-required="true" ng-maxlength="32"  >
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" id="btn-save" ng-click="" ng-disabled="">Guardar</button>
                    </div>
                </div>
            </div>
        </div>

	

</div>