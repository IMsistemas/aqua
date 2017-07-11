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
		<h3><strong> Balance De Comprobación </strong></h3>
	</div>
	<div class="col-xs-12 text-center">
		<h4><strong>Desde: <?= $filtro->FechaI ?>  Hasta : <?= $filtro->FechaF ?> </strong>   <strong>  Moneda: USD $ </strong> </h4>
	</div>
	<div class="col-xs-12 text-right">
		<h4><strong>Fecha: <?= $today ?> </strong></h4>
	</div>

	<div class="col-xs-12">
	<?php
		function orden_plan_cuenta($orden)
	    {
	        $aux_orden=explode('.', $orden);
	        $aux_numero_orden="";
	        $aux_numero_completar="";
	        $tam=count($aux_orden);
	        if($tam>0){
	              for($x=0;$x<$tam;$x++){
	                if($x<3){
	                    $aux_numero_orden.=$aux_orden[$x];
	                }elseif($x>=3){
	                    if($x==3){
	                        $aux_numero_completar=$aux_orden[$x];
	                        if(strlen ((string)$aux_numero_completar)==1){
	                            $aux_numero_completar="0".$aux_numero_completar;
	                        }
	                        $aux_numero_orden.=$aux_numero_completar;
	                    }elseif($x>3){
	                        $aux_numero_orden.=$aux_orden[$x];
	                    }

	                }
	            }
	        }else{
	           $aux_numero_orden=$orden;
	        }
	        
	        return $aux_numero_orden;
	    }
	?>
		<table class="table table-bordered">
			<thead>
				<tr>
					<th colspan="2"  class="text-center">Cuentas</th>
					<th colspan="2"  class="text-center">Sumas</th>
					<th colspan="2"  class="text-center">Saldos</th>
				</tr>
				<tr>
					<th>Código</th>
					<th>Cuenta</th>
					<th>Debe</th>
					<th>Haber</th>
					<th>Debe</th>
					<th>Haber</th>
				</tr>
			</thead>
			<tbody>
				<?php
					$aux_debe=0;
					$aux_haber=0;
					$aux_debe_saldo=0;
					$aux_haber_saldo=0;
				?>
				<?php foreach ($comprobacion as $item):?>
		 			<tr>
		 				<td class=""><?= orden_plan_cuenta($item->jerarquia)  ?></td>
		 				<td class=""><?= $item->concepto ?></td>
		 				<td class="text-right"><?= "$ ".number_format($item->debe,4,'.',',') ?></td>
		 				<td class="text-right"><?= "$ ".number_format($item->haber,4,'.',',') ?></td>
		 				<td class="text-right"><?= "$ ".number_format($item->saldo_debe,4,'.',',') ?></td>
		 				<td class="text-right"><?= "$ ".number_format($item->saldo_haber,4,'.',',') ?></td>
		 				<?php 
		 					$aux_debe+=((float) $item->debe);
		 					$aux_haber+=((float) $item->haber);
		 					$aux_debe_saldo+=((float) $item->saldo_debe);
		 					$aux_haber_saldo+=((float) $item->saldo_haber);
		 				?>
		 			</tr>
		 		<?php  endforeach;?>
			</tbody>
			<tfoot>
				<tr>
					<th colspan="2" class="text-center">Total:</th>
					<th class="text-right"><?= "$ ".number_format($aux_debe,4,'.',',') ?></th>
					<th class="text-right"><?= "$ ".number_format($aux_haber,4,'.',',') ?></th>
					<th class="text-right"><?= "$ ".number_format($aux_debe_saldo,4,'.',',') ?></th>
					<th class="text-right"><?= "$ ".number_format($aux_haber_saldo,4,'.',',') ?></th>
				</tr>
			</tfoot>
		</table>
	</div>



</body>
</html>