<!doctype html>
<html lang="es-ES" ng-app="softver-aqua">
    <head>
        <meta charset="UTF-8">

        <title>Aqua - Empleados</title>

        <link href="<?= asset('css/bootstrap.min.css') ?>" rel="stylesheet">
        <link href="<?= asset('css/style_generic_app.css') ?>" rel="stylesheet">

    </head>
    <body ng-controller="cargosController">

        <div class="container" style="margin-top: 2%;">
            <fieldset>
                <legend style="padding-bottom: 10px;">
                    <span style="font-weight: bold;">ADMINISTRACION DE EMPLEADOS</span>
                    <button type="button" id="btnAgregar" class="btn btn-primary" style="float: right;" ng-click="toggle('add', 0)">Agregar</button>
                </legend>

                <div class="col-xs-12">
                    <div class="alert alert-info" role="alert" id="message-positions" style="display: none;">
                        <span style="font-weight: bold;">INFORMACION: </span>
                        Para Administrar Empleados, se necesita crear Cargos primeramente...
                    </div>
                </div>

                <div class="col-xs-6">
                    <div class="form-group has-feedback">
                        <input type="text" class="form-control input-sm" id="search-list-trans" placeholder="BUSCAR..." ng-pagination-search="empleados">
                        <span class="glyphicon glyphicon-search form-control-feedback" aria-hidden="true"></span>
                    </div>
                </div>

                <div class="col-xs-12">

                    <table class="table table-responsive table-striped table-hover table-condensed">
                        <thead class="bg-primary">
                            <tr>
                                <th>Doc ID</th>
                                <th>Razon Social</th>
                                <th>Cargo</th>
                                <th>Telefono</th>
                                <th>Cel.</th>
                                <th style="width: 20%;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-pagination="empleado in empleados" ng-pagination-size="10">
                                <td>{{empleado.documentoidentidadempleado}}</td>
                                <td>{{empleado.apellido + ' ' + empleado.nombre}}</td>
                                <td>{{empleado.nombrecargo}}</td>
                                <td>{{empleado.telefonoprincipal}}</td>
                                <td>{{empleado.celular}}</td>
                                <td>
                                    <a href="#" class="btn btn-warning" ng-click="toggle('edit', empleado.documentoidentidadempleado)">Editar</a>
                                    <a href="#" class="btn btn-danger" ng-click="showModalConfirm(empleado.documentoidentidadempleado)">Eliminar</a>
                                    <a href="#" class="btn btn-info" ng-click="toggle('info', empleado.documentoidentidadempleado)">Ver</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <ng-pagination-control pagination-id="empleados"></ng-pagination-control>
                </div>


            </fieldset>
        </div>

        <div class="modal fade" tabindex="-1" role="dialog" id="modalAction">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header modal-header-primary">
                        <div class="col-md-6 col-xs-12">
                            <h4 class="modal-title">{{form_title}}</h4>
                        </div>
                        <div class="col-md-6 col-xs-12">
                            <div class="form-group">
                                <label for="fechaingreso" class="col-sm-5 control-label">Fecha de Ingreso:</label>
                                <div class="col-sm-6" style="padding: 0;">
                                    <input type="text" class="form-control" name="fechaingreso" id="fechaingreso" ng-model="fechaingreso" placeholder="" disabled>
                                </div>
                                <div class="col-sm-1 col-xs-12 text-right" style="padding: 0;">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-body">

                        <div class="row">
                            <form class="form-horizontal" name="formEmployee" novalidate="">

                                <div class="col-xs-12">
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group error">
                                            <label class="col-sm-4 control-label">Doc. de Identidad:</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" name="documentoidentidadempleado" id="documentoidentidadempleado"
                                                       ng-model="documentoidentidadempleado" ng-required="true" ng-maxlength="32" ng-pattern="/[0-9]+/" >
                                                <span class="help-block error"
                                                      ng-show="formEmployee.documentoidentidadempleado.$invalid && formEmployee.documentoidentidadempleado.$touched">La Identificación es requerido</span>
                                                <span class="help-block error"
                                                      ng-show="formEmployee.documentoidentidadempleado.$invalid && formEmployee.documentoidentidadempleado.$error.maxlength">La longitud máxima es de 32 caracteres</span>
                                                <span class="help-block error"
                                                      ng-show="formEmployee.documentoidentidadempleado.$invalid && formEmployee.documentoidentidadempleado.$error.pattern">La Identificación debe ser solo números</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Cargo:</label>
                                            <div class="col-sm-8">
                                                <select class="form-control" name="idcargo" id="idcargo" ng-model="idcargo"
                                                        ng-options="value.id as value.label for value in idcargos" ng-required="true"></select>
                                                <span class="help-block error"
                                                      ng-show="formEmployee.idcargo.$invalid && formEmployee.idcargo.$touched">El Cargo es requerido</span>
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
                                                       ng-model="apellido" ng-required="true" ng-maxlength="32" ng-pattern="/[a-zA-ZáéíóúñÑ ]+/" >
                                                <span class="help-block error"
                                                      ng-show="formEmployee.apellido.$invalid && formEmployee.apellido.$touched">El Apellido es requerido</span>
                                                <span class="help-block error"
                                                      ng-show="formEmployee.apellido.$invalid && formEmployee.apellido.$error.maxlength">La longitud máxima es de 32 caracteres</span>
                                                <span class="help-block error"
                                                      ng-show="formEmployee.apellido.$invalid && formEmployee.apellido.$error.pattern">El Apellido debe ser solo letras y espacios</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group error">
                                            <label class="col-sm-4 control-label">Nombres:</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" name="nombre" id="nombre"
                                                       ng-model="nombre" ng-required="true" ng-maxlength="32" ng-pattern="/[a-zA-ZáéíóúñÑ ]+/" >
                                                <span class="help-block error"
                                                      ng-show="formEmployee.nombre.$invalid && formEmployee.nombre.$touched">El Nombre es requerido</span>
                                                <span class="help-block error"
                                                      ng-show="formEmployee.nombre.$invalid && formEmployee.nombre.$error.maxlength">La longitud máxima es de 32 caracteres</span>
                                                <span class="help-block error"
                                                      ng-show="formEmployee.nombre.$invalid && formEmployee.nombre.$error.pattern">El Nombre debe ser solo letras y espacios</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12">
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group error">
                                            <label class="col-sm-4 control-label">Teléfono Principal:</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" name="telefonoprincipal" id="telefonoprincipal"
                                                       ng-model="telefonoprincipal" ng-maxlength="16" ng-pattern="/[0-9- ]+/" >
                                                <span class="help-block error"
                                                      ng-show="formEmployee.telefonoprincipal.$invalid && formEmployee.telefonoprincipal.$error.maxlength">La longitud máxima es de 16 números</span>
                                                <span class="help-block error"
                                                      ng-show="formEmployee.telefonoprincipal.$invalid && formEmployee.telefonoprincipal.$error.pattern">El Teléfono debe ser solo números, guion y espacios</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group error">
                                            <label class="col-sm-4 control-label">Teléfono Secundario:</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" name="telefonosecundario" id="telefonosecundario"
                                                       ng-model="telefonosecundario" ng-maxlength="16" ng-pattern="/[0-9- ]+/" >
                                                <span class="help-block error"
                                                      ng-show="formEmployee.telefonosecundario.$invalid && formEmployee.telefonosecundario.$error.maxlength">La longitud máxima es de 16 números</span>
                                                <span class="help-block error"
                                                      ng-show="formEmployee.telefonosecundario.$invalid && formEmployee.telefonosecundario.$error.pattern">El Teléfono debe ser solo números, guion y espacios</span>
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
                                                       ng-model="celular" ng-maxlength="16" ng-pattern="/[0-9- ]+/" >
                                                <span class="help-block error"
                                                      ng-show="formEmployee.celular.$invalid && formEmployee.celular.$error.maxlength">La longitud máxima es de 16 números</span>
                                                <span class="help-block error"
                                                      ng-show="formEmployee.celular.$invalid && formEmployee.celular.$error.pattern">El Teléfono debe ser solo números, guion y espacios</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group error">
                                            <label class="col-sm-4 control-label">Dirección:</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" name="direccion" id="direccion"
                                                       ng-model="direccion" ng-maxlength="16" ng-pattern="/[a-zA-ZáéíóúñÑ0-9. ]+/">
                                                <span class="help-block error"
                                                      ng-show="formEmployee.direccion.$invalid && formEmployee.direccion.$error.maxlength">La longitud máxima es de 32 caracteres</span>
                                                <span class="help-block error"
                                                      ng-show="formEmployee.direccion.$invalid && formEmployee.direccion.$error.pattern">La Dirección debe ser solo letras, puntos, números, guion y espacios</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12">
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="correo" class="col-sm-4 control-label">E-mail:</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" name="correo" id="correopersona" ng-model="correo" placeholder="" >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="foto" class="col-sm-4 control-label">Foto:</label>
                                            <div class="col-sm-8">
                                                <input type="file" class="form-control" name="foto" id="foto" ng-model="foto" placeholder="" >
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" id="btn-save" ng-click="save(modalstate, id)" ng-disabled="formEmployee.$invalid">Guardar</button>
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
                        <span>Realmente desea eliminar el Empleado: <span style="font-weight: bold;">{{empleado_seleccionado}}</span></span>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" id="btn-save" ng-click="destroyCargo()">Eliminar</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" tabindex="-1" role="dialog" id="modalInfoEmpleado">
            <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content">
                    <div class="modal-header modal-header-info">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Información del Empleado</h4>
                    </div>
                    <div class="modal-body">
                        <div class="col-xs-12 text-center">
                            <img class="img-thumbnail" src="<?= asset('img/user_anonymous.jpg') ?>" alt="">
                        </div>
                        <div class="row text-center">
                            <div class="col-xs-12 text-center" style="font-size: 18px;">{{name_employee}}</div>
                            <div class="col-xs-12 text-center" style="font-size: 16px;">{{cargo_employee}}</div>
                            <div class="col-xs-12">
                                <span style="font-weight: bold">Empleado desde: </span>{{date_registry_employee}}
                            </div>
                            <div class="col-xs-12">
                                <span style="font-weight: bold">Teléfonos: </span>{{phones_employee}}
                            </div>
                            <div class="col-xs-12">
                                <span style="font-weight: bold">Celular: </span>{{cel_employee}}
                            </div>
                            <div class="col-xs-12">
                                <span style="font-weight: bold">Dirección: </span>{{address_employee}}
                            </div>
                            <div class="col-xs-12">
                                <span style="font-weight: bold">Email: </span>{{email_employee}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </body>

    <script src="<?= asset('app/lib/angular/angular.min.js') ?>"></script>
    <script src="<?= asset('app/lib/angular/angular-pagination.js') ?>"></script>
    <script src="<?= asset('js/jquery.min.js') ?>"></script>
    <script src="<?= asset('js/bootstrap.min.js') ?>"></script>

    <!-- AngularJS Application Scripts -->
    <script src="<?= asset('app/app.js') ?>"></script>
    <script src="<?= asset('app/controllers/empleadosController.js') ?>"></script>
</html>