    <div ng-controller="cantonesController">
        <div   class="container">

            <!-- Table-to-load-the-data Part -->
            <div class="col-xs-6">
                <div class="form-group has-feedback">
                    <input type="text" class="form-control input-sm" id="search-list-trans" placeholder="BUSCAR..." ng-model="busqueda">
                    <span class="glyphicon glyphicon-search form-control-feedback" aria-hidden="true"></span>
                </div>

            </div>
            <table class="table" >
                <thead>
                    <tr>
                        <th>Código de Canton</th>
                        <th>Nombre de Provincia</th>
                        <th>Nombre de Canton</th>
                        <th><button id="btn-add" class="btn btn-primary btn-xs" ng-click="toggle('add', 0)">Agregar</button></th>
                    </tr>
                </thead>
                <tbody>
                    <tr ng-repeat="canton in cantones|filter:busqueda">
                        <td>{{canton.idcanton}}</td>
                        <td>{{canton.idprovincia}}</td>
                        <td>{{canton.nombrecanton}}</td>
                        <td>
                            <button class="btn btn-default btn-xs btn-detail" ng-click="toggle('edit', canton.idcanton)">Editar Canton</button>
                            <button class="btn btn-danger btn-xs btn-delete" ng-click="confirmDelete(canton.idcanton)">Borrar Canton</button>
                            <button ng-click="toModuloParroquia(canton.idcanton);">Ver Parroquias</button>
                        </td>
                    </tr>

                </tbody>
                    
            </table>
            <!-- End of Table-to-load-the-data Part -->
            <!-- Modal (Pop up when detail button clicked) -->
            <div class="modal fade" id="add" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                            <h4 class="modal-title" id="myModalLabel">{{form_title}}</h4>
                        </div>
                        <div class="modal-body">
                            <form name="frmCanton" class="form-horizontal" novalidate="">

                                <div class="form-group error">
                                    <label for="inputEmail3" class="col-sm-3 control-label">Código Cantón</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control has-error" id="idcanton" name="idcanton" placeholder="Cédula" value="{{idcanton}}" 
                                        ng-model="canton.idcanton" ng-required="true">
                                        <span class="help-inline" 
                                        ng-show="frmCanton.idcanton.$invalid && frmCanton.idcanton.$touched">El código del cantón es requerido</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-3 control-label">Nombre de Cantón</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="nombrecanton" name="nombrecanton" placeholder="Fecha Ingreso" value="{{nombrecanton}}" ng-model="canton.nombrecanton" ng-required="true">
                                        <span class="help-inline" 
                                        ng-show="frmCanton.nombrecanton.invalid && frmCanton.nombrecanton.touched">La fecha de ingreso del cliente es requerida</span>
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


            <div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                            <h4 class="modal-title" id="myModalLabel">{{form_title}}</h4>
                        </div>
                        <div class="modal-body">
                            <form name="frmProvincias" class="form-horizontal" novalidate="">

                                <div class="form-group error">
                                    <label for="inputEmail3" class="col-sm-3 control-label">Código de Provincia</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control has-error" id="idprovincia" name="idprovincia" placeholder="Cédula" value="{{idprovincia}}" 
                                        ng-model="provincia.idprovincia" ng-required="true">
                                        <span class="help-inline" 
                                        ng-show="frmProvincias.idprovincia.$invalid && frmProvincias.idprovincia.$touched">La cédula del provincia es requerida</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-3 control-label">Nombre Provincia</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="nombreprovincia" name="nombreprovincia" value="{{nombreprovincia}}" ng-model="provincia.nombreprovincia" ng-required="true">
                                        <span class="help-inline" 
                                        ng-show="frmProvincias.nombreprovincia && frmProvincias.nombreprovincia">La fecha de ingreso del provincia es requerida</span>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" id="btn-save" ng-click="save(modalstate, documentoidentidad)" ng-disabled="frmProvincias.$invalid">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
