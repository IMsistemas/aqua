<div ng-controller="suministrosController">
	<div class="container" style="margin-top: 2%;">
	
		<div class="col-xs-4">
			<div class="form-group has-feedback">
				<input type="text" class="form-control input-sm" id="search-list-trans" placeholder="BUSCAR..." ng-model="busqueda">
	            <span class="glyphicon glyphicon-search form-control-feedback" aria-hidden="true"></span>
	         </div> 
	     </div>
			
			<div class="cos-xs-12">
				<table class="table table-responsive table-striped table-hover table-condensed">
					<thead class="bg-primary">
						<tr>
							<th>
								<a href="#" style="text-decoration:none; color:white;" >Nro.</a>
							</th>
							<th>
								<a href="#" style="text-decoration:none; color:white;" >Cliente</a>
							</th>
							<th>
								<a href="#" style="text-decoration:none; color:white;" >Tarifa</a>
							</th>
							<th>
								<a href="#" style="text-decoration:none; color:white;" >Zona</a>
							</th>
							<th>
								<a href="#" style="text-decoration:none; color:white;" >Dirección</a>
							</th>
							<th>
								<a href="#" style="text-decoration:none; color:white;" >Teléfono</a>
							</th>
							<th>
								<a href="#" style="text-decoration:none; color:white;" >Acciones</a>
							</th>
						</tr>
					</thead>
					<tbody>
						<tr ng-repeat="suministro in suministros" >
							<td>{{suministro.numerosuministro}}</td>
							<td>{{suministro.cliente.apellido+" "+suministro.cliente.nombre}}</td>
							<td>{{suministro.tarifa.nombretarifa}}</td>
							<td>{{suministro.calle.barrio.nombrebarrio+" - "+suministro.calle.nombrecalle}}</td>
							<td>{{suministro.direccionsumnistro}}</td>
							<td>{{suministro.telefonosuministro}}</td>
							<td >
								<a href="#" class="btn btn-info" ng-click="getSuministro(suministro.numerosuministro);">Ver</a>
								<a href="#" class="btn btn-warning" ng-click="modalEditarSuministro(suministro.numerosuministro);">Editar</a>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
	</div>

<!--====================================MODALES===================================================================-->

<!--====================================MODAL EDITAR SUMINISTROS==================================================-->
<div class="modal fade" tabindex="-1" role="dialog" id="editar-suministro">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header modal-header-primary">
                        <div class="col-md-6 col-xs-12">
                            <h4 class="modal-title">Editar suministro Nro. {{suministro.numerosuministro}} </h4>
                        </div>
                        <div class="col-md-6 col-xs-12">
                            <div class="form-group">
                                <label for="fechaingreso" class="col-sm-5 control-label">Fecha de Ingreso:</label>
                                <div class="col-sm-6" style="padding: 0;">
                                   <label >{{12/02/2016 | date : format : 'fullDate'}}</label>
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

							<fieldset>
							<legend style="padding-bottom: 5px; padding-left: 20px">Datos Cliente</legend>
                                <div class="col-xs-12">
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group error">
                                            <label class="col-sm-4 control-label">CI/Ruc:</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" name="documentoidentidad" id="documentoidentidad"
                                                       ng-model="suministro.cliente.documentoidentidad" ng-required="true" ng-maxlength="32"  >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group error">
                                            <label class="col-sm-4 control-label">Cliente</label>
                                            <div class="col-sm-8">
                                                {{suministro.cliente.apellido+" "+suministro.cliente.nombre}}
                                            </div>
                                        </div>
                                     </div>
                                </div>
                                </fieldset>
                                	
                                <fieldset>
                                	<legend style="padding-bottom: 5px; padding-left: 20px">Datos Suministro</legend>
                                	<div class="col-xs-12">
	                                    <div class="col-md-6 col-xs-12">
	                                        <div class="form-group error">
	                                            <label class="col-sm-4 control-label">Tarifa:</label>
	                                            <div class="col-sm-8">

	                                                <select class="form-control" ng-model="suministro.tarifa" ng-options="tarifa as tarifa.nombretarifa for tarifa in tarifas track by tarifa.idtarifa">
	                                                	<option>Elige tarifa</option>
`		                                            </select>

	                                            </div>
	                                        </div>
	                                    </div>
	                                    <div class="col-md-6 col-xs-12">
	                                        <div class="form-group error">
	                                            <label class="col-sm-4 control-label">Zona:</label>
	                                            <div class="col-sm-8">

	                                               <select class="form-control" ng-model="barrio" ng-options="barrio as barrio.nombrebarrio for barrio in barrios track by barrio.idbarrio">
                                                	<option value="">Seleccione Zona</option>
                                               	   </select>

                                               	   <select class="form-control" ng-model="suministro.calle" 
                                                 	ng-options="calle as calle.nombrecalle for calle in barrio.calle track by calle.idcalle" >
                                                	<option value="">{{suministro.calle.nombrecalle}}</option>
                                                	</select>

	                                            </div>
	                                        </div>
	                                    </div>
                                </div>

                                <div class="col-xs-12">
	                                    <div class="col-md-6 col-xs-12">
	                                        <div class="form-group error">
	                                            <label class="col-sm-4 control-label">Dirección:</label>
	                                            <div class="col-sm-8">
	                                                <input type="text" class="form-control" name="direccionsuministro" id="direccionsuministro"
	                                                       ng-model="suministro.direccionsumnistro" ng-required="true" ng-maxlength="32" >
	                                            </div>
	                                        </div>
	                                    </div>
	                                    <div class="col-md-6 col-xs-12">
	                                        <div class="form-group error">
	                                            <label class="col-sm-4 control-label">Telefono:</label>
	                                            <div class="col-sm-8">
	                                                <input type="text" class="form-control" name="telefonosolicitud" id="telefonosolicitud"
	                                                       ng-model="suministro.telefonosuministro" ng-required="true" ng-maxlength="32"  >
	                                            </div>
	                                        </div>
	                                    </div>
                                </div>

                                </fieldset>
                            </form>
                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" id="btn-save" ng-click="editarSuministro(suministro.numerosuministro);" ng-disabled="">Guardar</button>
                    </div>
                </div>
            </div>
        </div>



<!--====================================MODAL VER SUMINISTROS=====================================================-->
<div class="modal fade" tabindex="-1" role="dialog" id="modalVerSuministro">
            <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content">
                    <div class="modal-header modal-header-info">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Suministro No.{{suministro.numerosuministro}}</h4>
                    </div>
                    <div class="modal-body">
                        <div class="col-xs-12 text-center">
                            <img class="img-thumbnail" src="<?= asset('img/suministro.png') ?>" alt="">
                        </div>
                        <div class="row text-center">
                            <div class="col-xs-12 text-center" style="font-size: 18px;">Instalado el: 17/02/1991</div>
                      		<div class="col-xs-12">
                                <span style="font-weight: bold">Cliente:</span>{{suministro.cliente.apellido+" "+suministro.cliente.nombre}} 
                            </div>      
                            <div class="col-xs-12">
                                <span style="font-weight: bold">Tarifa:</span>{{suministro.tarifa.nombretarifa}} 
                            </div>
                            <div class="col-xs-12">
                                <span style="font-weight: bold">Zona: </span>{{suministro.calle.barrio.nombrebarrio+" - "+suministro.calle.nombrecalle}}
                            </div>
                            <div class="col-xs-12">
                                <span style="font-weight: bold">Dirección Suministro: </span>{{suministro.direccionsuministro}}
                            </div>
                            <div class="col-xs-12">
                                <span style="font-weight: bold">Teléfono: </span>{{suministro.telefonosuministro}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


<!--=================================Modal Confirmacion====================================-->
		 <div class="modal fade" tabindex="-1" role="dialog" id="modalConfirmacion">
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


<!--=================================Modal Error====================================-->
        <div class="modal fade" tabindex="-1" role="dialog" id="modalError">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header modal-header-danger">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Error</h4>
                    </div>
                    <div class="modal-body">
                        <span>{{mensajeError}}</span>
                    </div>
                </div>
            </div>
        </div>
	





</div>