<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<style>

    /*body {
        font-family: "Helvetica Neue", Helvetica, Arial, sans-serif !important;
        font-size: 16px;
        padding-top: 10%;
    }*/

    .subcontainer1 {
        position: relative;
        float: left;
        width: 70%;
    }

    .subcontainer2 {
        position: relative;
        float: right;
        width: 24%;
    }

    body{
        font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
        font-size: 12px;
        padding-top: 10%;
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
    .text-right
    {
        text-align: right !important;
    }

    .text-center
    {
        text-align: center !important;
    }

    .text-left
    {
        text-align: left !important;
    }
    .bg-primary{
        background:#2F70A8 !important;
    }
    .bg-success{
        background:#DFF0D8 !important;
    }
    .bg-warning{
        background:#FCF8E3 !important;
    }

</style>

<body>

<div class="container">
    <div class="subcontainer1">
        <table border="0" style="width: 100%;">
            <tr>
                <td class="label_text" style="width: 20%; font-weight: bold;">NOMBRE:</td>
                <td colspan="3"><?= $values['razonsocial'] ?></td>
            </tr>
            <tr>
                <td class="label_text" style="width: 20%; font-weight: bold;">RUC/CI:</td>
                <td><?= $values['numdocidentific'] ?></td>
                <td class="label_text" style="width: 20%; font-weight: bold;">PERIODO</td>
                <td><?= $values['partial_date'] ?></td>
            </tr>
            <tr>
                <td class="label_text" style="font-weight: bold;">DIRECCION:</td>
                <td colspan="3"><?= $values['direccion'] ?></td>
            </tr>
            <tr>
                <td class="label_text" style="font-weight: bold;">TELEFONO:</td>
                <td><?= $values['telefonoprincipaldomicilio'] ?></td>
                <td class="label_text" style="font-weight: bold;">FECHA:</td>
                <td><?= date('d/m/Y') ?></td>
            </tr>
            <tr>
                <td class="label_text" colspan="4" style="font-weight: bold;">DETALLE:</td>
            </tr>

        </table>

        <table class="table table-responsive table-striped table-hover table-condensed table-bordered" style="width: 90%;">
            <thead style="background-color: #eceff1;">
            <tr>
                <th style="width: 70%; font-weight: bold;">CONCEPTO</th>
                <th style="width: 30%; font-weight: bold;">TOTAL</th>
            </tr>
            </thead>
            <tbody>

                <?php

                    foreach ($values['solicitudservicio']['catalogoitem_solicitudservicio'] as $item) {
                        echo '<tr>';
                        echo '<td>' . ucwords(strtolower($item['cont_catalogitem']['nombreproducto'])) . '</td>';
                        echo '<td style="text-align: right;">' . $item['valor'] . '</td>';
                        echo '</tr>';
                    }



                ?>

            </tbody>
            <tfoot>
            <tr>
                <th style="text-align: right;">Subtotal USD $: </th>
                <th style="text-align: right; border-top: solid 1px;"><?= $values['subtotalfactura'] ?></th>
            </tr>
            <tr>
                <th style="text-align: right;">Imponible <?= $values['porcentaje_iva'] ?>%: </th>
                <th style="text-align: right;"><?= $values['iva'] ?></th>
            </tr>
            <tr>
                <th style="text-align: right;">Total Factura USD $: </th>
                <th style="text-align: right;"><?= $values['totalfactura'] ?></th>
            </tr>
            </tfoot>
        </table>
    </div>

</div>


</body>
</html>