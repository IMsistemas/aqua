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

    <style>
        td{
            vertical-align: middle !important;
        }
    </style>

</head>
<body>

<div ng-controller="clientesController">

    <div class="col-xs-12" style="margin-top: 15px;">
        <div class="col-sm-6 col-xs-12">
            <div class="form-group has-feedback">
                <input type="text" class="form-control" id="t_busqueda" placeholder="BUSCAR..." ng-model="t_busqueda">
                <span class="glyphicon glyphicon-search form-control-feedback" aria-hidden="true"></span>
            </div>
        </div>
        <div class="col-sm-6 col-xs-12">
            <button type="button" class="btn btn-primary" style="float: right;" ng-click="showModalAddCliente()">
                Nuevo <i class="fa fa-lg fa-user-plus" aria-hidden="true"></i>
            </button>
        </div>
    </div>

    <div class="col-xs-12">
        <table class="table table-responsive table-striped table-hover table-condensed">
            <thead class="bg-primary">
            <tr>
                <th class="text-center" style="width: 10%;">CI / RUC</th>
                <th class="text-center" style="width: 10%;">Fecha Ingreso</th>
                <th class="text-center" style="">Razón Social</th>
                <th class="text-center" style="width: 8%;">Celular</th>
                <th class="text-center" style="width: 10%;">Telf. Domicilio</th>
                <th class="text-center" style="width: 9%;">Telf. Trabajo</th>
                <th class="text-center" style="width: 10%;">Dirección</th>
                <th class="text-center" style="width: 7%;">Estado</th>
                <th class="text-center" style="width: 16%;">Acciones</th>
            </tr>
            </thead>
            <tbody>
            <tr ng-repeat="item in clientes | filter : t_busqueda" ng-cloak>
                <td>{{item.documentoidentidad}}</td>
                <td>{{item.fechaingreso | formatDate}}</td>
                <td style="font-weight: bold;"><i class="fa fa-user fa-lg" aria-hidden="true"></i> {{item.apellidos + ' ' + item.nombres}}</td>
                <td>{{item.celular}}</td>
                <td>{{item.telefonoprincipaldomicilio}}</td>
                <td>{{item.telefonoprincipaltrabajo}}</td>
                <td>{{item.direcciondomicilio}}</td>
                <td ng-if="item.estaactivo == true">
                    <span class="label label-primary" style="font-size: 14px !important;">Activo</span>
                </td>
                <td ng-if="item.estaactivo == false">
                    <span class="label label-warning" style="font-size: 14px !important;">Inactivo</span>
                </td>
                <td  class="text-center">
                    <button type="button" class="btn btn-info btn-sm" ng-click="showModalInfoCliente(item)">
                        <i class="fa fa-lg fa-info-circle" aria-hidden="true"></i>
                    </button>
                    <button type="button" class="btn btn-warning btn-sm" ng-click="edit(item)">
                        <i class="fa fa-lg fa-pencil-square-o" aria-hidden="true"></i>
                    </button>
                    <button type="button" class="btn btn-danger btn-sm" ng-click="showModalDeleteCliente(item)">
                        <i class="fa fa-lg fa-trash" aria-hidden="true"></i>
                    </button>
                </td>
            </tr>
            </tbody>
        </table>
    </div>


    <div class="modal fade" tabindex="-1" role="dialog" id="modalAddCliente">
        <div class="modal-dialog" role="document" style="width: 60%;">
            <div class="modal-content">
                <div class="modal-header modal-header-primary">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">{{title_modal_cliente}}</h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" name="formCliente" novalidate="">

                        <div class="row">
                            <div class="col-xs-12">
                                <div class="col-sm-6 col-xs-12 form-group">
                                    <label for="t_fecha_ingreso" class="col-sm-4 col-xs-12 control-label">Fecha Ingreso:</label>
                                    <div class="col-sm-8 col-xs-12">
                                        <input type="text" class="form-control datepicker" name="t_fecha_ingreso" id="t_fecha_ingreso" ng-model="t_fecha_ingreso" disabled>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xs-12">
                                <fieldset>
                                    <legend style="font-size: 16px; font-weight: bold;">Datos de Cliente</legend>

                                    <div class="col-sm-6 col-xs-12 form-group error">
                                        <label for="t_tipocliente" class="col-sm-4 col-xs-12 control-label">Tipo Cliente:</label>
                                        <div class="col-sm-8 col-xs-12">
                                            <select id="t_tipocliente" class="form-control" ng-model="t_tipocliente"
                                                    ng-options="value.id as value.label for value in tipo_cliente" required></select>

                                        </div>
                                    </div>

                                    <input type="hidden" id="t_codigocliente" ng-model="t_codigocliente" value="0">
                                    <div class="col-xs-12" style="padding: 0;">
                                        <div class="col-sm-6 col-xs-12 form-group error">
                                            <label for="t_doc_id" class="col-sm-4 col-xs-12 control-label">CI/RUC:</label>
                                            <div class="col-sm-8 col-xs-12">
                                                <input type="text" class="form-control" name="t_doc_id" id="t_doc_id" ng-keypress="onlyNumber($event)"
                                                       ng-model="t_doc_id" ng-required="true" ng-minlength="10" ng-pattern="/^([0-9]+)$/">
                                                <span class="help-block error"
                                                      ng-show="formCliente.t_doc_id.$invalid && formCliente.t_doc_id.$touched">El Identificación es requerida</span>
                                                <span class="help-block error"
                                                      ng-show="formCliente.t_doc_id.$invalid && formCliente.t_doc_id.$error.pattern">La Identificación debe ser solo números</span>
                                                <span class="help-block error"
                                                      ng-show="formCliente.t_doc_id.$invalid && formCliente.t_doc_id.$error.minlength">La Identificación debe ser mayor a 10 digitos</span>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-xs-12 form-group error">
                                            <label for="t_doc_id" class="col-sm-4 col-xs-12 control-label">Email:</label>
                                            <div class="col-sm-8 col-xs-12">
                                                <input type="text" class="form-control" name="t_email" id="t_email"
                                                       ng-model="t_email" ng-pattern="/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/">
                                                <span class="help-block error"
                                                      ng-show="formCliente.t_email.$invalid && formCliente.t_email.$error.pattern">Formato de email no es correcto</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-xs-12 form-group error">
                                        <label for="t_apellidos" class="col-sm-4 col-xs-12 control-label">Apellidos:</label>
                                        <div class="col-sm-8 col-xs-12">
                                            <input type="text" class="form-control" name="t_apellidos" id="t_apellidos"
                                                   ng-model="t_apellidos" ng-required="true" ng-keypress="onlyCharasterAndSpace($event);">
                                            <span class="help-block error"
                                                  ng-show="formCliente.t_apellidos.$invalid && formCliente.t_apellidos.$touched" >El Apellido es requerido</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-xs-12 form-group error">
                                        <label for="t_nombres" class="col-sm-4 col-xs-12 control-label">Nombre(s):</label>
                                        <div class="col-sm-8 col-xs-12">
                                            <input type="text" class="form-control" name="t_nombres" id="t_nombres"
                                                   ng-model="t_nombres" ng-required="true" ng-keypress="onlyCharasterAndSpace($event);">
                                            <span class="help-block error"
                                                  ng-show="formCliente.t_nombres.$invalid && formCliente.t_nombres.$touched">El Nombre(s) es requerido</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-xs-12 form-group error">
                                        <label for="t_telf_principal" class="col-sm-4 col-xs-12 control-label" style="padding: 5px 0 5px 0;">Teléf. Principal:</label>
                                        <div class="col-sm-8 col-xs-12">
                                            <input type="text" class="form-control" name="t_telf_principal" id="t_telf_principal"
                                                   ng-model="t_telf_principal" ng-pattern="/^([0-9-\(\)]+)$/">
                                            <span class="help-block error"
                                                  ng-show="formCliente.t_telf_principal.$invalid && formCliente.t_telf_principal.$error.pattern">Solo números, guion y parentesis</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-xs-12 form-group error">
                                        <label for="t_telf_secundario" class="col-sm-4 col-xs-12 control-label" style="padding: 5px 0 5px 0;">Teléf. Secundario:</label>
                                        <div class="col-sm-8 col-xs-12">
                                            <input type="text" class="form-control" name="t_telf_secundario" id="t_telf_secundario"
                                                   ng-model="t_telf_secundario" ng-pattern="/^([0-9-\(\)]+)$/">
                                            <span class="help-block error"
                                                  ng-show="formCliente.t_telf_secundario.$invalid && formCliente.t_telf_secundario.$error.pattern">Solo números, guion y parentesis</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-xs-12 form-group error">
                                        <label for="t_celular" class="col-sm-4 col-xs-12 control-label">Celular:</label>
                                        <div class="col-sm-8 col-xs-12">
                                            <input type="text" class="form-control" name="t_celular" id="t_celular"
                                                   ng-model="t_celular" ng-pattern="/^([0-9-\(\)]+)$/">
                                            <span class="help-block error"
                                                  ng-show="formCliente.t_celular.$invalid && formCliente.t_celular.$error.pattern">Solo números, guion y parentesis</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-xs-12 form-group error">
                                        <label for="t_direccion" class="col-sm-4 col-xs-12 control-label">Dirección:</label>
                                        <div class="col-sm-8 col-xs-12">
                                            <input type="text" class="form-control" name="t_direccion" id="t_direccion" ng-model="t_direccion">
                                        </div>
                                    </div>
                                </fieldset>
                            </div>

                            <div class="col-xs-12">
                                <fieldset>
                                    <legend style="font-size: 16px; font-weight: bold;">Datos del Trabajo</legend>
                                    <div class="col-sm-6 col-xs-12 form-group error">
                                        <label for="t_telf_principal_emp" class="col-sm-4 col-xs-12 control-label" style="padding: 5px 0 5px 0;">Teléf. Principal:</label>
                                        <div class="col-sm-8 col-xs-12">
                                            <input type="text" class="form-control" name="t_telf_principal_emp" id="t_telf_principal_emp"
                                                   ng-model="t_telf_principal_emp" ng-pattern="/^([0-9-\(\)]+)$/">
                                            <span class="help-block error"
                                                  ng-show="formCliente.t_telf_principal_emp.$invalid && formCliente.t_telf_principal_emp.$error.pattern">Solo números, guion y parentesis</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-xs-12 form-group error">
                                        <label for="t_telf_secundario_emp" class="col-sm-4 col-xs-12 control-label" style="padding: 5px 0 5px 0;">Teléf. Secundario:</label>
                                        <div class="col-sm-8 col-xs-12">
                                            <input type="text" class="form-control" name="t_telf_secundario_emp" id="t_telf_secundario_emp"
                                                   ng-model="t_telf_secundario_emp" ng-pattern="/^([0-9-\(\)]+)$/">
                                            <span class="help-block error"
                                                  ng-show="formCliente.t_telf_secundario_emp.$invalid && formCliente.t_telf_secundario_emp.$error.pattern">Solo números, guion y parentesis</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-xs-12 form-group error">
                                        <label for="t_direccion_emp" class="col-sm-4 col-xs-12 control-label">Dirección:</label>
                                        <div class="col-sm-8 col-xs-12">
                                            <input type="text" class="form-control" name="t_direccion_emp" id="t_direccion_emp" ng-model="t_direccion_emp">
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        Cancelar <span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span>
                    </button>
                    <button type="button" class="btn btn-success" id="btn-save" ng-click="saveCliente()" ng-disabled="formCliente.$invalid">
                        Guardar <span class="glyphicon glyphicon-floppy-saved" aria-hidden="true"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" id="modalDeleteCliente">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header modal-header-danger">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Confirmación</h4>
                </div>
                <div class="modal-body">
                    <span>Realmente desea eliminar el cliente: <strong>"{{nom_cliente}}"</strong> seleccionado...</span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        Cancelar <span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span>
                    </button>
                    <button type="button" class="btn btn-danger" id="btn-save" ng-click="deleteCliente()">
                        Eliminar <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" id="modalInfoCliente">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header modal-header-info">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Información Cliente</h4>
                </div>
                <div class="modal-body">
                    <div class="col-xs-12 text-center">
                        <img class="img-thumbnail" src="<?= asset('img/solicitud.png') ?>" alt="">
                    </div>
                    <div class="row text-center">
                        <div class="col-xs-12 text-center" style="font-size: 18px;">{{name_cliente}}</div>
                        <div class="col-xs-12">
                            <span style="font-weight: bold">CI/RUC: </span>{{identify_cliente}}
                        </div>
                        <div class="col-xs-12">
                            <span style="font-weight: bold">Fecha Solicitud: </span>{{fecha_solicitud}}
                        </div>
                        <div class="col-xs-12">
                            <span style="font-weight: bold">Dirección Domicilio: </span>{{address_cliente}}
                        </div>
                        <div class="col-xs-12">
                            <span style="font-weight: bold">Email: </span>{{email_cliente}}
                        </div>
                        <div class="col-xs-12">
                            <span style="font-weight: bold">Celular: </span>{{celular_cliente}}
                        </div>
                        <div class="col-xs-12">
                            <span style="font-weight: bold">Teléfonos Domicilio: </span>{{telf_cliente}}
                        </div>
                        <div class="col-xs-12">
                            <span style="font-weight: bold">Teléfonos Trabajo: </span>{{telf_cliente_emp}}
                        </div>
                        <div class="col-xs-12">
                            <span style="font-weight: bold">Estado: </span>{{estado_solicitud}}
                        </div>
                        <div class="col-xs-12">
                            <span style="font-weight: bold">Tipo de Cliente: </span>{{tipo_cliente}}
                        </div>
                    </div>
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

</div>

</body>

<script src="<?= asset('app/lib/angular/angular.min.js') ?>"></script>
<script src="<?= asset('app/lib/angular/angular-route.min.js') ?>"></script>
<script src="<?= asset('js/jquery.min.js') ?>"></script>
<script src="<?= asset('js/bootstrap.min.js') ?>"></script>

<script src="<?= asset('app/app.js') ?>"></script>
<script src="<?= asset('app/controllers/clientesController.js') ?>"></script>

</html>

