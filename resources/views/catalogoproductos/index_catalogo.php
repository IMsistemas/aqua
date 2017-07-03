


    <div ng-controller="catalogoproductosController">

            <div class="col-xs-12">

                <h4>Gestión de Item</h4>

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
                        <select class="form-control" name="linea" id="linea" ng-model="lineaFiltro"
                            ng-change="loadSubLinea(lineaFiltro,true,0); searchByFilter();">
                            <option value="">Línea</option>
                            <option ng-repeat="item in lineasFiltro"
                                    value="{{item.jerarquia}}">{{item.nombrecategoria}}
                            </option>
                            </select>
                    </div>
                </div>
                <div class="col-sm-2 col-xs-3">
                    <div class="form-group has-feedback">
                        <select class="form-control" name="sublinea" id="sublinea" ng-model="idCategoria"
                            ng-change="searchByFilter()">
                            <option value="">Sublínea</option>
                            <option ng-repeat="item in sublineasFiltro"
                                    value="{{item.idcategoria}}">{{item.nombrecategoria}}
                            </option>
                            </select>
                    </div>
                </div>

                <div class="col-sm-2 col-xs-3">
                    <button type="button" class="btn btn-primary" style="float: right;" ng-click="toggle('add', 0)">
                       Agregar <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    </button>
                </div>

                <div class="col-xs-12" style="font-size: 12px !important;">
                    <table class="table table-responsive table-striped table-hover table-condensed table-bordered">
                        <thead class="bg-primary">
                        <tr>
                            <th style="text-align: center;">FOTO</th>
                            <th style="text-align: center; width: 15%;"">CODIGO</th>
                            <th style="text-align: center; width: 35%;"">DETALLE ITEM</th>
                            <th style="text-align: center; width: 15%;"">TIPO ITEM</th>
                            <th style="text-align: center; width: 10%;">FECHA INGRESO</th>
                            <th style="text-align: center; width: 15%;">ACCIONES</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr dir-paginate="producto in items | orderBy:sortKey:reverse | itemsPerPage:5"  >
                            <td style="text-align: center;">
                                <img class="img-thumbnail" ng-if="producto.foto" ng-src="{{ producto.foto }}" onerror="defaultImage(this)"  style="width: 100%;" >
                            </td>
                            <td style="">{{producto.codigoproducto}}</td>
                            <td style="">{{producto.nombreproducto}}</td>
                            <td style="">{{producto.nameclaseitem}}</td>
                            <td style="text-align: center;">{{ formatDate(producto.created_at) | date:'yyyy-MM-dd' }}</p></td>
                            <td style="text-align: center;">
                                <button type="button" class="btn btn-warning" ng-click="toggle('edit', producto.idcatalogitem)"
                                        data-toggle="tooltip" data-placement="bottom" title="Editar" >
                                    <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                                </button>
                                <button type="button" class="btn btn-danger" ng-click="showModalConfirm(producto.idcatalogitem)"
                                        data-toggle="tooltip" data-placement="bottom" title="Eliminar">
                                    <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                                </button>
                                <button type="button" class="btn btn-info" ng-click="toggle('info',producto.idcatalogitem)"
                                        data-toggle="tooltip" data-placement="bottom" title="Información">
                                    <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                                </button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <dir-pagination-controls                           
                            template-url="dirPagination.html"
                            class="pull-right"
                            max-size="5"
                            direction-links="true"
                            boundary-links="true" >
                    </dir-pagination-controls>
                 
                    
                </div>

           

        </div>


        <div class="modal fade" tabindex="-1" role="dialog" id="modalAction">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header modal-header-primary">

                        <h4 class="modal-title">{{form_title}} {{producto.idcatalogitem}} </h4>

                    </div>
                    <div class="modal-body">

                        <div class="row">
                            <form class="form-horizontal" name="formProducto" id="formProducto" novalidate="" >

                                <div class="col-xs-12">
                                    <div class="col-sm-5 col-xs-12">
                                        <div class="input-group">
                                            <span class="input-group-addon">Código Item: </span>
                                            <input type="text" class="form-control" name="t_codigoitem" id="t_codigoitem" ng-model="producto.codigoproducto" ng-required="true" ng-maxlength="200" ng-pattern="/[a-zA-ZáéíóúñÑ0-9 ]+/"/>
                                        </div>
                                        <span class="help-block error"
                                                      ng-show="formProducto.t_codigoitem.$invalid && formProducto.t_codigoitem.$touched">El Código es requerido</span>
                                                <span class="help-block error"
                                                      ng-show="formProducto.t_codigoitem.$invalid && formProducto.t_codigoitem.$error.maxlength">La longitud máxima es de 200 caracteres</span>
                                                <span class="help-block error"
                                                      ng-show="formProducto.t_codigoitem.$invalid && formProducto.t_codigoitem.$error.pattern">El Código debe ser solo letras y espacios</span>
                                    </div>

                                    <div class="col-sm-7 col-xs-12">
                                        <div class="input-group">
                                            <span class="input-group-addon">Detalle Item: </span>
                                            <input type="text" class="form-control" name="t_detalleitem" id="t_detalleitem" ng-model="producto.nombreproducto" ng-required="true" ng-maxlength="200" ng-pattern="/[a-zA-ZáéíóúñÑ0-9 ]+/"/>
                                        </div>
                                        <span class="help-block error"
                                                      ng-show="formProducto.t_detalleitem.$invalid && formProducto.t_detalleitem.$touched">El Detalle es requerido</span>
                                                <span class="help-block error"
                                                      ng-show="formProducto.t_detalleitem.$invalid && formProducto.t_detalleitem.$error.maxlength">La longitud máxima es de 200 caracteres</span>
                                                <span class="help-block error"
                                                      ng-show="formProducto.t_detalleitem.$invalid && formProducto.t_detalleitem.$error.pattern">El Detalle debe ser solo letras y espacios</span>
                                    </div>

                                    <div class="col-sm-12 col-xs-12" style="margin-top: 5px;">
                                        <div class="input-group">
                                            <span class="input-group-addon">Tipo Item: </span>
                                            <select class="form-control" name="s_tipoitem" id="s_tipoitem"
                                                    ng-model="producto.idclaseitem" ng-options="value.id as value.label for value in tipo" required>
                                            </select>
                                        </div>
                                        <span class="help-block error"
                                              ng-show="formProducto.s_tipoitem.$invalid && formProducto.s_tipoitem.$touched">El Tipo de Item es requerido</span>
                                    </div>


                                    

                                    <div class="col-sm-6 col-xs-12" style="margin-top: 5px;">
                                        <div class="input-group">
                                            <span class="input-group-addon">Línea: </span>                                            
                                            <select class="form-control" name="s_linea" id="s_linea"
                                                    ng-model="s_linea" ng-options="value.id as value.label for value in lineas" required ng-change="loadSubLinea(s_linea,false,0)">
                                            </select>
                                       
                                    	</div>
                                    	 <span class="help-block error"
                                              ng-show="formProducto.s_linea.$invalid && formProducto.s_linea.$touched">La línea es requerida</span>
                                    </div>

                                    <div class="col-sm-6 col-xs-12" style="margin-top: 5px;">
                                        <div class="input-group">
                                            <span class="input-group-addon">SubLínea: </span>
                                            <select class="form-control" name="s_sublinea" id="s_sublinea" ng-model="producto.idcategoria"
                                            	ng-options="value.id as value.label for value in sublineas" 
                                            	required>
                                            	</select>
                                        </div>
                                        <span class="help-block error"
                                              ng-show="formProducto.s_sublinea.$invalid && formProducto.s_sublinea.$touched">La sublínea es requerida</span>
                                    </div>

                                    <div class="col-sm-6 col-xs-12" style="margin-top: 5px;">
                                        <div class="input-group">
                                            <span class="input-group-addon">Precio Venta: </span>
                                            <input type="text" class="form-control" name="t_precioventa" id="t_precioventa" ng-model="producto.precioventa" ng-pattern="/^[0-9]+([.][0-9]+)?$/"/>
                                        </div>
                                        <!--<span class="help-block error"
                                                      ng-show="formProducto.t_precioventa.$invalid && formProducto.t_precioventa.$touched">El Precio Venta es requerido</span>-->
                                        <span class="help-block error"
                                                      ng-show="formProducto.t_precioventa.$invalid && formProducto.t_precioventa.$error.pattern">El Precio Venta debe ser solo numeros</span>
                                    </div>

                                    <div class="col-sm-6 col-xs-12" style="margin-top: 5px;">
                                        <div class="input-group">
                                            <span class="input-group-addon">IVA: </span>
                                            <select class="form-control" name="s_iva" id="s_iva"
                                                    ng-model="producto.idtipoimpuestoiva" ng-options="value.id as value.label for value in imp_iva" required>
                                            </select>
                                        </div>
                                        <span class="help-block error"
                                              ng-show="formProducto.s_iva.$invalid && formProducto.s_iva.$touched">El Impuesto IVA es requerido</span>
                                    </div>

                                    <div class="col-xs-12" style="margin-top: 5px;">
                                        <div class="input-group">
                                            <span class="input-group-addon">ICE: </span>
                                            <select class="form-control" name="s_ice" id="s_ice"
                                                    ng-model="producto.idtipoimpuestoice" ng-options="value.id as value.label for value in imp_ice">
                                            </select>
                                        </div>
                                        <span class="help-block error"
                                              ng-show="formProducto.s_ice.$invalid && formProducto.s_ice.$touched">El Impuesto ICE es requerido</span>
                                    </div>

                                    <!--<div class="col-xs-12" style="margin-top: 5px;">
                                        <div class="input-group">
                                            <span class="input-group-addon">Cuenta Contable: </span>
                                            <input type="text" class="form-control" name="t_cuentacontable" id="t_cuentacontable" ng-model="t_cuentacontable" placeholder=""
                                                   ng-required="true" readonly>
                                            <input type="hidden" name="h_idplancuenta" id="h_idplancuenta" ng-model="producto.idplancuenta">
                                            <span class="input-group-btn" role="group">
                                                <button type="button" class="btn btn-info" id="btn-pcc" ng-click="showPlanCuenta(1)">
                                                    <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                                </button>
                                            </span>
                                        </div>
                                        <span class="help-block error" ng-show="formProducto.t_cuentacontable.$error.required">La asignación de una cuenta es requerida</span>
                                    </div>-->

                                    <div class="col-xs-12" style="margin-top: 5px;">
                                        <div class="input-group">
                                            <span class="input-group-addon">Cuenta Contable: </span>
                                            <input type="text" class="form-control" name="t_cuentacontableingreso" id="t_cuentacontableingreso"
                                                   ng-model="t_cuentacontableingreso" placeholder=""  readonly ng-required="true">
                                            <input type="hidden" name="producto.idplancuenta_ingreso" id="h_idplancuenta_i" ng-model="h_idplancuenta_i">
                                            <span class="input-group-btn" role="group">
                                                <button type="button" class="btn btn-info" id="btn-pcc_i" ng-click="showPlanCuenta(2)">
                                                    <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                                </button>
                                            </span>
                                        </div>
                                        <span class="help-block error" ng-show="formProducto.t_cuentacontableingreso.$error.required">La asignación de una cuenta es requerida</span>
                                    </div>

                                    <div class="col-xs-12" style="margin-top: 5px;">
                                        <div class="input-group">
                                            <span class="input-group-addon">Foto: </span>
                                            <input type="file" ngf-select class="form-control" name="t_file" id="t_file" ng-model="producto.foto" accept="image/*" ngf-max-size="2MB" ngf-pattern="image/*"  onchange="angular.element(this).scope().photoChanged(this.files)"  />
                                        </div>
                                        <span class="help-block error"
										           ng-show="formProducto.t_file.$error.pattern">El archivo debe ser Imagen</span>
											      <span class="help-block error"
											       ng-show="formProducto.t_file.$error.maxSize">El tamaño máximo es de 2 MB </span> 
                                    </div>
                                    <div class="col-xs-12" style="margin-top: 25px;">
                                         
                                         <img class="img-circle" ng-if="producto.foto" ng-src="{{ thumbnail.dataUrl  }}" onerror="defaultImage(this)"  style="width: 150px;" >
                                    </div>
                                </div>

                            </form>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            Cancelar <span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span>
                        </button>
                        <button type="button" class="btn btn-success" id="btn-save" ng-click="save(modalstate, id)" ng-disabled="formProducto.$invalid">
                            Guardar   <span class="glyphicon glyphicon-floppy-saved" aria-hidden="true"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>


        <!--<div class="modal fade" tabindex="-1" role="dialog" id="modalAction">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header modal-header-primary">

                        <h4 class="modal-title">{{form_title}}{{producto.codigoproducto}} <span class='pull-right'> Fecha Creación: {{formatoFecha(producto.fechaingreso)}}</span></h4>

                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal" name="formProducto" id="formProducto" novalidate="" >

                            <div class="form-group">
                                <label class="col-sm-4 control-label">Nombre del Producto:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="nombre" id="nombre"
                                           ng-model="producto.nombreproducto" ng-required="true" ng-maxlength="16" ng-pattern="/[a-zA-ZáéíóúñÑ ]+/" >
                                    <span class="help-block error"
                                          ng-show="formProducto.nombre.$invalid && formProducto.nombre.$touched">El Nombre es requerido</span>
                                    <span class="help-block error"
                                          ng-show="formProducto.nombre.$invalid && formProducto.nombre.$error.maxlength">La longitud máxima es de 16 caracteres</span>
                                    <span class="help-block error"
                                          ng-show="formProducto.nombre.$invalid && formProducto.nombre.$error.pattern">El Nombre debe ser solo letras y espacios</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Selecione Categoría:</label>
                                <div class="col-sm-8">
                                    <select class="form-control" name="categoria" id="categoria" ng-model="categoria" ng-required="true" ng-change="loadLinea(categoria,false)">
                                        <option value="">Categoría</option>
                                        <option ng-repeat="item in categorias"
                                                value="{{item.idcategoria}}">{{item.nombrecategoria}}
                                        </option>
                                    </select>
                                    <span class="help-block error"
                                          ng-show="formProducto.categoria.$invalid && formProducto.categoria.$touched">La categoria es requerida</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Selecione Línea:</label>
                                <div class="col-sm-8">
                                    <select class="form-control" name="linea" id="linea" ng-model="linea"  ng-required="true" ng-change="loadSublinea(linea,false)">
                                        <option value="">Línea</option>
                                        <option ng-repeat="item in lineas"
                                                value="{{item.idcategoria}}">{{item.nombrecategoria}}
                                        </option>
                                    </select>
                                    <span class="help-block error"
                                          ng-show="formProducto.linea.$invalid && formProducto.linea.$touched">La Línea es requerida</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Selecione Sublínea:</label>
                                <div class="col-sm-8">
                                    <select class="form-control" name="idcategoria" id="idcategoria" ng-model="producto.idcategoria" ng-required="true">
                                        <option value="">Sublínea</option>
                                        <option ng-repeat="item in sublineas"
                                                value="{{item.idcategoria}}">{{item.nombrecategoria}}
                                        </option>
                                    </select>
                                    <span class="help-block error"
                                          ng-show="formProducto.idcategoria.$invalid && formProducto.idcategoria.$touched">La Sublínea es requerida</span>
                                </div>
                            </div>

                            <div class="form-group" ng-show="modalstate!='edit'">
                                <label for="foto" class="col-sm-4 control-label">Foto del Producto:</label>
                                <div class="col-sm-8">
                                    <input type="file" ngf-select name="foto" id="foto"  ng-model="producto.rutafoto"
                                           accept="image/*" ngf-max-size="2MB"  ng-required="true" ngf-pattern="image/*"
                                    >
                                    <span class="help-block error"
                                          ng-show="formProducto.foto.$error.required">La Foto del producto es requerida</span>
                                    <span class="help-block error"
                                          ng-show="formProducto.foto.$error.pattern">El archivo debe ser Imagen</span>
                                    <span class="help-block error"
                                          ng-show="formProducto.foto.$error.maxSize">El tamaño máximo es de 2 MB </span>

                                </div>
                            </div>
                            <div class="form-group" ng-show="modalstate=='edit'">
                                <label for="foto" class="col-sm-4 control-label">Foto del Producto:</label>
                                <div class="col-sm-8">
                                    <input type="file" ngf-select name="fotoedit" id="fotoedit"  ng-model="producto.rutafoto"
                                           accept="image/*" ngf-max-size="2MB" ngf-pattern="image/*"
                                    >
                                    <span class="help-block error"
                                          ng-show="formProducto.fotoedit.$error.pattern">El archivo debe ser Imagen</span>
                                    <span class="help-block error"
                                          ng-show="formProducto.fotoedit.$error.maxSize">El tamaño máximo es de 2 MB </span>

                                </div>
                            </div>
                            <div class="form-group" >
                                <label for="foto" class="col-sm-4 control-label"></label>
                                <div class="col-sm-8">
                                    <img id="fotoPre" ng-src="{{ rutafoto }}" onerror="defaultImage(this)" class="img-circle" style="width:150px" >
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">
                                    Cancelar <span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span>
                                </button>
                                <button type="button" class="btn btn-success" id="btn-save" ng-click="save(modalstate, id)" ng-disabled="formProducto.$invalid">
                                    Guardar   <span class="glyphicon glyphicon-floppy-saved" aria-hidden="true"></span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>-->

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

        <div class="modal fade" tabindex="-1" role="dialog" id="modalMessageError">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header modal-header-danger">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Información</h4>
                    </div>
                    <div class="modal-body">
                        <span>{{message_error}}</span>
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
                        <span>Realmente desea eliminar el Item: <span style="font-weight: bold;">{{producto.nombreproducto}}</span></span>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" id="btn-save" ng-click="destroyProducto()">Eliminar</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" tabindex="-1" role="dialog" id="modalInfoEmpleado">
            <div class="modal-dialog " role="document">
                <div class="modal-content">
                    <div class="modal-header modal-header-info">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Información del Producto </h4>
                    </div>
                    <div class="modal-body">
                        <div class="col-xs-12 text-center">
                            <img ng-if="producto.foto" ng-src="{{ producto.foto }}" onerror="defaultImage(this)" class="img-circle" style="width:150px" >

                        </div>
                        <div class="row">
                            <div class="col-xs-12 text-center" style="font-size: 18px;">{{producto.nombreproducto}}</div>

                            <div class="col-xs-12">
                                <span style="font-weight: bold">Fecha Ingreso: </span>{{ formatDate(producto.created_at) | date:'yyyy-MM-dd' }}
                            </div>
                            <div class="col-xs-12">
                                <span style="font-weight: bold">Línea: </span>{{linea}}
                            </div>
                            <div class="col-xs-12">
                                <span style="font-weight: bold">Sublínea: </span>{{producto.nombrecategoria}}
                            </div>
                            <div class="col-xs-12">
                                <span style="font-weight: bold">Tipo Item: </span>{{producto.nameclaseitem}}
                            </div>
                            <div class="col-xs-12">
                                <span style="font-weight: bold">Precio Venta: </span>{{producto.precioventa}}
                            </div>
                            <div class="col-xs-12">
                                <span style="font-weight: bold">IVA: </span>{{producto.nametipoimpuestoiva}}
                            </div>
                            <div class="col-xs-12">
                                <span style="font-weight: bold">ICE: </span>{{producto.nametipoimpuestoice}}
                            </div>

                            <div class="col-xs-12">
                                <span style="font-weight: bold">Cuenta contable Ingreso: </span>{{producto.c2}}
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
