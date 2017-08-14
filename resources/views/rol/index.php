

<div ng-controller="rolController">

    <div class="col-xs-12">

        <h4>Gestión de Roles</h4>

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
                    <th>NOMBRE ROL</th>
                    <th style="width: 25%;">ACCIONES</th>
                </tr>
                </thead>
                <tbody>
                <tr dir-paginate="item in roles | orderBy:sortKey:reverse | itemsPerPage:10" total-items="totalItems" ng-cloak">
                    <td>{{item.namerol}}</td>
                    <td class="text-center">


                        <div class="btn-group" role="group" aria-label="...">
                            <button ng-show="item.idrol != 1" type="button" class="btn btn-info" ng-click="toggle('perm', item.idrol)">
                                Permisos <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                            </button>
                            <button type="button" class="btn btn-warning" ng-click="toggle('edit', item.idrol)">
                                Editar <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                            </button>
                            <button ng-show="item.idrol != 1" type="button" class="btn btn-danger" ng-click="showModalConfirm(item)">
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
                                    <span class="input-group-addon">Nombre del Rol: </span>
                                    <input type="text" class="form-control" name="nombrerol" id="nombrerol" ng-model="nombrerol" placeholder=""
                                           ng-required="true" ng-maxlength="150">
                                </div>
                                <span class="help-block error"
                                      ng-show="formCargo.nombrerol.$invalid && formCargo.nombrerol.$touched">El nombre del Rol es requerido</span>
                                <span class="help-block error"
                                      ng-show="formCargo.nombrerol.$invalid && formCargo.nombrerol.$error.maxlength">La longitud máxima es de 50 caracteres</span>
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
                    <span>Realmente desea eliminar el Rol: <span style="font-weight: bold;">{{cargo_seleccionado}}</span></span>

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

    <div class="modal fade" tabindex="-1" role="dialog" id="modalPermisos">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header modal-header-primary">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Permisos</h4>
                </div>
                <div class="modal-body">
                    <div class="row">

                        <div class="col-xs-12">
                            <table class="table table-responsive table-striped table-hover table-condensed table-bordered">
                                <thead class="bg-primary">
                                    <tr>
                                        <th>Nombre</th>
                                        <th style="width: 4%;"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="item in permisos" ng-cloak >
                                        <td>{{item.namepermiso}}</td>
                                        <td>
                                            <input type="checkbox" name="select_cuenta" ng-model="item.state">
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
                    <button type="button" class="btn btn-primary" id="btn-ok" ng-click="savePermisos()">
                        Aceptar <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>

    