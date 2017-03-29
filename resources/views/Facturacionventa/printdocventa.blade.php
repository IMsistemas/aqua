<!DOCTYPE html>
<html>
<head>
	<title>Documento venta</title>
	<style type="text/css">
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
	</style>
</head>
<body>
 
 <table width="100%" border="0">
 	<tr>
 		<th colspan="10" class="text-center">
 			Documento Venta
 		</th>
 	</tr>
 	<tr>
 		<th class="text-left">Fecha de registro:</th>
 		<td colspan="4" class="text-left"><?= $aux_venta["fecharegistrocompra"]?></td>
 		<th class="text-left">Registro de venta:</th>
 		<td colspan="4" class="text-left"><?=str_pad($aux_venta["codigoventa"], 7, "0", STR_PAD_LEFT); ?> </span></td>
 	</tr>
 	<tr>
 		<th colspan="10" class="text-left"> 
 			Datos Cliente
 		</th>
 	</tr>
 	<tr>
 		<th colspan="10"></th>
 	</tr>
 	<tr>
 		<th class="text-left">Ruc/Ci:</th>
 		<td class="text-left" colspan="4"><?= $aux_cliente["documentoidentidad"] ?></td>
 		<th class="text-left">Razon social:</th>
 		<td class="text-left" colspan="4"><?= $aux_cliente["apellidos"]." ".$aux_cliente["nombres"] ?></td>
 	</tr>
 	<tr>
 		<th class="text-left">Telefono:</th>
 		<td class="text-left" colspan="4"><?= $aux_cliente["telefonosecundariodomicilio"] ?></td>
 		<th class="text-left">Direccion:</th>
 		<td class="text-left" colspan="4"><?= $aux_cliente["direcciondomicilio"] ?></td>
 	</tr>
 	<tr>
 		<th colspan="10" class="text-left"> 
 			Datos del documento
 		</th>
 	</tr>
 	<tr>
 		<th colspan="10"></th>
 	</tr>
 	<tr>
 		<th class="text-left">Numero documento:</th>
 		<td class="text-left" colspan="4"><?= $aux_venta["numerodocumento"] ?></td>
 		<th class="text-left">Autorizacion:</th>
 		<td class="text-left" colspan="4"><?= $aux_venta["autorizacionfacturar"] ?></td>
 	</tr>
 	<tr>
 		<th class="text-left">Forma Pago:</th>
 		<td class="text-left" colspan="4"><?= $aux_venta["pago"]->nombreformapago ?></td>
 		<th class="text-left">Vendedor:</th>
 		<td class="text-left" colspan="4"><?= $aux_venta["puntoventa"]->empleado->apellidos." ".$aux_venta["puntoventa"]->empleado->nombres ?></td>
 	</tr>
 	<tr>
 		<th colspan="10" class="text-left"> 
 			Detalle de la venta
 		</th>
 	</tr>
 	<tr>
 		<th colspan="10"></th>
 	</tr>
 </table>

 <table width="100%" style="border-collapse: collapse !important; border: 1px solid #ddd !important;">
 	<thead>
 		<tr  style="background-color: #337ab7;">
	 		<th>Tipo Venta</th>
	 		<th>Bodega</th>
	 		<th>Cod. Prod</th>
	 		<th>Detalle</th>
	 		<th>Cantidad</th>
	 		<th>PVP Unitario</th>
	 		<th>IVA</th>
	 		<th>Total</th>
	 	</tr>
 	</thead>
 	<tbody>
 		<?php foreach ($aux_venta["productosenventa"] as $item):?>
 			<tr>
 				<td class="text-center">Producto</td>
 				<td class="text-center"><?= $item["idbodega"] ?></td>
 				<td class="text-center"><?= $item["codigoproducto"] ?></td>
 				<td class="text-center"><?= $item["producto"]->nombreproducto ?></td>
 				<td class="text-center"><?= $item["cantidad"] ?></td>
 				<td class="text-center"><?= $item["precio"] ?></td>
 				<td class="text-center"><?= $item["porcentajeiva"] ?></td>
 				<td class="text-center"><?= ($item["cantidad"]* $item["precio"]) ?></td>
 			</tr>
 		<?php  endforeach;?>
 		<tr>
 			<td colspan="5" rowspan="6">
 				<strong> Comentario:  </strong>  <?= $aux_venta["comentario"] ?>
 			</td>
 			<td rowspan="6">
 				<strong> Descuento:  </strong> <?= $aux_venta["procentajedescuentocompra"] ?> %
 			</td>
 			<th class="text-left"> Subtotal 14% </th>
 			<td><?= $aux_venta["subtotalivaventa"] ?></td>
 		</tr>
 		<tr>
 			<th class="text-left"> Subtotal 0% </th>
 			<td><?= $aux_venta["subtotalnoivaventa"] ?></td>
 		</tr>
 		<tr>
 			<th class="text-left"> Descuento </th>
 			<td><?= $aux_venta["descuentoventa"] ?></td>
 		</tr>
 		<tr>
 			<th class="text-left"> Otros </th>
 			<td><?= $aux_venta["otrosvalores"] ?></td>
 		</tr>
 		<tr>
 			<th class="text-left"> IVA </th>
 			<td><?= $aux_venta["ivaventa"] ?></td>
 		</tr>
 		<tr>
 			<th class="text-left"> Total </th>
 			<td><?= $aux_venta["totalventa"] ?></td>
 		</tr>
 	</tbody>
 </table>

</body>
</html>