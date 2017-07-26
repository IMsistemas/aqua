
<div ng-controller="retencionVentasController" ng-cloak>

    <div class="col-xs-12">

        <h4>Retención de Ventas</h4>

        <hr>

    </div>

    <div ng-show="active=='0'" ng-hide="active=='1'">
        <div class="col-xs-12" style="margin-top: 15px;">
            <div class="col-sm-7 col-xs-12">
                <div class="form-group has-feedback">
                    <input type="text" class="form-control" id="t_busqueda" placeholder="Buscar por número de comprobante de retención..." ng-model="t_busqueda" ng-keyup="initLoad(1)">
                    <span class="glyphicon glyphicon-search form-control-feedback" aria-hidden="true"></span>
                </div>
            </div>
            <div class="col-sm-1 col-xs-12">
                <button type="button" class="btn btn-primary" style="float: right;" ng-click="newForm()"
                        data-toggle="tooltip" data-placement="bottom" title="Crear nueva Retención de Venta">
                    Agregar <i class="fa fa-lg fa-plus" aria-hidden="true"></i>
                </button>
            </div>
        </div>

        <div class="col-xs-12" style="font-size: 12px !important;">
            <table class="table table-responsive table-striped table-hover table-condensed table-bordered">
                <thead class="bg-primary">
                <tr>
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
                        CLIENTE
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
                </tr>
                </thead>
                <tbody>
                <tr dir-paginate="item in retencion | orderBy:sortKey:reverse | itemsPerPage:8 " total-items="totalItems" ng-cloak>
                    <td>{{$index + 1}}</td>
                    <td class="text-center">{{item.fechaemisioncomprob | formatDate}}</td>
                    <td style="font-weight: bold;">{{item.cont_documentoventa[0].cliente.persona.razonsocial}}</td>
                    <td class="text-center">{{item.nocomprobante}}</td>
                    <td class="text-right">$ {{item.total_retenido}}</td>
                    <td class="text-right">{{(item.cont_documentoventa[0].sri_retencionventa[0].estadoanulado) ? 'ANULADA' : 'NO ANULADA'}}</td>
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

    <!-- --------------------------------------------------------------------------------------------------- -->

    <div ng-show="active=='1'" ng-hide="active=='0'">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" id="idretencioncompra" value="">

        <form name="formRteCompras" novalidate="">

            <div class="container" style="margin-bottom: 5px;">

                <div class="row">
                    <div class="col-sm-4 col-xs-12">
                        <div class="input-group">
                            <span class="input-group-addon">Fecha Ingreso: </span>
                            <input type="text" class="datepicker form-control" id="t_fechaingreso" name="t_fechaingreso" ng-model="t_fechaingreso" placeholder="" disabled />
                        </div>
                    </div>

                    <div class="col-sm-4 col-xs-12 error">

                    </div>

                    <div class="col-sm-4 col-xs-12">
                        <div class="input-group">
                            <span class="input-group-addon">Nro. Documento: </span>
                            <!--<input type="text" class="form-control" id="t_nrocompra" name="t_nrocompra" ng-model="t_nrocompra" placeholder="" />-->

                            <angucomplete-alt
                                    id = "t_nrocompra"
                                    pause = "200"
                                    selected-object = "showDataPurchase"

                                    remote-url = "{{API_URL}}retencionVenta/getCompras/"

                                    title-field="numdocumentoventa"

                                    minlength="1"
                                    input-class="form-control"
                                    match-class="highlight"
                                    field-required="true"
                                    input-name="t_nrocompra"
                                    disable-input="guardado"
                                    text-searching="Buscando Documentos de Ventas"
                                    text-no-results="Venta no encontrada"

                            />


                        </div>
                        <span class="help-block error" style="text-align: right !important; color: red;"
                              ng-show="formRteCompras.t_nrocompra.$invalid && formRteCompras.t_nrocompra.$touched" >El Nro. de Venta es requerido</span>
                    </div>
                </div>

            </div>

            <div class="container" style="margin-top: 5px;">

                <div class="row">
                    <div class="col-sm-6 col-xs-12">
                        <div class="input-group">
                            <span class="input-group-addon">RUC / CI: </span>
                            <input type="text" class="form-control" id="t_rucci" name="t_rucci" ng-model="t_rucci" disabled />
                        </div>
                    </div>
                    <div class="col-sm-6 col-xs-12">
                        <div class="input-group">
                            <span class="input-group-addon">Razón Social: </span>
                            <input type="text" class="form-control" id="t_razonsocial" name="t_razonsocial" ng-model="t_razonsocial" disabled />
                        </div>
                    </div>
                </div>


            </div>

            <div class="container" style="margin-top: 5px;">

                <div class="row">
                    <div class="col-xs-6">
                        <div class="input-group">
                            <span class="input-group-addon">Tipo de Pago: </span>
                            <select class="form-control" name="tipopago" id="tipopago" ng-model="tipopago"
                                    ng-options="value.id as value.label for value in listtipopago" ng-change="typeResident()" required>
                            </select>

                        </div>
                        <span class="help-block error" style="text-align: right !important; color: red;"
                              ng-show="formRteCompras.tipopago.$invalid && formRteCompras.tipopago.$touched" >El Tipo de Pago es requerido</span>
                    </div>
                    <div class="col-xs-6">
                        <div class="input-group">
                            <span class="input-group-addon">País Pago: </span>
                            <select class="form-control" name="paispago" id="paispago" ng-model="paispago"
                                    ng-options="value.id as value.label for value in listpaispago">
                            </select>

                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-6" style="margin-top: 5px;">
                        <div class="input-group">
                            <span class="input-group-addon">Régimen Fiscal?: </span>
                            <select class="form-control" name="regimenfiscal" id="regimenfiscal" ng-model="regimenfiscal"
                                    ng-options="value.id as value.name for value in estados" >
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-6" style="margin-top: 5px;">
                        <div class="input-group">
                            <span class="input-group-addon">Convenio doble Tributación?: </span>
                            <select class="form-control" name="convenio" id="convenio" ng-model="convenio"
                                    ng-options="value.id as value.name for value in estados">
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-6" style="margin-top: 5px;">
                        <div class="input-group">
                            <span class="input-group-addon">Aplicación de Norma Legal?: </span>
                            <select class="form-control" name="normalegal" id="normalegal" ng-model="normalegal"
                                    ng-options="value.id as value.name for value in estados">
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-6" style="margin-top: 5px;">
                        <div class="input-group">
                            <span class="input-group-addon">Fecha Emisión Comprobante: </span>
                            <input type="text" class="form-control datepicker" name="fechaemisioncomprobante" id="fechaemisioncomprobante" ng-model="fechaemisioncomprobante" ng-blur="valueFecha()" required/>
                        </div>
                        <span class="help-block error" style="text-align: right !important; color: red;"
                              ng-show="formRteCompras.fechaemisioncomprobante.$invalid && formRteCompras.fechaemisioncomprobante.$touched" >La Fecha de Emisión es requerido</span>
                    </div>
                </div>

                <div class="row">
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
                            <span class="input-group-addon">Autorización: </span>
                            <input type="text" class="form-control" id="t_nroautorizacion" name="t_nroautorizacion"
                                   ng-model="t_nroautorizacion" ng-required="true" ng-keypress="onlyNumber($event, 16, 't_nroautorizacion')" placeholder="" />
                        </div>
                        <span class="help-block error" style="text-align: right !important; color: red;"
                              ng-show="formRteCompras.t_nroautorizacion.$invalid && formRteCompras.t_nroautorizacion.$touched" >El Nro. de Autorización es requerido</span>
                    </div>
                </div>

            </div>

            <div class="col-xs-12" style="margin-top: 15px;">
                <div class="container">

                    <div class="col-xs-12">
                        <button type="button" class="btn btn-primary" id="btn-createrow" style="float: right;" ng-click="createRow()"
                                data-toggle="tooltip" data-placement="left" title="Agregar Retención" disabled>
                            <i class="fa fa-lg fa-plus" aria-hidden="true"></i>
                        </button>
                    </div>

                    <div class="col-xs-12" style="margin-top: 5px;">
                        <table class="table table-responsive table-striped table-hover table-condensed">
                            <thead class="bg-primary">
                            <tr>
                                <th class="text-center" style="width: 10%;">
                                    AÑO FISCAL
                                </th>
                                <th class="text-center" style="width: 12%;">
                                    CODIGO
                                </th>
                                <th class="text-center" ng-click="sort('')">
                                    DETALLE
                                    <span class="glyphicon sort-icon" ng-show="sortKey==''"
                                          ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                                </th>
                                <th style="width: 5%;">TIPO</th>
                                <th class="text-center" style="width: 15%;" ng-click="sort('')">
                                    BASE IMPONIBLE
                                    <span class="glyphicon sort-icon" ng-show="sortKey==''"
                                          ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                                </th>
                                <th class="text-center" style="width: 10%;" ng-click="sort('')">
                                    %
                                    <span class="glyphicon sort-icon" ng-show="sortKey==''"
                                          ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                                </th>
                                <th class="text-center" style="width: 12%;" ng-click="sort('')">
                                    VALOR
                                    <span class="glyphicon sort-icon" ng-show="sortKey==''"
                                          ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                                </th>
                                <th style="width: 5%;"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr ng-repeat="item in itemretencion" ng-cloak>
                                <td class="text-center">{{item.year}}</td>
                                <td>
                                    <input type="hidden" name="h_iddetalleretencionfuente" ng-model="h_iddetalleretencionfuente">
                                    <div class="col-xs-12">
                                        <angucomplete-alt
                                                id = "item.codigo{{$index}}"
                                                pause = "400"
                                                selected-object = "showInfoRetencion"
                                                selected-object-data = "item"

                                                remote-url = "{{API_URL}}retencionVenta/getCodigos/"

                                                title-field="codigosri"

                                                minlength="1"
                                                input-class="form-control text-right error"
                                                match-class="highlight"
                                                field-required="true"
                                                input-name="itemcodigo{{$index}}"
                                                disable-input="guardado"
                                                text-searching="Buscando Códigos"
                                                text-no-results="Código no encontrado"
                                                initial-value="item.codigo"
                                        />
                                    </div>
                                    <span class="help-block error" style="text-align: right !important; color: red;"
                                          ng-show="formRteCompras.itemcodigo{{$index}}.$invalid && formRteCompras.itemcodigo{{$index}}.$touched" >
                                                Es requerido</span>
                                </td>
                                <td>{{item.detalle}}</td>
                                <td>{{item.tipo}}</td>
                                <td class="text-right">$ {{item.baseimponible}}</td>
                                <td>
                                    <input type="text" class="form-control" style="text-align: right !important;" ng-model="item.porciento" ng-blur="recalculateRow(item)" ng-keypress="onlyDecimal($event)">
                                </td>
                                <td class="text-right">$ {{item.valor}}</td>
                                <td>
                                    <button type="button" class="btn btn-danger" name="btn-deleterow" style="float: right;" ng-click="deleteRow(item)"
                                            data-toggle="tooltip" data-placement="left" title="Eliminar Retención" >
                                        <i class="fa fa-lg fa-trash" aria-hidden="true"></i>
                                    </button>
                                </td>
                            </tr>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th colspan="5" class="text-right">TOTAL:</th>
                                <th>
                                    <input type="text" class="form-control" style="text-align: right !important;" id="t_total" name="t_total" ng-model="t_total" ng-keypress="onlyDecimal($event)" disabled/>
                                </th>
                                <th></th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="col-xs-12">
                        <button type="button" class="btn btn-primary" style="float: right;" ng-click="returnList()"
                                data-toggle="tooltip" data-placement="bottom" title="Regresar al Registro de Retenciones de Compra">
                            Registro <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                        </button>
                        <button type="button" class="btn btn-default" style="float: right;" ng-click=""
                                data-toggle="tooltip" data-placement="bottom" title="Anular la Retención de la Compra">
                            Anular <span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span>
                        </button>
                        <button type="button" class="btn btn-success" id="btn_save" style="float: right;" ng-click="save()" ng-disabled="formRteCompras.$invalid"
                                data-toggle="tooltip" data-placement="left" title="Guardar la Retención de la Compra" >
                            Guardar <span class="glyphicon glyphicon-floppy-saved" aria-hidden="true"></span>
                        </button>
                    </div>

                </div>
            </div>
        </form>


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


