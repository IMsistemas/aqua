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

			<tbody style="border-bottom: none;">

				<tr >
					<td style="width: 80% !important;">
						<strong>RECIBI DE:</strong> <span style="margin-top: 1px;"><?= strtoupper($cobro[0]->razonsocial) ?></span>
					</td>
					<td class="text-right" style="width: 20% !important;">
						<strong>USD $:</strong> <span style="margin-top: 1px;"><?= $cobro[0]->valorpagado ?></span>
					</td>
				</tr>
				<tr>

                    <?php
						/*!
						  @function num2letras ()
						  @abstract Dado un n?mero lo devuelve escrito.
						  @param $num number - N?mero a convertir.
						  @param $fem bool - Forma femenina (true) o no (false).
						  @param $dec bool - Con decimales (true) o no (false).
						  @result string - Devuelve el n?mero escrito en letra.

						*/
						function num2letras($num, $fem = false, $dec = true) {
							$matuni[2]  = "dos";
							$matuni[3]  = "tres";
							$matuni[4]  = "cuatro";
							$matuni[5]  = "cinco";
							$matuni[6]  = "seis";
							$matuni[7]  = "siete";
							$matuni[8]  = "ocho";
							$matuni[9]  = "nueve";
							$matuni[10] = "diez";
							$matuni[11] = "once";
							$matuni[12] = "doce";
							$matuni[13] = "trece";
							$matuni[14] = "catorce";
							$matuni[15] = "quince";
							$matuni[16] = "dieciseis";
							$matuni[17] = "diecisiete";
							$matuni[18] = "dieciocho";
							$matuni[19] = "diecinueve";
							$matuni[20] = "veinte";
							$matunisub[2] = "dos";
							$matunisub[3] = "tres";
							$matunisub[4] = "cuatro";
							$matunisub[5] = "quin";
							$matunisub[6] = "seis";
							$matunisub[7] = "sete";
							$matunisub[8] = "ocho";
							$matunisub[9] = "nove";

							$matdec[2] = "veint";
							$matdec[3] = "treinta";
							$matdec[4] = "cuarenta";
							$matdec[5] = "cincuenta";
							$matdec[6] = "sesenta";
							$matdec[7] = "setenta";
							$matdec[8] = "ochenta";
							$matdec[9] = "noventa";
							$matsub[3]  = 'mill';
							$matsub[5]  = 'bill';
							$matsub[7]  = 'mill';
							$matsub[9]  = 'trill';
							$matsub[11] = 'mill';
							$matsub[13] = 'bill';
							$matsub[15] = 'mill';
							$matmil[4]  = 'millones';
							$matmil[6]  = 'billones';
							$matmil[7]  = 'de billones';
							$matmil[8]  = 'millones de billones';
							$matmil[10] = 'trillones';
							$matmil[11] = 'de trillones';
							$matmil[12] = 'millones de trillones';
							$matmil[13] = 'de trillones';
							$matmil[14] = 'billones de trillones';
							$matmil[15] = 'de billones de trillones';
							$matmil[16] = 'millones de billones de trillones';

							//Zi hack
							$float=explode('.',$num);
							$num=$float[0];

							$num = trim((string)@$num);
							if ($num[0] == '-') {
								$neg = 'menos ';
								$num = substr($num, 1);
							}else
								$neg = '';
							while ($num[0] == '0') $num = substr($num, 1);
							if ($num[0] < '1' or $num[0] > 9) $num = '0' . $num;
							$zeros = true;
							$punt = false;
							$ent = '';
							$fra = '';
							for ($c = 0; $c < strlen($num); $c++) {
								$n = $num[$c];
								if (! (strpos(".,'''", $n) === false)) {
									if ($punt) break;
									else{
										$punt = true;
										continue;
									}

								}elseif (! (strpos('0123456789', $n) === false)) {
									if ($punt) {
										if ($n != '0') $zeros = false;
										$fra .= $n;
									}else

										$ent .= $n;
								}else

									break;

							}
							$ent = '     ' . $ent;
							if ($dec and $fra and ! $zeros) {
								$fin = ' coma';
								for ($n = 0; $n < strlen($fra); $n++) {
									if (($s = $fra[$n]) == '0')
										$fin .= ' cero';
									elseif ($s == '1')
										$fin .= $fem ? ' una' : ' un';
									else
										$fin .= ' ' . $matuni[$s];
								}
							}else
								$fin = '';
							if ((int)$ent === 0) return 'Cero ' . $fin;
							$tex = '';
							$sub = 0;
							$mils = 0;
							$neutro = false;
							while ( ($num = substr($ent, -3)) != '   ') {
								$ent = substr($ent, 0, -3);
								if (++$sub < 3 and $fem) {
									$matuni[1] = 'una';
									$subcent = 'as';
								}else{
									$matuni[1] = $neutro ? 'un' : 'uno';
									$subcent = 'os';
								}
								$t = '';
								$n2 = substr($num, 1);
								if ($n2 == '00') {
								}elseif ($n2 < 21)
									$t = ' ' . $matuni[(int)$n2];
								elseif ($n2 < 30) {
									$n3 = $num[2];
									if ($n3 != 0) $t = 'i' . $matuni[$n3];
									$n2 = $num[1];
									$t = ' ' . $matdec[$n2] . $t;
								}else{
									$n3 = $num[2];
									if ($n3 != 0) $t = ' y ' . $matuni[$n3];
									$n2 = $num[1];
									$t = ' ' . $matdec[$n2] . $t;
								}
								$n = $num[0];
								if ($n == 1) {
									$t = ' ciento' . $t;
								}elseif ($n == 5){
									$t = ' ' . $matunisub[$n] . 'ient' . $subcent . $t;
								}elseif ($n != 0){
									$t = ' ' . $matunisub[$n] . 'cient' . $subcent . $t;
								}
								if ($sub == 1) {
								}elseif (! isset($matsub[$sub])) {
									if ($num == 1) {
										$t = ' mil';
									}elseif ($num > 1){
										$t .= ' mil';
									}
								}elseif ($num == 1) {
									$t .= ' ' . $matsub[$sub] . '?n';
								}elseif ($num > 1){
									$t .= ' ' . $matsub[$sub] . 'ones';
								}
								if ($num == '000') $mils ++;
								elseif ($mils != 0) {
									if (isset($matmil[$sub])) $t .= ' ' . $matmil[$sub];
									$mils = 0;
								}
								$neutro = true;
								$tex = $t . $tex;
							}
							$tex = $neg . substr($tex, 1) . $fin;
							//Zi hack --> return ucfirst($tex);
							$end_num=ucfirst($tex).' dolares '.$float[1].'/100 centavos';
							return $end_num;
						}
                    ?>

					<td colspan="2" style="width: 100%;">
						<strong>LA CANTIDAD DE:</strong>
						<span style="margin-top: 1px;"><?= strtoupper(num2letras(number_format($cobro[0]->valorpagado, 2, '.', ''))) ?></span>
					</td>
				</tr>
				<tr>
					<td>
						<strong>CONCEPTO:</strong> <span style="margin-top: 1px;"><?= strtoupper($cobro[0]->descripcion) ?></span>
					</td>
					<td class="text-right">
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