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
            <button type="button" class="btn btn-primary" style="float: right;" ng-click="Generar()">
                Generar <i class="glyphicon glyphicon-refresh" aria-hidden="true"></i>
            </button>
        </div>

    </div>

    <div class="col-xs-12">
        <table class="table table-responsive table-striped table-hover table-condensed table-bordered">
            <thead class="bg-primary">
            <tr>
                <th style="width: 15%;">No. Factura</th>
                <th style="width: 15%;">Fecha Factura</th>
                <th style="width: 15%;">Periodo</th>
                <th style="width: 15%;">Servicios</th>
                <th style="width: 15%;">Suministro</th>
                <th style="width: 15%;">Tarifa</th>
                <th style="width: 15%;">Direccion</th>
                <th style="width: 15%;">Telefono</th>
                <th style="width: 15%;">m3</th>
                <th style="width: 15%;">Estado</th>
                <th style="width: 15%;">Total</th>
                <th style="width: 15%;">Acciones</th>
            </tr>
            </thead>
            <tbody>
            <tr ng-repeat="item in facturas|filter:busqueda" ng-cloak>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
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

