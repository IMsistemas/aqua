<!DOCTYPE html>
<html lang="es-ES" ng-app="softver-aqua">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <title>Retencion Compras</title>

        <link href="<?= asset('css/bootstrap.min.css') ?>" rel="stylesheet">
        <link href="<?= asset('css/font-awesome.min.css') ?>" rel="stylesheet">
        <link href="<?= asset('css/index.css') ?>" rel="stylesheet">
        <link href="<?= asset('css/bootstrap-datetimepicker.min.css') ?>" rel="stylesheet">
        <link href="<?= asset('css/style_generic_app.css') ?>" rel="stylesheet">
        <link href="<?= asset('css/angucomplete-alt.css') ?>" rel="stylesheet">

        <style>
            .dataclient{
                font-weight: bold;
            }
        </style>
    </head>

    <body>

        <div ng-controller="retencionComprasController">

            <div class="col-xs-12" style="margin-top: 15px;">
                <div class="col-sm-3 col-xs-12">
                    <div class="form-group has-feedback">
                        <input type="text" class="form-control" id="t_busqueda" placeholder="BUSCAR..." ng-model="t_busqueda">
                        <span class="glyphicon glyphicon-search form-control-feedback" aria-hidden="true"></span>
                    </div>
                </div>
                <div class="col-sm-2 col-xs-12">
                    <select class="form-control" name="s_tiporetencion" id="s_tiporetencion"
                            ng-model="s_tiporetencion" ng-options="value.id as value.name for value in tiporetencion" ng-change="">
                    </select>
                </div>
                <div class="col-sm-2 col-xs-12">
                    <select class="form-control" name="s_codigoretencion" id="s_codigoretencion"
                            ng-model="s_codigoretencion" ng-options="value.id as value.name for value in tipo" ng-change="">
                    </select>
                </div>
                <div class="col-sm-2 col-xs-12">
                    <select class="form-control" name="s_year" id="s_year"
                            ng-model="s_year" ng-options="value.id as value.name for value in tipo" ng-change="">
                    </select>
                </div>
                <div class="col-sm-2 col-xs-12">
                    <select class="form-control" name="s_month" id="s_month"
                            ng-model="s_month" ng-options="value.id as value.name for value in tipo" ng-change="">
                    </select>
                </div>
                <div class="col-sm-1 col-xs-12">
                    <button type="button" class="btn btn-primary" style="float: right;" ng-click="loadFormPage()">
                        <i class="fa fa-lg fa-plus" aria-hidden="true"></i>
                    </button>
                </div>
            </div>

            <div class="col-xs-12">
                <table class="table table-responsive table-striped table-hover table-condensed">
                    <thead class="bg-primary">
                        <tr>
                            <th class="text-center" style="width: 10%;" ng-click="sort('')">
                                Nro. Retención
                                <span class="glyphicon sort-icon" ng-show="sortKey==''"
                                      ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                            </th>
                            <th class="text-center" style="width: 10%;" ng-click="sort('')">
                                Fecha Ingreso
                                <span class="glyphicon sort-icon" ng-show="sortKey==''"
                                      ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                            </th>
                            <th class="text-center" ng-click="sort('')">
                                Proveedor
                                <span class="glyphicon sort-icon" ng-show="sortKey==''"
                                      ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                            </th>
                            <th class="text-center" style="width: 8%;" ng-click="sort('')">
                                Tipo
                                <span class="glyphicon sort-icon" ng-show="sortKey==''"
                                      ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                            </th>
                            <th class="text-center" style="width: 10%;" ng-click="sort('')">
                                Nro. Documento
                                <span class="glyphicon sort-icon" ng-show="sortKey==''"
                                      ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                            </th>
                            <th class="text-center" style="width: 9%;" ng-click="sort('')">
                                Valor
                                <span class="glyphicon sort-icon" ng-show="sortKey==''"
                                      ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                            </th>
                            <th class="text-center" style="width: 16%;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr dir-paginate="item in retencion | orderBy:sortKey:reverse | itemsPerPage:10 " total-items="totalItems" ng-cloak>
                            <td>{{item.documentoidentidad}}</td>
                            <td>{{item.fechaingreso | formatDate}}</td>
                            <td style="font-weight: bold;"><i class="fa fa-user fa-lg" aria-hidden="true"></i> {{item.complete_name}}</td>
                            <td>{{item.celular}}</td>
                            <td>{{item.telefonoprincipaldomicilio}}</td>
                            <td>{{item.telefonoprincipaltrabajo}}</td>
                            <td  class="text-center">
                                <button type="button" class="btn btn-info btn-sm" ng-click="showModalInfoCliente(item)">
                                    <i class="fa fa-lg fa-info-circle" aria-hidden="true"></i>
                                </button>
                                <button type="button" class="btn btn-warning btn-sm" ng-click="edit(item)">
                                    <i class="fa fa-lg fa-pencil-square-o" aria-hidden="true"></i>
                                </button>
                                <button type="button" class="btn btn-danger btn-sm" ng-click="showModalDeleteCliente(item)">
                                    <i class="fa fa-lg fa-trash" aria-hidden="true"></i>
                                </button>
                                <button type="button" class="btn btn-primary btn-sm" ng-click="showModalAction(item)">
                                    <i class="fa fa-lg fa-cogs" aria-hidden="true"></i>
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

        </div>

    </body>

    <script src="<?= asset('app/lib/angular/angular.min.js') ?>"></script>
    <script src="<?= asset('app/lib/angular/angular-route.min.js') ?>"></script>
    <script src="<?= asset('app/lib/angular/dirPagination.js') ?>"></script>
    <script src="<?= asset('js/jquery.min.js') ?>"></script>
    <script src="<?= asset('js/bootstrap.min.js') ?>"></script>
    <script src="<?= asset('js/menuLateral.js') ?>"></script>
    <script src="<?= asset('js/moment.min.js') ?>"></script>
    <script src="<?= asset('js/es.js') ?>"></script>
    <script src="<?= asset('js/bootstrap-datetimepicker.min.js') ?>"></script>

    <script src="<?= asset('app/lib/angular/ng-file-upload-shim.min.js') ?>"></script>
    <script src="<?= asset('app/lib/angular/ng-file-upload.min.js') ?>"></script>

    <script src="<?= asset('app/lib/angular/angucomplete-alt.min.js') ?>"></script>
    <script src="<?= asset('app/app.js') ?>"></script>

    <script src="<?= asset('app/controllers/retencionCompra.js') ?>"></script>
</html>

