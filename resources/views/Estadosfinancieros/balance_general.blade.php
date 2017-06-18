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
		<h3><strong><?= $aux_empresa[0]->nombrecomercial ?></strong></h3>
	</div>
 	<div class="col-xs-12 text-center">
		<h3><strong>Estados Situacion Finaciera </strong></h3>
	</div>
	<div class="col-xs-12 text-center">
		<h4><strong>Hasta : <?= $filtro->FechaF ?> </strong></h4>
	</div>
	<div class="col-xs-12 text-right">
		<h4><strong>Fecha: <?= $today ?> </strong></h4>
	</div>

	<div class="col-xs-12">
		<table class="table table-bordered">
			<thead>
				<tr>
					<th colspan="3">Estados Situacion Finaciera: <?= $filtro->FechaF ?> </th>
				</tr>
				<tr>
					<th>Código</th>
					<th>Cuenta</th>
	        		<th>Balance</th>
				</tr>
			</thead>
			<tbody>
				<?php 
				$aux_total_activo=0;
				$aux_cont=0;
				foreach ($balance_general_contable["Activo"] as $item):?>
		 			<tr>
		 				<td class=""><?= $item["aux_jerarquia"] ?></td>
		 				<td class=""><?= $item["concepto"] ?></td>
		 				<td class="text-right"><?php 
		 					if($aux_cont==0){
		 						$aux_total_activo=$item["balance"];
		 					}
		 					if(((float) $item["saldo"] )==0){
		 						echo '';
		 					}else{
		 						echo "$ ".number_format($item["saldo"],4,'.',',');
		 					}
		 					$aux_cont++;
		 				 ?></td>
		 			</tr>
		 		<?php  endforeach;?>
		 		<tr>
		 			<th colspan="2" class="text-right">Total Activo</th>
		 			<th class="text-right"><?=  "$ ".number_format($aux_total_activo,4,'.',',') ?></th>
		 		</tr>
		 		<tr>
		 			<th colspan="3"></th>
		 		</tr>

		 		<?php 
				$aux_total_pasivo=0;
				$aux_cont2=0;
				foreach ($balance_general_contable["Pasivo"] as $item1):?>
		 			<tr>
		 				<td class=""><?= $item1["aux_jerarquia"] ?></td>
		 				<td class=""><?= $item1["concepto"] ?></td>
		 				<td class="text-right"><?php 
		 					if($aux_cont2==0){
		 						$aux_total_pasivo=$item1["balance"];
		 					}
		 					if(((float) $item1["saldo"] )==0){
		 						echo '';
		 					}else{
		 						echo "$ ".number_format($item1["saldo"],4,'.',',');
		 					}
		 					$aux_cont2++;
		 				 ?></td>
		 			</tr>
		 		<?php  endforeach;?>
		 		<tr>
		 			<th colspan="2" class="text-right">Total Pasivo</th>
		 			<th class="text-right"><?=  "$ ".number_format($aux_total_pasivo,4,'.',',') ?></th>
		 		</tr>
		 		<tr>
		 			<th colspan="3"></th>
		 		</tr>

		 		<?php 
				$aux_total_patrimonio=0;
				$aux_cont3=0;
				foreach ($balance_general_contable["Patrimonio"] as $item2):?>
		 			<tr>
		 				<td class=""><?= $item2["aux_jerarquia"] ?></td>
		 				<td class=""><?= $item2["concepto"] ?></td>
		 				<td class="text-right"><?php 
		 					if($aux_cont3==0){
		 						$aux_total_patrimonio=$item2["balance"];
		 					}
		 					if(((float) $item2["saldo"] )==0){
		 						echo '';
		 					}else{
		 						echo "$ ".number_format($item2["saldo"],4,'.',',');
		 					}
		 					$aux_cont3++;
		 				 ?></td>
		 			</tr>
		 		<?php  endforeach;?>
		 		<tr>
		 			<th colspan="2" class="text-right">Total Patrimonio</th>
		 			<th class="text-right"><?=  "$ ".number_format($aux_total_patrimonio,4,'.',',') ?></th>
		 		</tr>

		 		<tr>
		 			<th colspan="3"></th>
		 		</tr>

		 		<tr>
		 			<th colspan="2">Total Pasivo + Patrimonio</th>
		 			<th class="text-right">
		 				<?php 
		 					echo "$ ".number_format(( ((float) $aux_total_pasivo) + ((float) $aux_total_patrimonio) ),4,'.',',');
		 				?>
		 			</th>
		 		</tr>
			</tbody>
			<tfoot>
				<tr>
					<th colspan="3">

					</th>
				</tr>
			</tfoot>
		</table>
	</div>

	<div class="col-xs-6 text-center">
		<strong>REPRESENTANTE LEGAL</strong>
	</div>
	<div class="col-xs-6 text-center">
		<strong>CONTADOR</strong>
	</div>


</body>
</html>