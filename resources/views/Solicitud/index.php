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
                        <td>{{solicitud.no_solicitud}}</td>
                        <td>{{solicitud.fecha | formatDate}}</td>
                        <td style="font-weight: bold;"><i class="fa fa-user fa-lg" aria-hidden="true"></i> {{solicitud.cliente}}</td>
                        <td>{{solicitud.direccion}}</td>
                        <td>{{solicitud.telefono}}</td>
                        <td>{{solicitud.tipo}}</td>
                        <td ng-if="solicitud.estado == true"><span class="label label-primary" style="font-size: 14px !important;">Procesada</span></td>
                        <td ng-if="solicitud.estado == false"><span class="label label-warning" style="font-size: 14px !important;">En Espera</span></td>
                        <td ng-if="solicitud.estado == true">
                            <button type="button" class="btn btn-info" id="btn_inform" ng-click="info(solicitud)" >
                                <i class="fa fa-info-circle fa-lg" aria-hidden="true"></i>
                            </button>
                            <button type="button" class="btn btn-primary" id="btn_process" ng-click="" disabled>
                                <i class="fa fa-cogs fa-lg" aria-hidden="true"></i>
                            </button>

                            <span ng-if="solicitud.tipo == 'Riego'">
                                <button type="button" class="btn btn-default" id="btn_pdf" ng-click="" >
                                    <i class="fa fa-file-pdf-o fa-lg" aria-hidden="true" style="color: red !important;"></i>
                                </button>
                            </span>


                        </td>
                        <td ng-if="solicitud.estado == false">
                            <button type="button" class="btn btn-info" id="btn_inform" disabled>
                                <i class="fa fa-info-circle fa-lg" aria-hidden="true"></i>
                            </button>
                            <button type="button" class="btn btn-primary" id="btn_process" ng-click="showModalProcesar(solicitud)" >
                                <i class="fa fa-cogs fa-lg" aria-hidden="true"></i>
                            </button>
                            <span ng-if="solicitud.tipo == 'Riego'">
                                <button type="button" class="btn btn-default" id="btn_pdf" disabled>
                                    <i class="fa fa-file-pdf-o fa-lg" aria-hidden="true" style="color: red !important;"></i>
                                </button>
                            </span>

                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <div class="modal fade" tabindex="-1" role="dialog" id="modalProcesar">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header modal-header-info">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Confirmación</h4>
                        </div>
                        <div class="modal-body">
                            <span>
                                Desea procesar la Solicitud Nro: <strong>"{{num_solicitud_process}}"</strong>
                                del Cliente: <strong>"{{cliente_process}}"</strong> de Tipo: <strong>"{{tipo_process}}"</strong>...
                            </span>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">
                                Cancelar <span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span>
                            </button>
                            <button type="button" class="btn btn-primary" id="btn-save" ng-click="procesarSolicitud()">
                                Procesar <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" tabindex="-1" role="dialog" id="modalProcesarRiego">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header modal-header-info">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Confirmación</h4>
                        </div>
                        <div class="modal-body">
                            <span>
                                Desea procesar la Solicitud Nro: <strong>"{{num_solicitud_process}}"</strong>
                                del Cliente: <strong>"{{cliente_process}}"</strong> de Tipo: <strong>"{{tipo_process}}"</strong>...
                            </span>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">
                                Cancelar <span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span>
                            </button>
                            <button type="button" class="btn btn-primary" id="btn-save" ng-click="showSolicitudRiego()">
                                Procesar <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" tabindex="-1" role="dialog" id="modalProcesarSetNombre">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header modal-header-info">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Confirmación</h4>
                        </div>
                        <div class="modal-body">
                            <span>
                                Desea procesar la Solicitud Nro: <strong>"{{num_solicitud_process}}"</strong>
                                del Cliente: <strong>"{{cliente_process}}"</strong> de Tipo: <strong>"{{tipo_process}}"</strong>...
                            </span>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">
                                Cancelar <span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span>
                            </button>
                            <button type="button" class="btn btn-primary" id="btn-save" ng-click="showSolicitudSetN()">
                                Procesar <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" tabindex="-1" role="dialog" id="modalProcesarFraccion">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header modal-header-info">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Confirmación</h4>
                        </div>
                        <div class="modal-body">
                            <span>
                                Desea procesar la Solicitud Nro: <strong>"{{num_solicitud_process}}"</strong>
                                del Cliente: <strong>"{{cliente_process}}"</strong> de Tipo: <strong>"{{tipo_process}}"</strong>...
                            </span>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">
                                Cancelar <span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span>
                            </button>
                            <button type="button" class="btn btn-primary" id="btn-save" ng-click="showSolicitudFraccion()">
                                Procesar <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" tabindex="-1" role="dialog" id="modalInfoSolOtros">
                <div class="modal-dialog modal-sm" role="document">
                    <div class="modal-content">
                        <div class="modal-header modal-header-info">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Otra Solicitud Nro.: {{no_info_otro}}</h4>
                        </div>
                        <div class="modal-body">
                            <div class="col-xs-12 text-center">
                                <img class="img-thumbnail" src="<?= asset('img/solicitud.png') ?>" alt="">
                            </div>
                            <div class="row text-center">

                                <div class="col-xs-12">
                                    <span style="font-weight: bold">Ingresada el: </span>{{ingresada_info_otro}}
                                </div>
                                <div class="col-xs-12">
                                    <span style="font-weight: bold">Procesada el: </span>{{procesada_info_otro}}
                                </div>
                                <div class="col-xs-12">
                                    <span style="font-weight: bold">Cliente: </span>{{cliente_info_otro}}
                                </div>
                                <div class="col-xs-12">
                                    <span style="font-weight: bold">Descripción: </span>{{descripcion_info_otro}}
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" tabindex="-1" role="dialog" id="modalInfoSolRiego">
                <div class="modal-dialog modal-sm" role="document">
                    <div class="modal-content">
                        <div class="modal-header modal-header-info">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Solicitud de Riego Nro.: {{no_info_riego}}</h4>
                        </div>
                        <div class="modal-body">
                            <div class="col-xs-12 text-center">
                                <img class="img-thumbnail" src="<?= asset('img/solicitud.png') ?>" alt="">
                            </div>
                            <div class="row text-center">

                                <div class="col-xs-12">
                                    <span style="font-weight: bold">Ingresada el: </span>{{ingresada_info_riego}}
                                </div>
                                <div class="col-xs-12">
                                    <span style="font-weight: bold">Procesada el: </span>{{procesada_info_riego}}
                                </div>
                                <div class="col-xs-12">
                                    <span style="font-weight: bold">Cliente: </span>{{cliente_info_riego}}
                                </div>
                                <div class="col-xs-12">
                                    <span style="font-weight: bold">Terreno Nro: </span>{{noterreno_info_riego}}
                                </div>
                                <div class="col-xs-12">
                                    <span style="font-weight: bold">Junta Modular: </span>{{junta_info_riego}}
                                </div>
                                <div class="col-xs-12">
                                    <span style="font-weight: bold">Ubicado en la Toma: </span>{{toma_info_riego}}
                                </div>
                                <div class="col-xs-12">
                                    <span style="font-weight: bold">Canal: </span>{{canal_info_riego}}
                                </div>
                                <div class="col-xs-12">
                                    <span style="font-weight: bold">Derivación: </span>{{derivacion_info_riego}}
                                </div>
                                <div class="col-xs-12">
                                    <span style="font-weight: bold">Area: </span>{{area_info_riego}} (m2)
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" tabindex="-1" role="dialog" id="modalInfoSolSetName">
                <div class="modal-dialog modal-sm" role="document">
                    <div class="modal-content">
                        <div class="modal-header modal-header-info">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Solicitud de Cambio de Nombre Nro.: {{no_info_setN}}</h4>
                        </div>
                        <div class="modal-body">
                            <div class="col-xs-12 text-center">
                                <img class="img-thumbnail" src="<?= asset('img/solicitud.png') ?>" alt="">
                            </div>
                            <div class="row text-center">

                                <div class="col-xs-12">
                                    <span style="font-weight: bold">Ingresada el: </span>{{ingresada_info_setN}}
                                </div>
                                <div class="col-xs-12">
                                    <span style="font-weight: bold">Procesada el: </span>{{procesada_info_setN}}
                                </div>
                                <div class="col-xs-12">
                                    <span style="font-weight: bold">Cliente Anterior: </span>{{cliente_info_setN}}
                                </div>
                                <div class="col-xs-12">
                                    <span style="font-weight: bold">Cliente Actual: </span>{{cliente_a_info_setN}}
                                </div>
                                <div class="col-xs-12">
                                    <span style="font-weight: bold">Terreno Nro: </span>{{noterreno_info_setN}}
                                </div>
                                <div class="col-xs-12">
                                    <span style="font-weight: bold">Junta Modular: </span>{{junta_info_setN}}
                                </div>
                                <div class="col-xs-12">
                                    <span style="font-weight: bold">Ubicado en la Toma: </span>{{toma_info_setN}}
                                </div>
                                <div class="col-xs-12">
                                    <span style="font-weight: bold">Canal: </span>{{canal_info_setN}}
                                </div>
                                <div class="col-xs-12">
                                    <span style="font-weight: bold">Derivación: </span>{{derivacion_info_setN}}
                                </div>
                                <div class="col-xs-12">
                                    <span style="font-weight: bold">Area: </span>{{area_info_setN}} (m2)
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" tabindex="-1" role="dialog" id="modalInfoSolFraccion">
                <div class="modal-dialog modal-sm" role="document">
                    <div class="modal-content">
                        <div class="modal-header modal-header-info">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Solicitud de Fraccionamiento Nro.: {{no_info_fraccion}}</h4>
                        </div>
                        <div class="modal-body">
                            <div class="col-xs-12 text-center">
                                <img class="img-thumbnail" src="<?= asset('img/solicitud.png') ?>" alt="">
                            </div>
                            <div class="row text-center">

                                <div class="col-xs-12">
                                    <span style="font-weight: bold">Ingresada el: </span>{{ingresada_info_fraccion}}
                                </div>
                                <div class="col-xs-12">
                                    <span style="font-weight: bold">Procesada el: </span>{{procesada_info_fraccion}}
                                </div>
                                <div class="col-xs-12">
                                    <span style="font-weight: bold">Cliente Arrendador: </span>{{cliente_info_fraccion}}
                                </div>
                                <div class="col-xs-12">
                                    <span style="font-weight: bold">Cliente Arrendatario: </span>{{cliente_a_info_fraccion}}
                                </div>
                                <div class="col-xs-12">
                                    <span style="font-weight: bold">Area Arrendada: </span>{{area_info_fraccion}} (m2)
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- MODALS DE PROCESAR SOLICITUDES -->

            <div class="modal fade" tabindex="-1" role="dialog" id="modalActionOtro">
                <div class="modal-dialog" role="document" style="width: 60%;">
                    <div class="modal-content">
                        <div class="modal-header modal-header-primary">

                            <div class="col-md-6 col-xs-12">
                                <h4 class="modal-title">Otra Solicitud Nro: {{num_solicitud_otro}}</h4>
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
                                                    <span class="label label-default" style="font-size: 12px !important;">RUC/CI:</span> {{documentoidentidad_cliente_otro}}
                                                </div>
                                                <div class="col-sm-6 col-xs-12">
                                                    <span class="label label-default" style="font-size: 12px !important;">Cliente:</span> {{nom_cliente_otro}}
                                                    <input type="hidden" ng-model="h_codigocliente_otro">
                                                </div>
                                            </div>
                                            <div class="col-xs-12" style="padding: 0; margin-top: 5px;">
                                                <div class="col-sm-6 col-xs-12">
                                                    <span class="label label-default" style="font-size: 12px !important;">Dirección Domicilio:</span> {{direcc_cliente_otro}}
                                                </div>
                                                <div class="col-sm-6 col-xs-12">
                                                    <span class="label label-default" style="font-size: 12px !important;">Teléfono Domicilio:</span> {{telf_cliente_otro}}
                                                </div>
                                            </div>
                                            <div class="col-xs-12" style="padding: 0; margin-top: 5px;">
                                                <div class="col-sm-6 col-xs-12">
                                                    <span class="label label-default" style="font-size: 12px !important;">Celular:</span> {{celular_cliente_otro}}
                                                </div>
                                                <div class="col-sm-6 col-xs-12">
                                                    <span class="label label-default" style="font-size: 12px !important;">Teléfono Trabajo:</span> {{telf_trab_cliente_otro}}
                                                </div>
                                            </div>
                                        </fieldset>
                                    </div>

                                    <div class="col-xs-12 form-group" style="">
                                        <label for="t_derivacion" class="col-sm-2 col-xs-12 control-label" style="padding: 5px 0 5px 0;">Descripción:</label>
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
                                    ng-click="procesarSolicitudOtro()" >
                                Procesar <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" tabindex="-1" role="dialog" id="modalActionRiego">
                <div class="modal-dialog" role="document" style="width: 60%;">
                    <div class="modal-content">
                        <div class="modal-header modal-header-primary">

                            <div class="col-md-6 col-xs-12">
                                <h4 class="modal-title">Solicitud de Riego Nro: {{num_solicitud_riego}}</h4>
                            </div>
                            <div class="col-md-6 col-xs-12">
                                <div class="form-group">
                                    <h4 class="modal-title"><label for="t_fecha_process" class="col-sm-6" style="font-weight: normal !important;">Fecha Ingreso:</label></h4>
                                    <div class="col-sm-5" style="padding: 0;">
                                        <input type="text" class="form-control input-sm datepicker" name="t_fecha_process"
                                               id="t_fecha_process" ng-model="t_fecha_process" style="color: black !important;" disabled>
                                    </div>
                                    <div class="col-sm-1 col-xs-12 text-right" style="padding: 0;">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-body">
                            <form class="form-horizontal" name="formProcess" novalidate="">

                                <div class="row">
                                    <div class="col-xs-12" style="padding: 2%; margin-top: -20px !important;">
                                        <fieldset ng-cloak>
                                            <legend style="font-size: 16px; font-weight: bold;">Datos del Cliente</legend>

                                            <div class="col-xs-12" style="padding: 0;">
                                                <div class="col-sm-6 col-xs-12">
                                                    <span class="label label-default" style="font-size: 12px !important;">RUC/CI:</span> {{documentoidentidad_cliente}}
                                                </div>
                                                <div class="col-sm-6 col-xs-12">
                                                    <span class="label label-default" style="font-size: 12px !important;">Cliente:</span> {{nom_cliente}}
                                                    <input type="hidden" ng-model="h_codigocliente">
                                                </div>
                                            </div>
                                            <div class="col-xs-12" style="padding: 0; margin-top: 5px;">
                                                <div class="col-sm-6 col-xs-12">
                                                    <span class="label label-default" style="font-size: 12px !important;">Dirección Domicilio:</span> {{direcc_cliente}}
                                                </div>
                                                <div class="col-sm-6 col-xs-12">
                                                    <span class="label label-default" style="font-size: 12px !important;">Teléfono Domicilio:</span> {{telf_cliente}}
                                                </div>
                                            </div>
                                            <div class="col-xs-12" style="padding: 0; margin-top: 5px;">
                                                <div class="col-sm-6 col-xs-12">
                                                    <span class="label label-default" style="font-size: 12px !important;">Celular:</span> {{celular_cliente}}
                                                </div>
                                                <div class="col-sm-6 col-xs-12">
                                                    <span class="label label-default" style="font-size: 12px !important;">Teléfono Trabajo:</span> {{telf_trab_cliente}}
                                                </div>
                                            </div>
                                        </fieldset>
                                    </div>

                                    <div class="col-xs-12" style="padding: 2%; margin-top: -25px !important;">
                                        <fieldset>
                                            <legend style="font-size: 16px; font-weight: bold;">Datos de Terreno</legend>

                                            <div class="col-xs-12" style="padding: 0; margin-top: -15px;">
                                                <div class="col-sm-6 col-xs-12 form-group error">
                                                    <label for="t_terreno" class="col-sm-4 col-xs-12 control-label">Nro. Terreno:</label>
                                                    <div class="col-sm-8 col-xs-12" style="padding-top: 10px;">
                                                        {{nro_terreno}}
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-xs-12" style="padding: 0;">
                                                <div class="col-sm-6 col-xs-12 form-group error">
                                                    <label for="t_tarifa" class="col-sm-4 col-xs-12 control-label">Tipo Cultivo:</label>
                                                    <div class="col-sm-8 col-xs-12">
                                                        <select class="form-control" name="t_tarifa" id="t_tarifa"
                                                                ng-model="t_tarifa" ng-options="value.id as value.label for value in tarifas"
                                                                ng-change="getCultivos()"></select><!--ng-change="showAddCultivo()"-->
                                                    </div>
                                                </div>

                                                <div class="col-sm-6 col-xs-12 form-group error">
                                                    <label for="t_cultivo" class="col-sm-4 col-xs-12 control-label">Cultivo:</label>
                                                    <div class="col-sm-8 col-xs-12">
                                                        <select class="form-control" name="t_cultivo" id="t_cultivo"
                                                                ng-model="t_cultivo" ng-options="value.id as value.label for value in cultivos">
                                                        </select><!--ng-change="showAddCultivo()"-->
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-xs-12" style="padding: 0;">
                                                <div class="col-sm-6 col-xs-12 form-group error">
                                                    <label for="t_area" class="col-sm-4 col-xs-12 control-label" >Area (m2):</label>
                                                    <div class="col-sm-8 col-xs-12">
                                                        <input type="text" class="form-control" name="t_area" id="t_area" ng-keypress="onlyNumber($event)"
                                                               ng-model="t_area" ng-required="true" ng-pattern="/^([0-9.]+)$/" ng-blur="calculate()">
                                                        <span class="help-block error"
                                                              ng-show="formProcess.t_area.$invalid && formProcess.t_area.$touched">El Area es requerido</span>
                                                        <span class="help-block error"
                                                              ng-show="formProcess.t_area.$invalid && formProcess.t_area.$error.pattern">El Area debe ser solo números</span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6 col-xs-12 form-group error" style="margin-top: 5px;">
                                                    <div class="col-sm-6 col-xs-12" ng-cloak>
                                                        <span class="label label-primary" style="font-size: 12px !important;">Caudal:</span>
                                                        <span style="font-size: 14px !important; font-weight: bold;">{{calculate_caudal}}</span>
                                                    </div>
                                                    <div class="col-sm-6 col-xs-12" ng-cloak>
                                                        <span class="label label-primary" style="font-size: 12px !important;">Valor Anual:</span>
                                                        <span style="font-size: 14px !important; font-weight: bold;">{{valor_total}}</span>
                                                    </div>
                                                </div>
                                            </div>

                                        </fieldset>
                                    </div>

                                    <div class="col-xs-12" style="padding: 2%; margin-top: -35px !important;">
                                        <fieldset>
                                            <legend style="font-size: 16px; font-weight: bold;">Datos de Ubicación</legend>
                                            <div class="col-sm-6 col-xs-12 form-group error">
                                                <label for="t_junta" class="col-sm-4 col-xs-12 control-label" style="padding: 5px 0 5px 0;">Junta Modular:</label>
                                                <div class="col-sm-8 col-xs-12">
                                                    <select class="form-control" name="t_junta" id="t_junta"
                                                            ng-model="t_junta" ng-options="value.id as value.label for value in barrios"
                                                            ng-change="getTomas()" ></select>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-xs-12 form-group error">
                                                <label for="t_toma" class="col-sm-4 col-xs-12 control-label" style="padding: 5px 0 5px 0;">Toma:</label>
                                                <div class="col-sm-8 col-xs-12">
                                                    <select class="form-control" name="t_toma" id="t_toma"
                                                            ng-model="t_toma" ng-options="value.id as value.label for value in tomas"
                                                            ng-change="getCanales()"></select>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-xs-12 form-group error">
                                                <label for="t_canal" class="col-sm-4 col-xs-12 control-label">Canal:</label>
                                                <div class="col-sm-8 col-xs-12">
                                                    <select class="form-control" name="t_canal" id="t_canal"
                                                            ng-model="t_canal" ng-options="value.id as value.label for value in canales"
                                                            ng-change="getDerivaciones()"></select>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-xs-12 form-group error">
                                                <label for="t_derivacion" class="col-sm-4 col-xs-12 control-label" style="padding: 5px 0 5px 0;">Derivación:</label>
                                                <div class="col-sm-8 col-xs-12">
                                                    <select class="form-control" name="t_derivacion" id="t_derivacion"
                                                            ng-model="t_derivacion" ng-options="value.id as value.label for value in derivaciones"></select>
                                                </div>
                                            </div>

                                        </fieldset>
                                    </div>

                                    <div class="col-xs-12 form-group" style="margin-top: -15px;">
                                        <label for="t_derivacion" class="col-sm-2 col-xs-12 control-label" style="padding: 5px 0 5px 0;">Observación:</label>
                                        <div class="col-sm-10 col-xs-12">
                                            <textarea class="form-control" id="t_observacion_riego" ng-model="t_observacion_riego" rows="2"></textarea>
                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">
                                Cancelar <span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span>
                            </button>
                            <button type="button" class="btn btn-success" id="btn-save-riego"
                                    ng-click="saveSolicitudRiego()" ng-disabled="formProcess.$invalid">
                                Guardar <span class="glyphicon glyphicon-floppy-saved" aria-hidden="true"></span>
                            </button>
                            <button type="button" class="btn btn-primary" id="btn-process-riego"
                                    ng-click="procesarSolicitudRiego()">
                                Procesar <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" tabindex="-1" role="dialog" id="modalActionSetNombre">
                <div class="modal-dialog" role="document" style="width: 60%;">
                    <div class="modal-content">
                        <div class="modal-header modal-header-primary">

                            <div class="col-md-6 col-xs-12">
                                <h4 class="modal-title">Solicitud de Cambio de Nombre Nro: {{num_solicitud_setnombre}}</h4>
                            </div>
                            <div class="col-md-6 col-xs-12">
                                <div class="form-group">
                                    <h4 class="modal-title"><label for="t_fecha_process" class="col-sm-6" style="font-weight: normal !important;">Fecha Ingreso:</label></h4>
                                    <div class="col-sm-5" style="padding: 0;">
                                        <input type="text" class="form-control input-sm datepicker" name="t_fecha_setnombre"
                                               id="t_fecha_setnombre" ng-model="t_fecha_setnombre" style="color: black !important;" disabled>
                                    </div>
                                    <div class="col-sm-1 col-xs-12 text-right" style="padding: 0;">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-body">
                            <form class="form-horizontal" name="formSetNombre" novalidate="">

                                <div class="row">
                                    <div class="col-xs-12" style="padding: 2%; margin-top: -20px !important;">
                                        <fieldset ng-cloak>
                                            <legend style="font-size: 16px; font-weight: bold;">Datos del Cliente actual</legend>

                                            <div class="col-xs-12" style="padding: 0;">
                                                <div class="col-sm-6 col-xs-12">
                                                    <span class="label label-default" style="font-size: 12px !important;">RUC/CI:</span> {{documentoidentidad_cliente_setnombre}}
                                                </div>
                                                <div class="col-sm-6 col-xs-12">
                                                    <span class="label label-default" style="font-size: 12px !important;">Cliente:</span> {{nom_cliente_setnombre}}
                                                    <input type="hidden" ng-model="h_codigocliente_setnombre">
                                                </div>
                                            </div>
                                            <div class="col-xs-12" style="padding: 0; margin-top: 5px;">
                                                <div class="col-sm-6 col-xs-12">
                                                    <span class="label label-default" style="font-size: 12px !important;">Dirección Domicilio:</span> {{direcc_cliente_setnombre}}
                                                </div>
                                                <div class="col-sm-6 col-xs-12">
                                                    <span class="label label-default" style="font-size: 12px !important;">Teléfono Domicilio:</span> {{telf_cliente_setnombre}}
                                                </div>
                                            </div>
                                            <div class="col-xs-12" style="padding: 0; margin-top: 5px;">
                                                <div class="col-sm-6 col-xs-12">
                                                    <span class="label label-default" style="font-size: 12px !important;">Celular:</span> {{celular_cliente_setnombre}}
                                                </div>
                                                <div class="col-sm-6 col-xs-12">
                                                    <span class="label label-default" style="font-size: 12px !important;">Teléfono Trabajo:</span> {{telf_trab_cliente_setnombre}}
                                                </div>
                                            </div>
                                        </fieldset>
                                    </div>

                                    <div class="col-xs-12" style="padding: 2%; margin-top: -25px !important;">
                                        <fieldset>
                                            <legend style="font-size: 16px; font-weight: bold;">Datos de Terreno</legend>

                                            <div class="col-xs-12" style="">
                                                <div class="col-sm-6 col-xs-12 form-group">
                                                    <label for="t_terreno" class="col-sm-4 col-xs-12 control-label">Terrenos:</label>
                                                    <div class="col-sm-8 col-xs-12" style="">
                                                        <select class="form-control" name="t_terrenos_setnombre" id="t_terrenos_setnombre"
                                                                ng-model="t_terrenos_setnombre" ng-options="value.id as value.label for value in terrenos_setN"
                                                                ng-change="searchInfoTerreno()"></select>
                                                    </div>
                                                </div>

                                                <div class="col-sm-6 col-xs-12" style="padding-left: 45px;">
                                                    <span class="label label-default" style="!important; font-size: 12px !important;">Junta Modular:</span> {{junta_setnombre}}
                                                </div>
                                            </div>

                                            <div class="col-xs-12" style="padding: 0;">
                                                <div class="col-sm-6 col-xs-12">
                                                    <span class="label label-default" style="font-size: 12px !important;">Toma:</span> {{toma_setnombre}}
                                                </div>
                                                <div class="col-sm-6 col-xs-12">
                                                    <span class="label label-default" style="font-size: 12px !important;">Canal:</span> {{canal_setnombre}}
                                                </div>
                                            </div>

                                            <div class="col-xs-12" style="padding: 0; margin-top: 5px;">
                                                <div class="col-sm-6 col-xs-12">
                                                    <span class="label label-default" style="font-size: 12px !important;">Derivación:</span> {{derivacion_setnombre}}
                                                </div>
                                                <div class="col-sm-6 col-xs-12">
                                                    <span class="label label-default" style="font-size: 12px !important;">Tipo Cultivo:</span> {{cultivo_setnombre}}
                                                </div>
                                            </div>

                                            <div class="col-xs-12" style="padding: 0; margin-top: 5px;">
                                                <div class="col-sm-6 col-xs-12">
                                                    <span class="label label-default" style="font-size: 12px !important;">Area:</span> {{area_setnombre}} m2
                                                </div>

                                                <div class="col-sm-6 col-xs-12">
                                                    <span class="label label-default" style="font-size: 12px !important;">Caudal:</span> {{caudal_setnombre}}
                                                </div>
                                            </div>

                                        </fieldset>
                                    </div>

                                    <div class="col-xs-12" style="padding: 2%; margin-top: -20px !important;">
                                        <fieldset>
                                            <legend style="font-size: 16px; font-weight: bold;">Datos del nuevo Cliente</legend>

                                            <div class="col-xs-12" style="padding: 0;">
                                                <div class="col-sm-6 col-xs-12 form-group">

                                                    <label for="t_terreno" class="col-sm-4 col-xs-12 control-label">RUC/CI:</label>
                                                    <div class="col-sm-8 col-xs-12" style="">
                                                        <select class="form-control"
                                                                name="t_ident_new_client_setnombre" id="t_ident_new_client_setnombre"
                                                                ng-model="t_ident_new_client_setnombre" ng-options="value.id as value.label for value in clientes_setN"
                                                                ng-change="getClienteByIdentify()"></select>
                                                    </div>

                                                </div>
                                                <div class="col-sm-6 col-xs-12" style="padding-left: 45px;">
                                                    <span class="label label-default" style="font-size: 12px !important;">Cliente:</span> {{nom_new_cliente_setnombre}}
                                                    <input type="hidden" ng-model="h_new_codigocliente_setnombre">
                                                </div>
                                            </div>
                                            <div class="col-xs-12" style="padding: 0;">
                                                <div class="col-sm-6 col-xs-12">
                                                    <span class="label label-default" style="font-size: 12px !important;">Dirección Domicilio:</span> {{direcc_new_cliente_setnombre}}
                                                </div>
                                                <div class="col-sm-6 col-xs-12">
                                                    <span class="label label-default" style="font-size: 12px !important;">Teléfono Domicilio:</span> {{telf_new_cliente_setnombre}}
                                                </div>
                                            </div>
                                            <div class="col-xs-12" style="padding: 0; margin-top: 5px;">
                                                <div class="col-sm-6 col-xs-12">
                                                    <span class="label label-default" style="font-size: 12px !important;">Celular:</span> {{celular_new_cliente_setnombre}}
                                                </div>
                                                <div class="col-sm-6 col-xs-12">
                                                    <span class="label label-default" style="font-size: 12px !important;">Teléfono Trabajo:</span> {{telf_trab_new_cliente_setnombre}}
                                                </div>
                                            </div>

                                        </fieldset>
                                    </div>
                                    <div class="col-xs-12 form-group" style="">
                                        <label for="t_derivacion" class="col-sm-2 col-xs-12 control-label" style="padding: 5px 0 5px 0;">Observación:</label>
                                        <div class="col-sm-10 col-xs-12">
                                            <textarea class="form-control" id="t_observacion_setnombre" ng-model="t_observacion_setnombre" rows="2"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">
                                Cancelar <span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span>
                            </button>
                            <button type="button" class="btn btn-success" id="btn-save-setnombre"
                                    ng-click="saveSolicitudSetName()" ng-disabled="formSetNombre.$invalid">
                                Guardar <span class="glyphicon glyphicon-floppy-saved" aria-hidden="true"></span>
                            </button>
                            <button type="button" class="btn btn-primary" id="btn-process-setnombre"
                                    ng-click="procesarSolicitudSetN()" >
                                Procesar <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" tabindex="-1" role="dialog" id="modalActionFraccion">
                <div class="modal-dialog" role="document" style="width: 60%;">
                    <div class="modal-content">
                        <div class="modal-header modal-header-primary">

                            <div class="col-md-6 col-xs-12">
                                <h4 class="modal-title">Solicitud de Fraccionamiento Nro: {{num_solicitud_fraccion}}</h4>
                            </div>
                            <div class="col-md-6 col-xs-12">
                                <div class="form-group">
                                    <h4 class="modal-title"><label for="t_fecha_fraccion" class="col-sm-6" style="font-weight: normal !important;">Fecha Ingreso:</label></h4>
                                    <div class="col-sm-5" style="padding: 0;">
                                        <input type="text" class="form-control input-sm datepicker" name="t_fecha_fraccion"
                                               id="t_fecha_fraccion" ng-model="t_fecha_fraccion" style="color: black !important;" disabled>
                                    </div>
                                    <div class="col-sm-1 col-xs-12 text-right" style="padding: 0;">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-body">
                            <form class="form-horizontal" name="formFraccion" novalidate="">

                                <div class="row">
                                    <div class="col-xs-12" style="padding: 2%; margin-top: -20px !important;">
                                        <fieldset ng-cloak>
                                            <legend style="font-size: 16px; font-weight: bold;">Datos del Cliente</legend>

                                            <div class="col-xs-12" style="padding: 0;">
                                                <div class="col-sm-6 col-xs-12">
                                                    <span class="label label-default" style="font-size: 12px !important;">RUC/CI:</span> {{documentoidentidad_cliente_fraccion}}
                                                </div>
                                                <div class="col-sm-6 col-xs-12">
                                                    <span class="label label-default" style="font-size: 12px !important;">Cliente:</span> {{nom_cliente_fraccion}}
                                                    <input type="hidden" ng-model="h_codigocliente_fraccion">
                                                </div>
                                            </div>
                                            <div class="col-xs-12" style="padding: 0; margin-top: 5px;">
                                                <div class="col-sm-6 col-xs-12">
                                                    <span class="label label-default" style="font-size: 12px !important;">Dirección Domicilio:</span> {{direcc_cliente_fraccion}}
                                                </div>
                                                <div class="col-sm-6 col-xs-12">
                                                    <span class="label label-default" style="font-size: 12px !important;">Teléfono Domicilio:</span> {{telf_cliente_fraccion}}
                                                </div>
                                            </div>
                                            <div class="col-xs-12" style="padding: 0; margin-top: 5px;">
                                                <div class="col-sm-6 col-xs-12">
                                                    <span class="label label-default" style="font-size: 12px !important;">Celular:</span> {{celular_cliente_fraccion}}
                                                </div>
                                                <div class="col-sm-6 col-xs-12">
                                                    <span class="label label-default" style="font-size: 12px !important;">Teléfono Trabajo:</span> {{telf_trab_cliente_fraccion}}
                                                </div>
                                            </div>
                                        </fieldset>
                                    </div>

                                    <div class="col-xs-12" style="padding: 2%; margin-top: -25px !important;">
                                        <fieldset>
                                            <legend style="font-size: 16px; font-weight: bold;">Datos de Terreno</legend>

                                            <div class="col-xs-12" style="padding: 0;">
                                                <div class="col-sm-6 col-xs-12 form-group">
                                                    <label for="t_terreno_fraccion" class="col-sm-4 col-xs-12 control-label">Terrenos:</label>
                                                    <div class="col-sm-8 col-xs-12" style="">
                                                        <select class="form-control" name="t_terrenos_fraccion" id="t_terrenos_fraccion"
                                                                ng-model="t_terrenos_fraccion" ng-options="value.id as value.label for value in terrenos_fraccion"
                                                                ng-change="searchInfoTerrenoFraccion()"></select>
                                                    </div>
                                                </div>

                                                <div class="col-sm-6 col-xs-12" style="padding-left: 45px;">
                                                    <span class="label label-default" style="font-size: 12px !important;">Junta Modular:</span> {{junta_fraccion}}
                                                </div>
                                            </div>

                                            <div class="col-xs-12" style="padding: 0; margin-top: 5px;">
                                                <div class="col-sm-6 col-xs-12">
                                                    <span class="label label-default" style="font-size: 12px !important;">Toma:</span> {{toma_fraccion}}
                                                </div>
                                                <div class="col-sm-6 col-xs-12">
                                                    <span class="label label-default" style="font-size: 12px !important;">Canal:</span> {{canal_fraccion}}
                                                </div>
                                            </div>

                                            <div class="col-xs-12" style="padding: 0; margin-top: 5px;">
                                                <div class="col-sm-6 col-xs-12">
                                                    <span class="label label-default" style="font-size: 12px !important;">Derivación:</span> {{derivacion_fraccion}}
                                                </div>
                                                <div class="col-sm-6 col-xs-12">
                                                    <span class="label label-default" style="font-size: 12px !important;">Tipo Cultivo:</span> {{cultivo_fraccion}}
                                                </div>
                                            </div>

                                            <div class="col-xs-12" style="padding: 0; margin-top: 5px;">
                                                <div class="col-sm-4 col-xs-12">
                                                    <span class="label label-default" style="font-size: 12px !important;">Area Actual:</span> {{area_fraccion}} m2
                                                </div>

                                                <div class="col-sm-4 col-xs-12">
                                                    <span class="label label-default" style="font-size: 12px !important;">Caudal:</span> {{caudal_fraccion}}
                                                </div>

                                                <div class="col-sm-4 col-xs-12">
                                                    <span class="label label-default" style="font-size: 12px !important;">Valor:</span> {{valor_fraccion}}
                                                </div>
                                            </div>

                                            <div class="col-xs-12" style="padding: 0; margin-top: 5px;">
                                                <div class="col-sm-4 col-xs-12">
                                                    <label for="t_area_fraccion" class="col-sm-5 col-xs-12 control-label" style="padding: 5px 0 5px 0;">Area Fracc.:</label>
                                                    <div class="col-sm-7 col-xs-12">
                                                        <input type="text" class="form-control" name="t_area_fraccion" id="t_area_fraccion" ng-keypress="onlyNumber($event)"
                                                               ng-model="t_area_fraccion" ng-required="true" ng-pattern="/^([0-9]+)$/" ng-blur="calculateFraccion()">
                                                        <span class="help-block error"
                                                              ng-show="formFraccion.t_area.$invalid && formProcess.t_area.$touched">El Area es requerido</span>
                                                        <span class="help-block error"
                                                              ng-show="formFraccion.t_area.$invalid && formProcess.t_area.$error.pattern">El Area debe ser solo números</span>
                                                    </div>
                                                </div>

                                                <div class="col-sm-4 col-xs-12">
                                                    <span class="label label-default" style="font-size: 12px !important;">Caudal:</span> {{caudal_new_fraccion}}
                                                </div>

                                                <div class="col-sm-4 col-xs-12">
                                                    <span class="label label-default" style="font-size: 12px !important;">Valor:</span> {{valor_new_fraccion}}
                                                </div>
                                            </div>

                                        </fieldset>
                                    </div>

                                    <div class="col-xs-12" style="padding: 2%; margin-top: -25px !important;">
                                        <fieldset>
                                            <legend style="font-size: 16px; font-weight: bold;">Datos del Nuevo Cliente</legend>

                                            <div class="col-xs-12" style="padding: 0;">
                                                <div class="col-sm-5 col-xs-12 form-group">
                                                    <label for="t_ident_new_client_fraccion" class="col-sm-4 col-xs-12 control-label">RUC/CI:</label>
                                                    <div class="col-sm-8 col-xs-12" style="">
                                                        <select class="form-control"
                                                                name="t_ident_new_client_fraccion" id="t_ident_new_client_fraccion"
                                                                ng-model="t_ident_new_client_fraccion" ng-options="value.id as value.label for value in clientes_fraccion"
                                                                ng-change="getClienteByIdentifyFraccion()"></select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-5 col-xs-12" style="padding-left: 45px;">
                                                    <span class="label label-default" style="font-size: 12px !important;">Cliente:</span> {{nom_new_cliente_fraccion}}
                                                    <input type="hidden" ng-model="h_new_codigocliente_fraccion">
                                                </div>
                                                <div class="col-sm-2 col-xs-12">
                                                    <input type="checkbox" class="" ng-model="ch_arriend_fraccion"> Arriendo
                                                </div>
                                            </div>

                                        </fieldset>
                                    </div>

                                    <div class="col-xs-12 form-group" style="margin-top: -15px;">
                                        <label for="t_derivacion" class="col-sm-2 col-xs-12 control-label" style="padding: 5px 0 5px 0;">Observación:</label>
                                        <div class="col-sm-10 col-xs-12">
                                            <textarea class="form-control" id="t_observacion_fraccion" ng-model="t_observacion_fraccion" rows="2"></textarea>
                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">
                                Cancelar <span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span>
                            </button>
                            <button type="button" class="btn btn-success" id="btn-save-fraccion"
                                    ng-click="saveSolicitudFraccion()" ng-disabled="formFraccion.$invalid">
                                Guardar <span class="glyphicon glyphicon-floppy-saved" aria-hidden="true"></span>
                            </button>
                            <button type="button" class="btn btn-primary" id="btn-process-fraccion"
                                    ng-click="procesarSolicitudFraccion()">
                                Procesar <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
                            </button>
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

        </div>

    </body>

    <script src="<?= asset('app/lib/angular/angular.min.js') ?>"></script>
    <script src="<?= asset('app/lib/angular/angular-route.min.js') ?>"></script>
    <script src="<?= asset('js/jquery.min.js') ?>"></script>
    <script src="<?= asset('js/bootstrap.min.js') ?>"></script>

    <script src="<?= asset('js/moment.min.js') ?>"></script>
    <script src="<?= asset('js/es.js') ?>"></script>
    <script src="<?= asset('js/bootstrap-datetimepicker.min.js') ?>"></script>

    <script src="<?= asset('app/app.js') ?>"></script>
    <script src="<?= asset('app/controllers/solicitudController.js') ?>"></script>

</html>