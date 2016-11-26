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
    <link href="<?= asset('css/bootstrap-datetimepicker.min.css') ?>" rel="stylesheet">
    <link href="<?= asset('css/index.css') ?>" rel="stylesheet">
    <link href="<?= asset('css/style_generic_app.css') ?>" rel="stylesheet">

    <style>
        td{
            vertical-align: middle !important;
        }
    </style>

</head>

<body>

<div ng-controller="facturaController">

    <div class="col-xs-12" style="margin-top: 15px;">

        <div class="col-sm-2 col-xs-12">
            <div class="form-group has-feedback">
                <input type="text" class="form-control" id="t_busqueda" placeholder="BUSCAR..." ng-model="t_busqueda">
                <span class="glyphicon glyphicon-search form-control-feedback" aria-hidden="true"></span>
            </div>
        </div>


        <div class="col-sm-2 col-xs-12">

            <input type="text" class="form-control datepicker_a" name="t_anio"
                   id="t_anio" ng-model="t_anio"  ng-change="Filtrar()" placeholder="-- Año --">


        </div>

        <div class="col-sm-2 col-xs-12">
            <select id="s_mes" name="s_mes" class="form-control" ng-model="s_mes" ng-change="Filtrar()"
                    ng-options="value.id as value.label for value in meses"></select>
        </div>

        <div class="col-sm-2 col-xs-12">
            <select id="s_estado" class="form-control" ng-model="s_estado" ng-change="Filtrar()"
                    ng-options="value.id as value.label for value in estadoss"></select>
        </div>

        <div class="col-sm-2 col-xs-12">
            <button type="button" class="btn btn-primary" id="btn-generate"  style="float: right;" ng-click="generate()" disabled="true">
                Generar <i class="glyphicon glyphicon-refresh" aria-hidden="true"></i>
            </button>
        </div>

    </div>

    <div class="col-xs-12">
        <table class="auto table table-responsive table-striped table-hover table-condensed table-bordered ">
            <thead class="bg-primary">
            <tr>
                <th style="width: 5%;">No. Factura</th>
                <th style="width: 7%;">Fecha</th>
                <th style="width: 12%;">Periodo</th>
                <th style="width: 16%;">Servicios</th>
                <th style="width: 5%;">Suministro</th>
                <th style="width: 15%;">Tarifa</th>
                <th style="width: 15%;">Dirección Suministro</th>
                <th style="width: 5%;">Teléfono Suministro</th>
                <th style="width: 5%;">Consumo (m3)</th>
                <th style="width: 5%;">Estado</th>
                <th style="width: 5%;">Total</th>
                <th style="width: 5%;">Acciones</th>
            </tr>
            </thead>
            <tbody>
            <tr ng-repeat="item in factura|filter:t_busqueda" ng-cloak>
                <td>{{item.idfactura}}</td>
                <td>{{ FormatoFecha(item.fechafactura)}}</td>
                <td>{{yearmonth (item.fechafactura)}}</td>
                <td>
                    <span ng-repeat="serviciosenfactura in item.serviciosenfactura">{{serviciosenfactura.serviciojunta.nombreservicio}}; </span>

                </td>
                <td>{{item.cobroagua.suministro.numerosuministro}}</td>
                <td>{{item.cobroagua.suministro.aguapotable.nombretarifaaguapotable}}</td>
                <td>{{item.cobroagua.suministro.direccionsumnistro}}</td>
                <td>{{item.cobroagua.suministro.telefonosuministro}}</td>
                <td>{{item.cobroagua.lectura.consumo}}</td>
                <td>{{Pagada(item.estapagada)}}</td>
                <td>{{item.totalfactura}}</td>
                <td>
                    <button type="button" class="btn btn-success btn-sm" ng-click="Print(item)">
                        <i class="fa fa-lg fa-print" aria-hidden="true"></i>
                    </button>
                    <button type="button" class="btn btn-info btn-sm" ng-click="ShowModalFactura(item)">
                        <i class="fa fa-lg fa-eye" aria-hidden="true"></i>
                    </button>
                </td>
            </tr>
            </tbody>
        </table>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" id="modalFactura">
        <div class="modal-dialog" role="document" style="width: 60%;">
            <div class="modal-content">
                <div class="modal-header modal-header-primary">
                    <div class="col-md-11 col-xs-12">
                        <h4 class="modal-title">Factura</h4>
                    </div>
                            <div class="col-sm-1 col-xs-12 text-right">
                                <div class="col-xs-2"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>
                            </div>
                </div>

                <div class="modal-body">
                    <form class="form-horizontal" name="formProcess" novalidate="">

                        <div class="col-sm-6 col-xs-12 form-group" style="padding-left: 0;">
                            <label for="num_factura" class="col-sm-3 col-xs-12 control-label text-left">No. Factura:</label>
                            <div class="col-sm-9 col-xs-12" style="padding-left: 0;">
                                <input type="text" class="form-control" name="num_factura" id="num_factura" ng-model="num_factura" disabled>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-xs-12" style="padding: 2%; margin-top: -20px !important;">
                                <fieldset ng-cloak>
                                    <legend style="font-size: 16px; font-weight: bold;">Datos del Cliente</legend>

                                    <div class="col-xs-12" style="padding: 0;">
                                        <div class="col-sm-6 col-xs-12">
                                            <span class="label label-default" style="font-size: 12px !important;">RUC/CI:</span> {{documentoidentidad_cliente}}
                                        </div>
                                        <div class="col-sm-6 col-xs-12">
                                            <span class="label label-default" style="font-size: 12px !important;">CLIENTE:</span> {{nom_cliente}}
                                            <input type="hidden" ng-model="h_codigocliente">
                                        </div>
                                    </div>
                                    <div class="col-xs-12" style="padding: 0; margin-top: 5px;">
                                        <div class="col-sm-6 col-xs-12">
                                            <span class="label label-default" style="font-size: 12px !important;">Dirección Domicilio:</span> {{direcc_cliente}}
                                        </div>
                                        <div class="col-sm-6 col-xs-12">
                                            <span class="label label-default" style="font-size: 12px !important;">Teléf. Celular:</span> {{telf_cliente}}
                                        </div>
                                    </div>
                                   </fieldset>
                            </div>

                            <div class="col-xs-12" style="padding: 2%;">
                                <fieldset>
                                    <legend style="font-size: 16px; font-weight: bold;">Detalle</legend>

                                    <div class="col-xs-12">
                                        <table class="table table-responsive table-striped table-hover table-condensed table-bordered">
                                            <thead class="bg-primary">
                                            <tr>
                                                <th style="width: 15%;">Descripción</th>
                                                <th style="width: 15%;">Valor</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tbody>
                                            <tr ng-repeat="item in aux_modal" ng-cloak >
                                                <td>{{item.nombre}}</td>
                                                <td ng-if="item.id == 0">
                                                    <input type="text" class="form-control" ng-model="item.valor" disabled>
                                                </td>
                                                <td ng-if="item.id != 0">
                                                    <input type="text" class="form-control" ng-model="item.valor" ng-keypress="onlyDecimal($event)" ng-blur="reCalculateTotal()">
                                                </td>
                                            </tr>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th class="text-right">TOTAL:</th>
                                                    <th> {{total}}</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </fieldset>
                            </div>

                        </div>

                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="btn-save"
                            ng-click="save()" ng-disabled="formProcess.$invalid">
                        Guardar <span class="glyphicon glyphicon-floppy-saved" aria-hidden="true"></span>
                    </button>
                    <button type="button" class="btn btn-primary" id="btn-pagar" ng-click="pagar()" >
                        Pagar <span class="glyphicon glyphicon-usd" aria-hidden="true"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" id="modalMessage" style="z-index: 99999;">
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


</div>

</body>

<script src="<?= asset('app/lib/angular/angular.min.js') ?>"></script>
<script src="<?= asset('app/lib/angular/angular-route.min.js') ?>"></script>
<script src="<?= asset('js/jquery.min.js') ?>"></script>
<script src="<?= asset('js/bootstrap.min.js') ?>"></script>

<script src="<?= asset('js/moment.min.js') ?>"></script>
<script src="<?= asset('js/es.js') ?>"></script>
<script src="<?= asset('js/bootstrap-datetimepicker.min.js') ?>"></script>


<script src="<?= asset('app/lib/angular/ng-file-upload-shim.min.js') ?>"></script>
<script src="<?= asset('app/lib/angular/ng-file-upload.min.js') ?>"></script>

<script src="<?= asset('app/app.js') ?>"></script>
<script src="<?= asset('app/controllers/facturaController.js') ?>"></script>

</html>

