

    <div class="col-xs-12" ng-controller="solicitudController">

        <div class="col-xs-12">

            <h4>Gestión de Solicitudes</h4>

            <hr>

        </div>

        <div class="col-xs-12">
            <div class="col-sm-4 col-xs-12">
                <div class="form-group has-feedback">
                    <input type="text" class="form-control" id="search" placeholder="BUSCAR..." ng-model="search" ng-keyup="searchByFilter()">
                    <span class="glyphicon glyphicon-search form-control-feedback" aria-hidden="true"></span>
                </div>
            </div>
            <div class="col-sm-4 col-xs-12">

                <div class="input-group">
                    <span class="input-group-addon">Tipo Solicitud: </span>
                    <select class="form-control" name="t_estado" id="t_tipo_solicitud"
                            ng-model="t_tipo_solicitud" ng-options="value.id as value.name for value in tipo"
                            ng-change="searchByFilter()"> </select>
                </div>

            </div>
            <div class="col-sm-2 col-xs-12">

                <div class="input-group">
                    <span class="input-group-addon">Estado: </span>
                    <select class="form-control" name="t_estado" id="t_estado"
                            ng-model="t_estado" ng-options="value.id as value.name for value in estados"
                            ng-change="searchByFilter()"> </select>
                </div>

            </div>

            <div class="col-sm-2 col-xs-12">
                <div class="btn-group">
                    <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Imprimir <i class="glyphicon glyphicon-print"></i> <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a href="#" ng-click="printReportMant();" ><i class="glyphicon glyphicon-print"></i> Mantenimientos sin atender</a></li>
                    </ul>
                </div>
            </div>

        </div>

        <div class="col-xs-12" style="font-size: 12px;">
            <table class="table table-responsive table-striped table-hover table-condensed table-bordered">
                <thead class="bg-primary">
                    <tr>
                        <th style="width: 5%;" ng-click="sort('data.idsolicitud')">
                            NO.
                            <span class="glyphicon sort-icon" ng-show="sortKey=='data.idsolicitud'"
                                  ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                        </th>
                        <th style="width: 10%;" ng-click="sort('data.fechasolicitud')">
                            FECHA
                            <span class="glyphicon sort-icon" ng-show="sortKey=='data.fechasolicitud'"
                                  ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                        </th>
                        <th ng-click="sort('data.cliente.complete_name')">
                            CLIENTE
                            <span class="glyphicon sort-icon" ng-show="sortKey=='data.cliente.complete_name'"
                                  ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                        </th>
                        <th ng-click="sort('data.cliente.direcciondomicilio')">
                            DIRECCION
                            <span class="glyphicon sort-icon" ng-show="sortKey=='data.cliente.direcciondomicilio'"
                                  ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                        </th>
                        <th style="width: 10%;" ng-click="sort('data.cliente.telefonoprincipaldomicilio')">
                            TELEFONO
                            <span class="glyphicon sort-icon" ng-show="sortKey=='data.cliente.telefonoprincipaldomicilio'"
                                  ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                        </th>
                        <th style="width: 10%;">TIPO DE SOLICITUD</th>
                        <th style="width: 10%;">ESTADO</th>
                        <th style="width: 10%;">ACCIONES</th>
                    </tr>
                </thead>
                <tbody>
                    <!--<tr dir-paginate="solicitud in solicitudes |orderBy:sortKey:reverse |itemsPerPage:10 | filter : search" ng-cloak>-->
                    <tr dir-paginate="solicitud in solicitudes | orderBy:sortKey:reverse | itemsPerPage:8" total-items="totalItems" ng-cloak>
                        <td class="text-center">{{solicitud.idsolicitud}}</td>
                        <td>{{solicitud.fechasolicitud | formatDate}}</td>
                        <td style="font-weight: bold;">{{solicitud.lastnamepersona}} {{solicitud.namepersona}}</td>
                        <td>{{solicitud.direccion}}</td>
                        <td>{{solicitud.telefonoprincipaldomicilio}}</td>
                        <td>{{solicitud.tipo}}</td>
                        <td ng-if="solicitud.estadoprocesada == true" class="btn-success" style="font-weight: bold;">PROCESADA</td>
                        <td ng-if="solicitud.estadoprocesada == false" class="btn-warning" style="font-weight: bold;">EN ESPERA</td>
                        <td ng-if="solicitud.estadoprocesada == true">

                            <div class="btn-group" role="group" aria-label="...">
                                <button type="button" class="btn btn-info" id="btn_inform" ng-click="info(solicitud)" title="Información">
                                    <i class="fa fa-info-circle fa-lg" aria-hidden="true"></i>
                                </button>
                                <!--<button type="button" class="btn btn-primary" id="btn_process" ng-click="" disabled>
                                    <i class="fa fa-cogs fa-lg" aria-hidden="true"></i>
                                </button>-->
                                <span ng-if="solicitud.tipo == 'Suministro'">
                                    <button type="button" class="btn btn-default" id="btn_pdf" ng-click="viewPDF(solicitud.rutapdf)" title="Archivo de Solicitud">
                                        <i class="fa fa-file-pdf-o fa-lg" aria-hidden="true" style="color: red !important;"></i>
                                    </button>
                                </span>
                            </div>

                        </td>
                        <td ng-if="solicitud.estadoprocesada == false">

                            <div class="btn-group" role="group" aria-label="...">
                                <button type="button" class="btn btn-info" id="btn_inform" ng-click="info(solicitud)" >
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
                            </div>

                        </td>
                    </tr>
                </tbody>
            </table>
            <dir-pagination-controls

                    on-page-change="pageChanged(newPageNumber)"

                    template-url="dirPagination.html"

                    class="pull-right"
                    max-size="8"
                    direction-links="true"
                    boundary-links="true" >

            </dir-pagination-controls>
        </div>


        <div class="modal fade" tabindex="-1" role="dialog" id="modalMessage"  style="z-index: 999999;">
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


        <div class="modal fade" tabindex="-1" role="dialog" id="modalActionServicio">
            <div class="modal-dialog" role="document" style="width: 45%;">
                <div class="modal-content">
                    <div class="modal-header modal-header-primary">

                        <div class="col-md-5 col-xs-12">
                            <h4 class="modal-title">Solicitud de Servicio Nro: {{num_solicitud_servicio}}</h4>
                        </div>
                        <div class="col-md-7 col-xs-12">
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

                                        <div class="col-sm-6 col-xs-12">
                                            <div class="input-group">
                                                <span class="input-group-addon">RUC/CI: </span>
                                                <input type="text" class="form-control" name="documentoidentidad_cliente" id="documentoidentidad_cliente"
                                                       ng-model="documentoidentidad_cliente" readonly/>
                                            </div>
                                        </div>

                                        <div class="col-sm-6 col-xs-12">
                                            <div class="input-group">
                                                <span class="input-group-addon">Cliente: </span>
                                                <input type="text" class="form-control" name="nom_cliente" id="nom_cliente"
                                                       ng-model="nom_cliente" readonly/>
                                            </div>
                                            <input type="hidden" ng-model="h_codigocliente">
                                        </div>

                                        <div class="col-xs-12" style="margin-top: 5px;">
                                            <div class="input-group">
                                                <span class="input-group-addon">Dirección Domicilio: </span>
                                                <input type="text" class="form-control" name="direcc_cliente" id="direcc_cliente"
                                                       ng-model="direcc_cliente" readonly/>
                                            </div>
                                        </div>

                                        <div class="col-sm-6 col-xs-12" style="margin-top: 5px;">
                                            <div class="input-group">
                                                <span class="input-group-addon">Teléfono Domicilio: </span>
                                                <input type="text" class="form-control" name="telf_cliente" id="telf_cliente"
                                                       ng-model="telf_cliente" readonly/>
                                            </div>
                                        </div>

                                        <div class="col-sm-6 col-xs-12" style="margin-top: 5px;">
                                            <div class="input-group">
                                                <span class="input-group-addon">Celular: </span>
                                                <input type="text" class="form-control" name="celular_cliente" id="celular_cliente"
                                                       ng-model="celular_cliente" readonly/>
                                            </div>
                                        </div>

                                        <div class="col-sm-6 col-xs-12" style="margin-top: 5px;">
                                            <div class="input-group">
                                                <span class="input-group-addon">Teléfono Trabajo: </span>
                                                <input type="text" class="form-control" name="telf_trab_cliente" id="telf_trab_cliente"
                                                       ng-model="telf_trab_cliente" readonly/>
                                            </div>
                                        </div>

                                        <div class="col-sm-6 col-xs-12" style="margin-top: 5px;">
                                            <div class="input-group">
                                                <span class="input-group-addon">Tipo Cliente: </span>
                                                <input type="text" class="form-control" name="tipo_tipo_cliente" id="tipo_tipo_cliente"
                                                       ng-model="tipo_tipo_cliente" readonly/>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>

                                <div class="col-xs-12" style="padding: 2%;">
                                    <fieldset>
                                        <legend style="font-size: 16px; font-weight: bold;">Solicitud Servicios</legend>

                                        <table class="table table-responsive table-striped table-hover table-condensed">
                                            <thead class="bg-primary">
                                            <tr>
                                                <th class="text-center">SERVICIOS</th>
                                                <th class="text-center" style="width: 30%;">PRECIO</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr ng-repeat="item in services" ng-cloak>
                                                <td>{{item.nombreservicio}}</td>
                                                <td>
                                                    <input type="text" class="form-control" ng-model="item.valor" ng-keypress="onlyDecimal($event)">
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>

                                    </fieldset>
                                </div>
                            </div>

                        </form>
                    </div>
                    <div class="modal-footer" id="modal-footer-servicio">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            Cancelar <span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span>
                        </button>
                        <button type="button" class="btn btn-success" id="btn-save-servicio"
                                ng-click="saveSolicitudServicio()" ng-disabled="formProcess.$invalid">
                            Guardar <span class="glyphicon glyphicon-floppy-saved" aria-hidden="true"></span>
                        </button>
                        <button type="button" class="btn btn-primary" id="btn-process-servicio"
                                ng-click="procesarSolicitud('btn-process-servicio')" >
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

                                                <div class="input-group">
                                                    <span class="input-group-addon">RUC/CI: </span>
                                                    <input class="form-control" type="text" name="documentoidentidad_cliente_setnombre" id="documentoidentidad_cliente_setnombre"
                                                           ng-model="documentoidentidad_cliente_setnombre" disabled >
                                                </div>

                                            </div>

                                            <div class="col-sm-6 col-xs-12">

                                                <div class="input-group">
                                                    <span class="input-group-addon">Cliente: </span>
                                                    <input class="form-control" type="text" name="nom_cliente_setnombre" id="nom_cliente_setnombre"
                                                           ng-model="nom_cliente_setnombre" disabled >
                                                </div>

                                                <input type="hidden" ng-model="h_codigocliente_setnombre">
                                            </div>
                                        </div>

                                        <div class="col-xs-12" style="padding: 0; margin-top: 5px;">
                                            <div class="col-xs-12">

                                                <div class="input-group">
                                                    <span class="input-group-addon">Dirección Domicilio: </span>
                                                    <input class="form-control" type="text" name="direcc_cliente_setnombre" id="direcc_cliente_setnombre"
                                                           ng-model="direcc_cliente_setnombre" disabled >
                                                </div>

                                            </div>
                                        </div>

                                        <div class="col-xs-12" style="padding: 0; margin-top: 5px;">
                                            <div class="col-sm-4 col-xs-12">

                                                <div class="input-group">
                                                    <span class="input-group-addon">Celular: </span>
                                                    <input class="form-control" type="text" name="celular_cliente_setnombre" id="celular_cliente_setnombre"
                                                           ng-model="celular_cliente_setnombre" disabled >
                                                </div>

                                            </div>

                                            <div class="col-sm-4 col-xs-12">

                                                <div class="input-group">
                                                    <span class="input-group-addon">Teléfono Domicilio: </span>
                                                    <input class="form-control" type="text" name="telf_cliente_setnombre" id="telf_cliente_setnombre"
                                                           ng-model="telf_cliente_setnombre" disabled >
                                                </div>

                                            </div>

                                            <div class="col-sm-4 col-xs-12">

                                                <div class="input-group">
                                                    <span class="input-group-addon">Teléfono Trabajo: </span>
                                                    <input class="form-control" type="text" name="telf_trab_cliente_setnombre" id="telf_trab_cliente_setnombre"
                                                           ng-model="telf_trab_cliente_setnombre" disabled >
                                                </div>

                                            </div>
                                        </div>


                                        <!--<div class="col-xs-12" style="padding: 0;">
                                            <div class="col-sm-6 col-xs-12">
                                            <span class="label label-info" style="font-size: 14px !important;">
                                                <i class="fa fa-star" aria-hidden="true"></i> RUC/CI:</span> {{documentoidentidad_cliente_setnombre}}
                                            </div>
                                            <div class="col-sm-6 col-xs-12">
                                            <span class="label label-info" style="font-size: 14px !important;">
                                                <i class="fa fa-user" aria-hidden="true"></i> CLIENTE:</span> {{nom_cliente_setnombre}}
                                                <input type="hidden" ng-model="h_codigocliente_setnombre">
                                            </div>
                                        </div>
                                        <div class="col-xs-12" style="padding: 0; margin-top: 5px;">
                                            <div class="col-sm-6 col-xs-12">
                                            <span class="label label-default" style="font-size: 14px !important;">
                                                <i class="fa fa-map-marker" aria-hidden="true"></i> Dirección Domicilio:</span> {{direcc_cliente_setnombre}}
                                            </div>
                                            <div class="col-sm-6 col-xs-12">
                                            <span class="label label-default" style="font-size: 14px !important;">
                                                <i class="fa fa-phone" aria-hidden="true"></i> Teléfono Domicilio:</span> {{telf_cliente_setnombre}}
                                            </div>
                                        </div>
                                        <div class="col-xs-12" style="padding: 0; margin-top: 5px;">
                                            <div class="col-sm-6 col-xs-12">
                                            <span class="label label-default" style="font-size: 14px !important;">
                                                <i class="fa fa-mobile" aria-hidden="true"></i> Celular:</span> {{celular_cliente_setnombre}}
                                            </div>
                                            <div class="col-sm-6 col-xs-12">
                                            <span class="label label-default" style="font-size: 14px !important;">
                                                <i class="fa fa-phone" aria-hidden="true"></i> Teléfono Trabajo:</span> {{telf_trab_cliente_setnombre}}
                                            </div>
                                        </div>-->
                                    </fieldset>
                                </div>

                                <div class="col-xs-12" style="padding: 2%; margin-top: -25px !important;">
                                    <fieldset>
                                        <legend style="font-size: 16px; font-weight: bold;">Datos de Suministro</legend>

                                        <div class="col-sm-6 col-xs-12 error">

                                            <div class="input-group">
                                                <span class="input-group-addon">Suministros: </span>
                                                <select class="form-control" name="s_suministro_setnombre" id="s_suministro_setnombre"
                                                        ng-model="s_suministro_setnombre" ng-options="value.id as value.label for value in suministro_setN"
                                                        ng-change="showInfoSuministroForSetName()" required ></select>
                                            </div>

                                            <span class="help-block error"
                                                  ng-show="formSetNombre.s_suministro_setnombre.$invalid && formSetNombre.s_suministro_setnombre.$touched">
                                                            Seleccione un Suministro</span>

                                        </div>

                                        <div class="col-sm-6 col-xs-12">

                                            <div class="input-group">
                                                <span class="input-group-addon">Zona: </span>
                                                <input class="form-control" type="text" name="zona_setnombre" id="zona_setnombre"
                                                       ng-model="zona_setnombre" disabled >
                                            </div>

                                        </div>

                                        <div class="col-sm-6 col-xs-12" style="margin-top: 5px;">

                                            <div class="input-group">
                                                <span class="input-group-addon">Transversal: </span>
                                                <input class="form-control" type="text" name="transversal_setnombre" id="transversal_setnombre"
                                                       ng-model="transversal_setnombre" disabled >
                                            </div>

                                        </div>

                                        <div class="col-sm-6 col-xs-12" style="margin-top: 5px;">

                                            <div class="input-group">
                                                <span class="input-group-addon">Tarifa: </span>
                                                <input class="form-control" type="text" name="tarifa_setnombre" id="tarifa_setnombre"
                                                       ng-model="tarifa_setnombre" disabled >
                                            </div>

                                        </div>

                                    </fieldset>
                                </div>

                                <div class="col-xs-12" style="padding: 2%; margin-top: -20px !important;">
                                    <fieldset>
                                        <legend style="font-size: 16px; font-weight: bold;">Datos del nuevo Cliente</legend>

                                        <div class="col-xs-12" style="padding: 0;">
                                            <div class="col-sm-6 col-xs-12 error">

                                                <div class="input-group">
                                                    <span class="input-group-addon">RUC/CI: </span>
                                                    <angucomplete-alt
                                                            id="s_ident_new_client_setnombre"
                                                            pause="400"
                                                            selected-object="showInfoClienteForSetName"

                                                            remote-url="{{API_URL}}cliente/getIdentifyClientes/"

                                                            title-field="numdocidentific"

                                                            minlength="1"
                                                            input-class="form-control"
                                                            match-class="highlight"
                                                            field-required="true"
                                                            input-name="s_ident_new_client_setnombre"

                                                            text-searching="Buscando RUC Clientes"
                                                            text-no-results="RUC no encontrado"
                                                            initial-value="numdocidentific"
                                                            disable-input="disableInput"
                                                    />
                                                </div>
                                                <input type="hidden" id="h_codigocliente_new" ng-model="h_codigocliente_new">

                                                <span class="help-block error"
                                                      ng-show="formSetNombre.s_ident_new_client_setnombre.$invalid && formSetNombre.s_ident_new_client_setnombre.$error.pattern">
                                                            Seleccione un Cliente</span>

                                            </div>
                                            <div class="col-sm-6 col-xs-12">

                                                <div class="input-group">
                                                    <span class="input-group-addon">Cliente: </span>
                                                    <input class="form-control" type="text" name="nom_new_cliente_setnombre" id="nom_new_cliente_setnombre"
                                                           ng-model="nom_new_cliente_setnombre" disabled >
                                                </div>
                                                <input type="hidden" ng-model="h_new_codigocliente_setnombre">

                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-xs-12" style="margin-top: 5px;">

                                            <div class="input-group">
                                                <span class="input-group-addon">Dirección Domicilio: </span>
                                                <input class="form-control" type="text" name="direcc_new_cliente_setnombre" id="direcc_new_cliente_setnombre"
                                                       ng-model="direcc_new_cliente_setnombre" disabled >
                                            </div>

                                        </div>

                                        <div class="col-sm-6 col-xs-12" style="margin-top: 5px;">

                                            <div class="input-group">
                                                <span class="input-group-addon">Teléfono Domicilio: </span>
                                                <input class="form-control" type="text" name="telf_new_cliente_setnombre" id="telf_new_cliente_setnombre"
                                                       ng-model="telf_new_cliente_setnombre" disabled >
                                            </div>

                                        </div>

                                        <div class="col-sm-6 col-xs-12" style="margin-top: 5px;">

                                            <div class="input-group">
                                                <span class="input-group-addon">Celular: </span>
                                                <input class="form-control" type="text" name="celular_new_cliente_setnombre" id="celular_new_cliente_setnombre"
                                                       ng-model="celular_new_cliente_setnombre" disabled >
                                            </div>

                                        </div>

                                        <div class="col-sm-6 col-xs-12" style="margin-top: 5px;">

                                            <div class="input-group">
                                                <span class="input-group-addon">Teléfono Trabajo: </span>
                                                <input class="form-control" type="text" name="telf_trab_new_cliente_setnombre" id="telf_trab_new_cliente_setnombre"
                                                       ng-model="telf_trab_new_cliente_setnombre" disabled >
                                            </div>

                                        </div>


                                    </fieldset>
                                </div>
                            </div>

                        </form>
                    </div>
                    <div class="modal-footer"  id="modal-footer-setnombre">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            Cancelar <span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span>
                        </button>
                        <button type="button" class="btn btn-success" id="btn-save-setnombre"
                                ng-click="saveSolicitudCambioNombre()" ng-disabled="formSetNombre.$invalid">
                            Guardar <span class="glyphicon glyphicon-floppy-saved" aria-hidden="true"></span>
                        </button>
                        <button type="button" class="btn btn-primary" id="btn-process-setnombre"
                                ng-click="procesarSolicitud()" >
                            Procesar <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!--<div class="modal fade" tabindex="-1" role="dialog" id="modalActionSetNombre">
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
                                        <span class="label label-info" style="font-size: 14px !important;">
                                            <i class="fa fa-star" aria-hidden="true"></i> RUC/CI:</span> {{documentoidentidad_cliente_setnombre}}
                                            </div>
                                            <div class="col-sm-6 col-xs-12">
                                        <span class="label label-info" style="font-size: 14px !important;">
                                            <i class="fa fa-user" aria-hidden="true"></i> CLIENTE:</span> {{nom_cliente_setnombre}}
                                                <input type="hidden" ng-model="h_codigocliente_setnombre">
                                            </div>
                                        </div>
                                        <div class="col-xs-12" style="padding: 0; margin-top: 5px;">
                                            <div class="col-sm-6 col-xs-12">
                                        <span class="label label-default" style="font-size: 14px !important;">
                                            <i class="fa fa-map-marker" aria-hidden="true"></i> Dirección Domicilio:</span> {{direcc_cliente_setnombre}}
                                            </div>
                                            <div class="col-sm-6 col-xs-12">
                                        <span class="label label-default" style="font-size: 14px !important;">
                                            <i class="fa fa-phone" aria-hidden="true"></i> Teléfono Domicilio:</span> {{telf_cliente_setnombre}}
                                            </div>
                                        </div>
                                        <div class="col-xs-12" style="padding: 0; margin-top: 5px;">
                                            <div class="col-sm-6 col-xs-12">
                                        <span class="label label-default" style="font-size: 14px !important;">
                                            <i class="fa fa-mobile" aria-hidden="true"></i> Celular:</span> {{celular_cliente_setnombre}}
                                            </div>
                                            <div class="col-sm-6 col-xs-12">
                                        <span class="label label-default" style="font-size: 14px !important;">
                                            <i class="fa fa-phone" aria-hidden="true"></i> Teléfono Trabajo:</span> {{telf_trab_cliente_setnombre}}
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>

                                <div class="col-xs-12" style="padding: 2%; margin-top: -25px !important;">
                                    <fieldset>
                                        <legend style="font-size: 16px; font-weight: bold;">Datos de Suministro</legend>

                                        <div class="col-xs-12" style="">
                                            <div class="col-sm-6 col-xs-12 form-group error">
                                                <label for="s_suministro_setnombre" class="col-sm-4 col-xs-12 control-label">Suministros:</label>
                                                <div class="col-sm-8 col-xs-12" style="">
                                                    <select class="form-control" name="s_suministro_setnombre" id="s_suministro_setnombre"
                                                            ng-model="s_suministro_setnombre" ng-options="value.id as value.label for value in suministro_setN"
                                                            ng-change="showInfoSuministroForSetName()" ng-pattern="/^[1-9]+$/"></select>
                                                    <span class="help-block error"
                                                          ng-show="formSetNombre.s_suministro_setnombre.$invalid && formSetNombre.s_suministro_setnombre.$error.pattern">
                                                        Seleccione un Cliente</span>
                                                </div>
                                            </div>

                                            <div class="col-sm-6 col-xs-12" style="padding-left: 45px;">
                                        <span class="label label-default" style="!important; font-size: 14px !important;">
                                            <i class="fa fa-map-marker" aria-hidden="true"></i> Zona:</span> {{zona_setnombre}}
                                            </div>
                                        </div>

                                        <div class="col-xs-12" style="padding: 0;">
                                            <div class="col-sm-6 col-xs-12">
                                        <span class="label label-default" style="font-size: 14px !important;">
                                            <i class="fa fa-map-marker" aria-hidden="true"></i> Transversal:</span> {{transversal_setnombre}}
                                            </div>
                                            <div class="col-sm-6 col-xs-12">
                                        <span class="label label-default" style="font-size: 14px !important;">
                                            <i class="fa fa-list" aria-hidden="true"></i> Tarifa:</span> {{tarifa_setnombre}}
                                            </div>
                                        </div>

                                    </fieldset>
                                </div>

                                <div class="col-xs-12" style="padding: 2%; margin-top: -20px !important;">
                                    <fieldset>
                                        <legend style="font-size: 16px; font-weight: bold;">Datos del nuevo Cliente</legend>

                                        <div class="col-xs-12" style="padding: 0;">
                                            <div class="col-sm-6 col-xs-12 form-group error">

                                                <label for="s_ident_new_client_setnombre" class="col-sm-4 col-xs-12 control-label">RUC/CI:</label>
                                                <div class="col-sm-8 col-xs-12" style="">
                                                    <select class="form-control"
                                                            name="s_ident_new_client_setnombre" id="s_ident_new_client_setnombre"
                                                            ng-model="s_ident_new_client_setnombre" ng-options="value.id as value.label for value in clientes_setN"
                                                            ng-change="showInfoClienteForSetName()" ng-pattern="/^[1-9]+$/"></select>
                                                    <span class="help-block error"
                                                          ng-show="formSetNombre.s_ident_new_client_setnombre.$invalid && formSetNombre.s_ident_new_client_setnombre.$error.pattern">
                                                        Seleccione un Cliente</span>
                                                </div>

                                            </div>
                                            <div class="col-sm-6 col-xs-12" style="padding-left: 45px;">
                                        <span class="label label-default" style="font-size: 14px !important;">
                                            <i class="fa fa-user" aria-hidden="true"></i> Cliente:</span> {{nom_new_cliente_setnombre}}
                                                <input type="hidden" ng-model="h_new_codigocliente_setnombre">
                                            </div>
                                        </div>
                                        <div class="col-xs-12" style="padding: 0;">
                                            <div class="col-sm-6 col-xs-12">
                                        <span class="label label-default" style="font-size: 14px !important;">
                                            <i class="fa fa-map-marker" aria-hidden="true"></i> Dirección Domicilio:</span> {{direcc_new_cliente_setnombre}}
                                            </div>
                                            <div class="col-sm-6 col-xs-12">
                                        <span class="label label-default" style="font-size: 14px !important;">
                                            <i class="fa fa-phone" aria-hidden="true"></i> Teléfono Domicilio:</span> {{telf_new_cliente_setnombre}}
                                            </div>
                                        </div>
                                        <div class="col-xs-12" style="padding: 0; margin-top: 5px;">
                                            <div class="col-sm-6 col-xs-12">
                                        <span class="label label-default" style="font-size: 14px !important;">
                                            <i class="fa fa-mobile" aria-hidden="true"></i> Celular:</span> {{celular_new_cliente_setnombre}}
                                            </div>
                                            <div class="col-sm-6 col-xs-12">
                                        <span class="label label-default" style="font-size: 14px !important;">
                                            <i class="fa fa-phone" aria-hidden="true"></i> Teléfono Trabajo:</span> {{telf_trab_new_cliente_setnombre}}
                                            </div>
                                        </div>

                                    </fieldset>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer" id="modal-footer-setnombre">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            Cancelar <span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span>
                        </button>
                        <button type="button" class="btn btn-success" id="btn-save-setnombre"
                                ng-click="saveSolicitudCambioNombre()" ng-disabled="formSetNombre.$invalid">
                            Guardar <span class="glyphicon glyphicon-floppy-saved" aria-hidden="true"></span>
                        </button>
                        <button type="button" class="btn btn-primary" id="btn-process-setnombre"
                                ng-click="procesarSolicitudSetName()" ng-disabled="formSetNombre.$invalid" >
                            Procesar <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div> -->


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

                                                <div class="input-group">
                                                    <span class="input-group-addon">RUC/CI: </span>
                                                    <input class="form-control" type="text" name="documentoidentidad_cliente_mant" id="documentoidentidad_cliente_mant"
                                                           ng-model="documentoidentidad_cliente_mant" disabled >
                                                </div>

                                            </div>

                                            <div class="col-sm-6 col-xs-12">

                                                <div class="input-group">
                                                    <span class="input-group-addon">Cliente: </span>
                                                    <input class="form-control" type="text" name="nom_cliente_mant" id="nom_cliente_mant"
                                                           ng-model="nom_cliente_mant" disabled >
                                                </div>

                                                <input type="hidden" ng-model="h_codigocliente">
                                            </div>
                                        </div>

                                        <div class="col-xs-12" style="padding: 0; margin-top: 5px;">
                                            <div class="col-xs-12">

                                                <div class="input-group">
                                                    <span class="input-group-addon">Dirección Domicilio: </span>
                                                    <input class="form-control" type="text" name="direcc_cliente_mant" id="direcc_cliente_mant"
                                                           ng-model="direcc_cliente_mant" disabled >
                                                </div>

                                            </div>
                                        </div>
                                        <div class="col-xs-12" style="padding: 0; margin-top: 5px;">
                                            <div class="col-sm-4 col-xs-12">

                                                <div class="input-group">
                                                    <span class="input-group-addon">Celular: </span>
                                                    <input class="form-control" type="text" name="celular_cliente_mant" id="celular_cliente_mant"
                                                           ng-model="celular_cliente_mant" disabled >
                                                </div>

                                            </div>

                                            <div class="col-sm-4 col-xs-12">

                                                <div class="input-group">
                                                    <span class="input-group-addon">Teléfono Domicilio: </span>
                                                    <input class="form-control" type="text" name="telf_cliente_mant" id="telf_cliente_mant"
                                                           ng-model="telf_cliente_mant" disabled >
                                                </div>

                                            </div>

                                            <div class="col-sm-4 col-xs-12">

                                                <div class="input-group">
                                                    <span class="input-group-addon">Teléfono Trabajo: </span>
                                                    <input class="form-control" type="text" name="telf_trab_cliente_mant" id="telf_trab_cliente_mant"
                                                           ng-model="telf_trab_cliente_mant" disabled >
                                                </div>

                                            </div>
                                        </div>



                                        <!--<div class="col-xs-12" style="padding: 0;">
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
                                        </div>-->
                                    </fieldset>
                                </div>

                                <div class="col-xs-12" style="padding: 2%; margin-top: -25px !important;">
                                    <fieldset>
                                        <legend style="font-size: 16px; font-weight: bold;">Datos de Suministro</legend>

                                        <div class="col-sm-6 col-xs-12 error ">

                                            <div class="input-group">
                                                <span class="input-group-addon">Suministros: </span>
                                                <select class="form-control" name="s_suministro_mant" id="s_suministro_mant"
                                                        ng-model="s_suministro_mant" ng-options="value.id as value.label for value in suministro_mant"
                                                        ng-change="showInfoSuministro()" required></select>
                                            </div>
                                            <span class="help-block error"
                                                  ng-show="formMant.s_suministro_mant.$invalid && formMant.s_suministro_mant.$touched">Suministro es requerida</span>
                                            <!--<span class="help-block error"
                                                  ng-show="formMant.s_suministro_mant.$invalid && formMant.s_suministro_mant.$error.pattern">
                                                            Seleccione un Suministro</span>-->

                                        </div>

                                        <div class="col-sm-6 col-xs-12">

                                            <div class="input-group">
                                                <span class="input-group-addon">Zona: </span>
                                                <input class="form-control" type="text" name="zona_mant" id="zona_mant"
                                                       ng-model="zona_mant" disabled >
                                            </div>

                                        </div>

                                        <div class="col-sm-6 col-xs-12" style="margin-top: 5px;">

                                            <div class="input-group">
                                                <span class="input-group-addon">Transversal: </span>
                                                <input class="form-control" type="text" name="transversal_mant" id="transversal_mant"
                                                       ng-model="transversal_mant" disabled >
                                            </div>


                                        </div>

                                        <div class="col-sm-6 col-xs-12" style="margin-top: 5px;">

                                            <div class="input-group">
                                                <span class="input-group-addon">Tarifa: </span>
                                                <input class="form-control" type="text" name="tarifa_mant" id="tarifa_mant"
                                                       ng-model="tarifa_mant" disabled >
                                            </div>

                                        </div>

                                    </fieldset>
                                </div>

                                <div class="col-xs-12">
                                    <div class="col-xs-12 error">
                                <textarea class="form-control" name="t_observacion_mant" id="t_observacion_mant" ng-model="t_observacion_mant" rows="3"
                                          ng-required="true" placeholder="Observación"></textarea>
                                        <span class="help-block error"
                                              ng-show="formMant.t_observacion_mant.$invalid && formMant.t_observacion_mant.$touched">La Observación es requerida</span>
                                    </div>
                                </div>


                            </div>

                        </form>
                    </div>
                    <div class="modal-footer" id="modal-footer-mant">
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

        <!--<div class="modal fade" tabindex="-1" role="dialog" id="modalActionMantenimiento">
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
                    <div class="modal-footer" id="modal-footer-mant">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            Cancelar <span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span>
                        </button>
                        <button type="button" class="btn btn-success" id="btn-save-mant"
                                ng-click="saveSolicitudMantenimiento()" ng-disabled="formMant.$invalid">
                            Guardar <span class="glyphicon glyphicon-floppy-saved" aria-hidden="true"></span>
                        </button>
                        <button type="button" class="btn btn-primary" id="btn-process-mant"
                                ng-click="procesarSolicitud('btn-process-mant')" >
                            Procesar <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>-->


        <div class="modal fade" tabindex="-1" role="dialog" id="modalActionOtro">
            <div class="modal-dialog" role="document" style="width: 60%;">
                <div class="modal-content">
                    <div class="modal-header modal-header-primary">

                        <div class="col-md-6 col-xs-12">
                            <h4 class="modal-title">Otro Tipo de Solicitud Nro: {{num_solicitud_otro}}</h4>
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

                                                <div class="input-group">
                                                    <span class="input-group-addon">RUC/CI: </span>
                                                    <input class="form-control" type="text" name="documentoidentidad_cliente_otro" id="documentoidentidad_cliente_otro"
                                                           ng-model="documentoidentidad_cliente_otro" disabled >
                                                </div>

                                            </div>

                                            <div class="col-sm-6 col-xs-12">

                                                <div class="input-group">
                                                    <span class="input-group-addon">Cliente: </span>
                                                    <input class="form-control" type="text" name="nom_cliente_otro" id="nom_cliente_otro"
                                                           ng-model="nom_cliente_otro" disabled >
                                                </div>

                                                <input type="hidden" ng-model="h_codigocliente">
                                            </div>
                                        </div>

                                        <div class="col-xs-12" style="padding: 0; margin-top: 5px;">
                                            <div class="col-xs-12">

                                                <div class="input-group">
                                                    <span class="input-group-addon">Dirección Domicilio: </span>
                                                    <input class="form-control" type="text" name="direcc_cliente_otro" id="direcc_cliente_otro"
                                                           ng-model="direcc_cliente_otro" disabled >
                                                </div>

                                            </div>
                                        </div>

                                        <div class="col-xs-12" style="padding: 0; margin-top: 5px;">
                                            <div class="col-sm-4 col-xs-12">

                                                <div class="input-group">
                                                    <span class="input-group-addon">Celular: </span>
                                                    <input class="form-control" type="text" name="celular_cliente_otro" id="celular_cliente_otro"
                                                           ng-model="celular_cliente_otro" disabled >
                                                </div>

                                            </div>

                                            <div class="col-sm-4 col-xs-12">

                                                <div class="input-group">
                                                    <span class="input-group-addon">Teléfono Domicilio: </span>
                                                    <input class="form-control" type="text" name="telf_cliente_otro" id="telf_cliente_otro"
                                                           ng-model="telf_cliente_otro" disabled >
                                                </div>

                                            </div>

                                            <div class="col-sm-4 col-xs-12">

                                                <div class="input-group">
                                                    <span class="input-group-addon">Teléfono Trabajo: </span>
                                                    <input class="form-control" type="text" name="telf_trab_cliente_otro" id="telf_trab_cliente_otro"
                                                           ng-model="telf_trab_cliente_otro" disabled >
                                                </div>

                                            </div>
                                        </div>


                                        <!--<div class="col-xs-12" style="padding: 0;">
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
                                        </div>-->
                                    </fieldset>
                                </div>

                                <div class="col-xs-12">
                                    <div class="col-xs-12 error">
                                    <textarea class="form-control" id="t_observacion_otro" ng-model="t_observacion_otro" rows="3"
                                              ng-required="true" placeholder="Descripción"></textarea>
                                        <span class="help-block error"
                                              ng-show="formProcessOtros.t_observacion_otro.$invalid && formProcessOtros.t_observacion_otro.$touched">La Descripción es requerida</span>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer" id="modal-footer-otro">
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


        <!--<div class="modal fade" tabindex="-1" role="dialog" id="modalActionOtro">
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
                    <div class="modal-footer" id="modal-footer-otro">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            Cancelar <span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span>
                        </button>
                        <button type="button" class="btn btn-success" id="btn-save-otro"
                                ng-click="saveSolicitudOtro();" ng-disabled="formProcessOtros.$invalid">
                            Guardar <span class="glyphicon glyphicon-floppy-saved" aria-hidden="true"></span>
                        </button>
                        <button type="button" class="btn btn-primary" id="btn-process-otro"
                                ng-click="procesarSolicitud('btn-process-otro')" >
                            Procesar <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>-->


        <!--<div class="modal fade" tabindex="-1" role="dialog" id="modalActionSuministro">
            <div class="modal-dialog" role="document" style="width: 60%;">
                <div class="modal-content">
                    <div class="modal-header modal-header-primary">

                        <div class="col-md-6 col-xs-12">
                            <h4 class="modal-title">Solicitud de Servicio Nro: {{num_solicitud_suministro}} - Agua Potable</h4>
                        </div>
                        <div class="col-md-6 col-xs-12">
                            <div class="form-group text-right">
                                <h4 class="modal-title">
                                    <label for="t_fecha_process" class="col-sm-11" style="font-weight: normal !important;">
                                        <i class="fa fa-user fa-lg" aria-hidden="true"></i> {{nom_cliente_suministro}}
                                    </label>
                                </h4>
                                <div class="col-sm-1 col-xs-12 text-right" style="padding: 0;">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal" name="formProcessSuministro" novalidate="">
                            <div class="row">
                                <div class="col-xs-12" style="padding: 2%; margin-top: -20px !important;">
                                    <fieldset ng-cloak>
                                        <legend style="font-size: 16px; font-weight: bold;">Datos Suministro</legend>

                                        <div class="col-sm-6 col-xs-12">

                                            <div class="input-group">
                                                <span class="input-group-addon"> Nro Suministro: </span>
                                                <input type="text" class="form-control" id="t_suministro_nro" ng-model="t_suministro_nro" disabled>
                                            </div>

                                        </div>
                                        <div class="col-sm-6 col-xs-12">

                                            <div class="input-group">
                                                <span class="input-group-addon">Tarifa: </span>
                                                <select name="s_suministro_tarifa" id="s_suministro_tarifa" class="form-control" ng-model="s_suministro_tarifa"
                                                        ng-options="value.id as value.label for value in tarifas" required></select>
                                            </div>
                                            <span class="help-block error"
                                                  ng-show="formProcessSuministro.s_suministro_tarifa.$invalid && formProcessSuministro.s_suministro_tarifa.$touched" >
                                                            Seleccione una Tarifa</span>
                                        </div>

                                        <div class="col-sm-6 col-xs-12" style="margin-top: 5px;">

                                            <div class="input-group">
                                                <span class="input-group-addon"> Zona: </span>
                                                <select name="s_suministro_zona" id="s_suministro_zona" class="form-control" ng-model="s_suministro_zona"
                                                        ng-options="value.id as value.label for value in barrios"
                                                        ng-change="getCalles()" required></select>
                                            </div>
                                            <span class="help-block error"
                                                  ng-show="formProcessSuministro.s_suministro_zona.$invalid && formProcessSuministro.s_suministro_zona.$touched">
                                                            Seleccione una Zona</span>
                                        </div>

                                        <div class="col-sm-6 col-xs-12" style="margin-top: 5px;">

                                            <div class="input-group">
                                                <span class="input-group-addon"> Transversal: </span>
                                                <select name="s_suministro_transversal" id="s_suministro_transversal" class="form-control" ng-model="s_suministro_transversal"
                                                        ng-options="value.id as value.label for value in calles" required></select>
                                            </div>
                                            <span class="help-block error"
                                                  ng-show="formProcessSuministro.s_suministro_transversal.$invalid && formProcessSuministro.s_suministro_transversal.$touched">
                                                            Seleccione una Transversal</span>
                                        </div>

                                        <div class="col-sm-6 col-xs-12 error" style="margin-top: 5px;">

                                            <div class="input-group">
                                                <span class="input-group-addon"> Dirección Instalac.: </span>
                                                <input type="text" class="form-control" name="t_suministro_direccion" id="t_suministro_direccion" ng-model="t_suministro_direccion"
                                                       ng-required="true">
                                            </div>
                                            <span class="help-block error"
                                                  ng-show="formProcessSuministro.t_suministro_direccion.$invalid && formProcessSuministro.t_suministro_direccion.$touched">
                                                        La Dirección es requerida</span>
                                        </div>

                                        <div class="col-sm-6 col-xs-12 error" style="margin-top: 5px;">

                                            <div class="input-group">
                                                <span class="input-group-addon"> Teléfono Instalac.: </span>
                                                <input type="text" class="form-control" name="t_suministro_telf" id="t_suministro_telf"
                                                       ng-model="t_suministro_telf" ng-required="true" ng-keypress="onlyNumber($event)" ng-minlength="9" ng-pattern="/^([0-9]+)$/">
                                            </div>

                                            <span class="help-block error"
                                                  ng-show="formProcessSuministro.t_suministro_telf.$invalid && formProcessSuministro.t_suministro_telf.$error.pattern">Solo números</span>
                                            <span class="help-block error"
                                                  ng-show="formProcessSuministro.t_suministro_telf.$invalid && formProcessSuministro.t_suministro_telf.$touched">
                                                        El Teléfono es requerido</span>
                                        </div>

                                    </fieldset>
                                </div>

                                <div class="col-xs-12" style="padding: 2%; margin-top: -25px;">
                                    <fieldset>
                                        <legend style="font-size: 16px; font-weight: bold;">Datos Costo</legend>

                                        <div class="col-xs-12" style="padding: 2%; margin-top: -35px;">
                                            <fieldset>
                                                <legend style="font-size: 14px; font-weight: bold;">Acometida</legend>

                                                <div class="col-sm-6 col-xs-12 error">

                                                    <div class="input-group">
                                                        <span class="input-group-addon"> Agua Potable: </span>
                                                        <input type="text" class="form-control" name="t_suministro_aguapotable" id="t_suministro_aguapotable" ng-model="t_suministro_aguapotable"
                                                               ng-blur="calculateTotalSuministro()" ng-required="true" ng-keypress="onlyDecimal($event)">
                                                    </div>
                                                    <span class="help-block error"
                                                          ng-show="formProcessSuministro.t_suministro_aguapotable.$invalid && formProcessSuministro.t_suministro_aguapotable.$touched">
                                                        El Agua Potable es requerido</span>

                                                </div>

                                                <div class="col-sm-6 col-xs-12 error">

                                                    <div class="input-group">
                                                        <span class="input-group-addon"> Alcantarillado: </span>
                                                        <input type="text" class="form-control" name="t_suministro_alcantarillado" id="t_suministro_alcantarillado" ng-model="t_suministro_alcantarillado"
                                                               ng-blur="calculateTotalSuministro()" ng-required="true" ng-keypress="onlyDecimal($event)">
                                                    </div>
                                                    <span class="help-block error"
                                                          ng-show="formProcessSuministro.t_suministro_alcantarillado.$invalid && formProcessSuministro.t_suministro_alcantarillado.$touched">
                                                        El Alcantarillado es requerido</span>
                                                </div>

                                                <div class="col-sm-6 col-xs-12 error" style="margin-top: 5px;">

                                                    <div class="input-group">
                                                        <span class="input-group-addon"> Garantía Apertura de Calle: </span>
                                                        <input type="text" class="form-control" name="t_suministro_garantia" id="t_suministro_garantia"
                                                               ng-model="t_suministro_garantia" ng-required="true" ng-keypress="onlyDecimal($event)">
                                                    </div>
                                                    <span class="help-block error"
                                                          ng-show="formProcessSuministro.t_suministro_garantia.$invalid && formProcessSuministro.t_suministro_garantia.$touched">
                                                        La Garantía es requerida</span>
                                                </div>
                                            </fieldset>
                                        </div>

                                        <div class="col-xs-12" style="padding: 2%; margin-top: -35px;">
                                            <fieldset>
                                                <legend style="font-size: 14px; font-weight: bold;">Medidor</legend>

                                                <div class="col-sm-4 col-xs-12">

                                                    <div class="input-group">
                                                        <span class="input-group-addon"> ¿Cliente no tiene Medidor?: </span>
                                                        <input type="checkbox" id="t_suministro_medidor" ng-model="t_suministro_medidor"
                                                               ng-click="deshabilitarMedidor()">
                                                    </div>

                                                </div>

                                                <div class="col-sm-4 col-xs-12">

                                                    <div class="input-group">
                                                        <span class="input-group-addon"> Marca: </span>
                                                        <input type="text" class="form-control" id="t_suministro_marca" ng-model="t_suministro_marca">
                                                    </div>

                                                    <input type="hidden" name="iditem" id="iditem" ng-model="iditem">

                                                </div>

                                                <div class="col-sm-4 col-xs-12 form-group">

                                                    <div class="input-group">
                                                        <span class="input-group-addon"> Costo: </span>
                                                        <input type="text" class="form-control" id="t_suministro_costomedidor" ng-model="t_suministro_costomedidor"
                                                               ng-blur="calculateTotalSuministro()" ng-keypress="onlyDecimal($event)" disabled>
                                                    </div>

                                                </div>

                                            </fieldset>
                                        </div>

                                        <div class="col-xs-12" style="padding: 2%; margin-top: -40px;">
                                            <fieldset>
                                                <legend style="font-size: 14px; font-weight: bold;">Total</legend>

                                                <div class="col-sm-6 col-xs-12 error">

                                                    <div class="input-group">
                                                        <span class="input-group-addon"> Cuota Inicial: </span>
                                                        <input type="text" class="form-control" name="t_suministro_cuota" id="t_suministro_cuota" ng-model="t_suministro_cuota"
                                                               ng-blur="calculateTotalSuministro()" ng-required="true" ng-keypress="onlyDecimal($event)">
                                                    </div>
                                                    <span class="help-block error"
                                                          ng-show="formProcessSuministro.t_suministro_cuota.$invalid && formProcessSuministro.t_suministro_cuota.$touched">
                                                        La Couta Inicial es requerida</span>
                                                </div>

                                                <div class="col-sm-6 col-xs-12 error">

                                                    <div class="input-group">
                                                        <span class="input-group-addon"> Crédito: </span>
                                                        <select name="s_suministro_credito" id="s_suministro_credito" class="form-control" ng-model="s_suministro_credito"
                                                                ng-options="value.id as value.label for value in creditos"
                                                                ng-change="calculateTotalSuministro()"  required></select>
                                                    </div>
                                                    <span class="help-block error"
                                                          ng-show="formProcessSuministro.s_suministro_credito.$invalid && formProcessSuministro.s_suministro_credito.$touched">
                                                            Seleccione un Crédito</span>
                                                </div>
                                            </fieldset>
                                        </div>

                                        <div class="col-xs-12">
                                            <div class="col-sm-6 col-xs-12 text-center" id="info_partial" style="font-size: 14px; display: none;">
                                                Total: <span style="font-weight: bold;">$ {{total_partial}}</span> a
                                                <span style="font-weight: bold;">{{credit_cant}}</span> meses plazo
                                            </div>
                                            <div class="col-sm-6 col-xs-12 text-center" id="info_total" style="font-size: 14px; display: none;">
                                                Cuotas de: <span style="font-weight: bold;">$ {{total_suministro}}</span> mensuales
                                            </div>
                                        </div>


                                    </fieldset>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer" id="modal-footer-suministro">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            Cancelar <span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span>
                        </button>
                        <button type="button" class="btn btn-success" id="btn-save-solsuministro"
                                ng-click="saveSolicitudSuministro()" ng-disabled="formProcessSuministro.$invalid">
                            Guardar <span class="glyphicon glyphicon-floppy-saved" aria-hidden="true"></span>
                        </button>
                        <button type="button" class="btn btn-primary" id="btn-process-solsuministro"
                                ng-click="procesarSolicitudSuministro()" disabled>
                            Procesar <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>-->

        <div class="modal fade" tabindex="-1" role="dialog" id="modalActionSuministro">
            <div class="modal-dialog" role="document" style="width: 60%;">
                <div class="modal-content">
                    <div class="modal-header modal-header-primary">

                        <div class="col-md-6 col-xs-12">
                            <h4 class="modal-title">Solicitud de Servicio Nro: {{num_solicitud_suministro}} - Agua Potable</h4>
                        </div>
                        <div class="col-md-6 col-xs-12">
                            <div class="form-group text-right">
                                <h4 class="modal-title">
                                    <label for="t_fecha_process" class="col-sm-11" style="font-weight: normal !important;">
                                        <i class="fa fa-user fa-lg" aria-hidden="true"></i> {{nom_cliente_suministro}}
                                    </label>
                                </h4>
                                <div class="col-sm-1 col-xs-12 text-right" style="padding: 0;">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal" name="formProcessSuministro" novalidate="">
                            <div class="row">
                                <div class="col-xs-12" style="padding: 2%; margin-top: -20px !important;">
                                    <fieldset ng-cloak>
                                        <legend style="font-size: 16px; font-weight: bold;">Datos Suministro</legend>

                                        <div class="col-sm-6 col-xs-12">

                                            <div class="input-group">
                                                <span class="input-group-addon"> Nro Suministro: </span>
                                                <input type="text" class="form-control" id="t_suministro_nro" ng-model="t_suministro_nro" disabled>
                                            </div>

                                        </div>
                                        <div class="col-sm-6 col-xs-12">

                                            <div class="input-group">
                                                <span class="input-group-addon">Tarifa: </span>
                                                <select name="s_suministro_tarifa" id="s_suministro_tarifa" class="form-control" ng-model="s_suministro_tarifa"
                                                        ng-options="value.id as value.label for value in tarifas" required></select>
                                            </div>
                                            <span class="help-block error"
                                                  ng-show="formProcessSuministro.s_suministro_tarifa.$invalid && formProcessSuministro.s_suministro_tarifa.$touched" >
                                                            Seleccione una Tarifa</span>
                                        </div>

                                        <div class="col-sm-6 col-xs-12" style="margin-top: 5px;">

                                            <div class="input-group">
                                                <span class="input-group-addon"> Zona: </span>
                                                <select name="s_suministro_zona" id="s_suministro_zona" class="form-control" ng-model="s_suministro_zona"
                                                        ng-options="value.id as value.label for value in barrios"
                                                        ng-change="getCalles()" required></select>
                                            </div>
                                            <span class="help-block error"
                                                  ng-show="formProcessSuministro.s_suministro_zona.$invalid && formProcessSuministro.s_suministro_zona.$touched">
                                                            Seleccione una Zona</span>
                                        </div>

                                        <div class="col-sm-6 col-xs-12" style="margin-top: 5px;">

                                            <div class="input-group">
                                                <span class="input-group-addon"> Transversal: </span>
                                                <select name="s_suministro_transversal" id="s_suministro_transversal" class="form-control" ng-model="s_suministro_transversal"
                                                        ng-options="value.id as value.label for value in calles" required></select>
                                            </div>
                                            <span class="help-block error"
                                                  ng-show="formProcessSuministro.s_suministro_transversal.$invalid && formProcessSuministro.s_suministro_transversal.$touched">
                                                            Seleccione una Transversal</span>
                                        </div>

                                        <div class="col-sm-6 col-xs-12 error" style="margin-top: 5px;">

                                            <div class="input-group">
                                                <span class="input-group-addon"> Dirección Instalac.: </span>
                                                <input type="text" class="form-control" name="t_suministro_direccion" id="t_suministro_direccion" ng-model="t_suministro_direccion"
                                                       ng-required="true">
                                            </div>
                                            <span class="help-block error"
                                                  ng-show="formProcessSuministro.t_suministro_direccion.$invalid && formProcessSuministro.t_suministro_direccion.$touched">
                                                        La Dirección es requerida</span>
                                        </div>

                                        <div class="col-sm-6 col-xs-12 error" style="margin-top: 5px;">

                                            <div class="input-group">
                                                <span class="input-group-addon"> Teléfono Instalac.: </span>
                                                <input type="text" class="form-control" name="t_suministro_telf" id="t_suministro_telf"
                                                       ng-model="t_suministro_telf" ng-required="true" ng-keypress="onlyNumber($event)" ng-minlength="9" ng-maxlength="9" ng-pattern="/^([0-9]+)$/">
                                            </div>

                                            <span class="help-block error"
                                                  ng-show="formProcessSuministro.t_suministro_telf.$invalid && formProcessSuministro.t_suministro_telf.$error.pattern">Solo números</span>
                                            <span class="help-block error"
                                                  ng-show="formProcessSuministro.t_suministro_telf.$invalid && formProcessSuministro.t_suministro_telf.$touched">
                                                        El Teléfono es requerido</span>
                                            <span class="help-block error"
                                                  ng-show="formProcessSuministro.t_suministro_telf.$invalid && formProcessSuministro.t_suministro_telf.$error.maxlength">La longitud máxima es de 9 números</span>
                                            <span class="help-block error"
                                                  ng-show="formProcessSuministro.t_suministro_telf.$invalid && formProcessSuministro.t_suministro_telf.$error.minlength">La longitud mínima es de 9 números</span>
                                        </div>

                                    </fieldset>
                                </div>

                                <div class="col-xs-12" style="padding: 2%; margin-top: -25px;">
                                    <fieldset>
                                        <legend style="font-size: 16px; font-weight: bold;">Datos Costo</legend>

                                        <div class="col-xs-12" style="padding: 2%; margin-top: -20px;">
                                            <fieldset>
                                                <legend style="font-size: 14px; font-weight: bold;">Acometida</legend>

                                                <div class="col-sm-6 col-xs-12 error">

                                                    <div class="input-group">
                                                        <span class="input-group-addon"> Agua Potable: </span>
                                                        <input type="text" class="form-control" name="t_suministro_aguapotable" id="t_suministro_aguapotable" ng-model="t_suministro_aguapotable"
                                                               ng-blur="calculateTotalSuministro()" ng-required="true" ng-keypress="onlyDecimal($event)">
                                                    </div>
                                                    <span class="help-block error"
                                                          ng-show="formProcessSuministro.t_suministro_aguapotable.$invalid && formProcessSuministro.t_suministro_aguapotable.$touched">
                                                        El Agua Potable es requerido</span>

                                                </div>

                                                <div class="col-sm-6 col-xs-12 error">

                                                    <div class="input-group">
                                                        <span class="input-group-addon"> Alcantarillado: </span>
                                                        <input type="text" class="form-control" name="t_suministro_alcantarillado" id="t_suministro_alcantarillado" ng-model="t_suministro_alcantarillado"
                                                               ng-blur="calculateTotalSuministro()" ng-required="true" ng-keypress="onlyDecimal($event)">
                                                    </div>
                                                    <span class="help-block error"
                                                          ng-show="formProcessSuministro.t_suministro_alcantarillado.$invalid && formProcessSuministro.t_suministro_alcantarillado.$touched">
                                                        El Alcantarillado es requerido</span>
                                                </div>

                                                <div class="col-sm-6 col-xs-12 error" style="margin-top: 5px;">

                                                    <div class="input-group">
                                                        <span class="input-group-addon"> Garantía Apertura de Calle: </span>
                                                        <input type="text" class="form-control" name="t_suministro_garantia" id="t_suministro_garantia"
                                                               ng-model="t_suministro_garantia" ng-required="true" ng-keypress="onlyDecimal($event)">
                                                    </div>
                                                    <span class="help-block error"
                                                          ng-show="formProcessSuministro.t_suministro_garantia.$invalid && formProcessSuministro.t_suministro_garantia.$touched">
                                                        La Garantía es requerida</span>
                                                </div>
                                            </fieldset>
                                        </div>

                                        <!--<div class="col-xs-12" style="padding: 2%; margin-top: -35px;">
                                            <fieldset>
                                                <legend style="font-size: 14px; font-weight: bold;">Medidor</legend>

                                                <div class="col-sm-4 col-xs-12">

                                                    <div class="input-group">
                                                        <span class="input-group-addon"> ¿Cliente no tiene Medidor?: </span>
                                                        <input type="checkbox" class="" id="t_suministro_medidor" ng-model="t_suministro_medidor"
                                                               ng-click="deshabilitarMedidor()">
                                                    </div>

                                                </div>

                                                <div class="col-sm-4 col-xs-12">

                                                    <div class="input-group">
                                                        <span class="input-group-addon"> Medidor: </span>
                                                        <input type="text" class="form-control" id="t_suministro_marca" ng-model="t_suministro_marca" disabled>
                                                    </div>

                                                    <input type="hidden" name="iditem" id="iditem" ng-model="iditem">

                                                </div>

                                                <div class="col-sm-4 col-xs-12 form-group">

                                                    <div class="input-group">
                                                        <span class="input-group-addon"> Costo: </span>
                                                        <input type="text" class="form-control" id="t_suministro_costomedidor" ng-model="t_suministro_costomedidor"
                                                               ng-blur="calculateTotalSuministro()" ng-keypress="onlyDecimal($event)" disabled>
                                                    </div>

                                                </div>

                                            </fieldset>
                                        </div>-->

                                        <div class="col-xs-12" style="padding: 2%; margin-top: -20px;">
                                            <fieldset>
                                                <legend style="font-size: 14px; font-weight: bold;">Total</legend>

                                                <div class="col-sm-4 col-xs-12 error">

                                                    <div class="input-group">
                                                        <span class="input-group-addon"> Cuota Inicial: </span>
                                                        <input type="text" class="form-control" name="t_suministro_cuota" id="t_suministro_cuota" ng-model="t_suministro_cuota"
                                                               ng-blur="calculateTotalSuministro()" ng-required="true" ng-keypress="onlyDecimal($event)">
                                                    </div>
                                                    <span class="help-block error"
                                                          ng-show="formProcessSuministro.t_suministro_cuota.$invalid && formProcessSuministro.t_suministro_cuota.$touched">
                                                        La Couta Inicial es requerida</span>
                                                </div>

                                                <div class="col-sm-4 col-xs-12 error">

                                                    <div class="input-group">
                                                        <span class="input-group-addon"> Crédito: </span>
                                                        <select name="s_suministro_credito" id="s_suministro_credito" class="form-control" ng-model="s_suministro_credito"
                                                                ng-options="value.id as value.label for value in creditos"
                                                                ng-change="calculateTotalSuministro()"  required></select>
                                                    </div>
                                                    <span class="help-block error"
                                                          ng-show="formProcessSuministro.s_suministro_credito.$invalid && formProcessSuministro.s_suministro_credito.$touched">
                                                            Seleccione un Crédito</span>
                                                </div>

                                                <div class="col-sm-4 col-xs-12 error">

                                                    <div class="input-group">
                                                        <span class="input-group-addon"> Forma Pago: </span>
                                                        <select name="s_suministro_formapago" id="s_suministro_formapago" class="form-control" ng-model="s_suministro_formapago"
                                                                required>
                                                            <option value="">-- Seleccione --</option>
                                                            <option value="1">EFECTIVO</option>
                                                            <option value="2">COUTAS</option>
                                                            <option value="3">RECARGAR</option>

                                                        </select>
                                                    </div>
                                                    <span class="help-block error"
                                                          ng-show="formProcessSuministro.s_suministro_formapago.$invalid && formProcessSuministro.s_suministro_formapago.$touched">
                                                            Seleccione una Forma de Pago</span>
                                                </div>
                                            </fieldset>
                                        </div>

                                        <div class="col-xs-12">
                                            <div class="col-sm-6 col-xs-12 text-center" id="info_partial" style="font-size: 14px; display: none;">
                                                Total: <span style="font-weight: bold;">$ {{total_partial}}</span> a
                                                <span style="font-weight: bold;">{{credit_cant}}</span> meses plazo
                                            </div>
                                            <div class="col-sm-6 col-xs-12 text-center" id="info_total" style="font-size: 14px; display: none;">
                                                Cuotas de: <span style="font-weight: bold;">$ {{total_suministro}}</span> mensuales
                                            </div>
                                        </div>


                                    </fieldset>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer" id="modal-footer-suministro">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            Cancelar <span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span>
                        </button>
                        <button type="button" class="btn btn-success" id="btn-save-solsuministro"
                                ng-click="saveSolicitudSuministro()" ng-disabled="formProcessSuministro.$invalid">
                            Guardar <span class="glyphicon glyphicon-floppy-saved" aria-hidden="true"></span>
                        </button>
                        <button type="button" class="btn btn-primary" id="btn-process-solsuministro"
                                ng-click="procesarSolicitudSuministro()" disabled>
                            Procesar <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" tabindex="-1" role="dialog" id="modalRegistroItem">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header modal-header-primary">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Registro de Productos</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">

                            <div class="col-xs-12">
                                <div class="form-group  has-feedback">
                                    <input type="text" class="form-control" id="" ng-model="searchContabilidad" placeholder="BUSCAR..." >
                                    <span class="glyphicon glyphicon-search form-control-feedback" ></span>
                                </div>
                            </div>

                            <div class="col-xs-12">
                                <table style="vertical-align: middle !important;" class="table table-responsive table-striped table-hover table-condensed table-bordered">
                                    <thead class="bg-primary">
                                    <tr>
                                        <th style="width: 5%;"></th>
                                        <th style="width: 20%;">FOTO</th>
                                        <th>PRODUCTO</th>
                                        <th style="width: 15%;">P. VENTA</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr ng-repeat="item in items | filter:searchContabilidad" ng-cloak >
                                        <td><input type="radio" name="itemprod" ng-click="selectItems(item)"></td>
                                        <td><img ng-src="{{item.foto}}" class="img-thumbnail" style="width: 100%;" alt=""></td>
                                        <td>{{item.nombreproducto}}</td>
                                        <td>{{item.precioventa}}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            Cancelar <span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span>
                        </button>
                        <button type="button" class="btn btn-primary" id="btn-ok" ng-click="assignItems()">
                            Aceptar <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="WPrint" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header btn-primary">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="WPrint_head"></h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-xs-12" id="bodyprint">

                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar <i class="glyphicon glyphicon glyphicon-ban-circle"></i> </button>
                    </div>
                </div>
            </div>
        </div>

    </div>
