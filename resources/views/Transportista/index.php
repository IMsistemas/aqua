<!doctype html>
<html lang="es-ES" ng-app="softver-aqua">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Transportista</title>

    <link href="<?= asset('css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= asset('css/font-awesome.min.css') ?>" rel="stylesheet">
    <link href="<?= asset('css/index.css') ?>" rel="stylesheet">
    <link href="<?= asset('css/bootstrap-datetimepicker.min.css') ?>" rel="stylesheet">
    <link href="<?= asset('css/angucomplete-alt.css') ?>" rel="stylesheet">
    <link href="<?= asset('css/style_generic_app.css') ?>" rel="stylesheet">

    <style>
        .modal-body {
            max-height: calc(100vh - 210px);
            overflow-y: auto;
        }
    </style>

</head>

<body>
<div ng-controller="transportistaController">

    <div class="col-xs-12">

        <h4>Gestión de Transportistas</h4>

        <hr>

    </div>

    <div class="col-xs-12" style="margin-top: 5px;">

        <div class="col-sm-6 col-xs-8">
            <div class="form-group has-feedback">
                <input type="text" class="form-control" id="busqueda" placeholder="BUSCAR..." ng-model="busqueda" ng-keyup="initLoad(1)">
                <span class="glyphicon glyphicon-search form-control-feedback" aria-hidden="true"></span>
            </div>
        </div>

        <div class="col-sm-6 col-xs-4">
            <button type="button" class="btn btn-primary" id="btnAgregar" style="float: right;" ng-click="toggle('add', 0)">Agregar  <span class="glyphicon glyphicon-plus" aria-hidden="true"></button>
        </div>

        <div class="col-xs-12" style="font-size: 12px !important;">

            <table class="table table-responsive table-striped table-hover table-condensed table-bordered">
                <thead class="bg-primary">
                <tr>
                    <th style="width: 4%">NO</th>
                    <th style="width: 10%">RUC / CI</th>
                    <th>RAZON SOCIAL</th>
                    <th style="width: 8%">PLACA</th>
                    <th style="width: 15%">EMAIL</th>
                    <th style="width: 8%">CELULAR</th>
                    <th style="width: 24%;">ACCIONES</th>
                </tr>
                </thead>
                <tbody>
                <tr dir-paginate="transp in transportistas | orderBy:sortKey:reverse | itemsPerPage:10" total-items="totalItems" ng-cloak >
                    <td>{{$index + 1}}</td>
                    <td>{{transp.numdocidentific}}</td>
                    <td>{{transp.razonsocial}}</td>
                    <td>{{transp.placa}}</td>
                    <td>{{transp.email}}</td>
                    <td>{{transp.celphone}}</td>
                    <td>
                        <button type="button" class="btn btn-info" ng-click="toggle('info', transp)"
                                data-toggle="tooltip" data-placement="bottom" title="Información">
                            Información <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                        </button>
                        <button type="button" class="btn btn-warning" ng-click="toggle('edit', transp)"
                                data-toggle="tooltip" data-placement="bottom" title="Editar" >
                            Editar <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                        </button>
                        <button type="button" class="btn btn-danger" ng-click="showModalConfirm(transp)"
                                data-toggle="tooltip" data-placement="bottom" title="Eliminar">
                            Eliminar <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
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
                            <h4 class="modal-title">{{form_title}}. (Chofer)</h4>
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

                                        <angucomplete-alt
                                            id = "documentoidentidadempleado"
                                            pause = "200"
                                            selected-object = "showDataPurchase"

                                            input-changed="inputChanged"

                                            remote-url = "{{API_URL}}transportista/getIdentify/"

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
                                        <span class="input-group-addon">Nombre y Apellidos: </span>
                                        <input type="text" class="form-control" name="razonsocial" id="razonsocial"
                                               ng-model="razonsocial" ng-required="true" ng-maxlength="200" >
                                    </div>
                                    <span class="help-block error"
                                          ng-show="formEmployee.razonsocial.$invalid && formEmployee.razonsocial.$touched">Nombre y Apellidos es requerida</span>
                                    <span class="help-block error"
                                          ng-show="formEmployee.razonsocial.$invalid && formEmployee.razonsocial.$error.maxlength">La longitud máxima es de 200 caracteres</span>
                                </div>

                                <div class="col-md-6 col-xs-12">
                                    <div class="input-group">
                                        <span class="input-group-addon">Proveedor: </span>
                                        <select class="form-control" name="proveedor" id="proveedor" ng-model="proveedor"
                                                ng-options="value.id as value.label for value in proveedores" required></select>
                                    </div>
                                    <span class="help-block error"
                                          ng-show="formEmployee.proveedor.$invalid && formEmployee.proveedor.$touched">El Proveedor es requerido</span>
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
                                        <span class="input-group-addon">Celular: </span>
                                        <input type="text" class="form-control" name="celular" id="celular"
                                               ng-model="celular" ng-minlength="10" ng-maxlength="10" ng-pattern="/^([0-9-\(\)]+)$/">
                                    </div>
                                    <span class="help-block error"
                                          ng-show="formEmployee.celular.$invalid && formEmployee.celular.$error.maxlength">La longitud máxima es de 10 números</span>
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
                                <div class="col-md-6 col-xs-12">
                                    <div class="input-group">
                                        <span class="input-group-addon">Teléfono Principal: </span>
                                        <input type="text" class="form-control" name="telefonoprincipal" id="telefonoprincipal"
                                               ng-model="telefonoprincipal" ng-minlength="9" ng-maxlength="9" ng-pattern="/^([0-9-\(\)]+)$/" >
                                    </div>
                                    <span class="help-block error"
                                          ng-show="formEmployee.telefonoprincipal.$invalid && formEmployee.telefonoprincipal.$error.maxlength">La longitud máxima es de 9 números</span>
                                    <span class="help-block error"
                                          ng-show="formEmployee.telefonoprincipal.$invalid && formEmployee.telefonoprincipal.$error.pattern">El Teléfono debe ser solo números, guion y espacios</span>
                                    <span class="help-block error"
                                          ng-show="formEmployee.telefonoprincipal.$invalid && formEmployee.telefonoprincipal.$error.minlength">La longitud mínima es de 9 caracteres</span>
                                </div>

                                <div class="col-sm-6 col-xs-12">
                                    <div class="input-group">
                                        <span class="input-group-addon">Placa: </span>
                                        <input type="text" class="form-control" name="placa" id="placa" ng-model="placa" ng-required="true" ng-maxlength="20">
                                    </div>
                                    <span class="help-block error"
                                          ng-show="formEmployee.placa.$invalid && formEmployee.placa.$touched">La Placa es requerida</span>
                                    <span class="help-block error"
                                          ng-show="formEmployee.placa.$invalid && formEmployee.placa.$error.maxlength">La longitud máxima es de 20 caracteres</span>
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
                <span>Realmente desea eliminar el Transportista: <span style="font-weight: bold;">{{empleado_seleccionado}}</span></span>

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
                <h4 class="modal-title">Información del Transportista</h4>
            </div>
            <div class="modal-body">
                <div class="row text-center">
                    <div class="col-xs-12 text-center" style="font-size: 18px;">{{razonsocial_transp}}</div>
                    <div class="col-xs-12">
                        <span style="font-weight: bold">Ingresado desde: </span>{{date_registry_transp}}
                    </div>
                    <div class="col-xs-12">
                        <span style="font-weight: bold">Dirección: </span>{{address_transp}}
                    </div>
                    <div class="col-xs-12">
                        <span style="font-weight: bold">Celular: </span>{{cel_transp}}
                    </div>
                    <div class="col-xs-12">
                        <span style="font-weight: bold">Teléfono: </span>{{phones_transp}}
                    </div>
                    <div class="col-xs-12">
                        <span style="font-weight: bold">Email: </span>{{email_transp}}
                    </div>
                    <div class="col-xs-12">
                        <span style="font-weight: bold">Placa: </span>{{placa_transp}}
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
                        <div class="form-group  has-feedback">
                            <input type="text" class="form-control" id="" ng-model="searchContabilidad" placeholder="BUSCAR..." >
                            <span class="glyphicon glyphicon-search form-control-feedback" ></span>
                        </div>
                    </div>

                    <div class="col-xs-12">
                        <table class="table table-responsive table-striped table-hover table-condensed table-bordered">
                            <thead class="bg-primary">
                            <tr>
                                <th style="width: 15%;">ORDEN</th>
                                <th>CONCEPTO</th>
                                <th style="width: 10%;">CODIGO</th>
                                <th style="width: 4%;"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr ng-repeat="item in cuentas | filter:searchContabilidad" ng-cloak >
                                <td>{{item.jerarquia}}</td>
                                <td>{{item.concepto}}</td>
                                <td>{{item.codigosri}}</td>
                                <td>
                                    <input ng-show="item.madreohija=='1'" ng-hide="item.madreohija!='1'" type="radio" name="select_cuenta"  ng-click="click_radio(item)">
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
<script src="<?= asset('js/moment.min.js') ?>"></script>
<script src="<?= asset('js/es.js') ?>"></script>
<script src="<?= asset('js/bootstrap-datetimepicker.min.js') ?>"></script>
<script src="<?= asset('app/lib/angular/angucomplete-alt.min.js') ?>"></script>
<script src="<?= asset('app/lib/angular/dirPagination.js') ?>"></script>

<script src="<?= asset('app/app.js') ?>"></script>
<script src="<?= asset('app/controllers/transportistaController.js') ?>"></script>


</html>












