
<div ng-controller="empleadosController">

    <div class="container" style="margin-top: 2%;">

        <div class="col-sm-6 col-xs-8">
            <div class="form-group has-feedback">
                <input type="text" class="form-control" id="busqueda" placeholder="BUSCAR..." ng-model="busqueda">
                <span class="glyphicon glyphicon-search form-control-feedback" aria-hidden="true"></span>
            </div>
        </div>

        <div class="col-sm-6 col-xs-4">
            <button type="button" class="btn btn-primary" id="btnAgregar" style="float: right;" ng-click="toggle('add', 0)">Nuevo  <span class="glyphicon glyphicon-plus" aria-hidden="true"></button>
        </div>

        <div class="col-xs-12">
            <div class="alert alert-info" role="alert" id="message-positions" style="display: none;">
                <span style="font-weight: bold;">INFORMACION: </span>
                Para Administrar Empleados, se necesita crear Cargos primeramente...
            </div>
        </div>

        <div class="col-xs-12">

            <table class="table table-responsive table-striped table-hover table-condensed">
                <thead class="bg-primary">
                <tr>
                    <th>CI/RUC</th>
                    <th>Razon Social</th>
                    <th>Cargo</th>
                    <th>Telefono</th>
                    <th>Cel.</th>
                    <th style="width: 160px;">Acciones</th>
                </tr>
                </thead>
                <tbody>
                <tr dir-paginate="empleado in empleados | orderBy:sortKey:reverse |itemsPerPage:10 |filter:busqueda" ng-cloak >
                    <td>{{empleado.documentoidentidadempleado}}</td>
                    <td>{{empleado.complete_name}}</td>
                    <td>{{empleado.cargo.nombrecargo}}</td>
                    <td>{{empleado.telefonoprincipaldomicilio}}</td>
                    <td>{{empleado.celular}}</td>
                    <td>

                        <button type="button" class="btn btn-info" ng-click="toggle('info', empleado)"
                                data-toggle="tooltip" data-placement="bottom" title="Información">
                            <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                        </button>
                        <button type="button" class="btn btn-warning" ng-click="toggle('edit', empleado)"
                                data-toggle="tooltip" data-placement="bottom" title="Editar" >
                            <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                        </button>
                        <button type="button" class="btn btn-danger" ng-click="showModalConfirm(empleado)"
                                data-toggle="tooltip" data-placement="bottom" title="Eliminar">
                            <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
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
                                        <label class="col-sm-4 control-label">CI/RUC:</label>
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
                                                   ng-model="apellido" ng-required="true" ng-maxlength="128" ng-pattern="/^([a-zA-ZáéíóúñÑ ])+$/" >
                                            <span class="help-block error"
                                                  ng-show="formEmployee.apellido.$invalid && formEmployee.apellido.$touched">El Apellido es requerido</span>
                                            <span class="help-block error"
                                                  ng-show="formEmployee.apellido.$invalid && formEmployee.apellido.$error.maxlength">La longitud máxima es de 128 caracteres</span>
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
                                                   ng-model="nombre" ng-required="true" ng-maxlength="128" ng-pattern="/^([a-zA-ZáéíóúñÑ ])+$/" >
                                            <span class="help-block error"
                                                  ng-show="formEmployee.nombre.$invalid && formEmployee.nombre.$touched">El Nombre es requerido</span>
                                            <span class="help-block error"
                                                  ng-show="formEmployee.nombre.$invalid && formEmployee.nombre.$error.maxlength">La longitud máxima es de 128 caracteres</span>
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
                                                   ng-model="telefonoprincipal" ng-maxlength="16" ng-pattern="/^([0-9-\(\)]+)$/" >
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
                                                   ng-model="telefonosecundario" ng-maxlength="16" ng-pattern="/^([0-9-\(\)]+)$/" >
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
                                                   ng-model="celular" ng-maxlength="16" ng-pattern="/^([0-9-\(\)]+)$/">
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
                                                   ng-model="direccion" ng-maxlength="256">
                                            <span class="help-block error"
                                                  ng-show="formEmployee.direccion.$invalid && formEmployee.direccion.$error.maxlength">La longitud máxima es de 256 caracteres</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12">
                                <div class="col-md-6 col-xs-12">
                                    <div class="form-group">
                                        <label for="correo" class="col-sm-4 control-label">E-mail:</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" name="correo" id="correopersona" ng-model="correo" ng-pattern="/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/" placeholder="" >
                                            <span class="help-block error"
                                                  ng-show="formEmployee.correo.$invalid && formEmployee.correo.$error.pattern">Formato de email no es correcto</span>

                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xs-12">

                                    <label for="foto" class="col-sm-4 control-label">Foto del Empleado:</label>
                                    <div class="col-sm-8">
                                        <input class="form-control" type="file" ngf-select ng-model="file" name="file" id="file"
                                               accept="image/*" ngf-max-size="2MB"  ng-required="false" ngf-pattern="image/*">
                                        <span class="help-block error"
                                              ng-show="formEmployee.file.$error.required">La Foto del Empleado es requerida</span>
                                        <span class="help-block error"
                                              ng-show="formEmployee.file.$error.pattern">El archivo debe ser Imagen</span>
                                        <span class="help-block error"
                                              ng-show="formEmployee.file.$error.maxSize">El tamaño máximo es de 2 MB </span>
                                    </div>

                                </div>
                            </div>
                            <div class="col-md-6 col-xs-12">
                                <label for="salario" class="col-sm-4 control-label">Salario:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="salario" id="salario" ng-model="salario" placeholder="" ng-maxlength="12" ng-pattern="/^([0-9]{1,9}\.[0-9]{2})$/">
                                    <span class="help-block error"
                                          ng-show="formEmployee.salario.$invalid && formEmployee.salario.$error.maxlength">La longitud máxima es de 12 caracteres</span>
                                    <span class="help-block error"
                                          ng-show="formEmployee.salario.$invalid && formEmployee.salario.$error.pattern">El Salario debe ser solo números y punto</span>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        Cancelar <span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span>
                    </button>
                    <button type="button" class="btn btn-success" id="btn-save" ng-click="save(modalstate)" ng-disabled="formEmployee.$invalid">
                        Guardar <span class="glyphicon glyphicon-floppy-saved" aria-hidden="true"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" id="modalMessageError" style="z-index: 99999;">
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
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        Cancelar <span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span>
                    </button>
                    <button type="button" class="btn btn-danger" id="btn-save" ng-click="destroy()">Eliminar</button>
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
                        <img class="img-thumbnail" src="{{url_foto}}" alt="">
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
                        <div class="col-xs-12">
                            <span style="font-weight: bold">Salario: </span>{{salario_employee}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>














