

<div ng-controller="recaudacionCController" ng-init="initLoad(1)">

    <div class="col-xs-12">

        <h4>Recaudación (Cobros)</h4>

        <hr>

    </div>

    <div class="col-xs-12" style="margin-top: 5px;">
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

            <div class="btn-group" role="group" aria-label="...">
                <button type="button" class="btn btn-primary" style="" ng-click="showModalAddCliente()">
                    Agregar <span class="glyphicon glyphicon glyphicon-plus" aria-hidden="true"></span>
                </button>
                <button type="button" class="btn btn-info" ng-click="printReport();">
                    Imprimir <span class="glyphicon glyphicon glyphicon-print" aria-hidden="true"></span>
                </button>
            </div>

        </div>
    </div>

    <div class="col-xs-12" style="font-size: 12px !important;">
        <table class="table table-responsive table-striped table-hover table-condensed table-bordered">
            <thead class="bg-primary">
            <tr>
                <th style="width: 5%;">NO</th>
                <th style="width: 10%;">CI / RUC</th>
                <th style="width: 10%;">FECHA INGRESO</th>
                <th style="">RAZON SOCIAL / APELLIDOS Y NOMBRE</th>
                <th style="width: 5%;">ACCION</th>
            </tr>
            </thead>
            <tbody>
            <tr dir-paginate="item in clientes|orderBy:sortKey:reverse| itemsPerPage:8" total-items="totalItems" ng-cloak >
                <td>{{$index + 1}}</td>
                <td>{{item.numdocidentific}}</td>
                <td>{{item.fechaingreso | formatDate}}</td>
                <td>{{item.lastnamepersona}} {{item.namepersona}}</td>
                <td  class="text-center">

                    <button type="button" class="btn btn-primary btn-sm" ng-click="getTransacciones(item.idcliente)">
                        <i class="fa fa-lg fa-cogs" aria-hidden="true" title=""></i>
                    </button>

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

    <div class="modal fade" tabindex="-1" role="dialog" id="listCobros">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header modal-header-primary">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Listado de Transacciones a Cobrar </h4>
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
                                        <th style="width: 4%;"></th>
                                        <th style="width: 10%;">FECHA</th>
                                        <th>TIPO</th>
                                        <th style="width: 11%;">TOTAL</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="item in listTransacciones" ng-cloak">

                                        <td></td>
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

</div>


