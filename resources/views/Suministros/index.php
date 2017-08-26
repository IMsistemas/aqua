

<div ng-controller="suministrosController">

    <div class="col-xs-12">

        <div class="col-xs-12">

            <h4>Gestión de Suministros</h4>

            <hr>

        </div>

        <div class="col-xs-12"  style="margin-top: 5px;">

        <div class="col-sm-6 col-xs-8">
            <div class="form-group has-feedback">
                <input type="text" class="form-control" id="busqueda" placeholder="BUSCAR..." ng-model="busqueda">
                <span class="glyphicon glyphicon-search form-control-feedback" aria-hidden="true"></span>
            </div>
        </div>

        <div class="col-sm-3 col-xs-12">

            <div class="input-group">
                <span class="input-group-addon">Zonas: </span>
                <select id="s_zona" class="form-control" ng-model="s_zona" ng-change="getByFilter(1)"
                        ng-options="value.id as value.label for value in zonass"></select>
            </div>

        </div>

        <div class="col-sm-3 col-xs-12">

            <div class="input-group">
                <span class="input-group-addon">Transversales: </span>
                <select id="s_transversales" class="form-control" ng-model="s_transversales" ng-change="getByFilter(2)"
                        ng-options="value.id as value.label for value in transversaless"></select>
            </div>

        </div>


            <div class="cos-xs-12" style="font-size: 12px !important;">
            <table class="table table-responsive table-striped table-hover table-condensed table-bordered">
                <thead class="bg-primary">
                <tr>
                    <th style="width: 5%;">
                        <a href="#" style="text-decoration:none; color:white;" >NO.</a>
                    </th>
                    <th>
                        <a href="#" style="text-decoration:none; color:white;" >CLIENTE</a>
                    </th>
                    <th style="width: 12%;">
                        <a href="#" style="text-decoration:none; color:white;" >ZONA</a>
                    </th>
                    <th>
                        <a href="#" style="text-decoration:none; color:white;" >DIRECCION</a>
                    </th>
                    <th style="width: 10%;">
                        <a href="#" style="text-decoration:none; color:white;" >TELEFONO</a>
                    </th>
                    <th style="width: 12%;">
                        <a href="#" style="text-decoration:none; color:white;" >FACTURA</a>
                    </th>
                    <th style="width: 8%;">
                        <a href="#" style="text-decoration:none; color:white;" >VALOR</a>
                    </th>
                    <th style="width: 12%;">
                        <a href="#" style="text-decoration:none; color:white;" >ACCIONES</a>
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr dir-paginate="suministro in suministros| orderBy:sortKey:reverse|filter:busqueda|itemsPerPage:8" total-items="totalItems" ng-cloak>
                    <td>{{suministro.idsuministro}}</td>
                    <td>{{suministro.lastnamepersona}} {{suministro.namepersona}}</td>
                    <td>{{suministro.calle.barrio.namebarrio}}</td>
                    <td>{{suministro.direccionsumnistro}}</td>
                    <td>{{suministro.telefonosuministro}}</td>
                    <td>{{numFactura(suministro)}}</td>
                    <td class="text-right">{{suministro.valortotalsuministro}}</td>
                    <td>

                        <div class="btn-group" role="group" aria-label="...">
                            <button type="button" class="btn btn-info btn-sm" ng-click="getSuministro(suministro.idsuministro);" title="Información">
                                <i class="fa fa-lg fa-info-circle" aria-hidden="true"></i>
                            </button>
                            <button type="button" class="btn btn-warning btn-sm" ng-click="modalEditarSuministro(suministro);" title="Editar">
                                <i class="fa fa-lg fa-pencil-square-o" aria-hidden="true"></i>
                            </button>
                            <button type="button" class="btn btn-primary btn-sm" ng-click="loadViewFactura(suministro.idsuministro);" ng-disabled="suministro.cont_documentoventa" title="Facturar Suministro">
                                <i class="fa fa-lg fa-usd" aria-hidden="true"></i>
                            </button>
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
        </div>
    </div>

    <!--====================================MODALES===================================================================-->

    <!--====================================MODAL EDITAR SUMINISTROS==================================================-->
    <div class="modal fade" tabindex="-1" role="dialog" id="editar-suministro">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header modal-header-primary">
                    <div class="col-md-6 col-xs-12">
                        <h4 class="modal-title">Editar Suministro Nro. {{idsuministro}} </h4>
                    </div>
                    <div class="col-md-6 col-xs-12">
                        <div class="form-group">
                            <label for="fechaingreso" class="col-sm-5 control-label">Fecha de Ingreso:</label>
                            <div class="col-sm-6" style="padding: 0;">
                                <label >{{fechainstalacionsuministro}}</label>
                            </div>
                            <div class="col-sm-1 col-xs-12 text-right" style="padding: 0;">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-body">

                    <div class="row">
                        <form class="form-horizontal" name="formNuevaSolicitud" novalidate="">

                            <div class="col-xs-12">
                                <h4>Datos del Cliente</h4>
                                <hr>
                            </div>

                            <div class="col-sm-6 col-xs-12 error" style="margin-top: 5px;">

                                <div class="input-group">
                                    <span class="input-group-addon"> RUC / CI: </span>
                                    <input type="text" class="form-control" name="t_ruc" id="t_ruc" ng-model="t_ruc" disabled>
                                </div>
                            </div>

                            <div class="col-sm-6 col-xs-12 error" style="margin-top: 5px;">

                                <div class="input-group">
                                    <span class="input-group-addon"> Cliente: </span>
                                    <input type="text" class="form-control" name="t_cliente" id="t_cliente" ng-model="t_cliente" disabled>
                                </div>

                            </div>

                            <div class="col-xs-12" style="margin-top: 5px;">
                                <h4>Datos del Suministro</h4>
                                <hr>
                            </div>

                            <div class="col-sm-6 col-xs-12" style="margin-top: 5px;">

                                <div class="input-group">
                                    <span class="input-group-addon"> Zona: </span>
                                    <select name="s_suministro_zona" id="s_suministro_zona" class="form-control" ng-model="s_suministro_zona"
                                            ng-options="value.id as value.label for value in barrios"
                                            ng-change="getCalles()" required></select>
                                </div>
                                <span class="help-block error"
                                      ng-show="formNuevaSolicitud.s_suministro_zona.$invalid && formNuevaSolicitud.s_suministro_zona.$touched">
                                                                Seleccione una Zona</span>
                            </div>

                            <div class="col-sm-6 col-xs-12" style="margin-top: 5px;">

                                <div class="input-group">
                                    <span class="input-group-addon"> Transversal: </span>
                                    <select name="s_suministro_transversal" id="s_suministro_transversal" class="form-control" ng-model="s_suministro_transversal"
                                            ng-options="value.id as value.label for value in calles" required></select>
                                </div>
                                <span class="help-block error"
                                      ng-show="formNuevaSolicitud.s_suministro_transversal.$invalid && formNuevaSolicitud.s_suministro_transversal.$touched">
                                                                Seleccione una Transversal</span>
                            </div>

                            <div class="col-sm-6 col-xs-12 error" style="margin-top: 5px;">

                                <div class="input-group">
                                    <span class="input-group-addon"> Dirección Instalac.: </span>
                                    <input type="text" class="form-control" name="t_suministro_direccion" id="t_suministro_direccion" ng-model="t_suministro_direccion"
                                           ng-required="true">
                                </div>
                                <span class="help-block error"
                                      ng-show="formNuevaSolicitud.t_suministro_direccion.$invalid && formNuevaSolicitud.t_suministro_direccion.$touched">
                                                            La Dirección es requerida</span>
                            </div>

                            <div class="col-sm-6 col-xs-12 error" style="margin-top: 5px;">

                                <div class="input-group">
                                    <span class="input-group-addon"> Teléfono Instalac.: </span>
                                    <input type="text" class="form-control" name="t_suministro_telf" id="t_suministro_telf"
                                           ng-model="t_suministro_telf" ng-required="true" ng-keypress="onlyNumber($event)" ng-minlength="9" ng-maxlength="9" ng-pattern="/^([0-9]+)$/">
                                </div>

                                <span class="help-block error"
                                      ng-show="formNuevaSolicitud.t_suministro_telf.$invalid && formNuevaSolicitud.t_suministro_telf.$error.pattern">Solo números</span>
                                <span class="help-block error"
                                      ng-show="formNuevaSolicitud.t_suministro_telf.$invalid && formNuevaSolicitud.t_suministro_telf.$touched">
                                                            El Teléfono es requerido</span>
                                <span class="help-block error"
                                      ng-show="formNuevaSolicitud.t_suministro_telf.$invalid && formNuevaSolicitud.t_suministro_telf.$error.maxlength">La longitud máxima es de 9 números</span>
                                <span class="help-block error"
                                      ng-show="formNuevaSolicitud.t_suministro_telf.$invalid && formNuevaSolicitud.t_suministro_telf.$error.minlength">La longitud mínima es de 9 números</span>
                            </div>

                        </form>
                    </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        Cancelar <span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span>
                    </button>

                    <button type="button" class="btn btn-success" id="btn-save"
                            ng-click="editarSuministro()" ng-disabled="formNuevaSolicitud.$invalid">
                        Guardar <span class="glyphicon glyphicon-floppy-saved" aria-hidden="true"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>



    <!--====================================MODAL VER SUMINISTROS=====================================================-->
    <div class="modal fade" tabindex="-1" role="dialog" id="modalVerSuministro">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header modal-header-info">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Suministro No.{{numerosuministro}}</h4>
                </div>
                <div class="modal-body">
                    <div class="row text-center">
                        <div class="col-xs-12 text-center" style="font-size: 18px;">Instalado el: {{fechainstalacionsuministro}}</div>
                        <div class="col-xs-12">
                            <span style="font-weight: bold">Cliente:</span>{{nombre_apellido}}
                        </div>
                        <div class="col-xs-12">
                            <span style="font-weight: bold">Zona: </span>{{zona}}
                        </div>
                        <div class="col-xs-12">
                            <span style="font-weight: bold">Transversal: </span>{{transversal}}
                        </div>
                        <div class="col-xs-12">
                            <span style="font-weight: bold">Dirección Suministro: </span>{{direccionsumnistro}}
                        </div>
                        <div class="col-xs-12">
                            <span style="font-weight: bold">Teléfono: </span>{{telefonosuministro}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!--=================================Modal Confirmacion====================================-->
    <div class="modal fade" tabindex="-1" role="dialog" id="modalConfirmacion">
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


    <!--=================================Modal Error====================================-->
    <div class="modal fade" tabindex="-1" role="dialog" id="modalError">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header modal-header-danger">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Error</h4>
                </div>
                <div class="modal-body">
                    <span>{{mensajeError}}</span>
                </div>
            </div>
        </div>
    </div>


    <!--=================================Modal FACTURA====================================-->
    <div class="modal fade" tabindex="-1" role="dialog" id="modalFactura">
        <div class="modal-dialog modal-lg" role="document"  style="height: 90%; width: 90%;">
            <div class="modal-content" style="height: 90%;">
                <div class="modal-header modal-header-primary">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Factura</h4>
                </div>
                <div class="modal-body" id="bodyfactura">
                    <div id="aux_venta">

                    </div>
                    <!--<object id="aux_venta" height="450px" width="100%" > <!--content--> <!--/object>-->
                </div>
            </div>
        </div>
    </div>

</div>









