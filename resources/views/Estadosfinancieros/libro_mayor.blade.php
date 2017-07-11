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
		<h3><strong><?= $aux_empresa[0]->nombrecomercial ?>   </strong></h3>
	</div>
    <div class="col-xs-12 text-center">
        <h3><strong> Libro Mayor:  <?= $filtro->Cuenta->concepto ?>  </strong></h3>
    </div>
	<div class="col-xs-12 text-center">
		<h4><strong>En El Periodo Desde: <?= $filtro->FechaI ?>  Hasta : <?= $filtro->FechaF ?> </strong>  <strong>  Moneda: USD $ </strong></h4>
	</div>
	<div class="col-xs-12 text-right">
		<h4><strong>Fecha: <?= $today ?> </strong></h4>
	</div>

	<div class="col-xs-12">
		<table class="table">
			<thead class="">
				<tr>
					<th>Tipo</th>
					<th>Fecha</th>
					<th>Número</th>
					<th>Cuenta</th>
					<th>Descripción</th>
					<th>Debe</th>
					<th>Haber</th>
					<th>Saldo</th>
					<th>Estado</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($libro_mayor as $item):?>
		 			<tr>
		 				<td class=""><?= $item["cont_transaccion"]["cont_tipotransaccion"]["siglas"] ?></td>
		 				<td class=""><?= $item["fecha"] ?></td>
		 				<td class=""><?= $item["idtransaccion"] ?></td>
		 				<td class=""><?= $item["cont_plancuentas"]["concepto"] ?></td>
		 				<td class=""><?= $item["descripcion"] ?></td>
		 				<td class=""><?= "$ ".number_format($item["debe_c"],4,'.',',') ?></td>
		 				<td class=""><?= "$ ".number_format($item["haber_c"],4,'.',',') ?></td>
		 				<td class=""><?= "$ ".number_format($item["saldo"],4,'.',',') ?></td>
		 				<?php if($item["estadoanulado"]==true){
		 					 echo "<td class='bg-success'>Activo</td>";
		 					}
		 					elseif ($item["estadoanulado"]==false) {
		 						echo "<td class='bg-warning'>Anulado</td>";
		 					}
		 				?>
		 			</tr>
		 		<?php  endforeach;?>
			</tbody>
		</table>
	</div>

</body>
</html>