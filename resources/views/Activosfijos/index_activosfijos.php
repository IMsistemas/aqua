<!DOCTYPE html>
<html lang="en" ng-app="softver-aqua">
<head>
	<meta charset="UTF-8" >
	<title>Activos fijos</title>
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
            

        </style>
	

</head>
<body ng-controller="ActivosFijosController" ng-init="ListarActivosFijos()">

	
		<div class="container" style="margin-top:70px";>

		<div class="col-md-7"></div>
			
			<div class="col-md-4">
					<div class="input-group">
  						<input type="text" class="form-control" placeholder="BUSCAR..." aria-describedby="basic-addon2" ng-model="busqueda"
  						 ng-change="buscar()">
  							<span class="input-group-addon" id="basic-addon2"><span class="glyphicon glyphicon-search"></span></span>
					</div>
			</div>

			<div class="col-md-1" >
				<button class="btn btn-primary" style="float: right;" id="add"  ng-click="addactivofijo1('add', 0)"><span class="glyphicon glyphicon-plus"></span></button>
			</div>


		</div>


		<div class="container">

				<div class="col-xs-12">
  					<h4>Gestión de Activos Fijos</h4>	
				<div>
             
               <hr>

			<div class="table-responsive">

				<table class="table">
					
					<thead class="btn-primary">
						
						<td>
							Foto
						</td>

						<td>
							Código
						</td>

						<td>
							Producto
						</td>

						<td style="float: right;">
							Acciones
						</td>


					</thead>

						<br>

					<tbody dir-paginate="activo in activofijo | itemsPerPage:5" ng-cloak>

	                    <th><img style="width: 100px; height: 80px;"  src="/aqua/public/imgActivosFijos/{{activo.foto}}"></th>
	                    <td>{{activo.codigoproducto}}</td>
	                    <td>{{activo.nombreproducto}}</td>
	                    <td style="float: right;">
                        <button type="button" class="btn btn-warning" id="edit" ng-click="addactivofijo1('edit', activo.idcatalogitem)"
                                data-toggle="tooltip" data-placement="bottom" title="Editar" >
                            <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                        </button>
                        <button type="button" class="btn btn-danger" ng-click="showModalConfirm(activo)"
                                data-toggle="tooltip" data-placement="bottom" title="Eliminar">
                            <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                        </button>
                    </td>
                
                </tbody>
            </table>
           <span ng-show="warning" class="danger">No se encontraron conincidencias con su busqueda</span> 
            <dir-pagination-controls
                max-size="5"
                direction-links="true"
                boundary-links="true" >
            </dir-pagination-controls>
			</div>

		</div>







<!-- FORMULARIO PARA AGREGAR Y EDITAR -->

<div class="modal fade" tabindex="-1" role="dialog" id="addactivofijo">
        <div class="modal-dialog  modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header modal-header-primary">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">{{title}}</h4>
                </div>
                <div class="modal-body">
                    <div class="row"> 
                        <div class="col-md-12">
      
      <form name="FromActivosFijos" id="FromActivosFijos" enctype="multipart/form-data">

		<div class="col-xs-12">
			<fieldset>
				
				<div class="col-sm-6 col-xs-12">
					<div class="input-group">                        
		                <span class="input-group-addon">Código Item: </span>
		                <input type="text" class="form-control" name="codigoItem" maxlength="20" ng-keyup="codigo()" ng-model="codigoItem" placeholder="Puede escribir hasta 20 caracteres" required />
		            </div> 
		             <span ng-cloak style="color:red;"
                        ng-show="FromActivosFijos.codigoItem.$invalid && FromActivosFijos.codigoItem.$touched">El campo es requerido 
                     </span>
                      <span ng-cloak style="color:red;" ng-show="error">El código ya existe, por favor ingrese un codigo nuevo</span>
                     <br>
		          
				</div>

				<div class="col-sm-6 col-xs-12">
					<div class="input-group">                        
		                <span class="input-group-addon">Detalle Item: </span>
		                <input type="text" class="form-control" name="detalleItem"  maxlength="50" ng-model="detalleItem" placeholder="Puede escribir hasta 50 caracteres" required>
		            </div>
		            <span ng-cloak style="color:red;"
                        ng-show="FromActivosFijos.detalleItem.$invalid && FromActivosFijos.detalleItem.$touched">El campo es requerido
                     </span>
                     <br>
				</div>


				<div class="col-xs-6" style="padding: 0;">
					<div class="col-xs-12" style="margin-top: 5px;">
						<div class="input-group">                        
			                <span class="input-group-addon">Tipo Item: </span>
			                  <select class="form-control" ng-model="claseItem" name="claseItem" ng-change="GetTipoItem()" required>

			                  	<option value="{{idTipoItem}}"  selected="selected">{{tipoItem}}</option>

			                	<!--<select class="form-control" ng-model="claseItem" name="claseItem" ng-focus="GetTipoItem()" required>
			                  	<option value>Seleccione</option>
			                  	<option value="{{idTipoItem}}">{{tipoItem}}</option>
 								</select> -->	
			                </select>
			            </div> 
			             	<input type="hidden" name=""  value="{{idTipoItem}}" ng-model="idTipoItem" >
			            	<span ng-cloak style="color:red;"
                        		ng-show="FromActivosFijos.claseItem.$invalid && FromActivosFijos.claseItem.$touched">El campo es requerido
                     		</span>
					</div>


					<div class="col-xs-12" style="margin-top: 5px;">
						<div class="input-group">                        
			                <span class="input-group-addon" >Categoría: </span>

				              <select class="form-control" ng-model="categoria" ng-focus="GetCategorias()" name="categoria" ng-change="habilitarLinea()" required> 
				            	<option value=" ">--Seleccione---</option>	
				                <option ng-repeat="categoriaItem in categorias" value="{{categoriaItem.idcategoria}}" selected="selected">{{categoriaItem.nombrecategoria}}
				                </option>
				               </select>
				          
			            </div> 
				            <span ng-cloak style="color:red;"
	                        		ng-show="FromActivosFijos.categoria.$invalid && FromActivosFijos.categoria.$touched">El campo es requerido
	                     	</span>
					</div>

					<div class="col-xs-12" style="margin-top: 5px;">
						<div class="input-group">                        
			                <span class="input-group-addon">Línea: </span>
			                <select class="form-control" ng-disabled="deshabilitarlinea" name="linea" ng-model="linea" id="linea" ng-change="habilitarsublinea()" required>
			                	<option value=" ">--Seleccione---</option>	
			                	<option value="{{linea.jerarquia}}" ng-repeat="linea in GetLinea" selected="selected">{{linea.nombrecategoria}}</option>
			                </select>
			                <input type="hidden" name="idlinea" id="idlinea" ng-model="idlinea" class="form-control">
			            </div> 
			            	<span ng-cloak style="color:red;"
	                        		ng-show="FromActivosFijos.linea.$invalid && FromActivosFijos.linea.$touched">El campo es requerido
	                     	</span>
					</div>

				<!--	<div class="col-xs-12" style="margin-top: 5px;">
						<div class="input-group">                        
			                <span class="input-group-addon">SubLínea: </span>
			                <select class="form-control" ng-disabled="deshabilitarsublinea" name="SubLinea" ng-model="SubLinea" id="subLinea" required>
			                	<option value=" ">--Seleccione---</option>	
			                	<option  value="{{sublinea.idcategoria}}" ng-repeat="sublinea in GetSubLinea" selected="selected">{{sublinea.nombrecategoria}}</option>
			                </select>			               
			            </div> 
			            <span ng-cloak style="color:red;"
	                        ng-show="FromActivosFijos.SubLinea.$invalid && FromActivosFijos.SubLinea.$touched">El campo es requerido
	                     </span>
					</div>-->

					<div class="col-xs-12" style="margin-top: 5px;">
						<div class="input-group">                        
			                <span class="input-group-addon">IVA: </span>
							<select class="form-control" ng-model="iva_item" name="iva_item" ng-focus="GetTipoIva()" required>
								<option value=" ">--Seleccione---</option>	
			                	<option ng-repeat="iva in TipoIva" value="{{iva.idtipoimpuestoiva}}" selected="selected" >{{iva.nametipoimpuestoiva}}</option>
			                </select>
			            </div> 
			             <span ng-cloak style="color:red;"
	                        ng-show="FromActivosFijos.iva_item.$invalid && FromActivosFijos.iva_item.$touched">El campo es requerido
	                     </span>
					</div>
					<div class="col-xs-12" style="margin-top: 5px;">
						<div class="input-group">                        
			                <span  class="input-group-addon" >Foto: </span>
			                <input type="file" class="form-control" ngf-select ng-model="foto" accept="image/*"/>
			            </div> 
					</div>
				</div>

				<div class="col-xs-6">
					<div class="col-xs-12" style="margin-top: 15px;">
						<img ng-model="fotoEdit" src="http://localhost/aqua/public/{{fotoEdit}}" class="img-thumbnail">
					</div>
				</div>

				

				<div class="col-xs-12" style="margin-top: 5px;">
					<div class="input-group">                        
		                <span class="input-group-addon">ICE: </span>
							<select class="form-control" ng-model="ice_item" ng-focus="GetTipoIce()" ng-cloak>
								<option value=" ">--Seleccione---</option>		
			                	<option ng-repeat="ice in TipoIce" value="{{ice.idtipoimpuestoice}}" selected="selected">{{ice.nametipoimpuestoice}}</option>
			                </select>
		            </div> 
				</div>

				<div class="col-xs-12" style="margin-top: 5px;">
					<div class="input-group">                        
		                <span class="input-group-addon">Cuenta Contable: </span>
		                <input type="text" class="form-control" ng-model="cuenta_contable" ng-disabled="true" />
		                <span class="input-group-btn" role="group">
	                        <button type="button" class="btn btn-info" id="btn-pcc" ng-click="showPlanCuenta()">
	                            <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
	                        </button>
	                    </span>
		            </div>
		            <input type="hidden" class="form-control" ng-model="id_cuenta_contable" readonly />
				</div>


			</fieldset>
		</div>

		<div class="col-xs-12 text-right" style="margin-top: 10px;">
			<button type="button" class="btn btn-default"  ng-click="cancelar()">
	            Cancelar <span class="glyphicon glyphicon glyphicon-ban-circle" aria-hidden="true"></span> 
	        </button>
			<button type="button" class="btn btn-success" ng-click="GuardarActivoFijo()" ng-disabled="FromActivosFijos.$invalid">
	            Guardar <span class="glyphicon glyphicon glyphicon-floppy-saved" aria-hidden="true"></span> 
	        </button>
		</div>

<div class="modal fade" tabindex="-1" role="dialog" id="modalCuentas">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header modal-header-primary">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Plan de Cuenta</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xs-12">
                            <table class="table table-responsive table-striped table-hover table-condensed table-bordered">
                                <thead class="bg-primary">
                                <tr>
                                    <th style="width: 15%;">ORDEN</th>
                                    <th>CONCEPTO</th>
                                    <th style="width: 10%;">COD. SRI</th>
                                    <th style="width: 4%;"></th>
                                </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="cuentas in PlanCuentas" ng-cloak>
                                        <td>{{cuentas.jerarquia}}</td>
                                        <td>{{cuentas.concepto}}</td>
                                        <td>{{cuentas.codigosri}}</td>
                                        <td>
                                            <input type="radio" name="select_cuenta" ng-click="click_radio(cuentas)">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


	</form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




 <div class="modal fade" tabindex="-1" role="dialog" id="modalConfirmDelete">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header modal-header-danger">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Confirmación</h4>
                </div>
                <div class="modal-body">
                    <span>Realmente desea eliminar el activo Fijo: <span style="font-weight: bold;">{{ActivoFijo}}</span></span>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        Cancelar <span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span>
                    </button>
                    <button type="button" class="btn btn-danger" id="btn-save" ng-click="DeleteActivoFijo(iditemactfijo,Idcatal,NomImg)">
                        Eliminar<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>


<div class="modal fade" tabindex="-1" role="dialog" id="modalMensaje">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header modal-header-success">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Acción Exitosa</h4>
                </div>
                <div class="modal-body">
                    <div class="row"> 
                        <div class="col-md-12">
                      	  <span>{{mensaje}}</span>
      					</div>
      				</div>
      			</div>
      		</div>
      	</div>
  </div>

  <div class="modal fade" tabindex="-1" role="dialog" id="modalMensajedelete">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header modal-header-success">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Acción Exitosa</h4>
                </div>
                <div class="modal-body">
                    <div class="row"> 
                        <div class="col-md-12">
                      	  <span>{{mensajeDelete}}</span>
      					</div>
      				</div>
      			</div>
      		</div>
      	</div>
  </div>

	
	<script src="<?= asset('app/lib/angular/angular.min.js') ?>"></script>
	<script src="<?= asset('app/app.js') ?>"></script>
	
    <script src="<?= asset('app/lib/angular/angular-route.min.js') ?>"></script>
    <script src="<?= asset('js/jquery.min.js') ?>"></script>
    <script src="<?= asset('js/bootstrap.min.js') ?>"></script>
    <script src="<?= asset('js/menuLateral.js') ?>"></script>
    <script src="<?= asset('js/moment.min.js') ?>"></script>
    <script src="<?= asset('js/es.js') ?>"></script>
    <script src="<?= asset('js/bootstrap-datetimepicker.min.js') ?>"></script>

	<script src="<?= asset('app/lib/angular/angucomplete-alt.min.js') ?>"></script>
  	

    <script src="<?= asset('app/lib/angular/ng-file-upload-shim.min.js') ?>"></script>
    <script src="<?= asset('app/lib/angular/ng-file-upload.min.js') ?>"></script>

    <script src="<?= asset('app/lib/angular/dirPagination.js') ?>"></script>
	<script src="<?= asset('app/controllers/ActivosFijosController.js') ?>"></script>
    

</body>


	

</html>

