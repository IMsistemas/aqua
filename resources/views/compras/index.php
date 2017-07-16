

<div ng-controller="comprasController">

    <div class="col-xs-12">

        <h4>Facturación de Compras</h4>

        <hr>

    </div>

    <!-- Listado -->

    <div class="container1" ng-show="listado">

        <div class="col-xs-12" style="margin-top: 5px; margin-bottom: 2%">

            <div class="col-sm-4 col-xs-6">
                <div class="form-group has-feedback">
                    <input type="text" class="form-control" id="search" placeholder="BUSCAR..."
                           ng-model="search" ng-change="searchByFilter()">
                    <span class="glyphicon glyphicon-search form-control-feedback" aria-hidden="true"></span>
                </div>
            </div>
            <div class="col-sm-3 col-xs-3">
                <select class="form-control" name="proveedorFiltro0" id="proveedorFiltro0" ng-model="proveedorFiltro0"
                        ng-change="searchByFilter()" ng-options="value.id as value.label for value in proveedor0" >
                </select>
            </div>

            <div class="col-sm-3 col-xs-3">
                <div class="form-group has-feedback">
                    <select class="form-control" name="estado" id="estado" ng-model="estadoFiltro"
                            ng-change="searchByFilter()">
                        <option value="">-- Seleccione Estado --</option>
                        <option ng-repeat="item in estados"
                                value="{{item.id}}">{{item.nombre}}
                        </option>
                    </select>
                </div>
            </div>


            <div class="col-sm-2 col-xs-6">
                <button type="button" class="btn btn-primary" style="float: right;" ng-click="activeForm(0)">
                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                </button>
            </div>

            <div class="col-xs-12" style="font-size: 12px !important;">
                <table class="table table-responsive table-striped table-hover table-condensed table-bordered">
                    <thead class="bg-primary">
                    <tr>
                        <th style="text-align: center; width: 4%;" ng-click="sort('codigocompra')">
                            NO
                            <span class="glyphicon sort-icon" ng-show="sortKey=='codigocompra'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                        </th>
                        <th style="text-align: center; width: 10%;" ng-click="sort('fecharegistrocompra')">
                            FECHA INGRESO
                            <span class="glyphicon sort-icon" ng-show="sortKey=='fecharegistrocompra'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                        </th>
                        <th style="text-align: center;" ng-click="sort('razonsocialproveedor')">
                            PROVEEDOR
                            <span class="glyphicon sort-icon" ng-show="sortKey=='razonsocialproveedor'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                        </th>
                        <th style="text-align: center; width: 14%;">NO. FACTURA</th>
                        <th style="text-align: center; width: 8%;">SUBTOTAL</th>
                        <th style="text-align: center; width: 8%;">IVA</th>
                        <th style="text-align: center; width: 10%;" ng-click="sort('totalcompra')">
                            TOTAL
                            <span class="glyphicon sort-icon" ng-show="sortKey=='totalcompra'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                        </th>
                        <th style="text-align: center; width: 9%;" ng-click="sort('estapagada')">
                            ESTADO
                            <span class="glyphicon sort-icon" ng-show="sortKey=='estapagada'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                        </th>
                        <th class="text-center" style="width: 9%;">ACCIONES</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr dir-paginate="item in compras | orderBy:sortKey:reverse | itemsPerPage:8" total-items="totalItems" ng-cloak">
                        <td style="text-align: center;">{{item.iddocumentocompra}}</td>
                        <td class="text-center">{{formatoFecha(item.fecharegistrocompra)}}</td>
                        <td class="text-left">{{item.razonsocial}}</td>
                        <td class="text-center">{{item.numdocumentocompra}}</td>
                        <td class="text-right">$ {{sumar(item.subtotalconimpuestocompra,item.subtotalcerocompra)}}</td>
                        <td class="text-right">$ {{item.ivacompra  }}</td>
                        <td class="text-right">$ {{item.valortotalcompra}}</td>
                        <td class="text-right">{{(item.estadoanulado)?'ANULADA':'NO ANULADA'}}</td>
                        <td class="text-center">
                            <button type="button" class="btn btn-info" ng-click="viewInfoCompra(item.iddocumentocompra)" ng-disabled="item.estaAnulada==1"
                                    data-toggle="tooltip" data-placement="bottom" title="Información">
                                <span class="glyphicon glyphicon-info-sign" aria-hidden="true">
                            </button>

                            <button type="button" class="btn btn-default" ng-click="showModalConfirm(item,0)"
                                    data-toggle="tooltip" data-placement="bottom" title="Anular"  ng-disabled="item.estadoanulado==1" title="Anular">
                                <span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span>
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



        <div class="modal fade" tabindex="-1" role="dialog" id="modalInfoEmpleado">
            <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content">
                    <div class="modal-header modal-header-info">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Información Empleado No {{empleado.idempleado}} </h4>
                    </div>
                    <div class="modal-body">
                        <div class="col-xs-12 text-center">
                            <img ng-src="{{ rutafoto }}" onerror="defaultImage(this)" class="img-thumbnail" style="width:150px" >
                        </div>
                        <div class="row text-center">
                            <div class="col-xs-12 text-center" style="font-size: 18px;">{{empleado.nombres}} {{empleado.apellidos}}</div>
                            <div class="col-xs-12">
                                <span style="font-weight: bold">Cargo: </span>{{empleado.nombrecargo}}
                            </div>
                            <div class="col-xs-12">
                                <span style="font-weight: bold">Empleado desde: </span>{{formatoFecha(empleado.fechaingreso)}}
                            </div>
                            <div class="col-xs-12">
                                <span style="font-weight: bold">Teléfonos: </span>{{empleado.telefonoprincipaldomicilio}} / {{empleado.telefonosecundariodomicilio}}
                            </div>

                            <div class="col-xs-12">
                                <span style="font-weight: bold">Celular: </span>{{empleado.celular}}
                            </div>
                            <div class="col-xs-12">
                                <span style="font-weight: bold">Dirección: </span>{{empleado.direcciondomicilio}}
                            </div>
                            <div class="col-xs-12">
                                <span style="font-weight: bold">Email: </span>{{empleado.correo}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Formulario -->

    <div class="col-xs-12" ng-show="!listado" >
        <div>

            <div ng-show="false" style="float: right">
                <div style="float: left">
                    <a href="#id" ng-click="excel()" data-toggle="tab">
                        <img ng-src="img/excel.png" style="height: 40px" >
                    </a>
                </div>
                <div style="float: left">
                    <a href="#id" ng-click="pdf()" data-toggle="tab">
                        <img ng-src="img/pdf.png" style="height: 40px" >
                    </a>
                </div>
                <div style="float: left" >
                    <a href="#id" ng-click="imprimir()" data-toggle="tab" >
                        <img ng-src="img/impresora.png" style="height: 40px" >
                    </a>
                </div>

            </div>

        </div>

        <form class="form-horizontal" name="formCompra" id="formCompra"  novalidate="" >
            <div class="col-xs-12">

                <div class="col-xs-6">
                    <fieldset>
                        <legend>Datos Proveedor</legend>

                        <div class="col-sm-6 col-xs-12" style="margin-top: 5px;">
                            <div class="input-group">
                                <span class="input-group-addon">Fecha Registro: </span>
                                <input type="text" class="form-control datepicker" id="fecharegistrocompra" ng-model="fecharegistrocompra" >
                            </div>
                        </div>

                        <div class="col-sm-6 col-xs-12" style="margin-top: 5px;">
                            <div class="input-group">
                                <span class="input-group-addon">No. Compra: </span>
                                <input type="text" class="form-control" id="numcompra" ng-model="numcompra" readonly >
                            </div>
                        </div>

                        <div class="col-xs-12" style="margin-top: 5px;">
                            <div class="input-group">
                                <span class="input-group-addon">RUC: </span>

                                <angucomplete-alt
                                        id = "idproveedor"
                                        pause = "200"
                                        selected-object = "showDataProveedor"

                                        remote-url = "{{API_URL}}DocumentoCompras/getProveedorByIdentify/"

                                        title-field="numdocidentific"

                                        minlength="1"
                                        input-class="form-control form-control-small small-input"
                                        match-class="highlight"
                                        field-required="true"
                                        input-name="idproveedor"
                                        disable-input="guardado"
                                        text-searching="Buscando Identificaciones Proveedor"
                                        text-no-results="Proveedor no encontrado"

                                > </angucomplete-alt>



                                <!--<input type="hidden" id="idproveedor" name="idproveedor" ng-model="compra.idproveedor">
                                <input type="text" class="form-control" name="ci" ng-model="ci"
                                       ng-keyup="loadProveedor()"
                                       id="ci" ng-required="true"
                                       ng-maxlength="13"
                                       ng-pattern="/[0-9]+$/"
                                       ng-disabled="impreso"
                                >-->
                            </div>
                            <span class="help-block error" ng-show="formCompra.ci.$invalid && formCompra.ci.$touched">El RUC del Proveedor es requerido</span>
                            <span class="help-block error" ng-show="formCompra.ci.$invalid && formCompra.ci.$error.maxlength">La
									longitud máxima es de 13 caracteres.</span> <span
                                class="help-block error"
                                ng-show="formCompra.ci.$invalid && formCompra.ci.$error.pattern">El RUC/CI no es válido.</span>
                            <span class="help-block error" ng-show="mensaje">El Proveedor no Existe.</span>
                        </div>
                        <div class="col-xs-12" style="margin-top: 5px;">
                            <div class="input-group">
                                <span class="input-group-addon">Razón Social: </span>
                                <input type="text" class="form-control" id="razon" ng-model="razon" readonly >
                            </div>
                        </div>
                        <div class="col-xs-12" style="margin-top: 5px;">
                            <div class="input-group">
                                <span class="input-group-addon">Dirección: </span>
                                <input type="text" class="form-control" id="direccion" ng-model="direccion" readonly >
                            </div>
                        </div>
                        <div class="col-xs-12" style="margin-top: 5px;">
                            <div class="input-group">
                                <span class="input-group-addon">Teléfono: </span>
                                <input type="text" class="form-control" id="telefono" ng-model="telefono" readonly >
                            </div>
                        </div>

                        <div class="col-sm-6 col-xs-12" style="margin-top: 5px;">
                            <div class="input-group">
                                <span class="input-group-addon">IVA: </span>
                                <input type="text" class="form-control" id="iva" ng-model="iva" readonly >
                            </div>
                        </div>
                        <div class="col-sm-6 col-xs-12" style="margin-top: 5px;">
                            <div class="input-group">
                                <span class="input-group-addon">Bodega: </span>
                                <select class="form-control" name="Bodega" id="Bodega" ng-model="Bodega" ng-change=" Validabodegaprodct='0' ">
                                    <option value="">-- Seleccione --</option>
                                    <option ng-repeat="b in Bodegas" value="{{b.idbodega}}">{{b.namebodega+" "+b.observacion}}</option>
                                </select>
                            </div>
                            <!--<span class="help-block error" ng-show="formCompra.bodega.$invalid && formCompra.bodega.$touched">La Bodega es requerida</span>-->
                        </div>

                    </fieldset>

                </div>
                <div class="col-xs-6">

                    <fieldset>
                        <legend>Datos Factura de Compra</legend>

                        <div class="col-xs-12" style="margin-top: 5px;">

                            <div class="input-group">
                                <span class="input-group-addon">Nro. Documento: </span>

                                <span class="input-group-btn" style="width: 15%;">
                                    <input type="text" class="form-control" id="t_establ" name="t_establ" ng-model="t_establ" ng-keypress="onlyNumber($event, 3, 't_establ')" ng-blur="calculateLength('t_establ', 3)" />
		                        </span>

                                <span class="input-group-btn" style="width: 15%;" >
		                            <input type="text" class="form-control" id="t_pto" name="t_pto" ng-model="t_pto" ng-keypress="onlyNumber($event, 3, 't_pto')" ng-blur="calculateLength('t_pto', 3)" />
		                        </span>

                                <input type="text" class="form-control" id="t_secuencial" name="t_secuencial" ng-model="t_secuencial" ng-keypress="onlyNumber($event, 9, 't_secuencial')" ng-blur="calculateLength('t_secuencial', 9)" />
                            </div>

                        </div>

                        <div class="col-sm-8 col-xs-12" style="margin-top: 5px;">
                            <div class="input-group">
                                <span class="input-group-addon">Fecha Emisión: </span>
                                <input type="text" class="form-control datepicker"  name="fechaemisioncompra" id="fechaemisioncompra" ng-model="fechaemisioncompra">
                            </div>
                        </div>

                        <div class="col-xs-12" style="margin-top: 5px;">
                            <div class="input-group">
                                <span class="input-group-addon">Nro Autorización: </span>
                                <input type="text" class="form-control" name="nroautorizacioncompra" ng-model="nroautorizacioncompra"
                                       id="nroautorizacioncompra" ng-required="true" ng-keypress="onlyNumber($event, 49, 'nroautorizacioncompra')" >
                            </div>
                            <span class="help-block error"
                                  ng-show="formCompra.autorizacionfacturaproveedor.$invalid && formCompra.autorizacionfacturaproveedor.$touched">La Autorización es requerida</span>

                        </div>

                        <div class="col-xs-12" style="margin-top: 5px;">
                            <div class="input-group">
                                <span class="input-group-addon">Sustento Tributario: </span>
                                <select ng-disabled="impreso" class="form-control" name="sustentotributario" id="sustentotributario" ng-model="sustentotributario" ng-required="true"
                                        ng-options="value.id as value.label for value in listsustentotributario" ng-change="getTipoComprobante()">
                                </select>
                            </div>
                            <span class="help-block error" ng-show="formCompra.codigosustento.$invalid && formCompra.codigosustento.$touched">El Sustento Tributario es requerido</span>
                        </div>

                        <div class="col-xs-12" style="margin-top: 5px;">
                            <div class="input-group">
                                <span class="input-group-addon">Tipo Comprobante: </span>
                                <select ng-disabled="impreso" class="form-control" name="tipocomprobante" id="tipocomprobante" ng-model="tipocomprobante" ng-required="true"
                                        ng-options="value.id as value.label for value in listtipocomprobante">
                                </select>
                            </div>
                            <span class="help-block error" ng-show="formCompra.tipocomprobante.$invalid && formCompra.tipocomprobante.$touched">El Tipo Comprobante es requerido</span>
                        </div>
                    </fieldset>

                </div>

            </div>

            <div class="col-xs-12 text-right" style="">
                <button type="button" class="btn btn-primary" style="float: right;" ng-click="createRow()" ng-disabled="impreso">
                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                </button>
            </div>

            <div class="col-xs-12" style="margin-top: 5px;">
                <table class="table table-responsive table-striped table-hover table-condensed">
                    <thead class="bg-primary">
                    <tr>
                        <td>Código Item</td>
                        <td style="width: 20%">Detalle</td>
                        <td>Cantidad</td>
                        <td>Precio Unitario</td>
                        <td>Descuento(%)</td>
                        <td>IVA</td>
                        <td>ICE</td>
                        <td>Total</td>
                        <td></td>
                    </tr>
                    </thead>
                    <tbody>
                    <tr ng-repeat="item in items">
                        <td>
                            <div>
                                <!--compras/getCodigoProducto-->
                                <!--LoadProductos-->
                                <angucomplete-alt id="codigoproducto{{$index}}"
                                                  pause="400"
                                                  selected-object="AsignarData"
                                                  selected-object-data = "item"
                                                  remote-url="{{url}}DocumentoVenta/LoadProductos/"
                                                  title-field="codigoproducto"
                                                  description-field="twitter"
                                                  minlength="1"
                                                  input-class="form-control form-control-small"
                                                  match-class="highlight"
                                                  field-required="true"
                                                  input-name="codigoproducto{{$index}}"
                                                  disable-input="impreso"
                                                  text-searching="Buscando Producto"
                                                  text-no-results="Producto no encontrado"
                                                  initial-value="item.producto";
                                />
                            </div>
                            <span class="help-block error" ng-show="formventa.codigoproducto{{$index}}.$invalid && formventa.codigoproducto{{$index}}.$touched">El producto es requerido.</span>
                        </td>
                        <td>
                            <input type="text" class="form-control" ng-show="!read"  disabled ng-value="item.productoObj.originalObject.nombreproducto" />
                            <input type="text" class="form-control" ng-show="read"  disabled ng-value="item.producto.nombreproducto" />
                            <!--<label class="control-label" ng-show="!read">{{ item.productoObj.originalObject.nombreproducto }}</label>
                            <label class="control-label" ng-show="read">{{  item.producto.nombreproducto }}</label>-->
                        </td>
                        <td><input type="text" class="form-control text-right" ng-keyup="CalculaValores();ValidaProducto()" ng-model="item.cantidad"/></td>
                        <td><input type="text" class="form-control text-right" ng-keyup="CalculaValores();ValidaProducto()" ng-keypress="onlyNumber($event, undefined, undefined)" ng-model="item.precioU" placeholder="{{item.productoObj.originalObject.precioventa}}" /></td>
                        <td><input type="text" class="form-control text-right" ng-keyup="CalculaValores();ValidaProducto()" ng-model="item.descuento"/></td>
                        <td><input type="text" class="form-control text-right" disabled ng-model="item.productoObj.originalObject.porcentiva"  /></td>
                        <td><input type="text" class="form-control text-right" disabled ng-model="item.productoObj.originalObject.porcentice"  /></td>
                        <td><input type="text" class="form-control text-right" ng-model="item.total" disabled  ng-value="item.cantidad*item.precioU"/></td>
                        <td>
                            <button type="button" class="btn btn-danger" ng-click="QuitarItem(item)">
                                <span class="glyphicon glyphicon glyphicon-trash" aria-hidden="true"></span>
                            </button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <div class="col-xs-8">

                <div class="col-xs-12">
                    <div class="input-group">
                        <span class="input-group-addon">Forma Pago: </span>
                        <select class="form-control" name="formapago" id="formapago" ng-model="formapago" ng-required="true" ng-disabled="impreso"
                                ng-options="value.id as value.label for value in listformapago">
                        </select>
                    </div>
                    <span class="help-block error"
                          ng-show="formCompra.formapago.$invalid && formCompra.formapago.$touched">La Forma Pago es requerida</span>
                </div>

                <div class="col-xs-12" style="margin-top: 15px;">
                    <textarea class="form-control" name="observacion" id="observacion" ng-model="observacion" cols="30" rows="5" placeholder="Observacion" ng-required="true"></textarea>
                    <span class="help-block error"
                          ng-show="formCompra.observacion.$invalid && formCompra.observacion.$touched">La Observación es requerida</span>
                </div>

                <div class="col-xs-12" style="margin-top: 15px;">
                    <fieldset>
                        <legend>Comprobante de Retención</legend>

                        <div class="col-xs-6">
                            <div class="input-group">
                                <span class="input-group-addon">Tipo de Pago: </span>
                                <select class="form-control" name="tipopago" id="tipopago" ng-model="tipopago"
                                        ng-options="value.id as value.label for value in listtipopago" ng-change="typeResident()">
                                </select>

                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="input-group">
                                <span class="input-group-addon">Pais Pago: </span>
                                <select class="form-control" name="paispago" id="paispago" ng-model="paispago"
                                        ng-options="value.id as value.label for value in listpaispago">
                                </select>

                            </div>
                        </div>

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
                                <input type="text" class="form-control datepicker" name="fechaemisioncomprobante" id="fechaemisioncomprobante" ng-model="fechaemisioncomprobante" />
                            </div>
                        </div>
                        <div class="col-xs-12" style="margin-top: 5px;">
                            <div class="input-group">
                                <span class="input-group-addon">Nro. Comprobante Retención: </span>
                                <span class="input-group-btn" style="width: 15%;">
	                    <input type="text" class="form-control" id="t_establ_c" name="t_establ_c" ng-model="t_establ_c" ng-keypress="onlyNumber($event, 3, 't_establ_c')" ng-blur="calculateLength('t_establ_c', 3)" />
	                </span>
                                <span class="input-group-btn" style="width: 15%;" >
	                    <input type="text" class="form-control" id="t_pto_c" name="t_pto_c" ng-model="t_pto_c" ng-keypress="onlyNumber($event, 3, 't_pto_c')" ng-blur="calculateLength('t_pto_c', 3)" />
	                </span>
                                <input type="text" class="form-control" id="t_secuencial_c" name="t_secuencial_c" ng-model="t_secuencial_c" ng-keypress="onlyNumber($event, 9, 't_secuencial_c')" ng-blur="calculateLength('t_secuencial_c', 9)" />
                            </div>
                        </div>
                        <div class="col-xs-12" style="margin-top: 5px;">
                            <div class="input-group">
                                <span class="input-group-addon">Nro Autorización Comprobante: </span>
                                <input type="text" class="form-control" name="noauthcomprobante" id="noauthcomprobante" ng-model="noauthcomprobante" />
                            </div>
                        </div>

                    </fieldset>
                </div>


                <div class="col-xs-12 text-right" style="margin-top: 20px;">

                    <button type="button" class="btn btn-primary" ng-click="InicioList();">
                        Registros <span class="glyphicon glyphicon glyphicon-th-list" aria-hidden="true"></span>
                    </button>

                    <button type="button" class="btn btn-default" id="btn-anular" ng-click="anular()" ng-disabled="guardado" >
                        Anular <span class="glyphicon glyphicon glyphicon-ban-circle" aria-hidden="true"></span>
                    </button>

                    <button type="button" class="btn btn-success" id="btn-save" ng-click="confirmSave()" ng-disabled="formCompra.$invalid" >
                        Guardar <span class="glyphicon glyphicon glyphicon-floppy-saved" aria-hidden="true"></span>
                    </button>

                </div>

            </div>

            <div class="col-xs-4">
                <table class="table table-responsive table-striped table-hover table-condensed table-bordered">
                    <tbody>
                    <tr>
                        <td style="width: 60%;">SubTotal con Impuesto</td>
                        <td class="text-right">{{Subtotalconimpuestos}}</td>
                    </tr>
                    <tr>
                        <td>SubTotal 0%</td>
                        <td class="text-right">{{Subtotalcero}}</td>
                    </tr>
                    <tr>
                        <td>SubTotal No Objeto IVA</td>
                        <td class="text-right">{{Subtotalnobjetoiva}}</td>
                    </tr>
                    <tr>
                        <td>SubTotal Exento IVA</td>
                        <td class="text-right">{{Subototalexentoiva}}</td>
                    </tr>
                    <tr>
                        <td>SubTotal Sin Impuestos</td>
                        <td class="text-right">{{Subtotalsinimpuestos}}</td>
                    </tr>
                    <tr>
                        <td>Total Descuento</td>
                        <td class="text-right">{{Totaldescuento}}</td>
                    </tr>
                    <tr>
                        <td>ICE</td>
                        <td><input type="text" class="form-control input-sm text-right" id="ValICE"  ng-model="ValICE"  /></td>
                    </tr>
                    <tr>
                        <td>IVA</td>
                        <td><input type="text" class="form-control input-sm text-right" id="ValIVA"  ng-model="ValIVA" /></td>
                    </tr>
                    <tr>
                        <td>IRBPNR</td>
                        <td><input type="text" class="form-control input-sm text-right" id="ValIRBPNR" ng-keyup="CalculaValores();"  ng-model="ValIRBPNR"/></td>
                    </tr>
                    <tr>
                        <td>PROPINA</td>
                        <td><input type="text" class="form-control input-sm text-right" id="ValPropina" ng-keyup="CalculaValores();"  ng-model="ValPropina" /></td>
                    </tr>
                    <tr>
                        <td>VALOR TOTAL</td>
                        <td class="text-right">{{ValorTotal}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>

        </form>

    </div>

    <!-- Modales -->

    <div class="modal fade" tabindex="-1" role="dialog" id="modalConfirmAnular">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header modal-header-danger">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Confirmación</h4>
                </div>
                <div class="modal-body">
                    <span>Está seguro que desea Anular la compra: <strong>"{{numseriecompra}}"</strong> seleccionada?</span>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        Cancelar <span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span>
                    </button>
                    <button type="button" class="btn btn-danger" id="btn-save" ng-click="anularCompra()">
                        Anular
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" id="modalMessage1">
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

    <div class="modal fade" tabindex="-1" role="dialog" id="modalConfirmAnular1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header modal-header-danger">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Confirmación</h4>
                </div>
                <div class="modal-body">
                    <span>Está seguro que desea anular la compra?</span>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" id="btn-save" ng-click="anularCompra()">Anular</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" id="modalConfirmSave">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header modal-header-primary">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Confirmación</h4>
                </div>
                <div class="modal-body">
                    <span>¿Desea guardar la Factura de Compra actual...? </span>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        Cancelar <span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span>
                    </button>
                    <button type="button" class="btn btn-primary" id="btn-save0" ng-click="save()">
                        Aceptar <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="msm" style="z-index: 8000;" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary" id="titulomsm">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Mensaje</h4>
                </div>
                <div class="modal-body">
                    <strong>{{Mensaje}}</strong>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar <i class="glyphicon glyphicon glyphicon-ban-circle"></i></button>
                </div>
            </div>
        </div>
    </div>

</div>




