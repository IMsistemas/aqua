

<div ng-controller="reporteVentaBalanceController">

    <div class="col-xs-12">

        <h4>Reporte de Ventas / Balance</h4>

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

        <!--<div class="col-sm-2 col-xs-2">
            <button type="button" class="btn btn-info" ng-click="printReport();">
                Imprimir <span class="glyphicon glyphicon glyphicon-print" aria-hidden="true"></span>
            </button>
        </div>-->


        <div class="col-xs-12" style="font-size: 12px !important;">

            <table class="table table-responsive table-striped table-hover table-condensed table-bordered">
                <thead class="bg-primary">
                    <tr>
                        <th style="width: 4%;">NO.</th>
                        <th style="width: 8%;">JERARQUIA</th>
                        <th >CONCEPTO</th>
                        <th style="width: 11%;">FACTURA VENTA</th>
                    </tr>
                </thead>
                <tbody>
                    <tr ng-repeat="item in list | filter : busqueda" ng-cloak>

                        <td>{{$index + 1}}</td>
                        <td>{{item.jerarquia}}</td>
                        <td>{{item.concepto}}</td>
                        <td class="text-right">$ {{item.totalfactura}}</td>

                    </tr>
                </tbody>
                <tfoot class="bg-primary">
                    <tr>
                        <th colspan="3" class="text-right">TOTALES</th>
                        <th class="text-right btn-success" style="font-weight: bold;">$ {{total}}</th>
                    </tr>
                </tfoot>
            </table>

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

