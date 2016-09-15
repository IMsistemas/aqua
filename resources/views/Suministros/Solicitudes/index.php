<div ng-controller="solicitudController">
	<div class="container" style="margin-top: 2%;">
	
		<div class="col-xs-4">
			<div class="form-group has-feedback">
				<input type="text" class="form-control input-sm" id="search-list-trans" placeholder="BUSCAR..." ng-model="busqueda.cliente.apellido">
	            <span class="glyphicon glyphicon-search form-control-feedback" aria-hidden="true"></span>
	         </div> 
	     </div>
			<form class="form-inline">
			<button type="button" id="btnNuevaSol" class="btn btn-primary" style="float: right;" ng-click="modalNuevaSolicitud();">Nueva</button>
			 
				<div class="form-group">
				    <label for="comboYear" ng-show="false">Año</label>
				    <select ng-show="false" class="form-control" id="comboYear"  >
				    	<option value="">Seleccione año</option>
				    	<option  value="" ></option>
				    </select>
				</div>
				<div class="form-group">
				    <label for="comboMes" ng-show="false">Mes</label>
				    <select ng-show="false" class="form-control" id="comboMes" >
				    	<option value="">Seleccione mes</option>
				    	<option  value="" ></option>
				    </select>
				</div>
				<div class="form-group">
				    <label for="comboEstado" ng-show="{{estado}}">Estado:</label>
				    <select ng-show="{{estado}}" class="form-control" ng-model="busqueda.estaprocesada" >
				    	<option value="">Todos</option>
                        <option value="false" selected>En espera</option>
				    	<option value="true" >Procesada</option>
				    </select>
				</div>
				
			</form>
			
			<div class="cos-xs-12">
				<table class="table table-responsive table-striped table-hover table-condensed">
					<thead class="bg-primary">
						<tr>
							<th>
								<a href="#" style="text-decoration:none; color:white;" ng-click="ordenarColumna='idsolicitud'; reversa=!reversa;">Nro. Solicitud</a>
							</th>
							<th>
								<a href="#" style="text-decoration:none; color:white;" ng-click="ordenarColumna='fechasolicitud'; reversa=!reversa;">Fecha</a>
							</th>
							<th>
								<a href="#" style="text-decoration:none; color:white;" ng-click="ordenarColumna='cliente.apellido'; reversa=!reversa;">Cliente</a>
							</th>
							<th>
								Dirección
							</th>
							<th>
								Teléfono
							</th>
							<th>
								<a href="#" style="text-decoration:none; color:white;" ng-click="ordenarColumna='estaprocesada'; reversa=!reversa;">Estado</a>
							</th>
							<th>
								Acciones
							</th>
						</tr>
					</thead>
					<tbody>
						<tr ng-repeat="solicitud in solicitudes | filter: busqueda | orderBy:ordenarColumna:reversa" >
							<td>{{solicitud.idsolicitud}}</td>
							<td>{{solicitud.fechasolicitud | date : format : 'fullDate'}}</td>
							<td>{{solicitud.cliente.apellido+" "+solicitud.cliente.nombre}}</td>
							<td>{{solicitud.direccionsuministro}}</td>
							<td>{{solicitud.telefonosuministro}}</td>
							<td ng-show="solicitud.estaprocesada==true"><span>Procesada</span></td>
							<td ng-show="solicitud.estaprocesada==false"><span>En espera</span></td>
							<td >
								<a href="#" class="btn btn-warning" ng-show="solicitud.estaprocesada==false">Editar</a>

                                <a href="#" class="btn btn-info" ng-show="solicitud.estaprocesada==true" ng-click="modalVerSolicitud(solicitud.idsolicitud);">Ver</a>


								<a href="#" class="btn btn-danger" ng-hide="solicitud.estaprocesada==true" ng-click="modalEliminarSolicitud(solicitud.idsolicitud); ">Eliminar</a>

                                <a id="procesar" href="#" class="btn btn-success" ng-show="solicitud.estaprocesada==false" ng-click="modalProcesaSolicitud(solicitud.idsolicitud);"><i class="fa fa-check fa-lg" aria-hidden="true" ></i></a>

                                <a id="pdf" href="#" class="btn btn-danger" ng-show="solicitud.estaprocesada==true"><i class="fa fa-file-pdf-o fa-lg" aria-hidden="true"></i></a>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
	</div>


	


        <!--================Modal Nueva Solicitud======================================================================--> 

	  <div class="modal fade" tabindex="-1" role="dialog" id="nueva-solicitud">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header modal-header-primary">
                        <div class="col-md-6 col-xs-12">
                            <h4 class="modal-title">Ingresar Solicitud Nro. {{cantidadSolicitudes+1}} </h4>
                        </div>
                        <div class="col-md-6 col-xs-12">
                            <div class="form-group">
                                <label for="fechaingreso" class="col-sm-5 control-label">Fecha de Ingreso:</label>
                                <div class="col-sm-6" style="padding: 0;">
                                   <label ng-model="solicitud.cliente.fechaingreso">{{ahora | date : format : 'fullDate'}}</label>
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
                                            <label class="col-sm-4 control-label">CI/RUC:</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" name="documentoidentidad" id="documentoidentidad"
                                                       ng-model="solicitud.cliente.documentoidentidad" ng-required="true" ng-minlength ="10" ng-maxlength ="32" ng-pattern="/^[0-9]+$/">
                                                        <span class="help-inline" ng-show="formNuevaSolicitud.documentoidentidad.$invalid">El documento del cliente es requerido <br></span>
                                                        <span class="help-inline" 
                                                        ng-show="formNuevaSolicitud.documentoidentidad.$error.pattern">Sólo se permiten números <br></span>
                                                        <span class="help-inline" 
                                                        ng-show=" formNuevaSolicitud.documentoidentidad.$error.minlength">La longitud mínima es de 10 caracteres <br></span>
                                                        <span class="help-inline" 
                                                        ng-show=" formNuevaSolicitud.documentoidentidad.$error.maxlength">La longitud máxima es de 32 caracteres</span>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group error">
                                            <label class="col-sm-4 control-label">Correo:</label>
                                            <div class="col-sm-8">
                                                <input type="amail" class="form-control" name="correo" id="correro"
                                                       ng-model="solicitud.cliente.correo" ng-required="true" ng-maxlength="32" >
                                                       <span class="help-inline" ng-show="formNuevaSolicitud.correo.$invalid">El el correo del cliente es requerido <br></span>
                                                       <span class="help-inline" ng-show="formNuevaSolicitud.correo.$error.maxlength">La longitud máxima es de 32 caracteres <br></span>
                                                        <span class="help-inline" ng-show="formNuevaSolicitud.correo.$error.email">No es un correo valido <br></span>
                                            </div>
                                        </div>
                                     </div>

                                    
                                </div>
                                <div class="col-xs-12">
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group error">
                                            <label class="col-sm-4 control-label">Apellidos:</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" name="apellido" id="apellido"
                                                       ng-model="solicitud.cliente.apellido" ng-required="true" ng-maxlength="32" ng-pattern="/^[a-zA-Z]+(\s*[a-zA-Z]*)*[a-zA-Z]+$/">
                                                       <span class="help-inline" ng-show="formNuevaSolicitud.apellido.$invalid">El apellido del cliente es requerido <br></span>
                                                        <span class="help-inline" 
                                                        ng-show="formNuevaSolicitud.apellido.$error.maxlength">La longitud máxima es de 32 caracteres <br></span>
                                                        <span class="help-inline" 
                                                        ng-show="formNuevaSolicitud.apellido.$error.pattern">Sólo se aceptan caracteres alfabeticos <br></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group error">
                                            <label class="col-sm-4 control-label">Nombres:</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" name="nombre" id="nombre"
                                                       ng-model="solicitud.cliente.nombre" ng-required="true" ng-maxlength ="32" ng-pattern="/^[a-zA-Z]+(\s*[a-zA-Z]*)*[a-zA-Z]+$/" >
                                                        <span class="help-inline" ng-show="formNuevaSolicitud.nombre.$invalid">El nombre del cliente es requerido <br></span>
                                                        <span class="help-inline" 
                                                        ng-show="formNuevaSolicitud.nombre.$error.maxlength">La longitud máxima es de 32 caracteres <br></span>
                                                        <span class="help-inline" 
                                                        ng-show="formNuevaSolicitud.nombre.$error.pattern">Sólo se aceptan caracteres alfanumericos <br></span>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xs-12">
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group error">
                                            <label class="col-sm-4 control-label">Telf. Principal:</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" name="telefonoprincipal" id="telefonoprincipal"
                                                       ng-model="solicitud.cliente.telefonoprincipal" ng-maxlength ="16" ng-pattern="/^[0-9]+$/" >
                                                       <span class="help-inline" ng-show="formNuevaSolicitud.telefonoprincipal.$invalid">El teléfono principal del cliente es requerido <br></span>
                                                       <span class="help-inline" 
                                                        ng-show="formNuevaSolicitud.telefonoprincipal.$error.pattern">Sólo se permiten números <br></span>
                                                        <span class="help-inline" 
                                                        ng-show="formNuevaSolicitud.telefonoprincipal.$error.maxlength">La longitud máxima es de 16 caracteres <br></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group error">
                                            <label class="col-sm-4 control-label">Telf. Secundario:</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" name="telefonosecundario" id="telefonosecundario"
                                                       ng-model="solicitud.cliente.telefonosecundario" ng-required="true" ng-maxlength ="16"  ng-pattern="/^[0-9]+$/"  >
                                                       <span class="help-inline" ng-show="formNuevaSolicitud.telefonosecundario.$invalid">El teléfono secundario del cliente es requerido <br></span>
                                                       <span class="help-inline" 
                                                        ng-show="formNuevaSolicitud.telefonosecundario.$error.pattern">Sólo se permiten números <br></span>
                                                        <span class="help-inline" 
                                                        ng-show="formNuevaSolicitud.telefonosecundario.$error.maxlength">La longitud máxima es de 16 caracteres <br></span>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xs-12">
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group error">
                                            <label class="col-sm-4 control-label">Celular:</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" name="celular" id="celular"
                                                       ng-model="solicitud.cliente.celular" ng-required="true" ng-maxlength ="16" ng-pattern="/^[0-9]+$/">
                                                       <span class="help-inline" ng-show=" formNuevaSolicitud.celular.$invalid">El celular del cliente es requerido <br></span>
                                                       <span class="help-inline" 
                                                        ng-show="formNuevaSolicitud.celular.$error.pattern">Sólo se permiten números <br></span>
                                                        <span class="help-inline" 
                                                        ng-show=" formNuevaSolicitud.celular.$error.maxlength">La longitud máxima es de 16 caracteres <br></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group error">
                                            <label class="col-sm-4 control-label">Dirección:</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" name="direccion" id="direccion"
                                                       ng-model="solicitud.cliente.direccion" ng-required="true" ng-maxlength="32"  >
                                                       <span class="help-inline" ng-show="formNuevaSolicitud.direccion.$invalid">El dirección del cliente es requerido <br></span>                         
                                                        <span class="help-inline" 
                                                        ng-show="formNuevaSolicitud.direccion.$error.maxlength">La longitud máxima es de 32 caracteres <br></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </fieldset>
                                	
                                <fieldset>
                                	<legend style="padding-bottom: 5px; padding-left: 20px">Datos Instalacion</legend>
                                	<div class="col-xs-12">
	                                    <div class="col-md-6 col-xs-12">
	                                        <div class="form-group error">
	                                            <label class="col-sm-4 control-label">Dirección:</label>
	                                            <div class="col-sm-8">
	                                                <input type="text" class="form-control" name="direccionsolicitud" id="direccionsolicitud"
	                                                       ng-model="solicitud.direccionsuministro" ng-required="true" ng-maxlength="32" >
                                                           <span class="help-inline" ng-show="formNuevaSolicitud.direccionsolicitud.$invalid">El dirección del cliente es requerido <br></span>                         
                                                        <span class="help-inline" 
                                                        ng-show="formNuevaSolicitud.direccionsolicitud.$error.maxlength">La longitud máxima es de 32 caracteres <br></span>

	                                            </div>
	                                        </div>
	                                    </div>
	                                    <div class="col-md-6 col-xs-12">
	                                        <div class="form-group error">
	                                            <label class="col-sm-4 control-label">Telefono:</label>
	                                            <div class="col-sm-8">
	                                                <input type="text" class="form-control" name="telefonosolicitud" id="telefonosolicitud"
	                                                       ng-model="solicitud.telefonosuministro" ng-required="true" ng-maxlength ="16" ng-pattern="/^[0-9]+$/">
                                                           <span class="help-inline" ng-show="formNuevaSolicitud.telefonosolicitud.$invalid">El teléfono principal del cliente es requerido <br></span>
                                                       <span class="help-inline" 
                                                        ng-show="formNuevaSolicitud.telefonosolicitud.$error.pattern">Sólo se permiten números <br></span>
                                                        <span class="help-inline" 
                                                        ng-show="formNuevaSolicitud.telefonosolicitud.$error.maxlength">La longitud máxima es de 16 caracteres <br></span>
	                                            </div>
	                                        </div>
	                                    </div>
                                </div>

                                </fieldset>
                            </form>
                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" id="btn-save" ng-click="guardarNuevoCliente();" ng-disabled="">Guardar</button>
                    </div>
                </div>
            </div>
        </div>

<!--====================================Modal Editar Solicitud============================-->

<div class="modal fade" tabindex="-1" role="dialog" id="eidtarSolicitud">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header modal-header-primary">
                        <div class="col-md-6 col-xs-12">
                            <h4 class="modal-title">Editar Solicitud Nro. {{cantidadSolicitudes+1}} </h4>
                        </div>
                        <div class="col-md-6 col-xs-12">
                            <div class="form-group">
                                <label for="fechaingreso" class="col-sm-5 control-label">Fecha de Ingreso:</label>
                                <div class="col-sm-6" style="padding: 0;">
                                   <label ng-model="solicitud.cliente.fechaingreso">{{solicitud.fechaingreso | date : format : 'fullDate'}}</label>
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
                                                <label class="col-sm-4 control-label">Cliente:</label>
                                                <div class="col-sm-8">
                                                    {{solicitud.cliente.apellido+" "+solicitud.cliente.nombre}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-xs-12">
                                            <div class="form-group error">
                                                <label class="col-sm-4 control-label">Telefono:</label>
                                                <div class="col-sm-8">
                                                </div>
                                            </div>
                                        </div>
                                </div>
                                
                                </fieldset>
                                    
                                <fieldset>
                                    <legend style="padding-bottom: 5px; padding-left: 20px">Datos Instalacion</legend>
                                    <div class="col-xs-12">
                                        <div class="col-md-6 col-xs-12">
                                            <div class="form-group error">
                                                <label class="col-sm-4 control-label">Dirección:</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" name="direccionsolicitud" id="direccionsolicitud"
                                                           ng-model="solicitud.direccionsuministro" ng-required="true" ng-maxlength="32" >
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-xs-12">
                                            <div class="form-group error">
                                                <label class="col-sm-4 control-label">Telefono:</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" name="telefonosolicitud" id="telefonosolicitud"
                                                           ng-model="solicitud.telefonosuministro" ng-required="true" ng-maxlength ="16" ng-pattern="/^[0-9]+$/">
                                                           <span class="help-inline" ng-show="formNuevaSolicitud.telefonosolicitud.$invalid">El teléfono principal del cliente es requerido <br></span>
                                                       <span class="help-inline" 
                                                        ng-show="formNuevaSolicitud.telefonosolicitud.$error.pattern">Sólo se permiten números <br></span>
                                                        <span class="help-inline" 
                                                        ng-show="formNuevaSolicitud.telefonosolicitud.$error.maxlength">La longitud máxima es de 16 caracteres <br></span>
                                                </div>
                                            </div>
                                        </div>
                                </div>

                                </fieldset>
                            </form>
                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" id="btn-save" ng-click="editarSolicitud();" ng-disabled="">Guardar</button>
                    </div>
                </div>
            </div>
        </div>





<!--=====================================Modal Procesar===================================-->

<div class="modal fade" tabindex="-1" role="dialog" id="procesar-solicitud">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header modal-header-primary">
                        <div class="col-md-6 col-xs-12">
                            <h4 class="modal-title">Procesar Solicitud: {{procesarSolicitud.idsolicitud}} </h4>
                        </div>
                        <div class="col-md-6 col-xs-12">
                            <div class="form-group">
                                <label  class="col-sm-5 control-label">Fecha: {{ahora | date : format : 'fullDate'}}</label>
                                <div class="col-sm-6" style="padding: 0;">
              
                                </div>
                                <div class="col-sm-1 col-xs-12 text-right" style="padding: 0;">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-body">

                        <div class="row">
                            <form class="form-horizontal" name="formProcesarSolicitud" novalidate="">

							<fieldset>
							<legend style="padding-bottom: 5px; padding-left: 20px">Datos Solicitud</legend>
                                <div class="col-xs-12">
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group error">
                                            <label class="col-sm-4 control-label">Cliente: </label>
                                            <div class="col-sm-8">
                                                {{procesarSolicitud.cliente.apellido+" "+procesarSolicitud.cliente.nombre}}
                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group error">
                                            <label class="col-sm-4 control-label">Telefono:</label>
                                            <div class="col-sm-8">
                                                {{procesarSolicitud.telefonosuministro}}
                                            </div>
                                        </div>
                                     </div>

                                    
                                </div>
                                <div class="col-xs-12">
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group error">
                                            <label class="col-sm-4 control-label">Dirección:</label>
                                            <div class="col-sm-8">
                                                {{procesarSolicitud.direccionsuministro}}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        
                                    </div>
                                </div>

                                </fieldset>
                                <fieldset>
                                	<legend style="padding-bottom: 5px; padding-left: 20px">Datos Suministro</legend>

                                <div class="col-xs-12">
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group error">
                                            <label class="col-sm-4 control-label">Nro. Suministro:</label>
                                            <div class="col-sm-8">
                                                {{cantidadSuministros+1}}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group error">
                                            <label class="col-sm-4 control-label">Tarifa:</label>
                                            <div class="col-sm-8">
                                            	<select class="form-control" ng-model="suministro.tarifa" ng-options="tarifa as tarifa.nombretarifa for tarifa in tarifas track by tarifa.idtarifa">
	                                                	<option>Elige tarifa</option>
	                                            </select>
                                        	</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xs-12">
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group error">
                                            <label class="col-sm-4 control-label">Zona:</label>
                                            <div class="col-sm-8">
                                                <select class="form-control" ng-model="barrio" ng-options="barrio as barrio.nombrebarrio for barrio in barrios track by barrio.idbarrio">
                                                	<option>Seleccione Zona</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group error">
                                            <label class="col-sm-4 control-label">Transversal:</label>
                                            <div class="col-sm-8">
                                                <select class="form-control" ng-model="suministro.calle" 
                                                ng-options="calle as calle.nombrecalle for calle in barrio.calle track by calle.idcalle" >
                                                	<option>Seleccione transversal</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </fieldset>
                                	
                                <fieldset>
                                	<legend style="padding-bottom: 5px; padding-left: 20px">Datos Costo</legend>
                                	<div class="col-xs-12">
	                                    <div class="col-md-6 col-xs-12">
	                                        <div class="form-group error">
	                                            <label class="col-sm-4 control-label">Derecho acometida:</label>
	                                            <div class="col-sm-8">
	                                                <input type="text" class="form-control" name="acometida" id="acometida"
	                                                       ng-model="acometida" ng-required="true" ng-pattern="/^[0-9]+([.][0-9]+)?$/">
                                                            <span class="help-inline" ng-show="formProcesarSolicitud.acometida.$invalid">El costo del medidor es requerido <br></span>
                                                            <span class="help-inline" 
                                                            ng-show="formProcesarSolicitud.acometida.$error.pattern">Sólo se permiten números <br></span>
	                                            </div>
	                                        </div>
	                                    </div>
	                                    <div class="col-md-6 col-xs-12">
	                                        <div class="form-group error">
	                                            <label class="col-sm-4 control-label">Costo Medidor:</label>
	                                            <div class="col-sm-8">
	                                                <input type="text" class="form-control" name="costoproducto" id="costoproducto"
	                                                       ng-model="producto.costoproducto" ng-required="true" ng-pattern="/^[0-9]+([.][0-9]+)?$/">
                                                            <span class="help-inline" ng-show="formProcesarSolicitud.costoproducto.$invalid">El costo del medidor es requerido <br></span>
                                                            <span class="help-inline" 
                                                            ng-show="formProcesarSolicitud.costoproducto.$error.pattern">Sólo se permiten números <br></span>                                    
	                                            </div>
	                                        </div>
	                                    </div>
	                                </div>

	                                <div class="col-xs-12">
	                                    <div class="col-md-6 col-xs-12">
	                                        <div class="form-group error">
	                                            <label class="col-sm-4 control-label">Cuota inicial:</label>
	                                            <div class="col-sm-8">
	                                                <input type="text" class="form-control" name="cuotainicial" id="cuotainicial"
	                                                       ng-model="cuotainicial" ng-required="true" ng-pattern="/^[0-9]+([.][0-9]+)?$/">
                                                            <span class="help-inline" ng-show="formProcesarSolicitud.cuotainicial.$invalid">El costo del medidor es requerido <br></span>
                                                            <span class="help-inline" 
                                                            ng-show="formProcesarSolicitud.cuotainicial.$error.pattern">Sólo se permiten números <br></span>
	                                            </div>
	                                        </div>
	                                    </div>
	                                    <div class="col-md-6 col-xs-12">
	                                        <div class="form-group error">
	                                            <label class="col-sm-4 control-label">Crédito:</label>
	                                            <div class="col-sm-8">
	                                                <select class="form-control">
	                                                	
	                                                	<option ng-repeat="meses in nDividendos">{{meses}}</option>
	                                                </select>
	                                            </div>
	                                        </div>
	                                    </div>
                                </div>

                                 <div class="col-xs-12">
	                                    <div class="col-md-6 col-xs-12">
	                                        <div class="form-group error">
	                                            <label class="col-sm-4 control-label">Garantia apertura calle:</label>
	                                            <div class="col-sm-8">
	                                                <input type="text" class="form-control" name="garantiaaperturacalle" id="garantiaaperturacalle"
	                                                       ng-model="configuracion.garantiaaperturacalle" ng-required="true" ng-pattern="/^[0-9]+([.][0-9]+)?$/">
                                                            <span class="help-inline" ng-show="frmClientes.garantiaaperturacalle.$invalid">El costo del medidor es requerido <br></span>
                                                            <span class="help-inline" 
                                                            ng-show="frmClientes.garantiaaperturacalle.$error.pattern">Sólo se permiten números <br></span>
	                                            </div>
	                                        </div>
	                                    </div>
	                                    <div class="col-md-6 col-xs-12">
	                                        
	                                    </div>
                                </div>

	                                </fieldset>
                            </form>
                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" id="btn-save"  ng-click="procesaSolicitud(procesarSolicitud.idsolicitud);">Procesar</button>
                    </div>
                </div>
            </div>
        </div>



<!--=================================Modal Confirmacion====================================-->
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


<!--=================================Modal Error====================================-->
        <div class="modal fade" tabindex="-1" role="dialog" id="modalMessageError">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header modal-header-danger">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Error</h4>
                    </div>
                    <div class="modal-body">
                        <span>{{messageError}}</span>
                    </div>
                </div>
            </div>
        </div>
	
<!--=================================Modal Confirmacion eliminar====================================-->


    <div class="modal fade" tabindex="-1" role="dialog" id="modalConfirmDelete">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header modal-header-danger">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Confirmación</h4>
                    </div>
                    <div class="modal-body">
                        <span>Realmente desea eliminar la solicitud</span>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" id="btn-save" ng-click="eliminarSolicitud();">Eliminar</button>
                    </div>
                </div>
            </div>
        </div>


<!--=================================Modal Ver====================================-->

    <div class="modal fade" tabindex="-1" role="dialog" id="modalInfoSolicitud">
            <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content">
                    <div class="modal-header modal-header-info">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Solicitud No.{{solicitud.idsolicitud}}</h4>
                    </div>
                    <div class="modal-body">
                        <div class="col-xs-12 text-center">
                            <img class="img-thumbnail" src="<?= asset('img/solicitud.png') ?>" alt="">
                        </div>
                        <div class="row text-center">
                            <div class="col-xs-12 text-center" style="font-size: 18px;">{{solicitud.cliente.apellido+" "+solicitud.cliente.nombre}}</div>
                            
                      
                            <div class="col-xs-12">
                                <span style="font-weight: bold">Fecha Solicitud:</span>{{solicitud.fechasolicitud}} 
                            </div>
                            <div class="col-xs-12">
                                <span style="font-weight: bold">Dirección suministro: </span>{{solicitud.direccionsuministro}}
                            </div>
                            <div class="col-xs-12">
                                <span style="font-weight: bold">Teléfono Suministro: </span>{{solicitud.telefonosuministro}}
                            </div>
                            <div class="col-xs-12">
                                <span style="font-weight: bold">La solicitud esta procesada </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

</div>






<!--================Modal Nueva Solicitud Cliente=================================================================--> 

      <!-- <div class="modal fade" tabindex="-1" role="dialog" id="nueva-solicitud-cliente">
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
 -->