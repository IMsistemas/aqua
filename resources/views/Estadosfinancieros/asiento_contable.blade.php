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
		<h4><strong>COMPROBANTE DE DIARIO No.  
		<?php
								
			echo $data_asc[0]["numcomprobante"];
			
		?>
								
		</strong></h4>
	</div>


	<div class="col-xs-12" style="font-size: 12px !important;">

		<table class="table table-responsive table-striped table-hover table-condensed table-bordered">

			<tbody style="border-bottom: none;">

				<tr>
					<td>
						<strong>CONCEPTO:</strong> <span style="margin-top: 1px;">
							<?php
								
								echo strtoupper($data_asc[0]["descripcion"]);

							?>
						</span>
					</td>
					<td class="text-right">
						<strong>FECHA:</strong> <span style="margin-top: 1px;">
							<?php
								
								echo $data_asc[0]["fechatransaccion"];
								
							?>
						</span>
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
					$aux_debe=0;
					$aux_haber=0;
					foreach ($data_asc[0]["cont_registrocontable"] as $f) {
			            echo "<tr>";
			            echo "<td>".orden_plan_cuenta($f['cont_plancuentas']['jerarquia'])."</td>";
			            echo "<td></td>";
			            echo "<td>".$f['cont_plancuentas']['concepto']."</td>";
			            echo "<td> $ ".number_format($f["debe_c"],4,'.',',')."</td>";
			            echo "<td> $ ".number_format($f["haber_c"],4,'.',',')."</td>";
			            echo "</tr>";
			            $aux_debe+=$f["debe_c"];
			            $aux_haber+=$f["haber_c"];
			        }
				?>
           


			</tbody>

		</table>

		<table class="table table-responsive table-striped table-hover table-condensed table-bordered" style="margin-top: -20px;">

			<thead>
				<tr>
					<th style="width: 24%">ELABORADO</th>
					<th style="width: 23%">GERENTE</th>
					<th style="width: 23%">CONTABILIZADO</th>
					<th class="text-right" style="width: 15%"><?= "$ ".number_format($aux_debe,4,'.',',') ?></th>
					<th class="text-right" style="width: 15%"><?= "$ ".number_format($aux_haber,4,'.',',') ?></th>
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