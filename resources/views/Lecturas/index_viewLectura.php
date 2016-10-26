<!-- <!doctype html>
    <html lang="es-ES" ng-app="softver-aqua">
    <head>
        <meta charset="UTF-8">

        <title>Aqua-Consulta Lectura</title>

        <link href="<?= asset('css/bootstrap.min.css') ?>" rel="stylesheet">
        <link href="<?= asset('css/font-awesome.min.css') ?>" rel="stylesheet">
        <link href="<?= asset('css/bootstrap-datetimepicker.min.css') ?>" rel="stylesheet">
        <link href="<?= asset('css/style_generic_app.css') ?>" rel="stylesheet">


        <style>
            .dataclient{
                font-weight: bold;
            }
        </style>

    </head> -->
    <div ng-controller="viewLecturaController">

        <div class="col-xs-12" style="margin-top: 2%;">
            <fieldset>

                <legend style="padding-bottom: 10px;">
                    <span style="font-weight: bold;">CONSULTA DE LECTURAS</span>
                    <button type="button" class="btn btn-default" style="float: right; display: none;" ng-click="">
                        <i class="fa fa-print fa-2x" aria-hidden="true"></i>
                    </button>
                    <button type="button" class="btn btn-default" style="float: right; display: none;" ng-click="">
                        <i class="fa fa-file-pdf-o fa-2x" aria-hidden="true"></i>
                    </button>
                    <button type="button" class="btn btn-default" style="float: right; display: none;" ng-click="">
                        <i class="fa fa-file-excel-o fa-2x" aria-hidden="true"></i>
                    </button>
                </legend>

                <div class="col-sm-6 col-xs-12">
                    <fieldset>
                        <legend>Periodo:</legend>
                        <div class="row">
                            <div class="col-xs-6" style="padding: 0;">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Año:</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control datepicker_a" name="s_anno"
                                               id="s_anno" ng-model="s_anno" ng-change="searchByFilter();">
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-6" style="padding: 0;">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Mes:</label>
                                    <div class="col-sm-10">
                                        <select class="form-control" name="s_mes" id="s_mes" ng-model="s_mes" ng-change="searchByFilter();"
                                                    ng-options="value.id as value.name for value in meses">
                                           </select>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </fieldset>
                </div>
                
                <div class="col-sm-6 col-xs-12">
                    <fieldset>
                        <legend>Sector:</legend>
                        <div class="row">
                            <div class="col-xs-6" style="padding: 0;">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Barrio:</label>
                                    <div class="col-sm-9">
                                        <select class="form-control" name="s_barrio" id="s_barrio" ng-model="s_barrio"
                                                ng-options="value.id as value.label for value in barrios"
                                                ng-change="loadCalles(); searchByFilter();"></select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-6" style="padding: 0;">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Calle:</label>
                                    <div class="col-sm-9">
                                        <select class="form-control" name="s_calle" id="s_calle" ng-model="s_calle"
                                            ng-options="value.id as value.label for value in calles"
                                            ng-change="searchByFilter();"></select>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </fieldset>
                </div>

                <div class="col-xs-12" style="margin-top: 10px;">
                    <div class="form-group has-feedback" style="float: right; width: 50%;">
                        <input type="text" class="form-control" id="search-list-trans" placeholder="BUSCAR..." 
                                ng-model="t_search" ng-change="searchByFilter();" >
                        <span class="glyphicon glyphicon-search form-control-feedback" aria-hidden="true"></span>
                    </div>
                </div>

                <div class="col-xs-12">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr class="bg-primary">
                                <th style="width: 5%;">Código</th>
                                <th>Cliente</th>
                                <th>Suministro</th>
                                <th>Calle</th>
                                <th style="width: 10%;">Lectura Anterior</th>
                                <th style="width: 10%;">Lectura Actual</th>
                                <th style="width: 10%;">Consumo del Mes</th>
                                <th>Observaciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="lectura in lecturas" ng-cloak>
                                <td class="text-right">{{lectura.idlectura}}</td>
                                <td><i class="fa fa-user" ></i> {{lectura.apellido + ' ' + lectura.nombre}}</td>
                                <td class="text-right">{{lectura.numerosuministro}}</td>
                                <td>{{lectura.nombrecalle}}</td>
                                <td class="text-right">{{lectura.lecturaanterior}}</td>
                                <td  class="text-right">
                                   <span ng-if="verifyDate(lectura.fechalectura) == true">
                                       <input type="number" class="form-control" ng-change="prepareUpdate(lectura)" style="width: 100%;"
                                              string-to-number ng-model="lectura.lecturaactual">
                                   </span>
                                    <span ng-if="verifyDate(lectura.fechalectura) == false">
                                       <input type="number" class="form-control" ng-change="prepareUpdate(lectura)" style="width: 100%;"
                                              string-to-number ng-model="lectura.lecturaactual" disabled>
                                   </span>
                                </td>
                                <td class="text-right">{{lectura.consumo}}</td>
                                <td>
                                    <textarea class="form-control" name="" id="" cols="5" rows="2" 
                                            ng-change="prepareUpdate(lectura)" ng-model="lectura.observacion"></textarea>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="col-xs-12">
                    <button type="button" class="btn btn-success" style="float: right;" ng-click="save()">
                        Guardar <span class="glyphicon glyphicon-floppy-saved" aria-hidden="true"></span>
                    </button>
                </div>

            </fieldset>
        </div>


        <div class="modal fade" tabindex="-1" role="dialog" id="modalConfirm">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header modal-header-info">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Confirmación</h4>
                    </div>
                    <div class="modal-body">
                        <span>¿Realmente desea guardar la Lectura?. En caso de acierto, una vez guardada la misma no será editable...</span>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" id="btn-save" ng-click="save()">Aceptar</button>
                    </div>
                </div>
            </div>
        </div>


    </div>


  <!--   <script src="<?= asset('app/lib/angular/angular.min.js') ?>"></script>
  <script src="<?= asset('app/lib/angular/angular-route.min.js') ?>"></script>
  <script src="<?= asset('js/jquery.min.js') ?>"></script>
  <script src="<?= asset('js/bootstrap.min.js') ?>"></script>
  
  
  <script src="<?= asset('js/moment.min.js') ?>"></script>
  <script src="<?= asset('js/es.js') ?>"></script>
  <script src="<?= asset('js/bootstrap-datetimepicker.min.js') ?>"></script>
  
  AngularJS Application Scripts
  <script src="<?= asset('app/app.js') ?>"></script>
  <script src="<?= asset('app/controllers/viewLecturaController.js') ?>"></script>
  
  
  </html> -->