
    <div ng-controller="comprasproductoController">
    
    <div class="container1" ng-show="listado">       

        <div class="col-xs-12" style="margin-top: 2%; margin-bottom: 2%">

            <div class="col-sm-4 col-xs-6">
                <div class="form-group has-feedback">
                    <input type="text" class="form-control" id="search" placeholder="BUSCAR..."
                           ng-model="search" ng-change="searchByFilter()">
                    <span class="glyphicon glyphicon-search form-control-feedback" aria-hidden="true"></span>
                </div>
            </div>
            <div class="col-sm-3 col-xs-3">
                <div class="form-group has-feedback">
                    <select class="form-control" name="proveedor" id="proveedor" ng-model="proveedorFiltro"
                      ng-change="searchByFilter()">
                        <option value="">Proveedor</option>
						<option ng-repeat="item in proveedoresFiltro"						       
						        value="{{item.idproveedor}}">{{item.razonsocial}}     
						</option>                        
                        </select>                    
                </div>
            </div>
            
            <div class="col-sm-3 col-xs-3">
                <div class="form-group has-feedback">
                    <select class="form-control" name="estado" id="estado" ng-model="estadoFiltro"
                        ng-change="searchByFilter()">
                        <option value="">Estado</option>
						<option ng-repeat="item in estados"						       
						        value="{{item.id}}">{{item.nombre}}     
						</option>                        
                        </select>                    
                </div>
            </div>
 

            <div class="col-sm-2 col-xs-6">
                <button type="button" class="btn btn-primary" style="float: right;" ng-click="openForm(0)">
                   <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                </button>
            </div>

            <div class="col-xs-12">
                <table class="table table-responsive table-striped table-hover table-condensed">
                    <thead class="bg-primary">
                    <tr>
                        <th style="text-align: center;" ng-click="sort('codigocompra')">
                        Código
                         <span class="glyphicon sort-icon" ng-show="sortKey=='codigocompra'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                        </th>
                        <th style="text-align: center;" ng-click="sort('fecharegistrocompra')">
                        Fecha Ingreso
                        <span class="glyphicon sort-icon" ng-show="sortKey=='fecharegistrocompra'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                        </th>
                        <th style="text-align: center;" ng-click="sort('razonsocialproveedor')">
                        Proveedor
                        <span class="glyphicon sort-icon" ng-show="sortKey=='razonsocialproveedor'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                        </th>     
                        <th style="text-align: center;">Subtotal</th>   
                        <th style="text-align: center;">IVA</th> 
                        <th style="text-align: center;" ng-click="sort('totalcompra')">
                        Total
                         <span class="glyphicon sort-icon" ng-show="sortKey=='totalcompra'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                        </th>
                        <th style="text-align: center;" ng-click="sort('estapagada')">
                        Estado
                         <span class="glyphicon sort-icon" ng-show="sortKey=='estapagada'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                        </th>                 
                        <th style="width: 20%;">Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr dir-paginate="item in compras|orderBy:sortKey:reverse|itemsPerPage:10" >
                        <td style="text-align: center;">{{item.iddocumentocompra}}</td>
                        <td>{{formatoFecha(item.fecharegistrocompra)}}</td>
                        <td>{{item.razonsocial}}</td>
                        <td>{{ sumar(item.subtotalconimpuestocompra,item.subtotalcerocompra) }}</td>
                        <td>{{item.ivacompra  }}</td>
                        <td>{{item.valortotalcompra}}</td>                      
                        <td>{{(item.estaAnulada)?'Anulada':'No Anulada'}}</td>
                        <td>
                            <button type="button" class="btn btn-warning" ng-click="openForm(item.iddocumentocompra)" ng-disabled="item.estaAnulada==1"
                                    data-toggle="tooltip" data-placement="bottom" >
                                 Editar <span class="glyphicon glyphicon-edit" aria-hidden="true">
                            </button>
                            <button type="button" class="btn btn-danger" ng-click="showModalConfirm(item.iddocumentocompra,0)"
                                    data-toggle="tooltip" data-placement="bottom" title="Anular"  ng-disabled="item.estaAnulada==1">
                                Anular <span class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span>
                            </button>
                                                        
                        </td>
                    </tr>
                    </tbody>
                </table>
                <dir-pagination-controls
			       max-size="5"
			       direction-links="true"
			       boundary-links="true" >
			    </dir-pagination-controls>

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

        <div class="modal fade" tabindex="-1" role="dialog" id="modalConfirmAnular">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header modal-header-danger">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Confirmación</h4>
                    </div>
                    <div class="modal-body">
                        <span>Está seguro que desea Anular la compra seleccionada?</span>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" id="btn-save" ng-click="anularCompra()">Anular</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" tabindex="-1" role="dialog" id="modalInfoEmpleado">
            <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content">
                    <div class="modal-header modal-header-info">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Información Empleado No {{empleado.idempleado}} </h4>
                    </div>
                    <div class="modal-body">
                        <div class="col-xs-12 text-center">
                       			<img ng-src="{{ rutafoto }}" onerror="defaultImage(this)" class="img-thumbnail" style="width:150px" >                            
                        </div>
                        <div class="row text-center">
                            <div class="col-xs-12 text-center" style="font-size: 18px;">{{empleado.nombres}} {{empleado.apellidos}}</div>
                            <div class="col-xs-12">
                                <span style="font-weight: bold">Cargo: </span>{{empleado.nombrecargo}}
                            </div>
                            <div class="col-xs-12">
                                <span style="font-weight: bold">Empleado desde: </span>{{formatoFecha(empleado.fechaingreso)}}
                            </div>
                            <div class="col-xs-12">
                                <span style="font-weight: bold">Teléfonos: </span>{{empleado.telefonoprincipaldomicilio}} / {{empleado.telefonosecundariodomicilio}}
                            </div>
                            
                            <div class="col-xs-12">
                                <span style="font-weight: bold">Celular: </span>{{empleado.celular}}
                            </div>
                            <div class="col-xs-12">
                                <span style="font-weight: bold">Dirección: </span>{{empleado.direcciondomicilio}}
                            </div>
                            <div class="col-xs-12">
                                <span style="font-weight: bold">Email: </span>{{empleado.correo}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    
    <!-- Formulario -->
    
    
    
	<div class="col-xs-12" ng-show="!listado" style="padding-top: 30px">
<div>			
				
				<div ng-show="false" style="float: right">
				<div style="float: left">
				<a href="#id" ng-click="excel()" data-toggle="tab">
					<img ng-src="img/excel.png" style="height: 40px" >
					</a>
				</div>
				<div style="float: left">
				<a href="#id" ng-click="pdf()" data-toggle="tab">
					<img ng-src="img/pdf.png" style="height: 40px" >
					</a>
				</div>
				<div style="float: left" >
				<a href="#id" ng-click="imprimir()" data-toggle="tab" >
					<img ng-src="img/impresora.png" style="height: 40px" >
					</a>
				</div>
					
				</div>

			</div>



			<ul class="nav nav-tabs">
				<li class="active"><a href="#id1" data-toggle="tab">Ingreso</a></li>
				<li ><a href="#id2" ng-show="false" data-toggle="tab" >Retencion</a></li>
			</ul>
			<div class="tab-content">
				<div class="tab-pane active" id="id1">
				<div class="container1" style="padding-top: 20px;">
				<form class="form-horizontal" name="formCompra" id="formCompra"  novalidate="" >
		<div class="form-group col-xs-12">
			
				<div class="form-group col-xs-6">
			<fieldset>
				<legend>Datos Proveedor</legend>

				<div class="col-sm-6 col-xs-12" style="margin-top: 5px;">
					<div class="input-group">                        
		                <span class="input-group-addon">Fecha Registro: </span>
		                <input type="text" class="form-control input-sm datepicker" id="fecharegistrocompra" ng-model="compra.fecharegistrocompra" >
		            </div> 
				</div>
				<div class="col-sm-6 col-xs-12" style="margin-top: 5px;">
					<div class="input-group">                        
		                <span class="input-group-addon">No. Compra: </span>
		                <input type="text" class="form-control input-sm" value="{{('000000'+compra.codigocompra).slice(-7)}}" readonly >
		            </div> 
				</div>

				<div class="col-xs-12" style="margin-top: 5px;">
					<div class="input-group">                        
		                <span class="input-group-addon">RUC: </span>
		                <input type="hidden" id="idproveedor" name="idproveedor" ng-model="compra.idproveedor">
						<input type="text" class="form-control" name="ci" ng-model="ci"
									ng-keyup="loadProveedor()"
									id="ci" ng-required="true"
									ng-maxlength="13"
									ng-pattern="/[0-9]+$/"
									ng-disabled="impreso"
									> 
		            </div> 
		            <span class="help-block error"
									ng-show="formCompra.ci.$invalid && formCompra.ci.$touched">El Ruc/CI es requerido</span> 
									<span class="help-block error"
									ng-show="formCompra.ci.$invalid && formCompra.ci.$error.maxlength">La
									longitud máxima es de 13 caracteres.</span> <span
									class="help-block error"
									ng-show="formCompra.ci.$invalid && formCompra.ci.$error.pattern">El Ruc/CI no es válido.</span>
									<span class="help-block error" ng-show="mensaje">El Proveedor no Existe.</span>
				</div>
				<div class="col-xs-12" style="margin-top: 5px;">
					<div class="input-group">                        
		                <span class="input-group-addon">Razón Social: </span>
		                <input type="text" class="form-control input-sm" id="razon" readonly >
		               
		            </div> 
				</div>
				<div class="col-xs-12" style="margin-top: 5px;">
					<div class="input-group">                        
		                <span class="input-group-addon">Dirección: </span>
		                <input type="text" class="form-control input-sm" id="direccion" readonly >
		            </div> 
				</div>
				<div class="col-xs-12" style="margin-top: 5px;">
					<div class="input-group">                        
		                <span class="input-group-addon">Teléfono: </span>
		                <input type="text" class="form-control input-sm" id="telefono" readonly >
		            </div> 
				</div>

				<div class="col-sm-6 col-xs-12" style="margin-top: 5px;">
					<div class="input-group">                        
		                <span class="input-group-addon">IVA: </span>
		                <input type="text" class="form-control input-sm" id="iva" readonly >
		            </div> 
				</div>
				<div class="col-sm-6 col-xs-12" style="margin-top: 5px;">
					<div class="input-group">                        
		                <span class="input-group-addon">Bodega: </span>
						<select ng-disabled="impreso" class="form-control" name="bodega" id="bodega" ng-model="compra.idbodega" ng-required="true" 
						            ng-options="item.idbodega as item.namebodega for item in bodegas">
								<option value="">Bodega</option>
								</select>
		            </div> 
		            <span class="help-block error" ng-show="formCompra.bodega.$invalid && formCompra.bodega.$touched">La Bodega es requerida</span>          
				</div>

			</fieldset>			

		</div>
		<div class="col-xs-6">
			
			<fieldset>
				<legend>Datos Factura de Compra</legend>

				<div class="col-xs-12" style="margin-top: 5px;">
	                <div class="input-group">
	                    <span class="input-group-addon">Nro. Documento: </span>
	                    <span class="input-group-btn" style="width: 15%;">
		                    
		                    <input type="text" class="form-control" name="numero1" ng-model="numero1"									
										id="numero1" ng-required="true"
										ng-minlength="3"
										maxlength="3"
										ng-pattern="/[0-9]+$/"										
										ng-disabled="guardado"
										> 
		                    
		                    
		                </span>
	                    <span class="input-group-btn" style="width: 15%;" >
		                     <input type="text" class="form-control" name="numero2" ng-model="numero2"									
									id="numero2" ng-required="true"
									ng-minlength="3"
									maxlength="3"
									ng-pattern="/[0-9]+$/"
									
									ng-disabled="guardado"
									> 
		                </span>
	                    <input type="text" class="form-control" name="numero3" ng-model="numero3"									
									id="numero3" ng-required="true"
									ng-maxlength="8"
									maxlength="8"
									
									ng-pattern="/[0-9]+$/"
									style="width: 100px;"
									ng-disabled="guardado"
									> 
	                </div>
	                <span class="help-block error"
										ng-show="formCompra.numero1.$invalid && formCompra.numero1.$touched">El Establecimineto es requerido</span> 
										<span class="help-block error"
										ng-show="formCompra.numero1.$invalid && formCompra.numero1.$error.minlength">La
										longitud mínima es de 3 caracteres.</span> <span
										class="help-block error"
										ng-show="formCompra.numero1.$invalid && formCompra.numero1.$error.pattern">El Establecimineto no es válido.</span>
										<span class="help-block error"
									ng-show="formCompra.numero2.$invalid && formCompra.numero2.$touched">El Facturero es requerido</span> 
									<span class="help-block error"
									ng-show="formCompra.numero2.$invalid && formCompra.numero2.$error.minlength">La
									longitud mínima es de 3 caracteres.</span> <span
									class="help-block error"
									ng-show="formCompra.numero2.$invalid && formCompra.numero2.$error.pattern">El Facturero no es válido.</span>
									<span class="help-block error"
									ng-show="formCompra.numero3.$invalid && formCompra.numero3.$touched">El número es requerido</span> 
									<span class="help-block error"
									ng-show="formCompra.numero3.$invalid && formCompra.numero3.$error.minlength">La
									longitud mínima es de 8 caracteres.</span> <span
									class="help-block error"
									ng-show="formCompra.numero3.$invalid && formCompra.numero3.$error.pattern">El número no es válido.</span>
									
	            </div>

				<div class="col-sm-8 col-xs-12" style="margin-top: 5px;">
					<div class="input-group">                        
		                <span class="input-group-addon">Fecha Emisión: </span>
		                <input type="text" class="form-control datepicker" datetime-picker name="fechaemisioncompra"
									id="fechaemisioncompra" 
									ng-model="compra.fechaemisioncompra"	
																	
									>
		            </div> 
				</div>

				<div class="col-xs-12" style="margin-top: 5px;">
					<div class="input-group">                        
		                <span class="input-group-addon">Nro Autorización: </span>
		                <input type="text" class="form-control" name="autorizacionfacturaproveedor" ng-model="compra.nroautorizacioncompra"									
									id="autorizacionfacturaproveedor" ng-required="true"
									ng-maxlength="37"
									maxlength="37"
									ng-pattern="/[0-9]+$/"
									ng-disabled="impreso"
									> 
		            </div> 
		            <span class="help-block error"
									ng-show="formCompra.autorizacionfacturaproveedor.$invalid && formCompra.autorizacionfacturaproveedor.$touched">La Autorización es requerida</span> 
									<span class="help-block error"
									ng-show="formCompra.autorizacionfacturaproveedor.$invalid && formCompra.autorizacionfacturaproveedor.$error.maxlength">La
									longitud máxima es de 37 caracteres.</span> <span
									class="help-block error"
									ng-show="formCompra.autorizacionfacturaproveedor.$invalid && formCompra.autorizacionfacturaproveedor.$error.pattern">La Autorización no es válida.</span>
				</div>

				<div class="col-xs-12" style="margin-top: 5px;">
					<div class="input-group">                        
		                <span class="input-group-addon">Sustento Tributario: </span>							
							<select ng-disabled="impreso" class="form-control" name="codigosustento" id="codigosustento" ng-model="compra.idsustentotributario" ng-required="true"
						            ng-options="item.idsustentotributario as item.namesustento for item in sustentotributario">
								<option value="">Sustento Tributario</option>
								</select> 
							
							
							  
		            </div> 
		             <span class="help-block error" ng-show="formCompra.codigosustento.$invalid && formCompra.codigosustento.$touched">El Sustento Tributario es requerido</span>                    
				</div>

				<div class="col-xs-12" style="margin-top: 5px;">
					<div class="input-group">                        
		                <span class="input-group-addon">Tipo Comprobante: </span>
	 
				
				<select ng-disabled="impreso" class="form-control" name="tipocomprobante" id="tipocomprobante" ng-model="compra.idtipocomprobante" ng-required="true" 
						            ng-options="item.idtipocomprobante as item.namecomprobante for item in tiposComprobante">
								<option value="">Tipo Comprobante</option>
								</select> 
				
				
		            </div> 
		            <span class="help-block error" ng-show="formCompra.tipocomprobante.$invalid && formCompra.tipocomprobante.$touched">El Tipo Comprobante es requerido</span>   
				</div>
			</fieldset>
		           
		</div>							
	       
</div>

	
	<div class="col-xs-12 text-right" style="margin-top: 5px;">
		<button type="button" class="btn btn-primary" style="float: right;" ng-click="addDetalle()" ng-disabled="impreso">
		                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
		                </button>
	</div>

	<div class="col-xs-12" style="margin-top: 5px;">
		<table class="table table-responsive table-striped table-hover table-condensed">
			<thead class="bg-primary">
				<tr>
					<td>Código Item</td>
					<td style="width: 20%">Detalle</td>
					<td>Cantidad</td>
					<td>Precio Unitario</td>
					<td>Descuento</td>
					<td>IVA</td>
					<td>ICE</td>
					<td>Total</td>
					<td></td>
				</tr>
			</thead>
			<tbody>
				<tr ng-repeat="item in detalle">
					<td>
						<div>
                                            <angucomplete-alt 
                                            	  id="codigoproducto{{$index}}"									            
									              pause="400"
									              selected-object="item.productoObj"									              
									              remote-url="{{url}}compras/getCodigoProducto/"
									              title-field="codigoproducto"
									              description-field="twitter"									              
									              minlength="1"
									              input-class="form-control form-control-small"
									              match-class="highlight"
									              field-required="true"
									              input-name="codigoproducto{{$index}}"
									              disable-input="impreso"
									              text-searching="Buscando Producto"
									              text-no-results="Producto no encontrado"
									              initial-value="item.producto"
									               />
                                            </div>
                                            <span class="help-block error"
                                                      ng-show="formCompra.codigoproducto{{$index}}.$invalid && formCompra.codigoproducto{{$index}}.$touched">El producto es requerido.</span>     
					
					</td>
					<td>
						<label class="control-label" ng-show="!read">{{ item.productoObj.originalObject.nombreproducto }}</label> 
                    	<label class="control-label" ng-show="read">{{ item.producto.nombreproducto }}</label> 
					
					</td>
					<td>
						<input type="text" class="form-control" name="cantidad{{$index}}" id="cantidad{{$index}}" ng-disabled="impreso"
		                      ng-model="item.cantidad" ng-required="true" ng-maxlength="5" ng-pattern="/[0-9]+$/" ng-change="calcular()">
		                      <span class="help-block error"
									ng-show="formCompra.cantidad{{$index}}.$invalid && formCompra.cantidad{{$index}}.$touched">La Cantidad es requerida</span> 
									<span class="help-block error"
									ng-show="formCompra.cantidad{{$index}}.$invalid && formCompra.cantidad{{$index}}.$error.maxlength">La
									longitud máxima es de 5 números.</span> <span
									class="help-block error"
									ng-show="formCompra.cantidad{{$index}}.$invalid && formCompra.cantidad{{$index}}.$error.pattern">La Cantidad no es válida.</span>	
					</td>
					<td>
						<input type="text" class="form-control" name="unitario{{$index}}" id="unitario{{$index}}" ng-disabled="impreso"
		                      ng-model="item.precioUnitario" ng-required="true" ng-maxlength="8" ng-pattern="/^\d+(?:\.\d{1,4})?$/" ng-change="calcular()">
		                      <span class="help-block error"
									ng-show="formCompra.unitario{{$index}}.$invalid && formCompra.unitario{{$index}}.$touched">El Precio Unitario es requerido</span> 
									<span class="help-block error"
									ng-show="formCompra.unitario{{$index}}.$invalid && formCompra.unitario{{$index}}.$error.maxlength">La
									longitud máxima es de 7 números.</span> <span
									class="help-block error"
									ng-show="formCompra.unitario{{$index}}.$invalid && formCompra.unitario{{$index}}.$error.pattern">El Precio Unitario no es válido.</span>
					</td>
					<td>
						<input type="text" class="form-control" name="descuento{{$index}}" id="descuento{{$index}}" ng-disabled="impreso"
		                      ng-model="item.descuento" ng-required="true" ng-maxlength="5" ng-pattern="/^\d+(?:\.\d{1,2})?$/" ng-change="calcular()">
		                      <span class="help-block error"
									ng-show="formCompra.descuento{{$index}}.$invalid && formCompra.descuento{{$index}}.$touched">El IVA es requerido</span> 
									<span class="help-block error"
									ng-show="formCompra.descuento{{$index}}.$invalid && formCompra.descuento{{$index}}.$error.maxlength">La
									longitud máxima es de 4 números.</span> <span
									class="help-block error"
									ng-show="formCompra.descuento{{$index}}.$invalid && formCompra.descuento{{$index}}.$error.pattern">El IVA no es válido.</span>
					</td>
					<td>
						<input type="text" class="form-control" name="iva{{$index}}" id="iva{{$index}}" ng-disabled="impreso"
		                      ng-model="item.iva" ng-required="true" ng-maxlength="5" ng-pattern="/^\d+(?:\.\d{1,2})?$/" ng-change="calcular()">
		                      <span class="help-block error"
									ng-show="formCompra.iva{{$index}}.$invalid && formCompra.iva{{$index}}.$touched">El IVA es requerido</span> 
									<span class="help-block error"
									ng-show="formCompra.iva{{$index}}.$invalid && formCompra.iva{{$index}}.$error.maxlength">La
									longitud máxima es de 4 números.</span> <span
									class="help-block error"
									ng-show="formCompra.iva{{$index}}.$invalid && formCompra.iva{{$index}}.$error.pattern">El IVA no es válido.</span>
					</td>
					<td>
						<input type="text" class="form-control" name="ice{{$index}}" id="ice{{$index}}" ng-disabled="impreso"
		                      ng-model="item.ice" ng-required="true" ng-maxlength="5" ng-pattern="/^\d+(?:\.\d{1,2})?$/" ng-change="calcular()">
		                      <span class="help-block error"
									ng-show="formCompra.ice{{$index}}.$invalid && formCompra.ice{{$index}}.$touched">El ICE es requerido</span> 
									<span class="help-block error"
									ng-show="formCompra.ice{{$index}}.$invalid && formCompra.ice{{$index}}.$error.maxlength">La
									longitud máxima es de 4 números.</span> <span
									class="help-block error"
									ng-show="formCompra.ice{{$index}}.$invalid && formCompra.ice{{$index}}.$error.pattern">El ICE no es válido.</span>
					</td>
					<td>
						
						<input type="text" class="form-control" disabled ng-model="item.total" /></td>
					<td>
						<button type="button" class="btn btn-danger" ng-click="delDetalle($index)" ng-disabled="(detalle.length ==1)||impreso">
                               <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                        </button>
					</td>
				</tr>
			</tbody>
		</table>
	</div>

	<div class="col-xs-8">

		<div class="col-xs-12">
			<div class="input-group">                        
                <span class="input-group-addon">Forma Pago: </span>
                <select class="form-control" name="idformapago" id="idformapago" ng-model="compra.idformapago" ng-required="true" ng-disabled="impreso"
						                                             ng-options="item.idformapago as item.nameformapago for item in formasPago">
																	  <option value="">Forma Pago</option>
																		</select> 
            </div> 
            <span class="help-block error"
							                                                      ng-show="formCompra.idformapago.$invalid && formCompra.idformapago.$touched">La Forma Pago es requerida</span>								
		</div>

		<div class="col-xs-12" style="margin-top: 15px;">
			<fieldset>
			<legend>Comprobante de Retención</legend>

			<div class="col-xs-6">
				<div class="input-group">                        
	                <span class="input-group-addon">Tipo de Pago: </span>
	                <select class="form-control" name="codigoformapago" id="codigoformapago" ng-model="compra.codigoformapago3">
                               						<option value="">-- Seleccione --</option>
													<option ng-repeat="item in TiposPago"						       
													        value="{{item.idpagoresidente}}">{{item.tipopagoresidente}}     
													</option> 
												</select>
	                
	            </div>
			</div>
			<div class="col-xs-6">
				<div class="input-group">                        
	                <span class="input-group-addon">Pais Pago: </span>
	                <select class="form-control" name="pais" id="pais" ng-model="compra.codigopais"  >
                               						<option value="">-- Seleccione --</option>
                               						<option value="999">Residente</option>
													<option ng-repeat="item in paises"						       
													        value="{{item.idpagopais}}">{{item.pais }}     
													</option> 
												</select>
	               
	            </div>
			</div>

			<div class="col-xs-6" style="margin-top: 5px;">
				<div class="input-group">                        
	                <span class="input-group-addon">Régimen Fiscal?: </span>
	                <select class="form-control">
	                	<option value="1">SI</option>
	                	<option value="2">NO</option>
	                </select>
	            </div>
			</div>
			<div class="col-xs-6" style="margin-top: 5px;">
				<div class="input-group">                        
	                <span class="input-group-addon">Convenio doble Tributación?: </span>
	                <select class="form-control">
	                	<option value="1">SI</option>
	                	<option value="2">NO</option>
	                </select>
	            </div>
			</div>

			<div class="col-xs-6" style="margin-top: 5px;">
				<div class="input-group">                        
	                <span class="input-group-addon">Aplicación de Norma Legal?: </span>
	                <select class="form-control">
	                	<option value="1">SI</option>
	                	<option value="2">NO</option>
	                </select>
	            </div>
			</div>
			<div class="col-xs-6" style="margin-top: 5px;">
				<div class="input-group">                        
	                <span class="input-group-addon">Fecha Emisión Comprobante: </span>
	                <input type="text" class="form-control" />
	            </div> 
			</div>
			<div class="col-xs-12" style="margin-top: 5px;">
                <div class="input-group">
                    <span class="input-group-addon">Nro. Comprobante Retención: </span>
                    <span class="input-group-btn" style="width: 15%;">
	                    <input type="text" class="form-control" id="t_establ" name="t_establ" ng-model="t_establ" ng-keypress="onlyNumber($event, 3, 't_establ')" ng-blur="calculateLength('t_establ', 3)" />
	                </span>
                    <span class="input-group-btn" style="width: 15%;" >
	                    <input type="text" class="form-control" id="t_pto" name="t_pto" ng-model="t_pto" ng-keypress="onlyNumber($event, 3, 't_pto')" ng-blur="calculateLength('t_pto', 3)" />
	                </span>
                    <input type="text" class="form-control" id="t_secuencial" name="t_secuencial" ng-model="t_secuencial" ng-keypress="onlyNumber($event, 9, 't_secuencial')" ng-blur="calculateLength('t_secuencial', 9)" />
                </div>
            </div>
			<div class="col-xs-12" style="margin-top: 5px;">
				<div class="input-group">                        
	                <span class="input-group-addon">Nro Autorización Comprobante: </span>
	                <input type="text" class="form-control" />
	            </div> 
			</div>

		</fieldset>
		</div>

		


		<div class="col-xs-12 text-right" style="margin-top: 20px;">
			<button type="button" class="btn btn-warning" id="btn-anular" ng-click="showModalConfirm1()" ng-show="anulado" ng-disabled="!guardado" >
	           Anular <span class="glyphicon glyphicon glyphicon-ban-circle" aria-hidden="true"></span> 
	        </button>

	        <button type="button" class="btn btn-primary" id="btn-save" ng-click="save()" ng-disabled="formCompra.$invalid" ng-show="anulado">
	               Guardar <span class="glyphicon glyphicon glyphicon-floppy-saved" aria-hidden="true"></span>
	        </button>
			<button class="btn btn-success" ng-click="InicioList();"> Regresar</button>
		</div>
		
	</div>

	<div class="col-xs-4">
		<table class="table table-responsive table-striped table-hover table-condensed table-bordered">
			<tbody>
				<tr>
					<td style="width: 60%;">SubTotal con Impuesto</td>
					<td>
						{{ compra.subtotalconimpuestocompra }} 
					</td>
				</tr>
				<tr>
					<td>SubTotal 0%</td>
					<td>{{ compra.subtotalcerocompra }} </td>
				</tr>
				<tr>
					<td>SubTotal No Objeto IVA</td>
					<td>{{ compra.subtotalnoobjivacompra }}</td>
				</tr>
				<tr>
					<td>SubTotal Exento IVA</td>
					<td>{{ compra.subtotalexentivacompra }}</td>
				</tr>
				<tr>
					<td>SubTotal Sin Impuestos</td>
					<td>{{ compra.subtotalsinimpuestocompra }}</td>
				</tr>
				<tr>
					<td>Total Descuento</td>
					<td>{{ compra.totaldescuento }}</td>
				</tr>
				<tr>
					<td>ICE</td>
					<td>
						<input type="text" class="form-control" name="iceF" ng-model="compra.icecompra"									
										id="iceF" 
										ng-maxlength="5"
										maxlength="5"
										ng-pattern="/^\d+(?:\.\d{1,2})?$/"
										ng-change="calcular()"
										ng-disabled="impreso"
										> 
										<span class="help-block error"
										ng-show="formCompra.otros.$invalid && formCompra.iceF.$touched">El Valor ICE es requerido.</span> 
										<span class="help-block error"
										ng-show="formCompra.otros.$invalid && formCompra.iceF.$error.maxlength">La
										longitud máxima es de 7 números.</span> <span
										class="help-block error"
										ng-show="formCompra.otros.$invalid && formCompra.iceF.$error.pattern">El Valor ICE no es válido.</span>
					</td>
				</tr>
				<tr>
					<td>IVA</td>
					<td>
						<input type="text" class="form-control" name="ivaF" ng-model="compra.ivacompra"									
										id="ivaF" 
										ng-maxlength="5"
										maxlength="5"
										ng-pattern="/^\d+(?:\.\d{1,2})?$/"
										ng-change="calcular()"
										ng-disabled="impreso"
										> 
										<span class="help-block error"
										ng-show="formCompra.ivaF.$invalid && formCompra.ivaF.$touched">El Valor IVA es requerido.</span> 
										<span class="help-block error"
										ng-show="formCompra.ivaF.$invalid && formCompra.ivaF.$error.maxlength">La
										longitud máxima es de 7 números.</span> <span
										class="help-block error"
										ng-show="formCompra.ivaF.$invalid && formCompra.ivaF.$error.pattern">El Valor IVA no es válido.</span>
					</td>
				</tr>
				<tr>
					<td>IRBPNR</td>
					<td>
						<input type="text" class="form-control" name="irbpnr" ng-model="compra.irbpnrcompra"									
										id="irbpnr" 
										ng-maxlength="5"
										maxlength="5"
										ng-pattern="/^\d+(?:\.\d{1,2})?$/"
										ng-change="calcular()"
										ng-disabled="impreso"
										> 
										<span class="help-block error"
										ng-show="formCompra.irbpnr.$invalid && formCompra.irbpnr.$touched">El Valor IRBPNR es requerido.</span> 
										<span class="help-block error"
										ng-show="formCompra.irbpnr.$invalid && formCompra.irbpnr.$error.maxlength">La
										longitud máxima es de 7 números.</span> <span
										class="help-block error"
										ng-show="formCompra.irbpnr.$invalid && formCompra.irbpnr.$error.pattern">El Valor IRBPNR no es válido.</span>
					</td>
				</tr>
				<tr>
					<td>PROPINA</td>
					<td>
						<input type="text" class="form-control" name="propina" ng-model="compra.propinacompra"									
										id="propina" 
										ng-maxlength="5"
										maxlength="5"
										ng-pattern="/^\d+(?:\.\d{1,2})?$/"
										ng-change="calcular()"
										ng-disabled="impreso"
										> 
										<span class="help-block error"
										ng-show="formCompra.propina.$invalid && formCompra.iceF.$touched">El Valor PROPINA es requerido.</span> 
										<span class="help-block error"
										ng-show="formCompra.propina.$invalid && formCompra.iceF.$error.maxlength">La
										longitud máxima es de 7 números.</span> <span
										class="help-block error"
										ng-show="formCompra.propina.$invalid && formCompra.iceF.$error.pattern">El Valor PROPINA no es válido.</span>
					</td>
				</tr>
				<tr>
					<td>VALOR TOTAL</td>
					<td>{{ compra.valortotalcompra }}</td>
				</tr>
			</tbody>
		</table>
	</div>				

					</form>
					</div>

				</div>
				
			</div>
		</div>
		
		

							
		
		
		
	<div class="modal fade" tabindex="-1" role="dialog" id="modalMessage1">
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
        
        <div class="modal fade" tabindex="-1" role="dialog" id="modalConfirmAnular1">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header modal-header-danger">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Confirmación</h4>
                    </div>
                    <div class="modal-body">
                        <span>Está seguro que desea anular la compra?</span>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" id="btn-save" ng-click="anularCompra()">Anular</button>
                    </div>
                </div>
            </div>
        </div>	
		
	</div>


</div>