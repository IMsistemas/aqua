
    <div ng-controller="comprasproductoController">
    
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
                    <select class="form-control" name="proveedor" id="proveedor" ng-model="proveedorFiltro"
                      ng-change="searchByFilter()">
                        <option value="">Proveedor</option>
						<option ng-repeat="item in proveedoresFiltro"						       
						        value="{{item.idproveedor}}">{{item.razonsocialproveedor}}     
						</option>                        
                        </select>                    
                </div>
            </div>
            
            <div class="col-sm-2 col-xs-3">
                <div class="form-group has-feedback">
                    <select class="form-control" name="estado" id="estado" ng-model="estadoFiltro"
                        ng-change="searchByFilter()">
                        <option value="">Estado</option>
						<option ng-repeat="item in estados"						       
						        value="{{item.id}}">{{item.nombre}}     
						</option>                        
                        </select>                    
                </div>
            </div>
 

            <div class="col-sm-4 col-xs-6">
                <button type="button" class="btn btn-primary" style="float: right;" onclick="window.open('compras/formulario/0', '_blank')">
                   <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                </button>
            </div>

            <div class="col-xs-12">
                <table class="table table-responsive table-striped table-hover table-condensed">
                    <thead class="bg-primary">
                    <tr>
                        <th style="text-align: center;" ng-click="sort('codigocompra')">
                        Código
                         <span class="glyphicon sort-icon" ng-show="sortKey=='codigocompra'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                        </th>
                        <th style="text-align: center;" ng-click="sort('fecharegistrocompra')">
                        Fecha Ingreso
                        <span class="glyphicon sort-icon" ng-show="sortKey=='fecharegistrocompra'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                        </th>
                        <th style="text-align: center;" ng-click="sort('razonsocialproveedor')">
                        Proveedor
                        <span class="glyphicon sort-icon" ng-show="sortKey=='razonsocialproveedor'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                        </th>     
                        <th style="text-align: center;">Subtotal</th>   
                        <th style="text-align: center;">IVA</th> 
                        <th style="text-align: center;" ng-click="sort('totalcompra')">
                        Total
                         <span class="glyphicon sort-icon" ng-show="sortKey=='totalcompra'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                        </th>
                        <th style="text-align: center;" ng-click="sort('estapagada')">
                        Estado
                         <span class="glyphicon sort-icon" ng-show="sortKey=='estapagada'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                        </th>                 
                        <th style="width: 10%;">Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr dir-paginate="item in compras|orderBy:sortKey:reverse|itemsPerPage:10" >
                        <td style="text-align: center;">{{item.codigocompra}}</td>
                        <td>{{formatoFecha(item.fecharegistrocompra)}}</td>
                        <td>{{item.razonsocialproveedor}}</td>
                        <td>{{ sumar(item.subtotalnoivacompra,item.subtotalivacompra) }}</td>
                        <td>{{item.ivacompra  }}</td>
                        <td>{{item.totalcompra}}</td>                      
                        <td>{{(item.estapagada)?'Pagado':'No Pagado'}}</td>
                        <td>
                            <button type="button" class="btn btn-info" ng-click="loadPage(item.codigocompra)"
                                    data-toggle="tooltip" data-placement="bottom" title="Ver" >
                                <span class="glyphicon glyphicon-ok-circle" aria-hidden="true"></span>
                            </button>
                            <button type="button" class="btn btn-danger" ng-click="showModalConfirm(item.codigocompra,0)"
                                    data-toggle="tooltip" data-placement="bottom" title="Anular"  ng-disabled="item.estaanulada==1">
                                <span class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span>
                            </button>
                                                        
                        </td>
                    </tr>
                    </tbody>
                </table>
                <dir-pagination-controls
			       max-size="5"
			       direction-links="true"
			       boundary-links="true" >
			    </dir-pagination-controls>

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

        <div class="modal fade" tabindex="-1" role="dialog" id="modalConfirmAnular">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header modal-header-danger">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Confirmación</h4>
                    </div>
                    <div class="modal-body">
                        <span>Está seguro que desea Anular la compra seleccionada?</span>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" id="btn-save" ng-click="anularCompra()">Anular</button>
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
    
