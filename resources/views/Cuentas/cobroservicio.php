<!DOCTYPE html>
<html lang="es-ES" ng-app="softver-aqua">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>Cuentas x Cobrar</title>

    <link href="<?= asset('css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= asset('css/font-awesome.min.css') ?>" rel="stylesheet">
    <link href="<?= asset('css/index.css') ?>" rel="stylesheet">
    <link href="<?= asset('css/bootstrap-datetimepicker.min.css') ?>" rel="stylesheet">
    <link href="<?= asset('css/style_generic_app.css') ?>" rel="stylesheet">
    <link href="<?= asset('css/angucomplete-alt.css') ?>" rel="stylesheet">


</head>

<body>

<div ng-controller="cobroServicioController">

    <div class="col-xs-12">

        <h4>Gestión de Cobro de Solicitudes de Servicios</h4>

        <hr>

    </div>

    <div class="col-xs-12" style="margin-top: 5px;">

        <div class="col-sm-6 col-xs-8">
            <div class="form-group has-feedback">
                <input type="text" class="form-control" id="busqueda" placeholder="BUSCAR..." ng-model="busqueda" ng-keyup="initLoad()">
                <span class="glyphicon glyphicon-search form-control-feedback" aria-hidden="true"></span>
            </div>
        </div>

        <div class="col-sm-4 col-xs-2">
            <button type="button" class="btn btn-primary" ng-click="generate()">
                Generar <span class="glyphicon glyphicon-refresh" aria-hidden="true">
            </button>
        </div>

        <div class="col-xs-12" style="font-size: 12px !important;">

            <table class="table table-responsive table-striped table-hover table-condensed table-bordered">
                <thead class="bg-primary">
                <tr>
                    <th style="width: 4%;">NO.</th>
                    <th style="width: 10%;">PERIODO</th>
                    <th>CLIENTE</th>
                    <th style="width: 11%;">VALOR TOTAL</th>
                    <th style="width: 11%;">ESTADO</th>
                    <th style="width: 11%;">ACCION</th>
                </tr>
                </thead>
                <tbody>
                    <tr ng-repeat="item in list | filter : busqueda" ng-cloak">

                        <td>{{$index + 1}}</td>
                        <td class="text-center">{{item.fechacobro}}</td>
                        <td>{{item.razonsocial}}</td>
                        <td class="text-right">$ {{item.total}}</td>
                        <td class="text-right"></td>
                        <td class="text-right">

                        </td>

                    </tr>
                </tbody>
            </table>

        </div>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" id="listCobros">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header modal-header-primary">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Listado de Cobros x Factura </h4>
                </div>
                <div class="modal-body">
                    <div class="row">

                        <div class="col-xs-12 text-right">
                            <button type="button" id="btn-cobrar" class="btn btn-primary" ng-click="showModalFormaCobro()">
                                Cobrar <span class="glyphicon glyphicon-usd" aria-hidden="true">
                            </button>
                        </div>

                        <div class="col-xs-12" style="font-size: 12px !important; margin-top: 5px;">

                            <table class="table table-responsive table-striped table-hover table-condensed table-bordered">
                                <thead class="bg-primary">
                                <tr>
                                    <th style="width: 4%;">NO. COMPROBANTE</th>
                                    <th style="width: 10%;">FECHA</th>
                                    <th>FORMA PAGO</th>
                                    <th style="width: 11%;">VALOR</th>
                                    <th style="width: 5%;">ACCION</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr ng-repeat="item in listcobro" ng-cloak">

                                <td>{{item.nocomprobante}}</td>
                                <td class="text-center">{{item.fecharegistro}}</td>
                                <td>{{item.nameformapago}}</td>
                                <td class="text-right">$ {{item.valorpagado}}</td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-delete" ng-click="" title="Anular">
                                                <span class="glyphicon glyphicon-ban-circle" aria-hidden="true">
                                    </button>
                                </td>

                                </tr>
                                </tbody>
                            </table>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" id="formCobros">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header modal-header-info">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Forma de Cobro </h4>
                </div>
                <div class="modal-body">

                    <div class="col-sm-6 col-xs-12" style="margin-top: 5px;">
                        <div class="input-group">
                            <span class="input-group-addon">No. Comprobante: </span>
                            <input type="text" class="form-control" id="nocomprobante" ng-model="nocomprobante" >
                        </div>
                    </div>

                    <div class="col-sm-6 col-xs-12" style="margin-top: 5px;">
                        <div class="input-group">
                            <span class="input-group-addon">Fecha Cobro: </span>
                            <input type="text" class="form-control datepicker" id="fecharegistro" ng-model="fecharegistro" >
                        </div>
                    </div>

                    <div class="col-xs-12" style="margin-top: 5px;">
                        <div class="input-group">
                            <span class="input-group-addon">Forma Pago: </span>
                            <select class="form-control" name="formapago" id="formapago" ng-model="formapago" ng-required="true"
                                    ng-options="value.id as value.label for value in listformapago">
                            </select>
                        </div>
                        <span class="help-block error"
                              ng-show="formCompra.formapago.$invalid && formCompra.formapago.$touched">La Forma Pago es requerida</span>
                    </div>

                    <div class="col-sm-6 col-xs-12" style="margin-top: 5px;">
                        <div class="input-group">
                            <span class="input-group-addon">A Cobrar: </span>
                            <input type="text" class="form-control" id="valorpendiente" ng-model="valorpendiente" disabled>
                        </div>
                    </div>

                    <div class="col-sm-6 col-xs-12" style="margin-top: 5px;">
                        <div class="input-group">
                            <span class="input-group-addon">Cobrado: </span>
                            <input type="text" class="form-control" id="valorrecibido" ng-model="valorrecibido" >
                        </div>
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
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        Cancelar <span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span>
                    </button>
                    <button type="button" class="btn btn-primary" id="btn-ok" ng-click="saveCobro()">
                        Aceptar <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
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
<script src="<?= asset('app/lib/angular/dirPagination.js') ?>"></script>
<script src="<?= asset('js/jquery.min.js') ?>"></script>
<script src="<?= asset('js/bootstrap.min.js') ?>"></script>
<script src="<?= asset('js/menuLateral.js') ?>"></script>
<script src="<?= asset('js/moment.min.js') ?>"></script>
<script src="<?= asset('js/es.js') ?>"></script>
<script src="<?= asset('js/bootstrap-datetimepicker.min.js') ?>"></script>

<script src="<?= asset('app/lib/angular/ng-file-upload-shim.min.js') ?>"></script>
<script src="<?= asset('app/lib/angular/ng-file-upload.min.js') ?>"></script>

<script src="<?= asset('app/lib/angular/angucomplete-alt.min.js') ?>"></script>
<script src="<?= asset('app/app.js') ?>"></script>

<script src="<?= asset('app/controllers/cobroServicioController.js') ?>"></script>
</html>

