    <div ng-controller="clientesController">
        <div   class="container">

            <div class="container" style="margin-top: 2%;">
            <fieldset>
                <legend style="padding-bottom: 10px;">
                    <span style="font-weight: bold;">ADMINISTRACION DE CLIENTES</span>
                    <button type="button" class="btn btn-primary" style="float: right;" ng-click="toggle('add', 0)">Agregar</button>
                </legend>
            <div class="col-xs-6">
                <div class="form-group has-feedback">
                    <input type="text" class="form-control input-sm" id="search-list-trans" placeholder="BUSCAR..." ng-model="busqueda">
                    <span class="glyphicon glyphicon-search form-control-feedback" aria-hidden="true"></span>
                </div>

            </div>
            <div class="col-xs-12">
            <table class="table table-responsive table-striped table-hover table-condensed" >
                <thead class="bg-primary">
                    <tr>
                        <th style="width: 90px;">Documento de Identidad</th>
                        <th>Fecha</th>
                        <th>Razón Social</th>
                        <th>Teléfono</th>
                        <th>Teléfono Secundario</th>
                        <th>Celular</th>
                        <th>Dirección</th>
                        <th>Correo</th>
                        <th style="width: 180px;" colspan="3" class="text-center">Acciones</th>
                        
                    </tr>
                </thead>
                <tbody>
                    <tr ng-repeat="cliente in clientes|filter:busqueda">
                        <td class="text-center">{{cliente.documentoidentidad}}</td>
                        <td>{{cliente.fechaingreso|date}}</td>
                        <td>{{cliente.apellido+' '+cliente.nombre}}</td>                    
                        <td>{{cliente.telefonoprincipal}}</td>   
                        <td>{{cliente.telefonosecundario}}</td>
                        <td>{{cliente.celular}}</td>
                        <td>{{cliente.direccion}}</td>
                        <td>{{cliente.correo}}</td>
                        <td class="text-center">
                            <button class="btn btn-default btn-xs btn-detail" ng-click="toggle('edit', cliente.documentoidentidad)">Editar Cliente</button>
                        </td>
                        <td class="text-center">
                            <button class="btn btn-danger btn-xs btn-delete" ng-click="confirmDelete(cliente.documentoidentidad)">Borrar Cliente</button>
                        </td>
                    </tr>

                </tbody>
                    
            </table>
            </fieldset>

            <!-- End of Table-to-load-the-data Part -->
            <!-- Modal (Pop up when detail button clicked) -->


            <div class="modal fade" id="myModal" tabindex="-1" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">{{form_title}}</h4>
                        </div>
                        <div class="modal-body">
                            <form name="frmClientes" class="form-horizontal" novalidate="">

                                <div class="form-group error">
                                    <label for="inputEmail3" class="col-sm-3 control-label">Documento de identidad del Cliente</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control has-error" id="documentoidentidad" name="documentoidentidad" placeholder="" 
                                        ng-model="cliente.documentoidentidad" ng-required="true">
                                        <span class="help-inline" 
                                        ng-show="frmClientes.documentoidentidad.$invalid && frmClientes.documentoidentidad.$touched">La cédula del cliente es requerida</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-3 control-label">Fecha de Ingreso</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="fechaingreso" name="fechaingreso"="cliente.fechaingreso" ng-required="true">
                                        <span class="help-inline" 
                                        ng-show="frmClientes.fechaingreso && frmClientes.fechaingreso">La fecha de ingreso del cliente es requerida</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-3 control-label">Nombre del Cliente</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="nombre" name="nombre" placeholder="" 
                                        ng-model="cliente.nombre" ng-required="true">
                                    <span class="help-inline" 
                                        ng-show="frmClientes.nombre.$invalid && frmClientes.nombre.$touched">El nombre del cliente del cliente es requerido</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-3 control-label">Apellido del CLiente</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="apellido" name="apellido" placeholder="" 
                                        ng-model="cliente.apellido" ng-required="true">
                                    <span class="help-inline" 
                                        ng-show="frmClientes.apellido.$invalid && frmClientes.apellido.$touched">El apellido del cliente del es requerido</span>
                                    </div>
                                </div>

                                <div class="form-group" >
                                    <label for="inputEmail3" class="col-sm-3 control-label">Teléfono del CLiente</label>
                                    <div class="col-sm-9" >
                                        <input type="text" class="form-control" id="telefonoprincipal" name="telefonoprincipal" placeholder="Teléfono del Cliente" 
                                        ng-model="cliente.telefonoprincipal" ng-required="true">
                                    <span class="help-inline" 
                                        ng-show="frmClientes.telefonoprincipal.$invalid && frmClientes.telefonoprincipal.$touched">El teléfono del cliente es requerido</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-3 control-label">Teléfono alternativo del CLiente</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="telefonosecundario" name="telefonosecundario" placeholder="Teléfono Alternativo del Cliente" 
                                        ng-model="cliente.telefonosecundario" ng-required="true">
                                    <span class="help-inline" 
                                        ng-show="frmClientes.telefonosecundario.$invalid && frmClientes.telefonosecundario.$touched">El telélefono alternativo del cliente es requerido</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-3 control-label">Celular del CLiente</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="celudar" name="celudar" placeholder="Celular del Cliente" 
                                        ng-model="cliente.celular" ng-required="true">
                                    <span class="help-inline" 
                                        ng-show="frmClientes.celudar.$invalid && frmClientes.celudar.$touched">El celular de la  es requerido</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-3 control-label">Dirección del CLiente</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="drirecion" name="drirecion" placeholder="Direción del Cliente" 
                                        ng-model="cliente.direccion" ng-required="true">
                                    <span class="help-inline" 
                                        ng-show="frmClientes.drirecion.$invalid && frmClientes.drirecion.$touched">La direción del cliente es requerida</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-3 control-label">Correo del CLiente</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="correo" name="correo" placeholder="Correo del CLiente" 
                                        ng-model="cliente.correo" ng-required="true">
                                    <span class="help-inline" 
                                        ng-show="frmClientes.correo.$invalid && frmClientes.correo.$touched">El correo electrónico es requerido</span>
                                    </div>
                                </div>                          
                                


                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" id="btn-save" ng-click="save(modalstate, documentoidentidad)" ng-disabled="frmClientes.$invalid">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

