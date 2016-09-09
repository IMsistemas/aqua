    <div ng-controller="barriosController">
        <div   class="container">

            <div class="container" style="margin-top: 2%;">
            <fieldset>
                <legend style="padding-bottom: 10px;">
                    <button type="button" class="btn btn-primary" style="float: right;" ng-click="toggle('add', 0)">Agregar</button>
                </legend>

            <div class="col-xs-6">
                <div class="form-group has-feedback">
                    <input type="text" class="form-control input-sm" id="search-list-trans" placeholder="BUSCAR..." ng-model="busqueda">
                    <span class="glyphicon glyphicon-search form-control-feedback" aria-hidden="true"></span>
                </div>

            </div>
            <div class="col-xs-12">
            <table class="table table-responsive table-striped table-hover table-condensed" >
                <thead>
                    <tr class="bg-primary">
                        <th style="width: 90px;">Código del Barrio</th>
                        <th style="width: 90px;">Código del Parroquia</th>
                        <th>Nombre del Barrio</th>
                        <th style="width: 180px;" colspan="3" class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr ng-repeat="barrio in barrios|filter:busqueda">
                        <td class="text-center">{{barrio.idbarrio}}</td>
                        <td class="text-center">{{barrio.idparroquia}}</td>
                        <td>{{barrio.nombrebarrio}}</td>
                        <td class="text-center">
                            <button class="btn btn-default btn-xs btn-detail" ng-click="toggle('edit', barrio.idbarrio)">Editar Barrio</button>
                        </td>
                        <td class="text-center">
                            <button class="btn btn-danger btn-xs btn-delete" ng-click="confirmDelete(barrio.idbarrio)">Borrar Barrio</button>
                        </td>
                        <td class="text-center">
                            <button class="btn btn-default" ng-click="toModuloCalle(barrio.idbarrio);">Ver Calles</button>
                        </td>
                    </tr>

                </tbody>
                    
            </table>
            </fieldset>
            <!-- End of Table-to-load-the-data Part -->
            <!-- Modal (Pop up when detail button clicked) -->
            <div class="modal fade" id="myModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header modal-header-primary >
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">{{form_title}}</h4>
                        </div>
                        <div class="modal-body">
                            <form name="frmBarrio" class="form-horizontal" novalidate="">

                                <div class="form-group">
                                    <label for="t_codigo_provincia" class="col-sm-4 control-label">Código Barrio</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="idbarrio" name="idbarrio" placeholder=""  
                                        ng-model="barrio.idbarrio" disable>
                                        <span class="help-inline" 
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="t_nombre_provincia" class="col-sm-3 control-label">Nombre de Barrio</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="nombrebarrio" name="nombrebarrio" placeholder="" ng-model="barrio.nombrebarrio" ng-required="true" ng-maxlength="32">
                                        <span class="help-inline" 
                                        ng-show="frmBarrio.nombrebarrio.invalid && frmBarrio.nombrebarrio.touched">El nombre del barrio es requerido</span>
                                        <span class="help-inline" 
                                        ng-show="frmBarrio.nombrebarrio.invalid && frmBarrio.nombrebarrio.$error.maxlength">La longitud máxima es de 32 caracteres</span>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" id="btn-save" ng-click="save(modalstate, idbarrio)" ng-disabled="frmBarrio.$invalid">Guardar</button>
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
                        <span>Realmente desea eliminar el Barrio: <span style="font-weight: bold;">{{barrio_seleccionado}}</span></span>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" id="btn-save" ng-click="destroyBarrio()">Eliminar</button>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>
