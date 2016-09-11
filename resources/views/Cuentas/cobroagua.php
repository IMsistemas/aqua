<div ng-controller="recaudacionController">
	<div class="container" style="margin-top: 2%;">
		<div class="col-xs-6">
			<div class="form-group has-feedback">
				<input type="text" class="form-control input-sm" id="search-list-trans" placeholder="BUSCAR..." ng-model="busqueda">
	             <span class="glyphicon glyphicon-search form-control-feedback" aria-hidden="true"></span>
	         </div>
		</div>

		<form class="form-inline">
		
			<button type="button" id="btnNuevaSol" class="btn btn-primary" style="float: right;" ng-click="generarFacturasPeriodo();">Generar</button>

			<div class="form-group">
				<label for="comboYear">Año</label>
				<select class="form-control" id="comboYear" ng-model="yearSeleccionado" >
				<option value="">Seleccione año</option>
					<option ng-repeat="cuenta in cuentas" value="" >{{cuenta.fechaperiodo | date:'yyyy'}}</option>
				</select>
			</div>
			<div class="form-group">
				<label for="comboMes">Mes</label>
				<select class="form-control" id="comboMes" ng-model="mesSeleccionado" >
					<option value="">Seleccione mes</option>
				    <option ng-repeat="cuenta in cuentas" value="" >{{cuenta.fechaperiodo | date:'MMM'}}</option>
				</select>
			</div>


		</form>
	   
	    
           
        
		<div class="col-xs-12">
			<table class="table table-responsive table-striped table-hover table-condensed">
				<thead class="bg-primary">
					<tr>
						<th style="width:8%">
							<a href="#" style="text-decoration:none; color:white;" ng-click="columna = 'cuenta.fechaperiodo'; reversa = !reversa;">Período</a>
						</th>
						<th style="width:8%">
							<a href="#" style="text-decoration:none; color:white;" ng-click="columna = 'cuenta.suministro.numerosuministro'; reversa = !reversa;"># Sum.</a>
						</th>
						<th style="width:25%">
							<a href="#" style="text-decoration:none; color:white;" ng-click="columna = 'Cliente'; reversa = !reversa">Cliente</a>
						</th>
						<th>
							<a href="#" style="text-decoration:none; color:white;"  ng-click="columna = 'cuenta.suministro.tarifa.nombretarifa'; reversa = !reversa;">Tarifa</a>
						</th>
						<th>
							<a href="#" style="text-decoration:none; color:white;" ng-click="columna = 'cuenta.suministro.calle.nombrecalle'; reversa = !reversa;">Ubicación</a>
						</th>
						<th style= "width:25%">Dirección</th>
						<th>Telf.</th>
						<th style="width:5%">
							<a href="#" style="text-decoration:none; color:white;" ng-click="columna = ''; reversa = !reversa;">m<sup>3</sup></a>
						</th>
						<th>
							<a href="#" style="text-decoration:none; color:white;" ng-click="columna = 'cuenta.consumo'; reversa = !reversa;">Total</a>
						</th>
						<th style="width: 25%;" >Acciones</th>
					</tr>
				</thead>
				<tbody>
					<tr ng-repeat="cuenta in cuentas | filter: busqueda | orderBy:columna:reversa" >
						<td>{{cuenta.fechaperiodo | date:'MMM yyyy'}}</td>
						<td>{{cuenta.suministro.numerosuministro}}</td>
						<td>{{cuenta.suministro.cliente.apellido+" "+cuenta.suministro.cliente.nombre}}</td>
						<td>{{cuenta.suministro.tarifa.nombretarifa}}</td>
						<td>{{cuenta.suministro.calle.nombrecalle}}</td>
						<td>{{cuenta.suministro.direccionsuministro}}</td>
						<td>{{cuenta.suministro.telefonosuministro}}</td>
						<td>{{cuenta.consumo}}</td>
						<td>{{25.00 | currency}}</td>
						
						<td>
							<a href="#" class="btn btn-primary" ng-click="modalIngresoOtrosRubros(cuenta.suministro.numerosuministro)">Otros rubros</a>
                            <a href="#" class="btn btn-info" ng-click="modalInformacionCuenta(cuenta.suministro.numerosuministro)">Ver</a>
                           <a href="#" class="btn btn-success"><i class="fa fa-print" aria-hidden="true"></i></a>
                            
						</td>
					</tr>
				</tbody>
			</table>
		</div>

		 <div class="modal fade" id="ingresar-otros-rubros" tabindex="-1" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header  modal-header-primary">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="myModalLabel">Ingreso otros rubros</h4>
						Período: {{cuenta.fechaperiodo | date:'MMM yyyy'}}
					</div>

					<div class="modal-body">
						<form name="formularioOtrosRubros" class="form-horizonal" novalidate="">
							<fieldset>
								<legend>Datos suministro</legend>
								<div class="col-xs-12">
		                                <span style="font-weight: bold">No. suministro: </span>{{cuenta.suministro.numerosuministro}} 
		                        </div>
		                        <div class="col-xs-12">
		                                <span style="font-weight: bold">Cliente: </span>{{cuenta.suministro.cliente.apellido+" "+suministro.cliente.nombre}} 
		                        </div>
		                        <div class="col-xs-12">
		                                <span style="font-weight: bold">Barrio: </span>{{cuenta.suministro.calle.barrio}} 
		                        </div>
		                        <div class="col-xs-12">
		                                <span style="font-weight: bold">Dirección: </span>{{cuenta.suministro.direccionsuministro}} 
		                        </div>
							</fieldset>
							<br>
							<fieldset>
								<legend>Rubros</legend>
							</fieldset>	
								<table class="table table-bordered table-hover">
									<thead class="bg-info">
										<tr>
											<th>Rubro</th>
											<th>Valor</th>
										</tr>
									</thead>
									<tbody>
										<tr ng-repeat="rubroVariable in rubrosVariables">
											<td>{{rubroVariable.nombrerubrovariable}}</td>
											<td><input type="text" name="" ng-model="costo"></td>	
										</tr>
										<tr ng-repeat="rubroFijo in rubrosFijos">
											<td>{{rubroFijo.nombrerubrofijo}}</td>
											<td><input type="text" name=""></td>	
										</tr>
										<tr>
											<td><b>Total</b></td>
											<td>$ 0.40</td>
										</tr>
									</tbody>
								</table>
						</form>
					</div>
					<div class="modal-footer">
						<button class="btn btn-primary">Guardar</button>
					</div>
				</div>
			</div>			
		</div>

		 <div class="modal fade" tabindex="-1" role="dialog" id="modalInfoCuenta">
            <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content">
                    <div class="modal-header modal-header-info">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Periodo: {{cuenta.fechaperiodo | date:'MMM yyyy'}}</h4>
                    </div>
                    <div class="modal-body">
                        <div class="col-xs-12 text-center">
                            <img class="img-thumbnail" src="<?= asset('img/cliente.png') ?>" alt="">
                        </div>
                        <div class="row text-center">
                            <div class="col-xs-12 text-center" style="font-size: 18px;">{{cuenta.suministro.cliente.apellido+" "+cuenta.suministro.cliente.nombre}}</div>
                            <div class="col-xs-12 text-center" style="font-size: 16px;">{{}}</div>
                      
                            <div class="col-xs-12">
                                <span style="font-weight: bold">No. suministro:</span>{{cuenta.suministro.numerosuministro}} 
                            </div>
                            <div class="col-xs-12">
                                <span style="font-weight: bold">m<sup>3</sup> consumidos: </span>{{}}
                            </div>
                            <div class="col-xs-12">
                                <span style="font-weight: bold">m<sup>3</sup> excedidos: </span>{{}}
                            </div>
                            <div class="col-xs-12">
                                <span style="font-weight: bold">Meses atrasados: </span>{{}}
                            </div>
                            <div class="col-xs-12">
                                <span style="font-weight: bold">Otros valores: </span>{{}}
                            </div>
                            <div class="col-xs-12">
                                <span style="font-weight: bold">Total Consumo: </span>{{cuenta.total}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

		
	</div>

</div>
