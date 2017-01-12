<!DOCTYPE html>
<html lang="en-US" ng-app="softver-erp" ng-controller="comprasImprimirController" ng-init="constants={id:'[[$id]]'}">
<head>
<title>Compras Inventario</title>

<!-- Load Bootstrap CSS -->
<link href="<?= asset('css/bootstrap.min.css') ?>" rel="stylesheet">
<link href="<?= asset('css/font-awesome.min.css') ?>" rel="stylesheet">
<link href="<?= asset('css/bootstrap-datetimepicker.min.css') ?>"
	rel="stylesheet">
<link href="<?= asset('css/index.css') ?>" rel="stylesheet">
<link href="<?= asset('css/angucomplete-alt.css') ?>" rel="stylesheet">


<link href="<?= asset('css/style_generic_app.css') ?>" rel="stylesheet">
<style type="text/css"> 
thead:before, thead:after { display: none !important; } 
tbody:before, tbody:after { display: none !important; }
</style>



</head>
<body>
	<input type="hidden" value="<?=$id?>" id="idcompra">
	<div class="container"  >
		<div>
		<?php if($imprimir):?>
			<div class="col-sm-12 hidden-print" style="text-align: right;">
				<a href ng-click="imprimir1()"> <span
					class="glyphicon glyphicon-print"></span>&nbsp;Imprimir
				</a>
			</div>
			<?php endif;?>
			<img src="{{asset('img/logo.png')}}" style="height: 80px">
			<h1 style="text-align: center; margin-top: 0px;">Compras Inventario</h1>

		</div>

		<div style="padding-top: 20px;">
			<table style="width: 100%">
				<tr>
					<td style="width: 20%"><label class="control-label">Fecha Registro:</label></td>
					<td style="width: 30%"><span class="control-label" style="display: inline-block">{{
							$producto->fecharegistrocompra }}</span></td>
					<td style="width: 20%"><label class="control-label">Registro Compra No:</label></td>
					<td style="width: 30%"><span class="control-label"><?=str_pad($producto->codigocompra, 7, "0", STR_PAD_LEFT); ?> </span></td>
				</tr>
			</table>

			<div class="form-group" style="border-bottom: 1px solid">
				<label class="control-label">Datos Proveedor</label>
			</div>

			<table style="width: 100%">
				<tr>
					<td style="width: 20%"><label class="control-label">Ruc/CI:</label></td>
					<td style="width: 30%"><span class="control-label"><?=$producto->proveedor->documentoproveedor?></span></td>
					<td style="width: 20%"><label class="control-label">Razón Social:</label></td>
					<td style="width: 30%"><span class="control-label"><?=$producto->proveedor->razonsocialproveedor?></span></td>
				</tr>
				<tr>
					<td ><label class="control-label">Teléfono:</label></td>
					<td ><span id="telefono" class="control-label"><?=$producto->proveedor->telefonoproveedor?></span></td>
					<td ><label class="control-label">Dirección:</label></td>
					<td ><span id="direccion" class="control-label"><?=$producto->proveedor->direccionproveedor?></span></td>
				</tr>
				<tr>
					<td><label class="control-label">Tipo id. proveedor:</label></td>
					<td><span id="tipoidproveedor" class="control-label"
							style="display: inline-block"><?=$producto->proveedor->codigotipoid ." - ".$producto->proveedor->tipoidentificacion?></span></td>
					<td><?php if((($producto->proveedor->codigotipoid == '01')||($producto->proveedor->codigotipoid == '02')||($producto->proveedor->codigotipoid == '03'))):?>
						<label class="control-label">Parte Relacionada:</label> <?php endif;?></td>
					<td><?php if((($producto->proveedor->codigotipoid == '01')||($producto->proveedor->codigotipoid == '02')||($producto->proveedor->codigotipoid == '03'))):?>
					<span id="relacionada" class="control-label">Si</span><?php endif;?></td>
				</tr>
				<tr>
					<td><label class="control-label">Tipo Proveedor:</label></td>
					<td><span id="tipoproveedor" class="control-label"
							style="display: inline-block"><?=$producto->proveedor->idtipoproveedor ." - ".$producto->proveedor->nombretipoproveedor?></span></td>
					<td><label class="control-label">Ciudad:</label></td>
					<td><span id="ciudad" class="control-label"><?=$producto->proveedor->nombreciudad?></span></td>
				</tr>
			</table>

<div class="form-group" style="border-bottom: 1px solid">
				<label class="control-label">Datos Documento</label>
			</div>
			<table style="width: 100%">
				<tr>
					<td style="width: 20%"><label class="control-label">Fecha Emisión:</label></td>
					<td style="width: 30%"><span class="control-label" style="display: inline-block"><?=$producto->fechaemisionfacturaproveedor?></span></td>
					<td style="width: 20%"><label class="control-label">Fecha Caducidad:</label></td>
					<td style="width: 30%"><span class="control-label" style="display: inline-block"><?=$producto->fechacaducidad?></span></td>
				</tr>
				<tr>
					<td><label class="control-label">Número de documento:</label></td>
					<td><span class="control-label" style="display: inline-block"><?=$producto->numerodocumentoproveedor?></span></td>
					<td><label class="control-label">Tipo Comprobante:</label></td>
					<td><span class="control-label" style="display: inline-block"><?=$producto->codigocomprbante ." - ".$producto->nombretipocomprobante?></span></td>
				</tr>
				<tr>
					<td><label class="control-label">Autorización:</label></td>
					<td><span class="control-label" style="display: inline-block"><?=$producto->autorizacionfacturaproveedor?></span></td>
					<td><label class="control-label">Sustento Tributario:</label></td>
					<td><span class="control-label" style="display: inline-block"><?=$producto->codigosustento ." - ".$producto->nombresustento?></span></td>
				</tr>
				<tr>
					<td><label class="control-label">Forma Pago:</label></td>
					<td><span class="control-label" style="display: inline-block"><?=$producto->nombreformapago?></span></td>
					<td></td>
					<td></td>
				</tr>
			</table>		
<div class="form-group" style="border-bottom: 1px solid">
				<label class="control-label">Detalle Compra</label>
			</div>

			
				<table
					class="table table-responsive table-striped table-hover table-condensed">
					<thead class="bg-primary">
						<tr>
							<th style="width: 20%;">Bodega</th>
							<th style="width: 15%;">Cod. Prod</th>
							<th style="width: 7%;">Cant.</th>
							<th>Detalle</th>
							<th style="width: 10%;">PVP Unitario</th>
							<th style="width: 7%;">IVA</th>
							<th style="width: 7%;">ICE</th>
							<th style="width: 5%;">Total</th>

						</tr>
					</thead>
					
						<?php foreach ($producto->detalles as $item):?>
							<tr>
							<td><span class="control-label" style="display: inline-block"><?=$item->bodega?></span>

							</td>
							<td><span class="control-label" style="display: inline-block"><?=$item->codigoproducto?></span>

							</td>
							</td>
							<td><span class="control-label" style="display: inline-block"><?=$item->cantidadtotal?></span>

							</td>
							<td><span class="control-label" style="display: inline-block"><?=$item->nombreproducto?></span>

							</td>
							<td><span class="control-label" style="display: inline-block"><?=$item->precioUnitario?></span>

							</td>
							<td><span class="control-label" style="display: inline-block"><?=$item->iva?></span>

							</td>
							<td><span class="control-label" style="display: inline-block"><?=$item->ice?></span>
							</td>
							<td><span class="control-label" style="display: inline-block"><?=$item->total?></span>
							</td>

						</tr>
							<?php  endforeach;?>
						
				</table>
			<div class="form-group" style="border-bottom: 1px solid">
				<label class="control-label">Datos Pago</label>
			</div>
			
			<table style="width: 100%">
				<tr>
					<td style="width: 20%"><label class="control-label">País Pago:</label></td>
					<td style="width: 30%"><?php if($producto->codigotipopago == '01'):?>
							<span class="control-label" style="display: inline-block">Residente</span>
							<?php else:?>
								<span class="control-label" style="display: inline-block"><?=$producto->nombrepais?></span>
							<?php endif;?></td>
					<td style="width: 20%"></td>
					<td style="width: 30%"></td>
				</tr>
				<tr>
					<td VALIGN="TOP"><label class="control-label">Forma Pago:</label></td>
					<td VALIGN="TOP"><span class="control-label" style="display: inline-block"><?=$producto->codigoformapago ." - ".$producto->forma?></span></td>
					<td colspan="2">
						<table  style="width: 100%">
							<tr>
								<td style="width: 10%"><span class="control-label" style="display: inline-block"><?=$producto->procentajedescuentocompra?></span></td>
								<td style="width: 50%"><label> % Descuento</label></td>
								<td style="width: 20%"><label> Subtotal 14%:</label></td>
								<td  style="width: 20%"><span class="control-label" style="display: inline-block"><?=$producto->subtotalivacompra?></span></td>						
							</tr>			
							<tr>
								<td></td>
								<td></td>
								<td><label> Subtotal 0%:</label></td>
								<td><span class="control-label" style="display: inline-block"><?=$producto->subtotalnoivacompra?></span></td>
							</tr>
							<tr>
								<td></td>
								<td></td>
								<td><label>Descuento:</label></td>
								<td><span class="control-label" style="display: inline-block"><?=$producto->descuentocompra?></span></td>
							</tr>
							<tr>
								<td></td>
								<td></td>
								<td><label> Otros:</label></td>
								<td><span class="control-label" style="display: inline-block"><?=$producto->otrosvalores?></span></td>
							</tr>
							<tr>
								<td></td>
								<td></td>
								<td><label> IVA:</label></td>
								<td><span class="control-label" style="display: inline-block"><?=$producto->ivacompra?></span></td>
							</tr>
							<tr>
								<td></td>
								<td></td>
								<td><label> Total:</label></td>
								<td><span class="control-label" style="display: inline-block"><?=$producto->totalcompra?></span></td>
							</tr>
						</table>
						
			</td>
			</tr>
			</table>
			<br><br>
			</div>
		</div>
		
		 <div class="modal fade" tabindex="-1" role="dialog" id="modalConfirmAnular">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header modal-header-danger">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Confirmación</h4>
                    </div>
                    <div class="modal-body">
                        <span>Se imprimio correctamente la compra?</span>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" id="btn-save" ng-click="imprimirCompra()">Si</button>
                        <button type="button" class="btn btn-danger" id="btn-save" ng-click="anularCompra()">No, Anular compra</button>
                    </div>
                </div>
            </div>
        </div>	
	<style type="text/css" media="print">
	
@media print {
}

@page {
	size: auto A4 landscape;
	margin: 5mm;
}

</style>
<script src="<?= asset('app/lib/angular/angular.min.js') ?>"></script>
	<script src="<?= asset('app/lib/angular/angular-route.min.js') ?>"></script>
	<script src="<?= asset('js/jquery.min.js') ?>"></script>
	 <script src="<?= asset('js/bootstrap.min.js') ?>"></script>
	
	<!-- AngularJS Application Scripts -->
	<script src="<?= asset('app/app.js') ?>"></script>
	<script
		src="<?= asset('app/controllers/comprasImprimirController.js') ?>"></script>
</body>
</html>
