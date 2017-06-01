
<div ng-controller="depreciacionActivosFijosController" ng-init="GetAllActivosFijos()">

  		<div class="col-xs-12">
	  		<h4>Depreciación de Activos Fijos</h4>	
		<div>
	             
	    	<hr>
	
	<div class="col-xs-12 text-right" style="margin-top: 0px;">
		<button type="button"  class="btn btn-primary" ng-click="EjecuatarDepreciacion()" >
           <span id="palabraejecutar"> Ejecutar Depreciación </span> <span id="iconok" class="glyphicon glyphicon glyphicon-ok" aria-hidden="true" ></span> 
        </button>
        <button type="button" id="buttom" class="btn btn-info" ng-click="GetAllActivosFijos()">
           <span  id="palabra" > Actualizar </span> <span  id="icon" class="glyphicon glyphicon glyphicon-refresh" aria-hidden="true"></span> 
        </button>
	</div>

	<div class="col-xs-12" style="margin-top: 5px;">
		<table class="table table-responsive table-striped table-hover table-condensed">
			<thead class="bg-primary">
				<tr>
					<td>Imagen</td>
					<td>Codigo</td>
					<td >Nro Compra</td>
					<td>Valor Unitario</td>
					<td>Fecha</td>
					<td>Responsable</td>
					<td>Ubicacion</td>
					<td>Precio Venta</td>
					<td>Estado</td>
					<td>Accion</td>
				</tr>
			</thead>
			<tbody dir-paginate="activos in AllActivosFijos | itemsPerPage:6" ng-cloak>
				<tr>
					<th><img style="width: 100px; height: 80px;"  src="../{{activos.foto}}"></th>
					<td>{{activos.codigoproducto}}</td>
					<td>{{activos.numdocumentocompra}}</td>
					<td>{{activos.preciounitario}}</td>
					<td>{{activos.fecharegistrocompra}}</td>
					<td>{{activos.namepersona}}</td>
					<td>{{activos.namebodega}}</td>
					<td>{{activos.preciounitario}}</td>
					<td>{{activos.estado | estado}}</td>
					<td>
						<button type="button" class="btn btn-primary" ng-click="ShowModalGestionActivo(activos.idcatalogitem,activos.iditemcompra)">
			                <span class="glyphicon glyphicon glyphicon-cog" aria-hidden="true" ></span> 
			            </button>
					</td>
				</tr>
			</tbody>
		</table>
		  <dir-pagination-controls
                max-size="6"
                direction-links="true"
                boundary-links="true" >
            </dir-pagination-controls> 
	</div>



 <div class="modal fade" tabindex="-1" role="dialog" id="ModalGestionActivo">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header modal-header-primary">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Gestión de Activo Fijo</h4>
                </div>
                <div class="modal-body">


                <div class="col-xs-12">


		<div id="dvTab" style="margin-top: 5px;">
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" id="li_alta" class="active tabs"><a href="#alta" aria-controls="alta" role="tab" data-toggle="tab">Alta Activo</a></li>

                <div id="mensaje" class="ocultar"> </div>

                <li role="presentation" id="li_incidencia" class="tabs"><a href="#incidencia" aria-controls="incidencia" role="tab" data-toggle="tab" id="incidencia1" > Incidencias</a></li>

                <li role="presentation" id="li_mantencion" class="tabs"><a href="#mantencion" aria-controls="mantencion" role="tab" data-toggle="tab" id="mantencion1"> Mantencion</a></li>

                <li role="presentation" id="li_traslado" class="tabs"><a href="#traslado" aria-controls="traslado" role="tab" data-toggle="tab" id="traslado1"> Traslados</a></li>
 
                <li role="presentation" id="li_baja" class="tabs"><a href="#baja" aria-controls="baja" role="tab" data-toggle="tab" id="baja1"> Baja Activo</a></li>
            </ul>

            <div class="tab-content">


            <!--INICIO FORMULARIO ALTA ACTIVO FIJO-->

            <div role="tabpanel" class="tab-pane fade active in" id="alta" style="padding-top: 10px;">

                <form name="formularioAltaActivoFijo" id="formularioAltaActivoFijo">

					<div class="col-sm-5 col-xs-12">
						<div class="input-group">                        
			                <span class="input-group-addon">Código Item: </span>
			                <input type="text" class="form-control" ng-model="codigo" disabled />
			            </div>
			            	<input type="hidden" class="form-control" ng-model="iditemactivofijo" readonly />
					</div>

					<div class="col-sm-7 col-xs-12">
						<div class="input-group">                        
			                <span class="input-group-addon">Detalle Item: </span>
			                <input type="text" class="form-control" ng-model="detalle" disabled />
			            </div>
			            	<input type="hidden" class="form-control" ng-model="iditemcompra" readonly />
					</div>

					<div class="col-sm-6 col-xs-12" style="margin-top: 5px;">
						<div class="input-group">                        
			                <span class="input-group-addon">Nro Activo: </span>
			                <input type="text" id="numactivo" class="form-control" ng-model="numactivo" ng-keyup="NumActivo()" name="numactivo" ng-pattern="/^[0-9]+$/" ng-required="true" />
			            </div> 
			            	<span class="alert-danger"
                           		ng-show="formularioAltaActivoFijo.numactivo.$invalid && formularioAltaActivoFijo.numactivo.$touched">El campo Nro Activo es requerido<br />
                        	</span>
                        	<span class="alert-danger"
                           		ng-show="alerta">Ya existe este número de activo por favor ingrese otro
                        	</span>
					</div>

					<div class="col-sm-6 col-xs-12" style="margin-top: 5px;">
						<div class="input-group">                        
			                <span class="input-group-addon">Fecha Adquisicion: </span>
			                <input type="text" class="form-control" ng-model="fechaadquisicion" readonly />
			            </div>
					</div>

					<div class="col-sm-4 col-xs-12" style="margin-top: 5px;">
						<div class="input-group">                        
			                <span class="input-group-addon">Precio Venta: </span>
			                <input type="text" class="form-control" ng-model="precioventa" name="precioventa" ng-pattern="/^(\d{1}\.)?(\d+\.?)+(,\d{2})?$/" ng-disabled="InputPrecioVenta"/>
			            </div>
			           		<span class="alert-danger"
                           		ng-show="formularioAltaActivoFijo.precioventa.$invalid && formularioAltaActivoFijo.precioventa.$touched">Sólo se aceptan números con ó sin decimales
                        	</span>
					</div>

					<div class="col-sm-4 col-xs-12" style="margin-top: 5px;">
						<div class="input-group">                        
			                <span class="input-group-addon">Vida Util (Años): </span>
			                <input type="text" class="form-control" ng-model="vidautil" id="vidautil" name="vidautil" ng-pattern="/^[0-9]+$/" ng-required="true" />
			            </div>
			            <input type="hidden" class="form-control" name="fecha" id="fecha" ng-model="DiaActual" readonly />
			            	<span class="alert-danger"
                           		ng-show="formularioAltaActivoFijo.vidautil.$invalid && formularioAltaActivoFijo.vidautil.$error.pattern">Sólo se aceptan números
                        	</span><br />
                        	<span class="alert-danger"
                           		ng-show="formularioAltaActivoFijo.vidautil.$invalid && formularioAltaActivoFijo.vidautil.$touched">El campo Vida útil es requerido
                        	</span>
					</div>

					<div class="col-sm-4 col-xs-12" style="margin-top: 5px;">
						<div class="input-group">                        
			                <span class="input-group-addon">Estado: </span>
			                <select class="form-control" ng-model="estado" name="estado" ng-required="true" ng-disabled="InputEstado">
			                	<option value="">--Seleccione--</option>
			                	<option value="1" id="activo">Activo</option>
			                	<option value="0" id="inactivo">Inactivo</option>
			                </select>
			            </div>
			            	<span class="alert-danger"
                           		ng-show="formularioAltaActivoFijo.estado.$invalid && formularioAltaActivoFijo.estado.$touched">El campo Estado es requerido
                        	</span>
					</div>

					<div class="col-xs-12" style="margin-top: 5px;">
						<div class="input-group">                        
			                <span class="input-group-addon">Responsable: </span>

			                <angucomplete-alt 
                                            	  id="Responsable"
									              pause="200"
									              selected-object="responsable"						
									              input-changed="idempleado"
												  focus-out="focusOut()"
									              remote-url="{{API_URL}}/Activosfijos/AllResponsable/"
									              title-field="namepersona"
									              description-field="twitter"   
									              minlength="1"								              
									              input-class="form-control form-control-small Responsable"
									              match-class="highlight"
									              field-required="true"
									              input-name="Responsable"
									              disable-input="guardado"
									              text-searching="Buscando Responsable"
									              text-no-results="Responsable no encontrado"
									              initial-value="Responsable"
									              ng-disabled="InputResponsableAlta"
									              ng-model="modelempleado"
									             
							/>




							
			            </div>
			            <span class="alert-danger"
                           		ng-show="formularioAltaActivoFijo.Responsable.$invalid && formularioAltaActivoFijo.Responsable.$touched">El campo Responsable es requerido
                        	</span>
			            	<input type="hidden" class="form-control" name="idempleado" id="idempleado" ng-model="idresponsable" readonly />
					</div>

					<div class="col-xs-12" style="margin-top: 5px;">
						<div class="input-group">                        
			                <span class="input-group-addon">Ubicación: </span>
			                <input type="text" class="form-control" ng-model="ubicacion" name="ubicacion" ng-pattern="/^[a-zA-Z0-9]/" ng-disabled="InputUbicacion"/>
			            </div>
			           		 <span class="alert-danger"
                           		ng-show="formularioAltaActivoFijo.ubicacion.$invalid && formularioAltaActivoFijo.ubicacion.$touched">Sólo se aceptan números y letras
                        	</span>
					</div>			

					<div class="col-xs-12" style="margin-top: 5px;">
						<div class="input-group">                        
			                <span class="input-group-addon">C. Contable Depreciación: </span>
			                <input type="text" class="form-control" ng-model="cuenta_contable_depreciacion" name="cuenta_contable_depreciacion" readonly ng-required="true" />
			                <span class="input-group-btn" role="group">
		                        <button type="button" class="btn btn-info" ng-click="Plancuentas(0)" id="cuenta_contable_depreciacion">
		                            <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
		                        </button>
		                    </span>
		                 </div>
		                 <input type="hidden" class="form-control" ng-model="id_cuenta_contable_depreciacion" readonly>
		                 <span class="alert-danger"
                           		ng-show="formularioAltaActivoFijo.cuenta_contable_depreciacion.$invalid && formularioAltaActivoFijo.cuenta_contable_depreciacion.$touched">El campo C. Contable Depreciación es requerido
                         </span>
					</div>

					<div class="col-xs-12" style="margin-top: 5px;">
						<div class="input-group">                        
			                <span class="input-group-addon">C. Contable Gastos: </span>
			                <input type="text" class="form-control" name="cuenta_contable_gastos" ng-model="cuenta_contable_gastos" ng-required="true"  readonly />
			                <span class="input-group-btn" role="group">
		                        <button type="button" class="btn btn-info" ng-click="Plancuentas(1)" id="cuenta_contable_gastos">
		                            <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
		                        </button>
		                    </span>
		                 </div>
		                  <input type="hidden" class="form-control" ng-model="id_cuenta_contable_gastos" name="id_cuenta_contable_gastos" readonly>
		                   	<span class="alert-danger"
                           		ng-show="formularioAltaActivoFijo.cuenta_contable_gastos.$invalid && formularioAltaActivoFijo.cuenta_contable_gastos.$touched">El campo C. Contable Gastos es requerido
                         	</span>
					</div> 

					<div class="col-xs-12" style="margin-top: 5px;">
						<textarea class="form-control" rows="3" placeholder="Observacion" ng-model="observacion" 
						ng-disabled="InputObservacionAlta"></textarea>
					</div>

					<div class="col-xs-12 text-center" style="margin-top: 5px;">
						
						<button type="button" class="btn btn-default" data-dismiss="modal">
	                        Cancelar <span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span>
	                    </button>
	                    <button type="button" class="btn btn-success" id="btn-save-formularioAltaActivoFijo" ng-click="GuardarAltaActivoFijo(iddetalleitemactivofijo)" ng-disabled="formularioAltaActivoFijo.$invalid">
	                        Guardar <span class="glyphicon glyphicon-floppy-saved" aria-hidden="true" ></span>
	                    </button>

					</div>	

				</form>		

            </div>

       
                
                <!--FIN FORMULARIO ALTA ACTIVO FIJO-->









                <!--INICIO FORMULARIO INCIDENCIA ACTIVO FIJO-->

            <div role="tabpanel" class="tab-pane fade" id="incidencia" style="padding-top: 10px;">

            	<form name="formularioIncidenciaActivoFijo" id="formularioIncidenciaActivoFijo">

                	<div class="col-xs-12 text-right"">
                		<button type="button" class="btn btn-primary"  ng-click="MostrarIncidencias()" ng-disabled="VerIncidencias">
			               Ver incidencias <span class="glyphicon glyphicon-th-list" aria-hidden="true" ></span> 
			            </button>
			            <button type="button" class="btn btn-primary" ng-click=CrearFila('incidencia') ng-disabled="BotonAgregarIncidencia">
			                Agregar incidencias <span class="glyphicon glyphicon glyphicon-plus" aria-hidden="true" ></span> 
			            </button>
                	</div>
               
					<div class="col-xs-12" style="margin-top: 5px;">

						<!-- cuerpo de los datos-->
						<table class="table table-responsive table-striped table-hover table-condensed" ng-show="DatosIncidencias">
							<thead class="bg-primary">
								<tr>
									<td>Fecha</td>
									<td>Descripcion</td>

								</tr>
							</thead>

						

							<tbody dir-paginate="Datos in DatosIncidencias |itemsPerPage:6" pagination-id="incidencia">
								<tr>
									<td>{{Datos.fecha}}</td>
									<td>{{Datos.descripcion | uppercase }}</td>
								</tr>
							</tbody>

						</table>	



							<!-- cuerpo de los campos-->

						<table class="table table-responsive table-striped table-hover table-condensed" ng-show="CamposIncidencias">
							<thead class="bg-primary">
								<tr>
									<td>Fecha</td>
									<td>Descripcion</td>
									<td>Acción</td>
								</tr>
							</thead>

							<tbody dir-paginate="itemm in CampoIncidencia|itemsPerPage:6" pagination-id="incidencia" ng-show="CamposIncidencias">
								<tr id="campos">
									<td> 
										<div class='input-group date' >
		                    				<input type='date' class="form-control fecha" name="fecha{{$index}}" ng-model="itemm.fecha" id="fecha" string-to-fecha{{$index}} required="true" ng-disabled="CampoFechaIncidencias"/>
		                    					<span class="input-group-addon">
		                      				  		<span class="glyphicon glyphicon-calendar"></span>
		                    					</span>
	                					</div>
	                					   <span class="help-block error" ng-show="formularioIncidenciaActivoFijo.fecha{{$index}}.$invalid && formularioIncidenciaActivoFijo.fecha{{$index}}.$touched">el campo es requerido
	                					   </span>

                					</td>
									<td>
									<input type="text" class="form-control descripcion" name="descripcion{{$index}}" ng-model="itemm.descripcion" id="descripcion"   string-to-descripcion{{$index}} required="true"  ng-disabled="CampoDescripcionIncidencias"/>
										<span class="help-block error" ng-show="formularioIncidenciaActivoFijo.descripcion{{$index}}.$invalid && formularioIncidenciaActivoFijo.descripcion{{$index}}.$touched">el campo es requerido
		                				</span>
									<input type="hidden" class="form-control " name="iddetalleitemactivofijo{{$index}}" ng-model="iddetalleitemactivofijo" id="iddetalleitemactivofijo" string-to-iddetalleitemactivofijo{{$index}} readonly/></td>
									<td>
										<button type="button" class="btn btn-danger" id="{{$index}}" ng-click="ElimimarFilaInicidencia($index)">
							                <span class="glyphicon glyphicon glyphicon-trash" aria-hidden="true" ></span> 
							            </button>
									</td>
								</tr>
							</tbody>
							
						</table>

						<span ng-show="MensajeSinRegistrosIncidencias" style="color:#c09853;">El alta de la compra no posee registros que monstrar de este tipo </span>

					</div>
						<dir-pagination-controls
						        max-size="6"
						        direction-links="true"
						        boundary-links="true"
						        pagination-id="incidencia">
						</dir-pagination-controls>

					<div class="col-xs-12 text-center" style="margin-top: 5px;">
						
						<button type="button" class="btn btn-default" data-dismiss="modal">
	                        Cancelar <span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span>
	                    </button>
		                    <button type="button" class="btn btn-success" id="btn-save-formularioIncidenciaActivoFijo" ng-disabled="formularioIncidenciaActivoFijo.$invalid" ng-show="BotonGuardarIncidencias">
		                        Guardar <span class="glyphicon glyphicon-floppy-saved" aria-hidden="true" ></span>
		                    </button>

					</div>

				</form>

            </div>

                <!--FIN FORMULARIO INCIDENCIA ACTIVO FIJO-->









                <!--INICIO FORMULARIO MANTENCIÓN ACTIVO FIJO-->


            <div role="tabpanel" class="tab-pane fade" id="mantencion" style="padding-top: 10px;">

            	<form name="formularioMantencionActivoFijo" id="formularioMantencionActivoFijo">
					
					<div class="col-xs-12 text-right">
					<button type="button" class="btn btn-primary"  ng-click="MostrarMantencion()" ng-disabled="VerMantenciones">
			               Ver mantenciones <span class="glyphicon glyphicon-th-list" aria-hidden="true" ></span> 
			            </button>
                		<button type="button" class="btn btn-primary" ng-click="CrearFila('mantencion')" ng-disabled="BotonAgregarMantencion">
			                Agregar mantenciones <span class="glyphicon glyphicon glyphicon-plus" aria-hidden="true"></span> 
			            </button>
                	</div>
					
					<div class="col-xs-12" style="margin-top: 5px;">

					<!--datos mantencion-->

					<table class="table table-responsive table-striped table-hover table-condensed" ng-show="DatosMantencion">
							<thead class="bg-primary">
								<tr>
									<td>Fecha</td>
									<td>Tipo</td>
									<td>Observacion</td>
								</tr>
							</thead>

							<tbody dir-paginate="itemm in DatosMantenciones|itemsPerPage:6" pagination-id="mantencion">
								<tr>
									<td>{{itemm.fecha}}</td>
									<td>{{itemm.tipo}}</td>	
									<td>{{itemm.observacion}}</td>
								</tr>
							</tbody>
						</table>





					<!--campos mantencion-->
						<table class="table table-responsive table-striped table-hover table-condensed" ng-show="CamposMantenciones">
							<thead class="bg-primary">
								<tr>
									<td>Fecha</td>
									<td>Tipo</td>
									<td>Observacion</td>
									<td>Accion</td>
								</tr>
							</thead>
							<tbody dir-paginate="itemm in CampoMantencion|itemsPerPage:6" pagination-id="mantencion">
								<tr>
									<td><div class='input-group date'>
		                    				<input type="date" class="form-control" name="fechaMantencion{{$index}}" required="true" ng-model="itemm.fechaMantencion" ng-disabled="CampofechaMantencion"/>
		                    					<span class="input-group-addon">
		                      				  		<span class="glyphicon glyphicon-calendar"></span>
		                    					</span>
	                					</div>
									<input type="hidden" class="form-control" ng-model="iddetalleitemactivofijo" name="iddetalleitemactivofijo{{$index}}" readonly />
										<span class="help-block error" ng-show="formularioMantencionActivoFijo.fechaMantencion{{$index}}.$invalid && 	formularioMantencionActivoFijo.fechaMantencion{{$index}}.$touched">el campo es requerido
			                			</span>
									</td>
									<td>
										<select class="form-control" ng-model="itemm.TipoMantencion" name="TipoMantencion{{$index}}" required="true" ng-disabled="CampoTipoMantencion">
										<option value="" id="seleccione">--Seleccione--</option>
						                	<option ng-repeat="mantencion in tiposmantencion" ng-model="IdTipoMantencion" class="IdTipoMantencion" value={{mantencion.idtipomantencionaf}}>{{mantencion.tipo}}</option>
						                </select>
									</td>
										<span class="help-block error" ng-show="formularioMantencionActivoFijo.TipoMantencion{{$index}}.$invalid && 	formularioMantencionActivoFijo.TipoMantencion{{$index}}.$touched"">el campo es requerido
			                			</span>
									<td><input type="text" class="form-control ObservacionMantencion" ng-model="itemm.ObservacionMantencion" name="ObservacionMantencion{{$index}}" ng-disabled="CampoObservacionMantencion"/></td>
									<td>
										<button type="button" class="btn btn-danger" id="{{$index}}" ng-click="ElimimarFilaMantencion($index)">
							                <span class="glyphicon glyphicon glyphicon-trash" aria-hidden="true"></span> 
							            </button>
									</td>
								</tr>
							</tbody>
						</table>

						<span ng-show="MensajeSinRegistrosMantenciones" style="color:#c09853;">El alta de la compra no posee registros que monstrar de este tipo </span>
						</div>

						<dir-pagination-controls
							    on-page-change="pageChanged(newPageNumber)" 
						        max-size="6"
						        direction-links="true"
						        boundary-links="true"
						        pagination-id="mantencion">
						</dir-pagination-controls>

					<div class="col-xs-12 text-center" style="margin-top: 5px;">
						
						<button type="button" class="btn btn-default" data-dismiss="modal">
	                        Cancelar <span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span>
	                    </button>
	                    <button type="button" class="btn btn-success" id="btn-save-formularioMantencionActivoFijo" ng-disabled="formularioMantencionActivoFijo.$invalid"  ng-show="BotonGuardarMantencion">
	                        Guardar <span class="glyphicon glyphicon-floppy-saved" aria-hidden="true"></span>
	                    </button>

					</div>

				</form>

            </div>

                 <!--FIN FORMULARIO MANTENCIÓN ACTIVO FIJO-->












                <!--INICIO FORMULARIO TRASLADO ACTIVO FIJO-->

            <div role="tabpanel" class="tab-pane fade" id="traslado" style="padding-top: 10px;">
				
				<form name="formularioTrasladoActivoFijo" id="formularioTrasladoActivoFijo">

					<div class="col-xs-12 text-right">
					<button type="button" class="btn btn-primary"  ng-click="MostrarTraslados()" ng-disabled="VerTraslados" >
			               Ver traslados <span class="glyphicon glyphicon-th-list" aria-hidden="true" ></span> 
			            </button>
                		<button type="button" class="btn btn-primary" ng-click="CrearFila('traslado')">
			                Agregar traslados <span class="glyphicon glyphicon glyphicon-plus" aria-hidden="true"></span> 
			            </button>
                	</div>
					
					<div class="col-xs-12" style="margin-top: 5px;">


						<!-- Datos traslados-->

						<table class="table table-responsive table-striped table-hover table-condensed" ng-show="DatoTraslado">
							<thead class="bg-primary">
								<tr>
									<td>Fecha</td>
									<td>Responsable Origen</td>
									<td>Responsable Destino</td>
								</tr>
							</thead>
							<tbody dir-paginate="itemm in DatosTraslado|itemsPerPage:6" pagination-id="traslado">
								<tr>
									<td>{{itemm.fecha}}</td>
									<td>{{itemm.namepersonaorigen}}</td>
									<td>{{itemm.namepersonadestino}}</td>
								</tr>
							</tbody>
						</table>



						<!-- campos traslados-->
						<table class="table table-responsive table-striped table-hover table-condensed" ng-show="CampoTraslado">
							<thead class="bg-primary">
								<tr>
									<td>Fecha</td>
									<td>Responsable Origen</td>
									<td>Responsable Destino</td>
									<td>Accion</td>
								</tr>
							</thead>
							<tbody dir-paginate="itemm in CamposTraslado|itemsPerPage:6" pagination-id="traslado">
								<tr>
									<td><div class='input-group date'>
		                    				<input type="date" class="form-control" name="fechaTraslado{{$index}}" required="true" ng-model="itemm.fechaTraslado" ng-disabled="CampoFechaTraslado" />
		                    					<span class="input-group-addon">
		                      				  		<span class="glyphicon glyphicon-calendar"></span>
		                    					</span>
	                					</div>
	                					<span class="help-block error" ng-show="formularioTrasladoActivoFijo.fechaTraslado{{$index}}.$invalid && formularioTrasladoActivoFijo.fechaTraslado{{$index}}.$touched">el campo es requerido
		                				</span>

	                						<input type="hidden" class="form-control" name="iddetalleitemactivofijo{{$index}}" ng-model="iddetalleitemactivofijo" id="iddetalleitemactivofijo" string-to-iddetalleitemactivofijo{{$index}} readonly/></td>

	                				</td>
									<td>
											<div>
									
										 <angucomplete-alt 
                                        	id="Origen{{$index}}"
									        pause="100"
									        selected-object="itemm.ResponsableOrigen"						
									        ng-click="idempleadoorigen($index)"
											focus-out="focusOut()"
									        remote-url="{{API_URL}}AllResponsable/"
									        title-field="namepersona"
									        description-field="twitter"   
									        minlength="1"								              
									        input-class="form-control form-control-small"
									        match-class="highlight"
									        field-required="true"
									        input-name="ResponsableOrigen{{$index}}"
									        disable-input="guardado"
									        text-searching="Buscando Responsable Origen"
									        text-no-results="Responsable Origen no encontrado"
									        initial-value="Responsable_Origen"
									        ng-model="itemm.ResponsableOrigen"
									       
									        />	

									       </div>
									
									     <span class="help-block error" ng-show="formularioTrasladoActivoFijo.ResponsableOrigen{{$index}}.$invalid && formularioTrasladoActivoFijo.ResponsableOrigen{{$index}}.$touched">el campo es requerido
		                				</span>

		                				

		                				<!--<input type="text" name="ResponsableOrigen2{{$index}}" ng-model="idresponsableorigen" 
		                				id="idresponsableorigen{{$index}}"  readonly>-->

									</td>
										
									<td> 

										
									<div>
											<angucomplete-alt 
	                                         	id="Destino{{$index}}"
										        pause="100"
										        selected-object="itemm.ResponsableDestino"						
										        ng-click="idempleadodestino($index)"
												focus-out="focusOut()"
										        remote-url="{{API_URL}}AllResponsable/"
										        title-field="namepersona"
										        description-field="twitter"   
										        minlength="1"								              
										        input-class="form-control form-control-small"
										        match-class="highlight"
										        field-required="true"
										        input-name="ResponsableDestino{{$index}}"
										        disable-input="guardado"
										        text-searching="Buscando Responsable Destino"
										        text-no-results="Responsable Destino no encontrado"
										        initial-value="Responsable_Destino"
										        ng-model="itemm.ResponsableDestino"
										       
										        />
										
										       </div>
										 <span class="help-block error" ng-show="formularioTrasladoActivoFijo.ResponsableDestino{{$index}}.$invalid && formularioTrasladoActivoFijo.ResponsableDestino{{$index}}.$touched">el campo es requerido
		                				</span>
		                			

		                				<!--<input type="text" name="ResponsableDestino2{{$index}}" ng-model="idresponsabledestino"
		                				 id="idresponsabledestino{{$index}}"  readonly>-->

									</td>

									
									<td>
										<button type="button" class="btn btn-danger" id="{{$index}}" ng-click="ElimimarFilaTraslado($index)">
							                <span class="glyphicon glyphicon glyphicon-trash" aria-hidden="true"></span> 
							            </button>
									</td>
								</tr>
							</tbody>
						</table>
						<span ng-show="MensajeSinRegistrosTraslados" style="color:#c09853;">El alta de la compra no posee registros que monstrar de este tipo </span>
						
					</div>

					<dir-pagination-controls
							    on-page-change="pageChanged(newPageNumber)" 
						        max-size="6"
						        direction-links="true"
						        boundary-links="true"
						        pagination-id="traslado">
						</dir-pagination-controls>




					<div class="col-xs-12 text-center" style="margin-top: 5px;">
						
						<button type="button" class="btn btn-default"  data-dismiss="modal">
	                        Cancelar <span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span>
	                    </button>
	                    <button type="button" class="btn btn-success" ng-show="BotonGuararTraslado" id="btn-save-formularioTrasladoActivoFijo" 
	                    ng-disabled="formularioTrasladoActivoFijo.$invalid">
	                        Guardar <span class="glyphicon glyphicon-floppy-saved" aria-hidden="true"></span>
	                    </button>

					</div>

				</form>

            </div>

                <!--FIN FORMULARIO TRASLADO ACTIVO FIJO-->








                <!--INICIO FORMULARIO BAJA ACTIVO FIJO-->

            <div role="tabpanel" class="tab-pane fade" id="baja" style="padding-top: 10px;">

            	<form name="formularioBajaActivoFijo" id="formularioBajaActivoFijo">
					
					<div class="col-sm-4 col-xs-12" style="margin-top: 5px;">

						<div class='input-group date'>
		                    <input type="date" class="form-control" name="fechaBaja" required="true" ng-model="fechaBaja" ng-disabled="CampoFechaBaja" required="true" />
		                    	<span class="input-group-addon">
		                      		<span class="glyphicon glyphicon-calendar"></span>
		                    	</span>
		                    	  <span class="help-block error" ng-show="formularioBajaActivoFijo.fechaBaja.$invalid && formularioTrasladoActivoFijo.fechaBaja.$touched">el campo es requerido</span>
	                	</div>

			            <input type="hidden" class="form-control" name="iddetalleitemactivofijo" ng-model="iddetalleitemactivofijo" readonly/></td>
					</div>

					<div class="col-sm-8 col-xs-12" style="margin-top: 5px;">
						<div class="input-group">                        
			                <span class="input-group-addon">Concepto: </span>
			                <select class="form-control" ng-model="ConceptoBaja"  ng-disabled="CampoConceptoBaja" name="CampoConceptoBaja" required="true">
			                	<option value="" id="seleccioneconcepto">{{seleccione}}{{ConceptoBaja2}}</option>
			                	<option ng-repeat="concepto in Conceptobaja"  value="{{concepto.idconceptobajaaf}}" >{{concepto.concepto}}</option>
			                </select>
			                  <span class="help-block error" ng-show="formularioBajaActivoFijo.CampoConceptoBaja.$invalid && formularioTrasladoActivoFijo.CampoConceptoBaja.$touched">el campo es requerido</span>
			            </div>
					</div>

					<div class="col-xs-12" style="margin-top: 5px;">
						<textarea class="form-control" rows="4" placeholder="Descripcion de la Baja" ng-model="DescripcionBaja" ng-disabled="CampoDescripcionBaja" name="DescripcionBaja" required="true"></textarea>
						 <span class="help-block error" ng-show="formularioBajaActivoFijo.DescripcionBaja.$invalid && formularioTrasladoActivoFijo.DescripcionBaja.$touched">el campo es requerido</span>
					</div>

					<div class="col-xs-12 text-center" style="margin-top: 5px;">
						
						<button type="button" class="btn btn-default" data-dismiss="modal">
	                        Cancelar <span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span>
	                    </button>
	                    <button type="button" class="btn btn-success" id="btn-save" ng-hide="BotonVerificarBajaActivoFijo" ng-click="VerificarBajaActivoFijo()" ng-disabled="formularioBajaActivoFijo.$invalid" >
	                        Guardar <span class="glyphicon glyphicon-floppy-saved" aria-hidden="true"></span>
	                    </button>

					</div>

				</form>	

            </div>

                <!--FIN FORMULARIO BAJA ACTIVO FIJO-->


            </div>
        </div>
	</div>
               </div>
            </div>
        </div>
    </div>


       <div class="modal fade" tabindex="-1" role="dialog" id="modalMensaje2">
	       	 <div class="modal-dialog" role="document">
	            <div class="modal-content">
		                <div class="modal-header modal-header-warning">
		                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		                    <h4 class="modal-title">Confirmación</h4>
		                </div>
		                <div class="modal-body">
		                    <div class="row"> 
		                       	<div class="col-md-12">
		                      	  	<span>{{mensaje2}}</span>
		      					</div>
		      				</div>
		      				<p style="float: right;">
	      				<button type="button" class="btn btn-default" data-dismiss="modal">
	                        Cancelar <span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span>
	                    </button>
	                    <button type="button" class="btn btn-warning" id="btn-save" ng-click="GuardarBajaActivoFijo(iddetalleitemactivofijo)">
	                        Dar baja <span class="glyphicon glyphicon-circle-arrow-down" aria-hidden="true"></span>
	                    </button>	

	      					</p>
	      				</div>

	      					
      			</div>


	      	</div>
	  	</div>

          <div class="modal fade" tabindex="-1" role="dialog" id="modalMensaje1">
	       	 <div class="modal-dialog" role="document">
	            <div class="modal-content">
		                <div class="modal-header modal-header-success">
		                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		                    <h4 class="modal-title">Acción Exitosa</h4>
		                </div>
		                <div class="modal-body">
		                    <div class="row"> 
		                       	<div class="col-md-12">
		                      	  	<span>{{mensaje1}}</span>
		      					</div>
		      				</div>
	      				</div>
      			</div>
	      	</div>
	  	</div>



    <div class="modal fade" tabindex="-1" role="dialog" id="modalCuentas">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header modal-header-primary">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Plan de Cuenta</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xs-12">
                            <table class="table table-responsive table-striped table-hover table-condensed table-bordered">
                                <thead class="bg-primary">
                                <tr>
                                    <th style="width: 15%;">ORDEN</th>
                                    <th>CONCEPTO</th>
                                    <th style="width: 10%;">COD. SRI</th>
                                    <th style="width: 4%;"></th>
                                </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="cuentas in PlanCuentas" ng-cloak>
                                        <td>{{cuentas.jerarquia}}</td>
                                        <td>{{cuentas.concepto}}</td>
                                        <td>{{cuentas.codigosri}}</td>
                                        <td>
                                            <input type="radio" name="select_cuenta" ng-click="click_radio(cuentas)">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
 


   


</div>

	



