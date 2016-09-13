   <div ng-controller="parroquiasController">
        <div   class="container">

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
                        <thead class="bg-primary">
                            <tr>
                                <th>
                                    <a  href="#" style="text-decoration:none; color:white; width: 10%;" ng-click="ordenarColumna='idparroquia'; reversa=!reversa;">Código</a>
                                </th>
                                <th>
                                    <a  href="#" style="text-decoration:none; color:white; width: 10%;" ng-click="ordenarColumna='nombreparroquia'; reversa=!reversa;">Nombre</a>
                                </th>
                                <th  href="#" style="text-decoration:none; color:white; width: 40%;" class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="parroquia in parroquias|filter:busqueda| orderBy:ordenarColumna:reversa">
                                <td >{{parroquia.idparroquia}}</td>
                                <td>{{parroquia.nombreparroquia}}</td>
                                <td >
                                    <a class="btn btn-warning" ng-click="toggle('edit', parroquia.idparroquia,parroquia.nombreparroquia)">Editar Parroquias</a>
                                    <a class="btn btn-danger" ng-click="showModalConfirm(parroquia.idparroquia,parroquia.nombreparroquia.trim())">Borrar Parroquias</a>
                                    <a class="btn btn-info" ng-click="toModuloBarrio(parroquia.idparroquia);">Ver Barrios</a>
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
                                        ng-model="idparroquia" disable>
                                    </div>
                                </div>

                                <div class="form-group error">
                                    <label for="t_nombre_parroquia" class="col-sm-4 control-label">Nombre de la Parroquia</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="nombreparroquia" name="nombreparroquia" placeholder="" ng-model="nombreparroquia" ng-required="true" ng-maxlength="16">
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
                        <span>Realmente desea eliminar la Parroquia: <span style="font-weight: bold;">{{parroquia_seleccionado}}</span></span>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" id="btn-save" ng-click="destroyParroquia()">Eliminar</button>
                    </div>
                </div>
            </div>
        </div>  

        </div>
    </div>
