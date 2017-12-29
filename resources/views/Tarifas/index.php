

<div class="container" ng-controller="tarifaController">

    <div class="col-xs-12">

        <h4>Gestión de Tarifas</h4>

        <hr>

    </div>

    <div class="col-xs-12" style="margin-top: 5px;">

        <div class="col-sm-6 col-xs-8">
            <div class="form-group has-feedback">
                <input type="text" class="form-control" id="busqueda" placeholder="BUSCAR..." ng-model="busqueda" ng-keyup="initLoad(1)">
                <span class="glyphicon glyphicon-search form-control-feedback" aria-hidden="true"></span>
            </div>
        </div>

        <div class="col-sm-6 col-xs-4">
            <button type="button" class="btn btn-primary" style="float: right;" ng-click="toggle('add', 0)">
                Agregar <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
            </button>
        </div>

        <div class="col-xs-12">
            <table class="table table-responsive table-striped table-hover table-condensed table-bordered">
                <thead class="bg-primary">
                <tr>
                    <th>NOMBRE TARIFA</th>
                    <th style="width: 35%;">ACCIONES</th>
                </tr>
                </thead>
                <tbody>
                <tr dir-paginate="item in tarifas | orderBy:sortKey:reverse | itemsPerPage:10" total-items="totalItems" ng-cloak">
                    <td>{{item.nametarifaaguapotable}}</td>
                    <td class="text-center">

                        <div class="btn-group" role="group" aria-label="...">
                            <button type="button" class="btn btn-primary" ng-click="toggle('action', item.idtarifaaguapotable)">
                                Parametros <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
                            </button>
                            <button type="button" class="btn btn-warning" ng-click="toggle('edit', item.idtarifaaguapotable)">
                                Editar <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                            </button>
                            <button type="button" class="btn btn-danger" ng-click="showModalConfirm(item)">
                                Eliminar <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
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
                max-size="10"
                direction-links="true"
                boundary-links="true" >

            </dir-pagination-controls>

        </div>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" id="modalActionCargo">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header modal-header-primary">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">{{form_title}}</h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" name="formCargo" novalidate="">
                        <div class="row">

                            <div class="col-xs-12 error">
                                <div class="input-group">
                                    <span class="input-group-addon">Tarifa: </span>
                                    <input type="text" class="form-control" name="nombretarifa" id="nombretarifa" ng-model="nombretarifa" placeholder=""
                                           ng-required="true" ng-maxlength="150">
                                </div>
                                <span class="help-block error"
                                      ng-show="formCargo.nombretarifa.$invalid && formCargo.nombretarifa.$touched">La Tarifa es requerida</span>
                                <span class="help-block error"
                                      ng-show="formCargo.nombrecargo.$invalid && formCargo.nombrecargo.$error.maxlength">La longitud máxima es de 150 caracteres</span>
                            </div>

                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        Cancelar <span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span>
                    </button>
                    <button type="button" class="btn btn-success" id="btn-save" ng-click="Save()" ng-disabled="formCargo.$invalid">
                        Guardar <span class="glyphicon glyphicon-floppy-saved" aria-hidden="true"></span>
                    </button>
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

    <div class="modal fade" tabindex="-1" role="dialog" id="modalConfirmDelete">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header modal-header-danger">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Confirmación</h4>
                </div>
                <div class="modal-body">
                    <span>Realmente desea eliminar la Tarifa: <span style="font-weight: bold;">{{cargo_seleccionado}}</span></span>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        Cancelar <span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span>
                    </button>
                    <button type="button" class="btn btn-danger" id="btn-save" ng-click="delete()">
                        Eliminar <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" id="modalAction">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <div class="modal-header modal-header-primary">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Confirmación</h4>
                </div>

                <form class="form-horizontal" name="formTarifa" novalidate="">

                    <div class="modal-body">

                        <div class="row">

                            <div id="dvTab" style="margin-top: 5px;">
                                <ul class="nav nav-tabs" role="tablist">
                                    <li role="presentation" class="active tabs"><a href="#basica" aria-controls="basica" role="tab" data-toggle="tab"> Básica</a></li>
                                    <li role="presentation" class="tabs"><a href="#excedente" aria-controls="excedente" role="tab" data-toggle="tab"> Excedente</a></li>
                                    <li role="presentation" class="tabs"><a href="#otros" aria-controls="otros" role="tab" data-toggle="tab"> Otros</a></li>
                                </ul>
                                <div class="tab-content">
                                    <div role="tabpanel" class="tab-pane fade active in" id="basica" style="padding-top: 3px;">

                                        <div class="col-xs-12" style="margin-top: 5px;">
                                            <button type="button" class="btn btn-primary" id="btnAgregar" style="float: right;" ng-click="createRowBasica()">
                                                Agregar <span class="glyphicon glyphicon-plus" aria-hidden="true">
                                            </button>
                                        </div>

                                        <div class="col-xs-12" style="font-size: 12px !important; margin-top: 5px;">

                                            <table class="table table-responsive table-striped table-hover table-condensed table-bordered">
                                                <thead class="bg-primary">
                                                <tr>
                                                    <th>DESDE</th>
                                                    <th>VALOR</th>
                                                    <th style="width: 5%;"></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                    <tr ng-repeat="elem_b in listbasica" ng-cloak >

                                                        <td>
                                                            <input type="text" class="form-control" ng-model="elem_b.apartirdenm3" ng-keypress="onlyDecimal($event)" />
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" ng-model="elem_b.valortarifa" ng-keypress="onlyDecimal($event)" />
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-danger" ng-click="deleteRowBasica(elem_b)"
                                                                    data-toggle="tooltip" data-placement="bottom" title="Eliminar">
                                                                <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                                                            </button>
                                                        </td>

                                                    </tr>
                                                </tbody>
                                            </table>

                                        </div>

                                    </div>
                                    <div role="tabpanel" class="tab-pane fade" id="excedente">

                                        <div class="col-xs-12" style="margin-top: 5px;">
                                            <button type="button" class="btn btn-primary" id="btnAgregar" style="float: right;" ng-click="createRowExcedente()">
                                                Agregar <span class="glyphicon glyphicon-plus" aria-hidden="true">
                                            </button>
                                        </div>

                                        <div class="col-xs-12" style="font-size: 12px !important; margin-top: 5px;">

                                            <table class="table table-responsive table-striped table-hover table-condensed table-bordered">
                                                <thead class="bg-primary">
                                                <tr>
                                                    <th>DESDE</th>
                                                    <th>VALOR</th>
                                                    <th style="width: 5%;"></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                    <tr ng-repeat="elem_e in listexcedente" ng-cloak >

                                                        <td>
                                                            <input type="text" class="form-control" ng-model="elem_e.desdenm3" ng-keypress="onlyDecimal($event)" />
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" ng-model="elem_e.valorexcedente" ng-keypress="onlyDecimal($event)" />
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-danger" ng-click="deleteRowExcedente(elem_e)"
                                                                    data-toggle="tooltip" data-placement="bottom" title="Eliminar">
                                                                <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                                                            </button>
                                                        </td>

                                                    </tr>
                                                </tbody>
                                            </table>

                                        </div>

                                    </div>
                                    <div role="tabpanel" class="tab-pane fade" id="otros">

                                        <div class="col-sm-6 col-xs-12 error" style="margin-top: 5px;">
                                            <div class="input-group">
                                                <span class="input-group-addon">Alcantarillado (%): </span>
                                                <input type="text" class="form-control" name="alcantarillado" id="alcantarillado" ng-model="alcantarillado" placeholder=""
                                                       ng-required="true" ng-keypress="onlyDecimal($event)">
                                            </div>
                                            <span class="help-block error"
                                                  ng-show="formTarifa.alcantarillado.$invalid && formTarifa.alcantarillado.$touched">Alcantarillado es requerido</span>
                                        </div>

                                        <div class="col-sm-6 col-xs-12 error" style="margin-top: 5px;">
                                            <div class="input-group">
                                                <span class="input-group-addon">Desechos Sólidos (%): </span>
                                                <input type="text" class="form-control" name="ddss" id="ddss" ng-model="ddss" placeholder=""
                                                       ng-required="true" ng-keypress="onlyDecimal($event)">
                                            </div>
                                            <span class="help-block error"
                                                  ng-show="formTarifa.ddss.$invalid && formTarifa.ddss.$touched">Desechos Sólidos es requerido</span>
                                        </div>

                                        <div class="col-sm-6 col-xs-12 error" style="margin-top: 5px;">
                                            <div class="input-group">
                                                <span class="input-group-addon">Medio Ambiente: </span>
                                                <input type="text" class="form-control" name="ma" id="ma" ng-model="ma" placeholder=""
                                                       ng-required="true" ng-keypress="onlyDecimal($event)">
                                            </div>
                                            <span class="help-block error"
                                                  ng-show="formTarifa.ma.$invalid && formTarifa.ma.$touched">Medio Ambiente es requerido</span>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>

                </form>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        Cancelar <span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span>
                    </button>
                    <button type="button" class="btn btn-success" id="btn-save" ng-click="saveParams()" ng-disabled="formTarifa.$invalid">
                        Guardar <span class="glyphicon glyphicon-floppy-saved" aria-hidden="true"></span>
                    </button>
                </div>

            </div>
        </div>
    </div>

</div>

