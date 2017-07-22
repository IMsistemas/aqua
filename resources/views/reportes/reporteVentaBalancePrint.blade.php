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
		<h3><strong> Reporte de Ventas / Balance </strong></h3>
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
					<th style="width: 4%;">NO.</th>
					<th style="width: 8%;">JERARQUIA</th>
					<th >CONCEPTO</th>
					<th style="width: 11%;">FACTURA VENTA</th>
				</tr>
			</thead>
			<tbody>

				<?php
					$total = 0;
					$i = 0;
				?>

                <?php foreach ($comprobacion as $item):?>
					<tr>

						<td><?= ++$i ?></td>
						<td><?= $item->jerarquia ?></td>
						<td class="text-center"><?= $item->concepto ?></td>
						<td class="text-right"><?= '$ ' . number_format($item->totalfactura, 2, '.', ',') ?></td>

						<?php

                        	$total += ((float) $item->totalfactura);

						?>

					</tr>
                <?php  endforeach;?>

			</tbody>
			<tfoot>
				<tr>
					<th colspan="3" class="text-right">TOTALES</th>
					<th class="text-right btn-success" style="font-weight: bold;"><?= '$ ' . number_format($total, 2, '.', ',') ?></th>
				</tr>
			</tfoot>
		</table>


	</div>

</body>
</html>