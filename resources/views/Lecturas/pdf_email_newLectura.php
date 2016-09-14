<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <title>Aqua-Nueva Lectura</title>

    <style>

        body{
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
            font-size: 14px;
        }

        .container {
            padding-right: 15px;
            padding-left: 15px;
            margin-right: auto;
            margin-left: auto;
            position: absolute;
        }

        .col-xs-3, .col-xs-6,  .col-xs-12 {
            position: relative;
            min-height: 1px;
            padding-right: 5px;
            padding-left: 5px;
        }

        /*.col-xs-3, .col-xs-6, .col-xs-12 {
            float: left;
        }*/

        .col-xs-12 {
            width: 100%;
        }

        .col-xs-6 {
            float: left;
            width: 50%;
        }

        .col-xs-3 {
            float: left;
            width: 25%;
        }

        .form-control {
            /*display: block;*/
            width: 100%;
            height: 20px;
            padding: 6px 12px;
            font-size: 14px;
            line-height: 1.42857143;
            color: #555;
            background-color: #fff;
            background-image: none;
            border: 1px solid #ccc;
            border-radius: 4px;
            -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
            box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
            -webkit-transition: border-color ease-in-out .15s, -webkit-box-shadow ease-in-out .15s;
            -o-transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
            transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;

            text-align: right;

        }

        .table {
            border-collapse: collapse !important;
        }
        .table td,
        .table th {
            background-color: #fff !important;
        }
        .table-bordered th,
        .table-bordered td {
            border: 1px solid #ddd !important;
        }

        .table {
            width: 100%;
            max-width: 100%;
            margin-bottom: 20px;
        }
        .table > thead > tr > th,
        .table > tbody > tr > th,
        .table > tfoot > tr > th,
        .table > thead > tr > td,
        .table > tbody > tr > td,
        .table > tfoot > tr > td {
            padding: 8px;
            line-height: 1.42857143;
            vertical-align: top;
            border-top: 1px solid #ddd;
        }
        .table > thead > tr > th {
            vertical-align: bottom;
            border-bottom: 2px solid #ddd;
        }

        .table-striped > tbody > tr:nth-of-type(odd) {
            background-color: #f9f9f9;
        }

        .dataclient{
            font-weight: bold;
        }
    </style>

</head>
<body>

<div class="container">
    <img src="img/LogoEnCabecera.jpg" alt="">
</div>

<div class="container" style="margin-top: 10%;">
    <div class="col-xs-6">
        <span style="font-size: 24px; font-weight: bold;">PREFACTURA</span>
    </div>
    <div class="col-xs-6">
        <span style="font-size: 20px;"><?= $data->mes ?> - <?= $data->anno ?></span>
    </div>
</div>

<div class="col-xs-12" style="border-bottom: 1px solid #e5e5e5; margin-top: 150px;">
    <span style="font-size: 16px; font-weight: bold">Datos de Suministro:</span>
</div>


<div class="container" style="margin-top: 2%;">
    <div class="col-xs-6" style="background: #e3f2fd; border: solid 1px #e0e0e0; border-radius: 5px; padding: 5px;">
        <span class="dataclient"><span style="font-size: 14px !important;" class="label label-default">Nro. Suministro:</span> </span> <?= $data->suministro ?>
        <p>
            <span class="dataclient"><span style="font-size: 14px !important;" class="label label-default">Cliente:</span> </span> <?= $data->nombre_cliente ?>
        </p>
        <p>
            <span class="dataclient"><span style="font-size: 14px !important;" class="label label-default">Barrio:</span> </span> <?= $data->barrio ?>
        </p>
        <p>
            <span class="dataclient"><span style="font-size: 14px !important;" class="label label-default">Calle:</span> </span> <?= $data->calle ?>
        </p>

        <span class="dataclient"><span style="font-size: 14px !important;" class="label label-default">Tarifa:</span> </span> <?= $data->tarifa ?>
    </div>
    <div class="col-xs-6">
        <table class="table-bordered table-striped" style="position: relative;">
            <thead style="background: #e3f2fd;">
            <tr>
                <th>Lectura Anterior</th>
                <th>Lectura Actual</th>
                <th>Consumo (m3)</th>
            </tr>
            </thead>
            <tbody>
            <tr style="text-align: right;">
                <td><?= $data->lectura_anterior ?></td>
                <td><?= $data->lectura_actual ?></td>
                <td><?= $data->consumo ?></td>
            </tr>
            </tbody>
        </table>
    </div>
</div>

<div class="col-xs-12" style="border-bottom: 1px solid #e5e5e5; margin-top: 20%;">
    <span style="font-size: 16px; font-weight: bold">Detalle de Consumo:</span>
</div>

<div class="col-xs-12" style=" margin-top: 2%;">
    Meses Atrasados: <?= $data->meses_atrasados ?>
</div>

<div class="container" style=" margin-top: 2%;">
    <table class="table table-bordered table-striped">
        <thead style="background: #e3f2fd !important;">
        <tr>
            <th>Concepto</th>
            <th>Total</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($data->rubros as $item){ ?>

            <tr>
                <td><?= $item->nombrerubro ?></td>
                <td style="text-align: right;"><?= $item->valorrubro ?></td>
            </tr>

        <?php } ?>
        </tbody>
        <tfoot>
        <tr>
            <th><span style="font-weight: bold;">TOTAL</span></th>
            <th style="text-align: right;"><?= $data->total ?></th>
        </tr>
        </tfoot>
    </table>
</div>


</body>
</html>