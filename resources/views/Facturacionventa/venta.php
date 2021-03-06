<!DOCTYPE html>
<html lang="es-ES" ng-app="softver-aqua">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">

    <title>Documento venta</title>

    <link href="<?= asset('css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= asset('css/font-awesome.min.css') ?>" rel="stylesheet">
    <link href="<?= asset('css/index.css') ?>" rel="stylesheet">
    <link href="<?= asset('css/bootstrap-datetimepicker.min.css') ?>" rel="stylesheet">
    <link href="<?= asset('css/style_generic_app.css') ?>" rel="stylesheet">
    <link href="<?= asset('css/angucomplete-alt.css') ?>" rel="stylesheet">

    <style>
        .dataclient{
            font-weight: bold;
        }
    </style>

</head>
<body>

<div class="col-xs-12">

    <div class="col-xs-12">

        <h4>Facturación de Ventas</h4>

        <hr>

    </div>

    <!--<div  class="container-fluid" ng-controller="Venta" ng-cloak ng-init="NumeroRegistroVenta();AllDocVenta();GetBodegas();GetFormaPago();GetPuntodeVenta(); ConfigContable();">-->
    <div  class="col-xs-12" ng-controller="Venta" ng-cloak ng-init="GetPuntodeVenta(); ConfigContable();NumeroRegistroVenta();GetBodegas();GetFormaPago();GetCentroCosto();GetTipoComprobanteV();">

        <input type="hidden" ng-model="otherFactura" id="otherFactura" value="<?= $viewFactura ?>">

        <form class="form-horizontal" name="formventa" id="formventa"  novalidate="" >
            <div class="form-group" ng-show="VerFactura!=1" ng-hide="VerFactura==1">
                <div class="row-fluid">

                    <div class="col-xs-4 ">
                        <div class="form-group has-feedback">
                            <!--<input type="text" class="form-control " id="busquedaventa" placeholder="BUSCAR..." ng-model="busquedaventa" ng-keyup="pageChanged(1)">-->
                            <input type="text" class="form-control " id="busquedaventa" placeholder="BUSCAR..." ng-model="busquedaventa">
                            <span class="glyphicon glyphicon-search form-control-feedback" aria-hidden="true"></span>
                        </div>
                    </div>

                    <div class="col-xs-4 ">
                        <div class="input-group">
                            <span class="input-group-addon">Estado: </span>
                            <select ng-model="cmb_estado_fact" name="cmb_estado_fact" id="cmb_estado_fact" class="form-control" ng-change="pageChanged(1)">
                                <option value="A">NO ANULADAS</option>
                                <option value="I">ANULADAS</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-4 text-right" >
                        <button type="button" class="btn btn-primary" ng-disabled="Valida=='1' " ng-click="VerFactura=1; LimiarDataVenta();NumeroRegistroVenta();" title="Nueva Factura"><i class="glyphicon glyphicon-plus"></i></button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <table class="table table-responsive table-striped table-hover table-condensed table-bordered">
                            <thead class="bg-primary">
                            <tr>
                                <th style="width: 4%;"></th>
                                <th style="width: 10%;">FECHA EMISION</th>
                                <th style="width: 12%;">NUMERO FACTURA</th>
                                <th>CLIENTE</th>
                                <th style="width: 15%;">SUBTOTAL</th>
                                <th style="width: 10%;">IVA</th>
                                <th style="width: 15%;">TOTAL</th>
                                <th style="width: 10%;">ACCIONES</th>
                            </tr>
                            </thead>
                            <tbody>
                            <!--<tr ng-repeat="v in Allventas">-->
                            <tr dir-paginate="v in Allventas | orderBy:sortKey:reverse |filter:busquedaventa| itemsPerPage:10" total-items="totalItems" ng-cloak">
                            <td>{{$index+1}}</td>
                            <td class="text-center">{{v.fechaemisionventa}}</td>
                            <td class="text-center">{{numFactura(v)}}</td>
                            <td>{{v.cliente.persona.lastnamepersona+" "+v.cliente.persona.namepersona}}</td>
                            <td class="text-right">{{v.subtotalconimpuestoventa}}</td>
                            <td class="text-right">{{v.ivacompra}}</td>
                            <td class="text-right">{{v.valortotalventa}}</td>
                            <td class="text-center">

                                <div class="btn-group" role="group" aria-label="...">
                                    <button type="button" class="btn btn-info" ng-click="ViewVenta(v)">
                                        <span class="glyphicon glyphicon glyphicon-info-sign"   aria-hidden="true"></span>
                                    </button>
                                    <button type="button" class="btn btn-default" ng-click="AnularVentaDirecto(v.iddocumentoventa)">
                                        <span class="glyphicon glyphicon glyphicon-ban-circle" aria-hidden="true"></span>
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
                                max-size="10"
                                direction-links="true"
                                boundary-links="true" >

                        </dir-pagination-controls>
                    </div>
                </div>
            </div>
            <div class="form-group" ng-show="VerFactura==1" ng-hide="VerFactura!=1" ng-show="VerFactura==1" ng-hide="VerFactura!=1">


                <div class="row">
                    <div class="col-xs-6">

                        <fieldset>
                            <legend>Datos Cliente</legend>

                            <div class="col-sm-6 col-xs-12" style="margin-top: 5px;">
                                <div class="input-group">
                                    <span class="input-group-addon">Fecha Registro: </span>
                                    <input type="text" class="form-control datepicker" id="FechaRegistro" ng-model="FechaRegistro" />
                                </div>
                            </div>
                            <div class="col-sm-6 col-xs-12" style="margin-top: 5px;">
                                <div class="input-group">
                                    <span class="input-group-addon">No. Venta: </span>
                                    <input type="text" class="form-control" name="NoVenta" id="NoVenta" ng-model="NoVenta" ng-required="true" />
                                </div>
                                <span class="help-block error" ng-show="formventa.NoVenta.$invalid && formventa.NoVenta.$touched">El número es requerido</span>

                            </div>

                            <div class="col-xs-12" style="margin-top: 5px;">
                                <div class="input-group">
                                    <span class="input-group-addon">RUC: </span>
                                    <input type="text" class="form-control" name="DICliente" id="DICliente" ng-model="DICliente"  ng-keyup="BuscarCliente();" ng-blur="DICliente=Cliente.numdocidentific"  ng-required="true" ng-maxlength="13"  ng-pattern="/[0-9]+$/"
                                    />

                                </div>

                                <span class="help-block error" ng-show="formventa.DICliente.$invalid && formventa.DICliente.$touched">El Ruc/CI es requerido</span>
                                <span class="help-block error"
                                      ng-show="formventa.DICliente.$invalid && formventa.DICliente.$error.maxlength">La
                    longitud máxima es de 13 caracteres.</span> <span
                                        class="help-block error"
                                        ng-show="formventa.DICliente.$invalid && formventa.DICliente.$error.pattern">El Ruc/CI no es válido.</span>
                                <span class="help-block error" ng-show="mensaje">El Cliente no Existe.</span>


                            </div>
                            <div class="col-xs-12" style="margin-top: 5px;">
                                <div class="input-group">
                                    <span class="input-group-addon">Razón Social: </span>
                                    <input type="text" class="form-control" disabled id="RazonSocialCliente" ng-model="RazonSocialCliente" ng-value="Cliente.lastnamepersona+' '+Cliente.namepersona"/>
                                </div>
                            </div>
                            <div class="col-xs-12" style="margin-top: 5px;">
                                <div class="input-group">
                                    <span class="input-group-addon">Dirección: </span>
                                    <input type="text" class="form-control" disabled id="DireccionCliente" ng-model="DireccionCliente" ng-value="Cliente.direccion"/>
                                </div>
                            </div>
                            <div class="col-xs-12" style="margin-top: 5px;">
                                <div class="input-group">
                                    <span class="input-group-addon">Teléfono: </span>
                                    <input type="text" class="form-control" disabled id="TelefonoCliente" ng-model="TelefonoCliente" ng-value="Cliente.telefonoprincipaldomicilio"/>
                                </div>
                            </div>

                            <div class="col-sm-6 col-xs-12" style="margin-top: 5px;">
                                <div class="input-group">
                                    <span class="input-group-addon">IVA: </span>
                                    <input type="text" class="form-control" disabled id="IvaCLiente" ng-model="IvaCLiente"  ng-value="Cliente.nametipoimpuestoiva"/>
                                </div>
                            </div>
                            <div class="col-sm-6 col-xs-12" style="margin-top: 5px;">
                                <div class="input-group">
                                    <span class="input-group-addon">Bodega: </span>
                                    <select class="form-control" name="Bodega" id="Bodega" ng-model="Bodega" ng-change=" Validabodegaprodct='0' " >
                                        <option value="">-- Seleccione --</option>
                                        <option ng-repeat="b in Bodegas" value="{{b.idbodega}}">{{b.namebodega+" "+b.observacion}}</option>
                                    </select>
                                </div>

                                <!--<span class="help-block error" ng-show="formventa.Bodega.$invalid && formventa.Bodega.$touched">Seleccione una bodega</span>-->
                            </div>

                        </fieldset>

                    </div>
                    <div class="col-xs-6">

                        <fieldset>
                            <legend>Datos Factura de Venta</legend>

                            <div class="col-xs-12" style="margin-top: 5px;">
                                <div class="input-group">
                                    <span class="input-group-addon">Agente Venta: </span>
                                    <select class="form-control" id="AgenteVenta" ng-change="DataNoDocumento();" ng-model="AgenteVenta">
                                        <option value="">-- Seleccione --</option>
                                        <option ng-repeat=" p in PuntoVenta"  value="{{p.idpuntoventa}}">{{p.lastnamepersona+' '+p.namepersona}}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-xs-12" style="margin-top: 5px;">
                                <div class="input-group">
                                    <span class="input-group-addon">Nro. Documento: </span>
                                    <span class="input-group-btn" style="width: 15%;">
                        <input type="text" class="form-control" id="t_establ" name="t_establ" ng-model="t_establ" ng-keypress="onlyNumber($event, 3, 't_establ')" ng-blur="calculateLength('t_establ', 3)" required="true" />
                    </span>
                                    <span class="input-group-btn" style="width: 15%;" >
                        <input type="text" class="form-control" id="t_pto" name="t_pto" ng-model="t_pto" ng-keypress="onlyNumber($event, 3, 't_pto')" ng-blur="calculateLength('t_pto', 3)" required="true" />
                    </span>
                                    <input type="text" class="form-control" id="t_secuencial" name="t_secuencial" ng-model="t_secuencial" ng-keypress="onlyNumber($event, 9, 't_secuencial')" ng-blur="calculateLength('t_secuencial', 9)" />
                                </div>


                                <span class="help-block error" ng-show="formventa.t_establ.$invalid && formventa.t_establ.$touched">Es requerido el establecimiento</span>
                                <span class="help-block error" ng-show="formventa.t_pto.$invalid && formventa.t_pto.$touched">Es requerido el punto de venta</span>
                                <span class="help-block error" ng-show="formventa.t_secuencial.$invalid && formventa.t_secuencial.$touched">Es requerido el secuencial</span>

                            </div>

                            <div class="col-sm-6 col-xs-12" style="margin-top: 5px;">
                                <div class="input-group">
                                    <span class="input-group-addon">Fecha Emisión: </span>
                                    <input type="text" class="form-control datepicker" id="FechaEmision" ng-model="FechaEmision"/>
                                </div>
                            </div>

                            <!--<div class="col-xs-12" style="margin-top: 5px;">
                                <div class="input-group">
                                    <span class="input-group-addon">Nro Autorización: </span>
                                    <input type="text" class="form-control" name="NoAutorizacion" id="NoAutorizacion" ng-keypress="onlyNumber($event, 49, 'NoAutorizacion')" ng-model="NoAutorizacion" required="true" />
                                </div>
                                <span class="help-block error" ng-show="formventa.NoAutorizacion.$invalid && formventa.NoAutorizacion.$touched">Es requerido el Nro Autorización</span>
                            </div>-->




                            <div class="col-xs-12" style="margin-top: 5px;">
                                <div class="input-group">
                                    <span class="input-group-addon">Nro. Guía Remisión: </span>
                                    <span class="input-group-btn" style="width: 15%;">
                                        <input type="text" class="form-control" id="t_establ_guia" name="t_establ_guia" ng-model="t_establ_guia" ng-keypress="onlyNumber($event, 3, 't_establ_guia')" ng-blur="calculateLength('t_establ_guia', 3)" />
                                    </span>
                                    <span class="input-group-btn" style="width: 15%;" >
                                        <input type="text" class="form-control" id="t_pto_guia" name="t_pto_guia" ng-model="t_pto_guia" ng-keypress="onlyNumber($event, 3, 't_pto_guia')" ng-blur="calculateLength('t_pto_guia', 3)" />
                                    </span>
                                    <input type="text" class="form-control" id="t_secuencial_guia" name="t_secuencial_guia" ng-model="t_secuencial_guia" ng-keypress="onlyNumber($event, 9, 't_secuencial_guia')" ng-blur="calculateLength('t_secuencial_guia', 9)" />
                                </div>
                            </div>

                            <div class="col-xs-12" style="margin-top: 5px;">
                                <div class="input-group">
                                    <span class="input-group-addon">Tipo Comprobante: </span>
                                    <select ng-disabled="impreso" class="form-control" name="tipocomprobante" id="tipocomprobante" ng-model="tipocomprobante" ng-required="true"
                                            ng-options="value.id as value.label for value in listtipocomprobante">
                                    </select>
                                </div>
                                <span class="help-block error" ng-show="formventa.tipocomprobante.$invalid && formventa.tipocomprobante.$touched">El Tipo Comprobante es requerido</span>
                            </div>

                            <div class="col-xs-12" style="margin-top: 5px;">
                                <div class="input-group">
                                    <span class="input-group-addon">Centro de Costo: </span>
                                    <select class="form-control" name="departamento" id="departamento" ng-model="departamento"
                                            ng-options="value.id as value.label for value in listdepartamento"></select>
                                </div>
                            </div>


                        </fieldset>

                    </div>
                </div>

                <div class="col-xs-12 text-right" style="margin-top: 5px;">
                    <button type="button" class="btn btn-primary" ng-click="Agregarfila();">
                        <span class="glyphicon glyphicon glyphicon-plus" aria-hidden="true"></span>
                    </button>
                </div>

                <div class="col-xs-12" style="margin-top: 5px; font-size: 12px !important;">
                    <table class="table table-responsive table-striped table-hover table-condensed table-bordered">
                        <thead class="bg-primary">
                        <tr>
                            <th style="width: 15%">CODIGO ITEM</th>
                            <th>DETALLE</th>
                            <th style="width: 5%;">CANTIDAD</th>
                            <th style="width: 8%;">PRECIO UNIT.</th>
                            <th style="width: 5%;">DESC(%)</th>
                            <th style="width: 6%;">IVA</th>
                            <th style="width: 6%;">ICE</th>
                            <th style="width: 10%;">TOTAL</th>
                            <th style="width: 4%"></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr ng-repeat="item in items">
                            <td>
                                <div>
                                    <!--compras/getCodigoProducto-->
                                    <!--LoadProductos-->
                                    <!--<angucomplete-alt id="codigoproducto{{$index}}"
                                                      pause="400"
                                                      selected-object="item.productoObj"
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
                                                      initial-value="item.producto" focus-out="AsignarData(item);"; />-->

                                    <angucomplete-alt id="codigoproducto{{$index}}"
                                                      pause="400"
                                                      selected-object="AsignarData"
                                                      selected-object-data = "item"
                                                      remote-url="{{url}}DocumentoVenta/LoadProductos/"
                                                      title-field="codigoproducto"
                                                      description-field="twitter"
                                                      minlength="1"
                                                      input-class="form-control form-control-small disabled_enter"
                                                      match-class="highlight"
                                                      field-required="true"
                                                      input-name="codigoproducto{{$index}}"
                                                      disable-input="impreso"
                                                      text-searching="Buscando Producto"
                                                      text-no-results="Producto no encontrado"
                                                      initial-value="item.producto" ; />
                                </div>
                                <span class="help-block error" ng-show="formventa.codigoproducto{{$index}}.$invalid && formventa.codigoproducto{{$index}}.$touched">El producto es requerido.</span>
                            </td>
                            <td>
                                <input type="text" class="form-control" ng-show="!read"  disabled ng-value="item.productoObj.originalObject.nombreproducto" />
                                <input type="text" class="form-control" ng-show="read"  disabled ng-value="item.producto.nombreproducto" />
                                <!--<label class="control-label" ng-show="!read">{{ item.productoObj.originalObject.nombreproducto }}</label>
                                <label class="control-label" ng-show="read">{{  item.producto.nombreproducto }}</label>-->
                            </td>
                            <td><input type="text" class="form-control" ng-keyup="CalculaValores();ValidaProducto()" ng-model="item.cantidad"/></td>
                            <td><input type="text" class="form-control" ng-keyup="CalculaValores();ValidaProducto()" ng-model="item.precioU" placeholder="{{item.productoObj.originalObject.precioventa}}"  /></td>
                            <td><input type="text" class="form-control" ng-keyup="CalculaValores();ValidaProducto()" ng-model="item.descuento"/></td>
                            <td><input type="text" class="form-control" disabled ng-model="item.productoObj.originalObject.porcentiva"  /></td>
                            <td><input type="text" class="form-control" disabled ng-model="item.productoObj.originalObject.porcentice"  /></td>
                            <td><input type="text" class="form-control" ng-model="item.total" disabled  ng-value="item.cantidad*item.precioU"/></td>
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
                            <select class="form-control" id="cmbFormapago" ng-model="cmbFormapago" name="cmbFormapago" required="true">
                                <option value="">-- Seleccione --</option>
                                <option ng-repeat="f in Formapago" value="{{f.idformapago}}">{{f.codigosri+"-"+f.nameformapago}}</option>
                            </select>
                        </div>
                        <span class="help-block error" ng-show="formventa.cmbFormapago.$invalid && formventa.cmbFormapago.$touched">Es requerido la forma de pago</span>
                    </div>

                    <div class="col-xs-12" style="margin-top: 15px;">
                        <textarea class="form-control" name="observacion" id="observacion" ng-model="observacion" cols="30" rows="5" placeholder="Observacion"></textarea>
                    </div>




                    <div class="col-xs-12 text-right" style="margin-top: 20px;">
                        <button type="button" class="btn btn-primary" ng-click="VerFactura=2; pageChanged(); LimiarDataVenta();">
                            Registros <span class="glyphicon glyphicon glyphicon-th-list" aria-hidden="true"></span>
                        </button>
                        <button type="button" ng-click="AnularVenta();" ng-disabled="IdDocumentoVentaedit=='0' " class="btn btn-default">
                            Anular <span class="glyphicon glyphicon glyphicon-ban-circle" aria-hidden="true"></span>
                        </button>
                        <button type="button" class="btn btn-success" ng-click="IniGuardarFactura()" ng-disabled="formventa.$invalid || IdDocumentoVentaedit!='0' || Validabodegaprodct!='0' || ValidacionCueContExt!='0' ">
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
                            <td><input type="text" class="text-right form-control input-sm" id="ValICE"  ng-model="ValICE"  /></td>
                        </tr>
                        <tr>
                            <td>IVA</td>
                            <td><input type="text" class="text-right form-control input-sm" id="ValIVA"  ng-model="ValIVA" /></td>
                        </tr>
                        <tr ng-hide="1 == 1">
                            <td>IRBPNR</td>
                            <td><input type="text" class="text-right form-control input-sm" id="ValIRBPNR" ng-keyup="CalculaValores();"  ng-model="ValIRBPNR"/></td>
                        </tr>
                        <tr ng-hide="1 == 1">
                            <td>PROPINA</td>
                            <td><input type="text" class="text-right form-control input-sm" id="ValPropina" ng-keyup="CalculaValores();"  ng-model="ValPropina" /></td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold;">VALOR TOTAL</td>
                            <td style="font-weight: bold;" class="text-right">{{ValorTotal}}</td>
                        </tr>
                        </tbody>
                    </table>
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

            <div class="modal fade" id="ConfirmarVenta" style="z-index: 8000;" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header btn-success" id="ConfirmarVentaH">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Mensaje</h4>
                        </div>
                        <div class="modal-body">
                            <strong>Esta seguro de guardar la venta</strong>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar <i class="glyphicon glyphicon glyphicon-ban-circle"></i></button>
                            <button type="button" class="btn btn-success" ng-click="EnviarDatosGuardarVenta();" data-dismiss="modal">Guardar <i class="glyphicon glyphicon glyphicon-floppy-saved"></i></button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="ConfirmarAnulacion" style="z-index: 8000;" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header btn-success" id="ConfirmarVentaH">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Mensaje</h4>
                        </div>
                        <div class="modal-body">
                            <strong>Esta seguro de anular la venta</strong>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar <i class="glyphicon glyphicon glyphicon-ban-circle"></i></button>
                            <button type="button" class="btn btn-primary" ng-click="ConfirmarAnularventa();" data-dismiss="modal">Aceptar <i class="glyphicon glyphicon glyphicon-ok"></i></button>
                        </div>
                    </div>
                </div>
            </div>

        </form>
    </div>



</div>

<script src="<?= asset('app/lib/angular/angular.min.js') ?>"></script>
<script src="<?= asset('app/lib/angular/angular-route.min.js') ?>"></script>

<script src="<?= asset('app/lib/angular/ng-file-upload-shim.min.js') ?>"></script>
<script src="<?= asset('app/lib/angular/ng-file-upload.min.js') ?>"></script>

<script src="<?= asset('app/lib/angular/dirPagination.js') ?>"></script>

<script src="<?= asset('js/jquery.min.js') ?>"></script>
<script src="<?= asset('js/bootstrap.min.js') ?>"></script>
<script src="<?= asset('js/menuLateral.js') ?>"></script>
<script src="<?= asset('js/moment.min.js') ?>"></script>
<script src="<?= asset('js/es.js') ?>"></script>
<script src="<?= asset('js/bootstrap-datetimepicker.min.js') ?>"></script>

<script src="<?= asset('app/lib/angular/angucomplete-alt.min.js') ?>"></script>


<script src="<?= asset('app/app.js') ?>"></script>


<script src="<?= asset('app/controllers/venta.js') ?>"></script>




</body>
</html>

    
