<!DOCTYPE html>
<html lang="en-US" ng-app="softver-aqua">
    <head>
        <title>Clientes</title>

        <!-- Load Bootstrap CSS -->
        <link href="<?= asset('css/bootstrap.min.css') ?>" rel="stylesheet">
    </head>
    <body>
        <h2 class="container">Lista de Clientes</h2>
        <div  ng-controller="clientesController" class="container">

            <!-- Table-to-load-the-data Part -->
            <input type="text" ng-pagination-search="clientes" >
            <table class="table" >
                <thead>
                    <tr>
                        <th>Documento de Identidad</th>
                        <th>Fecha</th>
                        <th>Razón Social</th>
                        <th>Teléfono</th>
                        <th>Teléfono Secundario</th>
                        <th>Celular</th>
                        <th>Dirección</th>
                        <th>Correo</th>
                        <th><button id="btn-add" class="btn btn-primary btn-xs" ng-click="toggle('add', 0)">Agregar</button></th>
                    </tr>
                </thead>
                <tbody>
                    <tr ng-pagination="cliente in clientes" ng-pagination-size="2">
                        <td>{{cliente.documentoidentidad}}</td>
                        <td>{{cliente.fechaingreso|date}}</td>
                        <td>{{cliente.apellido+' '+cliente.nombre}}</td>                    
                        <td>{{cliente.telefonoprincipal}}</td>   
                        <td>{{cliente.telefonosecundario}}</td>
                        <td>{{cliente.celular}}</td>
                        <td>{{cliente.direccion}}</td>
                        <td>{{cliente.correo}}</td>
                        <td>
                            <button class="btn btn-default btn-xs btn-detail" ng-click="toggle('edit', cliente.documentoidentidad)">Editar Cliente</button>
                            <button class="btn btn-danger btn-xs btn-delete" ng-click="confirmDelete(cliente.documentoidentidad)">Borrar Cliente</button>
                        </td>
                    </tr>

                </tbody>
                    
            </table>
              <ng-pagination-control pagination-id="clientes"></ng-pagination-control>
            <!-- End of Table-to-load-the-data Part -->
            <!-- Modal (Pop up when detail button clicked) -->
            <div class="modal fade" id="add" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                            <h4 class="modal-title" id="myModalLabel">{{form_title}}</h4>
                        </div>
                        <div class="modal-body">
                            <form name="frmClientes" class="form-horizontal" novalidate="">

                                <div class="form-group error">
                                    <label for="inputEmail3" class="col-sm-3 control-label">Documento de identidad del Cliente</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control has-error" id="documentoidentidad" name="documentoidentidad" placeholder="Cédula" value="{{documentoidentidad}}" 
                                        ng-model="cliente.documentoidentidad" ng-required="true">
                                        <span class="help-inline" 
                                        ng-show="frmClientes.documentoidentidad.$invalid && frmClientes.documentoidentidad.$touched">La cédula del cliente es requerida</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-3 control-label">Fecha de Ingreso</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="fechaingreso" name="fechaingreso" placeholder="Fecha Ingreso" value="{{fechaingreso}}" ng-model="cliente.fechaingreso" ng-required="true">
                                        <span class="help-inline" 
                                        ng-show="frmClientes.fechaingreso.invalid && frmClientes.fechaingreso.touched">La fecha de ingreso del cliente es requerida</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-3 control-label">Nombre del Cliente</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre del Cliente" value="{{nombre}}" 
                                        ng-model="cliente.nombre" ng-required="true">
                                    <span class="help-inline" 
                                        ng-show="frmClientes.nombre.$invalid && frmClientes.nombre.$touched">El nombre del cliente del cliente es requerido</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-3 control-label">Apellido del CLiente</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="apellido" name="apellido" placeholder="Apellido del Cliente" value="{{apellido}}" 
                                        ng-model="cliente.apellido" ng-required="true">
                                    <span class="help-inline" 
                                        ng-show="frmClientes.apellido.$invalid && frmClientes.apellido.$touched">El apellido del cliente del es requerido</span>
                                    </div>
                                </div>

                                <div class="form-group" >
                                    <label for="inputEmail3" class="col-sm-3 control-label">Teléfono del CLiente</label>
                                    <div class="col-sm-9" >
                                        <input type="text" class="form-control" id="telefonoprincipal" name="telefonoprincipal" placeholder="Teléfono del Cliente" value="{{telefonoprincipal}}" 
                                        ng-model="cliente.telefonoprincipal" ng-required="true">
                                    <span class="help-inline" 
                                        ng-show="frmClientes.telefonoprincipal.$invalid && frmClientes.telefonoprincipal.$touched">El teléfono del cliente es requerido</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-3 control-label">Teléfono alternativo del CLiente</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="telefonosecundario" name="telefonosecundario" placeholder="Teléfono Alternativo del Cliente" value="{{telefonosecundario}}" 
                                        ng-model="cliente.telefonosecundario" ng-required="true">
                                    <span class="help-inline" 
                                        ng-show="frmClientes.telefonosecundario.$invalid && frmClientes.telefonosecundario.$touched">El telélefono alternativo del cliente es requerido</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-3 control-label">Celular del CLiente</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="celudar" name="celudar" placeholder="Celular del Cliente" value="{{celudar}}" 
                                        ng-model="cliente.celular" ng-required="true">
                                    <span class="help-inline" 
                                        ng-show="frmClientes.celudar.$invalid && frmClientes.celudar.$touched">El celular de la  es requerido</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-3 control-label">Dirección del CLiente</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="drirecion" name="drirecion" placeholder="Direción del Cliente" value="{{drirecion}}" 
                                        ng-model="cliente.direccion" ng-required="true">
                                    <span class="help-inline" 
                                        ng-show="frmClientes.drirecion.$invalid && frmClientes.drirecion.$touched">La direción del cliente es requerida</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-3 control-label">Correo del CLiente</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="correo" name="correo" placeholder="Correo del CLiente" value="{{correo}}" 
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


            <div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                            <h4 class="modal-title" id="myModalLabel">{{form_title}}</h4>
                        </div>
                        <div class="modal-body">
                            <form name="frmClientes" class="form-horizontal" novalidate="">

                                <div class="form-group error">
                                    <label for="inputEmail3" class="col-sm-3 control-label">Documento de identidad del Cliente</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control has-error" id="documentoidentidad" name="documentoidentidad" placeholder="Cédula" value="{{documentoidentidad}}" 
                                        ng-model="cliente.documentoidentidad" ng-required="true">
                                        <span class="help-inline" 
                                        ng-show="frmClientes.documentoidentidad.$invalid && frmClientes.documentoidentidad.$touched">La cédula del cliente es requerida</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-3 control-label">Fecha de Ingreso</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="fechaingreso" name="fechaingreso" value="{{fechaingreso" ng-model="cliente.fechaingreso" ng-required="true">
                                        <span class="help-inline" 
                                        ng-show="frmClientes.fechaingreso && frmClientes.fechaingreso">La fecha de ingreso del cliente es requerida</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-3 control-label">Nombre del Cliente</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre del Cliente" value="{{nombre}}" 
                                        ng-model="cliente.nombre" ng-required="true">
                                    <span class="help-inline" 
                                        ng-show="frmClientes.nombre.$invalid && frmClientes.nombre.$touched">El nombre del cliente del cliente es requerido</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-3 control-label">Apellido del CLiente</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="apellido" name="apellido" placeholder="Apellido del Cliente" value="{{apellido}}" 
                                        ng-model="cliente.apellido" ng-required="true">
                                    <span class="help-inline" 
                                        ng-show="frmClientes.apellido.$invalid && frmClientes.apellido.$touched">El apellido del cliente del es requerido</span>
                                    </div>
                                </div>

                                <div class="form-group" >
                                    <label for="inputEmail3" class="col-sm-3 control-label">Teléfono del CLiente</label>
                                    <div class="col-sm-9" >
                                        <input type="text" class="form-control" id="telefonoprincipal" name="telefonoprincipal" placeholder="Teléfono del Cliente" value="{{telefonoprincipal}}" 
                                        ng-model="cliente.telefonoprincipal" ng-required="true">
                                    <span class="help-inline" 
                                        ng-show="frmClientes.telefonoprincipal.$invalid && frmClientes.telefonoprincipal.$touched">El teléfono del cliente es requerido</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-3 control-label">Teléfono alternativo del CLiente</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="telefonosecundario" name="telefonosecundario" placeholder="Teléfono Alternativo del Cliente" value="{{telefonosecundario}}" 
                                        ng-model="cliente.telefonosecundario" ng-required="true">
                                    <span class="help-inline" 
                                        ng-show="frmClientes.telefonosecundario.$invalid && frmClientes.telefonosecundario.$touched">El telélefono alternativo del cliente es requerido</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-3 control-label">Celular del CLiente</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="celudar" name="celudar" placeholder="Celular del Cliente" value="{{celudar}}" 
                                        ng-model="cliente.celular" ng-required="true">
                                    <span class="help-inline" 
                                        ng-show="frmClientes.celudar.$invalid && frmClientes.celudar.$touched">El celular de la  es requerido</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-3 control-label">Dirección del CLiente</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="drirecion" name="drirecion" placeholder="Direción del Cliente" value="{{drirecion}}" 
                                        ng-model="cliente.direccion" ng-required="true">
                                    <span class="help-inline" 
                                        ng-show="frmClientes.drirecion.$invalid && frmClientes.drirecion.$touched">La direción del cliente es requerida</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-3 control-label">Correo del CLiente</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="correo" name="correo" placeholder="Correo del CLiente" value="{{correo}}" 
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

        <!-- Load Javascript Libraries (AngularJS, JQuery, Bootstrap) -->
        <script src="<?= asset('app/lib/angular/angular.min.js') ?>"></script>
        <script src="<?= asset('app/lib/angular/angular-pagination.js') ?>"></script>
        <script src="<?= asset('js/jquery.min.js') ?>"></script>
        <script src="<?= asset('js/bootstrap.min.js') ?>"></script>      
        <!-- AngularJS Application Scripts -->
        <script src="<?= asset('app/app.js') ?>"></script>
        <script src="<?= asset('app/controllers/clientesController.js') ?>"></script>
        

    </body>
</html>
