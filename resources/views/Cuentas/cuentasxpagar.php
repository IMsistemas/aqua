

<div ng-controller="cuentasporPagarController">

    <div class="col-xs-12">

        <h4>Cuentas por Pagar</h4>

        <hr>

    </div>

    <div class="col-xs-12" style="margin-top: 5px;">

        <div class="col-sm-6 col-xs-8">
            <div class="form-group has-feedback">
                <input type="text" class="form-control" id="busqueda" placeholder="BUSCAR..." ng-model="busqueda" ng-keyup="initLoad()">
                <span class="glyphicon glyphicon-search form-control-feedback" aria-hidden="true"></span>
            </div>
        </div>

        <div class="col-sm-3 col-xs-2">
            <div class="input-group">
                <span class="input-group-addon">Fecha Inicio:</span>
                <input type="text" class="datepicker form-control" name="fechainicio" id="fechainicio" ng-model="fechainicio" ng-blur="initLoad()">
            </div>
        </div>

        <div class="col-sm-3 col-xs-2">
            <div class="input-group">
                <span class="input-group-addon">Fecha Fin:</span>
                <input type="text" class="datepicker form-control" name="fechafin" id="fechafin" ng-model="fechafin" ng-blur="initLoad()">
            </div>
        </div>


        <div class="col-xs-12" style="font-size: 12px !important;">

            <table class="table table-responsive table-striped table-hover table-condensed table-bordered">
                <thead class="bg-primary">
                    <tr>
                        <th style="width: 4%;">NO.</th>
                        <th style="width: 10%;">FECHA</th>
                        <th>PROVEEDOR</th>
                        <th style="width: 11%;">NO FACTURA</th>
                        <th style="width: 11%;">VALOR TOTAL</th>
                        <th style="width: 11%;">VALOR PAGADO</th>
                        <th style="width: 11%;">PENDIENTE</th>
                        <th style="width: 11%;">ACCION</th>
                    </tr>
                </thead>
                <tbody>
                    <tr ng-repeat="item in list | filter : busqueda" ng-cloak">

                        <td>{{$index + 1}}</td>

                        <td class="text-center">{{item.fecharegistrocompra}}</td>

                        <td>{{item.razonsocial}}</td>


                        <td class="text-center">{{item.numdocumentocompra}}</td>

                        <td class="text-right">$ {{item.valortotalcompra }}</td>

                        <td class="text-right">$ {{item.valorcobrado}}</td>

                        <td class="text-right">$ {{(item.valortotalcompra - item.valorcobrado).toFixed(2)}}</td>

                        <td class="text-right">
                            <button type="button" class="btn btn-primary" ng-click="showModalListCobro(item)" title="Cobros">
                               Pagos <span class="glyphicon glyphicon-usd" aria-hidden="true">
                            </button>
                        </td>

                    </tr>
                </tbody>
            </table>

        </div>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" id="listCobros">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header modal-header-primary">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Listado de Pagos x Factura de Compra</h4>
                </div>
                <div class="modal-body">
                    <div class="row">

                        <div class="col-xs-12 text-right">
                            <button type="button" id="btn-cobrar" class="btn btn-primary" ng-click="showModalFormaCobro()">
                                Pagar <span class="glyphicon glyphicon-usd" aria-hidden="true">
                            </button>
                        </div>

                        <div class="col-xs-12" style="font-size: 12px !important; margin-top: 5px;">

                            <table class="table table-responsive table-striped table-hover table-condensed table-bordered">
                                <thead class="bg-primary">
                                    <tr>
                                        <th style="width: 4%;">NO. COMPROBANTE</th>
                                        <th style="width: 10%;">FECHA</th>
                                        <th>FORMA PAGO</th>
                                        <th style="width: 11%;">VALOR</th>
                                        <th style="width: 12%;">ESTADO</th>
                                        <th style="width: 5%;">ACCION</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="item in listcobro" ng-cloak">

                                        <td>{{item.nocomprobante}}</td>
                                        <td class="text-center">{{item.fecharegistro}}</td>
                                        <td>{{item.nameformapago}}</td>
                                        <td class="text-right">$ {{item.valorpagado}}</td>
                                        <td class="text-right">{{(item.estadoanulado) ? 'ANULADA' : 'NO ANULADA'}}</td>
                                        <td class="text-center" style="width: 12%;">
                                            <button ng-show="item.estadoanulado == false" type="button" class="btn btn-default" ng-click="showModalConfirm(item)" title="Anular">
                                                <span class="glyphicon glyphicon-ban-circle" aria-hidden="true">
                                            </button>
                                            <button type="button" class="btn btn-info" ng-click="printComprobante(item.idcuentasporpagar)" title="Imprimir">
                                                <span class="glyphicon glyphicon-print" aria-hidden="true">
                                            </button>
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

    <div class="modal fade" tabindex="-1" role="dialog" id="formCobros">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header modal-header-info">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Forma de Cobro </h4>
                </div>
                <div class="modal-body">

                    <form class="form-horizontal" name="formCobro" novalidate="">

                        <div class="col-sm-6 col-xs-12" style="margin-top: 5px;">
                            <div class="input-group">
                                <span class="input-group-addon">Fecha Cobro: </span>
                                <input type="text" class="form-control datepicker" name="fecharegistro" id="fecharegistro" ng-model="fecharegistro" ng-blur="autoAssignDate()" required >
                            </div>
                            <span class="help-block error"
                                  ng-show="formCobro.fecharegistro.$invalid && formCobro.fecharegistro.$touched">La Fecha Registro es requerido</span>
                        </div>

                        <div class="col-sm-6 col-xs-12" style="margin-top: 5px;">
                            <div class="input-group">
                                <span class="input-group-addon">No. Comprobante: </span>
                                <input type="text" class="form-control" name="nocomprobante" id="nocomprobante" ng-model="nocomprobante" required >
                            </div>
                            <span class="help-block error"
                                  ng-show="formCobro.nocomprobante.$invalid && formCobro.nocomprobante.$touched">La Fecha Registro es requerido</span>
                        </div>

                        <div class="col-xs-12" style="margin-top: 5px;">
                            <div class="input-group">
                                <span class="input-group-addon">Forma Pago: </span>
                                <select class="form-control" name="formapago" id="formapago" ng-model="formapago" ng-required="true"
                                        ng-options="value.id as value.label for value in listformapago" >
                                </select>
                            </div>
                            <span class="help-block error"
                                  ng-show="formCobro.formapago.$invalid && formCobro.formapago.$touched">La Forma Pago es requerida</span>
                        </div>

                        <div class="col-sm-6 col-xs-12" style="margin-top: 5px;">
                            <div class="input-group">
                                <span class="input-group-addon">A Pagar: </span>
                                <input type="text" class="form-control" id="valorpendiente" ng-model="valorpendiente" disabled>
                            </div>
                        </div>

                        <div class="col-sm-6 col-xs-12" style="margin-top: 5px;">
                            <div class="input-group">
                                <span class="input-group-addon">Pagado: </span>
                                <input type="text" class="form-control" name="valorrecibido" id="valorrecibido" ng-model="valorrecibido" required />
                            </div>
                            <span class="help-block error"
                                  ng-show="formCobro.valorrecibido.$invalid && formCobro.valorrecibido.$touched">Pagado es requerido</span>
                        </div>

                        <div class="col-xs-12" style="margin-top: 5px;">
                            <div class="input-group">
                                <span class="input-group-addon">Concepto: </span>
                                <input type="text" class="form-control" id="concepto" ng-model="concepto" />
                            </div>
                        </div>

                        <div class="col-xs-12" style="margin-top: 5px;">
                            <div class="input-group">
                                <span class="input-group-addon">No. Cheque (si procede): </span>
                                <input type="text" class="form-control" id="nocheque" ng-model="nocheque" />
                            </div>
                        </div>

                        <div class="col-xs-12" style="margin-top: 5px;">
                            <div class="input-group">
                                <span class="input-group-addon">C. Contab.: </span>
                                <input type="text" class="form-control" name="cuenta_employee" id="cuenta_employee" ng-model="cuenta_employee" placeholder=""
                                       ng-required="true" readonly>
                                <span class="input-group-btn" role="group">
                                    <button type="button" class="btn btn-info" id="btn-pcc" ng-click="showPlanCuenta()">
                                        <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                    </button>
                                </span>

                            </div>
                            <span class="help-block error"
                                  ng-show="formCobro.cuenta_employee.$error.required">La asignación de una cuenta es requerida</span>
                        </div>

                    </form>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        Cancelar <span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span>
                    </button>
                    <button type="button" class="btn btn-primary" id="btn-ok" ng-click="saveCobro()" ng-disabled="formCobro.$invalid">
                        Aceptar <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" id="modalPlanCuenta">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header modal-header-primary">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Plan de Cuenta</h4>
                </div>
                <div class="modal-body">
                    <div class="row">

                        <div class="col-xs-12">
                            <div class="form-group  has-feedback">
                                <input type="text" class="form-control" id="" ng-model="searchContabilidad" placeholder="BUSCAR..." >
                                <span class="glyphicon glyphicon-search form-control-feedback" ></span>
                            </div>
                        </div>

                        <div class="col-xs-12">
                            <table class="table table-responsive table-striped table-hover table-condensed table-bordered">
                                <thead class="bg-primary">
                                <tr>
                                    <th style="width: 15%;">ORDEN</th>
                                    <th>CONCEPTO</th>
                                    <th style="width: 10%;">CODIGO</th>
                                    <th style="width: 4%;"></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr ng-repeat="item in cuentas | filter:searchContabilidad" ng-cloak >
                                    <td>{{item.jerarquia}}</td>
                                    <td>{{item.concepto}}</td>
                                    <td>{{item.codigosri}}</td>
                                    <td>
                                        <input ng-show="item.madreohija=='1'" ng-hide="item.madreohija!='1'" type="radio" name="select_cuenta"  ng-click="click_radio(item)">
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        Cancelar <span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span>
                    </button>
                    <button type="button" class="btn btn-primary" id="btn-ok" ng-click="selectCuenta()">
                        Aceptar <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" id="modalMessageError" style="z-index: 99999;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header modal-header-danger">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Información</h4>
                </div>
                <div class="modal-body">
                    <span>{{message_error}}</span>
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

    <div class="modal fade" tabindex="-1" role="dialog" id="modalConfirmAnular">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header modal-header-danger">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Confirmación</h4>
                </div>
                <div class="modal-body">
                    <span>Está seguro que desea Anular el Pago seleccionado...?</span>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        Cancelar <span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span>
                    </button>
                    <button type="button" class="btn btn-danger" id="btn-save" ng-click="anular()">
                        Anular
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="WPrint" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header btn-primary">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="WPrint_head"></h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xs-12" id="bodyprint">

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar <i class="glyphicon glyphicon glyphicon-ban-circle"></i> </button>
                </div>
            </div>
        </div>
    </div>

</div>


