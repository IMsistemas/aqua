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
                <td style="font-weight: bold;"><i class="fa fa-user fa-lg" aria-hidden="true"></i> {{item.complete_name}}</td>
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
                    <button type="button" class="btn btn-primary btn-sm" ng-click="showModalAction(item)">
                        <i class="fa fa-lg fa-cogs" aria-hidden="true"></i>
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
                                                   ng-model="t_apellidos" ng-required="true" ng-keypress="onlyCharasterAndSpace($event);" ng-pattern="/^([a-zA-ZáéíóúñÑ ])+$/">
                                            <span class="help-block error"
                                                  ng-show="formCliente.t_apellidos.$invalid && formCliente.t_apellidos.$touched" >El Apellido es requerido</span>
                                            <span class="help-block error"
                                                  ng-show="formCliente.t_apellidos.$invalid && formCliente.t_apellidos.$error.pattern">Solo letras y espacios</span>

                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-xs-12 form-group error">
                                        <label for="t_nombres" class="col-sm-4 col-xs-12 control-label">Nombre(s):</label>
                                        <div class="col-sm-8 col-xs-12">
                                            <input type="text" class="form-control" name="t_nombres" id="t_nombres"
                                                   ng-model="t_nombres" ng-required="true" ng-keypress="onlyCharasterAndSpace($event);" ng-pattern="/^([a-zA-ZáéíóúñÑ ])+$/">
                                            <span class="help-block error"
                                                  ng-show="formCliente.t_nombres.$invalid && formCliente.t_nombres.$touched">El Nombre(s) es requerido</span>
                                            <span class="help-block error"
                                                  ng-show="formCliente.t_nombres.$invalid && formCliente.t_nombres.$error.pattern">Solo letras y espacios</span>

                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-xs-12 form-group error">
                                        <label for="t_telf_principal" class="col-sm-4 col-xs-12 control-label" style="padding: 5px 0 5px 0;">Teléf. Principal:</label>
                                        <div class="col-sm-8 col-xs-12">
                                            <input type="text" class="form-control" name="t_telf_principal" id="t_telf_principal"
                                                   ng-model="t_telf_principal" ng-keypress="onlyNumber($event)" ng-minlength="9" ng-pattern="/^([0-9]+)$/">
                                            <span class="help-block error"
                                                  ng-show="formCliente.t_telf_principal.$invalid && formCliente.t_telf_principal.$error.pattern">Solo números</span>
                                            <span class="help-block error"
                                                  ng-show="formCliente.t_telf_principal.$invalid && formCliente.t_telf_principal.$error.minlength">El Teléf. Principal debe ser mayor a 9 digitos</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-xs-12 form-group error">
                                        <label for="t_telf_secundario" class="col-sm-4 col-xs-12 control-label" style="padding: 5px 0 5px 0;">Teléf. Secundario:</label>
                                        <div class="col-sm-8 col-xs-12">
                                            <input type="text" class="form-control" name="t_telf_secundario" id="t_telf_secundario"
                                                   ng-model="t_telf_secundario" ng-keypress="onlyNumber($event)" ng-minlength="9" ng-pattern="/^([0-9]+)$/">
                                            <span class="help-block error"
                                                  ng-show="formCliente.t_telf_secundario.$invalid && formCliente.t_telf_secundario.$error.pattern">Solo números</span>
                                            <span class="help-block error"
                                                  ng-show="formCliente.t_telf_secundario.$invalid && formCliente.t_telf_secundario.$error.minlength">El Teléf. Secundario debe ser mayor a 9 digitos</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-xs-12 form-group error">
                                        <label for="t_celular" class="col-sm-4 col-xs-12 control-label">Celular:</label>
                                        <div class="col-sm-8 col-xs-12">
                                            <input type="text" class="form-control" name="t_celular" id="t_celular"
                                                   ng-model="t_celular" ng-keypress="onlyNumber($event)" ng-minlength="10" ng-pattern="/^([0-9]+)$/">
                                            <span class="help-block error"
                                                  ng-show="formCliente.t_celular.$invalid && formCliente.t_celular.$error.pattern">Solo números</span>
                                            <span class="help-block error"
                                                  ng-show="formCliente.t_celular.$invalid && formCliente.t_celular.$error.minlength">El Nro Celular debe ser mayor a 10 digitos</span>
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
                                                   ng-model="t_telf_principal_emp"  ng-keypress="onlyNumber($event)" ng-minlength="9" ng-pattern="/^([0-9]+)$/">
                                            <span class="help-block error"
                                                  ng-show="formCliente.t_telf_principal_emp.$invalid && formCliente.t_telf_principal_emp.$error.pattern">Solo números</span>
                                            <span class="help-block error"
                                                  ng-show="formCliente.t_telf_principal_emp.$invalid && formCliente.t_telf_principal_emp.$error.minlength">El Teléf. Principal debe ser mayor a 9 digitos</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-xs-12 form-group error">
                                        <label for="t_telf_secundario_emp" class="col-sm-4 col-xs-12 control-label" style="padding: 5px 0 5px 0;">Teléf. Secundario:</label>
                                        <div class="col-sm-8 col-xs-12">
                                            <input type="text" class="form-control" name="t_telf_secundario_emp" id="t_telf_secundario_emp"
                                                   ng-model="t_telf_secundario_emp" ng-keypress="onlyNumber($event)" ng-minlength="9" ng-pattern="/^([0-9]+)$/">
                                            <span class="help-block error"
                                                  ng-show="formCliente.t_telf_secundario_emp.$invalid && formCliente.t_telf_secundario_emp.$error.pattern">Solo números</span>
                                            <span class="help-block error"
                                                  ng-show="formCliente.t_telf_secundario_emp.$invalid && formCliente.t_telf_secundario_emp.$error.minlength">El Teléf. Secundario debe ser mayor a 9 digitos</span>
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

    <div class="modal fade" tabindex="-1" role="dialog" id="modalMessage" style="z-index: 999999;">
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

    <div class="modal fade" tabindex="-1" role="dialog" id="modalMessageInfo" style="z-index: 999999;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header modal-header-info">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Información</h4>
                </div>
                <div class="modal-body">
                    <span>{{message_info}}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" id="modalAction">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header modal-header-info">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Tipo de Solicitud</h4>
                </div>
                <div class="modal-body">
                    <button type="button" class="btn btn-primary btn-block" ng-click="actionSuministro()">
                        Suministros
                    </button>
                    <button type="button" class="btn btn-primary btn-block" ng-click="actionServicio()">
                        Servicios
                    </button>
                    <button type="button" class="btn btn-primary btn-block" ng-click="actionSetName()">
                        Cambio de Nombre
                    </button>
                    <button type="button" class="btn btn-primary btn-block" ng-click="actionMantenimiento()">
                        Mantenimiento
                    </button>
                    <button type="button" class="btn btn-primary btn-block" ng-click="actionOtro()">
                        Otras Solicitudes
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" id="modalActionServicio">
        <div class="modal-dialog" role="document" style="width: 45%;">
            <div class="modal-content">
                <div class="modal-header modal-header-primary">

                    <div class="col-md-5 col-xs-12">
                        <h4 class="modal-title">Solicitud de Servicio Nro: {{num_solicitud_servicio}}</h4>
                    </div>
                    <div class="col-md-7 col-xs-12">
                        <div class="form-group">
                            <h4 class="modal-title"><label for="t_fecha_process" class="col-sm-6" style="font-weight: normal !important;">Fecha Ingreso:</label></h4>
                            <div class="col-sm-5" style="padding: 0;">
                                <input type="text" class="form-control input-sm datepicker" name="t_fecha_process"
                                       id="t_fecha_process" ng-model="t_fecha_process" style="color: black !important;" disabled>
                            </div>
                            <div class="col-sm-1 col-xs-12 text-right" style="padding: 0;">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" name="formProcess" novalidate="">

                        <div class="row">
                            <div class="col-xs-12" style="padding: 2%; margin-top: -20px !important;">
                                <fieldset ng-cloak>
                                    <legend style="font-size: 16px; font-weight: bold;">Datos del Cliente</legend>

                                    <div class="col-xs-12" style="padding: 0;">
                                        <div class="col-sm-6 col-xs-12">
                                            <span class="label label-info" style="font-size: 14px !important;">
                                                <i class="fa fa-star" aria-hidden="true"></i> RUC/CI:</span> {{documentoidentidad_cliente}}
                                        </div>
                                        <div class="col-sm-6 col-xs-12">
                                            <span class="label label-info" style="font-size: 14px !important;">
                                                <i class="fa fa-user" aria-hidden="true"></i> CLIENTE:</span> {{nom_cliente}}
                                            <input type="hidden" ng-model="h_codigocliente">
                                        </div>
                                    </div>
                                    <div class="col-xs-12" style="padding: 0; margin-top: 5px;">
                                        <div class="col-sm-6 col-xs-12">
                                            <span class="label label-default" style="font-size: 14px !important;">
                                                <i class="fa fa-map-marker" aria-hidden="true"></i> Dirección Domicilio:</span> {{direcc_cliente}}
                                        </div>
                                        <div class="col-sm-6 col-xs-12">
                                            <span class="label label-default" style="font-size: 14px !important;">
                                                <i class="fa fa-phone" aria-hidden="true"></i> Teléfono Domicilio:</span> {{telf_cliente}}
                                        </div>
                                    </div>
                                    <div class="col-xs-12" style="padding: 0; margin-top: 5px;">
                                        <div class="col-sm-6 col-xs-12">
                                            <span class="label label-default" style="font-size: 14px !important;">
                                                <i class="fa fa-mobile" aria-hidden="true"></i> Celular:</span> {{celular_cliente}}
                                        </div>
                                        <div class="col-sm-6 col-xs-12">
                                            <span class="label label-default" style="font-size: 14px !important;">
                                                <i class="fa fa-phone" aria-hidden="true"></i> Teléfono Trabajo:</span> {{telf_trab_cliente}}
                                        </div>
                                    </div>
                                    <div class="col-xs-12" style="padding: 0; margin-top: 5px;">
                                        <div class="col-sm-6 col-xs-12">
                                            <span class="label label-default" style="font-size: 14px !important;">
                                                <i class="fa fa-users" aria-hidden="true"></i> Tipo Cliente:</span> {{tipo_tipo_cliente}}
                                        </div>
                                    </div>
                                </fieldset>
                            </div>

                            <div class="col-xs-12" style="padding: 2%;">
                                <fieldset>
                                    <legend style="font-size: 16px; font-weight: bold;">Solicitud Servicios</legend>

                                    <table class="table table-responsive table-striped table-hover table-condensed">
                                        <thead class="bg-primary">
                                            <tr>
                                                <th class="text-center">SERVICIOS</th>
                                                <th class="text-center" style="width: 30%;">PRECIO</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr ng-repeat="item in services" ng-cloak>
                                                <td>{{item.nombreservicio}}</td>
                                                <td>
                                                    <input type="text" class="form-control" ng-model="item.valor" ng-keypress="onlyDecimal($event)">
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>

                                </fieldset>
                            </div>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        Cancelar <span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span>
                    </button>
                    <button type="button" class="btn btn-success" id="btn-save-servicio"
                            ng-click="saveSolicitudServicio()" ng-disabled="formProcess.$invalid">
                        Guardar <span class="glyphicon glyphicon-floppy-saved" aria-hidden="true"></span>
                    </button>
                    <button type="button" class="btn btn-primary" id="btn-process-servicio"
                            ng-click="procesarSolicitud('btn-process-servicio')" disabled>
                        Procesar <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" id="modalActionSetNombre">
        <div class="modal-dialog" role="document" style="width: 60%;">
            <div class="modal-content">
                <div class="modal-header modal-header-primary">

                    <div class="col-md-6 col-xs-12">
                        <h4 class="modal-title">Solicitud de Cambio de Nombre Nro: {{num_solicitud_setnombre}}</h4>
                    </div>
                    <div class="col-md-6 col-xs-12">
                        <div class="form-group">
                            <h4 class="modal-title"><label for="t_fecha_process" class="col-sm-6" style="font-weight: normal !important;">Fecha Ingreso:</label></h4>
                            <div class="col-sm-5" style="padding: 0;">
                                <input type="text" class="form-control input-sm datepicker" name="t_fecha_setnombre"
                                       id="t_fecha_setnombre" ng-model="t_fecha_setnombre" style="color: black !important;" disabled>
                            </div>
                            <div class="col-sm-1 col-xs-12 text-right" style="padding: 0;">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" name="formSetNombre" novalidate="">

                        <div class="row">
                            <div class="col-xs-12" style="padding: 2%; margin-top: -20px !important;">
                                <fieldset ng-cloak>
                                    <legend style="font-size: 16px; font-weight: bold;">Datos del Cliente actual</legend>

                                    <div class="col-xs-12" style="padding: 0;">
                                        <div class="col-sm-6 col-xs-12">
                                            <span class="label label-info" style="font-size: 14px !important;">
                                                <i class="fa fa-star" aria-hidden="true"></i> RUC/CI:</span> {{documentoidentidad_cliente_setnombre}}
                                        </div>
                                        <div class="col-sm-6 col-xs-12">
                                            <span class="label label-info" style="font-size: 14px !important;">
                                                <i class="fa fa-user" aria-hidden="true"></i> CLIENTE:</span> {{nom_cliente_setnombre}}
                                            <input type="hidden" ng-model="h_codigocliente_setnombre">
                                        </div>
                                    </div>
                                    <div class="col-xs-12" style="padding: 0; margin-top: 5px;">
                                        <div class="col-sm-6 col-xs-12">
                                            <span class="label label-default" style="font-size: 14px !important;">
                                                <i class="fa fa-map-marker" aria-hidden="true"></i> Dirección Domicilio:</span> {{direcc_cliente_setnombre}}
                                        </div>
                                        <div class="col-sm-6 col-xs-12">
                                            <span class="label label-default" style="font-size: 14px !important;">
                                                <i class="fa fa-phone" aria-hidden="true"></i> Teléfono Domicilio:</span> {{telf_cliente_setnombre}}
                                        </div>
                                    </div>
                                    <div class="col-xs-12" style="padding: 0; margin-top: 5px;">
                                        <div class="col-sm-6 col-xs-12">
                                            <span class="label label-default" style="font-size: 14px !important;">
                                                <i class="fa fa-mobile" aria-hidden="true"></i> Celular:</span> {{celular_cliente_setnombre}}
                                        </div>
                                        <div class="col-sm-6 col-xs-12">
                                            <span class="label label-default" style="font-size: 14px !important;">
                                                <i class="fa fa-phone" aria-hidden="true"></i> Teléfono Trabajo:</span> {{telf_trab_cliente_setnombre}}
                                        </div>
                                    </div>
                                </fieldset>
                            </div>

                            <div class="col-xs-12" style="padding: 2%; margin-top: -25px !important;">
                                <fieldset>
                                    <legend style="font-size: 16px; font-weight: bold;">Datos de Suministro</legend>

                                    <div class="col-xs-12" style="">
                                        <div class="col-sm-6 col-xs-12 form-group error">
                                            <label for="s_suministro_setnombre" class="col-sm-4 col-xs-12 control-label">Suministros:</label>
                                            <div class="col-sm-8 col-xs-12" style="">
                                                <select class="form-control" name="s_suministro_setnombre" id="s_suministro_setnombre"
                                                        ng-model="s_suministro_setnombre" ng-options="value.id as value.label for value in suministro_setN"
                                                        ng-change="showInfoSuministroForSetName()" ng-pattern="/^[1-9]+$/"></select>
                                                <span class="help-block error"
                                                      ng-show="formSetNombre.s_suministro_setnombre.$invalid && formSetNombre.s_suministro_setnombre.$error.pattern">
                                                            Seleccione un Suministro</span>
                                            </div>
                                        </div>

                                        <div class="col-sm-6 col-xs-12" style="padding-left: 45px;">
                                            <span class="label label-default" style="!important; font-size: 14px !important;">
                                                <i class="fa fa-map-marker" aria-hidden="true"></i> Zona:</span> {{zona_setnombre}}
                                        </div>
                                    </div>

                                    <div class="col-xs-12" style="padding: 0;">
                                        <div class="col-sm-6 col-xs-12">
                                            <span class="label label-default" style="font-size: 14px !important;">
                                                <i class="fa fa-map-marker" aria-hidden="true"></i> Transversal:</span> {{transversal_setnombre}}
                                        </div>
                                        <div class="col-sm-6 col-xs-12">
                                            <span class="label label-default" style="font-size: 14px !important;">
                                                <i class="fa fa-list" aria-hidden="true"></i> Tarifa:</span> {{tarifa_setnombre}}
                                        </div>
                                    </div>

                                </fieldset>
                            </div>

                            <div class="col-xs-12" style="padding: 2%; margin-top: -20px !important;">
                                <fieldset>
                                    <legend style="font-size: 16px; font-weight: bold;">Datos del nuevo Cliente</legend>

                                    <div class="col-xs-12" style="padding: 0;">
                                        <div class="col-sm-6 col-xs-12 form-group error">

                                            <label for="s_ident_new_client_setnombre" class="col-sm-4 col-xs-12 control-label">RUC/CI:</label>
                                            <div class="col-sm-8 col-xs-12" style="">
                                                <select class="form-control"
                                                        name="s_ident_new_client_setnombre" id="s_ident_new_client_setnombre"
                                                        ng-model="s_ident_new_client_setnombre" ng-options="value.id as value.label for value in clientes_setN"
                                                        ng-change="showInfoClienteForSetName()" ng-pattern="/^[1-9]+$/"></select>
                                                <span class="help-block error"
                                                      ng-show="formSetNombre.s_ident_new_client_setnombre.$invalid && formSetNombre.s_ident_new_client_setnombre.$error.pattern">
                                                            Seleccione un Cliente</span>
                                            </div>

                                        </div>
                                        <div class="col-sm-6 col-xs-12" style="padding-left: 45px;">
                                            <span class="label label-default" style="font-size: 14px !important;">
                                                <i class="fa fa-user" aria-hidden="true"></i> Cliente:</span> {{nom_new_cliente_setnombre}}
                                            <input type="hidden" ng-model="h_new_codigocliente_setnombre">
                                        </div>
                                    </div>
                                    <div class="col-xs-12" style="padding: 0;">
                                        <div class="col-sm-6 col-xs-12">
                                            <span class="label label-default" style="font-size: 14px !important;">
                                                <i class="fa fa-map-marker" aria-hidden="true"></i> Dirección Domicilio:</span> {{direcc_new_cliente_setnombre}}
                                        </div>
                                        <div class="col-sm-6 col-xs-12">
                                            <span class="label label-default" style="font-size: 14px !important;">
                                                <i class="fa fa-phone" aria-hidden="true"></i> Teléfono Domicilio:</span> {{telf_new_cliente_setnombre}}
                                        </div>
                                    </div>
                                    <div class="col-xs-12" style="padding: 0; margin-top: 5px;">
                                        <div class="col-sm-6 col-xs-12">
                                            <span class="label label-default" style="font-size: 14px !important;">
                                                <i class="fa fa-mobile" aria-hidden="true"></i> Celular:</span> {{celular_new_cliente_setnombre}}
                                        </div>
                                        <div class="col-sm-6 col-xs-12">
                                            <span class="label label-default" style="font-size: 14px !important;">
                                                <i class="fa fa-phone" aria-hidden="true"></i> Teléfono Trabajo:</span> {{telf_trab_new_cliente_setnombre}}
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
                    <button type="button" class="btn btn-success" id="btn-save-setnombre"
                            ng-click="saveSolicitudCambioNombre()" ng-disabled="formSetNombre.$invalid">
                        Guardar <span class="glyphicon glyphicon-floppy-saved" aria-hidden="true"></span>
                    </button>
                    <button type="button" class="btn btn-primary" id="btn-process-setnombre"
                            ng-click="procesarSolicitudSetName()" disabled>
                        Procesar <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" id="modalActionMantenimiento">
        <div class="modal-dialog" role="document" style="width: 60%;">
            <div class="modal-content">
                <div class="modal-header modal-header-primary">

                    <div class="col-md-6 col-xs-12">
                        <h4 class="modal-title">Solicitud de Mantenimiento Nro: {{num_solicitud_mant}}</h4>
                    </div>
                    <div class="col-md-6 col-xs-12">
                        <div class="form-group">
                            <h4 class="modal-title"><label for="t_fecha_mant" class="col-sm-6" style="font-weight: normal !important;">Fecha Ingreso:</label></h4>
                            <div class="col-sm-5" style="padding: 0;">
                                <input type="text" class="form-control input-sm datepicker" name="t_fecha_mant"
                                       id="t_fecha_mant" ng-model="t_fecha_mant" style="color: black !important;" disabled>
                            </div>
                            <div class="col-sm-1 col-xs-12 text-right" style="padding: 0;">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" name="formMant" novalidate="">
                        <div class="row">
                            <div class="col-xs-12" style="padding: 2%; margin-top: -20px !important;">
                                <fieldset ng-cloak>
                                    <legend style="font-size: 16px; font-weight: bold;">Datos del Cliente</legend>

                                    <div class="col-xs-12" style="padding: 0;">
                                        <div class="col-sm-6 col-xs-12">
                                            <span class="label label-info" style="font-size: 14px !important;">
                                                <i class="fa fa-star" aria-hidden="true"></i> RUC/CI:</span> {{documentoidentidad_cliente_mant}}
                                        </div>
                                        <div class="col-sm-6 col-xs-12">
                                            <span class="label label-info" style="font-size: 14px !important;">
                                                <i class="fa fa-user" aria-hidden="true"></i> CLIENTE:</span> {{nom_cliente_mant}}
                                            <input type="hidden" ng-model="h_codigocliente_mant">
                                        </div>
                                    </div>
                                    <div class="col-xs-12" style="padding: 0; margin-top: 5px;">
                                        <div class="col-sm-6 col-xs-12">
                                            <span class="label label-default" style="font-size: 14px !important;">
                                                <i class="fa fa-map-marker" aria-hidden="true"></i> Dirección Domicilio:</span> {{direcc_cliente_mant}}
                                        </div>
                                        <div class="col-sm-6 col-xs-12">
                                            <span class="label label-default" style="font-size: 14px !important;">
                                                <i class="fa fa-phone" aria-hidden="true"></i> Teléfono Domicilio:</span> {{telf_cliente_mant}}
                                        </div>
                                    </div>
                                    <div class="col-xs-12" style="padding: 0; margin-top: 5px;">
                                        <div class="col-sm-6 col-xs-12">
                                            <span class="label label-default" style="font-size: 14px !important;">
                                                <i class="fa fa-mobile" aria-hidden="true"></i> Celular:</span> {{celular_cliente_mant}}
                                        </div>
                                        <div class="col-sm-6 col-xs-12">
                                            <span class="label label-default" style="font-size: 14px !important;">
                                                <i class="fa fa-phone" aria-hidden="true"></i> Teléfono Trabajo:</span> {{telf_trab_cliente_mant}}
                                        </div>
                                    </div>
                                </fieldset>
                            </div>

                            <div class="col-xs-12" style="padding: 2%; margin-top: -25px !important;">
                                <fieldset>
                                    <legend style="font-size: 16px; font-weight: bold;">Datos de Suministro</legend>

                                    <div class="col-xs-12" style="">
                                        <div class="col-sm-6 col-xs-12 form-group error text-left">
                                            <label for="s_suministro_mant" class="col-sm-4 col-xs-12 control-label">Suministros:</label>
                                            <div class="col-sm-8 col-xs-12" style="">
                                                <select class="form-control" name="s_suministro_mant" id="s_suministro_mant"
                                                        ng-model="s_suministro_mant" ng-options="value.id as value.label for value in suministro_mant"
                                                        ng-change="showInfoSuministro()" ng-pattern="/^[1-9]+$/"></select>
                                                <!--<span class="help-block error"
                                                      ng-show="formMant.s_suministro_mant.$invalid && formMant.s_suministro_mant.$touched">Suministro es requerida</span>-->
                                                <span class="help-block error"
                                                      ng-show="formMant.s_suministro_mant.$invalid && formMant.s_suministro_mant.$error.pattern">
                                                            Seleccione un Suministro</span>
                                            </div>
                                        </div>

                                        <div class="col-sm-6 col-xs-12" style="padding-left: 45px;">
                                            <span class="label label-default" style="!important; font-size: 14px !important;">
                                                <i class="fa fa-map-marker" aria-hidden="true"></i> Zona:</span> {{zona_mant}}
                                        </div>
                                    </div>

                                    <div class="col-xs-12" style="padding: 0;">
                                        <div class="col-sm-6 col-xs-12">
                                            <span class="label label-default" style="font-size: 14px !important;">
                                                <i class="fa fa-map-marker" aria-hidden="true"></i> Transversal:</span> {{transversal_mant}}
                                        </div>
                                        <div class="col-sm-6 col-xs-12">
                                            <span class="label label-default" style="font-size: 14px !important;">
                                                <i class="fa fa-list" aria-hidden="true"></i> Tarifa:</span> {{tarifa_mant}}
                                        </div>
                                    </div>

                                </fieldset>
                            </div>

                            <div class="col-xs-12 form-group error" style="">
                                <label for="t_observacion_mant" class="col-sm-2 col-xs-12 control-label" style="padding: 5px 0 5px 0;">Observación:</label>
                                <div class="col-sm-10 col-xs-12">
                                    <textarea class="form-control" name="t_observacion_mant" id="t_observacion_mant" ng-model="t_observacion_mant" rows="2" ng-required="true"></textarea>
                                    <span class="help-block error"
                                          ng-show="formMant.t_observacion_mant.$invalid && formMant.t_observacion_mant.$touched">La Observación es requerida</span>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        Cancelar <span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span>
                    </button>
                    <button type="button" class="btn btn-success" id="btn-save-mant"
                            ng-click="saveSolicitudMantenimiento()" ng-disabled="formMant.$invalid">
                        Guardar <span class="glyphicon glyphicon-floppy-saved" aria-hidden="true"></span>
                    </button>

                    <!--<button type="button" class="btn btn-success" id="btn-save-otro"
                            ng-click="saveSolicitudOtro();" ng-disabled="formMant.$invalid">
                        Guardar <span class="glyphicon glyphicon-floppy-saved" aria-hidden="true"></span>
                    </button>-->

                    <button type="button" class="btn btn-primary" id="btn-process-mant"
                            ng-click="procesarSolicitud('btn-process-mant')" disabled>
                        Procesar <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" id="modalActionOtro">
        <div class="modal-dialog" role="document" style="width: 60%;">
            <div class="modal-content">
                <div class="modal-header modal-header-primary">

                    <div class="col-md-6 col-xs-12">
                        <h4 class="modal-title">Otro Tipo de Solicitud Solicitud Nro: {{num_solicitud_otro}}</h4>
                    </div>
                    <div class="col-md-6 col-xs-12">
                        <div class="form-group">
                            <h4 class="modal-title"><label for="t_fecha_process" class="col-sm-6" style="font-weight: normal !important;">Fecha Ingreso:</label></h4>
                            <div class="col-sm-5" style="padding: 0;">
                                <input type="text" class="form-control input-sm datepicker" name="t_fecha_otro"
                                       id="t_fecha_otro" ng-model="t_fecha_otro" style="color: black !important;" disabled>
                            </div>
                            <div class="col-sm-1 col-xs-12 text-right" style="padding: 0;">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" name="formProcessOtros" novalidate="">

                        <div class="row">
                            <div class="col-xs-12" style="padding: 2%; margin-top: -20px !important;">
                                <fieldset ng-cloak>
                                    <legend style="font-size: 16px; font-weight: bold;">Datos del Cliente</legend>

                                    <div class="col-xs-12" style="padding: 0;">
                                        <div class="col-sm-6 col-xs-12">
                                            <span class="label label-info" style="font-size: 14px !important;">
                                                <i class="fa fa-star" aria-hidden="true"></i> RUC/CI:</span> {{documentoidentidad_cliente_otro}}
                                        </div>
                                        <div class="col-sm-6 col-xs-12">
                                            <span class="label label-info" style="font-size: 14px !important;">
                                                <i class="fa fa-user" aria-hidden="true"></i> CLIENTE:</span> {{nom_cliente_otro}}
                                            <input type="hidden" ng-model="h_codigocliente_otro">
                                        </div>
                                    </div>
                                    <div class="col-xs-12" style="padding: 0; margin-top: 5px;">
                                        <div class="col-sm-6 col-xs-12">
                                            <span class="label label-default" style="font-size: 14px !important;">
                                                <i class="fa fa-map-marker" aria-hidden="true"></i> Dirección Domicilio:</span> {{direcc_cliente_otro}}
                                        </div>
                                        <div class="col-sm-6 col-xs-12">
                                            <span class="label label-default" style="font-size: 14px !important;">
                                                <i class="fa fa-phone" aria-hidden="true"></i> Teléfono Domicilio:</span> {{telf_cliente_otro}}
                                        </div>
                                    </div>
                                    <div class="col-xs-12" style="padding: 0; margin-top: 5px;">
                                        <div class="col-sm-6 col-xs-12">
                                            <span class="label label-default" style="font-size: 14px !important;">
                                                <i class="fa fa-mobile" aria-hidden="true"></i> Celular:</span> {{celular_cliente_otro}}
                                        </div>
                                        <div class="col-sm-6 col-xs-12">
                                            <span class="label label-default" style="font-size: 14px !important;">
                                                <i class="fa fa-phone" aria-hidden="true"></i> Teléfono Trabajo:</span> {{telf_trab_cliente_otro}}
                                        </div>
                                    </div>
                                </fieldset>
                            </div>

                            <div class="col-xs-12 form-group" style="">
                                <label for="t_observacion_otro" class="col-sm-2 col-xs-12 control-label" style="padding: 5px 0 5px 0;">Descripción:</label>
                                <div class="col-sm-10 col-xs-12">
                                    <textarea class="form-control" id="t_observacion_otro" ng-model="t_observacion_otro" rows="2" ng-required="true"></textarea>
                                    <span class="help-block error"
                                          ng-show="formProcessOtros.t_observacion_otro.$invalid && formProcessOtros.t_observacion_otro.$touched">La Descripción es requerida</span>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        Cancelar <span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span>
                    </button>
                    <button type="button" class="btn btn-success" id="btn-save-otro"
                            ng-click="saveSolicitudOtro();" ng-disabled="formProcessOtros.$invalid">
                        Guardar <span class="glyphicon glyphicon-floppy-saved" aria-hidden="true"></span>
                    </button>
                    <button type="button" class="btn btn-primary" id="btn-process-otro"
                            ng-click="procesarSolicitud('btn-process-otro')" disabled>
                        Procesar <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" id="modalActionSuministro">
        <div class="modal-dialog" role="document" style="width: 60%;">
            <div class="modal-content">
                <div class="modal-header modal-header-primary">

                    <div class="col-md-6 col-xs-12">
                        <h4 class="modal-title">Solicitud de Servicio Nro: {{num_solicitud_suministro}} - Agua Potable</h4>
                    </div>
                    <div class="col-md-6 col-xs-12">
                        <div class="form-group text-right">
                            <h4 class="modal-title">
                                <label for="t_fecha_process" class="col-sm-11" style="font-weight: normal !important;">
                                    <i class="fa fa-user fa-lg" aria-hidden="true"></i> {{nom_cliente_suministro}}
                                </label>
                            </h4>
                            <div class="col-sm-1 col-xs-12 text-right" style="padding: 0;">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" name="formProcessSuministro" novalidate="">
                        <div class="row">
                            <div class="col-xs-12" style="padding: 2%; margin-top: -20px !important;">
                                <fieldset ng-cloak>
                                    <legend style="font-size: 16px; font-weight: bold;">Datos Suministro</legend>

                                    <div class="col-sm-6 col-xs-12 form-group">
                                        <label for="t_suministro_nro" class="col-sm-5 col-xs-12 control-label">Nro Suministro:</label>
                                        <div class="col-sm-7 col-xs-12" style="">
                                            <input type="text" class="form-control" id="t_suministro_nro" ng-model="t_suministro_nro" disabled>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-xs-12 form-group">
                                        <label for="s_suministro_tarifa" class="col-sm-5 col-xs-12 control-label">Tarifa:</label>
                                        <div class="col-sm-7 col-xs-12" style="">
                                            <select name="s_suministro_tarifa" id="s_suministro_tarifa" class="form-control" ng-model="s_suministro_tarifa"
                                                    ng-options="value.id as value.label for value in tarifas" ng-pattern="/^[1-9]+$/"></select>
                                            <span class="help-block error"
                                                  ng-show="formProcessSuministro.s_suministro_tarifa.$invalid && formProcessSuministro.s_suministro_tarifa.$error.pattern">
                                                            Seleccione una Tarifa</span>
                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-xs-12 form-group">
                                        <label for="s_suministro_zona" class="col-sm-5 col-xs-12 control-label">Zona:</label>
                                        <div class="col-sm-7 col-xs-12" style="">
                                            <select name="s_suministro_zona" id="s_suministro_zona" class="form-control" ng-model="s_suministro_zona"
                                                    ng-options="value.id as value.label for value in barrios"
                                                    ng-change="getCalles()" ng-pattern="/^[1-9]+$/"></select>
                                            <span class="help-block error"
                                                  ng-show="formProcessSuministro.s_suministro_zona.$invalid && formProcessSuministro.s_suministro_zona.$error.pattern">
                                                            Seleccione una Zona</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-xs-12 form-group">
                                        <label for="s_suministro_transversal" class="col-sm-5 col-xs-12 control-label">Transversal:</label>
                                        <div class="col-sm-7 col-xs-12" style="">
                                            <select name="s_suministro_transversal" id="s_suministro_transversal" class="form-control" ng-model="s_suministro_transversal"
                                                    ng-options="value.id as value.label for value in calles" ng-pattern="/^[1-9]+$/"></select>
                                            <span class="help-block error"
                                                  ng-show="formProcessSuministro.s_suministro_transversal.$invalid && formProcessSuministro.s_suministro_transversal.$error.pattern">
                                                            Seleccione una Transversal</span>
                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-xs-12 form-group error">
                                        <label for="t_suministro_direccion" class="col-sm-5 col-xs-12 control-label">Dirección Instalac.:</label>
                                        <div class="col-sm-7 col-xs-12" style="">
                                            <input type="text" class="form-control" name="t_suministro_direccion" id="t_suministro_direccion" ng-model="t_suministro_direccion" ng-required="true">
                                            <span class="help-block error"
                                                  ng-show="formProcessSuministro.t_suministro_direccion.$invalid && formProcessSuministro.t_suministro_direccion.$touched">
                                                        La Dirección es requerida</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-xs-12 form-group error">
                                        <label for="t_suministro_telf" class="col-sm-5 col-xs-12 control-label">Teléfono Instalac.:</label>
                                        <div class="col-sm-7 col-xs-12" style="">
                                            <input type="text" class="form-control" name="t_suministro_telf" id="t_suministro_telf"
                                                   ng-model="t_suministro_telf" ng-required="true" ng-keypress="onlyDecimal($event)">
                                            <span class="help-block error"
                                                  ng-show="formProcessSuministro.t_suministro_telf.$invalid && formProcessSuministro.t_suministro_telf.$touched">
                                                        El Teléfono es requerido</span>
                                        </div>
                                    </div>

                                </fieldset>
                            </div>

                            <div class="col-xs-12" style="padding: 2%; margin-top: -35px;">
                                <fieldset>
                                    <legend style="font-size: 16px; font-weight: bold;">Datos Costo</legend>

                                    <div class="col-xs-12" style="padding: 2%; margin-top: -15px;">
                                        <fieldset>
                                            <legend style="font-size: 14px; font-weight: bold;">Acometida</legend>
                                            <div class="col-sm-6 col-xs-12 form-group">
                                                <label for="t_suministro_aguapotable" class="col-sm-5 col-xs-12 control-label">Agua Potable:</label>
                                                <div class="col-sm-7 col-xs-12" style="">
                                                    <input type="text" class="form-control" name="t_suministro_aguapotable" id="t_suministro_aguapotable" ng-model="t_suministro_aguapotable"
                                                           ng-blur="calculateTotalSuministro()" ng-required="true" ng-keypress="onlyDecimal($event)">
                                                    <span class="help-block error"
                                                          ng-show="formProcessSuministro.t_suministro_aguapotable.$invalid && formProcessSuministro.t_suministro_aguapotable.$touched">
                                                        El Agua Potable es requerido</span>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-xs-12 form-group error">
                                                <label for="t_suministro_alcantarillado" class="col-sm-5 col-xs-12 control-label">Alcantarillado:</label>
                                                <div class="col-sm-7 col-xs-12" style="">
                                                    <input type="text" class="form-control" name="t_suministro_alcantarillado" id="t_suministro_alcantarillado" ng-model="t_suministro_alcantarillado"
                                                           ng-blur="calculateTotalSuministro()" ng-required="true" ng-keypress="onlyDecimal($event)">
                                                    <span class="help-block error"
                                                          ng-show="formProcessSuministro.t_suministro_alcantarillado.$invalid && formProcessSuministro.t_suministro_alcantarillado.$touched">
                                                        El Alcantarillado es requerido</span>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-xs-12 form-group error">
                                                <label for="t_suministro_garantia" class="col-sm-8 col-xs-12 control-label">Garantía Apertura de Calle:</label>
                                                <div class="col-sm-4 col-xs-12" style="">
                                                    <input type="text" class="form-control" name="t_suministro_garantia" id="t_suministro_garantia"
                                                           ng-model="t_suministro_garantia" ng-required="true" ng-keypress="onlyDecimal($event)">
                                                    <span class="help-block error"
                                                          ng-show="formProcessSuministro.t_suministro_garantia.$invalid && formProcessSuministro.t_suministro_garantia.$touched">
                                                        La Garantía es requerida</span>
                                                </div>
                                            </div>
                                        </fieldset>
                                    </div>

                                    <div class="col-xs-12" style="padding: 2%; margin-top: -35px;">
                                        <fieldset>
                                            <legend style="font-size: 14px; font-weight: bold;">Medidor</legend>

                                            <div class="col-sm-4 col-xs-12 form-group">
                                                <label for="t_suministro_medidor" class="col-sm-10 col-xs-12 control-label">¿Cliente tiene Medidor?</label>
                                                <div class="col-sm-2 col-xs-12" style="">
                                                    <input type="checkbox" id="t_suministro_medidor" ng-model="t_suministro_medidor"
                                                           ng-click="deshabilitarMedidor()">
                                                </div>
                                            </div>
                                            <div class="col-sm-4 col-xs-12"  style="padding: 0;">
                                                <div class="col-xs-12 form-group">
                                                    <label for="t_suministro_marca" class="col-sm-5 col-xs-12 control-label">Marca:</label>
                                                    <div class="col-sm-7 col-xs-12" style="">
                                                        <input type="text" class="form-control" id="t_suministro_marca" ng-model="t_suministro_marca">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-4 col-xs-12 form-group">
                                                <label for="t_suministro_costomedidor" class="col-sm-6 col-xs-12 control-label">Costo:</label>
                                                <div class="col-sm-6 col-xs-12" style="">
                                                    <input type="text" class="form-control" id="t_suministro_costomedidor" ng-model="t_suministro_costomedidor"
                                                           ng-blur="calculateTotalSuministro()" ng-keypress="onlyDecimal($event)">
                                                </div>
                                            </div>

                                        </fieldset>
                                    </div>

                                    <div class="col-xs-12" style="padding: 2%; margin-top: -35px;">
                                        <fieldset>
                                            <legend style="font-size: 14px; font-weight: bold;">Total</legend>
                                            <div class="col-sm-6 col-xs-12 form-group error">
                                                <label for="t_suministro_cuota" class="col-sm-5 col-xs-12 control-label">Cuota Inicial:</label>
                                                <div class="col-sm-7 col-xs-12" style="">
                                                    <input type="text" class="form-control" name="t_suministro_cuota" id="t_suministro_cuota" ng-model="t_suministro_cuota"
                                                        ng-blur="calculateTotalSuministro()" ng-required="true" ng-keypress="onlyDecimal($event)">
                                                    <span class="help-block error"
                                                          ng-show="formProcessSuministro.t_suministro_cuota.$invalid && formProcessSuministro.t_suministro_cuota.$touched">
                                                        La Couta Inicial es requerida</span>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-xs-12 form-group error">
                                                <label for="s_suministro_credito" class="col-sm-5 col-xs-12 control-label">Crédito:</label>
                                                <div class="col-sm-7 col-xs-12" style="">
                                                    <select name="s_suministro_credito" id="s_suministro_credito" class="form-control" ng-model="s_suministro_credito"
                                                            ng-options="value.id as value.label for value in creditos"
                                                            ng-change="calculateTotalSuministro()"  ng-pattern="/^[1-9]+$/"></select>
                                                    <span class="help-block error"
                                                          ng-show="formProcessSuministro.s_suministro_credito.$invalid && formProcessSuministro.s_suministro_credito.$error.pattern">
                                                            Seleccione un Crédito</span>
                                                </div>
                                            </div>
                                        </fieldset>
                                    </div>

                                    <div class="col-sm-6 col-xs-12 text-center" id="info_partial" style="font-size: 14px; display: none;">
                                        Total: <span style="font-weight: bold;">$ {{total_partial}}</span> a
                                        <span style="font-weight: bold;">{{credit_cant}}</span> meses plazo
                                    </div>
                                    <div class="col-sm-6 col-xs-12 text-center" id="info_total" style="font-size: 14px; display: none;">
                                        Cuotas de: <span style="font-weight: bold;">$ {{total_suministro}}</span> mensuales
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
                    <button type="button" class="btn btn-success" id="btn-save-solsuministro"
                            ng-click="saveSolicitudSuministro()" ng-disabled="formProcessSuministro.$invalid">
                        Guardar <span class="glyphicon glyphicon-floppy-saved" aria-hidden="true"></span>
                    </button>
                    <button type="button" class="btn btn-primary" id="btn-process-solsuministro"
                            ng-click="procesarSolicitudSuministro()" disabled>
                        Procesar <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
                    </button>
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


<script src="<?= asset('app/lib/angular/ng-file-upload-shim.min.js') ?>"></script>
<script src="<?= asset('app/lib/angular/ng-file-upload.min.js') ?>"></script>

<script src="<?= asset('app/app.js') ?>"></script>
<script src="<?= asset('app/controllers/clientesController.js') ?>"></script>

</html>

