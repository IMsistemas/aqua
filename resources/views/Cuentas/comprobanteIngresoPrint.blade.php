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

	<div class="col-xs-12">
		<div class="col-xs-6" style="font-size: 14px;">
			<strong><?= $aux_empresa[0]->nombrecomercial ?> </strong>
		</div>

		<div class="col-xs-6 text-center">
            <?= $today ?>
		</div>
	</div>

	<br>

	<div class="col-xs-12 text-right" style="margin-top: 20px;">
		<h4><strong>COMPROBANTE DE INGRESO No.  <?= $cobro[0]->idcuentasporcobrar ?></strong></h4>
	</div>


	<div class="col-xs-12" style="font-size: 12px !important;">

		<table class="table table-responsive table-striped table-hover table-condensed table-bordered">

			<tbody>

				<tr>
					<td style="width: 80% !important;"><strong>RECIBI DE:</strong> </td>
					<td class="text-right" style="width: 20%;">
						<strong>USD $:</strong> <span style="margin-top: 1px;"><?= $cobro[0]->valorpagado ?></span>
					</td>
				</tr>
				<tr>
					<td colspan="2" style="width: 100%;"><strong>LA CANTIDAD DE:</strong> </td>
				</tr>
				<tr>
					<td style="width: 80%;">
						<strong>CONCEPTO:</strong> <span style="margin-top: 1px;"><?= strtoupper($cobro[0]->descripcion) ?></span>
					</td>
					<td class="text-right" style="width: 20%;">
						<strong>FECHA:</strong> <span style="margin-top: 1px;"><?= $cobro[0]->fecharegistro ?></span>
					</td>
				</tr>

			</tbody>

		</table>

		<table class="table table-responsive table-striped table-hover table-condensed table-bordered" style="margin-top: -20px;">

			<thead>
				<tr>
					<th style="width: 19%">CUENTA</th>
					<th style="width: 5%">CC</th>
					<th style="width: 46%">DETALLE</th>
					<th style="width: 15%">DEBITO</th>
					<th style="width: 15%">CREDITO</th>
				</tr>
			</thead>

			<tbody>

            <?php
				$debito = 0;
				$credito = 0;
            ?>

            <?php foreach ($registro as $item):?>
				<tr>

					<td><?= $item->jerarquia ?></td>
					<td></td>
					<td><?= $item->concepto ?></td>
					<td class="text-right"><?= number_format($item->debe, 2, '.', ',') ?></td>
					<td class="text-right"><?= number_format($item->haber, 2, '.', ',') ?></td>

				</tr>

				<?php
					$debito += ((float) $item->debe);
					$credito += ((float) $item->haber);
				?>
            <?php  endforeach;?>

			</tbody>

		</table>

		<table class="table table-responsive table-striped table-hover table-condensed table-bordered" style="margin-top: -20px;">

			<thead>
				<tr>
					<th style="width: 24%">ELABORADO</th>
					<th style="width: 23%">GERENTE</th>
					<th style="width: 23%">CONTABILIZADO</th>
					<th class="text-right" style="width: 15%"><?= number_format($debito, 2, '.', ',') ?></th>
					<th class="text-right" style="width: 15%"><?= number_format($credito, 2, '.', ',') ?></th>
				</tr>
			</thead>

			<tbody>

				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td style="height: 35px;" colspan="2"></td>
				</tr>

			</tbody>

		</table>

	</div>



</body>
</html>