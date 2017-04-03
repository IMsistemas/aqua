<!DOCTYPE html>
<html lang="es-ES" ng-app="softver-aqua">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<title>Guía de Remisión </title>

	 <link href="<?= asset('css/bootstrap.min.css') ?>" rel="stylesheet">
        <link href="<?= asset('css/font-awesome.min.css') ?>" rel="stylesheet">
        <link href="<?= asset('css/index.css') ?>" rel="stylesheet">
        <link href="<?= asset('css/bootstrap-datetimepicker.min.css') ?>" rel="stylesheet">
        <link href="<?= asset('css/angucomplete-alt.css') ?>" rel="stylesheet">
        <link href="<?= asset('css/style_generic_app.css') ?>" rel="stylesheet">

        <style>
            .dataclient{
                font-weight: bold;
            }

            td{
                vertical-align: middle !important;
            }

            .datepicker{
                color: #000 !important;
            }
            .error {
			  border-color: red;
			}
			 
			.warning {
			  border-color: yellow;
			}
 
        </style>

</head>
<body>	

	<div ng-controller="guiaremisionController" ng-cloak ng-init="initLoad()">
		<div ng-show="ActivaGuia=='0'" ng-hide="ActivaGuia=='1'">
			<div class="col-sm-4 col-xs-6">
	                <div class="form-group has-feedback">
	                    <input type="text" class="form-control" id="search" placeholder="BUSCAR..."
	                           ng-model="search" ng-change="searchByFilter()">
	                    <span class="glyphicon glyphicon-search form-control-feedback" aria-hidden="true"></span>
	                </div>
	        </div>
	        <div class="col-sm-8 col-xs-6">	           
	                <button type="button" class="btn btn-primary" style="float: right;" ng-click="ActivaGuia='1';createRow();">
	                   <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
	                </button>
	        </div>

			<div class="col-xs-12" style="margin-top: 5px;">
				<table class="table table-responsive table-striped table-hover table-condensed">
					<thead class="bg-primary">
						<tr>
							<td>Nro.</td>
							<td>Cliente</td>
							<td>Nro. Guía de Remisión</td>
							<td>Nro. Factura Venta</td>
							<td>Acción</td>							
						</tr>
					</thead>
					<tbody>
					<tr>{{item}}</tr>
						<tr dir-paginate="item in guiaremision|orderBy:sortKey:reverse|filter:search|itemsPerPage:10" ng-cloak>
							<td>{{item.iddocumentoguiaremision}}</td>
							<td>{{item.razonsocial}}</td>
							<td>{{item.nrodocumentoguiaremision}}</td>
							<td>{{item.iddocumentoventa==null ?  "No existe venta": item.numdocumentoventa}}</td>
							<td>
								<button type="button" class="btn btn-warning">
					                <span class="glyphicon glyphicon glyphicon-edit" aria-hidden="true" ng-click="editarGuia(item);"></span> 
					            </button>
					            <button type="button" class="btn btn-danger">
					                <span class="glyphicon glyphicon glyphicon-trash" aria-hidden="true" ng-click="delete(item.iddocumentoguiaremision)"></span> 
					            </button>
							</td>
						</tr>
					</tbody>
				</table>
				<dir-pagination-controls
	               on-page-change="pageChanged(newPageNumber)"

                    template-url="dirPagination.html"

                    class="pull-right"
                    max-size="10"
                    direction-links="true"
                    boundary-links="true" >
	            </dir-pagination-controls>
			</div>
		</div>
<!--/-------------------------------------------------------------------/
/-------------------Ingreso Guía Retensión--------------------------/
/-------------------------------------------------------------------/-->
	
		<div ng-show="ActivaGuia=='1'" ng-hide="ActivaGuia=='0'">
				<div class="col-xs-12">
					<form class="form-horizontal" name="formguia" id="formguia">
							<fieldset>
								<legend>Datos Guía de Remisión</legend>
								<div class="col-sm-6 col-xs-12">
									<div class="input-group">
						                <span class="input-group-addon"><i class="glyphicon glyphicon-tag"></i> Nro. Guía de Remisión: </span>
						                <span class="input-group-btn" style="width: 15%;">
						                    <input type="text" class="form-control" id="t_establ" name="t_establ" ng-model="t_establ" ng-keypress="onlyNumber($event,3,'t_establ')" ng-blur="t_establ=calculateLength('t_establ','3')" ng-maxlength="3" maxlength="3">
						                </span>
						                <span class="input-group-btn" style="width: 15%;">
						                    <input type="text" class="form-control" id="t_pto" name="t_pto" ng-model="t_pto" ng-keypress="onlyNumber($event,3,'t_pto')" ng-blur="t_pto=calculateLength('t_pto','3')" ng-maxlength="3" maxlength="3">
						                </span>
						                <input type="text" class="form-control" id="t_sec" name="t_sec" ng-model="t_sec" ng-keypress="onlyNumber($event,9,'t_sec')" ng-blur="t_sec=calculateLength('t_sec','9')" ng-maxlength="9" maxlength="9">
						            </div>
								</div>

								<div class="col-sm-6 col-xs-12">
									<div class="input-group">                        
						                <span class="input-group-addon"><i class="glyphicon glyphicon-tag"></i> Punto Partida: </span>
						                <input type="text" class="form-control" id="puntopartida" name="puntopartida" ng-model="puntopartida" maxlength="300" required >
						            </div> 
						            <span class="help-block error" ng-show="formguia.puntopartida.$touched && formguia.puntopartida.$invalid">El punto de partida es requerido</span>
								</div>

								<div class="col-sm-6 col-xs-12" style="margin-top: 5px;">
									<div class="input-group">                        
						                <span class="input-group-addon"> <i class="glyphicon glyphicon-tag"></i>Identificación Transportista: </span>										
						                <angucomplete-alt 
                                            	  id="citransportista"
									              pause="200"
									              selected-object="Transportista"						
									              input-changed="inputChanged"
												  focus-out="focusOut()"
									              remote-url="{{API_URL}}guiaremision/getransportista/"
									              title-field="numdocidentific"
									              description-field="twitter"   
									              minlength="1"									         
									              input-class="form-control form-control-small"
									              match-class="highlight"
									              field-required="true"
									              input-name="citransportista"
									              text-searching="Buscando Transportista"
									              text-no-results="Transportista no encontrado"
									              initial-value="citransportista"
									              />
									
						          
						            </div>
								</div>

								<div class="col-sm-6 col-xs-12" style="margin-top: 5px;">
									<div class="input-group">                        
						                <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i> Transportista: </span>
						                <input type="text" ng-cloak ng-disabled="true" ng-show="!editar" id="transrazoncocial" name="transportista" ng-model="Transportista.originalObject.razonsocial" class="form-control">
						                <input type="text" ng-cloak ng-disabled="true" ng-show="editar" id="transrazoncocial" name="transportista" ng-model="transrazoncocial" class="form-control">
						            </div>
								</div>

								<div class="col-sm-6 col-xs-12" style="margin-top: 5px;">
									<div class="input-group">                        
						                <span class="input-group-addon"> <i class="glyphicon glyphicon-tag"></i> Nro. Placa: </span>
						                <input type="text" ng-disabled="true" ng-cloak ng-show="!editar" class="form-control" id="placa" name="placa" ng-model="Transportista.originalObject.placa">
						                <input type="text" ng-disabled="true" ng-cloak ng-show="editar" class="form-control" id="placa" name="placa" ng-model="placa">
						            </div>
								</div>

								<div class="col-sm-6 col-xs-12" style="margin-top: 5px;">
									<div class="input-group">                        
						                <span class="input-group-addon"><i class="glyphicon glyphicon-tag"></i> Ruta: </span>
						                <input type="text" class="form-control" id="ruta" name="ruta" ng-model="ruta" required>
						            </div>
						            <span class="help-block error" ng-show="formguia.ruta.$touched && formguia.ruta.$invalid">La ruta es requerida</span>
								</div>	

								<div class="col-sm-6 col-xs-12" style="margin-top: 5px;">
									<div class="input-group">                        
						                <apan class="input-group-addon" ><i class="fa fa-calendar"></i> Fecha Inicio Transporte: </apan>
						                <input type="date" class="form-control" id="finiciotrans" name="finiciotrans" ng-model="finiciotrans" ng-blur="todayinicio('finiciotrans')" required>
						                <span class="input-group-addon"  ><i class="fa fa-calendar"></i></span>      
						            </div>
						             <span class="help-block error" ng-show="formguia.finiciotrans.$touched && formguia.finiciotrans.$invalid">La fecha es requerida</span>
						             <span class="help-block error" ng-show="Menor==1">La fecha es anterior a la actual</span>
								</div>

								<div class="col-sm-6 col-xs-12" style="margin-top: 5px;">
									<div class="input-group">                        
						                <span class="input-group-addon"><i class="fa fa-calendar"></i> Fecha Fin Transporte: </span>
						                <input type="date" class="form-control" id="ffintrans" name="ffintrans| date:'dd/MM/yyyy'" ng-model="ffintrans" ng-blur="todayfin('ffintrans')" required>
						                <span class="input-group-addon"  ><i class="fa fa-calendar"></i></span>
						            </div>
						             <span class="help-block error" ng-show="formguia.ffintrans.$touched && formguia.ffintrans.$invalid">La fecha es requerida</span>
						             <span class="help-block error" ng-show="menor==1">La fecha es anterior a la actual</span>
								</div>

								<div class="col-xs-12" style="margin-top: 5px;">
									<div class="input-group">                        
						                <span class="input-group-addon"><i class="glyphicon glyphicon-tag"></i> Motivo Traslado: </span>
						                <input type="text" class="form-control" id="motivotraslado" name="motivotraslado" ng-keypress="onlyCharasterAndSpace($event)" ng-model="motivotraslado" maxlength="300" required>
						            </div>
						             <span class="help-block error" ng-show="formguia.motivotraslado.$touched && formguia.motivotraslado.$invalid">La ruta es requerida</span>
								</div>

								<div class="col-sm-6 col-xs-12" style="margin-top: 5px;">
									<div class="input-group">                        
						                <span class="input-group-addon"><i class="glyphicon glyphicon-tag"></i> Código Establecimiento: </span>
						                <input type="text" class="form-control" id="codestablecimiento" name="codestablecimiento" ng-keypress="onlyNumber($event,3,'codestablecimiento')" ng-blur="codestablecimiento=calculateLength('codestablecimiento','3')" ng-model="codestablecimiento" maxlength="3">
						            </div>
								</div>

								<div class="col-sm-6 col-xs-12" style="margin-top: 5px;">
									<div class="input-group">                        
						                <span class="input-group-addon"><i class="glyphicon glyphicon-list-alt"></i> Documento Aduanero: </span>
						                <input type="text" class="form-control" id="docaduana" name="docaduana" ng-keypress="onlyNumber($event,20,'docaduana')" ng-model="docaduana" maxlength="20" required>
						            </div>
						             <span class="help-block error" ng-show="formguia.docaduana.$touched && formguia.docaduana.$invalid">La ruta es requerida</span>
								</div>
							</fieldset>

							<br>

							<fieldset>
								<legend>Datos Factura</legend>

								<div class="col-sm-6 col-xs-12">
									<div class="input-group">                        
						                <span class="input-group-addon"><i class="glyphicon glyphicon-tag"></i> Identificación Destinatario: </span>
						                <angucomplete-alt 
                                            	  id="cidestinatario"
									              pause="200"
									              selected-object="Destinatario"						
									              input-changed="inputChanged"
												  focus-out="focusOut()"
									              remote-url="{{API_URL}}guiaremision/getdestinatario/"
									              title-field="numdocidentific"
									              description-field="twitter"   
									              minlength="1"									            
									              input-class="form-control form-control-small"
									              match-class="highlight"
									              field-required="true"
									              input-name="citransportista"
									              -idisablenput="guardado"
									              text-searching="Buscando Destinatario"
									              text-no-results="Destinatario no encontrado"
									              initial-value="cidestinatario"
									              />

						            </div>
						             
								</div>

								<div class="col-sm-6 col-xs-12">
									<div class="input-group">                        
						                <span class="input-group-addon"><i class="glyphicon glyphicon-tag"></i> Razón Social Destinatario: </span>
						                <input type="text" class="form-control" ng-show="!editar" ng-disabled="true" ng-cloak ng-model="Destinatario.originalObject.razonsocial">
						                <input type="text" class="form-control" ng-show="editar" ng-disabled="true" ng-cloak ng-model="destirazonsocial">
						            </div>
								</div>

								<div class="col-sm-6 col-xs-12" style="margin-top: 5px;">
									<div class="input-group">
						                <span class="input-group-addon"><i class="glyphicon glyphicon-tag"></i> Nro Documento Venta: </span>
						                <!--<input type="text" class="form-control" class="form-control input-sm" ng-cloak ng-model="nroventa" id="nroventa" name="nroventa" ng-keyup="BuscarVenta()" ng-keypress="onlyNumber($event,49,'nroventa')">
						                <span class="input-group-addon btn"><i class="glyphicon glyphicon-search"></i> </span>-->
						                <angucomplete-alt 
                                            	  id="nroventa"
									              pause="200"
									              selected-object="venta"						
									              input-changed="inputChanged"
												  focus-out="focusOut()"
									              remote-url="{{API_URL}}guiaremision/venta/"
									              title-field="numdocumentoventa"
									              description-field="twitter"   
									              minlength="1"								              
									              input-class="form-control form-control-small"
									              match-class="highlight"
									              input-name="nroventa"
									              disable-input="guardado"
									              text-searching="Buscando Destinatario"
									              text-no-results="Destinatario no encontrado"
									              initial-value="nroventa"
									              />
						            </div>

								</div>

								<div class="col-sm-6 col-xs-12" style="margin-top: 5px;">
									<div class="input-group">  
									<span class="input-group-addon btn"><i ng-click="BuscarVenta()" class="glyphicon glyphicon-search"></i> </span>                      
						                <span class="input-group-addon"><i class="glyphicon glyphicon-tag"></i> Nro Autorización Venta: </span>
						                <input type="text" class="form-control" ng-disabled="true" ng-cloak ng-model="ventanumautorizacion">
						            </div>
								</div>

								<div class="col-sm-6 col-xs-12" style="margin-top: 5px;">
									<div class="input-group">                        
						                <span class="input-group-addon"><i class="fa fa-calendar"></i> Fecha Venta: </span>
						                <input type="text" class="form-control" ng-disabled="true" ng-cloak ng-model="ventafecha|date: 'dd/MM/yyyy'" >
						                <span class="input-group-addon"  ><i class="fa fa-calendar"></i></span>
						            </div>
								</div>

								<div class="col-sm-6 col-xs-12" style="margin-top: 5px;">
									<div class="input-group">                        
						                <span class="input-group-addon"><i class="glyphicon glyphicon-plane"></i> Destino (Pto Llegada): </span>
						                <input type="text" class="form-control" ng-show="!editar" ng-disabled="true" ng-cloak ng-model="Destinatario.originalObject.direccion">
						                <input type="text" class="form-control" ng-show="editar" ng-disabled="true" ng-cloak ng-model="direccion">
						            </div>
								</div>
							</fieldset>
							<br>
							<fieldset>
								<legend>Datos Productos Factura Venta</legend>

								<div class="col-xs-12">
									<table class="table table-responsive table-striped table-hover table-condensed">
										<thead class="bg-primary">
											<tr>
												<td>Nro.</td>
												<td>Linea</td>
												<td>Código</td>
												<td>Detalle</td>
												<td>Cantidad</td>							
											</tr>
										</thead>
										<tbody>
											<tr dir-paginate="item in guia |orderBy:sortKey:reverse | itemsPerPage:3" ng-cloak>
												<td>{{item.idcatalogitem}}<br></td>
												<td>{{item.nombrecategoria}}</td>
												<td>{{item.codigoproducto}}</td>
												<td>{{item.nombreproducto}}</td>
												<td>{{item.cantidad}}</td>
											</tr>
										</tbody>
									</table>
									<dir-pagination-controls
						               on-page-change="pageChanged(newPageNumber)"

					                    template-url="dirPagination.html"

					                    class="pull-right"
					                    max-size="10"
					                    direction-links="true"
					                    boundary-links="true" >
						            </dir-pagination-controls>
								</div>
							</fieldset>
					</form>
						<fieldset>
							<legend>Datos Disposición Mercancía</legend>
							<div class="col-xs-12 text-right">
					            <button type="button" class="btn btn-primary" style="float:right;" ng-click="createRow()">
					                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
					            </button>
							</div>

							<div class="container-fluid col-xs-12">
							<form class="form-horizontal" name="formmercaderia" id="formmercaderia">
								<div>
									<table class="table table-responsive table-striped table-hover table-condensed">
										<thead class="bg-primary">
											<td style="width:8.33%;">Cantidad</td>
											<td style="width:8.33%;">Tipo Empaque</td>
											<td style="width:8.33%;">Peso(kg)</td>
											<td style="width:8.33%;">Largo(cm)</td>
											<td style="width:8.33%;">Ancho(cm)</td>
											<td style="width:8.33%;">Altura(cm)</td>
											<td style="width:8.33%;">P.Volumetrico(Kg)</td>
											<td style="width:33.33%;">Descripcion</td>
											<td style="width:8.33%;">Acciones</td>
										</thead>
									</table>
									<div>
										<div class="table table-responsive table-striped table-hover table-condensed" dir-paginate="itemm in itemguiaretension|itemsPerPage:3" ng-cloak>
											<div class="col-sm-1 col-xs-1" style="width: 8.33% float: left;">
												<div>
												   <input type="number" name='cantidad{{$index}}' class="form-control" 
				                                   string-to-cantidad{{$index}} ng-model="itemm.cantidad" required>
				                                </div>
				                                   <span class="help-block error" ng-show="formmercaderia.cantidad{{$index}}.$touched && formmercaderia.cantidad{{$index}}.$invalid">La cantidad es requerida</span>
			                                </div>
											<div class="col-sm-1 col-xs-1" style="width:8.33%;">
												<div >
													<input type="text" name='tipoempaque{{$index}}' ng-pattern="/^[a-zA-Z]+(\s*[a-zA-Z]*)*[a-zA-Z]*$/" class="form-control" string-to-tipoempaque{{$index}} ng-model="itemm.tipoempaque" required>
												</div>
					                                <span class="help-block error" ng-show="formmercaderia.tipoempaque{{$index}}.$touched && formmercaderia.tipoempaque{{$index}}.$invalid">El tipo del empaque es requerido</span>
					                                <span class="help-block error" ng-show="formmercaderia.tipoempaque{{$index}}.$error.pattern">Digitar sólo caracteres alfabéticos</span>
			                                </div>
											<div class="col-sm-1 col-xs-1" style="width:8.33%;">
												<div>
													<input type="text" name='peso{{$index}}' class="form-control" ng-keypress="onlyNumber($event,3,'itemm.peso')" ng-model="itemm.peso" required>
												</div> 
				                                    <span class="help-block error" ng-show="formmercaderia.peso{{$index}}.$touched && formmercaderia.peso{{$index}}.$invalid">El peso del contenedor del producto es requerido</span>
			                                </div>
											<div class="col-sm-1 col-xs-1" style="width:8.33%;">
												<div>
													<input type="text" name='largo{{$index}}' class="form-control" ng-keypress="onlyNumber($event,3,'itemm.largo')" ng-model="itemm.largo"  required>
												</div>
				                                    <span class="help-block error" ng-show="formmercaderia.largo{{$index}}.$touched && formmercaderia.largo{{$index}}.$invalid">La longitud del contenedor del producto es requerido</span>
				                                    <span class="help-block error" ng-show="formmercaderia.largo{{$index}}.$error.number">Digitar sólo caracteres numéricos</span>
			                                </div>
											<div class="col-sm-1 col-xs-1" style="width:8.33%;">
												<div>
													<input type="text" name='ancho{{$index}}' class="form-control" ng-keypress="onlyNumber($event,3,'itemm.ancho')" ng-model="itemm.ancho"  required>
												</div>
				                                    <span class="help-block error" ng-show="formmercaderia.ancho{{$index}}.$touched && formmercaderia.ancho{{$index}}.$invalid">El ancho del contenedor del producto es requerido</span>
				                                    <span class="help-block error" ng-show="formmercaderia.ancho{{$index}}.$error.number">Digitar sólo caracteres numéricos</span>
			                                </div>
											<div class="col-sm-1 col-xs-1" style="width:8.33%;">
												<div>
													<input type="text" name='altura{{$index}}' class="form-control" ng-keypress="onlyNumber($event,3,'itemm.altura')" ng-model="itemm.altura"  required>
												</div>	
				                                    <span class="help-block error" ng-show="formmercaderia.altura{{$index}}.$touched && formmercaderia.altura{{$index}}.$invalid">La altura del contenedor del producto es requerido</span>
				                                    <span class="help-block error" ng-show="formmercaderia.altura{{$index}}.$error.number">Digitar sólo caracteres numéricos</span>    
			                                </div>
											<div class="col-sm-1 col-xs-1" style="width:8.33%;">
												<input type="text" name='pvolumetrico' class="form-control" ng-disabled="true" placeholder="{{((itemm.largo*itemm.ancho*itemm.altura)/355)|number:4}}">
											</div>
											<div class="col-sm-4 col-xs-4" style="width:33.33%;">
												<div>
													<input type="text" name='descripcion{{$index}}' class="form-control" ng-model="itemm.descripcion" ng-pattern="/^[a-zA-Z]+(\s*[a-zA-Z]*)*[a-zA-Z]*$/+" ng-pattern-restrict>
												</div>
				                                    <span class="help-block error" ng-show="formmercaderia.descripcion{{$index}}.$touched && formmercaderia.descripcion{{$index}}.$invalid">El descripcion del producto es requerido</span>
				                                    <span class="help-block error" ng-show="formmercaderia.descripcion{{$index}}.$error.pattern">Digitar sólo caracteres alfabéticos</span>
			                                </div>
			                                <div class="col-sm-1 col-xs-1" style="width:8.33%;">
												<div>
													<button type="button" class="btn btn-danger" ng-click="delItemGuiaRetension($index)" ng-disabled="(detalle.length ==1)||impreso">
			                               			<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
												</div>
			                                </div>
										</div>
									</div>
								</div>
								<dir-pagination-controls
			               			on-page-change="pageChanged(newPageNumber)"

				                    template-url="dirPagination.html"

				                    class="pull-right"
				                    max-size="10"
				                    direction-links="true"
				                    boundary-links="true" >
			            		</dir-pagination-controls>
			            	</form>
							</div>
						</fieldset>
						<div class="col-xs-12 text-right" style="margin-top: 10px;">
							<button type="button" class="btn btn-default" ng-click="ActivaGuia='0'; BorrarEditar();">
				                Cancelar <span class="glyphicon glyphicon glyphicon-ban-circle" aria-hidden="true"></span> 
				            </button>
							<button type="button" name="guardar" class="btn btn-success"  ng-click="save()" ng-disabled="(formmercaderia.$invalid ||  form.puntopartida.$invalid ||  formguia.motivotraslado.$invalid ||  formguia.ffintrans.$invalid ||  formguia.finiciotrans.$invalid ||  formguia.ruta.$invalid ||  formguia.codestablecimiento.$invalid ||  formguia.docaduana.$invalid ||  formguia.citransportista.$invalid ||  formguia.cidestinatario.$invalid)">
				                Guardar <span class="glyphicon glyphicon glyphicon-floppy-saved" aria-hidden="true"></span> 
				            </button>
						</div>
				</div>
		</div>

		<div class="modal fade" tabindex="-1" role="dialog" id="modalMessage">
	            <div class="modal-dialog" role="document">
	                <div class="modal-content">
	                    <div class="modal-header modal-header-success">
	                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	                        <h4 class="modal-title">Confirmación</h4>
	                    </div>
	                    <div class="modal-body">
	                        <span>{{message}}</span>
	                    </div>
	                </div>
	            </div>
	    </div>
	    <div class="modal fade" tabindex="-1" role="dialog" id="modalMessageError">
	            <div class="modal-dialog" role="document">
	                <div class="modal-content">
	                    <div class="modal-header modal-header-error">
	                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	                        <h4 class="modal-title">Error</h4>
	                    </div>
	                    <div class="modal-body">
	                        <span>{{message}}</span>
	                    </div>
	                </div>
	            </div>
	    </div>

	</div>


	<script src="<?= asset('app/lib/angular/angular.min.js') ?>"></script>
    <script src="<?= asset('app/lib/angular/angular-route.min.js') ?>"></script>
    <script src="<?= asset('js/jquery.min.js') ?>"></script>
    <script src="<?= asset('js/bootstrap.min.js') ?>"></script>
    <script src="<?= asset('js/menuLateral.js') ?>"></script>
    <script src="<?= asset('js/moment.min.js') ?>"></script>
    <script src="<?= asset('js/es.js') ?>"></script>
    <script src="<?= asset('js/bootstrap-datetimepicker.min.js') ?>"></script>

	<script src="<?= asset('app/lib/angular/angucomplete-alt.min.js') ?>"></script>
    <script src="<?= asset('app/app.js') ?>"></script>


    <script src="<?= asset('app/lib/angular/ng-file-upload-shim.min.js') ?>"></script>
    <script src="<?= asset('app/lib/angular/ng-file-upload.min.js') ?>"></script>

    <script src="<?= asset('app/lib/angular/dirPagination.js') ?>"></script>
    <script src="<?= asset('app/controllers/guiaremisionController.js') ?>"></script>
</body>
</html>