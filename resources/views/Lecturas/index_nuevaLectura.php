<!Doctype html>
    <html lang="es-ES" ng-app="softver-aqua">
    <head>
        <meta charset="UTF-8">
 
        <title>Aqua - Nueva Lectura</title>

        <link href="<?= asset('css/bootstrap.min.css') ?>" rel="stylesheet">
        <link href="<?= asset('css/font-awesome.min.css') ?>" rel="stylesheet">
        <link href="<?= asset('css/bootstrap-datetimepicker.min.css') ?>" rel="stylesheet">
        <link href="<?= asset('css/style_generic_app.css') ?>" rel="stylesheet">

    </head>
    <body ng-controller="nuevaLecturaController">

        <div class="col-xs-12" style="margin-top: 5px;">

            <h3>NUEVA LECTURA</h3>
            <hr>

            <form class="form-horizontal" name="formNewLectura" novalidate >

                <div class="col-xs-12">
                    <div class="col-sm-6 col-xs-12" style="margin-top: 5px;">

                        <div class="input-group">
                            <span class="input-group-addon">Lectura Nro: </span>
                            <input type="text" class="form-control" name="t_no_lectura" id="t_no_lectura"
                                   ng-model="t_no_lectura" disabled />
                        </div>

                    </div>

                    <div class="col-sm-6 col-xs-12" style="margin-top: 5px;">

                        <div class="input-group">
                            <span class="input-group-addon">Fecha Ingreso: </span>
                            <input type="text" class="form-control datepicker" name="t_fecha_ing"
                                   id="t_fecha_ing" ng-model="t_fecha_ing" ng-required="true" disabled />
                        </div>
                        <span class="help-block error"
                              ng-show="formNewLectura.t_fecha_ing.$invalid && formNewLectura.t_fecha_ing.$touched">La fecha de ingreso es requerida</span>

                    </div>
                </div>

                <div class="col-xs-12">

                    <h4>Periodo:</h4>
                    <hr>

                    <div class="row">

                        <div class="col-md-3 col-xs-6">

                            <div class="input-group">
                                <span class="input-group-addon">Año: </span>
                                <input type="text" class="form-control datepicker_a" name="s_anno" id="s_anno"
                                       ng-model="s_anno" ng-required="true" >
                            </div>
                            <span class="help-block error"
                                  ng-show="formNewLectura.s_anno.$invalid && formNewLectura.s_anno.$touched">El año es requerido</span>

                        </div>

                        <div class="col-md-3 col-xs-6">

                            <div class="input-group">
                                <span class="input-group-addon">Mes: </span>
                                <select class="form-control" name="s_mes" id="s_mes" ng-model="s_mes"
                                        ng-options="value.id as value.name for value in meses">
                                </select>
                            </div>

                        </div>

                        <div class="col-md-3 col-xs-6">

                            <div class="input-group">
                                <span class="input-group-addon">Suministro Nro: </span>
                                <input type="number" class="form-control" name="t_no_suministro"
                                       id="t_no_suministro" ng-model="t_no_suministro" onkeypress="return isOnlyNumberPto(this, event);"
                                       ng-required="true" />
                            </div>
                            <span class="help-block error"
                                  ng-show="formNewLectura.t_no_suministro.$invalid && formNewLectura.t_no_suministro.$touched">El Nro. de Suministro es requerido</span>

                        </div>

                        <div class="col-md-3 col-xs-6">

                            <div class="input-group">
                                <span class="input-group-addon">Lectura: </span>
                                <input type="number" class="form-control" name="t_lectura" id="t_lectura"
                                       ng-model="t_lectura" onkeypress="return isOnlyNumberPto(this, event);" ng-required="true" />
                            </div>
                            <span class="help-block error"
                                  ng-show="formNewLectura.t_lectura.$invalid && formNewLectura.t_lectura.$touched">El Nro de Lectura es requerido</span>

                        </div>

                    </div>

                    <div class="col-xs-12 text-center" style="margin-top: 5px;">
                        <button type="button" class="btn btn-primary" ng-click="loadInfo();" ng-disabled="formNewLectura.$invalid">
                            Ingresar <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                        </button>
                    </div>


                    <h4>Datos Suministro:</h4>
                    <hr>

                    <div class="col-md-6 col-xs-12" style="margin-bottom: 10px;">

                        <div class="col-xs-12" style="margin-top: 5px;">
                            <div class="input-group" style="border: 1px solid #337ab7; border-radius: 4px;">
                                <span class="input-group-addon" style="background-color: #337ab7; color: white;">Cliente: </span>
                                <input type="text" class="form-control" name="nombre_cliente"
                                       id="nombre_cliente" ng-model="nombre_cliente" disabled />
                            </div>
                        </div>

                        <div class="col-xs-12" style="margin-top: 5px;">
                            <div class="input-group" style="border: 1px solid #337ab7; border-radius: 4px;">
                                <span class="input-group-addon" style="background-color: #337ab7; color: white;">Barrio: </span>
                                <input type="text" class="form-control" name="barrio"
                                       id="barrio" ng-model="barrio" disabled />
                            </div>
                        </div>

                        <div class="col-xs-12" style="margin-top: 5px;">
                            <div class="input-group" style="border: 1px solid #337ab7; border-radius: 4px;">
                                <span class="input-group-addon" style="background-color: #337ab7; color: white;">Calle: </span>
                                <input type="text" class="form-control" name="calle"
                                       id="calle" ng-model="calle" disabled />
                            </div>
                        </div>

                        <div class="col-xs-12" style="margin-top: 5px;">
                            <div class="input-group" style="border: 1px solid #337ab7; border-radius: 4px;">
                                <span class="input-group-addon" style="background-color: #337ab7; color: white;">Tarifa: </span>
                                <input type="text" class="form-control" name="tarifa"
                                       id="tarifa" ng-model="tarifa" disabled />
                            </div>
                        </div>

                    </div>

                    <div class="col-md-6 col-xs-12">
                        <div class="col-xs-12" style="padding: 0;" ng-cloak>
                            <table class="table table-bordered">
                                <thead class="bg-primary">
                                <tr>
                                    <th><i class="fa fa-search" aria-hidden="true"></i> Lectura Anterior</th>
                                    <th><i class="fa fa-search" aria-hidden="true"></i> Lectura Actual</th>
                                    <th>Consumo (m3)</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr class="text-right">
                                    <td>{{lectura_anterior}}</td>
                                    <td>{{lectura_actual}}</td>
                                    <td>{{consumo}}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>

                <div class="col-xs-12" style="margin-top: 15px;">

                    <h4>Detalle de Consumo:</h4>
                    <hr>

                    <div class="col-sm-6 col-xs-12" style="margin-top: 5px;">

                        <div class="input-group" style="border: 1px solid #337ab7; border-radius: 4px;">
                            <span class="input-group-addon" style="background-color: #337ab7; color: white;">Meses Atrasados: </span>
                            <input type="text" class="form-control" name="meses_atrasados" id="meses_atrasados"
                                   ng-model="meses_atrasados" disabled />
                        </div>

                    </div>

                    <div class="col-xs-12" style="margin-top: 15px;">
                        <table class="table table-bordered table-striped table-hover">
                            <thead class="bg-primary">
                            <tr>
                                <th>Concepto</th>
                                <th>Total</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr ng-repeat="rubro in rubros" ng-cloak>
                                <td>{{rubro.nombreservicio}}</td>
                                <td class="text-right">{{(rubro.valor).toFixed(2)}}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="col-xs-12" style="margin-bottom: 15px;">
                    <div class="col-xs-6">
                        <button type="button" id="btn_save" class="btn btn-success" ng-click="confirmSave()" disabled>
                            Guardar <span class="glyphicon glyphicon-floppy-saved" aria-hidden="true"></span>
                        </button>
                    </div>
                    <div class="col-xs-6 text-right" ng-cloak>
                        <span style="font-size: 14px !important;" class="label label-primary">Total: {{total}}</span>
                    </div>
                </div>

            </form>

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
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            Cancelar <span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span>
                        </button>
                        <button type="button" class="btn btn-success" id="btn-save" ng-click="save()">
                            Aceptar <span class="glyphicon glyphicon-floppy-saved" aria-hidden="true"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" tabindex="-1" role="dialog" id="modalMessage">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header modal-header-info">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Información</h4>
                    </div>
                    <div class="modal-body">
                        <span>{{message}}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- MODAL PARA LA ACCION DE MOSTRAR MENSAJE DE CARGA -->
        <div class="modal fade" id="myModalProgressBar" data-keyboard="false" data-backdrop="static">
            <div class="modal-dialog" style="margin-top: 200px;">
                <div class="modal-content">
                    <div class="modal-body">
                        <p style="font-size: 12px !important; font-weight: bold;" id="text-demo-load">
                            ESPERE POR FAVOR!, ESTAMOS GUARDANDO LA LECTURA, CREANDO CORREO A ENVIAR.....
                        </p>
                        <div class="row" style="padding: 5px;">
                            <div class="progress">
                                <div id="bar" class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="0"
                                     aria-valuemin="0" aria-valuemax="100" style="width: 100%;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

    </body>


    <script src="<?= asset('app/lib/angular/angular.min.js') ?>"></script>
    <script src="<?= asset('app/lib/angular/angular-route.min.js') ?>"></script>
    <script src="<?= asset('js/jquery.min.js') ?>"></script>
    <script src="<?= asset('js/bootstrap.min.js') ?>"></script>

    <script src="<?= asset('app/lib/angular/ng-file-upload-shim.min.js') ?>"></script>
    <script src="<?= asset('app/lib/angular/ng-file-upload.min.js') ?>"></script>

    <script src="<?= asset('app/lib/angular/dirPagination.js') ?>"></script>

    <script src="<?= asset('js/moment.min.js') ?>"></script>
    <script src="<?= asset('js/es.js') ?>"></script>
    <script src="<?= asset('js/bootstrap-datetimepicker.min.js') ?>"></script>

    <script src="<?= asset('app/lib/angular/angucomplete-alt.min.js') ?>"></script>

    <!-- AngularJS Application Scripts -->
    <script src="<?= asset('app/app.js') ?>"></script>
    <script src="<?= asset('app/controllers/nuevaLecturaController.js') ?>"></script>

</html>