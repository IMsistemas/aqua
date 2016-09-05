<!DOCTYPE html>
<html lang="en-US" ng-app="softver-aqua">
    <head>
        <title>Provincias</title>

        <!-- Load Bootstrap CSS -->
        <link href="<?= asset('css/bootstrap.min.css') ?>" rel="stylesheet">
    </head>
    <body>
        <h2 class="container">Lista de Clientes</h2>
        <div  ng-controller="provinciasController" class="container">

            <!-- Table-to-load-the-data Part -->
            <input type="text" ng-pagination-search="provincias" >
            <table class="table" >
                <thead>
                    <tr>
                        <th>Código de Provincia</th>
                        <th>Nombre de Provincia</th>
                        <th><button id="btn-add" class="btn btn-primary btn-xs" ng-click="toggle('add', 0)">Agregar</button></th>
                    </tr>
                </thead>
                <tbody>
                    <tr ng-pagination="provincia in provincias" ng-pagination-size="2">
                        <td>{{provincia.idprovincia}}</td>
                        <td>{{provincia.nombreprovincia}}</td>
                        <td>
                            <button class="btn btn-default btn-xs btn-detail" ng-click="toggle('edit', provincia.idprovincia)">Editar Provincia</button>
                            <button class="btn btn-danger btn-xs btn-delete" ng-click="confirmDelete(provincia.idprovincia)">Borrar Provincia</button>
                        </td>
                    </tr>

                </tbody>
                    
            </table>
              <ng-pagination-control pagination-id="provincias"></ng-pagination-control>
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
                            <form name="frmProvincias" class="form-horizontal" novalidate="">

                                <div class="form-group error">
                                    <label for="inputEmail3" class="col-sm-3 control-label">Documento de identidad del Cliente</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control has-error" id="idprovincia" name="idprovincia" placeholder="Cédula" value="{{idprovincia}}" 
                                        ng-model="provincia.idprovincia" ng-required="true">
                                        <span class="help-inline" 
                                        ng-show="frmProvincias.idprovincia.$invalid && frmProvincias.idprovincia.$touched">La cédula del cliente es requerida</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-3 control-label">Nombre de Provincia</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="nombreprovincia" name="nombreprovincia" placeholder="Fecha Ingreso" value="{{nombreprovincia}}" ng-model="cliente.nombreprovincia" ng-required="true">
                                        <span class="help-inline" 
                                        ng-show="frmProvincias.nombreprovincia.invalid && frmProvincias.nombreprovincia.touched">La fecha de ingreso del cliente es requerida</span>
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

        <!-- Load Javascript Libraries (AngularJS, JQuery, Bootstrap) -->
        <script src="<?= asset('app/lib/angular/angular.min.js') ?>"></script>
        <script src="<?= asset('app/lib/angular/angular-pagination.js') ?>"></script>
        <script src="<?= asset('js/jquery.min.js') ?>"></script>
        <script src="<?= asset('js/bootstrap.min.js') ?>"></script>      
        <!-- AngularJS Application Scripts -->
        <script src="<?= asset('app/app.js') ?>"></script>
        <script src="<?= asset('app/controllers/provinciasController.js') ?>"></script>
        

    </body>
</html>
