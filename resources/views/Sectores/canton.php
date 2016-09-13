    <div ng-controller="cantonesController">
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
            <table class="table table-responsive table-striped table-hover table-condensed"" >
                <thead class="bg-primary">
                    <tr>
                        <th> 
                            <a href="" style="text-decoration:none; color:white; width: 10%; " ng-click="ordenarColumna='idcanton'; reversa=!reversa;" >Código </a>
                        </th>
                        <th >
                             <a href=""style="text-decoration:none; color:white; width: 10%; " ng-click="ordenarColumna='nombrecanton'; reversa=!reversa;" >Nombre</a>
                        </th>
                        <th style="text-decoration:none; color:white; width: 40%; "  >Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr ng-repeat="canton in cantones|filter:busqueda|orderBy:ordenarColumna:reversa">
                        <td>{{canton.idcanton}}</td>                       
                        <td>{{canton.nombrecanton}}</td>
                        <td >
                            <a href="#" class="btn btn-warning" ng-click="toggle('edit', canton.idcanton,canton.nombrecanton)">Editar Canton</a> 
                            <a href="#" class="btn btn-danger" ng-click="showModalConfirm(canton.idcanton,canton.nombrecanton)">Borrar Canton</a>
                            <a href="#" class="btn btn-info" ng-click="toModuloParroquia(canton.idcanton);">Ver Parroquias</a>
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
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" >{{form_title}}</h4>
                        </div>
                        <div class="modal-body">
                            <form name="frmCanton" class="form-horizontal" novalidate="">

                                <div class="form-group error">
                                    <label for="t_codigo_canton" class="col-sm-4 control-label">Código Cantón</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="idcanton" name="idcanton" placeholder="" 
                                        ng-model="idcanton" disable>                    
                                    </div>
                                </div>

                                <div class="form-group error">
                                    <label for="t_name_canton" class="col-sm-4 control-label">Nombre de Cantón</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="nombrecanton" name="nombrecanton" placeholder=""  ng-model="nombrecanton" ng-required="true" ng-maxlength="32">
                                        <span class="help-inline" 
                                        ng-show="frmCanton.nombrecanton.invalid && frmCanton.nombrecanton.touched">El nombre de la provincia es requerido</span>
                                        <span class="help-inline" 
                                        ng-show="frmCanton.nombrecanton.invalid && frmCanton.nombrecanton.$error.maxlength">La longitud máxima es de 32 caracteres</span>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" id="btn-save" ng-click="save(modalstate, idcanton)" ng-disabled="frmCanton.$invalid">Guardar</button>
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
                        <span>Realmente desea eliminar el Canton: <span style="font-weight: bold;">{{canton_seleccionado}}</span></span>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" id="btn-save" ng-click="destroyCanton()">Eliminar</button>
                    </div>
                </div>
            </div>
        </div>

        </div>
    </div>
