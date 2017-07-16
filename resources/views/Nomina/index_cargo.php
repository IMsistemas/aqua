

    <div ng-controller="cargosController">

        <div class="col-xs-12">

            <h4>Gestión de Cargos</h4>

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
                <button type="button" id="btnAgregar" class="btn btn-primary" style="float: right;" ng-click="toggle('add', 0)">
                    Agregar <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                </button>
            </div>

            <div class="col-xs-12">
                <div class="alert alert-info" role="alert" id="message-positions" style="display: none;">
                    <span style="font-weight: bold;">INFORMACION: </span>
                    Para Administrar Cargos, se necesita crear Departamentos primeramente...
                </div>
            </div>

            <div class="col-xs-12">
                <table class="table table-responsive table-striped table-hover table-condensed">
                    <thead class="bg-primary">
                    <tr>
                        <th>NOMBRE CARGO</th>
                        <th>DEPARTAMENTO</th>
                        <th style="width: 200px;">ACCIONES</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr dir-paginate="cargo in cargos | orderBy:sortKey:reverse | itemsPerPage:10" total-items="totalItems" ng-cloak">
                        <td>{{cargo.namecargo}}</td>
                        <td>{{cargo.departamento.namedepartamento}}</td>
                        <td class="text-center">
                            <button type="button" class="btn btn-warning" ng-click="toggle('edit', cargo.idcargo)">
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
                                        <span class="input-group-addon">Departamento: </span>
                                        <select class="form-control" name="departamento" id="departamento" ng-model="departamento"
                                                ng-options="value.id as value.label for value in iddepartamentos" required></select>
                                    </div>
                                    <span class="help-block error"
                                          ng-show="formCargo.departamento.$invalid && formCargo.departamento.$touched">El Departamento es requerido</span>
                                </div>

                                <div class="col-xs-12 error" style="margin-top: 5px;">
                                    <div class="input-group">
                                        <span class="input-group-addon">Nombre del Cargo: </span>
                                        <input type="text" class="form-control" name="nombrecargo" id="nombrecargo" ng-model="nombrecargo" placeholder=""
                                               ng-required="true" ng-maxlength="50">
                                    </div>
                                    <span class="help-block error"
                                          ng-show="formCargo.nombrecargo.$invalid && formCargo.nombrecargo.$touched">El nombre del Cargo es requerido</span>
                                    <span class="help-block error"
                                          ng-show="formCargo.nombrecargo.$invalid && formCargo.nombrecargo.$error.maxlength">La longitud máxima es de 50 caracteres</span>
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
                        <span>Realmente desea eliminar el Cargo: <span style="font-weight: bold;">{{cargo_seleccionado}}</span></span>

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

    </div>

    