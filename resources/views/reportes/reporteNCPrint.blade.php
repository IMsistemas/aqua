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
		<h3><strong> Reporte de Facturación Notas de Créditos </strong></h3>
	</div>
	<div class="col-xs-12 text-center">
		<h4><strong>Desde: <?= $filtro->FechaI ?>  Hasta : <?= $filtro->FechaF ?> </strong>   <strong>  Moneda: USD $ </strong> </h4>
	</div>

	<div class="col-xs-12 text-right">
		<h4><strong>Fecha: <?= $today ?> </strong></h4>
	</div>


	<div class="col-xs-12" style="font-size: 12px !important;">

		<table class="table table-responsive table-striped table-hover table-condensed table-bordered">
			<thead>
			<tr>
				<th>NO.</th>
				<th>CLIENTE</th>
				<th style="width: 8%;">FECHA INGRESO</th>
				<th style="width: 11%;">NO FACTURA</th>
				<th style="width: 6%;">SUBTOTAL C/I</th>
				<th style="width: 6%;">SUBTOTAL S/I</th>
				<th style="width: 6%;">SUBTOTAL 0%</th>
				<th style="width: 6%;">SUBTOTAL NO/OBJ</th>
				<th style="width: 6%;">SUBTOTAL EXENTO</th>
				<th style="width: 6%;">IVA</th>
				<th style="width: 6%;">ICE</th>
				<th style="width: 6%;">DESCUENTO</th>
				<th style="width: 9%;">TOTAL</th>
			</tr>
			</thead>
			<tbody>

                <?php
					$subtotalconimpuestoncf = 0;
					$subtotalsinimpuestoncf = 0;
					$subtotalceroncf = 0;
					$subtotalnoobjivancf = 0;
					$subtotalexentivancf = 0;
					$ivancf = 0;
					$icencf = 0;
					$totaldescuento = 0;
					$valortotalncf = 0;

					$i = 0;
                ?>
                <?php foreach ($comprobacion as $item):?>
					<tr>

						<td><?= ++$i ?></td>
						<td><?= $item->razonsocial ?></td>
						<td class="text-center"><?= $item->fecharegistroncf ?></td>
						<td class="text-center"><?= $item->numdocumentonotacredit ?></td>
						<td class="text-right"><?= '$ ' . number_format($item->subtotalconimpuestoncf, 2, '.', ',') ?></td>
						<td class="text-right"><?= '$ ' . number_format($item->subtotalsinimpuestoncf, 2, '.', ',') ?></td>
						<td class="text-right"><?= '$ ' . number_format($item->subtotalceroncf, 2, '.', ',') ?></td>
						<td class="text-right"><?= '$ ' . number_format($item->subtotalnoobjivancf, 2, '.', ',') ?></td>
						<td class="text-right"><?= '$ ' . number_format($item->subtotalexentivancf, 2, '.', ',') ?></td>
						<td class="text-right"><?= '$ ' . number_format($item->ivancf, 2, '.', ',') ?></td>
						<td class="text-right"><?= '$ ' . number_format($item->icencf, 2, '.', ',') ?></td>
						<td class="text-right"><?= '$ ' . number_format($item->totaldescuento, 2, '.', ',') ?></td>
						<td class="text-right" style="font-weight: bold;"><?= '$ ' . number_format($item->valortotalncf, 2, '.', ',') ?></td>

						<?php

							$subtotalconimpuestoncf += ((float) $item->subtotalconimpuestoncf);
							$subtotalsinimpuestoncf += ((float) $item->subtotalsinimpuestoncf);
							$subtotalceroncf += ((float) $item->subtotalceroncf);
							$subtotalnoobjivancf += ((float) $item->subtotalnoobjivancf);
							$subtotalexentivancf += ((float) $item->subtotalexentivancf);
							$ivancf += ((float) $item->ivancf);
							$icencf += ((float) $item->icencf);
							$totaldescuento += ((float) $item->totaldescuento);
							$valortotalncf += ((float) $item->valortotalncf);

						?>

					</tr>
                <?php  endforeach;?>


			</tbody>
			<tfoot>

			<tr>
				<th colspan="4" class="text-right">TOTALES</th>
				<th class="text-right btn-warning" style="color: #000;"><?= '$ ' . number_format($subtotalconimpuestoncf, 2, '.', ',') ?></th>
				<th class="text-right btn-warning" style="color: #000;"><?= '$ ' . number_format($subtotalsinimpuestoncf, 2, '.', ',') ?></th>
				<th class="text-right btn-warning" style="color: #000;"><?= '$ ' . number_format($subtotalceroncf, 2, '.', ',') ?></th>
				<th class="text-right btn-warning" style="color: #000;"><?= '$ ' . number_format($subtotalnoobjivancf, 2, '.', ',') ?></th>
				<th class="text-right btn-warning" style="color: #000;"><?= '$ ' . number_format($subtotalexentivancf, 2, '.', ',') ?></th>
				<th class="text-right btn-warning" style="color: #000;"><?= '$ ' . number_format($ivancf, 2, '.', ',') ?></th>
				<th class="text-right btn-warning" style="color: #000;"><?= '$ ' . number_format($icencf, 2, '.', ',') ?></th>
				<th class="text-right btn-warning" style="color: #000;"><?= '$ ' . number_format($totaldescuento, 2, '.', ',') ?></th>
				<th class="text-right btn-danger" style="font-weight: bold;"><?= '$ ' . number_format($valortotalncf, 2, '.', ',') ?></th>
			</tr>
			</tfoot>
		</table>

	</div>



</body>
</html>