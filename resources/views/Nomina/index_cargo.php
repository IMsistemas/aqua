
<div ng-controller="cargosController">
    <div class="col-xs-12" style="margin-top: 2%;">

        <div class="col-sm-6 col-xs-8">
            <div class="form-group has-feedback">
                <input type="text" class="form-control" id="busqueda" placeholder="BUSCAR..." ng-model="busqueda">
                <span class="glyphicon glyphicon-search form-control-feedback" aria-hidden="true"></span>
            </div>
        </div>

        <div class="col-sm-6 col-xs-4">
            <button type="button" class="btn btn-primary" style="float: right;" ng-click="Add('add',0)">
                Agregar <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
            </button>
        </div>

        <div class="col-xs-12">
            <table class="table table-responsive table-striped table-hover table-condensed">
                <thead class="bg-primary">
                <tr>
                    <th>Nombre</th>
                    <th style="width: 200px;">Acciones</th>
                </tr>
                </thead>
                <tbody>
                <tr dir-paginate="cargo in cargos | orderBy:sortKey:reverse |itemsPerPage:10 | filter : busqueda" ng-cloak>
                    <td>{{cargo.nombrecargo}}</td>
                    <td class="text-center">
                        <button type="button" class="btn btn-warning" ng-click="Add('edit', cargo.idcargo)">
                            Editar <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                        </button>
                        <button type="button" class="btn btn-danger" ng-click="showModalConfirm(cargo)">
                            Eliminar <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
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
                        <div class="form-group error">
                            <label for="t_name_cargo" class="col-sm-4 control-label">Nombre del Cargo:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="nombrecargo" id="nombrecargo" ng-model="nombrecargo" placeholder=""
                                       ng-required="true" ng-maxlength="16">
                                <span class="help-block error"
                                      ng-show="formCargo.nombrecargo.$invalid && formCargo.nombrecargo.$touched">El nombre del Cargo es requerido</span>
                                <span class="help-block error"
                                      ng-show="formCargo.nombrecargo.$invalid && formCargo.nombrecargo.$error.maxlength">La longitud máxima es de 16 caracteres</span>
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

    <div class="modal fade" tabindex="-1" role="dialog" id="modalConfirmDelete">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header modal-header-danger">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Confirmación</h4>
                </div>
                <div class="modal-body">
                    <span>Realmente desea eliminar el Cargo: <strong>"{{nom_cargo_delete}}"</strong>?</span>
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
