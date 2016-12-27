
    <div ng-controller="bodegasController">
    
    <div class="container">


        <div class="col-xs-12" style="margin-top: 2%; margin-bottom: 2%">

            <div class="col-sm-4 col-xs-6">
                <div class="form-group has-feedback">
                    <input type="text" class="form-control" id="search" placeholder="BUSCAR..."
                           ng-model="search" ng-change="searchByFilter()">
                    <span class="glyphicon glyphicon-search form-control-feedback" aria-hidden="true"></span>
                </div>
            </div>
            <div class="col-sm-2 col-xs-3">
                <div class="form-group has-feedback">
                    <select class="form-control" name="provincia" id="provincia" ng-model="provinciaFiltro"
                        ng-change="loadCiudad(provinciaFiltro,true)">
                        <option value="">Provincia</option>
						<option ng-repeat="item in provinciasFiltro"						       
						        value="{{item.idprovincia}}">{{item.nombreprovincia}}     
						</option>                        
                        </select>                    
                </div>
            </div>
            
            <div class="col-sm-2 col-xs-3">
                <div class="form-group has-feedback">
                    <select class="form-control" name="ciudad" id="ciudad" ng-model="ciudadFiltro"
                        ng-change="loadSector(ciudadFiltro,true)">
                        <option value="">Ciudad</option>
						<option ng-repeat="item in ciudadesFiltro"						       
						        value="{{item.idciudad}}">{{item.nombreciudad}}     
						</option>                        
                        </select>                    
                </div>
            </div>
            <div class="col-sm-2 col-xs-3">
                <div class="form-group has-feedback">
                    <select class="form-control" name="sector" id="sector" ng-model="sectorFiltro"
                        ng-change="searchByFilter()">
                        <option value="">Sector</option>
						<option ng-repeat="item in sectoresFiltro"						       
						        value="{{item.idsector}}">{{item.nombreparroquia}}     
						</option>                        
                        </select>                    
                </div>
            </div>

            <div class="col-sm-2 col-xs-3">
                <button type="button" class="btn btn-primary" style="float: right;" ng-click="toggle('add', 0)">
                   <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                </button>
            </div>

            <div class="col-xs-12">
                <table class="table table-responsive table-striped table-hover table-condensed">
                    <thead class="bg-primary">
                    <tr>
                        <th style="text-align: center;">Código</th>
                        
                        <th style="text-align: center;">Ubicación</th>     
                        <th style="text-align: center;">Bodeguero</th>   
                        <th style="text-align: center;">Teléfonos</th> 
                        <th style="text-align: center;">Dirección</th>
                        <th style="text-align: center;">Correo</th> 
                        <th style="text-align: center;">Estado</th>                  
                        <th style="width: 20%;">Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr ng-repeat="bodega in bodegas" >
                        <td style="text-align: center;">{{bodega.idbodega}}</td>
                        
                        <td>{{bodega.ubicacion}}</td>
                        <td><a href="" ng-click="toggle('info', bodega.idbodega)">{{bodega.bodeguero}}</a></td>
                        <td>{{bodega.telefonobodega}}/{{bodega.telefonosecundariobodega}}</td>
                        <td>{{bodega.direccionbodega}}</td>
                        <td>{{bodega.correo}}</td>
                        <td>{{(bodega.estado)?'Activo':'No Activo'}}</td>
                        <td>
                            <button type="button" class="btn btn-warning" ng-click="toggle('edit', bodega.idbodega)"
                                    data-toggle="tooltip" data-placement="bottom" title="Editar" >
                                <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                            </button>
                            <button type="button" class="btn btn-danger" ng-click="showModalConfirm(bodega.idbodega,0)"
                                    data-toggle="tooltip" data-placement="bottom" title="Anular"  ng-show="bodega.estado==1">
                                <span class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span>
                            </button>
                            <button type="button" class="btn btn-info" ng-click="showModalConfirm(bodega.idbodega,1)"
                                    data-toggle="tooltip" data-placement="bottom" title="Activar"  ng-show="bodega.estado==0">
                                <span class="glyphicon glyphicon-ok-circle" aria-hidden="true"></span>
                            </button>
                            
                        </td>
                    </tr>
                    </tbody>
                </table>

            </div>

        </div>

        <div class="modal fade" tabindex="-1" role="dialog" id="modalAction">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header modal-header-primary">
                       
                            <h4 class="modal-title">{{form_title}}{{bodega.idbodega}} <span class='pull-right'> </span></h4>
                      
                    </div>
                    <div class="modal-body">                        
                            <form class="form-horizontal" name="formBodega" novalidate="" >
								<div class="form-group col-xs-12">
                                            <label class="control-label">Datos Bodega</label>
                                 </div>
								
                                      <div class="form-group col-xs-12">
                                            <label class="col-sm-2 control-label">Bodeguero:</label>
                                            <div class="col-sm-8">
                                            <div>
                                            <angucomplete-alt 
                                            	  id="nombre"									            
									              pause="400"
									              selected-object="testObj"									              
									              remote-url="{{API_URL}}bodega/getEmpleado/"
									              title-field="documentoidentidadempleado,nombres,apellidos"
									              description-field="twitter"									              
									              minlength="1"
									              input-class="form-control form-control-small"
									              match-class="highlight"
									              field-required="true"
									              input-name="nombre"
									              initial-value="empleado"
									              text-searching="Buscando Empleado"
									              text-no-results="Empleado no encontrado"
									               />
                                            </div>
                                            <span class="help-block error"
                                                      ng-show="formBodega.nombre.$invalid && formBodega.nombre.$touched">El Nombre es requerido.</span>                                                                                   
                                            </div>
                                            
                                        </div>
                                        
                                        <div class="form-group col-xs-12">
                                    <div class="col-md-6 col-xs-12">
                                        
                                            <label class="col-sm-4 control-label">Teléfono Principal:</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" name="telefonoprincipal" id="telefonoprincipal"
                                                       ng-required="true" ng-model="bodega.telefonobodega" ng-maxlength="16" ng-pattern="/[0-9\-\ ]+$/" >
                                                 <span class="help-block error"
                                                      ng-show="formBodega.telefonoprincipal.$invalid && formBodega.telefonoprincipal.$touched">El Teléfono Principal es requerido</span>
                                                <span class="help-block error"
                                                      ng-show="formBodega.telefonoprincipal.$invalid && formBodega.telefonoprincipal.$error.maxlength">La longitud máxima es de 16 números.</span>
                                                <span class="help-block error"
                                                      ng-show="formBodega.telefonoprincipal.$invalid && formBodega.telefonoprincipal.$error.pattern">El Teléfono debe ser solo números, guion y espacios.</span>
                                            </div>
                                       
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        
                                            <label class="col-sm-4 control-label">Teléfono Secundario:</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" name="telefonosecundario" id="telefonosecundario"
                                                       ng-model="bodega.telefonosecundariobodega" ng-maxlength="16" ng-pattern="/[0-9\-\ ]+$/" >
                                                <span class="help-block error"
                                                      ng-show="formBodega.telefonosecundario.$invalid && formBodega.telefonosecundario.$error.maxlength">La longitud máxima es de 16 números</span>
                                                <span class="help-block error"
                                                      ng-show="formBodega.telefonosecundario.$invalid && formBodega.telefonosecundario.$error.pattern">El Teléfono debe ser solo números, guion y espacios</span>
                                            </div>
                                       
                                    </div>
                                </div>
                                        
                                      <div class="form-group">
                                    <div class="col-md-6 col-xs-12">
                                        
                                            <label class="col-sm-4 control-label">Teléfono Opcional:</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" name="opcional" id="opcional"
                                                       ng-model="bodega.telefonoopcionalbodega" ng-maxlength="16" ng-pattern="/[0-9\-\ ]+$/" >
                                                <span class="help-block error"
                                                      ng-show="formBodega.opcional.$invalid && formBodega.opcional.$error.maxlength">La longitud máxima es de 16 números</span>
                                                <span class="help-block error"
                                                      ng-show="formBodega.opcional.$invalid && formBodega.opcional.$error.pattern">El Teléfono debe ser solo números, guion y espacios</span>
                                            </div>
                                       
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                    </div>
                                    </div>  
                                        
                                     <div class="form-group col-xs-12">
                                            <label class="control-label">Ubicación Bodega</label>
                                 	</div>                             
                                        <div class="form-group col-xs-12">
                                         <div class="col-md-4 col-xs-6">
                                            <label class="col-sm-4 control-label">Provincia:</label>
                                            <div class="col-sm-8">
                                                <select class="form-control" name="provincia" id="provincia" ng-model="provincia" 
                                                ng-required="true" ng-change="loadCiudad(provincia,false)"
                                                ng-options="item.idprovincia as item.nombreprovincia for item in provincias">
                               						<option value="">Provincia</option>													
												</select>
                                                <span class="help-block error"
                                                      ng-show="formBodega.provincia.$invalid && formBodega.provincia.$touched">La provincia es requerida.</span>
                                            </div>
                                            </div>
                                            
                                            <div class="col-md-4 col-xs-6">
                                            <label class="col-sm-4 control-label">Ciudad:</label>
                                            <div class="col-sm-8">
                                                <select class="form-control" name="ciudad" id="ciudad" ng-model="ciudad"  
                                                ng-required="true" ng-change="loadSector(ciudad,false)"
                                                ng-options="item.idciudad as item.nombreciudad for item in ciudades">
                                                    <option value="">Ciudad</option>													
												</select>
                                                <span class="help-block error"
                                                      ng-show="formBodega.ciudad.$invalid && formBodega.ciudad.$touched">La Ciudad es requerida.</span>
                                            </div>
                                            </div>
                                            <div class="col-md-4 col-xs-6">
                                            <label class="col-sm-4 control-label">Sector:</label>
                                            <div class="col-sm-8">
                                                <select class="form-control" name="idsector" id="idsector" ng-model="bodega.idsector" 
                                                ng-required="true" ng-options="item.idsector as item.nombreparroquia for item in sectores">
                                                    <option value="">Sector</option>													  
                                                 </select>
                                                <span class="help-block error"
                                                      ng-show="formBodega.idsector.$invalid && formBodega.idsector.$touched">El sector es requerido.</span>
                                            </div>
                                            </div>
                                            
                                        </div>
                                       
                                       
                                       <div class="form-group col-xs-12">                                        
                                            <label class="col-sm-2 control-label">Dirección:</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" name="direccion" id="direccion"
                                                      ng-required="true" ng-model="bodega.direccionbodega" ng-maxlength="32" ng-pattern="/[a-zA-ZáéíóúñÑ0-9. ]+/">
                                                <span class="help-block error"
                                                      ng-show="formBodega.direccion.$invalid && formBodega.direccion.$touched">La dirección es requerida.</span>
                                                <span class="help-block error"
                                                      ng-show="formBodega.direccion.$invalid && formBodega.direccion.$error.maxlength">La longitud máxima es de 32 caracteres.</span>
                                                <span class="help-block error"
                                                      ng-show="formBodega.direccion.$invalid && formBodega.direccion.$error.pattern">La Dirección debe ser solo letras, puntos, números, guion y espacios.</span>
                                            </div>                                       
                                    </div>
                                        
                                      <div class="form-group col-xs-12">                                        
                                            <label class="col-sm-2 control-label">Observacion:</label>
                                            <div class="col-sm-8">
                                            <textarea name="observacion" id="observacion" rows="2" cols="80" ng-model="bodega.observacion" ng-maxlength="1024" ng-pattern="/[a-zA-ZáéíóúñÑ0-9. ]+/"></textarea>
                                                
                                                <span class="help-block error"
                                                      ng-show="formBodega.observacion.$invalid && formBodega.observacion.$error.maxlength">La longitud máxima es de 1024 caracteres.</span>
                                                <span class="help-block error"
                                                      ng-show="formBodega.observacion.$invalid && formBodega.observacion.$error.pattern">La Observación debe ser solo letras, puntos, números, guion y espacios.</span>
                                            </div>                                       
                                    </div> 
                                            
                                                                     
                           
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            Cancelar <span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span>
                        </button>
                        <button type="button" class="btn btn-success" id="btn-save" ng-click="save(modalstate, id)" ng-disabled="formBodega.$invalid">
                            Guardar <span class="glyphicon glyphicon-floppy-saved" aria-hidden="true"></span>
                        </button>
                    </div>
                    </form>
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

        <div class="modal fade" tabindex="-1" role="dialog" id="modalConfirmDelete">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header modal-header-danger">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Confirmación</h4>
                    </div>
                    <div class="modal-body">
                        <span>Está seguro que desea {{ mensaje }} la bodega de : <span style="font-weight: bold;">{{empleado.nombres}} {{empleado.apellidos}}</span></span>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" id="btn-save" ng-click="anularBodega()">{{ mensaje }}</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" tabindex="-1" role="dialog" id="modalInfoEmpleado">
            <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content">
                    <div class="modal-header modal-header-info">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Información Empleado No {{empleado.idempleado}} </h4>
                    </div>
                    <div class="modal-body">
                        <div class="col-xs-12 text-center">
                       			<img ng-src="{{ rutafoto }}" onerror="defaultImage(this)" class="img-thumbnail" style="width:150px" >                            
                        </div>
                        <div class="row text-center">
                            <div class="col-xs-12 text-center" style="font-size: 18px;">{{empleado.nombres}} {{empleado.apellidos}}</div>
                            <div class="col-xs-12">
                                <span style="font-weight: bold">Cargo: </span>{{empleado.nombrecargo}}
                            </div>
                            <div class="col-xs-12">
                                <span style="font-weight: bold">Empleado desde: </span>{{formatoFecha(empleado.fechaingreso)}}
                            </div>
                            <div class="col-xs-12">
                                <span style="font-weight: bold">Teléfonos: </span>{{empleado.telefonoprincipaldomicilio}} / {{empleado.telefonosecundariodomicilio}}
                            </div>
                            
                            <div class="col-xs-12">
                                <span style="font-weight: bold">Celular: </span>{{empleado.celular}}
                            </div>
                            <div class="col-xs-12">
                                <span style="font-weight: bold">Dirección: </span>{{empleado.direcciondomicilio}}
                            </div>
                            <div class="col-xs-12">
                                <span style="font-weight: bold">Email: </span>{{empleado.correo}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    </div>
    
