

<div ng-controller="recaudacionCController" ng-init="initLoad(1)">

    <div class="container">

        <h4>Recaudación (Cobros)</h4>

        <hr>

    </div>

    <div class="container" style="margin-top: 5px;">
        <div class="col-sm-6 col-xs-12">
            <div class="form-group has-feedback">
                <input type="text" class="form-control" id="t_busqueda" placeholder="BUSCAR..." ng-model="busqueda" ng-keyup="initLoad(1)">
                <span class="glyphicon glyphicon-search form-control-feedback" aria-hidden="true"></span>
            </div>
        </div>
        <div class="col-sm-3 col-xs-12">
            <div class="input-group">
                <span class="input-group-addon">Estado:</span>
                <select class="form-control" name="s_estado_search" id="s_estado_search" ng-model="s_estado_search" ng-change="initLoad(1)">
                    <option value="0">-- Seleccione --</option>
                    <option value="1">ACTIVO</option>
                    <option value="2">INACTIVO</option>
                </select>
            </div>
        </div>
        <div class="col-sm-3 col-xs-12">

            <!--<div class="btn-group" role="group" aria-label="...">
                <button type="button" class="btn btn-primary" style="" ng-click="showModalAddCliente()">
                    Agregar <span class="glyphicon glyphicon glyphicon-plus" aria-hidden="true"></span>
                </button>
                <button type="button" class="btn btn-info" ng-click="printReport();">
                    Imprimir <span class="glyphicon glyphicon glyphicon-print" aria-hidden="true"></span>
                </button>
            </div>-->

            <div class="btn-group" role="group" aria-label="...">

                <button type="button" class="btn btn-primary" id="btn-generate" ng-click="generate()" >
                    GENERAR <span class="glyphicon glyphicon-refresh" aria-hidden="true"></span>
                </button>

                <button type="button" class="btn btn-warning" id="btn-generate" ng-click="showCierreCaja()" >
                    CIERRE <span class="glyphicon glyphicon-usd" aria-hidden="true"></span>
                </button>

            </div>



        </div>
    </div>

    <div class="container" style="font-size: 12px !important;">
        <table class="table table-responsive table-striped table-hover table-condensed table-bordered">
            <thead class="bg-primary">
            <tr>
                <th style="width: 8%;">NO. CONEX.</th>
                <th style="width: 10%;">CI / RUC</th>
                <th style="width: 10%;">FECHA INGRESO</th>
                <th style="">RAZON SOCIAL / APELLIDOS Y NOMBRE</th>
                <th style="width: 10%;">VALOR COBRAR</th>
                <th style="width: 8%;">ACCION</th>
            </tr>
            </thead>
            <tbody>
            <tr dir-paginate="item in clientes|orderBy:sortKey:reverse| itemsPerPage:8" total-items="totalItems" ng-cloak >
                <td>{{item.numconexion}}</td>
                <td>{{item.numdocidentific}}</td>
                <td>{{item.fechaingreso | formatDate}}</td>
                <td>{{item.lastnamepersona}} {{item.namepersona}}</td>
                <td class="text-right">$ {{item.valorcobrar}}</td>
                <td class="text-center">

                    <!--<button type="button" class="btn btn-primary btn-sm" ng-click="getTransacciones(item.idcliente)">
                        <i class="fa fa-lg fa-cogs" aria-hidden="true" title=""></i>
                    </button>

                    <button type="button" class="btn btn-primary btn-sm" ng-click="getFacturas(item.idcliente)">
                        <i class="fa fa-lg fa-usd" aria-hidden="true" title=""></i>
                    </button>-->




                    <div class="btn-group" role="group" aria-label="...">

                        <button type="button" class="btn btn-primary" ng-click="getItemsCobro(item.idcliente)">
                            <i class="fa fa-lg fa-usd" aria-hidden="true" title=""></i>
                        </button>

                        <button type="button" class="btn btn-info" ng-click="getRegistroCobro(item.idcliente)" >
                            <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                        </button>

                    </div>

                </td>
            </tr>
            </tbody>
        </table>
        <dir-pagination-controls

            on-page-change="pageChanged(newPageNumber)"

            template-url="dirPagination.html"

            class="pull-right"
            max-size="8"
            direction-links="true"
            boundary-links="true" >

        </dir-pagination-controls>

    </div>

    <div class="modal fade" tabindex="-1" role="dialog" id="listCobros" style="z-index: 99999;">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header modal-header-primary">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Listado de Transacciones a Cobrar </h4>
                </div>
                <div class="modal-body">
                    <div class="row">

                        <div class="col-xs-12 text-right">
                            <button type="button" id="btn-cobrar" class="btn btn-primary" ng-click="createFactura()">
                                Cobrar <span class="glyphicon glyphicon-usd" aria-hidden="true">
                            </button>
                        </div>

                        <div class="col-xs-12" style="font-size: 12px !important; margin-top: 5px;">

                            <table class="table table-responsive table-striped table-hover table-condensed table-bordered">
                                <thead class="bg-primary">
                                    <tr>
                                        <th style="width: 4%;"></th>
                                        <th style="width: 10%;">FECHA</th>
                                        <th>TIPO</th>
                                        <th style="width: 11%;">TOTAL</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="item in listTransacciones" ng-cloak">

                                        <td>
                                            <input class="transfer" type="checkbox" ng-model="item.idtype" value="{{item.idtype}}" ng-true-value="{{item.idtype}}"  />
                                        </td>
                                        <td class="text-center">{{item.fecha}}</td>
                                        <td>{{item.name}}</td>
                                        <td class="text-right">$ {{item.total}}</td>

                                    </tr>
                                </tbody>
                            </table>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" tabindex="-1" role="dialog" id="modalFactura" style="z-index: 99988;">
        <div class="modal-dialog modal-lg" role="document"  style="height: 90%; width: 90%;">
            <div class="modal-content" style="height: 90%;">
                <div class="modal-header modal-header-primary">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Factura</h4>
                </div>
                <div class="modal-body" id="bodyfactura">
                    <div id="aux_venta">

                    </div>
                    <!--<object id="aux_venta" height="450px" width="100%" > <!--content--> <!--/object>-->
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

    <div class="modal fade" tabindex="-1" role="dialog" id="modalCobros">
        <div class="modal-dialog modal-lg" role="document"  style="">
            <div class="modal-content" style="height: 90%;">
                <div class="modal-header modal-header-primary">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Factura</h4>
                </div>
                <div class="modal-body">

                    <div class="col-sm-1 col-xs-2">
                        <button type="button" class="btn btn-primary" ng-click="showModalListCobro2()" title="Cobros">
                            Cobros <span class="glyphicon glyphicon-usd" aria-hidden="true">
                        </button>
                    </div>

                    <table class="table table-responsive table-striped table-hover table-bordered">
                        <thead class="bg-primary">
                            <tr>
                                <th style="width: 1%;"></th>
                                <th style="width: 15%;">FECHA</th>
                                <th style="">NO FACTURA</th>
                                <th style="width: 15%;">VALOR TOTAL</th>
                                <th style="width: 15%;">VALOR COBRADO</th>
                                <th style="width: 15%;">VALOR PENDIENTE</th>
                                <th style="width: 15%;">VALOR A COBRAR</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="item in list track by $index" ng-cloak>

                            <td>
                                <span ng-if="item.iddocumentoventa != undefined">
                                    <input type="checkbox" id="f{{item.iddocumentoventa}}" ng-click="pushListSelect('f', item.iddocumentoventa, item)"/>
                                </span>
                            </td>

                            <td class="text-center" ng-if="item.fecharegistroventa != undefined">{{item.fecharegistroventa}}</td>
                            <td class="text-center" ng-if="item.fechacobro != undefined">{{item.fechacobro}}</td>

                            <!--<td>{{item.lastnamepersona + ' ' + item.namepersona}}</td>-->

                            <td class="text-center" ng-if="item.numdocumentoventa != undefined">{{item.numdocumentoventa}}</td>

                            <td class="text-right" ng-if="item.valortotalventa != undefined">$ {{item.valortotalventa }}</td>
                            <td class="text-right">$ {{item.valorcobrado}}</td>
                            <td class="text-right" ng-if="item.valortotalventa != undefined">$ {{(item.valortotalventa - item.valorcobrado).toFixed(2)}}</td>
                            <td class="text-right" ng-if="item.total != undefined">$ {{(item.total - item.valorcobrado).toFixed(2)}}</td>

                            <td>
                                <input type="text" class="form-control text-right" ng-model="item.acobrar">
                            </td>

                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" id="listCobrosPago" style="z-index: 99999;">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header modal-header-primary">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Listado de Cobros x Factura </h4>
                </div>
                <div class="modal-body">
                    <div class="row">

                        <div class="col-xs-12 text-right">
                            <button type="button" id="btn-cobrar" class="btn btn-primary" ng-click="showModalFormaCobro()">
                                Cobrar <span class="glyphicon glyphicon-usd" aria-hidden="true">
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
                                    <th style="width: 12%;">ACCION</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr ng-repeat="item in listcobro" ng-cloak">

                                <td>{{item.nocomprobante}}</td>
                                <td class="text-center">{{item.fecharegistro}}</td>
                                <td>{{item.nameformapago}}</td>
                                <td class="text-right">$ {{item.valorpagado}}</td>
                                <td class="text-right">{{(item.estadoanulado) ? 'ANULADA' : 'NO ANULADA'}}</td>
                                <td class="text-center">

                                    <div class="btn-group" role="group" aria-label="...">
                                        <button ng-show="item.estadoanulado == false" type="button" class="btn btn-default" ng-click="showModalConfirm(item)" title="Anular">
                                                    <span class="glyphicon glyphicon-ban-circle" aria-hidden="true">
                                        </button>
                                        <button type="button" class="btn btn-info" ng-click="printComprobante(item.idcuentasporcobrar)" title="Imprimir">
                                                    <span class="glyphicon glyphicon-print" aria-hidden="true">
                                        </button>
                                    </div>

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

    <div class="modal fade" tabindex="-1" role="dialog" id="formCobros" style="z-index: 99999;">
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
                                <span class="input-group-addon">Pendiente a Cobrar: </span>
                                <input type="text" class="form-control text-right" id="valorpendiente" ng-model="valorpendiente" disabled>
                            </div>
                        </div>

                        <div class="col-sm-6 col-xs-12" style="margin-top: 5px;">
                            <div class="input-group">
                                <span class="input-group-addon">Cobrado: </span>
                                <input type="text" class="form-control text-right" name="valorrecibido" id="valorrecibido" ng-model="valorrecibido" required />
                            </div>
                            <span class="help-block error"
                                  ng-show="formCobro.valorrecibido.$invalid && formCobro.valorrecibido.$touched">Cobrado es requerido</span>
                        </div>

                        <!--<div class="col-xs-12" style="margin-top: 5px;">
                            <div class="input-group">
                                <span class="input-group-addon">Concepto: </span>
                                <input type="text" class="form-control" id="concepto" ng-model="concepto" />
                            </div>
                        </div>-->

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












    <div class="modal fade" tabindex="-1" role="dialog" id="modalCobrosItems">
        <div class="modal-dialog modal-lg" role="document"  style="">
            <div class="modal-content" style="height: 90%;">
                <div class="modal-header modal-header-primary">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">A Cobrar</h4>
                </div>
                <div class="modal-body">

                    <table class="table table-responsive table-striped table-hover table-bordered">
                        <thead class="bg-primary">
                            <tr>
                                <th>ITEMS</th>
                                <th style="width: 20%;">VALOR PENDIENTE</th>
                                <th style="width: 20%;">VALOR A COBRAR</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="item in listItemsCobrar track by $index" ng-cloak>

                                <td class="text-left">{{item.nombreproducto}}</td>
                                <td class="text-right">$ {{item.valor}}</td>
                                <td>
                                    <input type="text" class="form-control text-right" ng-model="item.acobrar"
                                           ng-keypress="onlyNumber($event, undefined, undefined)"
                                            ng-blur="verifiedCobro(item)">
                                </td>

                            </tr>
                        </tbody>
                    </table>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        Cancelar <span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span>
                    </button>
                    <button type="button" class="btn btn-primary" id="btn-ok" ng-click="createFacturaItems()">
                        Aceptar <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" id="listCuentasCerrar" style="">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header modal-header-primary">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Listado de Cuentas a Cerrar </h4>
                </div>
                <div class="modal-body">
                    <div class="row">

                        <div class="col-xs-6">

                            <table class="table table-responsive table-striped table-hover table-condensed table-bordered">
                                <thead class="bg-primary">
                                    <tr>
                                        <th style="width: 15%;">CODIGO</th>
                                        <th>CONCEPTO</th>
                                        <th style="width: 20%;">TOTAL</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="item in listCuentas" ng-cloak">

                                        <td class="text-center">{{item.jerarquia}}</td>
                                        <td>{{item.concepto}}</td>
                                        <td class="text-right">$ {{item.valor}}</td>

                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="2" class="text-right">
                                            TOTAL
                                        </th>
                                        <th class="text-right">$ {{totalacerrar}}</th>
                                    </tr>
                                </tfoot>
                            </table>

                        </div>

                        <div class="col-xs-6">
                            <div class="col-xs-12">
                                <button ng-disabled="EstadoSave=='M'"  ng-click="AddIntemCotable()" class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i></button>
                            </div>

                            <div class="col-xs-12">
                                <table class="table table-bordered table-condensed">
                                    <thead>
                                    <tr class="bg-primary">
                                        <th></th>
                                        <th></th>
                                        <th>CUENTA</th>
                                        <th>VALOR</th>
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
                                    </tr>
                                    </tbody>

                                </table>
                            </div>

                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        Cancelar <span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span>
                    </button>
                    <button type="button" class="btn btn-primary" id="btn-ok" ng-click="ProcesarDatosAsientoContable()">
                        Aceptar <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" id="modalRegistroCobros">
        <div class="modal-dialog" role="document"  style="">
            <div class="modal-content" style="height: 90%;">
                <div class="modal-header modal-header-primary">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Registro</h4>
                </div>
                <div class="modal-body">

                    <table class="table table-responsive table-striped table-hover table-bordered">
                        <thead class="bg-primary">
                            <tr>
                                <th>FACTURA</th>
                                <th style="width: 20%;">FECHA</th>
                                <th style="width: 20%;">VALOR</th>
                                <th style="width: 10%;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="e in listRegistro track by $index" ng-cloak>

                                <td class="text-left"></td>
                                <td class="text-center"></td>
                                <td class="text-right">$ {{e.valor}}</td>
                                <td>
                                    <button type="button" class="btn btn-info" ng-click="" >
                                        <span class="glyphicon glyphicon-print" aria-hidden="true"></span>
                                    </button>
                                </td>

                            </tr>
                        </tbody>
                    </table>

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


</div>


