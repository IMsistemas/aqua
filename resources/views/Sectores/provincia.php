    <div ng-controller="provinciasController">
        <div   class="container" style="margin-top: 2%;">
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
                <div class="col-xs-12 table-responsive table-striped table-hover table-condensed">
                    <table class="table" >
                        <thead class="bg-primary">
                            <tr>
                                <th>
                                    <a href="#" style="text-decoration:none; color:white; width: 10%;" ng-click="ordenarColumna='idprovincia'; reversa=!reversa;">Código</a>
                                </th>
                                <th>
                                    <a href="#" style="text-decoration:none; color:white; width: 10%;" ng-click="ordenarColumna='nombreprovincia'; reversa=!reversa;">Nombre</a>
                                </th>
                                <th style="text-decoration:none; color:white; width: 40%; "  class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="provincia in provincias|filter:busqueda | orderBy:ordenarColumna:reversa">
                                <td >{{provincia.idprovincia}}</td>
                                <td >{{provincia.nombreprovincia}}</td>
                                <td  >
                                    <a href="#" class="btn btn-warning" ng-click="toggle('edit', provincia.idprovincia,provincia.nombreprovincia)">Editar Provincia</a>                           
                                     <a href="#" class="btn btn-danger" ng-click="showModalConfirm(provincia.idprovincia,provincia.nombreprovincia)">Borrar Provincia</a>                              
                                    <a  href="#" class="btn btn-info" ng-click="toModuloCanton(provincia.idprovincia);">Ver Cantones</a>
                                </td>
                               
                            </tr>

                        </tbody>
                            
                    </table>
            <!-- End of Table-to-load-the-data Part -->
            <!-- Modal (Pop up when detail button clicked) -->
            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" >
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header modal-header-primary">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">{{form_title}}</h4>
                        </div>
                        <div class="modal-body">
                            <form name="frmProvincias" class="form-horizontal" novalidate="">

                                <div class="form-group">
                                    <label for="t_codigo_provincia" class="col-sm-4 control-label">Código Provincia</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="idprovincia" name="idprovincia" placeholder="" disable 
                                        ng-model="idprovincia">                                    
                                    </div>
                                </div>

                                <div class="form-group error">
                                    <label for="t_name_provincia" class="col-sm-4 control-label">Nombre de Provincia</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="nombreprovincia" name="nombreprovincia" placeholder=""  ng-model="nombreprovincia" ng-required="true" ng-maxlength="32">
                                        <span class="help-inline" 
                                        ng-show="frmProvincias.nombreprovincia.invalid && frmProvincias.nombreprovincia.touched">El nombre de la provincia es requerida</span>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" id="btn-save" ng-click="save(modalstate, idprovincia)" ng-disabled="frmProvincias.$invalid">Guardar</button>
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
                        <span>Realmente desea eliminar el la provincia: <span style="font-weight: bold;">{{provincia_seleccionado}}</span></span>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" id="btn-save" ng-click="destroyProvincia()">Eliminar</button>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>
