<!doctype html>
    <html lang="es-ES" ng-app="softver-aqua">
    <head>
        <meta charset="UTF-8">

        <title>Aqua-Consulta Lectura</title>

        <link href="<?= asset('css/bootstrap.min.css') ?>" rel="stylesheet">
        <link href="<?= asset('css/font-awesome.min.css') ?>" rel="stylesheet">
        <link href="<?= asset('css/bootstrap-datetimepicker.min.css') ?>" rel="stylesheet">
        <link href="<?= asset('css/style_generic_app.css') ?>" rel="stylesheet">

        <script src="<?= asset('app/lib/angular/angular.min.js') ?>"></script>

        <style>
            .dataclient{
                font-weight: bold;
            }
        </style>

    </head>
    <body ng-controller="viewLecturaController">

        <div class="container" style="margin-top: 2%;">
            <fieldset>

                <legend style="padding-bottom: 10px;">
                    <span style="font-weight: bold;">CONSULTA DE LECTURAS</span>
                    <button type="button" class="btn btn-default" style="float: right;" ng-click="">
                        <i class="fa fa-print fa-2x" aria-hidden="true"></i>
                    </button>
                    <button type="button" class="btn btn-default" style="float: right;" ng-click="">
                        <i class="fa fa-file-pdf-o fa-2x" aria-hidden="true"></i>
                    </button>
                    <button type="button" class="btn btn-default" style="float: right;" ng-click="">
                        <i class="fa fa-file-excel-o fa-2x" aria-hidden="true"></i>
                    </button>
                </legend>

                <div class="col-xs-12">
                    <fieldset>
                        <legend>Periodo:</legend>
                        <div class="row">
                            <div class="col-xs-3">
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Año:</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control datepicker_a" name="s_anno"
                                               id="s_anno" ng-model="s_anno">
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-3">
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Mes:</label>
                                    <div class="col-sm-8">
                                        <select class="form-control" name="s_mes" id="s_mes" ng-model="s_mes">
                                            <option value="01">Enero</option>
                                            <option value="02">Febrero</option>
                                            <option value="03">Marzo</option>
                                            <option value="04">Abril</option>
                                            <option value="05">Mayo</option>
                                            <option value="06">Junio</option>
                                            <option value="07">Julio</option>
                                            <option value="08">Agosto</option>
                                            <option value="09">Septiembre</option>
                                            <option value="10">Octubre</option>
                                            <option value="11">Noviembre</option>
                                            <option value="12">Diciembre</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </fieldset>
                </div>
                
                <div class="col-xs-12">
                    <fieldset>
                        <legend>Sector:</legend>
                        <div class="row">
                            <div class="col-xs-3">
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Barrio:</label>
                                    <div class="col-sm-8">
                                        <select class="form-control" name="s_barrio" id="s_barrio" ng-model="s_barrio"
                                                ng-options="value.id as value.label for value in barrios"
                                                ng-change="loadCalles()"></select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-3">
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Calle:</label>
                                    <div class="col-sm-8">
                                        <select class="form-control" name="s_calle" id="s_calle" ng-model="s_calle"
                                            ng-options="value.id as value.label for value in calles"></select>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </fieldset>
                </div>

                <div class="col-xs-12" style="margin-top: 10px;">
                    <div class="form-group has-feedback" style="float: right;">
                        <input type="text" class="form-control" id="search-list-trans" placeholder="BUSCAR..." >
                        <span class="glyphicon glyphicon-search form-control-feedback" aria-hidden="true"></span>
                    </div>
                </div>

                <div class="col-xs-12">
                    <table class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr class="bg-primary">
                                <th>Código</th>
                                <th>Cliente</th>
                                <th>Suministro</th>
                                <th>Calle</th>
                                <th>Lectura Anterior</th>
                                <th>Lectura Actual</th>
                                <th>Consumo del Mes</th>
                                <th>Observaciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="lectura in lecturas" ng-cloak>
                                <td>{{lectura.idlectura}}</td>
                                <td>{{lectura.apellido + ' ' + lectura.nombre}}</td>
                                <td>{{lectura.numerosuministro}}</td>
                                <td>{{lectura.nombrecalle}}</td>
                                <td>{{lectura.lecturaanterior}}</td>
                                <td><input type="text" class="form-control" style="width: 100%;" value="{{lectura.lecturaactual}}"></td>
                                <td>{{lectura.consumo}}</td>
                                <td><textarea class="form-control" name="" id="" cols="5" rows="2"></textarea></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="col-xs-12">
                    <button type="button" class="btn btn-success" style="float: right;" ng-click="">
                        Guardar
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


    </body>


    <script src="<?= asset('app/lib/angular/angular-pagination.js') ?>"></script>
    <script src="<?= asset('js/jquery.min.js') ?>"></script>
    <script src="<?= asset('js/bootstrap.min.js') ?>"></script>
    <script src="<?= asset('js/moment.min.js') ?>"></script>
    <script src="<?= asset('js/es.js') ?>"></script>
    <script src="<?= asset('js/bootstrap-datetimepicker.min.js') ?>"></script>

    <!-- AngularJS Application Scripts -->
    <script src="<?= asset('app/app.js') ?>"></script>
    <script src="<?= asset('app/controllers/viewLecturaController.js') ?>"></script>

    <script>
        $(function(){
            $('.datepicker').datetimepicker({
                locale: 'es',
                format: 'DD/MM/YYYY'
            });

            $('.datepicker_a').datetimepicker({
                locale: 'es',
                format: 'YYYY'
            });
        })
    </script>

</html>