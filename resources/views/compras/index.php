<!doctype html>
<html lang="es-ES" ng-app="softver-aqua">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Facturacion Compras</title>

    <link href="<?= asset('css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= asset('css/font-awesome.min.css') ?>" rel="stylesheet">
    <link href="<?= asset('css/index.css') ?>" rel="stylesheet">
    <link href="<?= asset('css/bootstrap-datetimepicker.min.css') ?>" rel="stylesheet">
    <link href="<?= asset('css/angucomplete-alt.css') ?>" rel="stylesheet">
    <link href="<?= asset('css/style_generic_app.css') ?>" rel="stylesheet">

    <style>
        .modal-body {
            max-height: calc(100vh - 210px);
            overflow-y: auto;
        }
    </style>

</head>

<body>



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
                <div class="form-group has-feedback">
                    <select class="form-control" name="proveedorFiltro" id="proveedorFiltro" ng-model="proveedorFiltro"
                            ng-change="searchByFilter()" ng-options="value.id as value.label for value in proveedor">
                    </select>
                </div>
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

            <div class="col-xs-12">
                <table class="table table-responsive table-striped table-hover table-condensed">
                    <thead class="bg-primary">
                    <tr>
                        <th style="text-align: center;" ng-click="sort('codigocompra')">
                            Código
                            <span class="glyphicon sort-icon" ng-show="sortKey=='codigocompra'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                        </th>
                        <th style="text-align: center;" ng-click="sort('fecharegistrocompra')">
                            Fecha Ingreso
                            <span class="glyphicon sort-icon" ng-show="sortKey=='fecharegistrocompra'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                        </th>
                        <th style="text-align: center;" ng-click="sort('razonsocialproveedor')">
                            Proveedor
                            <span class="glyphicon sort-icon" ng-show="sortKey=='razonsocialproveedor'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                        </th>
                        <th style="text-align: center;">Subtotal</th>
                        <th style="text-align: center;">IVA</th>
                        <th style="text-align: center;" ng-click="sort('totalcompra')">
                            Total
                            <span class="glyphicon sort-icon" ng-show="sortKey=='totalcompra'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                        </th>
                        <th style="text-align: center;" ng-click="sort('estapagada')">
                            Estado
                            <span class="glyphicon sort-icon" ng-show="sortKey=='estapagada'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                        </th>
                        <th style="width: 20%;">Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr dir-paginate="item in compras|orderBy:sortKey:reverse|itemsPerPage:10" >
                        <td style="text-align: center;">{{item.iddocumentocompra}}</td>
                        <td>{{formatoFecha(item.fecharegistrocompra)}}</td>
                        <td>{{item.razonsocial}}</td>
                        <td>{{ sumar(item.subtotalconimpuestocompra,item.subtotalcerocompra) }}</td>
                        <td>{{item.ivacompra  }}</td>
                        <td>{{item.valortotalcompra}}</td>
                        <td>{{(item.estadoanulado)?'Anulada':'No Anulada'}}</td>
                        <td>
                            <button type="button" class="btn btn-warning" ng-click="openForm(item.iddocumentocompra)" ng-disabled="item.estaAnulada==1"
                                    data-toggle="tooltip" data-placement="bottom" >
                                Editar <span class="glyphicon glyphicon-edit" aria-hidden="true">
                            </button>
                            <button type="button" class="btn btn-danger" ng-click="showModalConfirm(item.iddocumentocompra,0)"
                                    data-toggle="tooltip" data-placement="bottom" title="Anular"  ng-disabled="item.estaAnulada==1">
                                Anular <span class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span>
                            </button>

                        </td>
                    </tr>
                    </tbody>
                </table>
                <dir-pagination-controls
                    max-size="5"
                    class="pull-left"
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

        <div class="modal fade" tabindex="-1" role="dialog" id="modalConfirmAnular">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header modal-header-danger">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Confirmación</h4>
                    </div>
                    <div class="modal-body">
                        <span>Está seguro que desea Anular la compra seleccionada?</span>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" id="btn-save" ng-click="anularCompra()">Anular</button>
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
                                <input type="text" class="form-control" value="{{('000000'+compra.codigocompra).slice(-7)}}" readonly >
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
                                <select class="form-control" name="Bodega" id="Bodega" ng-model="Bodega" ng-change=" Validabodegaprodct='0' " ng-required="true">
                                    <option value="">-- Seleccione --</option>
                                    <option ng-repeat="b in Bodegas" value="{{b.idbodega}}">{{b.namebodega+" "+b.observacion}}</option>
                                </select>
                            </div>
                            <span class="help-block error" ng-show="formCompra.bodega.$invalid && formCompra.bodega.$touched">La Bodega es requerida</span>
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
                                                  initial-value="item.producto" focus-out="AsignarData(item);"; />
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
                        <td><input type="text" class="form-control" ng-keyup="CalculaValores();ValidaProducto()" ng-model="item.precioU" placeholder="{{item.productoObj.originalObject.precioventa}}" /></td>
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
                        <select class="form-control" name="formapago" id="formapago" ng-model="formapago" ng-required="true" ng-disabled="impreso"
                                ng-options="value.id as value.label for value in listformapago">
                        </select>
                    </div>
                    <span class="help-block error"
                          ng-show="formCompra.idformapago.$invalid && formCompra.idformapago.$touched">La Forma Pago es requerida</span>
                </div>

                <div class="col-xs-12" style="margin-top: 15px;">
                    <textarea class="form-control" name="observacion" id="observacion" ng-model="observacion" cols="30" rows="5" placeholder="Observacion"></textarea>
                </div>

                <div class="col-xs-12" style="margin-top: 15px;">
                    <fieldset>
                        <legend>Comprobante de Retención</legend>

                        <div class="col-xs-6">
                            <div class="input-group">
                                <span class="input-group-addon">Tipo de Pago: </span>
                                <select class="form-control" name="codigoformapago" id="codigoformapago" ng-model="compra.codigoformapago3">
                                    <option value="">-- Seleccione --</option>
                                    <option ng-repeat="item in TiposPago"
                                            value="{{item.idpagoresidente}}">{{item.tipopagoresidente}}
                                    </option>
                                </select>

                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="input-group">
                                <span class="input-group-addon">Pais Pago: </span>
                                <select class="form-control" name="pais" id="pais" ng-model="compra.codigopais"  >
                                    <option value="">-- Seleccione --</option>
                                    <option value="999">-- Seleccione --</option>
                                    <option ng-repeat="item in paises"
                                            value="{{item.idpagopais}}">{{item.pais }}
                                    </option>
                                </select>

                            </div>
                        </div>

                        <div class="col-xs-6" style="margin-top: 5px;">
                            <div class="input-group">
                                <span class="input-group-addon">Régimen Fiscal?: </span>
                                <select class="form-control">
                                    <option value="1">SI</option>
                                    <option value="2">NO</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-6" style="margin-top: 5px;">
                            <div class="input-group">
                                <span class="input-group-addon">Convenio doble Tributación?: </span>
                                <select class="form-control">
                                    <option value="1">SI</option>
                                    <option value="2">NO</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-xs-6" style="margin-top: 5px;">
                            <div class="input-group">
                                <span class="input-group-addon">Aplicación de Norma Legal?: </span>
                                <select class="form-control">
                                    <option value="1">SI</option>
                                    <option value="2">NO</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-6" style="margin-top: 5px;">
                            <div class="input-group">
                                <span class="input-group-addon">Fecha Emisión Comprobante: </span>
                                <input type="text" class="form-control" />
                            </div>
                        </div>
                        <div class="col-xs-12" style="margin-top: 5px;">
                            <div class="input-group">
                                <span class="input-group-addon">Nro. Comprobante Retención: </span>
                                <span class="input-group-btn" style="width: 15%;">
	                    <input type="text" class="form-control" id="t_establ" name="t_establ" ng-model="t_establ" ng-keypress="onlyNumber($event, 3, 't_establ')" ng-blur="calculateLength('t_establ', 3)" />
	                </span>
                                <span class="input-group-btn" style="width: 15%;" >
	                    <input type="text" class="form-control" id="t_pto" name="t_pto" ng-model="t_pto" ng-keypress="onlyNumber($event, 3, 't_pto')" ng-blur="calculateLength('t_pto', 3)" />
	                </span>
                                <input type="text" class="form-control" id="t_secuencial" name="t_secuencial" ng-model="t_secuencial" ng-keypress="onlyNumber($event, 9, 't_secuencial')" ng-blur="calculateLength('t_secuencial', 9)" />
                            </div>
                        </div>
                        <div class="col-xs-12" style="margin-top: 5px;">
                            <div class="input-group">
                                <span class="input-group-addon">Nro Autorización Comprobante: </span>
                                <input type="text" class="form-control" />
                            </div>
                        </div>

                    </fieldset>
                </div>




                <div class="col-xs-12 text-right" style="margin-top: 20px;">
                    <button type="button" class="btn btn-warning" id="btn-anular" ng-click="showModalConfirm1()" ng-disabled="!guardado" >
                        Anular <span class="glyphicon glyphicon glyphicon-ban-circle" aria-hidden="true"></span>
                    </button>

                    <button type="button" class="btn btn-primary" id="btn-save" ng-click="save()" ng-disabled="formCompra.$invalid" >
                        Guardar <span class="glyphicon glyphicon glyphicon-floppy-saved" aria-hidden="true"></span>
                    </button>
                    <button class="btn btn-success" ng-click="InicioList();"> Regresar</button>
                </div>

            </div>

            <div class="col-xs-4">
                <table class="table table-responsive table-striped table-hover table-condensed table-bordered">
                    <tbody>
                    <tr>
                        <td style="width: 60%;">SubTotal con Impuesto</td>
                        <td>{{Subtotalconimpuestos}}</td>
                    </tr>
                    <tr>
                        <td>SubTotal 0%</td>
                        <td>{{Subtotalcero}}</td>
                    </tr>
                    <tr>
                        <td>SubTotal No Objeto IVA</td>
                        <td>{{Subtotalnobjetoiva}}</td>
                    </tr>
                    <tr>
                        <td>SubTotal Exento IVA</td>
                        <td>{{Subototalexentoiva}}</td>
                    </tr>
                    <tr>
                        <td>SubTotal Sin Impuestos</td>
                        <td>{{Subtotalsinimpuestos}}</td>
                    </tr>
                    <tr>
                        <td>Total Descuento</td>
                        <td>{{Totaldescuento}}</td>
                    </tr>
                    <tr>
                        <td>ICE</td>
                        <td><input type="text" class="form-control input-sm" id="ValICE"  ng-model="ValICE"  /></td>
                    </tr>
                    <tr>
                        <td>IVA</td>
                        <td><input type="text" class="form-control input-sm" id="ValIVA"  ng-model="ValIVA" /></td>
                    </tr>
                    <tr>
                        <td>IRBPNR</td>
                        <td><input type="text" class="form-control input-sm" id="ValIRBPNR" ng-keyup="CalculaValores();"  ng-model="ValIRBPNR"/></td>
                    </tr>
                    <tr>
                        <td>PROPINA</td>
                        <td><input type="text" class="form-control input-sm" id="ValPropina" ng-keyup="CalculaValores();"  ng-model="ValPropina" /></td>
                    </tr>
                    <tr>
                        <td>VALOR TOTAL</td>
                        <td>{{ValorTotal}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>

        </form>

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

</div>


</div>

</body>

<script src="<?= asset('app/lib/angular/angular.min.js') ?>"></script>
<script src="<?= asset('app/lib/angular/angular-route.min.js') ?>"></script>


<script src="<?= asset('app/lib/angular/ng-file-upload-shim.min.js') ?>"></script>
<script src="<?= asset('app/lib/angular/ng-file-upload.min.js') ?>"></script>


<script src="<?= asset('js/jquery.min.js') ?>"></script>
<script src="<?= asset('js/bootstrap.min.js') ?>"></script>
<script src="<?= asset('js/moment.min.js') ?>"></script>
<script src="<?= asset('js/es.js') ?>"></script>
<script src="<?= asset('js/bootstrap-datetimepicker.min.js') ?>"></script>
<script src="<?= asset('app/lib/angular/angucomplete-alt.min.js') ?>"></script>
<script src="<?= asset('app/lib/angular/dirPagination.js') ?>"></script>

<script src="<?= asset('app/app.js') ?>"></script>
<script src="<?= asset('app/controllers/comprasController.js') ?>"></script>


</html>