

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


            <div class="cos-xs-12">
            <table class="table table-responsive table-striped table-hover table-condensed">
                <thead class="bg-primary">
                <tr>
                    <th>
                        <a href="#" style="text-decoration:none; color:white;" >Nro.</a>
                    </th>
                    <th>
                        <a href="#" style="text-decoration:none; color:white;" >Cliente</a>
                    </th>
                    <th>
                        <a href="#" style="text-decoration:none; color:white;" >Zona</a>
                    </th>
                    <th>
                        <a href="#" style="text-decoration:none; color:white;" >Dirección</a>
                    </th>
                    <th>
                        <a href="#" style="text-decoration:none; color:white;" >Teléfono</a>
                    </th>
                    <th>
                        <a href="#" style="text-decoration:none; color:white;" >Acciones</a>
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr dir-paginate="suministro in suministros| orderBy:sortKey:reverse|filter:busqueda|itemsPerPage:10" ng-cloak>
                    <td>{{suministro.idsuministro}}</td>
                    <td>{{suministro.cliente.persona.razonsocial}}</td>
                    <td>{{suministro.calle.barrio.namebarrio}}</td>
                    <td>{{suministro.direccionsumnistro}}</td>
                    <td>{{suministro.telefonosuministro}}</td>
                    <td >
                        <button type="button" class="btn btn-info btn-sm" ng-click="getSuministro(suministro.numerosuministro);">
                            <i class="fa fa-lg fa-info-circle" aria-hidden="true"></i>
                        </button>
                        <button type="button" class="btn btn-warning btn-sm" ng-click="modalEditarSuministro(suministro.numerosuministro);">
                            <i class="fa fa-lg fa-pencil-square-o" aria-hidden="true"></i>
                        </button>
                    </td>
                </tr>
                </tbody>
            </table>

                <dir-pagination-controls
                    max-size="5"
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
                        <h4 class="modal-title">Editar suministro Nro. {{suministro.numerosuministro}} </h4>
                    </div>
                    <div class="col-md-6 col-xs-12">
                        <div class="form-group">
                            <label for="fechaingreso" class="col-sm-5 control-label">Fecha de Ingreso:</label>
                            <div class="col-sm-6" style="padding: 0;">
                                <label >{{suministro.fechainstalacionsuministro}}</label>
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
                            <fieldset>
                                <legend style="padding-bottom: 0px; padding-left: 20px">Datos del Cliente</legend>
                                <div class="col-xs-12" style="margin-top: -20px;">
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group error">
                                            <h3><span class="col-sm-4 label label-default">CI/Ruc:</span></h3>
                                            <div class="col-sm-8">
                                                <label class="control-label">{{suministro.cliente.documentoidentidad}}</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group error">
                                            <h3><span class="col-sm-4 label label-default">Cliente:</span></h3>
                                            <div class="col-sm-8">
                                                <label class="control-label">{{suministro.cliente.nombres+" "+suministro.cliente.apellidos}}</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>

                            <fieldset>
                                <legend style="padding-bottom: 5px; padding-left: 20px">Datos Suministro</legend>
                                <div class="col-xs-12">
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group error">
                                            <label class="col-sm-4 control-label">Zona:</label>
                                            <div class="col-sm-8">
                                                <select id="calle" class="form-control" ng-model="calle"
                                                        ng-options="value.id as value.label for value in calles" required></select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xs-12">
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group error">
                                            <label class="col-sm-4 control-label">Dirección:</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" name="direccionsuministro" id="direccionsuministro"
                                                       ng-model="direccionsuministro" ng-required="true" ng-maxlength="128" >

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xs-12">


                                            <label for="telefonosuministro" class="col-sm-4 col-xs-12 control-label" style="padding: 5px 0 5px 0;">Teléfono:</label>
                                            <div class="col-sm-8 col-xs-12">
                                                <input type="text" class="form-control" name="telefonosuministro" id="telefonosuministro"
                                                       ng-model="telefonosuministro" ng-minlength="9" ng-pattern="/^([0-9-\(\)]+)$/">
                                                <span class="help-block error"
                                                      ng-show="formNuevaSolicitud.telefonosuministro.$invalid && formNuevaSolicitud.telefonosuministro.$error.pattern">Solo números, guiones y paréntesis</span>
                                                <span class="help-block error"
                                                      ng-show="formNuevaSolicitud.telefonosuministro.$invalid && formNuevaSolicitud.telefonosuministro.$error.minlength">El Teléfono debe ser mayor a 9 dígitos</span>

                                            </div>

                                        </div>
                                </div>

                            </fieldset>
                        </form>
                    </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        Cancelar <span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span>
                    </button>

                    <button type="button" class="btn btn-success" id="btn-save" ng-click="editarSuministro();" ng-disabled="">Guardar</button>

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


</div>









