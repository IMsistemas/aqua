
    <div ng-controller="viewLecturaController">

        <div class="col-xs-12">

            <h4>Registro de Tomas de Lecturas</h4>

            <hr>

        </div>

        <div class="col-xs-12">
            <fieldset>
                <div class="col-sm-6 col-xs-12">

                    <h4>Periodo:</h4>
                    <hr>

                    <div class="row">
                        <div class="col-xs-6">

                            <div class="input-group">
                                <span class="input-group-addon">Año: </span>
                                <input type="text" class="form-control datepicker_a" name="s_anno"
                                       id="s_anno" ng-model="s_anno" ng-change="searchByFilter();">
                            </div>

                        </div>

                        <div class="col-xs-6">

                            <div class="input-group">
                                <span class="input-group-addon">Mes: </span>
                                <select class="form-control" name="s_mes" id="s_mes" ng-model="s_mes" ng-change="searchByFilter();"
                                        ng-options="value.id as value.name for value in meses">
                                </select>
                            </div>

                        </div>

                    </div>

                </div>
                
                <div class="col-sm-6 col-xs-12">

                    <h4>Sector:</h4>
                    <hr>

                    <div class="row">
                        <div class="col-xs-6">

                            <div class="input-group">
                                <span class="input-group-addon">Barrio: </span>
                                <select class="form-control" name="s_barrio" id="s_barrio" ng-model="s_barrio"
                                        ng-options="value.id as value.label for value in barrios"
                                        ng-change="loadCalles(); searchByFilter();"></select>
                            </div>

                        </div>
                        <div class="col-xs-6">

                            <div class="input-group">
                                <span class="input-group-addon">Calle: </span>
                                <select class="form-control" name="s_calle" id="s_calle" ng-model="s_calle"
                                        ng-options="value.id as value.label for value in calles"
                                        ng-change="searchByFilter();"></select>
                            </div>

                        </div>

                    </div>

                </div>

                <div class="col-xs-12" style="margin-top: 5px;">
                    <div class="form-group has-feedback" style="width: 50%;">
                        <input type="text" class="form-control" id="search-list-trans" placeholder="BUSCAR..." 
                                ng-model="t_search" >
                        <span class="glyphicon glyphicon-search form-control-feedback" aria-hidden="true"></span>
                    </div>
                </div>

                <div class="col-xs-12">
                    <table class="table table-striped table-hover table-responsive table-bordered">
                        <thead>
                            <tr class="bg-primary">
                                <th style="width: 5%;">Código</th>
                                <th>Cliente</th>
                                <th style="width: 8%;">Suministro</th>
                                <th>Calle</th>
                                <th style="width: 10%;">Lectura Anterior</th>
                                <th style="width: 10%;">Lectura Actual</th>
                                <th style="width: 10%;">Consumo del Mes</th>
                                <th>Observaciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr dir-paginate="lectura in lecturas | orderBy:sortKey:reverse |itemsPerPage:4 | filter : t_search" ng-cloak>
                                <td class="text-right">{{lectura.idlectura}}</td>
                                <td><i class="fa fa-user" ></i> {{lectura.razonsocial}}</td>
                                <td class="text-center">{{lectura.idsuministro}}</td>
                                <td>{{lectura.namecalle}}</td>
                                <td class="text-right">{{lectura.lecturaanterior}}</td>
                                <td  class="text-right">
                                   <span ng-if="verifyDate(lectura.fechalectura, lectura.estapagado) == true">
                                       <input type="text" class="form-control" ng-change="prepareUpdate(lectura)" style="width: 100%;"
                                               ng-model="lectura.lecturaactual">
                                   </span>
                                    <span ng-if="verifyDate(lectura.fechalectura, lectura.estapagado) == false">
                                       <input type="text" class="form-control" ng-change="prepareUpdate(lectura)" style="width: 100%;"
                                              ng-model="lectura.lecturaactual" disabled>
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
                    <dir-pagination-controls
                            max-size="5"
                            direction-links="true"
                            boundary-links="true" >
                    </dir-pagination-controls>
                </div>

                <div class="col-xs-12">
                    <button type="button" class="btn btn-success" id="btn-save" style="float: right;" ng-click="save()">
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

        <div class="modal fade" tabindex="-1" role="dialog" id="modalMessageError" style="z-index: 99999;">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header modal-header-danger">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Información</h4>
                    </div>
                    <div class="modal-body">
                        <span>{{message_error}}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" tabindex="-1" role="dialog" id="modalMessage" style="z-index: 999999;">
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


