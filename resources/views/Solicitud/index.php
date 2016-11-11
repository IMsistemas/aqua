<!doctype html>
<html lang="es-ES" ng-app="softver-aqua">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Document</title>

        <link href="<?= asset('css/bootstrap.min.css') ?>" rel="stylesheet">
        <link href="<?= asset('css/font-awesome.min.css') ?>" rel="stylesheet">
        <link href="<?= asset('css/index.css') ?>" rel="stylesheet">
        <link href="<?= asset('css/bootstrap-datetimepicker.min.css') ?>" rel="stylesheet">
        <link href="<?= asset('css/style_generic_app.css') ?>" rel="stylesheet">

        <style>
            td{
                vertical-align: middle !important;
            }

            .datepicker{
                color: #000 !important;
            }
        </style>

    </head>

    <body>

        <div class="col-xs-12" ng-controller="solicitudController" style="margin-top: 2%;">

            <div class="col-xs-12">
                <div class="col-sm-4 col-xs-12">
                    <div class="form-group has-feedback">
                        <input type="text" class="form-control" id="search" placeholder="BUSCAR..." ng-model="search">
                        <span class="glyphicon glyphicon-search form-control-feedback" aria-hidden="true"></span>
                    </div>
                </div>
                <div class="col-sm-4 col-xs-12">
                    <div class="form-group">
                        <label for="t_estado" class="col-sm-5 control-label">Tipo Solicitud:</label>
                        <div class="col-sm-7">
                            <select class="form-control" name="t_estado" id="t_tipo_solicitud"
                                    ng-model="t_tipo_solicitud" ng-options="value.id as value.name for value in tipo"
                                    ng-change="searchByFilter()"> </select>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4 col-xs-12">
                    <div class="form-group">
                        <label for="t_estado" class="col-sm-4 control-label">Estado:</label>
                        <div class="col-sm-8">
                            <select class="form-control" name="t_estado" id="t_estado"
                                    ng-model="t_estado" ng-options="value.id as value.name for value in estados"
                                    ng-change="searchByFilter()"> </select>
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-xs-12">
                <table class="table table-responsive table-striped table-hover table-condensed">
                    <thead class="bg-primary">
                        <tr>
                            <th style="width: 10%;">Nro. Solicitud</th>
                            <th style="width: 10%;">Fecha</th>
                            <th>Cliente</th>
                            <th>Dirección</th>
                            <th style="width: 10%;">Teléfono</th>
                            <th style="width: 10%;">Tipo Solicitud</th>
                            <th style="width: 10%;">Estado</th>
                            <th style="width: 14%;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr ng-repeat="solicitud in solicitudes | filter : search" ng-cloak>
                            <td>{{solicitud.data.idsolicitud}}</td>
                            <td>{{solicitud.data.fechasolicitud | formatDate}}</td>
                            <td style="font-weight: bold;"><i class="fa fa-user fa-lg" aria-hidden="true"></i> {{solicitud.data.cliente.apellidos + ', ' + solicitud.data.cliente.nombres}}</td>
                            <td>{{solicitud.data.cliente.direcciondomicilio}}</td>
                            <td>{{solicitud.data.cliente.telefonoprincipaldomicilio}}</td>
                            <td>{{solicitud.tipo}}</td>
                            <td ng-if="solicitud.data.estaprocesada == true"><span class="label label-primary" style="font-size: 14px !important;">Procesada</span></td>
                            <td ng-if="solicitud.data.estaprocesada == false"><span class="label label-warning" style="font-size: 14px !important;">En Espera</span></td>
                            <td ng-if="solicitud.data.estaprocesada == true">
                                <button type="button" class="btn btn-info" id="btn_inform" ng-click="info(solicitud)" >
                                    <i class="fa fa-info-circle fa-lg" aria-hidden="true"></i>
                                </button>
                                <!--<button type="button" class="btn btn-primary" id="btn_process" ng-click="" disabled>
                                    <i class="fa fa-cogs fa-lg" aria-hidden="true"></i>
                                </button>-->
                                <span ng-if="solicitud.tipo == 'Suministro'">
                                    <button type="button" class="btn btn-default" id="btn_pdf" ng-click="" >
                                        <i class="fa fa-file-pdf-o fa-lg" aria-hidden="true" style="color: red !important;"></i>
                                    </button>
                                </span>

                            </td>
                            <td ng-if="solicitud.data.estaprocesada == false">
                                <button type="button" class="btn btn-info" id="btn_inform" disabled>
                                    <i class="fa fa-info-circle fa-lg" aria-hidden="true"></i>
                                </button>
                                <!--<button type="button" class="btn btn-primary" id="btn_process" ng-click="showModalProcesar(solicitud)" >
                                    <i class="fa fa-cogs fa-lg" aria-hidden="true"></i>
                                </button>-->
                                <span ng-if="solicitud.tipo == 'Suministro'">
                                    <button type="button" class="btn btn-default" id="btn_pdf" disabled>
                                        <i class="fa fa-file-pdf-o fa-lg" aria-hidden="true" style="color: red !important;"></i>
                                    </button>
                                </span>

                            </td>
                        </tr>
                    </tbody>
                </table>
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



            <div class="modal fade" tabindex="-1" role="dialog" id="modalActionMantenimiento">
                <div class="modal-dialog" role="document" style="width: 60%;">
                    <div class="modal-content">
                        <div class="modal-header modal-header-primary">

                            <div class="col-md-6 col-xs-12">
                                <h4 class="modal-title">Solicitud de Mantenimiento Nro: {{num_solicitud_mant}}</h4>
                            </div>
                            <div class="col-md-6 col-xs-12">
                                <div class="form-group">
                                    <h4 class="modal-title"><label for="t_fecha_mant" class="col-sm-6" style="font-weight: normal !important;">Fecha Ingreso:</label></h4>
                                    <div class="col-sm-5" style="padding: 0;">
                                        <input type="text" class="form-control input-sm datepicker" name="t_fecha_mant"
                                               id="t_fecha_mant" ng-model="t_fecha_mant" style="color: black !important;" disabled>
                                    </div>
                                    <div class="col-sm-1 col-xs-12 text-right" style="padding: 0;">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-body">
                            <form class="form-horizontal" name="formMant" novalidate="">
                                <div class="row">
                                    <div class="col-xs-12" style="padding: 2%; margin-top: -20px !important;">
                                        <fieldset ng-cloak>
                                            <legend style="font-size: 16px; font-weight: bold;">Datos del Cliente</legend>

                                            <div class="col-xs-12" style="padding: 0;">
                                                <div class="col-sm-6 col-xs-12">
                                            <span class="label label-info" style="font-size: 14px !important;">
                                                <i class="fa fa-star" aria-hidden="true"></i> RUC/CI:</span> {{documentoidentidad_cliente_mant}}
                                                </div>
                                                <div class="col-sm-6 col-xs-12">
                                            <span class="label label-info" style="font-size: 14px !important;">
                                                <i class="fa fa-user" aria-hidden="true"></i> CLIENTE:</span> {{nom_cliente_mant}}
                                                    <input type="hidden" ng-model="h_codigocliente_mant">
                                                </div>
                                            </div>
                                            <div class="col-xs-12" style="padding: 0; margin-top: 5px;">
                                                <div class="col-sm-6 col-xs-12">
                                            <span class="label label-default" style="font-size: 14px !important;">
                                                <i class="fa fa-map-marker" aria-hidden="true"></i> Dirección Domicilio:</span> {{direcc_cliente_mant}}
                                                </div>
                                                <div class="col-sm-6 col-xs-12">
                                            <span class="label label-default" style="font-size: 14px !important;">
                                                <i class="fa fa-phone" aria-hidden="true"></i> Teléfono Domicilio:</span> {{telf_cliente_mant}}
                                                </div>
                                            </div>
                                            <div class="col-xs-12" style="padding: 0; margin-top: 5px;">
                                                <div class="col-sm-6 col-xs-12">
                                            <span class="label label-default" style="font-size: 14px !important;">
                                                <i class="fa fa-mobile" aria-hidden="true"></i> Celular:</span> {{celular_cliente_mant}}
                                                </div>
                                                <div class="col-sm-6 col-xs-12">
                                            <span class="label label-default" style="font-size: 14px !important;">
                                                <i class="fa fa-phone" aria-hidden="true"></i> Teléfono Trabajo:</span> {{telf_trab_cliente_mant}}
                                                </div>
                                            </div>
                                        </fieldset>
                                    </div>

                                    <div class="col-xs-12" style="padding: 2%; margin-top: -25px !important;">
                                        <fieldset>
                                            <legend style="font-size: 16px; font-weight: bold;">Datos de Suministro</legend>

                                            <div class="col-xs-12" style="">
                                                <div class="col-sm-6 col-xs-12 form-group error text-left">
                                                    <label for="s_suministro_mant" class="col-sm-4 col-xs-12 control-label">Suministros:</label>
                                                    <div class="col-sm-8 col-xs-12" style="">
                                                        <select class="form-control" name="s_suministro_mant" id="s_suministro_mant"
                                                                ng-model="s_suministro_mant" ng-options="value.id as value.label for value in suministro_mant"
                                                                ng-change="showInfoSuministro()" ng-pattern="/^[1-9]+$/"></select>
                                                        <!--<span class="help-block error"
                                                              ng-show="formMant.s_suministro_mant.$invalid && formMant.s_suministro_mant.$touched">Suministro es requerida</span>-->
                                                        <span class="help-block error"
                                                              ng-show="formMant.s_suministro_mant.$invalid && formMant.s_suministro_mant.$error.pattern">
                                                            Seleccione un Suministro</span>
                                                    </div>
                                                </div>

                                                <div class="col-sm-6 col-xs-12" style="padding-left: 45px;">
                                            <span class="label label-default" style="!important; font-size: 14px !important;">
                                                <i class="fa fa-map-marker" aria-hidden="true"></i> Zona:</span> {{zona_mant}}
                                                </div>
                                            </div>

                                            <div class="col-xs-12" style="padding: 0;">
                                                <div class="col-sm-6 col-xs-12">
                                            <span class="label label-default" style="font-size: 14px !important;">
                                                <i class="fa fa-map-marker" aria-hidden="true"></i> Transversal:</span> {{transversal_mant}}
                                                </div>
                                                <div class="col-sm-6 col-xs-12">
                                            <span class="label label-default" style="font-size: 14px !important;">
                                                <i class="fa fa-list" aria-hidden="true"></i> Tarifa:</span> {{tarifa_mant}}
                                                </div>
                                            </div>

                                        </fieldset>
                                    </div>

                                    <div class="col-xs-12 form-group error" style="">
                                        <label for="t_observacion_mant" class="col-sm-2 col-xs-12 control-label" style="padding: 5px 0 5px 0;">Observación:</label>
                                        <div class="col-sm-10 col-xs-12">
                                            <textarea class="form-control" name="t_observacion_mant" id="t_observacion_mant" ng-model="t_observacion_mant" rows="2" ng-required="true"></textarea>
                                            <span class="help-block error"
                                                  ng-show="formMant.t_observacion_mant.$invalid && formMant.t_observacion_mant.$touched">La Observación es requerida</span>
                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">
                                Cancelar <span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span>
                            </button>
                            <button type="button" class="btn btn-success" id="btn-save-mant"
                                    ng-click="saveSolicitudMantenimiento()" ng-disabled="formMant.$invalid">
                                Guardar <span class="glyphicon glyphicon-floppy-saved" aria-hidden="true"></span>
                            </button>

                            <!--<button type="button" class="btn btn-success" id="btn-save-otro"
                                    ng-click="saveSolicitudOtro();" ng-disabled="formMant.$invalid">
                                Guardar <span class="glyphicon glyphicon-floppy-saved" aria-hidden="true"></span>
                            </button>-->

                            <button type="button" class="btn btn-primary" id="btn-process-mant"
                                    ng-click="procesarSolicitud('btn-process-mant')" disabled>
                                Procesar <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" tabindex="-1" role="dialog" id="modalActionOtro">
                <div class="modal-dialog" role="document" style="width: 60%;">
                    <div class="modal-content">
                        <div class="modal-header modal-header-primary">

                            <div class="col-md-6 col-xs-12">
                                <h4 class="modal-title">Otro Tipo de Solicitud Solicitud Nro: {{num_solicitud_otro}}</h4>
                            </div>
                            <div class="col-md-6 col-xs-12">
                                <div class="form-group">
                                    <h4 class="modal-title"><label for="t_fecha_process" class="col-sm-6" style="font-weight: normal !important;">Fecha Ingreso:</label></h4>
                                    <div class="col-sm-5" style="padding: 0;">
                                        <input type="text" class="form-control input-sm datepicker" name="t_fecha_otro"
                                               id="t_fecha_otro" ng-model="t_fecha_otro" style="color: black !important;" disabled>
                                    </div>
                                    <div class="col-sm-1 col-xs-12 text-right" style="padding: 0;">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-body">
                            <form class="form-horizontal" name="formProcessOtros" novalidate="">

                                <div class="row">
                                    <div class="col-xs-12" style="padding: 2%; margin-top: -20px !important;">
                                        <fieldset ng-cloak>
                                            <legend style="font-size: 16px; font-weight: bold;">Datos del Cliente</legend>

                                            <div class="col-xs-12" style="padding: 0;">
                                                <div class="col-sm-6 col-xs-12">
                                            <span class="label label-info" style="font-size: 14px !important;">
                                                <i class="fa fa-star" aria-hidden="true"></i> RUC/CI:</span> {{documentoidentidad_cliente_otro}}
                                                </div>
                                                <div class="col-sm-6 col-xs-12">
                                            <span class="label label-info" style="font-size: 14px !important;">
                                                <i class="fa fa-user" aria-hidden="true"></i> CLIENTE:</span> {{nom_cliente_otro}}
                                                    <input type="hidden" ng-model="h_codigocliente_otro">
                                                </div>
                                            </div>
                                            <div class="col-xs-12" style="padding: 0; margin-top: 5px;">
                                                <div class="col-sm-6 col-xs-12">
                                            <span class="label label-default" style="font-size: 14px !important;">
                                                <i class="fa fa-map-marker" aria-hidden="true"></i> Dirección Domicilio:</span> {{direcc_cliente_otro}}
                                                </div>
                                                <div class="col-sm-6 col-xs-12">
                                            <span class="label label-default" style="font-size: 14px !important;">
                                                <i class="fa fa-phone" aria-hidden="true"></i> Teléfono Domicilio:</span> {{telf_cliente_otro}}
                                                </div>
                                            </div>
                                            <div class="col-xs-12" style="padding: 0; margin-top: 5px;">
                                                <div class="col-sm-6 col-xs-12">
                                            <span class="label label-default" style="font-size: 14px !important;">
                                                <i class="fa fa-mobile" aria-hidden="true"></i> Celular:</span> {{celular_cliente_otro}}
                                                </div>
                                                <div class="col-sm-6 col-xs-12">
                                            <span class="label label-default" style="font-size: 14px !important;">
                                                <i class="fa fa-phone" aria-hidden="true"></i> Teléfono Trabajo:</span> {{telf_trab_cliente_otro}}
                                                </div>
                                            </div>
                                        </fieldset>
                                    </div>

                                    <div class="col-xs-12 form-group" style="">
                                        <label for="t_observacion_otro" class="col-sm-2 col-xs-12 control-label" style="padding: 5px 0 5px 0;">Descripción:</label>
                                        <div class="col-sm-10 col-xs-12">
                                            <textarea class="form-control" id="t_observacion_otro" ng-model="t_observacion_otro" rows="2" ng-required="true"></textarea>
                                            <span class="help-block error"
                                                  ng-show="formProcessOtros.t_observacion_otro.$invalid && formProcessOtros.t_observacion_otro.$touched">La Descripción es requerida</span>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">
                                Cancelar <span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span>
                            </button>
                            <button type="button" class="btn btn-success" id="btn-save-otro"
                                    ng-click="saveSolicitudOtro();" ng-disabled="formProcessOtros.$invalid">
                                Guardar <span class="glyphicon glyphicon-floppy-saved" aria-hidden="true"></span>
                            </button>
                            <button type="button" class="btn btn-primary" id="btn-process-otro"
                                    ng-click="procesarSolicitud('btn-process-otro')" disabled>
                                Procesar <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </body>

    <script src="<?= asset('app/lib/angular/angular.min.js') ?>"></script>
    <script src="<?= asset('app/lib/angular/angular-route.min.js') ?>"></script>
    <script src="<?= asset('js/jquery.min.js') ?>"></script>
    <script src="<?= asset('js/bootstrap.min.js') ?>"></script>

    <script src="<?= asset('js/moment.min.js') ?>"></script>
    <script src="<?= asset('js/es.js') ?>"></script>
    <script src="<?= asset('js/bootstrap-datetimepicker.min.js') ?>"></script>

    <script src="<?= asset('app/lib/angular/ng-file-upload-shim.min.js') ?>"></script>
    <script src="<?= asset('app/lib/angular/ng-file-upload.min.js') ?>"></script>

    <script src="<?= asset('app/app.js') ?>"></script>
    <script src="<?= asset('app/controllers/solicitudServicioController.js') ?>"></script>

</html>