
<!doctype html>
<html lang="es-ES" ng-app="softver-aqua">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <link href="<?= asset('css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= asset('css/font-awesome.min.css') ?>" rel="stylesheet">
    <link href="<?= asset('css/index.css') ?>" rel="stylesheet">
    <link href="<?= asset('css/style_generic_app.css') ?>" rel="stylesheet">

</head>

<body>
<div ng-controller="empleadosController">

    <div class="container" style="margin-top: 2%;">

        <div class="col-sm-6 col-xs-8">
            <div class="form-group has-feedback">
                <input type="text" class="form-control" id="busqueda" placeholder="BUSCAR..." ng-model="busqueda" ng-keypress="initLoad(1)">
                <span class="glyphicon glyphicon-search form-control-feedback" aria-hidden="true"></span>
            </div>
        </div>

        <div class="col-sm-6 col-xs-4">
            <button type="button" class="btn btn-primary" id="btnAgregar" style="float: right;" ng-click="toggle('add', 0)">Agregar  <span class="glyphicon glyphicon-plus" aria-hidden="true"></button>
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
                    <th>RUC / CI</th>
                    <th>Nombre y Apellidos</th>
                    <th>Cargo</th>
                    <th>Teléfono</th>
                    <th>Celular</th>
                    <th style="width: 160px;">Acciones</th>
                </tr>
                </thead>
                <tbody>
                <tr dir-paginate="empleado in empleados | orderBy:sortKey:reverse | itemsPerPage:10" total-items="totalItems" ng-cloak >
                    <td>{{empleado.numdocidentific}}</td>
                    <td>{{empleado.razonsocial}}</td>
                    <td>{{empleado.namecargo}}</td>
                    <td>{{empleado.telefprincipaldomicilio}}</td>
                    <td>{{empleado.celphone}}</td>
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

                    on-page-change="pageChanged(newPageNumber)"

                    template-url="dirPagination.html"

                    class="pull-right"
                    max-size="10"
                    direction-links="true"
                    boundary-links="true" >

            </dir-pagination-controls>

        </div>

    </div>

    <div class="modal fade" tabindex="-1" role="dialog" id="modalAction">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form class="form-horizontal" name="formEmployee" novalidate="">
                <div class="modal-header modal-header-primary">
                    <div class="col-md-6 col-xs-12">
                        <h4 class="modal-title">{{form_title}}</h4>
                    </div>
                    <div class="col-md-5 col-xs-12">
                        <div class="input-group">
                            <span class="input-group-addon">Fecha de Ingreso:</span>
                            <input type="text" class="datepicker form-control" name="fechaingreso" id="fechaingreso" ng-model="fechaingreso" ng-required="true">
                        </div>
                        <span class="help-block error"
                              ng-show="formEmployee.fechaingreso.$invalid && formEmployee.fechaingreso.$touched">La Fecha de Ingreso es requerida</span>
                    </div>
                    <div class="col-md-1 col-xs-12 text-right" style="padding: 0;">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                </div>
                <div class="modal-body">

                    <div class="row">


                            <div class="col-xs-12">
                                <div class="col-md-6 col-xs-12">
                                    <div class="input-group">
                                        <span class="input-group-addon">Departamento: </span>
                                        <select class="form-control" name="departamento" id="departamento" ng-model="departamento"
                                                ng-options="value.id as value.label for value in iddepartamentos" required></select>
                                    </div>
                                    <span class="help-block error"
                                          ng-show="formEmployee.departamento.$invalid && formEmployee.departamento.$touched">El Departamento es requerido</span>
                                    <!--<span class="help-block error"
                                          ng-show="formEmployee.departamento.$invalid && formEmployee.departamento.$error.pattern">Seleccione un Departamento</span>-->
                                </div>

                                <div class="col-md-6 col-xs-12">
                                    <div class="input-group">
                                        <span class="input-group-addon">Cargo: </span>
                                        <select class="form-control" name="idcargo" id="idcargo" ng-model="idcargo"
                                                ng-options="value.id as value.label for value in idcargos" required></select>
                                    </div>
                                    <span class="help-block error"
                                          ng-show="formEmployee.idcargo.$invalid && formEmployee.idcargo.$touched">El Cargo es requerido</span>

                                </div>
                            </div>

                            <div class="col-xs-12" style="margin-top: 5px;">

                                <div class="col-md-6 col-xs-12">
                                    <div class="input-group">
                                        <span class="input-group-addon">Tipo Identificación: </span>
                                        <select class="form-control" name="tipoidentificacion" id="tipoidentificacion" ng-model="tipoidentificacion"
                                                ng-options="value.id as value.label for value in idtipoidentificacion" required></select>
                                    </div>
                                    <span class="help-block error"
                                          ng-show="formEmployee.tipoidentificacion.$invalid && formEmployee.tipoidentificacion.$touched">El Tipo de Identificación es requerido</span>
                                </div>


                                <div class="col-md-6 col-xs-12">
                                    <div class="input-group">
                                        <span class="input-group-addon">RUC / CI:</span>
                                        <!--<input type="text" class="form-control" name="documentoidentidadempleado" id="documentoidentidadempleado"
                                               ng-model="documentoidentidadempleado" ng-required="true" ng-maxlength="13" > -->

                                        <angucomplete-alt
                                                id = "documentoidentidadempleado"
                                                pause = "200"
                                                selected-object = "showDataPurchase"

                                                input-changed="inputChanged"

                                                remote-url = "{{API_URL}}empleado/getIdentify/"

                                                focus-out="focusOut()"


                                                title-field="numdocidentific"

                                                minlength="1"
                                                input-class="form-control form-control-small small-input"
                                                match-class="highlight"
                                                field-required="true"
                                                input-name="documentoidentidadempleado"
                                                disable-input="guardado"
                                                text-searching="Buscando Identificaciones Personas"
                                                text-no-results="Persona no encontrada"

                                        > </angucomplete-alt>

                                    </div>
                                    <span class="help-block error"
                                          ng-show="formEmployee.documentoidentidadempleado.$invalid && formEmployee.documentoidentidadempleado.$touched">La Identificación es requerido</span>
                                    <span class="help-block error"
                                          ng-show="formEmployee.documentoidentidadempleado.$invalid && formEmployee.documentoidentidadempleado.$error.maxlength">La longitud máxima es de 13 caracteres</span>
                                </div>

                            </div>

                            <div class="col-xs-12" style="margin-top: 5px;">
                                <div class="col-md-6 col-xs-12">
                                    <div class="input-group">
                                        <span class="input-group-addon">Apellidos: </span>
                                        <input type="text" class="form-control" name="apellido" id="apellido"
                                               ng-model="apellido" ng-required="true" ng-maxlength="128" ng-pattern="/^([a-zA-ZáéíóúñÑ ])+$/" >
                                    </div>
                                    <span class="help-block error"
                                          ng-show="formEmployee.apellido.$invalid && formEmployee.apellido.$touched">El Apellido es requerido</span>
                                    <span class="help-block error"
                                          ng-show="formEmployee.apellido.$invalid && formEmployee.apellido.$error.maxlength">La longitud máxima es de 128 caracteres</span>
                                    <span class="help-block error"
                                          ng-show="formEmployee.apellido.$invalid && formEmployee.apellido.$error.pattern">El Apellido debe ser solo letras y espacios</span>
                                </div>

                                <div class="col-md-6 col-xs-12">
                                    <div class="input-group">
                                        <span class="input-group-addon">Nombre(s): </span>
                                        <input type="text" class="form-control" name="nombre" id="nombre"
                                               ng-model="nombre" ng-required="true" ng-maxlength="128" ng-pattern="/^([a-zA-ZáéíóúñÑ ])+$/" >
                                    </div>
                                    <span class="help-block error"
                                          ng-show="formEmployee.nombre.$invalid && formEmployee.nombre.$touched">El Nombre es requerido</span>
                                    <span class="help-block error"
                                          ng-show="formEmployee.nombre.$invalid && formEmployee.nombre.$error.maxlength">La longitud máxima es de 128 caracteres</span>
                                    <span class="help-block error"
                                          ng-show="formEmployee.nombre.$invalid && formEmployee.nombre.$error.pattern">El Nombre debe ser solo letras y espacios</span>
                                </div>
                            </div>

                            <div class="col-xs-12" style="margin-top: 5px;">
                                <div class="col-md-6 col-xs-12">
                                    <div class="input-group">
                                        <span class="input-group-addon">Teléfono Principal: </span>
                                        <input type="text" class="form-control" name="telefonoprincipal" id="telefonoprincipal"
                                               ng-model="telefonoprincipal" ng-minlength="9" ng-maxlength="16" ng-pattern="/^([0-9-\(\)]+)$/" >
                                    </div>
                                    <span class="help-block error"
                                          ng-show="formEmployee.telefonoprincipal.$invalid && formEmployee.telefonoprincipal.$error.maxlength">La longitud máxima es de 16 números</span>
                                    <span class="help-block error"
                                          ng-show="formEmployee.telefonoprincipal.$invalid && formEmployee.telefonoprincipal.$error.pattern">El Teléfono debe ser solo números, guion y espacios</span>
                                    <span class="help-block error"
                                          ng-show="formEmployee.telefonoprincipal.$invalid && formEmployee.telefonoprincipal.$error.minlength">La longitud mínima es de 9 caracteres</span>
                                </div>

                                <div class="col-md-6 col-xs-12">
                                    <div class="input-group">
                                        <span class="input-group-addon">Teléfono Secundario: </span>
                                        <input type="text" class="form-control" name="telefonosecundario" id="telefonosecundario"
                                               ng-model="telefonosecundario" ng-minlength="9"  ng-maxlength="16" ng-pattern="/^([0-9-\(\)]+)$/" >
                                    </div>
                                    <span class="help-block error"
                                          ng-show="formEmployee.telefonosecundario.$invalid && formEmployee.telefonosecundario.$error.maxlength">La longitud máxima es de 16 números</span>
                                    <span class="help-block error"
                                          ng-show="formEmployee.telefonosecundario.$invalid && formEmployee.telefonosecundario.$error.pattern">El Teléfono debe ser solo números, guion y espacios</span>
                                    <span class="help-block error"
                                          ng-show="formEmployee.telefonosecundario.$invalid && formEmployee.telefonosecundario.$error.minlength">La longitud mínima es de 9 caracteres</span>
                                </div>
                            </div>

                            <div class="col-xs-12" style="margin-top: 5px;">
                                <div class="col-md-6 col-xs-12">
                                    <div class="input-group">
                                        <span class="input-group-addon">Celular: </span>
                                        <input type="text" class="form-control" name="celular" id="celular"
                                               ng-model="celular" ng-minlength="10" ng-maxlength="16" ng-pattern="/^([0-9-\(\)]+)$/">
                                    </div>
                                    <span class="help-block error"
                                          ng-show="formEmployee.celular.$invalid && formEmployee.celular.$error.maxlength">La longitud máxima es de 16 números</span>
                                    <span class="help-block error"
                                          ng-show="formEmployee.celular.$invalid && formEmployee.celular.$error.pattern">El Teléfono debe ser solo números, guion y espacios</span>
                                    <span class="help-block error"
                                          ng-show="formEmployee.celular.$invalid && formEmployee.celular.$error.minlength">La longitud mínima es de 10 caracteres</span>
                                </div>

                                <div class="col-md-6 col-xs-12">
                                    <div class="input-group">
                                        <span class="input-group-addon">E-mail: </span>
                                        <input type="text" class="form-control" name="correo" id="correopersona" ng-model="correo" ng-pattern="/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/" placeholder="" >
                                    </div>
                                    <span class="help-block error"
                                          ng-show="formEmployee.correo.$invalid && formEmployee.correo.$error.pattern">Formato de email no es correcto</span>
                                </div>
                            </div>

                            <div class="col-xs-12" style="margin-top: 5px;">
                                <div class="col-xs-12">
                                    <div class="input-group">
                                        <span class="input-group-addon">Dirección: </span>
                                        <input type="text" class="form-control" name="direccion" id="direccion" ng-model="direccion" ng-maxlength="256">
                                    </div>
                                    <span class="help-block error"
                                          ng-show="formEmployee.direccion.$invalid && formEmployee.direccion.$error.maxlength">La longitud máxima es de 256 caracteres</span>
                                </div>
                            </div>

                            <div class="col-xs-6" style="margin-top: 5px;">
                                <div class="col-xs-12">
                                    <div class="input-group">
                                        <span class="input-group-addon">Foto: </span>
                                        <input class="form-control" type="file" ngf-select ng-model="file" name="file" id="file"
                                               accept="image/*" ngf-max-size="2MB"  ng-required="false" ngf-pattern="image/*">
                                    </div>
                                    <span class="help-block error"
                                          ng-show="formEmployee.file.$error.required">La Foto del Empleado es requerida</span>
                                    <span class="help-block error"
                                          ng-show="formEmployee.file.$error.pattern">El archivo debe ser Imagen</span>
                                    <span class="help-block error"
                                          ng-show="formEmployee.file.$error.maxSize">El tamaño máximo es de 2 MB </span>

                                </div>

                                <div class="col-xs-12" style="margin-top: 5px;">
                                    <div class="input-group">
                                        <span class="input-group-addon">Salario: </span>
                                        <input type="text" class="form-control" name="salario" id="salario" ng-model="salario" placeholder="" ng-maxlength="12"
                                               ng-pattern="/^([0-9]{1,9}\.[0-9]{2})$/">
                                    </div>
                                    <span class="help-block error"
                                          ng-show="formEmployee.salario.$invalid && formEmployee.salario.$error.maxlength">La longitud máxima es de 12 caracteres</span>
                                    <span class="help-block error"
                                          ng-show="formEmployee.salario.$invalid && formEmployee.salario.$error.pattern">El Salario debe ser solo números y punto</span>
                                </div>

                                <div class="col-xs-12" style="margin-top: 5px;">
                                    <div class="input-group">
                                        <span class="input-group-addon">C. Contab.: </span>
                                        <input type="text" class="form-control" name="cuenta_employee" id="cuenta_employee" ng-model="cuenta_employee" placeholder=""
                                               ng-required="true" readonly>
                                        <span class="input-group-btn" role="group">
                                            <button type="button" class="btn btn-info" id="btn-pcc" ng-click="showPlanCuenta()">
                                                <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                            </button>
                                        </span>

                                    </div>
                                    <span class="help-block error"
                                          ng-show="formEmployee.cuenta_employee.$error.required">La asignación de una cuenta es requerida</span>
                                </div>
                            </div>



                            <div class="col-xs-6 text-center" style="margin-top: 5px;">
                                <img class="img-thumbnail" ngf-src="file" src="{{url_foto}}" alt="" style="width: 50%;">
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
                    <button type="button" class="btn btn-danger" id="btn-save" ng-click="destroy()">
                        Eliminar<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
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
                            <table class="table table-responsive table-striped table-hover table-condensed table-bordered">
                                <thead class="bg-primary">
                                <tr>
                                    <th style="width: 15%;">ORDEN</th>
                                    <th>CONCEPTO</th>
                                    <th style="width: 10%;">COD. SRI</th>
                                    <th style="width: 4%;"></th>
                                </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="item in cuentas" ng-cloak >
                                        <td>{{item.jerarquia}}</td>
                                        <td>{{item.concepto}}</td>
                                        <td>{{item.codigosri}}</td>
                                        <td>
                                            <input type="radio" name="select_cuenta"  ng-click="click_radio(item)">
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

</body>


<script src="<?= asset('app/lib/angular/angular.min.js') ?>"></script>
<script src="<?= asset('app/lib/angular/angular-route.min.js') ?>"></script>


<script src="<?= asset('app/lib/angular/ng-file-upload-shim.min.js') ?>"></script>
<script src="<?= asset('app/lib/angular/ng-file-upload.min.js') ?>"></script>


<script src="<?= asset('js/jquery.min.js') ?>"></script>
<script src="<?= asset('js/bootstrap.min.js') ?>"></script>

<script src="<?= asset('app/app.js') ?>"></script>
<script src="<?= asset('app/controllers/empleadosController.js') ?>"></script>


</html>












