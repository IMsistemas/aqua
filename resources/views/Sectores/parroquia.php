   <div ng-controller="parroquiasController">
        <div   class="container">

           <fieldset>
                <legend style="padding-bottom: 10px;">
                    <span style="font-weight: bold;">ADMINISTRACION DE PARROQUIAS</span>
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
                        <thead class="bg-primary">
                            <tr>
                                <th style="width: 90px;">Código del Parroquia</th>
                                <th>Código del Cantón</th>
                                <th>Nombre del Parroquia</th>
                                <th style="width: 180px;" colspan="3" class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="parroquia in parroquias|filter:busqueda">
                                <td class="text-center">{{parroquia.idparroquia}}</td>
                                <td class="text-center">{{parroquia.idcanton}}</td>
                                <td>{{parroquia.nombreparroquia}}</td>
                                <td class="text-center">
                                    <button class="btn btn-default" ng-click="toggle('edit', parroquia.idparroquia)">Editar Parroquias</button>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-danger" ng-click="confirmDelete(parroquia.idparroquia)">Borrar Parroquias</button>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-default" ng-click="toModuloBarrio(parroquia.idparroquia);">Ver Barrios</button>
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
                        <div class="modal-header modal-header-primary">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                            <h4 class="modal-title" id="myModalLabel">{{form_title}}</h4>
                        </div>
                        <div class="modal-body">
                            <form name="frmParroquia" class="form-horizontal" novalidate="">

                                <div class="form-group">
                                    <label for="t_codigo_parroquia" class="col-sm-4 control-label">Código de la Parroquia</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control " id="idparroquia" name="idparroquia" placeholder=""  
                                        ng-model="parroquia.idparroquia" disable>
                                    </div>
                                </div>

                                <div class="form-group error">
                                    <label for="t_nombre_parroquia" class="col-sm-4 control-label">Nombre de la Parroquia</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="nombreparroquia" name="nombreparroquia" placeholder="" ng-model="parroquia.nombreparroquia" ng-required="true" ng-maxlength="16">
                                        <span class="help-inline" 
                                        ng-show="frmParroquia.nombreparroquia.invalid && frmParroquia.nombreparroquia.touched">El nombre del parroquia es requerido</span>
                                        <span class="help-inline" 
                                        ng-show="frmParroquia.nombreparroquia.invalid && frmParroquia.nombreparroquia.$error.maxlength">La longitud máxima es de 16 caracteres</span>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" id="btn-save" ng-click="save(modalstate, idparroquia)" ng-disabled="frmParroquia.$invalid">Guardar</button>
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
                        <span>Realmente desea eliminar el Parroquia: <span style="font-weight: bold;">{{parroquia_seleccionado}}</span></span>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" id="btn-save" ng-click="destroyParroquia()">Eliminar</button>
                    </div>
                </div>
            </div>
        </div>  

        </div>
    </div>
