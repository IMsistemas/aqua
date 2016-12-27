
    <div ng-controller="catalogoproductosController">
    
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
                    <select class="form-control" name="categoria" id="categoria" ng-model="categoriaFiltro"
                        ng-change="loadLinea(categoriaFiltro,true)">
                        <option value="">Categoría</option>
						<option ng-repeat="item in categoriasFiltro"						       
						        value="{{item.idcategoria}}">{{item.nombrecategoria}}     
						</option>                        
                        </select>                    
                </div>
            </div>
            
            <div class="col-sm-2 col-xs-3">
                <div class="form-group has-feedback">
                    <select class="form-control" name="linea" id="linea" ng-model="lineaFiltro"
                        ng-change="loadSublinea(lineaFiltro,true)">
                        <option value="">Línea</option>
						<option ng-repeat="item in lineasFiltro"						       
						        value="{{item.idcategoria}}">{{item.nombrecategoria}}     
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
                   <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                </button>
            </div>

            <div class="col-xs-12">
                <table class="table table-responsive table-striped table-hover table-condensed">
                    <thead class="bg-primary">
                    <tr>
                        <th>Foto</th>
                        <th style="text-align: center;">Código</th>
                        <th style="text-align: center;">Producto</th>
                        <th style="text-align: center;">Fecha Ingreso</th>                       
                        <th style="width: 20%;">Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr ng-repeat="producto in productos" >
                        <td style="text-align: center;">
                        <img class="img-circle" ng-if="producto.rutafoto" ng-src="{{ producto.rutafoto }}" onerror="defaultImage(this)"  style="width: 50px;" >
                        </td>
                        <td>{{producto.codigoproducto}}</td>
                        <td>{{producto.nombreproducto}}</td>
                        <td>{{formatoFecha(producto.fechaingreso)}}</td>
                        <td>
                            <button type="button" class="btn btn-warning" ng-click="toggle('edit', producto.codigoproducto)"
                                    data-toggle="tooltip" data-placement="bottom" title="Editar" >
                                <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                            </button>
                            <button type="button" class="btn btn-danger" ng-click="showModalConfirm(producto.codigoproducto)"
                                    data-toggle="tooltip" data-placement="bottom" title="Eliminar">
                                <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                            </button>
                            <button type="button" class="btn btn-info" ng-click="toggle('info',producto.codigoproducto)"
                                    data-toggle="tooltip" data-placement="bottom" title="Información">
                                <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                            </button>
                        </td>
                    </tr>
                    </tbody>
                </table>

            </div>

        </div>

        <div class="modal fade" tabindex="-1" role="dialog" id="modalAction">
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
                        <span>Realmente desea eliminar el Item: <span style="font-weight: bold;">{{producto.nombreproducto}}</span></span>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" id="btn-save" ng-click="destroyProducto()">Eliminar</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" tabindex="-1" role="dialog" id="modalInfoEmpleado">
            <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content">
                    <div class="modal-header modal-header-info">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Información del Producto </h4>
                    </div>
                    <div class="modal-body">
                        <div class="col-xs-12 text-center">
                       			<img ng-if="rutafoto" ng-src="{{ rutafoto }}" onerror="defaultImage(this)" class="img-circle" style="width:150px" >
                            
                        </div>
                        <div class="row text-center">
                            <div class="col-xs-12 text-center" style="font-size: 18px;">{{nombreproducto}}</div>
                            
                            <div class="col-xs-12">
                                <span style="font-weight: bold">Fecha Ingreso: </span>{{formatoFecha(fechaingreso)}}
                            </div>
                            
                            <div class="col-xs-12">
                                <span style="font-weight: bold">Categoría: </span>{{categoria}}
                            </div>
                            <div class="col-xs-12">
                                <span style="font-weight: bold">Línea: </span>{{linea}}
                            </div>
                            <div class="col-xs-12">
                                <span style="font-weight: bold">Sublínea: </span>{{sublinea}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    </div>
    

