
<div ng-controller="reporteCCController">

    <div class="col-xs-12">

        <h4>Reporte de Centro de Costo</h4>

        <hr>

    </div>

    <div class="col-xs-12" style="margin-top: 5px;">

        <div class="col-sm-2 col-xs-5">
            <div class="form-group has-feedback">
                <input type="text" class="form-control" id="busqueda" placeholder="BUSCAR..." ng-model="busqueda" ng-keyup="initLoad()">
                <span class="glyphicon glyphicon-search form-control-feedback" aria-hidden="true"></span>
            </div>
        </div>

        <div class="col-sm-2 col-xs-2">
            <select class="form-control" name="tipo" id="tipo" ng-model="tipo" ng-change="initLoad()">
                <option value="">-- Seleccione Tipo --</option>
                <option value="G">Gastos</option>
                <option value="I">Ingresos</option>
            </select>
        </div>

        <div class="col-sm-3 col-xs-3">
            <select class="form-control" name="searchCC" id="searchCC" ng-model="searchCC"
                    ng-options="value.id as value.label for value in search_cc" ng-change="initLoad()"></select>
        </div>

        <div class="col-sm-2 col-xs-2">
            <div class="input-group">
                <span class="input-group-addon">Fecha Inicio:</span>
                <input type="text" class="datepicker form-control" name="fechainicio" id="fechainicio" ng-model="fechainicio" ng-blur="initLoad()">
            </div>
        </div>

        <div class="col-sm-2 col-xs-2">
            <div class="input-group">
                <span class="input-group-addon">Fecha Fin:</span>
                <input type="text" class="datepicker form-control" name="fechafin" id="fechafin" ng-model="fechafin" ng-blur="initLoad()">
            </div>
        </div>

        <div class="col-sm-1 col-xs-1">
            <button type="button" class="btn btn-info" ng-click="printReport();">
                <span class="glyphicon glyphicon glyphicon-print" aria-hidden="true"></span>
            </button>
        </div>

        <div class="row" ng-show="reportegasto">
            <div class="col-xs-12">

                <h5>Gastos</h5>

                <hr>

            </div>

            <div class="col-xs-12" style="margin-top: 5px; font-size: 12px !important;" ng-repeat="element in centrocostogasto">

                <table class="table table-responsive table-striped table-hover table-condensed table-bordered">
                    <thead class="bg-primary">
                    <tr><th colspan="7">CENTRO DE COSTO: {{element.namedepartamento}}</th></tr>
                    <tr>
                        <th style="width: 5%;">NO.</th>
                        <th style="width: 12%;">DOCUMENTO</th>
                        <th style="width: 10%;">FECHA</th>
                        <th>ITEM (DETALLE)</th>
                        <th style="width: 6%;">CANTIDAD</th>
                        <th style="width: 10%;">PRECIO UNIT.</th>
                        <th style="width: 10%;">TOTAL</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr ng-repeat="item in element.costos | filter : busqueda" ng-cloak>

                        <td>{{$index + 1}}</td>
                        <td class="text-center">{{item.numdocumentocompra}}</td>
                        <td class="text-center">{{item.fecharegistrocompra}}</td>
                        <td class="text-left">{{item.detalle_item}}</td>
                        <td class="text-right">{{item.cantidad}}</td>
                        <td class="text-right">$ {{item.preciounitario}}</td>
                        <td class="text-right">$ {{item.preciototal}}</td>

                    </tr>
                    </tbody>
                    <tfoot class="bg-primary">
                    <tr>
                        <th colspan="6" class="text-right">TOTALES</th>
                        <th class="text-right btn-danger" style="font-weight: bold;">$ {{element.total}}</th>
                    </tr>
                    </tfoot>
                </table>

            </div>
        </div>


        <div class="row" ng-show="reporteingreso">
            <div class="col-xs-12">

                <h5>Ingresos</h5>

                <hr>

            </div>

            <div class="col-xs-12" style="margin-top: 5px; font-size: 12px !important;" ng-repeat="element in centrocostoingreso">

                <table class="table table-responsive table-striped table-hover table-condensed table-bordered">
                    <thead class="bg-primary">
                    <tr><th colspan="7">CENTRO DE COSTO: {{element.namedepartamento}}</th></tr>
                    <tr>
                        <th style="width: 5%;">NO.</th>
                        <th style="width: 12%;">DOCUMENTO</th>
                        <th style="width: 10%;">FECHA</th>
                        <th>ITEM (DETALLE)</th>
                        <th style="width: 6%;">CANTIDAD</th>
                        <th style="width: 10%;">PRECIO UNIT.</th>
                        <th style="width: 10%;">TOTAL</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr ng-repeat="item in element.costos | filter : busqueda" ng-cloak>

                        <td>{{$index + 1}}</td>
                        <td class="text-center">{{item.numdocumentocompra}}</td>
                        <td class="text-center">{{item.fecharegistrocompra}}</td>
                        <td class="text-left">{{item.detalle_item}}</td>
                        <td class="text-right">{{item.cantidad}}</td>
                        <td class="text-right">$ {{item.preciounitario}}</td>
                        <td class="text-right">$ {{item.preciototal}}</td>

                    </tr>
                    </tbody>
                    <tfoot class="bg-primary">
                    <tr>
                        <th colspan="6" class="text-right">TOTALES</th>
                        <th class="text-right btn-success" style="font-weight: bold;">$ {{element.total}}</th>
                    </tr>
                    </tfoot>
                </table>

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



