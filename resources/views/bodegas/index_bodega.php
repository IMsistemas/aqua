

    <div ng-controller="bodegasController">

        <div class="col-xs-12">

            <h4>Gestión de Bodegas</h4>

            <hr>

        </div>

        <div class="col-xs-12" style="margin-top: 5px; margin-bottom: 2%">

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
						        value="{{item.idprovincia}}">{{item.nameprovincia}}     
						</option>                        
                        </select>                    
                </div>
            </div>
            
            <div class="col-sm-2 col-xs-3">
                <div class="form-group has-feedback">
                    <select class="form-control" name="ciudad" id="ciudad" ng-model="ciudadFiltro"
                        ng-change="loadSector(ciudadFiltro,true,0)">
                        <option value="">Ciudad</option>
						<option ng-repeat="item in ciudadesFiltro"						       
						        value="{{item.idcanton}}">{{item.namecanton}}     
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
						        value="{{item.idparroquia}}">{{item.nameparroquia}}     
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
                <table class="table table-responsive table-striped table-hover table-condensed table-bordered">
                    <thead class="bg-primary">
                    <tr>
                        <th style="text-align: center;">CODIGO</th>
                        
                        <th style="text-align: center;">UBICACION</th>
                        <th style="text-align: center;">BODEGUERO</th>
                        <th style="text-align: center;">TELEFONO(s)</th>
                        <th style="text-align: center;">DIRECCION</th>
                        <th style="text-align: center;">NOMBRE</th>
                        <th style="width: 10%;">ACCIONES</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr dir-paginate="bodega in bodegas | orderBy:sortKey:reverse | itemsPerPage:10"  >
                    
                        <td style="text-align: center;">{{bodega.idbodega}}</td>
                        
                        <td>{{bodega.ubicacion}}</td>
                        <td><a href="" ng-click="toggle('info', bodega.idbodega)">{{bodega.bodeguero}}</a></td>
                        <td>{{bodega.telefonobodega}}/{{bodega.telefonosecundariobodega}}</td>
                        <td>{{bodega.direccionbodega}}</td>
                        <td>{{bodega.namebodega}}</td>
                        <td class="text-center">

                            <div class="btn-group" role="group" aria-label="...">
                                <button type="button" class="btn btn-warning" ng-click="toggle('edit', bodega.idbodega)"
                                        data-toggle="tooltip" data-placement="bottom" >
                                <span class="glyphicon glyphicon-edit" aria-hidden="true" title="Editar">
                                </button>
                                <button type="button" class="btn btn-danger" ng-click="showModalConfirm(bodega.idbodega,0)"
                                        data-toggle="tooltip" data-placement="bottom"  >
                                    <span class="glyphicon glyphicon-trash" aria-hidden="true"  title="Eliminar"></span>
                                </button>
                            </div>
                            
                        </td>
                    </tr>
                    </tbody>
                </table>
                <dir-pagination-controls                           
                            template-url="dirPagination.html"
                            class="pull-left"
                            max-size="10"
                            direction-links="true"
                            boundary-links="true" >
                    </dir-pagination-controls>

            </div>

        </div>

        <div class="modal fade" tabindex="-1" role="dialog" id="modalAction">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header modal-header-primary">
                       
                            <h4 class="modal-title">{{form_title}}{{bodega.idbodega}} <span class='pull-right'> </span></h4>
                      
                    </div>
                    <div class="modal-body">

                        <div class="row">
                            <form class="form-horizontal" name="formBodega" novalidate="" >

                                <div class="col-xs-12">
                                    <fieldset>
                                        <legend>Datos de Bodega</legend>

                                        <div class="col-sm-8 col-xs-12">
                                            <div class="input-group">
                                                <span class="input-group-addon">Empleado Bodeguero: </span>
                                                <angucomplete-alt
                                                        id="nombre"
                                                        pause="400"
                                                        selected-object="testObj"
                                                        remote-url="{{API_URL}}bodega/getEmpleado/"
                                                        title-field="numdocidentific,namepersona,lastnamepersona"
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

                                        <div class="col-sm-4 col-xs-12">
                                            <div class="input-group">
                                                <span class="input-group-addon">Nombre Bodega: </span>
                                                <input type="text" class="form-control" name="namebodega" id="namebodega"
                                                       ng-required="true" ng-model="bodega.namebodega" ng-maxlength="25" ng-pattern="/[a-zA-ZáéíóúñÑ0-9. ]+/" >
                                            </div>
                                            <span class="help-block error"
                                                  ng-show="formBodega.namebodega.$invalid && formBodega.namebodega.$touched">El nombre de Bodega es requerido</span>
                                            <span class="help-block error"
                                                  ng-show="formBodega.namebodega.$invalid && formBodega.namebodega.$error.maxlength">La longitud máxima es de 25 números.</span>
                                            <span class="help-block error"
                                                  ng-show="formBodega.namebodega.$invalid && formBodega.namebodega.$error.pattern">El nombre de Bodega debe ser solo letras, puntos, números, guion y espacios.</span>
                                        </div>

                                        <div class="col-sm-4 col-xs-12" style="margin-top: 5px;">
                                            <div class="input-group">
                                                <span class="input-group-addon">Teléfono Principal: </span>
                                                <input type="text" class="form-control" name="telefonoprincipal" id="telefonoprincipal"
                                                       ng-required="true" ng-model="bodega.telefonobodega" ng-maxlength="9" ng-minlength="9" ng-pattern="/[0-9\-\ ]+$/" >
                                            </div>
                                            <span class="help-block error"
                                                  ng-show="formBodega.telefonoprincipal.$invalid && formBodega.telefonoprincipal.$touched">El Teléfono Principal es requerido</span>
                                            <span class="help-block error"
                                                  ng-show="formBodega.telefonoprincipal.$invalid && formBodega.telefonoprincipal.$error.maxlength">La longitud máxima es de 9 números.</span>
                                                                                        <span class="help-block error"
                                                  ng-show="formBodega.telefonoprincipal.$invalid && formBodega.telefonoprincipal.$error.minlength">La longitud mínima es de 9 números.</span>
                                            <span class="help-block error"
                                                  ng-show="formBodega.telefonoprincipal.$invalid && formBodega.telefonoprincipal.$error.pattern">El Teléfono debe ser solo números, guion y espacios.</span>
                                        </div>

                                        <div class="col-sm-4 col-xs-12" style="margin-top: 5px;">
                                            <div class="input-group">
                                                <span class="input-group-addon">Teléfono Secundario: </span>
                                                <input type="text" class="form-control" name="telefonosecundario" id="telefonosecundario"
                                                       ng-model="bodega.telefonosecundariobodega" ng-maxlength="9" ng-pattern="/[0-9\-\ ]+$/" ng-minlength="9" >
                                            </div>
                                            <span class="help-block error"
                                                  ng-show="formBodega.telefonosecundario.$invalid && formBodega.telefonosecundario.$error.maxlength">La longitud máxima es de 9 números</span>
                                            <span class="help-block error"
                                                  ng-show="formBodega.telefonosecundario.$invalid && formBodega.telefonosecundario.$error.minlength">La longitud mínima es de 9 números</span>
                                            <span class="help-block error"
                                                  ng-show="formBodega.telefonosecundario.$invalid && formBodega.telefonosecundario.$error.pattern">El Teléfono debe ser solo números, guion y espacios</span>
                                        </div>

                                        <div class="col-sm-4 col-xs-12" style="margin-top: 5px;">
                                            <div class="input-group">
                                                <span class="input-group-addon">Teléfono Opcional: </span>
                                                <input type="text" class="form-control" name="opcional" id="opcional"
                                                       ng-model="bodega.telefonoopcionalbodega" ng-maxlength="9" ng-pattern="/[0-9\-\ ]+$/" ng-minlength="9" >
                                            </div>
                                            <span class="help-block error"
                                                  ng-show="formBodega.opcional.$invalid && formBodega.opcional.$error.maxlength">La longitud máxima es de 9 números</span>
                                                  <span class="help-block error"
                                                  ng-show="formBodega.opcional.$invalid && formBodega.opcional.$error.minlength">La longitud mínima es de 9 números</span>
                                            <span class="help-block error"
                                                  ng-show="formBodega.opcional.$invalid && formBodega.opcional.$error.pattern">El Teléfono debe ser solo números, guion y espacios</span>

                                        </div>

                                        <div class="col-xs-12" style="margin-top: 5px;">
                                            <div class="input-group">
                                                <span class="input-group-addon">C. Contab.: </span>
                                                    <input type="text" class="form-control" name="idplancuenta" id="idplancuenta" ng-model="idplancuenta" placeholder=""
                                                           ng-required="true" readonly>
                                                        <span class="input-group-btn" role="group">
                                                    <button type="button" class="btn btn-info" id="btn-pcc" ng-click="showPlanCuenta()">
                                                        <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                                    </button>
                                                </span>

                                            </div>
                                            <span class="help-block error" ng-show="formBodega.idplancuenta.$error.required">La asignación de una cuenta es requerida</span>
                                        </div>

                                    </fieldset>
                                </div>

                                <div class="col-xs-12">
                                    <fieldset>
                                        <legend>Datos Ubicación Bodega</legend>

                                        <div class="col-sm-4 col-xs-12" style="margin-top: 5px;">
                                            <div class="input-group">
                                                <span class="input-group-addon">Provincia: </span>
                                                <select class="form-control" name="provincia" id="provincia" ng-model="provincia"
                                                        ng-required="true" ng-change="loadCiudad(provincia,false)"
                                                        ng-options="item.idprovincia as item.nameprovincia for item in provincias">
                                                    <option value="">-- Seleccione --</option>
                                                </select>
                                            </div>
                                            <span class="help-block error"
                                                  ng-show="formBodega.provincia.$invalid && formBodega.provincia.$touched">La provincia es requerida.</span>
                                        </div>

                                        <div class="col-sm-4 col-xs-12" style="margin-top: 5px;">
                                            <div class="input-group">
                                                <span class="input-group-addon">Cantón: </span>
                                                <select class="form-control" name="ciudad" id="ciudad" ng-model="ciudad"
                                                        ng-required="true" ng-change="loadSector(ciudad,false,0)"
                                                        ng-options="item.idcanton as item.namecanton for item in ciudades">
                                                    <option value="">-- Seleccione --</option>
                                                </select>
                                            </div>
                                            <span class="help-block error"
                                                  ng-show="formBodega.ciudad.$invalid && formBodega.ciudad.$touched">El Canton es requerido.</span>
                                        </div>

                                        <div class="col-sm-4 col-xs-12" style="margin-top: 5px;">
                                            <div class="input-group">
                                                <span class="input-group-addon">Parroquía: </span>
                                                <select class="form-control" name="idsector" id="idsector" ng-model="bodega.idparroquia"
                                                        ng-required="true" ng-options="item.idparroquia as item.nameparroquia for item in sectores">
                                                    <option value="">-- Seleccione --</option>
                                                </select>
                                            </div>
                                            <span class="help-block error"
                                                  ng-show="formBodega.idsector.$invalid && formBodega.idsector.$touched">La parroquia es requerida.</span>
                                        </div>

                                        <div class="col-xs-12" style="margin-top: 5px;">
                                            <div class="input-group">
                                                <span class="input-group-addon">Dirección: </span>
                                                <input type="text" class="form-control" name="direccion" id="direccion"
                                                       ng-required="true" ng-model="bodega.direccionbodega">
                                            </div>
                                            <span class="help-block error"
                                                  ng-show="formBodega.direccion.$invalid && formBodega.direccion.$touched">La dirección es requerida.</span>

                                        </div>

                                        <div class="col-xs-12" style="margin-top: 5px;">
                                            <textarea class="form-control" placeholder="Observación" name="observacion" id="observacion" rows="2" cols="80" ng-model="bodega.observacion" ng-maxlength="1024" ng-pattern="/[a-zA-ZáéíóúñÑ0-9. ]+/"></textarea>
                                            <span class="help-block error"
                                                  ng-show="formBodega.observacion.$invalid && formBodega.observacion.$error.maxlength">La longitud máxima es de 1024 caracteres.</span>
                                            <span class="help-block error"
                                                  ng-show="formBodega.observacion.$invalid && formBodega.observacion.$error.pattern">La Observación debe ser solo letras, puntos, números, guion y espacios.</span>
                                        </div>

                                    </fieldset>
                                </div>

                            </form>
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
                        <span>Está seguro que desea Eliminar la bodega: <span style="font-weight: bold;">{{bodega.namebodega}}</span></span>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            Cancelar <span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span>
                        </button>
                        <button type="button" class="btn btn-danger" id="btn-save" ng-click="eliminarBodega()">Eliminar
                            <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                        </button>
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
                            <div class="col-xs-12 text-center" style="font-size: 18px;">{{empleado.namepersona}} {{empleado.lastnamepersona}}</div>
                            <div class="col-xs-12">
                                <span style="font-weight: bold">Cargo: </span>{{empleado.namecargo}}
                            </div>
                            <div class="col-xs-12">
                                <span style="font-weight: bold">Empleado desde: </span>{{formatoFecha(empleado.fechaingreso)}}
                            </div>
                            <div class="col-xs-12">
                                <span style="font-weight: bold">Teléfonos: </span>{{empleado.telefprincipaldomicilio}} / {{empleado.telefsecundariodomicilio}}
                            </div>
                            
                            <div class="col-xs-12">
                                <span style="font-weight: bold">Celular: </span>{{empleado.celphone}}
                            </div>
                            <div class="col-xs-12">
                                <span style="font-weight: bold">Dirección: </span>{{empleado.direccion}}
                            </div>
                            <div class="col-xs-12">
                                <span style="font-weight: bold">Email: </span>{{empleado.email}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" tabindex="-1" role="dialog" id="modalPlanCuenta">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header modal-header-primary">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Plan de Cuenta</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">

                            <div class="col-xs-12">
                                <div class="form-group  has-feedback">
                                    <input type="text" class="form-control" id="" ng-model="searchContabilidad" placeholder="BUSCAR..." >
                                    <span class="glyphicon glyphicon-search form-control-feedback" ></span>
                                </div>
                            </div>

                            <div class="col-xs-12">
                                <table class="table table-responsive table-striped table-hover table-condensed table-bordered">
                                    <thead class="bg-primary">
                                    <tr>
                                        <th style="width: 15%;">ORDEN</th>
                                        <th>CONCEPTO</th>
                                        <th style="width: 10%;">CODIGO</th>
                                        <th style="width: 4%;"></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr ng-repeat="item in cuentas | filter:searchContabilidad" ng-cloak >
                                        <td>{{item.jerarquia}}</td>
                                        <td>{{item.concepto}}</td>
                                        <td>{{item.codigosri}}</td>
                                        <td>
                                            <input ng-show="item.madreohija=='1'" ng-hide="item.madreohija!='1'" type="radio" name="select_cuenta"  ng-click="click_radio(item)">
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            Cancelar <span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span>
                        </button>
                        <button type="button" class="btn btn-primary" id="btn-ok" ng-click="selectCuenta()">
                            Aceptar <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>


    </div>
    
