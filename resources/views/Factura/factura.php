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

<div ng-controller="facturaController">

    <div class="col-xs-12" style="margin-top: 15px;">

        <div class="col-sm-2 col-xs-12">
            <div class="form-group has-feedback">
                <input type="text" class="form-control" id="t_busqueda" placeholder="BUSCAR..." ng-model="t_busqueda">
                <span class="glyphicon glyphicon-search form-control-feedback" aria-hidden="true"></span>
            </div>
        </div>

        <div class="col-sm-2 col-xs-12">
            <select id="s_servicio" class="form-control" ng-model="s_servicio" ng-change="FiltrarPorServicio()"
                    ng-options="value.id as value.label for value in servicioss"></select>
        </div>

        <div class="col-sm-2 col-xs-12">
            <select id="s_anio" class="form-control" ng-model="s_anio" ng-change="FiltrarPorAnio()"
                    ng-options="value.id as value.label for value in anios"></select>
        </div>

        <div class="col-sm-2 col-xs-12">
            <select id="s_mes" class="form-control" ng-model="s_mes" ng-change="FiltrarPorMes()"
                    ng-options="value.id as value.label for value in mesess"></select>
        </div>

        <div class="col-sm-2 col-xs-12">
            <select id="s_estado" class="form-control" ng-model="s_estado" ng-change="FiltrarPorEstado()"
                    ng-options="value.id as value.label for value in estadoss"></select>
        </div>

        <div class="col-sm-2 col-xs-12">
            <button type="button" class="btn btn-primary" id="btn-generate"  style="float: right;" ng-click="generate()" disabled="true">
                Generar <i class="glyphicon glyphicon-refresh" aria-hidden="true"></i>
            </button>
        </div>

    </div>

    <div class="col-xs-12">
        <table class="table table-responsive table-striped table-hover table-condensed table-bordered">
            <thead class="bg-primary">
            <tr>
                <th style="width: 15%;">No. Factura</th>
                <th style="width: 15%;">Fecha</th>
                <th style="width: 15%;">Periodo</th>
                <th style="width: 15%;">Servicios</th>
                <th style="width: 15%;">Suministro</th>
                <th style="width: 15%;">Tarifa</th>
                <th style="width: 15%;">Direccion Suministro</th>
                <th style="width: 15%;">Telefono Suministro</th>
                <th style="width: 15%;">m3</th>
                <th style="width: 15%;">Estado</th>
                <th style="width: 15%;">Total</th>
                <th style="width: 15%;">Acciones</th>
            </tr>
            </thead>
            <tbody>
            <tr ng-repeat="item in cobroagua|filter:busqueda" ng-cloak>
                <td>{{item.factura}}</td>
                <td>{{item.fecha}}</td>
                <td>{{item.periodo}}</td>
                <td>{{item.servicios}}</td>
                <td>{{item.suministro}}</td>
                <td>{{item.tarifas}}</td>
                <td>{{item.direccion}}</td>
                <td>{{item.telefono}}</td>
                <td>{{item.m3}}</td>
                <td>{{item.estado}}</td>
                <td>{{item.total}}</td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm" ng-click="showModalDelete(item)">
                        <i class="fa fa-lg fa-trash" aria-hidden="true"></i>
                    </button>
                    <button type="button" class="btn btn-info btn-sm" ng-click="showModalInfo(item)">
                        <i class="fa fa-lg fa-info-circle" aria-hidden="true"></i>
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
                    <div class="col-md-6 col-xs-12">
                        <h4 class="modal-title">Factura</h4>
                    </div>
                    <div class="col-md-6 col-xs-12">
                        <div class="form-group">
                            <div class="col-sm-1 col-xs-12 text-right" style="padding: 0;">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            </div>
                        </div>
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
                                            <span class="label label-default" style="font-size: 12px !important;">Teléfono:</span> {{telf_cliente}}
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
                                            <tr ng-repeat="item in facturas|filter:busqueda" ng-cloak>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th class="text-right">TOTAL:</th>
                                                    <th></th>
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
                    <button type="button" class="btn btn-primary" id="btn-save-riego"
                            ng-click="saveSolicitudRiego()" ng-disabled="formProcess.$invalid">
                        Guardar <span class="glyphicon glyphicon-floppy-saved" aria-hidden="true"></span>
                    </button>
                    <button type="button" class="btn btn-primary" id="btn-next" ng-click="" >
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


<script src="<?= asset('app/lib/angular/ng-file-upload-shim.min.js') ?>"></script>
<script src="<?= asset('app/lib/angular/ng-file-upload.min.js') ?>"></script>

<script src="<?= asset('app/app.js') ?>"></script>
<script src="<?= asset('app/controllers/facturaController.js') ?>"></script>

</html>

