


<div  class="container-fluid" ng-controller="ConciliacionC" ng-init="" ng-cloak>

    <div class="col-xs-12">

        <h4>Conciliación</h4>

        <hr>

    </div>

	<!--registro conciliacion-->
	<div ng-hide="viewconciliacion!=1 " ng-show=" viewconciliacion==1 "  style="margin-top: 5px;">
		<div class="row">
			<div class="col-xs-4 ">
	            <div class="form-group has-feedback">
	                <input type="text" class="form-control " id="busquedaconciliacion" placeholder="BUSCAR..." ng-model="busquedaconciliacion">
	                <span class="glyphicon glyphicon-search form-control-feedback" aria-hidden="true"></span>
	            </div>
	        </div>

	        <div class="col-xs-4 ">
	            <div class="input-group">
	                <span class="input-group-addon">Estado: </span>
	                <select ng-model="cmb_estado" name="cmb_estado" id="cmb_estado" class="form-control" ng-change="pageChanged(1)">
	                    <option value="A">Activas</option>
	                    <option value="I">Anuladas</option>
	                </select>
	            </div>
	        </div>

	        <div class="col-xs-4 " >
                <button type="button" class="btn btn-primary"  ng-click="new_conciliacion();" title="Nueva Conciliacion"><i class="glyphicon glyphicon-plus"></i></button>
            </div>
		</div>

		<div class="row">
			<div class="col-xs-12">
				<table class="table table-bordered table-striped">
					<thead class="bg-primary">
						<tr>
							<th style="width: 4%;"></th>
							<th>CONCEPTO</th>
							<th>CUENTA CONTABLE</th>
							<th style="width: 8%;">FECHA</th>
							<th style="width: 12%;">B. INICIAL</th>
							<th style="width: 12%;">B. FINAL</th>
							<th style="width: 8%;">ESTADO</th>
							<th style="width: 8%;">ACCIONES</th>
						</tr>
					</thead>
					<tbody>
						<tr dir-paginate="v in allConciliacion | orderBy:sortKey:reverse |filter:busquedaconciliacion| itemsPerPage:10" total-items="totalItems" ng-cloak">
						<td>{{$index+1}}</td>
						<td>{{v.descripcion}}</td>
						<td>{{v.cont_plancuenta.concepto}}</td>
						<td>{{v.fecha}}</td>
						<td class="text-right">{{v.balanceinicial}}</td>
						<td class="text-right">{{v.balancefinal}}</td>
						<td ng-show="v.estadoconciliacion=='2' " ng-hide=" 
						v.estadoconciliacion!='2' ">Finalizado</td>
						<td ng-show="v.estadoconciliacion=='0' " ng-hide=" v.estadoconciliacion!='0' ">En Proceso</td>
						<td>
                            <div class="btn-group" role="group" aria-label="...">
                                <button type="button" class="btn btn-primary btn-sm" ng-click='reload_conciliacion(v)'> <i class="glyphicon glyphicon glyphicon-cog"></i> </button>
                                <button type="button" class="btn btn-default btn-sm" ng-click="anular_conciliacion(v)" ><i class="glyphicon glyphicon glyphicon-ban-circle"></i></button>
                            </div>
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
	</div>
	<!--registro conciliacion-->

	<!--conciliacion de cuentas-->
	<div ng-hide="viewconciliacion!=2 " ng-show=" viewconciliacion==2 ">
		

		<div class="row">
			<div id="alert" class="alert alert-success" style="width: 10%; z-index: 500; position: absolute; float:left;" role="alert">Guardando</div>
			<div class="col-xs-12 text-center">
				{{aux_cuenta_contable_conciliada}}
			</div>
		</div>
		<div class="row">
			<div class="col-xs-6">
				<table class="table table-bordered table-striped table-condensed">
					<thead class="btn-success">
						<tr>
							<th colspan="6" class="text-center">Ingresos </th>
						</tr>
						<tr>
							<th></th>
							<th></th>
							<th>Numero</th>
							<th>Fecha</th>
							<th>Descripcion</th>
							<th>Dinero</th>
						</tr>
					</thead>
					<tbody>
						<tr ng-repeat=" ing in list_ingresos ">
							<th>{{$index+1}}</th>
							<th ng-hide="ing.idconciliacion" > 
								<input type="checkbox" name="" id="{{'i'+ing.idregistrocontable}}" ng-disabled=" aux_data_conciliacion.estadoconciliacion=='2'  "   ng-click="proc_conciliacion(ing.idregistrocontable,ing,1)"> 
							</th>

							<th ng-show="ing.idconciliacion>0" > 
								<input type="checkbox" name="" id="{{'i'+ing.idregistrocontable}}" checked="checked" ng-disabled=" aux_data_conciliacion.estadoconciliacion=='2'  "   ng-click="proc_conciliacion(ing.idregistrocontable,ing,1)"> 
							</th>
							

							<th>{{ing.cont_transaccion.numcomprobante}}</th>
							<th>{{ing.fecha}}</th>
							<th>{{ing.cont_transaccion.descripcion}}</th>
							<th>{{total_cuenta_conciliar(ing.debe_c,ing.haber_c)}}</th>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="col-xs-6">
				<table class="table table-bordered table-striped table-condensed">
					<thead class="btn-info">
						<tr>
							<th colspan="6" class="text-center">Egresos </th>
						</tr>
						<tr>
							<th></th>
							<th></th>
							<th>Numero</th>
							<th>Fecha</th>
							<th>Descripcion</th>
							<th>Dinero</th>
						</tr>
					</thead>
					<tbody>
						<tr ng-repeat=" eg in list_egresos ">
							<th>{{$index+1}}</th>
							<th ng-hide="eg.idconciliacion"> 
								<input type="checkbox" name="" id="{{'e'+eg.idregistrocontable}}" ng-disabled=" aux_data_conciliacion.estadoconciliacion=='2'  "  ng-click="proc_conciliacion(eg.idregistrocontable,eg,2)"> 
							</th>
							<th ng-show="eg.idconciliacion>0"> 
								<input type="checkbox" name="" id="{{'e'+eg.idregistrocontable}}" checked="checked" ng-disabled=" aux_data_conciliacion.estadoconciliacion=='2'  "  ng-click="proc_conciliacion(eg.idregistrocontable,eg,2)"> 
							</th>
							<th>{{eg.cont_transaccion.numcomprobante}}</th>
							<th>{{eg.fecha}}</th>
							<th>{{eg.cont_transaccion.descripcion}}</th>
							<th>{{total_cuenta_conciliar(eg.debe_c,eg.haber_c)}}</th>
						</tr>
					</tbody>
				</table>
			</div>
		</div>

		<div class="row">
			<div class="col-xs-6">
				<div class="row">
					<div class="col-xs-12">
						<table class="table table-bordered">
							<thead>
								<tr>
									<th> Balance Inicial: </th>
									<th>{{formato_dinero(aux_balance_inicial,"$")}}</th>
								</tr>
							</thead>
						</table>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12">
						<table class="table table-bordered table-condensed">
							<thead class="bg-primary">
								<tr>
									<th></th>
									<th>Clarificados</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<th>{{aux_contclarificado_egresos}}</th>
									<th>Egresos</th>
									<th>{{formato_dinero(aux_clarificado_egresos,"$")}}</th>
								</tr>
								<tr>
									<th>{{aux_contclarificado_ingresos}}</th>
									<th>Ingresos</th>
									<th>{{formato_dinero(aux_clarificado_ingresos,"$")}}</th>
								</tr>
							</tbody>
						</table>
					</div>
				</div>

				<div class="row">
					<div class="col-xs-12">
						<table class="table table-bordered table-condensed">
							<thead class="bg-primary">
								<tr>
									<th></th>
									<th>No Clarificados</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<th>{{aux_no_contclarificado_egresos}}</th>
									<th>Egresos</th>
									<th>{{formato_dinero(aux_no_clarificado_egresos,"$")}}</th>
								</tr>
								<tr>
									<th>{{aux_no_contclarificado_ingresos}}</th>
									<th>Ingresos</th>
									<th>{{formato_dinero(aux_no_clarificado_ingresos,"$")}}</th>
								</tr>
							</tbody>
						</table>
					</div>
				</div>

			</div>
			<div class="col-xs-6">

				<div class="row">
					<div class="col-xs-12">
						<table class="table table-bordered">
							<thead>
								<tr>
									<th> Balance Final: </th>
									<th>{{formato_dinero(aux_balance_final,"$")}}</th>
								</tr>
								<tr>
									<th> Conciliacion: </th>
									<th>{{formato_dinero(aux_conciliacion_egreso_ingreso,"$")}}</th>
								</tr>
								<tr>
									<th> Diferencia: </th>
									<th>{{formato_dinero(aux_diferencia_conciliacion,"$")}}</th>
								</tr>
							</thead>
						</table>
					</div>
				</div>

				<button type="button" ng-click="close_conciliar()" ng-disabled=" aux_data_conciliacion.estadoconciliacion=='2'  " class="btn btn-success"> Guardar 
					<span class="glyphicon glyphicon glyphicon-floppy-saved" aria-hidden="true"></span>
				</button>
				<button type="button" class="btn btn-primary" ng-click="viewconciliacion=1; pageChanged(1);" >
	                Registros <span class="glyphicon glyphicon glyphicon-th-list" aria-hidden="true"></span> 
	            </button>
			</div>
		</div>
	</div>
	<!--conciliacion de cuentas-->









<form class="form-horizontal" name="frm_conciliacion" id="frm_conciliacion"  novalidate="" >
<div class="modal fade" id="data_conciliacion" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Conciliacion</h4>
      </div>
      <div class="modal-body" style="">
        
      <div class="row">
      	<div class="col-xs-12">
      		<div class="input-group">
      		<span class="input-group-addon"> Cuenta Contable: </span>
            <input type="type" class="form-control input-sm" id="aux_descipcion_cuenta"  ng-model="aux_descipcion_cuenta" readonly placeholder="Cuenta Contable">
            <span  ng-click="BuscarCuentaContable();" class="btn btn-info input-group-addon">
            	<i class="glyphicon glyphicon-search"></i>
            </span>
          </div>
          <span class="help-block error" ng-show="frm_conciliacion.aux_descipcion_cuenta.$invalid && frm_conciliacion.aux_descipcion_cuenta.$touched">
          	La cuenta contable es requerida
          </span>
      	</div>

      	<div class="col-xs-12">
      		<div class="input-group">
              <span class="input-group-addon">Descripcion: </span>
              <input type="type" class="form-control input-sm" id="txt_descripcion" ng-model="txt_descripcion" ng-required="true" >
            </div>
            <span class="help-block error" ng-show="frm_conciliacion.txt_descripcion.$invalid && frm_conciliacion.txt_descripcion.$touched">
          		La descripcion es requerida
          	</span>
      	</div>

      	<div class="col-xs-12">
      		<div class="input-group">
              <span class="input-group-addon">Fecha I.: </span>
              <input type="type" class="form-control datepicker  input-sm" id="FechaI" ng-model="FechaI" >
            </div>
      	</div>

      	<div class="col-xs-12">
      		<div class="input-group">
              <span class="input-group-addon">Balance Incial: </span>
              <input type="type" class="form-control input-sm" id="txt_balanceI" ng-model="txt_balanceI" readonly>
            </div>
      	</div>

      	<div class="col-xs-12">
      		<div class="input-group">
              <span class="input-group-addon">Balance Final: </span>
              <input type="type" class="form-control input-sm" id="txt_balanceF"  ng-keypress="onlyNumber($event, 10, 'txt_balanceF')" ng-model="txt_balanceF" ng-required="true" >
            </div>
             <span class="help-block error" ng-show="frm_conciliacion.txt_balanceF.$invalid && frm_conciliacion.txt_balanceF.$touched">
          		El balance final es requerida
          	</span>
      	</div>
      </div>


      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"> Cancelar <i class="glyphicon glyphicon-ban-circle"></i> </button>
        <button type="button" class="btn btn-primary" ng-disabled="frm_conciliacion.$invalid " ng-click='creat_edit_conciliacion()' > Aceptar <i class="glyphicon glyphicon glyphicon-ok"></i></button>
      </div>
    </div>
  </div>
</div>
</form>




<div class="modal fade" id="PlanContable" style="z-index: 5000;" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header btn-primary" id="titulomsm">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Plan de cuentas contables</h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-xs-12">
            <div class="form-group  has-feedback">
            <input type="text" class="form-control" id="" ng-model="FiltraCuenta" placeholder="Buscar" >
            <span class="glyphicon glyphicon-search form-control-feedback" ></span>
          </div>
          </div>
        </div>
        <div class="row">
          <div class="col-xs-12">
            <table class="table table-bordered table-condensed">
              <thead>
                <tr class="btn-primary">
                  <th></th>
                  <th>Descripción</th>
                  <th>Codigo </th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <tr ng-repeat="cuenta in aux_plancuentas | filter:FiltraCuenta">
                  <td>{{cuenta.aux_jerarquia}}</td>
                  <td>{{cuenta.concepto}}</td>
                  <td>{{cuenta.codigosri}}</td>
                  <td>
                      <input ng-show="cuenta.madreohija=='1' " ng-hide="cuenta.madreohija!='1' " type="checkbox" name="" ng-click="AsignarCuentaContable(cuenta);">
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar <i class="glyphicon glyphicon glyphicon-ban-circle"></i></button>
        <button type="button" class="btn btn-primary" ng-click="AsignarCuentaContable();" >Aceptar <i class="glyphicon glyphicon glyphicon-ok"></i></button>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="msm" style="z-index: 8000;" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary" id="titulomsm">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Mensaje</h4>
            </div>
            <div class="modal-body">
                <strong>{{Mensaje}}</strong>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar <i class="glyphicon glyphicon glyphicon-ban-circle"></i></button>
            </div>
        </div>
    </div>
</div>




</div>


