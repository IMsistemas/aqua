<!Doctype html>
    <html lang="es-ES" ng-app="softver-aqua">
    <head>
        <meta charset="UTF-8">
 
        <title>Aqua-Nueva Lectura</title>

        <link href="<?= asset('css/bootstrap.min.css') ?>" rel="stylesheet">
        <link href="<?= asset('css/font-awesome.min.css') ?>" rel="stylesheet">
        <link href="<?= asset('css/bootstrap-datetimepicker.min.css') ?>" rel="stylesheet">
        <link href="<?= asset('css/style_generic_app.css') ?>" rel="stylesheet">

    </head>
    <body ng-controller="nuevaLecturaController">

        <div class="container" style="margin-top: 2%;">
            <fieldset>

                <legend style="padding-bottom: 10px;">
                    <span style="font-weight: bold;">NUEVA LECTURA</span>

                    <button type="button" id="btn_print_pdf" class="btn btn-primary" style="float: right; display: none; margin-top: -10px;" ng-click="exportToPDF(2)"
                            data-toggle="tooltip" data-placement="bottom" title="Imprimir Lectura" disabled>
                        <i class="fa fa-print fa-2x" aria-hidden="true"></i>
                    </button>
                    <button type="button" id="btn_export_pdf" class="btn btn-primary" style="float: right; display: none; margin-top: -10px; margin-right: 2px;" ng-click="exportToPDF(1)"
                            data-toggle="tooltip" data-placement="bottom" title="Exportar a PDF la Lectura"  disabled>
                        <i class="fa fa-file-pdf-o fa-2x" aria-hidden="true"></i>
                    </button>
                    <button type="button" class="btn btn-default" style="float: right; display: none;" ng-click="" disabled>
                        <i class="fa fa-file-excel-o fa-2x" aria-hidden="true"></i>
                    </button>

                </legend>


                <form class="form-horizontal" name="formNewLectura" novalidate>

                    <div class="col-xs-12">
                        <div class="col-xs-6">
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Lectura Nro:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="t_no_lectura" id="t_no_lectura"
                                           ng-model="t_no_lectura" disabled />
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="form-group error">
                                <label class="col-sm-4 control-label">Fecha Ingreso:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control datepicker" name="t_fecha_ing"
                                           id="t_fecha_ing" ng-model="t_fecha_ing" ng-required="true" disabled />
                                    <span class="help-block error"
                                          ng-show="formNewLectura.t_fecha_ing.$invalid && formNewLectura.t_fecha_ing.$touched">La fecha de ingreso es requerida</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12">
                        <fieldset>
                            <legend>Periodo:</legend>
                            <div class="row">
                                <div class="col-md-3 col-xs-6">
                                    <div class="form-group error">
                                        <label class="col-sm-4 control-label">Año:</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control datepicker_a" name="s_anno" id="s_anno" 
                                                ng-model="s_anno" ng-required="true" >
                                            <span class="help-block error"
                                                      ng-show="formNewLectura.s_anno.$invalid && formNewLectura.s_anno.$touched">El año es requerido</span>    
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-xs-6">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Mes:</label>
                                        <div class="col-sm-8">
                                            <select class="form-control" name="s_mes" id="s_mes" ng-model="s_mes"
                                                    ng-options="value.id as value.name for value in meses">
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-xs-6">
                                    <div class="form-group error">
                                        <label class="col-sm-6 control-label" >Suministro Nro:</label>
                                        <div class="col-sm-6">
                                            <input type="number" class="form-control" name="t_no_suministro"
                                                   id="t_no_suministro" ng-model="t_no_suministro" onkeypress="return isOnlyNumberPto(this, event);" 
                                                   ng-required="true" />
                                            <span class="help-block error"
                                                      ng-show="formNewLectura.t_no_suministro.$invalid && formNewLectura.t_no_suministro.$touched">El Nro. de Suministro es requerido</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-xs-6">
                                    <div class="form-group error">
                                        <label class="col-sm-4 control-label">Lectura:</label>
                                        <div class="col-sm-8">
                                            <input type="number" class="form-control" name="t_lectura" id="t_lectura"
                                                   ng-model="t_lectura" onkeypress="return isOnlyNumberPto(this, event);" ng-required="true" />
                                            <span class="help-block error"
                                                      ng-show="formNewLectura.t_lectura.$invalid && formNewLectura.t_lectura.$touched">El Nro de Lectura es requerido</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 text-center">
                                <button type="button" class="btn btn-primary" ng-click="loadInfo();" ng-disabled="formNewLectura.$invalid">
                                    Ingresar <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                                </button>
                            </div>
                        </fieldset>
                        
                        <fieldset>
                            <legend>Datos Suministro:</legend>
                            <div class="col-md-6 col-xs-12" style="margin-bottom: 10px;">
                                <div class="col-xs-12" style="background: #e3f2fd; border: solid 1px #e0e0e0; border-radius: 5px; padding: 10px;" ng-cloak>
                                    <p>
                                        <span style="font-weight: bold; font-size: 14px !important;" class="label label-default">
                                            <i class="fa fa-user" aria-hidden="true"></i> Cliente:
                                        </span>
                                        {{nombre_cliente}}
                                    </p>
                                    <p>
                                        <span style="font-weight: bold; font-size: 14px !important;" class="label label-default">
                                            <i class="fa fa-map-marker" aria-hidden="true"></i> Barrio:
                                        </span>
                                        {{barrio}}
                                    </p>
                                    <p>
                                        <span style="font-weight: bold; font-size: 14px !important;" class="label label-default">
                                            <i class="fa fa-map-marker" aria-hidden="true"></i> Calle:
                                        </span>
                                        {{calle}}
                                    </p>
                                    
                                    <span style="font-weight: bold; font-size: 14px !important;" class="label label-default">
                                        <i class="fa fa-list" aria-hidden="true"></i> Tarifa:
                                    </span>
                                    {{tarifa}}
                                    
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
                        </fieldset>
                    </div>

                    <div class="col-xs-12" style="margin-top: 15px;">
                        <fieldset>
                            <legend>Detalle de Consumo:</legend>

                            <div class="col-xs-12" ng-cloak>
                                <span style="font-weight: bold; font-size: 14px !important;" class="label label-default">
                                    <i class="fa fa-calendar" aria-hidden="true"></i> Meses Atrasados: {{meses_atrasados}}</span>
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

                        </fieldset>
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

    <script src="<?= asset('js/moment.min.js') ?>"></script>
    <script src="<?= asset('js/es.js') ?>"></script>
    <script src="<?= asset('js/bootstrap-datetimepicker.min.js') ?>"></script>

    <!-- AngularJS Application Scripts -->
    <script src="<?= asset('app/app.js') ?>"></script>
    <script src="<?= asset('app/controllers/nuevaLecturaController.js') ?>"></script>

</html>