

<div ng-controller="facturaController">

    <div class="col-xs-12">

        <h4>Gestión de Cobros y Servicios</h4>

        <hr>

    </div>

    <div class="col-xs-12" style="margin-top: 5px;">

        <div class="col-sm-4 col-xs-12">
            <div class="form-group has-feedback">
                <input type="text" class="form-control" id="t_busqueda" placeholder="BUSCAR..."
                       ng-model="t_busqueda" ng-keypress="initLoad(1)">
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

    <div class="col-xs-12">
        <table class="auto table table-responsive table-striped table-hover table-condensed table-bordered ">
            <thead class="bg-primary">
            <tr>
                <th style="width: 5%;">No. Factura</th>
                <th style="width: 8%;">Fecha</th>
                <th style="width: 10%;">Periodo</th>
                <th>Cliente</th>
                <th>Servicios</th>
                <th style="width: 5%;">Suministro</th>
                <th style="width: 8%;">Tarifa</th>
                <th style="width: 12%;">Dirección Suministro</th>
                <th style="width: 5%;">Teléfono Suministro</th>
                <th style="width: 5%;">Consumo (m3)</th>
                <th style="width: 5%;">Estado</th>
                <th style="width: 5%;">Total</th>
                <th style="width: 4%;">Acc.</th>
            </tr>
            </thead>
            <tbody>
            <tr dir-paginate="item in factura | orderBy:sortKey:reverse | itemsPerPage:5 " total-items="totalItems" ng-cloak>
                <td>{{item.idcobroagua}}</td>
                <td>{{ FormatoFecha(item.fechacobro)}}</td>
                <td>{{yearmonth (item.fechacobro)}}</td>
                <td>{{item.suministro.cliente.persona.razonsocial}}</td>
                <td>
                    <span ng-repeat="serviciosenfactura in item.serviciosenfactura">{{serviciosenfactura.serviciojunta.nombreservicio}}</span>
                </td>
                <td>{{item.suministro.idsuministro}}</td>
                <td>{{item.suministro.tarifaaguapotable.nametarifaaguapotable}}</td>
                <td>{{item.suministro.direccionsumnistro}}</td>
                <td>{{item.suministro.telefonosuministro}}</td>
                <td>{{item.lectura.consumo}}</td>
                <td>{{Pagada(item.estadopagado)}}</td>
                <td>{{item.totalfactura}}</td>
                <td>
                    <span ng-if="item.estadopagado == true">
                        <button type="button" class="btn btn-success btn-sm" ng-click="printer(item)">
                            <i class="fa fa-lg fa-print" aria-hidden="true"></i>
                        </button>
                    </span>
                    <span ng-if="item.estadopagado == false">
                        <button type="button" class="btn btn-success btn-sm" ng-click="printer(item)" disabled>
                            <i class="fa fa-lg fa-print" aria-hidden="true"></i>
                        </button>
                    </span>
                    <span ng-if="item.totalfactura == null">
                        <span ng-if="item.cobroagua == null">
                            <button type="button" class="btn btn-info btn-sm" ng-click="ShowModalFactura(item)" >
                            <i class="fa fa-lg fa-eye" aria-hidden="true"></i>
                        </button>
                        </span>
                        <span ng-if="item.cobroagua != null">
                            <button type="button" class="btn btn-info btn-sm" ng-click="ShowModalFactura(item)" disabled>
                                <i class="fa fa-lg fa-eye" aria-hidden="true"></i>
                            </button>
                        </span>
                    </span>
                    <span ng-if="item.totalfactura != null">
                        <button type="button" class="btn btn-info btn-sm" ng-click="ShowModalFactura(item)">
                            <i class="fa fa-lg fa-eye" aria-hidden="true"></i>
                        </button>
                    </span>

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

                <div class="modal-footer" id="footer-modal-factura">
                    <button type="button" class="btn btn-primary" id="btn-save"
                            ng-click="save()" ng-disabled="formProcess.$invalid">
                        Guardar <span class="glyphicon glyphicon-floppy-saved" aria-hidden="true"></span>
                    </button>
                    <button type="button" class="btn btn-primary" id="btn-pagar" ng-click="pagar()" >
                        Pagar <span class="glyphicon glyphicon-usd" aria-hidden="true"></span>
                    </button>
                </div>
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


</div>


