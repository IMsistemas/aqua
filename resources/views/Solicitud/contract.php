<!DOCTYPE html>
<html>
<head>
    <title></title>
    <style type="text/css">
        body{
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
            font-size: 12px;
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
</head>
<body>

<div class="col-xs-12 text-center">
    <h3><strong><?= $aux_empresa[0]->nombrecomercial ?> </strong></h3>
</div>

<div class="col-xs-12 text-center">
    <h3><strong> CONTRATO </strong></h3>
</div>


<div class="col-xs-12 text-right">
    <h4><strong>Fecha: <?= $today ?> </strong></h4>
</div>


<div class="col-xs-12" style="font-size: 12px !important;">

    <table class="table table-responsive table-striped table-hover table-condensed table-bordered">
        <tbody>

        <tr>
            <th style="width: 30%;">NO: CONEXION</th>
            <td><?= $filtro[0]->numconexion?></td>
        </tr>

        <tr>
            <th style="width: 30%;">CLIENTE</th>
            <td><?= $filtro[0]->lastnamepersona . ' ' . $filtro[0]->namepersona ?></td>
        </tr>

        <tr>
            <th>CI</th>
            <td><?= $filtro[0]->numdocidentific ?></td>
        </tr>

        </tbody>
    </table>

</div>



<div class="col-xs-12" style="font-size: 12px !important;">

    <table class="table table-responsive table-striped table-hover table-condensed table-bordered">
        <tbody>

        <tr>
            <th>Tarifa</th>
            <td><?= $filtro[0]->nametarifaaguapotable ?></td>
            <th>Zona</th>
            <td><?= $filtro[0]->namebarrio ?></td>
        </tr>

        <tr>
            <th>Transversal</th>
            <td><?= $filtro[0]->namecalle ?></td>
            <th>Teléfono Instalación</th>
            <td><?= $filtro[0]->telefonosuministro ?></td>
        </tr>

        <tr>
            <th>Dirección Instalación</th>
            <td colspan="3"><?= $filtro[0]->direccionsumnistro ?></td>
        </tr>

        </tbody>
    </table>

</div>

<div class="col-xs-12">
    <h3>ACOMETIDA</h3>
    <hr>
</div>

<div class="col-xs-12" style="font-size: 12px !important;">

    <table class="table table-responsive table-striped table-hover table-condensed table-bordered">
        <tbody>

        <tr>
            <th>Agua Potable</th>
            <td>USD $ <?= $filtro[0]->valoraguapotable ?></td>
            <th>Alcantarillado</th>
            <td>USD $ <?= $filtro[0]->valoralcantarillado ?></td>
        </tr>

        <tr>
            <th>Garantía Apertura</th>
            <td>USD $ <?= $filtro[0]->valorgarantia ?></td>
            <th></th>
            <td></td>
        </tr>

        </tbody>
    </table>

</div>

<div class="col-xs-12">
    <h3>TOTAL</h3>
    <hr>
</div>


<div class="col-xs-12" style="font-size: 12px !important;">

    <table class="table table-responsive table-striped table-hover table-condensed table-bordered">
        <tbody>

        <tr>
            <th>Cuota Inicial</th>
            <td>USD $ <?= $filtro[0]->valorcuotainicial ?></td>
            <th>Crédito</th>
            <td><?= $filtro[0]->dividendocredito ?></td>
        </tr>

        <tr>
            <th>Total</th>
            <td>USD $ <?= $filtro[0]->valortotalsuministro ?></td>
            <th>Cuotas de</th>
            <td>USD $ <?= $filtro[0]->valortotalsuministro / $filtro[0]->dividendocredito ?></td>
        </tr>

        </tbody>
    </table>

</div>

<div class="container" style="margin-top: 1%;">
    <span style="font-size: 12px; margin-bottom: 25px;">
        <p>
            El valor a plazos se cancelará en <?= $filtro[0]->dividendocredito ?> dividendos de <?= $filtro[0]->valortotalsuministro / $filtro[0]->dividendocredito ?> dólares americanos más el consumo mensual.
        </p>

        <p>
            El solicitante acepta conocer los deberes y atribuciones establecidas en la Ley 3327 de las Juntas
            Administradoras de Agua Potable y Alcantarillado Publicado en el Registro Oficial del 29 de Marzo de 1979, en
            actual vigencia dando a este documento el de CONTRATO DE SERVICIO de A.P.
            El mismo que se suscribe en original y copia en común de acuerdo en:
        </p>
    </span>
    <br><br>
    <div class="col-xs-12" style="font-size: 12px !important;">

        <table class="table table-responsive table-striped table-hover table-condensed table-bordered">
            <tbody>

            <tr>
                <th></th>
                <th></th>
            </tr>

            <tr>
                <th>SOLICITANTE</th>
                <th><?= $aux_empresa[0]->nombrecomercial ?></th>
            </tr>

            </tbody>
        </table>

    </div>
</div>




</body>
</html>