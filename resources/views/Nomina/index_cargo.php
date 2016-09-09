

    <div ng-controller="cargosController">

        <div class="container" style="margin-top: 2%;">
            <fieldset>
                <legend style="padding-bottom: 20px;">
                   
                    <button type="button" class="btn btn-primary" style="float: right;" ng-click="toggle('add', 0)">Agregar</button>
                </legend>

                <div class="col-xs-6">
                    <div class="form-group has-feedback">
                        <input type="text" class="form-control" id="search-list-trans" placeholder="BUSCAR..." ng-pagination-search="cargos">
                        <span class="glyphicon glyphicon-search form-control-feedback" aria-hidden="true"></span>
                    </div>
                </div>

                <div class="col-xs-12">
                    <table class="table table-responsive table-striped table-hover table-condensed">
                        <thead class="bg-primary">
                            <tr>
                                <th style="width: 90px;">Código</th>
                                <th>Nombre</th>
                                <th style="width: 180px;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-pagination="cargo in cargos" ng-pagination-size="10">
                                <td class="text-center">{{cargo.idcargo}}</td>
                                <td>{{cargo.nombrecargo}}</td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-warning" ng-click="toggle('edit', cargo.idcargo)">Editar</button>
                                    <button type="button" class="btn btn-danger" ng-click="showModalConfirm(cargo.idcargo)">Eliminar</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <ng-pagination-control pagination-id="cargos"></ng-pagination-control>
                </div>
            </fieldset>
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
                            <div class="form-group">
                                <label for="t_codigo_cargo" class="col-sm-4 control-label">Código Cargo:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="idcargo" id="idcargo" ng-model="idcargo" placeholder="" disabled>
                                </div>
                            </div>
                            <div class="form-group error">
                                <label for="t_name_cargo" class="col-sm-4 control-label">Nombre del Cargo:</label>
                                <div class="col-sm-8">
                                    <input type="number" class="form-control" name="nombrecargo" id="nombrecargo" ng-model="nombrecargo" placeholder=""
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
                        <button type="button" class="btn btn-success" id="btn-save" ng-click="save(modalstate, id)" ng-disabled="formCargo.$invalid">Guardar</button>
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
                        <span>Realmente desea eliminar el Cargo: <span style="font-weight: bold;">{{cargo_seleccionado}}</span></span>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" id="btn-save" ng-click="destroyCargo()">Eliminar</button>
                    </div>
                </div>
            </div>
        </div>

    </div>

    