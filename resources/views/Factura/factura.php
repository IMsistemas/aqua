

<div ng-controller="facturaController">

    <div class="col-xs-12">

        <h4>Gestión de Cobros de Agua (Lecturas)</h4>

        <hr>

    </div>

    <div class="col-xs-12" style="margin-top: 5px;">

        <div class="col-sm-4 col-xs-12">
            <div class="form-group has-feedback">
                <input type="text" class="form-control" id="t_busqueda" placeholder="BUSCAR..."
                       ng-model="t_busqueda" ng-keyup="initLoad(1)">
                <span class="glyphicon glyphicon-search form-control-feedback" aria-hidden="true"></span>
            </div>
        </div>


        <div class="col-sm-2 col-xs-12">

            <input type="text" class="form-control datepicker_a" name="t_anio"
                   id="t_anio" ng-model="t_anio"  ng-change="Filtrar()" placeholder="-- Año --">
        </div>

        <div class="col-sm-2 col-xs-12">
            <select id="s_mes" name="s_mes" class="form-control" ng-model="s_mes" ng-change="initLoad(1)"
                    ng-options="value.id as value.label for value in meses"></select>
        </div>

        <div class="col-sm-2 col-xs-12">
            <select id="s_estado" class="form-control" ng-model="s_estado" ng-change="initLoad(1)"
                    ng-options="value.id as value.label for value in estadoss"></select>
        </div>

        <div class="col-sm-2 col-xs-12">
            <button type="button" class="btn btn-primary" id="btn-generate"  style="float: right;" ng-click="generate()" disabled="true">
                Generar <i class="glyphicon glyphicon-refresh" aria-hidden="true"></i>
            </button>
        </div>

    </div>

    <div class="col-xs-12" style="font-size: 12px !important;">
        <table class="auto table table-responsive table-striped table-hover table-condensed table-bordered ">
            <thead class="bg-primary">
            <tr>
                <th style="width: 4%;">NO.</th>
                <th style="width: 7%;">FECHA</th>
                <th style="width: 7%;">PERIODO</th>
                <th>CLIENTE</th>
                <th style="width: 8%;">NO. SUMINIST.</th>
                <th style="width: 8%;">TARIFA</th>
                <th style="width: 12%;">DIRECC. SUMINIST.</th>
                <th style="width: 5%;">TELF. SUMINIST.</th>
                <th style="width: 5%;">CONSUMO(m3)</th>
                <th style="width: 5%;">ESTADO</th>
                <th style="width: 5%;">TOTAL</th>
                <th style="width: 10%;">ACCIONES</th>
            </tr>
            </thead>
            <tbody>
            <tr dir-paginate="item in factura | orderBy:sortKey:reverse | itemsPerPage:5 | filter:t_busqueda" total-items="totalItems" ng-cloak>
                <td>{{$index + 1}}</td>
                <td>{{ FormatoFecha(item.fechacobro)}}</td>
                <td>{{yearmonth (item.fechacobro)}}</td>
                <td>{{item.suministro.cliente.persona.razonsocial}}</td>
                <td>{{item.suministro.idsuministro}}</td>
                <td>{{item.suministro.tarifaaguapotable.nametarifaaguapotable}}</td>
                <td>{{item.suministro.direccionsumnistro}}</td>
                <td>{{item.suministro.telefonosuministro}}</td>
                <td>{{item.lectura.consumo}}</td>
                <td>{{Pagada(item.estadopagado)}}</td>
                <td class="text-right">$ {{item.total}}</td>
                <td>
                    <button type="button" class="btn btn-success btn-sm" ng-click="printer(item)"  title="Imprimir">
                        <i class="fa fa-lg fa-print" aria-hidden="true"></i>
                    </button>
                    <!--<span ng-if="item.estadopagado == true">
                        <button type="button" class="btn btn-success btn-sm" ng-click="printer(item)">
                            <i class="fa fa-lg fa-print" aria-hidden="true"></i>
                        </button>
                    </span>
                    <span ng-if="item.estadopagado == false">
                        <button type="button" class="btn btn-success btn-sm" ng-click="printer(item)" disabled>
                            <i class="fa fa-lg fa-print" aria-hidden="true"></i>
                        </button>
                    </span>-->
                    <span ng-if="item.totalfactura == null">
                        <span ng-if="item.cobroagua == null">
                            <button type="button" class="btn btn-info btn-sm" ng-click="ShowModalFactura(item)" title="Información">
                            <i class="fa fa-lg fa-eye" aria-hidden="true"></i>
                        </button>
                        </span>
                        <span ng-if="item.cobroagua != null">
                            <button type="button" class="btn btn-info btn-sm" ng-click="ShowModalFactura(item)" disabled title="Información">
                                <i class="fa fa-lg fa-eye" aria-hidden="true"></i>
                            </button>
                        </span>
                    </span>
                    <span ng-if="item.totalfactura != null">
                        <button type="button" class="btn btn-info btn-sm" ng-click="ShowModalFactura(item)" title="Información">
                            <i class="fa fa-lg fa-eye" aria-hidden="true"></i>
                        </button>
                    </span>
                    <button type="button" class="btn btn-primary btn-sm" ng-click="showModalListCobro(item)" title="Cobros" >
                         <span class="glyphicon glyphicon-usd" aria-hidden="true"></span>
                    </button>
                </td>
            </tr>
            </tbody>
        </table>
        <dir-pagination-controls

                on-page-change="pageChanged(newPageNumber)"

                template-url="dirPagination.html"

                class="pull-right"
                max-size="5"
                direction-links="true"
                boundary-links="true" >

        </dir-pagination-controls>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" id="modalFactura">
        <div class="modal-dialog" role="document" style="width: 50%;">
            <div class="modal-content">
                <div class="modal-header modal-header-primary">
                    <div class="col-md-11 col-xs-12">
                        <h4 class="modal-title">Factura: {{num_factura}} </h4>
                    </div>
                            <div class="col-sm-1 col-xs-12 text-right">
                                <div class="col-xs-2"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>
                            </div>
                </div>

                <div class="modal-body">
                    <form class="form-horizontal" name="formProcess" novalidate="">

                        <div class="row">
                            <div class="col-xs-12" style="padding: 2%; margin-top: -20px !important;">
                                <fieldset ng-cloak>
                                    <legend style="font-size: 16px; font-weight: bold;">Datos del Cliente</legend>

                                    <div class="col-xs-12" style="padding: 0;">
                                        <div class="col-sm-6 col-xs-12">
                                            <div class="input-group">
                                                <span class="input-group-addon">RUC/CI: </span>
                                                <input type="text" class="form-control" name="documentoidentidad_cliente" ng-model="documentoidentidad_cliente" readonly/>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-xs-12">
                                            <div class="input-group">
                                                <span class="input-group-addon">Cliente: </span>
                                                <input type="text" class="form-control" name="nom_cliente" ng-model="nom_cliente" readonly/>
                                            </div>
                                            <input type="hidden" ng-model="h_codigocliente">
                                        </div>
                                    </div>

                                    <div class="col-xs-12" style="padding: 0; margin-top: 5px;">

                                        <div class="col-sm-6 col-xs-12">
                                            <div class="input-group">
                                                <span class="input-group-addon">Dirección Domicilio: </span>
                                                <input type="text" class="form-control" name="direcc_cliente" ng-model="direcc_cliente" readonly/>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-xs-12">
                                            <div class="input-group">
                                                <span class="input-group-addon">Teléf. Celular: </span>
                                                <input type="text" class="form-control" name="telf_cliente" ng-model="telf_cliente" readonly/>
                                            </div>
                                        </div>
                                    </div>

                                   </fieldset>
                            </div>

                            <div class="col-xs-12" style="padding: 0% 2% 0% 2%;">
                                <fieldset style="">
                                    <legend style="font-size: 16px; font-weight: bold;">Detalle</legend>

                                    <div class="col-xs-12">
                                        <table class="table table-responsive table-striped table-hover table-condensed table-bordered">
                                            <thead class="bg-primary">
                                            <tr>
                                                <th style="width: 70%;">Descripción</th>
                                                <th>Valor</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tbody>
                                            <tr ng-repeat="item in aux_modal" ng-cloak >
                                                <td>{{item.nombre}}</td>
                                                <td ng-if="item.id == 0">
                                                    <input type="text" class="form-control" ng-model="item.valor" style="text-align: right !important;" disabled>
                                                </td>
                                                <td ng-if="item.id != 0">
                                                    <input type="text" class="form-control" style="text-align: right !important;" ng-model="item.valor" ng-keypress="onlyDecimal($event)" ng-blur="reCalculateTotal()">
                                                </td>
                                            </tr>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th class="text-right">TOTAL:</th>
                                                    <th style="text-align: right;"> {{total}}</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </fieldset>
                            </div>

                        </div>

                    </form>
                </div>

                <!--<div class="modal-footer" id="footer-modal-factura">
                    <button type="button" class="btn btn-primary" id="btn-save"
                            ng-click="save()" ng-disabled="formProcess.$invalid">
                        Guardar <span class="glyphicon glyphicon-floppy-saved" aria-hidden="true"></span>
                    </button>
                    <button type="button" class="btn btn-primary" id="btn-pagar" ng-click="showModalListCobro()" >
                        Cobrar <span class="glyphicon glyphicon-usd" aria-hidden="true"></span>
                    </button>
                </div>-->
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" id="modalMessage" style="z-index: 99999;">
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


    <!---------------------------- FORMULARIOS DE CUENTAS POR COBRAR ---------------------------------------------->

    <div class="modal fade" tabindex="-1" role="dialog" id="listCobros">
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
                                    <th style="width: 5%;">ACCION</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr ng-repeat="item in listcobro" ng-cloak">

                                <td>{{item.nocomprobante}}</td>
                                <td class="text-center">{{item.fecharegistro}}</td>
                                <td>{{item.nameformapago}}</td>
                                <td class="text-right">$ {{item.valorpagado}}</td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-delete" ng-click="" title="Anular">
                                                <span class="glyphicon glyphicon-ban-circle" aria-hidden="true">
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

                    <div class="col-sm-6 col-xs-12" style="margin-top: 5px;">
                        <div class="input-group">
                            <span class="input-group-addon">No. Comprobante: </span>
                            <input type="text" class="form-control" id="nocomprobante" ng-model="nocomprobante" >
                        </div>
                    </div>

                    <div class="col-sm-6 col-xs-12" style="margin-top: 5px;">
                        <div class="input-group">
                            <span class="input-group-addon">Fecha Cobro: </span>
                            <input type="text" class="form-control datepicker" id="fecharegistro" ng-model="fecharegistro" >
                        </div>
                    </div>

                    <div class="col-xs-12" style="margin-top: 5px;">
                        <div class="input-group">
                            <span class="input-group-addon">Forma Pago: </span>
                            <select class="form-control" name="formapago" id="formapago" ng-model="formapago" ng-required="true"
                                    ng-options="value.id as value.label for value in listformapago">
                            </select>
                        </div>
                        <span class="help-block error"
                              ng-show="formCompra.formapago.$invalid && formCompra.formapago.$touched">La Forma Pago es requerida</span>
                    </div>

                    <div class="col-sm-6 col-xs-12" style="margin-top: 5px;">
                        <div class="input-group">
                            <span class="input-group-addon">A Cobrar: </span>
                            <input type="text" class="form-control" id="valorpendiente" ng-model="valorpendiente" disabled>
                        </div>
                    </div>

                    <div class="col-sm-6 col-xs-12" style="margin-top: 5px;">
                        <div class="input-group">
                            <span class="input-group-addon">Cobrado: </span>
                            <input type="text" class="form-control" id="valorrecibido" ng-model="valorrecibido" >
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
                              ng-show="formEmployee.cuenta_employee.$error.required">La asignación de una cuenta es requerida</span>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        Cancelar <span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span>
                    </button>
                    <button type="button" class="btn btn-primary" id="btn-ok" ng-click="saveCobro()">
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

</div>


