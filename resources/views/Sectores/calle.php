
<div ng-controller="callesController">

    <div class="col-xs-12">

        <h4>Gestión de Transversales</h4>

        <hr>

    </div>

    <div class="col-xs-12"  style="margin-top: 5px;">
        <div class="col-sm-4 col-xs-12">
            <div class="form-group has-feedback">
                <input type="text" class="form-control" id="busqueda" placeholder="BUSCAR..." ng-model="busqueda">
                <span class="glyphicon glyphicon-search form-control-feedback" aria-hidden="true"></span>
            </div>
        </div>
        <div class="col-sm-4">
            <select id="s_barrio" class="form-control" ng-model="s_barrio" ng-change="FiltrarPorBarrio()"
                    ng-options="value.id as value.label for value in barrioss"></select>
        </div>
        <div class="col-sm-4 col-xs-12">
            <button type="button" class="btn btn-primary" style="float: right;" ng-click="viewModalAdd()">Agregar  <span class="glyphicon glyphicon-plus" aria-hidden="true"></button>
        </div>

    </div>

    <div class="col-xs-12">
        <table class="table table-responsive table-striped table-hover table-condensed table-bordered">
            <thead class="bg-primary">
            <tr>
                <th>NOMBRE TRANSVERSAL</th>
                <th style="width: 12%;">ACCION</th>
            </tr>
            </thead>
            <tbody>
            <tr dir-paginate="item in calles | orderBy:sortKey:reverse |itemsPerPage:10 |filter:busqueda" ng-cloak>
                <td><input type="text" class="form-control" ng-model="item.namecalle"></td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm" ng-click="showModalDelete(item)">
                        <i class="fa fa-lg fa-trash" aria-hidden="true"></i>
                    </button>

                </td>
            </tr>
            </tbody>
        </table>

        <dir-pagination-controls
            max-size="5"
            direction-links="true"
            boundary-links="true" >
        </dir-pagination-controls>

    </div>

    <div class="col-xs-12" style="float: right;">
        <button type="button" class="btn btn-success" style="float: right; " ng-click="editar()">Guardar <span class="glyphicon glyphicon-floppy-saved" aria-hidden="true"></span></button>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" id="modalNueva">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header modal-header-primary">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Nueva Transversal</h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" name="formCalle" novalidate="">

                        <div class="row">
                            <!--<div class="form-group">
                                <label for="t_codigo" class="col-sm-4 control-label">Código: </label>
                                <div class="col-sm-8" style="padding-top: 7px;">
                                    {{codigo_toma}}
                                </div>
                            </div>-->

                            <div class="col-xs-12 error">
                                <div class="input-group">
                                    <span class="input-group-addon">Zona: </span>
                                    <select id="t_barrio" class="form-control" ng-model="t_barrio" name="t_barrio"
                                            ng-options="value.id as value.label for value in barrios" required></select>
                                </div>
                                <span class="help-block error"
                                      ng-show="formCalle.t_barrio.$invalid && formCalle.t_barrio.$touched">La zona es requerida</span>
                            </div>

                            <div class="col-xs-12 error" style="margin-top: 5px;">
                                <div class="input-group">
                                    <span class="input-group-addon">Transversal: </span>
                                    <input type="text" class="form-control" name="nombrecalle" id="nombrecalle" ng-model="nombrecalle" placeholder=""
                                           ng-required="true" ng-maxlength="100">
                                </div>
                                <span class="help-block error"
                                      ng-show="formCalle.nombrecalle.$invalid && formCalle.nombrecalle.$touched">El nombre de la Transversal es requerido</span>
                                <span class="help-block error"
                                      ng-show="formCalle.nombrecalle.$invalid && formCalle.nombrecalle.$error.maxlength">La longitud máxima es de 100 caracteres</span>
                            </div>

                            <!--<div class="col-xs-12" style="margin-top: 5px;">
                                <textarea id="observacionCalle" class="form-control" rows="3" ng-model="observacionCalle" placeholder="Observación"></textarea>
                            </div>-->
                        </div>



                        <!--<div class="form-group">
                            <label for="t_codigo" class="col-sm-4 control-label">Código: </label>
                            <div class="col-sm-8" style="padding-top: 7px;">
                                {{codigo}}
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="t_barrio" class="col-sm-4 control-label">Zona:</label>
                            <div class="col-sm-8">
                                <select id="t_barrio" class="form-control" ng-model="t_barrio"
                                        ng-options="value.id as value.label for value in barrios" required></select>
                            </div>
                        </div>

                        <div class="form-group error">
                            <label for="nombrecalle" class="col-sm-4 control-label">Nombre de la Transversal:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="nombrecalle" id="nombrecalle" ng-model="nombrecalle" placeholder=""
                                       ng-required="true" ng-maxlength="64">
                                <span class="help-block error"
                                      ng-show="formCalle.nombrecalle.$invalid && formCalle.nombrecalle.$touched">El nombre de la Transversal es requerido</span>
                                <span class="help-block error"
                                      ng-show="formCalle.nombrecalle.$invalid && formCalle.nombrecalle.$error.maxlength">La longitud máxima es de 64 caracteres</span>
                            </div>
                        </div>-->
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        Cancelar <span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span>
                    </button>
                    <button type="button" class="btn btn-success" id="btn-save" ng-click="saveCalle();" ng-disabled="formCalle.$invalid">
                        Guardar <span class="glyphicon glyphicon-floppy-saved" aria-hidden="true"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" id="modalDelete">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header modal-header-danger">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Confirmación</h4>
                </div>
                <div class="modal-body">
                    <span>Realmente desea eliminar la Transversal: <strong>"{{nom_calle}}"</strong>?</span>
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

    <div class="modal fade" tabindex="-1" role="dialog" id="modalMessageError">
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
