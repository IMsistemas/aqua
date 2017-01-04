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

    body {
        font-family: Arial;
        font-size: 16px;
        padding-top: 18%;
    }

    .subcontainer1 {
        position: relative;
        float: left;
        width: 74%;
    }

    .subcontainer2 {
        position: relative;
        float: right;
        width: 24%;
    }

    .container {
        position: relative;
        width: 100%;
    }

    .table_bordered td {
        border-collapse: collapse !important;
        border: solid 1px;
    }

    .label_text {
        font-weight: bold;
    }

</style>

<body>

<div class="container">
    <div class="subcontainer1">
        <table border="0" style="width: 60%;">
            <tr>
                <td class="label_text" style="width: 20%;">Nombre:</td>
                <td colspan="3"><?= $data['cliente']['apellidos'] . ' ' . $data['cliente']['nombres'] ?></td>
            </tr>
            <tr>
                <td class="label_text" style="width: 20%;">RUC/CI:</td>
                <td><?= $data['cliente']['documentoidentidad'] ?></td>
                <td class="label_text" style="width: 20%;">Conexión:</td>
                <td><?= $data['cobroagua']['numerosuministro'] ?></td>
            </tr>
            <tr>
                <td class="label_text">Dirección:</td>
                <td colspan="3"><?= $data['cobroagua']['suministro']['direccionsumnistro'] ?></td>
            </tr>
            <tr>
                <td class="label_text">Teléfono:</td>
                <td><?= $data['cobroagua']['suministro']['telefonosuministro'] ?></td>
                <td class="label_text">Fecha:</td>
                <td><?= date('d/m/Y') ?></td>
            </tr>
            <tr>
                <td class="label_text" colspan="4">Detalle:</td>
            </tr>
            <tr>
                <table class="table_bordered" style="width: 60%;">
                    <tr>
                        <td><?= $data['partial_date'] ?></td>
                        <td><?= $data['cobroagua']['lectura']['lecturaanterior'] ?></td>
                        <td><?= $data['cobroagua']['lectura']['lecturaactual'] ?></td>
                        <td><?= $data['cobroagua']['lectura']['consumo'] ?> m3</td>
                    </tr>
                    <tr>
                        <td class="label_text" style="width: 25%;">Periodo</td>
                        <td class="label_text" style="width: 25%;">L. Anterior</td>
                        <td class="label_text" style="width: 25%;">L. Actual</td>
                        <td class="label_text" style="width: 25%;">Consumo</td>
                    </tr>
                </table>
            </tr>
        </table>

        <table style="width: 90%; margin-top: 15px;">
            <thead style="background-color: #eceff1;">
            <tr>
                <th style="width: 70%;">Concepto</th>
                <th style="width: 30%;">Total</th>
            </tr>
            </thead>
            <tbody>

                <?php

                    echo '<tr>';
                    echo '<td>Tarifa Básica</td>';
                    echo '<td style="text-align: right;">' . $data['cobroagua']['valortarifabasica']. '</td>';
                    echo '</tr>';

                    echo '<tr>';
                    echo '<td>Excedente</td>';
                    echo '<td style="text-align: right;">' . $data['cobroagua']['valorexcedente']. '</td>';
                    echo '</tr>';

                    echo '<tr>';
                    echo '<td>Valor Atrasado</td>';
                    echo '<td style="text-align: right;">' . $data['cobroagua']['valormesesatrasados']. '</td>';
                    echo '</tr>';

                    foreach ($data['serviciosenfactura'] as $item) {
                        echo '<tr>';
                        echo '<td>' . ucwords(strtolower($item['serviciojunta']['nombreservicio'])) . '</td>';
                        echo '<td style="text-align: right;">' . $item['valor'] . '</td>';
                        echo '</tr>';
                    }

                    foreach ($data['otrosvaloresfactura'] as $item) {
                        echo '<tr>';
                        echo '<td>' . ucwords(strtolower($item['otrovalor']['nombreotrovalor'])) . '</td>';
                        echo '<td style="text-align: right;">' . $item['valor'] . '</td>';
                        echo '</tr>';
                    }

                ?>

            </tbody>
            <tfoot>
            <tr>
                <th style="text-align: right;">Subtotal US$: </th>
                <th style="text-align: right; border-top: solid 1px;"><?= $data['ivafactura'] ?></th>
            </tr>
            <tr>
                <th style="text-align: right;">Imponible 0%: </th>
                <th style="text-align: right;"><?= $data['subtotalfactura'] ?></th>
            </tr>
            <tr>
                <th style="text-align: right;">Total Factura US$: </th>
                <th style="text-align: right;"><?= $data['totalfactura'] ?></th>
            </tr>
            </tfoot>
        </table>
    </div>
    <div class="subcontainer2">
        <table style="width: 90%; margin-top: 10%;">
            <tr>
                <td colspan="2"><?= $data['cliente']['apellidos'] . ' ' . $data['cliente']['nombres'] ?></td>
            </tr>
            <tr>
                <td style="width: 50%;"><?= $data['cliente']['documentoidentidad'] ?></td>
                <td>Conex: <?= $data['cobroagua']['numerosuministro'] ?></td>
            </tr>
            <tr>
                <td colspan="2"><?= $data['cobroagua']['suministro']['direccionsumnistro'] ?></td>
            </tr>
            <tr>
                <td style="width: 50%;"><?= date('d/m/Y') ?></td>
                <td>Telf: <?= $data['cobroagua']['suministro']['telefonosuministro'] ?></td>
            </tr>
            <tr>
                <td style="width: 50%;"><?= $data['partial_date'] ?></td>
                <td><?= $data['cobroagua']['lectura']['consumo'] ?> m3</td>
            </tr>
        </table>

        <table style="width: 99%; margin-top: 15px;">
            <thead style="background-color: #eceff1;">
            <tr>
                <th style="width: 70%;">Concepto</th>
                <th style="width: 30%;">Total</th>
            </tr>
            </thead>
            <tbody>
                <?php

                    echo '<tr>';
                    echo '<td>Tarifa Básica</td>';
                    echo '<td style="text-align: right;">' . $data['cobroagua']['valortarifabasica']. '</td>';
                    echo '</tr>';

                    echo '<tr>';
                    echo '<td>Excedente</td>';
                    echo '<td style="text-align: right;">' . $data['cobroagua']['valorexcedente']. '</td>';
                    echo '</tr>';

                    echo '<tr>';
                    echo '<td>Valor Atrasado</td>';
                    echo '<td style="text-align: right;">' . $data['cobroagua']['valormesesatrasados']. '</td>';
                    echo '</tr>';

                    foreach ($data['serviciosenfactura'] as $item) {
                        echo '<tr>';
                        echo '<td>' . ucwords(strtolower($item['serviciojunta']['nombreservicio'])) . '</td>';
                        echo '<td style="text-align: right;">' . $item['valor'] . '</td>';
                        echo '</tr>';
                    }

                    foreach ($data['otrosvaloresfactura'] as $item) {
                        echo '<tr>';
                        echo '<td>' . ucwords(strtolower($item['otrovalor']['nombreotrovalor'])) . '</td>';
                        echo '<td style="text-align: right;">' . $item['valor'] . '</td>';
                        echo '</tr>';
                    }

                ?>
            </tbody>
            <tfoot>
            <tr>
                <th style="text-align: right;">Subtotal US$: </th>
                <th style="text-align: right; border-top: solid 1px;"><?= $data['ivafactura'] ?></th>
            </tr>
            <tr>
                <th style="text-align: right;">Imponible 0%: </th>
                <th style="text-align: right;"><?= $data['subtotalfactura'] ?></th>
            </tr>
            <tr>
                <th style="text-align: right;">Total Factura US$: </th>
                <th style="text-align: right;"><?= $data['totalfactura'] ?></th>
            </tr>
            </tfoot>
        </table>
    </div>
</div>


</body>
</html>