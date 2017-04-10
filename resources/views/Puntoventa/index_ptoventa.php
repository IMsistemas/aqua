<!doctype html>
<html lang="es-ES" ng-app="softver-aqua">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Transportista</title>

    <link href="<?= asset('css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= asset('css/font-awesome.min.css') ?>" rel="stylesheet">
    <link href="<?= asset('css/index.css') ?>" rel="stylesheet">
    <link href="<?= asset('css/bootstrap-datetimepicker.min.css') ?>" rel="stylesheet">
    <link href="<?= asset('css/angucomplete-alt.css') ?>" rel="stylesheet">
    <link href="<?= asset('css/style_generic_app.css') ?>" rel="stylesheet">

    <style>
        .modal-body {
            max-height: calc(100vh - 210px);
            overflow-y: auto;
        }
    </style>

</head>

<body>
		<div ng-controller="puntoventaController">

			<div class="col-xs-12">

		        <h4>Punto de Venta</h4>

		        <hr>

		    </div>
			<div ng-cloak class="col-xs-12 text-right" style="margin-top: 5px;">
				<button type="button" class="btn btn-primary" ng-click="toggle('add', 0)">
		            Agregar <span class="glyphicon glyphicon glyphicon-plus" aria-hidden="true"></span> 
		        </button>
			</div>

			<div class="col-xs-12">
				<table class="table table-responsive table-striped table-hover table-condensed">
					<thead class="bg-primary">
						<tr >
							<th>Nro.</th>
							<th>Establecimiento</th>
							<th>Agente de Venta / Empleado</th>
							<th>Código Emisión</th>
							<th>Acción</th>							
						</tr>

					</thead>
					<tbody>
						<tr ng-repeat="puntoventa in puntoventas" >
							<td>{{puntoventa.idpuntoventa}}</td>
							<td>{{puntoventa.razonsocial}}</td>
							<td>{{puntoventa.namepersona+' '+puntoventa.lastnamepersona}}</td>
							<td>{{puntoventa.codigoptoemision}}</td>
							<td>
								<button type="button" class="btn btn-warning">
					                <span class="glyphicon glyphicon glyphicon-edit" ng-click="toggle('edit',puntoventa.idpuntoventa)" aria-hidden="true"></span> 
					            </button>
					            <button type="button" class="btn btn-danger">
					                <span class="glyphicon glyphicon glyphicon-trash" ng-click="showModalConfirm(puntoventa.idpuntoventa)" aria-hidden="true"></span> 
					            </button>
							</td>
						</tr>
					</tbody>
				</table>
				<div class="modal fade" tabindex="-1" role="dialog" id="modalConfirmDelete">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header modal-header-danger">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Confirmación</h4>
                    </div>
                    <div class="modal-body">
                        <span>Realmente desea eliminar el Punto de Venta: <span style="font-weight: bold;">{{cargo_seleccionado}}</span></span>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            Cancelar <span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span>
                        </button>
                        <button type="button" class="btn btn-danger" id="btn-save" ng-click="delete()">
                            Eliminar <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                        </button>
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
	    		<div class="modal fade" tabindex="-1" role="dialog" id="modalEmpleadoVacio">
		            <div class="modal-dialog" role="document">
		                <div class="modal-content">
		                    <div class="modal-header modal-header-error">
		                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		                        <h4 class="modal-title">Mensaje</h4>
		                    </div>
		                    <div class="modal-body">
		                        <span>{{message}}</span>
		                    </div>
		            	</div>
		        	</div>
	    		</div>
				 <div class="modal fade" tabindex="-1" role="dialog" id="modalActionPuntoventa">
				 	<div class="modal-dialog" role="document">
                		<div class="modal-content">
				                		<form class="form-horizontal" name="formpuntoventa" novalidate="">
				                			<div class="modal-header modal-header-primary">
				                        		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				                        		<h4 class="modal-title">{{form_title}}</h4>
				                    		</div>
				                    		<div class="modal-body">
				                    		
									            <div class="col-xs-12" style="margin-top: 5px;">
										
													<div class="col-xs-12" style="margin-top: 5px;">
														<div class="input-group">                        
											                <span class="input-group-addon">Establecimiento: </span>
											                <input type="text" class="form-control" ng-model="establecimiento" disabled/>
											            </div> 
													</div>

													<div class="col-xs-12" style="margin-top: 5px;">
														<div class="input-group">                        
											                <span class="input-group-addon">Empleado Agente de Ventas: </span>
											                 <angucomplete-alt 
				                                            	  id="empleado"
													              pause="200"
													              selected-object="Empleado"						
													              input-changed="inputChanged"
																  focus-out="focusOut()"
													              remote-url="{{API_URL}}puntoventa/getempleado/"
													              title-field="numdocidentific,namepersona,lastnamepersona"
													              description-field="twitter"   
													              minlength="1"									         
													              input-class="form-control form-control-small"
													              match-class="highlight"
													              field-required="true"
													              input-name="empleado"
													              text-searching="Buscando Empleado"
													              text-no-results="Empleado no encontrado"
													              initial-value="empleado"
													              />
											            </div> 
											            <span class="help-block error"
				                                                  ng-show="formpuntoventa.empleado.$invalid && formpuntoventa.empleado.$touched">El empleado es requerido.</span>
				                                        <input type="text" class="form-control" ng-show="false" ng-disabled="true" ng-cloak ng-model="Empleado.originalObject.numdocidentific">
													</div>

													<div class="col-xs-12" style="margin-top: 	5px;">
														<div class="input-group">                        
											                <span class="input-group-addon">Código Emisión: </span>
											                <input type="text" class="form-control" name="codigo" id="codigo" ng-model="codigo" ng-keypress="onlyNumber($event,3,'codigo')" ng-blur="verificarEmision();" ng-maxlength="3" maxlength="3" required>
											            </div>
											            <span class="help-block error"
				                                          ng-show="formpuntoventa.codigo.$invalid && formpuntoventa.codigo.$touched">EL código es requerido</span>
				                                          <span class="help-block error"
				                                          ng-show="confirmacion">El código ya existe</span>
													</div>
										</form>		
						</div>					
						<div class="modal-footer">
								<div class="col-xs-12 text-right" style="margin-top: 10px;">
									<button type="button" class="btn btn-default" data-dismiss="modal">
							            Cancelar <span class="glyphicon glyphicon glyphicon-ban-circle" aria-hidden="true"></span> 
							        </button>
									<button type="button" class="btn btn-success" ng-click="Save()" ng-disabled="formpuntoventa.$invalid || confirmacion">
							            Guardar <span class="glyphicon glyphicon glyphicon-floppy-saved" aria-hidden="true"></span> 
							        </button>
								</div>
						</div>
					</div>
		        </div>
			</div>

			
	    </div>

		</div>
</body>


<script src="<?= asset('app/lib/angular/angular.min.js') ?>"></script>
<script src="<?= asset('app/lib/angular/angular-route.min.js') ?>"></script>


<script src="<?= asset('app/lib/angular/ng-file-upload-shim.min.js') ?>"></script>
<script src="<?= asset('app/lib/angular/ng-file-upload.min.js') ?>"></script>


<script src="<?= asset('js/jquery.min.js') ?>"></script>
<script src="<?= asset('js/bootstrap.min.js') ?>"></script>
<script src="<?= asset('js/moment.min.js') ?>"></script>
<script src="<?= asset('js/es.js') ?>"></script>
<script src="<?= asset('js/bootstrap-datetimepicker.min.js') ?>"></script>
<script src="<?= asset('app/lib/angular/angucomplete-alt.min.js') ?>"></script>
<script src="<?= asset('app/lib/angular/dirPagination.js') ?>"></script>

<script src="<?= asset('app/app.js') ?>"></script>
<script src="<?= asset('app/controllers/transportistaController.js') ?>"></script>
<script src="<?= asset('app/controllers/puntoventaController.js') ?>"></script>

</html>