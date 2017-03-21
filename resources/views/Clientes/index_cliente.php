<!doctype html>
<html lang="es-ES" ng-app="softver-aqua">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cliente</title>

    <link href="<?= asset('css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= asset('css/font-awesome.min.css') ?>" rel="stylesheet">
    <link href="<?= asset('css/index.css') ?>" rel="stylesheet">
    <link href="<?= asset('css/bootstrap-datetimepicker.min.css') ?>" rel="stylesheet">
    <link href="<?= asset('css/angucomplete-alt.css') ?>" rel="stylesheet">
    <link href="<?= asset('css/style_generic_app.css') ?>" rel="stylesheet">

</head>

<body>
        <div ng-controller="clientesController">

            <div class="col-xs-12" style="margin-top: 15px;">
                <div class="col-sm-6 col-xs-12">
                    <div class="form-group has-feedback">
                        <input type="text" class="form-control" id="t_busqueda" placeholder="BUSCAR..." ng-model="busqueda" ng-keypress="initLoad(1)">
                        <span class="glyphicon glyphicon-search form-control-feedback" aria-hidden="true"></span>
                    </div>
                </div>
                <div class="col-sm-6 col-xs-12">
                    <button type="button" class="btn btn-primary" style="float: right;" ng-click="showModalAddCliente()">
                        Agregar <span class="glyphicon glyphicon glyphicon-plus" aria-hidden="true"></span>
                    </button>
                </div>
            </div>

            <div class="col-xs-12">
                <table class="table table-responsive table-striped table-hover table-condensed">
                    <thead class="bg-primary">
                    <tr>
                        <th class="text-center" style="width: 10%;">CI / RUC</th>
                        <th class="text-center" style="width: 10%;">Fecha Ingr.</th>
                        <th class="text-center" style="">Razón Social / Nombre y Apellidos</th>
                        <th class="text-center" style="width: 8%;">Celular</th>
                        <th class="text-center" style="width: 20%;">Dirección</th>
                        <th class="text-center" style="width: 7%;">Estado</th>
                        <th class="text-center" style="width: 16%;">Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr dir-paginate="item in clientes|orderBy:sortKey:reverse| itemsPerPage:10" total-items="totalItems" ng-cloak >
                            <td>{{item.numdocidentific}}</td>
                            <td>{{item.fechaingreso | formatDate}}</td>
                            <td>{{item.razonsocial}}</td>
                            <td>{{item.celphone}}</td>
                            <td>{{item.direccion}}</td>
                            <td ng-if="item.estado == true">
                                <span class="label label-primary" style="font-size: 14px !important;">Activo</span>
                            </td>
                            <td ng-if="item.estado == false">
                                <span class="label label-warning" style="font-size: 14px !important;">Inactivo</span>
                            </td>
                            <td  class="text-center">
                                <button type="button" class="btn btn-info btn-sm" ng-click="showModalInfoCliente(item)">
                                    <i class="fa fa-lg fa-info-circle" aria-hidden="true"></i>
                                </button>
                                <button type="button" class="btn btn-warning btn-sm" ng-click="showModalEditCliente(item)">
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
                <dir-pagination-controls

                        on-page-change="pageChanged(newPageNumber)"

                        template-url="dirPagination.html"

                        class="pull-right"
                        max-size="10"
                        direction-links="true"
                        boundary-links="true" >

                </dir-pagination-controls>

            </div>


            <div class="modal fade" tabindex="-1" role="dialog" id="modalAddCliente">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <form class="form-horizontal" name="formEmployee" novalidate="">
                            <div class="modal-header modal-header-primary">
                                <div class="col-md-6 col-xs-12">
                                    <h4 class="modal-title">{{title_modal_cliente}}</h4>
                                </div>
                                <div class="col-md-5 col-xs-12">
                                    <div class="input-group">
                                        <span class="input-group-addon">Fecha de Ingreso:</span>
                                        <input type="text" class="datepicker form-control" name="t_fecha_ingreso" id="t_fecha_ingreso" ng-model="t_fecha_ingreso" ng-required="true">
                                    </div>
                                    <span class="help-block error"
                                          ng-show="formEmployee.t_fecha_ingreso.$invalid && formEmployee.t_fecha_ingreso.$touched">La Fecha de Ingreso es requerida</span>
                                </div>
                                <div class="col-md-1 col-xs-12 text-right" style="padding: 0;">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                </div>
                            </div>
                            <div class="modal-body">

                                <div class="row">

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

                                                        remote-url = "{{API_URL}}cliente/getIdentify/"

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

                                    <div class="col-xs-12" style="margin-top: 5px;">
                                        <div class="col-md-6 col-xs-12">
                                            <div class="input-group">
                                                <span class="input-group-addon">Teléfono Principal Trabajo: </span>
                                                <input type="text" class="form-control" name="telefonoprincipaltrabajo" id="telefonoprincipaltrabajo"
                                                       ng-model="telefonoprincipaltrabajo" ng-minlength="9" ng-maxlength="16" ng-pattern="/^([0-9-\(\)]+)$/" >
                                            </div>
                                            <span class="help-block error"
                                                  ng-show="formEmployee.telefonoprincipaltrabajo.$invalid && formEmployee.telefonoprincipaltrabajo.$error.maxlength">La longitud máxima es de 16 números</span>
                                            <span class="help-block error"
                                                  ng-show="formEmployee.telefonoprincipaltrabajo.$invalid && formEmployee.telefonoprincipaltrabajo.$error.pattern">El Teléfono debe ser solo números, guion y espacios</span>
                                            <span class="help-block error"
                                                  ng-show="formEmployee.telefonoprincipaltrabajo.$invalid && formEmployee.telefonoprincipaltrabajo.$error.minlength">La longitud mínima es de 9 caracteres</span>
                                        </div>

                                        <div class="col-md-6 col-xs-12">
                                            <div class="input-group">
                                                <span class="input-group-addon">Teléfono Secundario Trabajo: </span>
                                                <input type="text" class="form-control" name="telefonosecundariotrabajo" id="telefonosecundariotrabajo"
                                                       ng-model="telefonosecundariotrabajo" ng-minlength="9"  ng-maxlength="16" ng-pattern="/^([0-9-\(\)]+)$/" >
                                            </div>
                                            <span class="help-block error"
                                                  ng-show="formEmployee.telefonosecundariotrabajo.$invalid && formEmployee.telefonosecundariotrabajo.$error.maxlength">La longitud máxima es de 16 números</span>
                                            <span class="help-block error"
                                                  ng-show="formEmployee.telefonosecundariotrabajo.$invalid && formEmployee.telefonosecundariotrabajo.$error.pattern">El Teléfono debe ser solo números, guion y espacios</span>
                                            <span class="help-block error"
                                                  ng-show="formEmployee.telefonosecundariotrabajo.$invalid && formEmployee.telefonosecundariotrabajo.$error.minlength">La longitud mínima es de 9 caracteres</span>
                                        </div>
                                    </div>

                                    <div class="col-xs-12" style="margin-top: 5px;">
                                        <div class="col-xs-12">
                                            <div class="input-group">
                                                <span class="input-group-addon">Dirección Trabajo: </span>
                                                <input type="text" class="form-control" name="direcciontrabajo" id="direcciontrabajo" ng-model="direcciontrabajo" ng-maxlength="256">
                                            </div>
                                            <span class="help-block error"
                                                  ng-show="formEmployee.direcciontrabajo.$invalid && formEmployee.direcciontrabajo.$error.maxlength">La longitud máxima es de 256 caracteres</span>
                                        </div>
                                    </div>

                                    <div class="col-xs-12" style="margin-top: 5px;">
                                        <div class="col-sm-6 col-xs-12">
                                            <div class="input-group">
                                                <span class="input-group-addon">Tipo Cliente: </span>
                                                <select class="form-control" name="s_tipocliente" id="s_tipocliente" ng-model="s_tipocliente"
                                                        ng-options="value.id as value.label for value in tipocliente" required></select>
                                            </div>
                                            <span class="help-block error"
                                                  ng-show="formEmployee.s_tipocliente.$invalid && formEmployee.s_tipocliente.$touched">El Tipo Cliente es requerido</span>
                                        </div>

                                        <div class="col-sm-6 col-xs-12">
                                            <div class="input-group">
                                                <span class="input-group-addon">Impuesto IVA: </span>
                                                <select class="form-control" name="iva" id="iva" ng-model="iva"
                                                        ng-options="value.id as value.label for value in imp_iva" required></select>
                                            </div>
                                            <span class="help-block error"
                                                  ng-show="formEmployee.iva.$invalid && formEmployee.iva.$touched">El Impuesto IVA es requerido</span>
                                        </div>
                                    </div>

                                    <div class="col-xs-12" style="margin-top: 5px;">
                                        <div class="col-xs-12">
                                            <div class="input-group">
                                                <span class="input-group-addon">Cuenta Contable: </span>
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

                                </div>

                            </div>
                        </form>
                        <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            Cancelar <span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span>
                        </button>
                        <button type="button" class="btn btn-success" id="btn-save" ng-click="saveCliente()" ng-disabled="formEmployee.$invalid">
                            Guardar <span class="glyphicon glyphicon-floppy-saved" aria-hidden="true"></span>
                        </button>
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

            <div class="modal fade" tabindex="-1" role="dialog" id="modalDeleteCliente">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header modal-header-danger">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Confirmación</h4>
                        </div>
                        <div class="modal-body">
                            <span>Realmente desea eliminar el cliente: <strong>"{{nom_cliente}}"</strong> </span>
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
                            </div>
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

                                            <div class="col-sm-6 col-xs-12">
                                                <div class="input-group">
                                                    <span class="input-group-addon">RUC/CI: </span>
                                                    <input type="text" class="form-control" name="documentoidentidad_cliente" id="documentoidentidad_cliente"
                                                        ng-model="documentoidentidad_cliente" readonly/>
                                                </div>
                                            </div>

                                            <div class="col-sm-6 col-xs-12">
                                                <div class="input-group">
                                                    <span class="input-group-addon">Cliente: </span>
                                                    <input type="text" class="form-control" name="nom_cliente" id="nom_cliente"
                                                           ng-model="nom_cliente" readonly/>
                                                </div>
                                                <input type="hidden" ng-model="h_codigocliente">
                                            </div>

                                            <div class="col-xs-12" style="margin-top: 5px;">
                                                <div class="input-group">
                                                    <span class="input-group-addon">Dirección Domicilio: </span>
                                                    <input type="text" class="form-control" name="direcc_cliente" id="direcc_cliente"
                                                           ng-model="direcc_cliente" readonly/>
                                                </div>
                                            </div>

                                            <div class="col-sm-6 col-xs-12" style="margin-top: 5px;">
                                                <div class="input-group">
                                                    <span class="input-group-addon">Teléfono Domicilio: </span>
                                                    <input type="text" class="form-control" name="telf_cliente" id="telf_cliente"
                                                           ng-model="telf_cliente" readonly/>
                                                </div>
                                            </div>

                                            <div class="col-sm-6 col-xs-12" style="margin-top: 5px;">
                                                <div class="input-group">
                                                    <span class="input-group-addon">Celular: </span>
                                                    <input type="text" class="form-control" name="celular_cliente" id="celular_cliente"
                                                           ng-model="celular_cliente" readonly/>
                                                </div>
                                            </div>

                                            <div class="col-sm-6 col-xs-12" style="margin-top: 5px;">
                                                <div class="input-group">
                                                    <span class="input-group-addon">Teléfono Trabajo: </span>
                                                    <input type="text" class="form-control" name="telf_trab_cliente" id="telf_trab_cliente"
                                                           ng-model="telf_trab_cliente" readonly/>
                                                </div>
                                            </div>

                                            <div class="col-sm-6 col-xs-12" style="margin-top: 5px;">
                                                <div class="input-group">
                                                    <span class="input-group-addon">Tipo Cliente: </span>
                                                    <input type="text" class="form-control" name="tipo_tipo_cliente" id="tipo_tipo_cliente"
                                                           ng-model="tipo_tipo_cliente" readonly/>
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

                                                    <div class="input-group">
                                                        <span class="input-group-addon">RUC/CI: </span>
                                                        <input class="form-control" type="text" name="documentoidentidad_cliente_setnombre" id="documentoidentidad_cliente_setnombre"
                                                               ng-model="documentoidentidad_cliente_setnombre" disabled >
                                                    </div>

                                                </div>

                                                <div class="col-sm-6 col-xs-12">

                                                    <div class="input-group">
                                                        <span class="input-group-addon">Cliente: </span>
                                                        <input class="form-control" type="text" name="nom_cliente_setnombre" id="nom_cliente_setnombre"
                                                               ng-model="nom_cliente_setnombre" disabled >
                                                    </div>

                                                    <input type="hidden" ng-model="h_codigocliente_setnombre">
                                                </div>
                                            </div>

                                            <div class="col-xs-12" style="padding: 0; margin-top: 5px;">
                                                <div class="col-xs-12">

                                                    <div class="input-group">
                                                        <span class="input-group-addon">Dirección Domicilio: </span>
                                                        <input class="form-control" type="text" name="direcc_cliente_setnombre" id="direcc_cliente_setnombre"
                                                               ng-model="direcc_cliente_setnombre" disabled >
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="col-xs-12" style="padding: 0; margin-top: 5px;">
                                                <div class="col-sm-4 col-xs-12">

                                                    <div class="input-group">
                                                        <span class="input-group-addon">Celular: </span>
                                                        <input class="form-control" type="text" name="celular_cliente_setnombre" id="celular_cliente_setnombre"
                                                               ng-model="celular_cliente_setnombre" disabled >
                                                    </div>

                                                </div>

                                                <div class="col-sm-4 col-xs-12">

                                                    <div class="input-group">
                                                        <span class="input-group-addon">Teléfono Domicilio: </span>
                                                        <input class="form-control" type="text" name="telf_cliente_setnombre" id="telf_cliente_setnombre"
                                                               ng-model="telf_cliente_setnombre" disabled >
                                                    </div>

                                                </div>

                                                <div class="col-sm-4 col-xs-12">

                                                    <div class="input-group">
                                                        <span class="input-group-addon">Teléfono Trabajo: </span>
                                                        <input class="form-control" type="text" name="telf_trab_cliente_setnombre" id="telf_trab_cliente_setnombre"
                                                               ng-model="telf_trab_cliente_setnombre" disabled >
                                                    </div>

                                                </div>
                                            </div>


                                            <!--<div class="col-xs-12" style="padding: 0;">
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
                                            </div>-->
                                        </fieldset>
                                    </div>

                                    <div class="col-xs-12" style="padding: 2%; margin-top: -25px !important;">
                                        <fieldset>
                                            <legend style="font-size: 16px; font-weight: bold;">Datos de Suministro</legend>

                                            <div class="col-sm-6 col-xs-12 error">

                                                <div class="input-group">
                                                    <span class="input-group-addon">Suministros: </span>
                                                    <select class="form-control" name="s_suministro_setnombre" id="s_suministro_setnombre"
                                                            ng-model="s_suministro_setnombre" ng-options="value.id as value.label for value in suministro_setN"
                                                            ng-change="showInfoSuministroForSetName()" required ></select>
                                                </div>

                                                <span class="help-block error"
                                                      ng-show="formSetNombre.s_suministro_setnombre.$invalid && formSetNombre.s_suministro_setnombre.$touched">
                                                                Seleccione un Suministro</span>

                                            </div>

                                            <div class="col-sm-6 col-xs-12">

                                                <div class="input-group">
                                                    <span class="input-group-addon">Zona: </span>
                                                    <input class="form-control" type="text" name="zona_setnombre" id="zona_setnombre"
                                                           ng-model="zona_setnombre" disabled >
                                                </div>

                                            </div>

                                            <div class="col-sm-6 col-xs-12" style="margin-top: 5px;">

                                                <div class="input-group">
                                                    <span class="input-group-addon">Transversal: </span>
                                                    <input class="form-control" type="text" name="transversal_setnombre" id="transversal_setnombre"
                                                           ng-model="transversal_setnombre" disabled >
                                                </div>

                                            </div>

                                            <div class="col-sm-6 col-xs-12" style="margin-top: 5px;">

                                                <div class="input-group">
                                                    <span class="input-group-addon">Tarifa: </span>
                                                    <input class="form-control" type="text" name="tarifa_setnombre" id="tarifa_setnombre"
                                                           ng-model="tarifa_setnombre" disabled >
                                                </div>

                                            </div>

                                        </fieldset>
                                    </div>

                                    <div class="col-xs-12" style="padding: 2%; margin-top: -20px !important;">
                                        <fieldset>
                                            <legend style="font-size: 16px; font-weight: bold;">Datos del nuevo Cliente</legend>

                                            <div class="col-xs-12" style="padding: 0;">
                                                <div class="col-sm-6 col-xs-12 error">

                                                    <div class="input-group">
                                                        <span class="input-group-addon">RUC/CI: </span>
                                                        <angucomplete-alt
                                                                id="s_ident_new_client_setnombre"
                                                                pause="400"
                                                                selected-object="showInfoClienteForSetName"

                                                                remote-url="{{API_URL}}cliente/getIdentifyClientes/"

                                                                title-field="numdocidentific"

                                                                minlength="1"
                                                                input-class="form-control"
                                                                match-class="highlight"
                                                                field-required="true"
                                                                input-name="s_ident_new_client_setnombre"
                                                                disable-input="guardado"
                                                                text-searching="Buscando RUC Clientes"
                                                                text-no-results="RUC no encontrado"
                                                                initial-value="numdocidentific"
                                                        />
                                                    </div>
                                                    <input type="hidden" id="h_codigocliente_new" ng-model="h_codigocliente_new">

                                                    <span class="help-block error"
                                                          ng-show="formSetNombre.s_ident_new_client_setnombre.$invalid && formSetNombre.s_ident_new_client_setnombre.$error.pattern">
                                                                Seleccione un Cliente</span>

                                                </div>
                                                <div class="col-sm-6 col-xs-12">

                                                    <div class="input-group">
                                                        <span class="input-group-addon">Cliente: </span>
                                                        <input class="form-control" type="text" name="nom_new_cliente_setnombre" id="nom_new_cliente_setnombre"
                                                               ng-model="nom_new_cliente_setnombre" disabled >
                                                    </div>
                                                    <input type="hidden" ng-model="h_new_codigocliente_setnombre">

                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-xs-12" style="margin-top: 5px;">

                                                <div class="input-group">
                                                    <span class="input-group-addon">Dirección Domicilio: </span>
                                                    <input class="form-control" type="text" name="direcc_new_cliente_setnombre" id="direcc_new_cliente_setnombre"
                                                           ng-model="direcc_new_cliente_setnombre" disabled >
                                                </div>

                                            </div>

                                            <div class="col-sm-6 col-xs-12" style="margin-top: 5px;">

                                                <div class="input-group">
                                                    <span class="input-group-addon">Teléfono Domicilio: </span>
                                                    <input class="form-control" type="text" name="telf_new_cliente_setnombre" id="telf_new_cliente_setnombre"
                                                           ng-model="telf_new_cliente_setnombre" disabled >
                                                </div>

                                            </div>

                                            <div class="col-sm-6 col-xs-12" style="margin-top: 5px;">

                                                <div class="input-group">
                                                    <span class="input-group-addon">Celular: </span>
                                                    <input class="form-control" type="text" name="celular_new_cliente_setnombre" id="celular_new_cliente_setnombre"
                                                           ng-model="celular_new_cliente_setnombre" disabled >
                                                </div>

                                            </div>

                                            <div class="col-sm-6 col-xs-12" style="margin-top: 5px;">

                                                <div class="input-group">
                                                    <span class="input-group-addon">Teléfono Trabajo: </span>
                                                    <input class="form-control" type="text" name="telf_trab_new_cliente_setnombre" id="telf_trab_new_cliente_setnombre"
                                                           ng-model="telf_trab_new_cliente_setnombre" disabled >
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
                                    ng-click="procesarSolicitud()" disabled>
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

                                                    <div class="input-group">
                                                        <span class="input-group-addon">RUC/CI: </span>
                                                        <input class="form-control" type="text" name="documentoidentidad_cliente_mant" id="documentoidentidad_cliente_mant"
                                                               ng-model="documentoidentidad_cliente_mant" disabled >
                                                    </div>

                                                </div>

                                                <div class="col-sm-6 col-xs-12">

                                                    <div class="input-group">
                                                        <span class="input-group-addon">Cliente: </span>
                                                        <input class="form-control" type="text" name="nom_cliente_mant" id="nom_cliente_mant"
                                                               ng-model="nom_cliente_mant" disabled >
                                                    </div>

                                                    <input type="hidden" ng-model="h_codigocliente">
                                                </div>
                                            </div>

                                            <div class="col-xs-12" style="padding: 0; margin-top: 5px;">
                                                <div class="col-xs-12">

                                                    <div class="input-group">
                                                        <span class="input-group-addon">Dirección Domicilio: </span>
                                                        <input class="form-control" type="text" name="direcc_cliente_mant" id="direcc_cliente_mant"
                                                               ng-model="direcc_cliente_mant" disabled >
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="col-xs-12" style="padding: 0; margin-top: 5px;">
                                                <div class="col-sm-4 col-xs-12">

                                                    <div class="input-group">
                                                        <span class="input-group-addon">Celular: </span>
                                                        <input class="form-control" type="text" name="celular_cliente_mant" id="celular_cliente_mant"
                                                               ng-model="celular_cliente_mant" disabled >
                                                    </div>

                                                </div>

                                                <div class="col-sm-4 col-xs-12">

                                                    <div class="input-group">
                                                        <span class="input-group-addon">Teléfono Domicilio: </span>
                                                        <input class="form-control" type="text" name="telf_cliente_mant" id="telf_cliente_mant"
                                                               ng-model="telf_cliente_mant" disabled >
                                                    </div>

                                                </div>

                                                <div class="col-sm-4 col-xs-12">

                                                    <div class="input-group">
                                                        <span class="input-group-addon">Teléfono Trabajo: </span>
                                                        <input class="form-control" type="text" name="telf_trab_cliente_mant" id="telf_trab_cliente_mant"
                                                               ng-model="telf_trab_cliente_mant" disabled >
                                                    </div>

                                                </div>
                                            </div>



                                            <!--<div class="col-xs-12" style="padding: 0;">
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
                                            </div>-->
                                        </fieldset>
                                    </div>

                                    <div class="col-xs-12" style="padding: 2%; margin-top: -25px !important;">
                                        <fieldset>
                                            <legend style="font-size: 16px; font-weight: bold;">Datos de Suministro</legend>

                                            <div class="col-sm-6 col-xs-12 error ">

                                                <div class="input-group">
                                                    <span class="input-group-addon">Suministros: </span>
                                                    <select class="form-control" name="s_suministro_mant" id="s_suministro_mant"
                                                            ng-model="s_suministro_mant" ng-options="value.id as value.label for value in suministro_mant"
                                                            ng-change="showInfoSuministro()" required></select>
                                                </div>
                                                <span class="help-block error"
                                                          ng-show="formMant.s_suministro_mant.$invalid && formMant.s_suministro_mant.$touched">Suministro es requerida</span>
                                                <!--<span class="help-block error"
                                                      ng-show="formMant.s_suministro_mant.$invalid && formMant.s_suministro_mant.$error.pattern">
                                                                Seleccione un Suministro</span>-->

                                            </div>

                                            <div class="col-sm-6 col-xs-12">

                                                <div class="input-group">
                                                    <span class="input-group-addon">Zona: </span>
                                                    <input class="form-control" type="text" name="zona_mant" id="zona_mant"
                                                           ng-model="zona_mant" disabled >
                                                </div>

                                            </div>

                                            <div class="col-sm-6 col-xs-12" style="margin-top: 5px;">

                                                <div class="input-group">
                                                    <span class="input-group-addon">Transversal: </span>
                                                    <input class="form-control" type="text" name="transversal_mant" id="transversal_mant"
                                                           ng-model="transversal_mant" disabled >
                                                </div>


                                            </div>

                                            <div class="col-sm-6 col-xs-12" style="margin-top: 5px;">

                                                <div class="input-group">
                                                    <span class="input-group-addon">Tarifa: </span>
                                                    <input class="form-control" type="text" name="tarifa_mant" id="tarifa_mant"
                                                           ng-model="tarifa_mant" disabled >
                                                </div>

                                            </div>

                                        </fieldset>
                                    </div>

                                    <div class="col-xs-12">
                                        <div class="col-xs-12 error">
                                        <textarea class="form-control" name="t_observacion_mant" id="t_observacion_mant" ng-model="t_observacion_mant" rows="3"
                                                  ng-required="true" placeholder="Observación"></textarea>
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
                                <h4 class="modal-title">Otro Tipo de Solicitud Nro: {{num_solicitud_otro}}</h4>
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

                                                    <div class="input-group">
                                                        <span class="input-group-addon">RUC/CI: </span>
                                                        <input class="form-control" type="text" name="documentoidentidad_cliente_otro" id="documentoidentidad_cliente_otro"
                                                               ng-model="documentoidentidad_cliente_otro" disabled >
                                                    </div>

                                                </div>

                                                <div class="col-sm-6 col-xs-12">

                                                    <div class="input-group">
                                                        <span class="input-group-addon">Cliente: </span>
                                                        <input class="form-control" type="text" name="nom_cliente_otro" id="nom_cliente_otro"
                                                               ng-model="nom_cliente_otro" disabled >
                                                    </div>

                                                    <input type="hidden" ng-model="h_codigocliente">
                                                </div>
                                            </div>

                                            <div class="col-xs-12" style="padding: 0; margin-top: 5px;">
                                                <div class="col-xs-12">

                                                    <div class="input-group">
                                                        <span class="input-group-addon">Dirección Domicilio: </span>
                                                        <input class="form-control" type="text" name="direcc_cliente_otro" id="direcc_cliente_otro"
                                                               ng-model="direcc_cliente_otro" disabled >
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="col-xs-12" style="padding: 0; margin-top: 5px;">
                                                <div class="col-sm-4 col-xs-12">

                                                    <div class="input-group">
                                                        <span class="input-group-addon">Celular: </span>
                                                        <input class="form-control" type="text" name="celular_cliente_otro" id="celular_cliente_otro"
                                                               ng-model="celular_cliente_otro" disabled >
                                                    </div>

                                                </div>

                                                <div class="col-sm-4 col-xs-12">

                                                    <div class="input-group">
                                                        <span class="input-group-addon">Teléfono Domicilio: </span>
                                                        <input class="form-control" type="text" name="telf_cliente_otro" id="telf_cliente_otro"
                                                               ng-model="telf_cliente_otro" disabled >
                                                    </div>

                                                </div>

                                                <div class="col-sm-4 col-xs-12">

                                                    <div class="input-group">
                                                        <span class="input-group-addon">Teléfono Trabajo: </span>
                                                        <input class="form-control" type="text" name="telf_trab_cliente_otro" id="telf_trab_cliente_otro"
                                                               ng-model="telf_trab_cliente_otro" disabled >
                                                    </div>

                                                </div>
                                            </div>


                                            <!--<div class="col-xs-12" style="padding: 0;">
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
                                            </div>-->
                                        </fieldset>
                                    </div>

                                    <div class="col-xs-12">
                                        <div class="col-xs-12 error">
                                            <textarea class="form-control" id="t_observacion_otro" ng-model="t_observacion_otro" rows="3"
                                                      ng-required="true" placeholder="Descripción"></textarea>
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

                                            <div class="col-sm-6 col-xs-12">

                                                <div class="input-group">
                                                    <span class="input-group-addon"> Nro Suministro: </span>
                                                    <input type="text" class="form-control" id="t_suministro_nro" ng-model="t_suministro_nro" disabled>
                                                </div>

                                            </div>
                                            <div class="col-sm-6 col-xs-12">

                                                <div class="input-group">
                                                    <span class="input-group-addon">Tarifa: </span>
                                                    <select name="s_suministro_tarifa" id="s_suministro_tarifa" class="form-control" ng-model="s_suministro_tarifa"
                                                            ng-options="value.id as value.label for value in tarifas" required></select>
                                                </div>
                                                <span class="help-block error"
                                                      ng-show="formProcessSuministro.s_suministro_tarifa.$invalid && formProcessSuministro.s_suministro_tarifa.$touched" >
                                                                Seleccione una Tarifa</span>
                                            </div>

                                            <div class="col-sm-6 col-xs-12" style="margin-top: 5px;">

                                                <div class="input-group">
                                                    <span class="input-group-addon"> Zona: </span>
                                                    <select name="s_suministro_zona" id="s_suministro_zona" class="form-control" ng-model="s_suministro_zona"
                                                            ng-options="value.id as value.label for value in barrios"
                                                            ng-change="getCalles()" required></select>
                                                </div>
                                                <span class="help-block error"
                                                      ng-show="formProcessSuministro.s_suministro_zona.$invalid && formProcessSuministro.s_suministro_zona.$touched">
                                                                Seleccione una Zona</span>
                                            </div>

                                            <div class="col-sm-6 col-xs-12" style="margin-top: 5px;">

                                                <div class="input-group">
                                                    <span class="input-group-addon"> Transversal: </span>
                                                    <select name="s_suministro_transversal" id="s_suministro_transversal" class="form-control" ng-model="s_suministro_transversal"
                                                            ng-options="value.id as value.label for value in calles" required></select>
                                                </div>
                                                <span class="help-block error"
                                                      ng-show="formProcessSuministro.s_suministro_transversal.$invalid && formProcessSuministro.s_suministro_transversal.$touched">
                                                                Seleccione una Transversal</span>
                                            </div>

                                            <div class="col-sm-6 col-xs-12 error" style="margin-top: 5px;">

                                                <div class="input-group">
                                                    <span class="input-group-addon"> Dirección Instalac.: </span>
                                                    <input type="text" class="form-control" name="t_suministro_direccion" id="t_suministro_direccion" ng-model="t_suministro_direccion"
                                                           ng-required="true">
                                                </div>
                                                <span class="help-block error"
                                                      ng-show="formProcessSuministro.t_suministro_direccion.$invalid && formProcessSuministro.t_suministro_direccion.$touched">
                                                            La Dirección es requerida</span>
                                            </div>

                                            <div class="col-sm-6 col-xs-12 error" style="margin-top: 5px;">

                                                <div class="input-group">
                                                    <span class="input-group-addon"> Teléfono Instalac.: </span>
                                                    <input type="text" class="form-control" name="t_suministro_telf" id="t_suministro_telf"
                                                           ng-model="t_suministro_telf" ng-required="true" ng-keypress="onlyNumber($event)" ng-minlength="9" ng-pattern="/^([0-9]+)$/">
                                                </div>

                                                <span class="help-block error"
                                                      ng-show="formProcessSuministro.t_suministro_telf.$invalid && formProcessSuministro.t_suministro_telf.$error.pattern">Solo números</span>
                                                <span class="help-block error"
                                                      ng-show="formProcessSuministro.t_suministro_telf.$invalid && formProcessSuministro.t_suministro_telf.$touched">
                                                            El Teléfono es requerido</span>
                                            </div>

                                        </fieldset>
                                    </div>

                                    <div class="col-xs-12" style="padding: 2%; margin-top: -25px;">
                                        <fieldset>
                                            <legend style="font-size: 16px; font-weight: bold;">Datos Costo</legend>

                                            <div class="col-xs-12" style="padding: 2%; margin-top: -35px;">
                                                <fieldset>
                                                    <legend style="font-size: 14px; font-weight: bold;">Acometida</legend>

                                                    <div class="col-sm-6 col-xs-12 error">

                                                        <div class="input-group">
                                                            <span class="input-group-addon"> Agua Potable: </span>
                                                            <input type="text" class="form-control" name="t_suministro_aguapotable" id="t_suministro_aguapotable" ng-model="t_suministro_aguapotable"
                                                                   ng-blur="calculateTotalSuministro()" ng-required="true" ng-keypress="onlyDecimal($event)">
                                                        </div>
                                                        <span class="help-block error"
                                                              ng-show="formProcessSuministro.t_suministro_aguapotable.$invalid && formProcessSuministro.t_suministro_aguapotable.$touched">
                                                            El Agua Potable es requerido</span>

                                                    </div>

                                                    <div class="col-sm-6 col-xs-12 error">

                                                        <div class="input-group">
                                                            <span class="input-group-addon"> Alcantarillado: </span>
                                                            <input type="text" class="form-control" name="t_suministro_alcantarillado" id="t_suministro_alcantarillado" ng-model="t_suministro_alcantarillado"
                                                                   ng-blur="calculateTotalSuministro()" ng-required="true" ng-keypress="onlyDecimal($event)">
                                                        </div>
                                                        <span class="help-block error"
                                                              ng-show="formProcessSuministro.t_suministro_alcantarillado.$invalid && formProcessSuministro.t_suministro_alcantarillado.$touched">
                                                            El Alcantarillado es requerido</span>
                                                    </div>

                                                    <div class="col-sm-6 col-xs-12 error" style="margin-top: 5px;">

                                                        <div class="input-group">
                                                            <span class="input-group-addon"> Garantía Apertura de Calle: </span>
                                                            <input type="text" class="form-control" name="t_suministro_garantia" id="t_suministro_garantia"
                                                                   ng-model="t_suministro_garantia" ng-required="true" ng-keypress="onlyDecimal($event)">
                                                        </div>
                                                        <span class="help-block error"
                                                              ng-show="formProcessSuministro.t_suministro_garantia.$invalid && formProcessSuministro.t_suministro_garantia.$touched">
                                                            La Garantía es requerida</span>
                                                    </div>
                                                </fieldset>
                                            </div>

                                            <div class="col-xs-12" style="padding: 2%; margin-top: -35px;">
                                                <fieldset>
                                                    <legend style="font-size: 14px; font-weight: bold;">Medidor</legend>

                                                    <div class="col-sm-4 col-xs-12">

                                                        <div class="input-group">
                                                            <span class="input-group-addon"> ¿Cliente tiene Medidor?: </span>
                                                            <input type="checkbox" id="t_suministro_medidor" ng-model="t_suministro_medidor"
                                                                   ng-click="deshabilitarMedidor()">
                                                        </div>

                                                    </div>

                                                    <div class="col-sm-4 col-xs-12">

                                                        <div class="input-group">
                                                            <span class="input-group-addon"> Marca: </span>
                                                            <input type="text" class="form-control" id="t_suministro_marca" ng-model="t_suministro_marca">
                                                        </div>

                                                    </div>

                                                    <div class="col-sm-4 col-xs-12 form-group">

                                                        <div class="input-group">
                                                            <span class="input-group-addon"> Costo: </span>
                                                            <input type="text" class="form-control" id="t_suministro_costomedidor" ng-model="t_suministro_costomedidor"
                                                                   ng-blur="calculateTotalSuministro()" ng-keypress="onlyDecimal($event)">
                                                        </div>

                                                    </div>

                                                </fieldset>
                                            </div>

                                            <div class="col-xs-12" style="padding: 2%; margin-top: -40px;">
                                                <fieldset>
                                                    <legend style="font-size: 14px; font-weight: bold;">Total</legend>

                                                    <div class="col-sm-6 col-xs-12 error">

                                                        <div class="input-group">
                                                            <span class="input-group-addon"> Cuota Inicial: </span>
                                                            <input type="text" class="form-control" name="t_suministro_cuota" id="t_suministro_cuota" ng-model="t_suministro_cuota"
                                                                   ng-blur="calculateTotalSuministro()" ng-required="true" ng-keypress="onlyDecimal($event)">
                                                        </div>
                                                        <span class="help-block error"
                                                              ng-show="formProcessSuministro.t_suministro_cuota.$invalid && formProcessSuministro.t_suministro_cuota.$touched">
                                                            La Couta Inicial es requerida</span>
                                                    </div>

                                                    <div class="col-sm-6 col-xs-12 error">

                                                        <div class="input-group">
                                                            <span class="input-group-addon"> Crédito: </span>
                                                            <select name="s_suministro_credito" id="s_suministro_credito" class="form-control" ng-model="s_suministro_credito"
                                                                    ng-options="value.id as value.label for value in creditos"
                                                                    ng-change="calculateTotalSuministro()"  required></select>
                                                        </div>
                                                        <span class="help-block error"
                                                              ng-show="formProcessSuministro.s_suministro_credito.$invalid && formProcessSuministro.s_suministro_credito.$touched">
                                                                Seleccione un Crédito</span>
                                                    </div>
                                                </fieldset>
                                            </div>

                                            <div class="col-xs-12">
                                                <div class="col-sm-6 col-xs-12 text-center" id="info_partial" style="font-size: 14px; display: none;">
                                                    Total: <span style="font-weight: bold;">$ {{total_partial}}</span> a
                                                    <span style="font-weight: bold;">{{credit_cant}}</span> meses plazo
                                                </div>
                                                <div class="col-sm-6 col-xs-12 text-center" id="info_total" style="font-size: 14px; display: none;">
                                                    Cuotas de: <span style="font-weight: bold;">$ {{total_suministro}}</span> mensuales
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


<script src="<?= asset('app/lib/angular/ng-file-upload-shim.min.js') ?>"></script>
<script src="<?= asset('app/lib/angular/ng-file-upload.min.js') ?>"></script>


<script src="<?= asset('js/jquery.min.js') ?>"></script>
<script src="<?= asset('js/bootstrap.min.js') ?>"></script>
<script src="<?= asset('js/moment.min.js') ?>"></script>
<script src="<?= asset('js/es.js') ?>"></script>
<script src="<?= asset('js/bootstrap-datetimepicker.min.js') ?>"></script>
<script src="<?= asset('app/lib/angular/angucomplete-alt.min.js') ?>"></script>
<script src="<?= asset('app/lib/angular/dirPagination.js') ?>"></script>

<script src="<?= asset('app/app.js') ?>"></script>
<script src="<?= asset('app/controllers/clientesController.js') ?>"></script>


</html>