<!doctype html>
<html lang="es-ES" ng-app="softver-aqua">
    <head>
        <meta charset="UTF-8">

        <title>Aqua - Empleados</title>

        <link href="<?= asset('css/bootstrap.min.css') ?>" rel="stylesheet">
        <link href="<?= asset('css/style_generic_app.css') ?>" rel="stylesheet">

    </head>
    <body ng-controller="cargosController">

        <div class="container" style="margin-top: 5%;">
            <fieldset>
                <legend style="padding-bottom: 10px;">
                    <span style="font-weight: bold;">ADMINISTRACION DE EMPLEADOS</span>
                    <button type="button" class="btn btn-primary" style="float: right;" ng-click="toggle('add', 0)">Agregar</button>
                </legend>

                <div class="col-xs-12">

                    <table class="table table-responsive table-striped table-hover table-condensed">
                        <thead class="bg-primary">
                            <tr>
                                <th>Doc ID</th>
                                <th>Razon Social</th>
                                <th>Cargo</th>
                                <th>Telefono</th>
                                <th>Cel.</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="empleado in empleados">
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
                            <form action="" class="form-horizontal">

                                <div class="col-xs-12">
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="documentoidentidadempleado" class="col-sm-4 control-label">Documento de Identidad:</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" name="documentoidentidadempleado" id="documentoidentidadempleado" ng-model="documentoidentidadempleado" placeholder="" >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="idcargo" class="col-sm-4 control-label">Cargo:</label>
                                            <div class="col-sm-8">
                                                <select class="form-control" name="idcargo" id="idcargo" ng-model="idcargo" ng-options="value.id as value.label for value in idcargos">
                                                    <option value="0">- Seleccione --</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12">
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="apellido" class="col-sm-4 control-label">Apellidos:</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" name="apellido" id="apellido" ng-model="apellido" placeholder="" >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="nombre" class="col-sm-4 control-label">Nombres:</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" name="nombre" id="nombre" ng-model="nombre" placeholder="" >
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12">
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="telefonoprincipal" class="col-sm-4 control-label">Telefono Principal:</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" name="telefonoprincipal" id="telefonoprincipal" ng-model="telefonoprincipal" placeholder="" >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="telefonosecundario" class="col-sm-4 control-label">Telefono Secundario:</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" name="telefonosecundario" id="telefonosecundario" ng-model="telefonosecundario" placeholder="" >
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12">
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="celular" class="col-sm-4 control-label">Celular:</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" name="celular" id="celular" ng-model="celular" placeholder="" >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="direccion" class="col-sm-4 control-label">Direccion:</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" name="direccion" id="direccion" ng-model="direccion" placeholder="" >
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
                        <button type="button" class="btn btn-primary" id="btn-save" ng-click="save(modalstate, id)">Guardar</button>
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
                        <div class="row text-center">
                            <div class="col-xs-12 text-center" style="font-size: 18px;">{{name_employee}}</div>
                            <div class="col-xs-12 text-center" style="font-size: 16px;">{{cargo_employee}}</div>
                            <div class="col-xs-12">
                                <span style="font-weight: bold">Empleado desde: </span>{{date_registry_employee}}
                            </div>
                            <div class="col-xs-12">
                                <span style="font-weight: bold">Telefonos: </span>{{phones_employee}}
                            </div>
                            <div class="col-xs-12">
                                <span style="font-weight: bold">Celular: </span>{{cel_employee}}
                            </div>
                            <div class="col-xs-12">
                                <span style="font-weight: bold">Direccion: </span>{{address_employee}}
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
    <script src="<?= asset('js/jquery.min.js') ?>"></script>
    <script src="<?= asset('js/bootstrap.min.js') ?>"></script>

    <!-- AngularJS Application Scripts -->
    <script src="<?= asset('app/app.js') ?>"></script>
    <script src="<?= asset('app/controllers/empleadosController.js') ?>"></script>
</html>