<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">

        <title>PLANTILLA PDF</title>

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
                position: relative;
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
                float: left !important;
                width: 50% !important;
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

            .table_info_consumo {
                border-collapse: collapse !important;
                width: 100%;
                max-width: 100%;
                margin-bottom: 20px;
            }

            .table_info_consumo td {
                border: 1px solid #ddd !important;
            }
            .table_info_consumo > thead > tr > th,
            .table_info_consumo > tbody > tr > th,
            .table_info_consumo > tfoot > tr > th,
            .table_info_consumo > thead > tr > td,
            .table_info_consumo > tbody > tr > td,
            .table_info_consumo > tfoot > tr > td {
                padding: 8px;
                line-height: 1.42857143;
                vertical-align: top;
            }
            .table_info_consumo > thead > tr > th {
                vertical-align: bottom;
                border-bottom: 2px solid #ddd;
            }

            .dataclient{
                font-weight: bold;
            }

        </style>

    </head>
    <body>

        <div class="container" style="margin-top: 2%;">
            <table style="width: 100%;">
                <tr>
                    <td style="width: 60%;"><span style="font-size: 16px; font-weight: bold;">SOLICITUD DE CONEXION DE AGUA POTABLE Nro: </span></td>
                    <td style="width: 40%;"><span style="font-size: 14px;"><?= $data->no_suministro ?></span></td>
                </tr>
            </table>
        </div>

        <div class="container" style="margin-top: 1%;">
            <table style="width: 100%;">
                <tr>
                    <td style="width: 15%;"><span style="font-size: 16px; font-weight: bold;">CLIENTE: </span></td>
                    <td style="width: 85%;"><span style="font-size: 14px;"><?= $data->nomcliente ?></span></td>
                </tr>
                <tr>
                    <td style="width: 15%;"><span style="font-size: 16px; font-weight: bold;">CI: </span></td>
                    <td style="width: 85%;"><span style="font-size: 14px;"><?= $data->ci ?></span></td>
                </tr>
            </table>
        </div>

        <div class="container" style="margin-top: 2%;">
            <table style="width: 100%;">
                <tr>
                    <td style="width: 20%;"><span style="font-size: 12px; font-weight: bold;">Tarifa: </span></td>
                    <td style="width: 30%;"><span style="font-size: 12px;"><?= $data->tarifa ?></span></td>
                    <td style="width: 20%;"><span style="font-size: 12px; font-weight: bold;">Zona: </span></td>
                    <td style="width: 30%;"><span style="font-size: 12px;"><?= $data->zona ?></span></td>
                </tr>
                <tr>
                    <td style="width: 20%;"><span style="font-size: 12px; font-weight: bold;">Transversal: </span></td>
                    <td style="width: 30%;"><span style="font-size: 12px;"><?= $data->transversal ?></span></td>
                    <td style="width: 20%;"><span style="font-size: 12px; font-weight: bold;">Teléfono Intalación: </span></td>
                    <td style="width: 30%;"><span style="font-size: 12px;"><?= $data->telefono ?></span></td>
                </tr>
                <tr>
                    <td style="width: 20%;"><span style="font-size: 12px; font-weight: bold;">Dirección Intalación: </span></td>
                    <td colspan="3" style="width: 25%;"><span style="font-size: 12px;"><?= $data->direccion ?></span></td>
                </tr>
            </table>
        </div>

        <div class="container" style="margin-top: 2%;">
            <span style="font-size: 16px; font-weight: bold;">Acometida </span>
            <hr>
        </div>

        <div class="container">
            <table style="width: 100%;">
                <tr>
                    <td style="width: 20%;"><span style="font-size: 12px; font-weight: bold;">Agua Potable: </span></td>
                    <td style="width: 30%;"><span style="font-size: 12px;">USD $ <?= $data->agua_potable ?></span></td>
                    <td style="width: 20%;"><span style="font-size: 12px; font-weight: bold;">Alcantarillado: </span></td>
                    <td style="width: 30%;"><span style="font-size: 12px;">USD $ <?= $data->alcantarillado ?></span></td>
                </tr>
                <tr>
                    <td style="width: 20%;"><span style="font-size: 12px; font-weight: bold;">Garantía Apertura: </span></td>
                    <td style="width: 30%;"><span style="font-size: 12px;">USD $ <?= $data->garantia ?></span></td>
                    <td style="width: 20%;"></td>
                    <td style="width: 30%;"></td>
                </tr>
            </table>
        </div>

        <div class="container" style="margin-top: 2%;">
            <span style="font-size: 16px; font-weight: bold;">Medidor </span>
            <hr>
        </div>

        <div class="container">
            <table style="width: 100%;">
                <tr>
                    <td style="width: 20%;"><span style="font-size: 12px; font-weight: bold;">Cliente tiene Medidor: </span></td>
                    <td style="width: 30%;"><span style="font-size: 12px;"><?= $data->tiene_medidor ?></span></td>
                    <td style="width: 20%;"><span style="font-size: 12px; font-weight: bold;">Marca: </span></td>
                    <td style="width: 30%;"><span style="font-size: 12px;"><?= $data->marca_medidor ?></span></td>
                </tr>
                <tr>
                    <td style="width: 20%;"><span style="font-size: 12px; font-weight: bold;">Costo: </span></td>
                    <td style="width: 30%;"><span style="font-size: 12px;">USD $ <?= $data->costo_medidor ?></span></td>
                    <td style="width: 20%;"></td>
                    <td style="width: 30%;"></td>
                </tr>
            </table>
        </div>

        <div class="container" style="margin-top: 2%;">
            <span style="font-size: 16px; font-weight: bold;">Total </span>
            <hr>
        </div>

        <div class="container">
            <table style="width: 100%;">
                <tr>
                    <td style="width: 20%;"><span style="font-size: 12px; font-weight: bold;">Cuota Inicial: </span></td>
                    <td style="width: 30%;"><span style="font-size: 12px;">USD $ <?= $data->cuota_inicial ?></span></td>
                    <td style="width: 20%;"><span style="font-size: 12px; font-weight: bold;">Crédito: </span></td>
                    <td style="width: 30%;"><span style="font-size: 12px;"><?= $data->dividendos ?></span></td>
                </tr>
                <tr>
                    <td style="width: 20%;"><span style="font-size: 12px; font-weight: bold;">Total: </span></td>
                    <td style="width: 30%;"><span style="font-size: 12px;">USD $ <?= $data->valor_partial ?></span></td>
                    <td style="width: 20%;"><span style="font-size: 12px; font-weight: bold;">Cuotas de: </span></td>
                    <td style="width: 30%;"><span style="font-size: 12px;">USD $ <?= $data->total_suministro ?></span></td>
                </tr>
            </table>
        </div>

        <div class="container" style="margin-top: 2%;">
            <span style="font-size: 12px;">
                <p>
                    El valor a plazos se cancelará en <?= $data->dividendos ?> dividendos de <?= $data->total_suministro ?> dólares americanos más el consumo mensual.
                </p>

                <p>
                    El solicitante acepta conocer los deberes y atribuciones establecidas en la Ley 3327 de las Juntas
                    Administradoras de Agua Potable y Alcantarillado Publicado en el Registro Oficial del 29 de Marzo de 1979, en
                    actual vigencia dando a este documento el de CONTRATO DE SERVICIO de A.P.
                    El mismo que se suscribe en original y copia en común de acuerdo en:
                </p>
            </span>

        </div>

    </body>
</html>