

<div ng-controller="reembolsoComprasController" ng-cloak>

    <div class="col-xs-12">

        <h4>Comprobantes de Reembolso (Compras)</h4>

        <hr>

    </div>

    <div class="col-xs-12" style="margin-top: 15px;">
        <div class="col-sm-7 col-xs-12">
            <div class="form-group has-feedback">
                <input type="text" class="form-control" id="t_busqueda" placeholder="Buscar por número de comprobante de retención..." ng-model="t_busqueda" ng-keyup="initLoad(1)">
                <span class="glyphicon glyphicon-search form-control-feedback" aria-hidden="true"></span>
            </div>
        </div>

        <div class="col-sm-1 col-xs-12">
            <button type="button" class="btn btn-primary" style="float: right;" ng-click="newForm()"
                    data-toggle="tooltip" data-placement="bottom" title="Crear nueva Retención de Compra">
                Agregar <i class="fa fa-lg fa-plus" aria-hidden="true"></i>
            </button>
        </div>
    </div>

    <div class="col-xs-12" style="font-size: 12px !important;">
        <table class="table table-responsive table-striped table-hover table-condensed table-bordered">
            <thead class="bg-primary">
            <th class="text-center" style="width: 10%;" ng-click="sort('')">
                NO. RETENCION
                <span class="glyphicon sort-icon" ng-show="sortKey==''"
                      ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
            </th>
            <th class="text-center" style="width: 10%;" ng-click="sort('')">
                FECHA EMISION
                <span class="glyphicon sort-icon" ng-show="sortKey==''"
                      ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
            </th>
            <th class="text-center" ng-click="sort('')">
                PROVEEDOR
                <span class="glyphicon sort-icon" ng-show="sortKey==''"
                      ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
            </th>
            <th class="text-center" style="width: 13%;" ng-click="sort('')">
                NO. DOC. RETENC.
                <span class="glyphicon sort-icon" ng-show="sortKey==''"
                      ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
            </th>
            <th class="text-center" style="width: 9%;" ng-click="sort('')">
                VALOR
                <span class="glyphicon sort-icon" ng-show="sortKey==''"
                      ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
            </th>
            <th style="text-align: center; width: 9%;">
                ESTADO
            </th>
            <th class="text-center" style="width: 10%;">ACCIONES</th>
            </thead>
            <tbody>
            <tr dir-paginate="item in retencion | orderBy:sortKey:reverse | itemsPerPage:8 " total-items="totalItems" ng-cloak>
                <td>{{$index + 1}}</td>
                <td class="text-center">{{item.fechaemisioncomprob | formatDate}}</td>
                <td style="font-weight: bold;">{{item.cont_documentocompra[0].proveedor.persona.razonsocial}}</td>
                <td class="text-center">{{item.nocomprobante}}</td>
                <td class="text-right">$ {{item.total_retenido}}</td>
                <td class="text-right">{{(item.cont_documentocompra[0].sri_retencioncompra[0].estadoanulado) ? 'ANULADA' : 'NO ANULADA'}}</td>
                <td  class="text-center">
                    <button type="button" class="btn btn-info btn-sm" ng-click="loadFormPage(item.idcomprobanteretencion)">
                        <i class="fa fa-lg fa-info-circle" aria-hidden="true"></i>
                    </button>

                    <button type="button" class="btn btn-default btn-sm" ng-click="showModalConfirmAnular(item)" ng-disabled="item.cont_documentocompra[0].sri_retencioncompra[0].estadoanulado==true">
                        <i class="fa fa-lg fa-ban" aria-hidden="true"></i>
                    </button>
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






    <div class="modal fade" tabindex="-1" role="dialog" id="modalAction">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header modal-header-primary">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Agregar Comprobante Reembolso</h4>
                </div>
                <div class="modal-body">

                    <div class="row">

                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" id="idretencioncompra" value="">

                        <form name="formRteCompras" novalidate="">

                            <div class="col-xs-12" style="padding: 0;">
                                <div class="col-sm-6 col-xs-12" style="margin-top: 5px;">
                                    <div class="input-group">
                                        <span class="input-group-addon">Nro. Documento Compras: </span>
                                        <!--<input type="text" class="form-control" id="t_nrocompra" name="t_nrocompra" ng-model="t_nrocompra" placeholder="" />-->

                                        <angucomplete-alt
                                            id = "t_nrocompra"
                                            pause = "400"
                                            selected-object = "showDataPurchase"

                                            remote-url = "{{API_URL}}retencionCompra/getCompras/"
                                            remote-url-request-formatter="remoteUrlRequestFn"


                                            remote-api-handler="searchAPI"

                                            title-field="numdocumentocompra"

                                            minlength="1"
                                            input-class="form-control"
                                            match-class="highlight"
                                            field-required="true"
                                            input-name="t_nrocompra"
                                            disable-input="guardado"
                                            text-searching="Buscando Compras"
                                            text-no-results="Compra no encontrada"

                                        />


                                    </div>
                                    <span class="help-block error" style="text-align: right !important; color: red;"
                                          ng-show="formRteCompras.t_nrocompra.$invalid && formRteCompras.t_nrocompra.$touched" >El Nro. de Compra es requerido</span>
                                </div>
                            </div>



                            <div class="col-sm-6 col-xs-12" style="margin-top: 5px;">
                                <div class="input-group">
                                    <span class="input-group-addon">Tipo Identificación: </span>
                                    <select class="form-control" name="tipoidentificacion" id="tipoidentificacion" ng-model="tipoidentificacion"
                                            ng-options="value.id as value.label for value in idtipoidentificacion" required></select>
                                </div>
                                <span class="help-block error"
                                      ng-show="formEmployee.tipoidentificacion.$invalid && formEmployee.tipoidentificacion.$touched">El Tipo de Identificación es requerido</span>
                            </div>

                            <div class="col-sm-6 col-xs-12" style="margin-top: 5px;">
                                <div class="input-group">
                                    <span class="input-group-addon">No. Identificación: </span>
                                    <input type="text" class="form-control" name="numdocidentific" id="numdocidentific"
                                           ng-model="numdocidentific" ng-maxlength="13" />
                                </div>

                            </div>



                            <div class="col-sm-6 col-xs-12" style="margin-top: 5px;">
                                <div class="input-group">
                                    <span class="input-group-addon">Tipo Comprobante: </span>
                                    <select ng-disabled="impreso" class="form-control" name="tipocomprobante" id="tipocomprobante" ng-model="tipocomprobante" ng-required="true"
                                            ng-options="value.id as value.label for value in listtipocomprobante">
                                    </select>
                                </div>
                                <span class="help-block error" ng-show="formCompra.tipocomprobante.$invalid && formCompra.tipocomprobante.$touched">El Tipo Comprobante es requerido</span>
                            </div>

                            <div class="col-sm-6 col-xs-12" style="margin-top: 5px;">
                                <div class="input-group">
                                    <span class="input-group-addon">Nro. Documento: </span>
                                    <span class="input-group-btn" style="width: 15%;">
                                        <input  type="text" class="form-control" id="t_establ" name="t_establ" ng-model="t_establ" ng-keypress="onlyNumber($event, 3, 't_establ')" ng-blur="calculateLength('t_establ', 3)" />
                                    </span>
                                    <span class="input-group-btn" style="width: 15%;" >
                                        <input  type="text" class="form-control" id="t_pto" name="t_pto" ng-model="t_pto" ng-keypress="onlyNumber($event, 3, 't_pto')" ng-blur="calculateLength('t_pto', 3)" />
                                    </span>
                                    <input  type="text" class="form-control" id="t_secuencial" name="t_secuencial" ng-model="t_secuencial" ng-keypress="onlyNumber($event, 9, 't_secuencial')" ng-blur="calculateLength('t_secuencial', 9)" />
                                </div>
                            </div>



                            <div class="col-sm-6 col-xs-12 error" style="margin-top: 5px;">
                                <div class="input-group">
                                    <span class="input-group-addon">No. Autorización Comprobante: </span>
                                    <input type="text" class="form-control" id="t_nroautorizacion" name="t_nroautorizacion"
                                           ng-model="t_nroautorizacion" ng-required="true" ng-keypress="onlyNumber($event, 16, 't_nroautorizacion')" placeholder="" />
                                </div>
                                <span class="help-block error" style="text-align: right !important; color: red;"
                                      ng-show="formRteCompras.t_nroautorizacion.$invalid && formRteCompras.t_nroautorizacion.$touched" >El Nro. de Autorización es requerido</span>
                            </div>

                            <div class="col-sm-6 col-xs-12" style="margin-top: 5px;">
                                <div class="input-group">
                                    <span class="input-group-addon">Fecha Emisión Comprobante: </span>
                                    <input type="text" class="form-control datepicker" name="fechaemisioncomprobante" id="fechaemisioncomprobante" ng-model="fechaemisioncomprobante" ng-blur="valueFecha()" required/>
                                </div>
                                <span class="help-block error" style="text-align: right !important; color: red;"
                                      ng-show="formRteCompras.fechaemisioncomprobante.$invalid && formRteCompras.fechaemisioncomprobante.$touched" >La Fecha de Emisión es requerido</span>
                            </div>



                            <div class="col-sm-6 col-xs-12" style="margin-top: 5px;">
                                <div class="input-group">
                                    <span class="input-group-addon">Tarifa IVA 0%: </span>
                                    <input type="text" class="form-control" id="t_tarifaivacero" name="t_tarifaivacero"
                                           ng-model="t_tarifaivacero" ng-required="true" ng-keypress="onlyNumber($event, null, 't_tarifaivacero')" placeholder="" />
                                </div>
                            </div>

                            <div class="col-sm-6 col-xs-12" style="margin-top: 5px;">
                                <div class="input-group">
                                    <span class="input-group-addon">Tarifa IVA diferente 0%: </span>
                                    <input type="text" class="form-control" name="t_tarifadifcero" id="t_tarifadifcero" ng-model="t_tarifadifcero" />
                                </div>
                            </div>



                            <div class="col-sm-6 col-xs-12" style="margin-top: 5px;">
                                <div class="input-group">
                                    <span class="input-group-addon">Tarifa No Obj IVA: </span>
                                    <input type="text" class="form-control" name="t_tarifanoobj" id="t_tarifanoobj" ng-model="t_tarifanoobj" />
                                </div>
                            </div>

                            <div class="col-sm-6 col-xs-12" style="margin-top: 5px;">
                                <div class="input-group">
                                    <span class="input-group-addon">Base Exento IVA: </span>
                                    <input type="text" class="form-control" id="t_tarifaex" name="t_tarifaex"
                                           ng-model="t_tarifaex" ng-required="true" ng-keypress="onlyNumber($event, null, 't_tarifaex')" placeholder="" />
                                </div>
                            </div>



                            <div class="col-sm-6 col-xs-12" style="margin-top: 5px;">
                                <div class="input-group">
                                    <span class="input-group-addon">Monto IVA: </span>
                                    <input type="text" class="form-control" name="t_montoiva" id="t_montoiva" ng-model="t_montoiva" />
                                </div>
                            </div>

                            <div class="col-sm-6 col-xs-12" style="margin-top: 5px;">
                                <div class="input-group">
                                    <span class="input-group-addon">Monto ICE: </span>
                                    <input type="text" class="form-control" id="t_montoice" name="t_montoice"
                                           ng-model="t_montoice" ng-required="true" ng-keypress="onlyNumber($event, null, 't_montoice')" placeholder="" />
                                </div>
                            </div>


                        </form>


                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        Cancelar <span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span>
                    </button>
                    <button type="button" class="btn btn-success" id="btn-saveCliente" ng-click="save()" ng-disabled="formEmployee.$invalid">
                        Guardar <span class="glyphicon glyphicon-floppy-saved" aria-hidden="true"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" id="modalConfirmAnular">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header modal-header-danger">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Confirmación</h4>
                </div>
                <div class="modal-body">
                    <span>Está seguro que desea Anular el Comprobante de Retención: <strong>"{{numseriecompra}}"</strong> seleccionada?</span>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        Cancelar <span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span>
                    </button>
                    <button type="button" class="btn btn-danger" id="btn-save" ng-click="anularRetencion()">
                        Anular
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" id="modalMessage" style="z-index: 99999;">
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

</div>


