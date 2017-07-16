
	<div class="container-fluid" ng-controller="Contabilidad" ng-init="LoadTipoTransaccion();" ng-cloak>

        <div class="col-xs-12">

            <h4>Plan de Cuentas</h4>

            <hr>

        </div>

		<div class="row">

			<div class="col-xs-6">

                <div class="row">
                    <div class="col-xs-4">
                        <div class="input-group">
                          <span class="input-group-addon">Generar: </span>
                          <select class="form-control input-sm" ng-model="GenerarPlanCuentasTipo">
                              <option value="E">Estado De Resultado</option>
                              <option value="B">Balance</option>
                          </select>
                        </div>
                    </div>
                    <div class="col-xs-3" ng-show=" GenerarPlanCuentasTipo=='E' " ng-hide="GenerarPlanCuentasTipo=='B' ">
                        <div class="input-group">
                          <span class="input-group-addon">Fecha I.: </span>
                          <input type="type" class="form-control datepicker  input-sm" id="FechaI" ng-model="FechaI">
                        </div>
                    </div>
                    <div class="col-xs-3" ng-show=" GenerarPlanCuentasTipo=='E' ||  GenerarPlanCuentasTipo=='B' " >
                        <div class="input-group">
                          <span class="input-group-addon">Fecha F.: </span>
                          <input type="type" class="form-control datepicker  input-sm" id="FechaF" ng-model="FechaF">
                        </div>
                    </div>
                    <div class="col-xs-2">
                        <div class="btn-group">
                          <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Action <i class="glyphicon glyphicon-cog"></i> <span class="caret"></span>
                          </button>
                          <ul class="dropdown-menu">
                            <li><a href="#" ng-click="GenereraFiltroPlanCuentas();" ><i class="glyphicon glyphicon-cog"></i> Generar</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="#" ng-click="AgregarCuentaMadre();" ><i class="glyphicon glyphicon-plus"></i> Crear Cuenta Madre </a></li>
                          </ul>
                        </div>
                    </div>
                </div>

                <div class="row" style="padding-top: 2px;">
                  <div class="col-xs-12">
                    <div class="form-group  has-feedback">
                      <input type="text" class="form-control" id="" ng-model="FiltraCuentaPlan" placeholder="Buscar" >
                      <span class="glyphicon glyphicon-search form-control-feedback" ></span>
                    </div>
                  </div>
                </div>

                <div class="row">
                    <div class="col-xs-12">
                        <table class="table table-bordered table-condensed">
                            <thead>
                                <tr class="bg-primary">
                                    <th colspan="6"></th>
                                </tr>
                                <tr class="bg-primary">
                                    <th style="width: 20%;"></th>
                                    <th ></th>
                                    <th style="width: 35%;">Detalle</th>
                                    <th style="width: 10%;">Codigo</th>
                                    <th style="width: 15%;">Balance</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody style="font-size: 12px;">
                                <tr ng-repeat="cuenta in CuentasContables | filter:FiltraCuentaPlan " >
                                    <td>
                                        <button class="btn btn-primary btn-sm" ng-click="AgregarCuentahija(cuenta);"><i class="glyphicon glyphicon glyphicon-plus"></i></button>
                                        <button class="btn btn-warning btn-sm" ng-click="ModificarCuentaC(cuenta);"><i class="glyphicon glyphicon glyphicon-edit"></i></button>
                                        <button ng-show="cuenta.madreohija=='1' " ng-hide=" cuenta.madreohija!='1' " class="btn btn-danger btn-sm"  ng-click="BorrarCuentaC(cuenta);"><i class="glyphicon glyphicon glyphicon-trash"></i></button>
                                    </td>
                                    <td>{{cuenta.aux_jerarquia}}</td>
                                    <td>{{cuenta.concepto}}</td>
                                    <td>{{cuenta.codigosri}}</td>
                                    <td class="text-right">{{formato_dinero(cuenta.balance,"$")}}</td>
                                    <td>
                                      <button ng-click="RegistroContableCuenta(cuenta);"; class="btn btn-primary btn-sm" ng-show="cuenta.madreohija=='1' " ng-hide=" cuenta.madreohija!='1' ">
                                        <span class="glyphicon glyphicon glyphicon-th-list" aria-hidden="true"></span>
                                      </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            <!--Registro-->
            <div class="col-xs-6">

              <div class="row">
                <div class="col-xs-1">
                  <button class="btn btn-primary" ng-click="AddAsientoContable();">Agregar  Asiento contable <i class="glyphicon glyphicon-plus"></i></button>
                </div>
              </div>
              <div class="row" style="padding-top: 5px;">
                <div class="col-xs-3">
                  <div class="input-group">
                    <span class="input-group-addon">Estado: </span>
                    <select class="form-control" ng-model="EstadoAsc" id="EstadoAsc" ng-change="LoadRegistroCuenta();">
                      <option value="Ac">Activas</option>
                      <option value="An">Anuladas</option>
                    </select>
                  </div>
                </div>
                <div class="col-xs-3">
                  <div class="input-group">
                    <span class="input-group-addon">Fecha I.: </span>
                    <input type="type" class="form-control datepicker  input-sm" id="FechaRI" ng-model="FechaRI">
                  </div>
                </div>
                <div class="col-xs-3">
                  <div class="input-group">
                    <span class="input-group-addon">Fecha F.: </span>
                    <input type="type" class="form-control datepicker  input-sm" id="FechaRF" ng-model="FechaRF">
                  </div>
                </div>
                <div class="col-xs-3">
                  <button class="btn btn-primary" ng-click="LoadRegistroCuenta();" >Actualizar <i class="glyphicon glyphicon-refresh"></i></button>
                </div>
              </div>

              <div class="row" style="padding-top: 5px;">
                <div class="col-xs-12">
                  <table class="table table-bordered table-condensed">
                    <thead>
                      <tr class="bg-primary">
                        <th colspan="9" class="text-center">
                          {{aux_CuentaContableSelc.concepto}}
                        </th>
                      </tr>
                      <tr class="bg-primary">
                        <th></th>
                        <th></th>
                        <th>Tipo</th>
                        <th>Fecha</th>
                        <th>Numero T.</th>
                        <th>Concepto</th>
                        <th>Debe</th>
                        <th>Haber</th>
                        <th>Saldo</th>
                      </tr>
                    </thead>
                    <tbody style="font-size: 12px;">
                      <tr ng-repeat=" registro in RegistroCuentaContable">
                        <td>{{$index+1}}</td>
                        <td>
                          <button    class="btn btn-warning btn-sm" ng-click="ProcesoModificarAsientoCt(registro)" > <i class="glyphicon glyphicon glyphicon-edit"></i></button>
                          <button ng-disabled="EstadoAsc=='An' " class="btn btn-danger btn-sm" ng-click="ProcesoBorrarAsientoCt(registro)" > <i class="glyphicon glyphicon glyphicon-ban-circle"></i></button>
                        </td>
                        <td>{{ registro.cont_transaccion.cont_tipotransaccion.siglas }}</td>
                        <td>{{ registro.fecha }}</td>
                        <td>{{ registro.cont_transaccion.numcontable }}</td>
                        <td>{{ registro.descripcion }}</td>
                        <td>{{ formato_dinero(registro.debe_c,"$") }}</td>
                        <td>{{ formato_dinero(registro.haber_c,"$") }}</td>
                        <td>{{ formato_dinero(registro.saldo,"$") }}</td>
                      </tr>
                    </tbody>
                    <tfoot>
                      <th colspan="6" class="text-right">Total: </th>
                      <th>{{formato_dinero(aux_total_debe,"$")}}</th>
                      <th>{{formato_dinero(aux_total_haber,"$")}}</th>
                      <th>{{formato_dinero((aux_total_debe-aux_total_haber),"$")}}</th>
                      <!--<th>{{formato_dinero(aux_total_saldo,"$")}}</th>-->
                    </tfoot>
                  </table>
                </div>
            </div>

            </div>

		</div>


        <div class="modal fade" id="AddCCMadre" tabindex="-1" role="dialog">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Agregar Cuenta Madre</h4>
              </div>
              <div class="modal-body">

                    <div class="row">
                        <div class="col-xs-6">
                            <div class="input-group">
                              <span class="input-group-addon">Concepto: </span>
                              <input type="type" class="form-control   input-sm"  ng-model="ConceptoCCM" >

                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="input-group">
                              <span class="input-group-addon">Codigo : </span>
                              <input type="type" class="form-control   input-sm" ng-model="CodigoSRICCM">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-6">
                            <div class="input-group">
                              <span class="input-group-addon">Tipo Estado Financiero: </span>
                              <select class="form-control input-sm" ng-model="TipoestadoF">
                                  <option value="E">Estado De Resultados</option>
                                  <option value="B">Balance</option>
                              </select>
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="input-group">
                              <span class="input-group-addon">Tipo De Cueta: </span>
                              <select class="form-control input-sm" ng-model="TipoCuenta">
                                  <option value="">Seleccione</option>
                                  <option ng-show="TipoestadoF=='B'" ng-hide="TipoestadoF=='E'" value="A">Activos</option>
                                  <option ng-show="TipoestadoF=='B'" ng-hide="TipoestadoF=='E'" value="P">Pasivos</option>
                                  <option ng-show="TipoestadoF=='B'" ng-hide="TipoestadoF=='E'" value="PT">Patrimonio</option>
                                  <option ng-show="TipoestadoF=='E'" ng-hide="TipoestadoF=='B'" value="I">Ingresos</option>
                                  <option ng-show="TipoestadoF=='E'" ng-hide="TipoestadoF=='B'" value="C">Costos</option>
                                  <option ng-show="TipoestadoF=='E'" ng-hide="TipoestadoF=='B'" value="G">Gastos</option>
                              </select>
                            </div>
                        </div>
                    </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar <i class="glyphicon glyphicon glyphicon-ban-circle"></i></button>
                <button type="button" class="btn btn-success" ng-click="GuardarCCMadre();">Guardar <i class="glyphicon glyphicon glyphicon-floppy-saved"></i></button>
              </div>
            </div>
          </div>
        </div>

        <div class="modal fade" id="AddCCNodo" tabindex="-1" role="dialog">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Agregar Cuenta Contable</h4>
              </div>
              <div class="modal-body">

                    <div class="row">
                        <div class="col-xs-6">
                            <div class="input-group">
                              <span class="input-group-addon">Concepto: </span>
                              <input type="type" class="form-control   input-sm"  ng-model="ConceptoCCM" >

                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="input-group">
                              <span class="input-group-addon">Codigo : </span>
                              <input type="type" class="form-control   input-sm" ng-model="CodigoSRICCM">
                            </div>
                        </div>
                    </div>

              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar <i class="glyphicon glyphicon glyphicon-ban-circle"></i></button>
                <button type="button" class="btn btn-success" ng-click="GuardarCCNodo();">Guardar <i class="glyphicon glyphicon glyphicon-floppy-saved"></i></button>
              </div>
            </div>
          </div>
        </div>

        <div class="modal fade" id="ModifyCCNodo" tabindex="-1" role="dialog">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Modificar Cuenta Contable</h4>
              </div>
              <div class="modal-body">

                    <div class="row">
                        <div class="col-xs-6">
                            <div class="input-group">
                              <span class="input-group-addon">Concepto: </span>
                              <input type="type" class="form-control   input-sm"  ng-model="ConceptoCCM" >

                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="input-group">
                              <span class="input-group-addon">Codigo : </span>
                              <input type="type" class="form-control   input-sm" ng-model="CodigoSRICCM">
                            </div>
                        </div>
                    </div>

              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar <i class="glyphicon glyphicon glyphicon-ban-circle"></i></button>
                <button type="button" class="btn btn-success" ng-click="GuardarModificacionNodo();">Guardar <i class="glyphicon glyphicon glyphicon-floppy-saved"></i></button>
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

        <div class="modal fade" id="msmBorarCC" tabindex="-1" role="dialog">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header btn-danger" id="titulomsm">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Mensaje De Validación</h4>
              </div>
              <div class="modal-body">
                <strong>Esta seguro de borrar</strong>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar <i class="glyphicon glyphicon glyphicon-ban-circle"></i></button>
                <button type="button" class="btn btn-danger" ng-click="okBorrarCuenta();" >Eliminar <i class="glyphicon glyphicon glyphicon-ok"></i></button>
              </div>
            </div>
          </div>
        </div>

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

        <div class="modal fade" id="AddAsc" tabindex="-1" role="dialog">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Agregar Asiento Contable</h4>
              </div>
              <div class="modal-body">

              <div class="row">
                <div class="col-xs-4">
                  <div class="input-group">
                    <span class="input-group-addon">Fecha : </span>
                    <input type="type" class="form-control datepicker  input-sm" id="FechaIASC" ng-model="FechaIASC">
                  </div>
                </div>
                <div class="col-xs-6">
                  <div class="input-group">
                    <span class="input-group-addon">Transacción: </span>
                    <select ng-disabled="EstadoSave=='M'"  class="form-control" id="tipotransaccion" ng-model="tipotransaccion" ng-change="NumeroComprobante();">
                      <option value="">Seleccione</option>
                      <option ng-repeat=" transaccion in listatipotransaccion" value="{{transaccion.idtipotransaccion}}">{{transaccion.descripcion +" "+ transaccion.cont_tipoingresoegreso.descripcion}}</option>
                    </select>
                  </div>
                </div>


              </div>

              <div class="row">
                <div class="col-xs-4">
                  <div class="input-group">
                    <span class="input-group-addon">Numero De comprobante: </span>
                    <input type="number" class="form-control   input-sm"   ng-model="NumeroIASC" readonly>
                  </div>
                </div>

                <div class="col-xs-6">
                  <div class="input-group">
                    <span class="input-group-addon">Descripción: </span>
                    <input type="type" class="form-control   input-sm"  ng-model="DescripcionASC">
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-xs-4">
                  <button ng-disabled="EstadoSave=='M'"  ng-click="AddIntemCotable()" class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i></button>
                </div>
              </div>
              <div class="row">
                <div class="col-xs-12">
                  <table class="table table-bordered table-condensed">
                    <thead>
                      <tr class="bg-primary">
                        <th></th>
                        <th></th>
                        <th>Cuenta</th>
                        <th>Debe</th>
                        <th>Haber</th>
                        <th>Descripción</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr ng-repeat="registro in RegistroC">
                        <td>
                          <button ng-disabled="EstadoSave=='M'"  class="btn btn-danger" ng-click="BorrarFilaAsientoContable(registro);"><i class="glyphicon glyphicon-trash"></i></button>
                        </td>
                        <td>
                          <input type="type" class="form-control datepicker  input-sm"  ng-model="registro.aux_jerarquia" readonly>
                        </td>
                        <td>
                          <div class="input-group">
                            <input type="hidden" class="form-control datepicker  input-sm"  ng-model="registro.idplancuenta">
                            <input type="hidden" class="form-control datepicker  input-sm"  ng-model="registro.tipocuenta">
                            <input type="hidden" class="form-control datepicker  input-sm"  ng-model="registro.controlhaber">
                            <input type="type" class="form-control datepicker  input-sm"  ng-model="registro.concepto" readonly>
                            <span ng-disabled="EstadoSave=='M'" ng-click="BuscarCuentaContable(registro);" class="btn btn-info input-group-addon"><i class="glyphicon glyphicon-search"></i></span>
                          </div>
                        </td>
                        <td>
                            <input ng-disabled="EstadoSave=='M'" type="type" class="form-control datepicker  input-sm"  ng-model="registro.Debe" ng-keyup="SumarDebeHaber();">
                        </td>
                        <td>
                            <input ng-disabled="EstadoSave=='M'" type="type" class="form-control datepicker  input-sm"  ng-model="registro.Haber" ng-keyup="SumarDebeHaber();">
                        </td>
                        <td>
                            <input ng-disabled="EstadoSave=='M'" type="type" class="form-control datepicker  input-sm"  ng-model="registro.Descipcion">
                        </td>
                      </tr>
                    </tbody>
                    <tfoot>
                      <tr>
                        <th colspan="3" class="text-right"> Diferencia: </th>
                        <th>{{formato_dinero(aux_sumdebedif,"$")}}</th>
                        <th>{{formato_dinero(aux_sumhaberdif,"$")}}</th>
                        <td></td>
                      </tr>
                      <tr>
                        <th colspan="3" class="text-right"> Total: </th>
                        <th>{{formato_dinero(aux_sumdebe,"$")}}</th>
                        <th>{{formato_dinero(aux_sumhaber,"$")}}</th>
                        <td></td>
                      </tr>
                    </tfoot>
                  </table>
                </div>
              </div>


              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar <i class="glyphicon glyphicon glyphicon-ban-circle"></i></button>
                <button type="button" class="btn btn-success" ng-disabled="EstadoAsc=='An' "   ng-click="AsientoContable();">Guardar <i class="glyphicon glyphicon glyphicon-floppy-saved"></i></button>
              </div>
            </div>
          </div>
        </div>

        <div class="modal fade" data-backdrop="static" data-keyboard="false" style="z-index: 9999999;" tabindex="-1" id="procesarinfomracion" role="dialog">
          <div class="modal-dialog" role="document">
            <div class="modal-content">

              <div class="modal-body">

                <div class="progress">
                  <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                    <span > Procesando </span>
                  </div>
              </div>

              </div>

            </div>
          </div>
        </div>

        <div class="modal fade" id="BorraTransaccion" tabindex="-1" role="dialog">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header btn-danger">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Mensaje De Validación</h4>
              </div>
              <div class="modal-body">
                <strong>Está Seguro De Anular La Transacción  Contable</strong>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar <i class="glyphicon glyphicon-ban-circle"></i></button>
                <button type="button" class="btn btn-danger" ng-click="ConfirmarBorrarTransaccion();">Anular <i class="glyphicon glyphicon-ban-circle"></i></button>
              </div>
            </div>
          </div>
        </div>

	</div>
