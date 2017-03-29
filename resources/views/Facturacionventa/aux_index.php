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





<div  class="container-fluid" ng-controller="facturacioventa" ng-cloak  ng-init="HeadInfoFacturaVenta();FormaPagoVenta(); ConfigContable();FiltrarVenta();LoadDataToFiltro();">


    <div ng-show="ActivaVenta=='0'" ng-hide="ActivaVenta=='1'">
      <div class="row">
        <hr/>
        
        <div class="col-xs-2">
          
          <div class="input-group ">
              <input type="text" class="form-control" ng-keyup="FiltrarVenta();" placeholder="Buscar" ng-model="F_RucCliente">
              <label class="input-group-addon" ><i class="glyphicon glyphicon-search"></i> </label>
              
            </div>

        </div>
        <div class="col-xs-2">
          <div class="input-group ">
              <label class="input-group-addon" id="basic-addon1"><i class="glyphicon glyphicon-tag"></i> </label>
              <select class="form-control" ng-model="F_PuntoVeta" ng-change="FiltrarVenta();" >
                <option value="">Punto de venta</option>
                <option ng-repeat=" ipv in auxf_PuntoVenta " value="ipv.idpuntoventa">{{ipv.idpuntoventa}}</option>
              </select>
            </div>
        </div>


        <div class="col-xs-2">
            <div class="input-group ">
              <label class="input-group-addon" id="basic-addon1"><i class="glyphicon glyphicon-tag"></i> </label>
              <select class="form-control" ng-model="F_Establecimiento" ng-change="FiltrarVenta();">
                <option value="">Establecimiento</option>
                <option ng-repeat=" ie in auxf_establecimiento " value="ie.idestablecimiento">{{ie.idestablecimiento}}</option>
              </select>
            </div>
        </div>
        
        <div class="col-xs-2">
            <div class="input-group ">
              <label class="input-group-addon" id="basic-addon1"><i class="glyphicon glyphicon-tag"></i> </label>
              <select class="form-control" ng-model="F_Estado" ng-change="FiltrarVenta();">
                <option value="">Estado</option>
                <option value="t">Pagada</option>
                <option value="f">No pagada</option>
              </select>
            </div>
        </div>

        <div class="col-xs-2">
            <div class="input-group ">
              <label class="input-group-addon" id="basic-addon1"><i class="glyphicon glyphicon-tag"></i> </label>
              <select class="form-control" ng-model="F_Anulada" ng-change="FiltrarVenta();">
                <option value="">Estado Anulada</option>
                <option value="f">Activa</option>
                <option value="t">Cancelada</option>
              </select>
            </div>
        </div>
        <div class="col-xs-2"> 
          <button class="btn btn-success" ng-click="ActivaVenta='1'; NumeroRegistroVenta();">
            <i class="glyphicon glyphicon-plus"></i>
            Nueva Venta
          </button>
        </div>
      
      </div>

      <div class="row">
        <div class="col-xs-12"> 
          <table class="table table-bordered table-striped table-condensend">
            <thead>
              <tr class="bg-primary">
                <th  ng-click="sort('codigoventa')"  >
                  No. Documento
                  <span class="glyphicon sort-icon" ng-show="sortKey=='codigoventa'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                </th>
                <th>Fecha Ingreso</th>
                <th>Razon Social</th>
                <th>Subtotal</th>
                <th>Iva</th>
                <th>Total</th>
                <th>Estado</th>
                <th>Anuladas</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              <tr dir-paginate="item in Registroventas|orderBy:sortKey:reverse|itemsPerPage:2" >
                <td>{{item.codigoventa}}</td>
                <td>{{item.fecharegistrocompra}}</td>
                <td>{{item.apellidos+' '+item.nombres }}</td>
                <td>{{item.subtotalivaventa}}</td>
                <td>{{item.ivaventa}}</td>
                <td>{{item.totalventa}}</td>
                <td>{{(item.estapagada)?'Pagado':'No Pagado'}}</td>
                <td>{{(item.estaanulada)?'Cancelada':'Activa'}}</td>
                <td>
                  
                  <button class="btn btn-info" ng-click="EditDocVenta(item)" title="Editar"><i class="glyphicon glyphicon-edit"></i> </button>
                  <button class="btn btn-danger" ng-click="AnularVenta(item);" title="Anular"><i class="glyphicon glyphicon-trash"></i> </button>

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



















    <div ng-show="ActivaVenta=='1'"  ng-hide="ActivaVenta=='0'">
      <div class="row">
          <div class="col-xs-12 text-right">

            <a   ng-click="Excel();" ng-hide="CodigoDocumentoVenta==''" ng-show=" CodigoDocumentoVenta!='' ">
              <img ng-src="../../img/excel.png" style="height: 40px" >
            </a>
            <a ng-click="print();" ng-hide="CodigoDocumentoVenta==''" ng-show=" CodigoDocumentoVenta!='' ">
              <img ng-src="../../img/pdf.png" style="height: 40px" >
            </a>
              <!--<button class="btn btn-success"><i class="glyphicon glyphicon-th"></i> </button>
              <button class="btn btn-primary"><i class="glyphicon glyphicon-file"></i> </button>
              <button class="btn btn-info"><i class="glyphicon glyphicon-print"></i> </button>-->
          </div>
      </div>
      <div class="row">
          <div class="col-xs-6">
              <div class="input-group date datepicker">
                <label class="input-group-addon" id="basic-addon1"><i class="fa fa-calendar"></i> Fecha Registro</label>
                <input type="text" class="form-control input-sm" id="aux_FechaRegistro" ng-model="FechaRegistro" >
                <label class="input-group-addon"  ><i class="fa fa-calendar"></i></label>
              </div>
          </div>
          <div class="col-xs-6">
              <div class="input-group">
                <span class="input-group-addon" id="basic-addon1"><i class="glyphicon glyphicon-tag"></i> Registro de venta </span>
                <input type="text" class="form-control input-sm" ng-model="NRegistroVenta" readonly >
              </div>
          </div>
      </div>

      <div class="row">
          <div class="col-xs-12">
              <hr/>
              <strong>Datos cliente</strong>
          </div>
      </div>

      <div class="row">
          <div class="col-xs-6">
              <div class="input-group">
                <span class="input-group-addon" id="basic-addon1"><i class="glyphicon glyphicon-tag"></i> RUC/CI: </span>
                <input type="text" class="form-control input-sm" ng-keyup="BuscarCliente()" ng-model="RUCCI" >
                <span class="input-group-addon btn" ng-click="BuscarCliente()"><i class="glyphicon glyphicon-search"></i> </span>
              </div>
          </div>
          <div class="col-xs-6">
            <div class="input-group">
                <span class="input-group-addon" id="basic-addon1"><i class="glyphicon glyphicon-tag"></i> Razon Social</span>
                <input type="text" class="form-control input-sm"  value="{{CLiente[0].apellidos +' '+ CLiente[0].nombres}}" readonly >
            </div>
          </div>
      </div>

      <div class="row">
          <div class="col-xs-6">
            <div class="input-group">
                <span class="input-group-addon" id="basic-addon1"><i class="glyphicon glyphicon-tag"></i> Telefono</span>
                <input type="text" class="form-control input-sm"  value="{{CLiente[0].telefonoprincipaldomicilio}}" readonly >
            </div>
          </div>
          <div class="col-xs-6">
            <div class="input-group">
                <span class="input-group-addon" id="basic-addon1"><i class="glyphicon glyphicon-tag"></i> Direccion</span>
                <input type="text" class="form-control input-sm"  value="{{CLiente[0].direcciondomicilio}}" readonly >
            </div>
          </div>
      </div>

      <div class="row">
          <div class="col-xs-12">
              <hr/>
              <strong>Datos documento</strong>
          </div>
      </div>

      <div class="row" >
          <div class="col-xs-6" >
              <div class="row" >
                <div class="col-xs-5" >
                  <div class="input-group" >
                  <span class="input-group-addon" id="basic-addon1"><i class="glyphicon glyphicon-filter"></i> No. Documento</span>
                    <input type="text"  class="form-control input-sm" maxlength="3"  ng-model="Establecimiento" >
                  </div>
                </div>
                <div class="col-xs-2" style="padding: 0px;">
                  <div class="input-group">
                    <input type="text" class="form-control input-sm" maxlength="3" ng-model="PuntoDeVenta" >
                  </div>
                </div>
                <div class="col-xs-5" >
                  <div class="input-group">
                    <input type="text" class="form-control input-sm" maxlength="13" ng-model="Numero"  >
                    <span class="input-group-addon" id="basic-addon1"><i class="glyphicon glyphicon-filter"></i></span>
                  </div>
                </div>
              </div>
            </div>
          

          <div class="col-xs-6">
              <div class="input-group" >
                <span class="input-group-addon" id="basic-addon1"><i class="glyphicon glyphicon-tag"></i> Autorizacion</span>
                <input type="text"  class="form-control input-sm" maxlength="37" ng-model="Autorizacion" >
              </div>
          </div>
      </div>

      <div class="row">
          <div class="col-xs-6">
              <div class="input-group" >
                <span class="input-group-addon" id="basic-addon1"><i class="fa fa-list-alt "></i></span>
                <select class="form-control" ng-model="pago">
                  <option value="0">Forma de pago</option>
                  <option ng-selected="{{fp.value == ''}}" ng-repeat="fp in Formapago" value="{{fp.codigoformapago}}">{{fp.nombreformapago}}</option>
                </select>
                <!--<select class="form-control" ng-model="pago" ng-options="fp.codigoformapago as fp.nombreformapago for fp in Formapago">
                  <option value="0">Forma de pago</option>
                </select>-->
              </div>
          </div>

          <div class="col-xs-6">
              <div class="input-group" >
                <span class="input-group-addon" id="basic-addon1"><i class="glyphicon glyphicon-user"></i> Vendedor</span>
                <input type="text"  class="form-control input-sm" ng-model="Vendor"  readonly>
              </div>
          </div>
      </div>

      <div class="row">
          <div class="col-xs-12">
              <hr/> 
              <strong>Detalle de la venta</strong>
          </div>
      </div>

      <div class="row">
          <div class="col-xs-1">
              <!--<button class="btn btn-success" title="Agregar Producto O Servicio" ng-click="AddFilaDetalleVenta()" ><i class="glyphicon glyphicon-plus"></i></button>-->
              <button class="btn btn-primary" title="Agregar Producto O Servicio" ng-click="SelectProductoServicio();" ><i class="glyphicon glyphicon-plus"></i></button>
          </div>
          <div class="col-xs-12">
              <table class="table table-bordered table-striped">
                  <thead>
                      <tr class="bg-primary">
                          <th></th>
                          <th></th>
                          <th>Tipo Venta</th>
                          <th>Bodega</th>
                          <th>Cod. Prod</th>
                          <th>Detalle</th>
                          <th>Cantidad</th>
                          <th>PVP Unitario</th>
                          <th>IVA</th>
                          <th>Total</th>
                      </tr>
                  </thead>
                  <tbody>
                      <tr ng-repeat="i in DetalleVenta" >
                          <td>{{$index+1}}</td>
                          <td>
                              <button class="btn btn-danger" ng-click="QuitarItem(i);CalculaTotalesVenta()"><i class="glyphicon glyphicon-trash"></i></button>
                          </td>
                          <td>
                              <!--<select class="form-control"  ng-model="i.TipoItem">
                                  <option value="P">Producto</option>
                                  <option value="S">Servicio</option>
                              </select>-->
                              <div ng-show="i.TipoItem=='P'" ng-hide="i.TipoItem=='S'">
                                <span>Producto</span>
                              </div>
                              <div ng-show="i.TipoItem=='S'" ng-hide="i.TipoItem=='P'" >
                                <span>Servicio</span>
                              </div>
                          </td>
                          <td>
                              <input type="text" class="form-control" ng-disabled="i.TipoItem=='S'"  ng-model='i.Bodega' readonly >

                              <!--<div ng-hide="i.TipoItem=='S'" >
                                  <angucomplete-alt 
                                        id="nombrebodega{{$index}}"                                               
                                        pause="400"
                                        selected-object="i.Bodega"                                                  
                                        remote-url="{{API_URL}}/DocumentoVenta/getBodega/"
                                        title-field="idbodega,nombrebodega"
                                        description-field="twitter"                                                 
                                        minlength="1"
                                        input-class="form-control form-control-small"
                                        match-class="highlight"
                                        field-required="true"
                                        input-name="nombrebodega{{$index}}"
                                        disable-input="impreso"
                                        text-searching="Buscando Bodega"
                                        text-no-results="Bodega no encontrada"
                                        initial-value="i.IdBodega"
                                         />
                              </div>-->
                              <!--<span class="help-block error" ng-show="formCompra.nombrebodega{{$index}}.$invalid && formCompra.nombrebodega{{$index}}.$touched">La bodega es requerida.</span>-->
                          </td>
                          <td>
                              <input type="text" class="form-control" ng-model='i.CodProducto' readonly>

                              <!--<div>
                                  <angucomplete-alt 
                                    id="codigoproducto{{$index}}"                                             
                                    pause="400"
                                    selected-object="i.Detalle"                                                  
                                    remote-url="{{API_URL}}/DocumentoVenta/getProducto/"
                                    title-field="nombreproducto"
                                    description-field="twitter"                                                 
                                    minlength="1"
                                    input-class="form-control form-control-small"
                                    match-class="highlight"
                                    field-required="true"
                                    input-name="codigoproducto{{$index}}"
                                    disable-input="impreso"
                                    text-searching="Buscando Producto"
                                    text-no-results="Producto no encontrado"
                                    initial-value="i.CodProducto"
                                     />
                              </div>-->
                              <!--<span class="help-block error" ng-show="formCompra.codigoproducto{{$index}}.$invalid && formCompra.codigoproducto{{$index}}.$touched">El producto es requerido.</span>-->
                          </td>
                          <td>
                              <input type="text" class="form-control" ng-model='i.Descripcion' readonly>
                          </td>
                          <td>
                              <input type="number" min="1" class="form-control" ng-keyup="CalculaTotalesVenta()" ng-disabled="i.TipoItem=='S'" ng-model='i.Cantidad'>
                          </td>
                          <td>
                              <input type="text" class="form-control" ng-keyup="CalculaTotalesVenta()" ng-model='i.PVPUnitario'>
                          </td>
                          <td>
                              <input type="text" class="form-control" ng-keyup="CalculaTotalesVenta()" ng-model='i.IVA'>
                          </td>
                          <td>
                              <strong> {{ (i.Cantidad)*(i.PVPUnitario)}} </strong>
                          </td>
                      </tr>
                  </tbody>
              </table>
              
          </div>
          
      </div>


      <div class="row">
          <div class="col-xs-6">
              <div class="row">
                  <div class="col-xs-12">
                      <hr/>
                      <strong>Comentario</strong>
                      <textarea class="form-control" ng-model="Comentario" ></textarea>
                  </div>
              </div>
              <div class="row" >
                  <div class="col-xs-12" style="padding: 2%;">
                      <button class="btn btn-primary" ng-click="SaveVenta();"><i class="glyphicon glyphicon-floppy-disk"></i> Guardar</button>


                      <button class="btn btn-success" ng-click="InicioList();" ><i class="glyphicon glyphicon-th-list"></i> Registro Ventas</button>
                      
                      <button class="btn btn-danger" ng-click="AnularVenta();" ng-hide="CodigoDocumentoVenta==''" ng-show=" CodigoDocumentoVenta!='' " ><i class="glyphicon glyphicon-trash"></i> Anular</button>


                      <button class="btn btn-info" ng-click="CobrarVenta();" ng-hide="CodigoDocumentoVenta==''" ng-show=" CodigoDocumentoVenta!='' " ><i class="glyphicon glyphicon-cog"></i> Cobrar</button>

                      <button class="btn btn-info" ng-click="Excel();" ng-hide="CodigoDocumentoVenta==''" ng-show=" CodigoDocumentoVenta!='' " ><i class="glyphicon glyphicon-file"></i> Excel</button>

                      <button class="btn btn-info" ng-click="print();" ng-hide="CodigoDocumentoVenta==''" ng-show=" CodigoDocumentoVenta!='' " ><i class="glyphicon glyphicon-cog"></i> Imprimir</button>
                      
                  </div>
              </div>
          </div>

          <div class="col-xs-6">
              <div class="col-xs-6">
                  <div class="col-xs-12">
                      <div class="input-group" >
                        <input type="text"  class="form-control input-sm" ng-keyup="CalculaTotalesVenta();"  ng-model="PorcentajeDescuento" >
                        <span class="input-group-addon" >% Descuento</span>
                      </div>
                  </div>
              </div>
              <div class="col-xs-6">
                  <div class="col-xs-12">
                      <div class="input-group" >
                        <span class="input-group-addon"  >Subtotal 14%</span>
                        <input type="text"  class="form-control input-sm"  ng-model="SubtotalIva"  readonly>
                      </div>
                  </div>
                  <div class="col-xs-12">
                      <div class="input-group" >
                        <span class="input-group-addon"  >Subtotal 0%</span>
                        <input type="text"  class="form-control input-sm" ng-model="SubtotalCero" readonly>
                      </div>
                  </div>
                  <div class="col-xs-12">
                      <div class="input-group" >
                        <span class="input-group-addon"  >Descuento</span>
                        <input type="text"  class="form-control input-sm"  ng-model="Descuento"  readonly>
                      </div>
                  </div>
                  <div class="col-xs-12">
                      <div class="input-group" >
                        <span class="input-group-addon" >Otros</span>
                        <input type="text"  class="form-control input-sm" ng-model="Otros"   readonly>
                      </div>
                  </div>
                  <div class="col-xs-12">
                      <div class="input-group" >
                        <span class="input-group-addon" >IVA</span>
                        <input type="text"  class="form-control input-sm" ng-model="Iva"  readonly>
                      </div>
                  </div>
                  <div class="col-xs-12">
                      <div class="input-group" >
                        <span class="input-group-addon" >Total</span>
                        <input type="text"  class="form-control input-sm"  ng-model="Total"  readonly>
                      </div>
                  </div>
              </div>

          </div>
      </div>


    </div>
 







<div class="modal fade" id="MBuscarProductoServicio" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Añadir</h4>
      </div>
      <div class="modal-body">
        
        <div class="row">
          <div class="col-xs-6">
              <div class="input-group" >
                <span class="input-group-addon" ><i class="glyphicon glyphicon-plus"></i> </span>
                <select class="form-control" ng-model="Aux_AddProductoServicio" ng-change="LoadServicios();">
                  <option value="">Seleccione</option>
                  <option value="P">Producto</option>
                  <option value="S">Servicio</option>
                </select>
              </div>
          </div>
        </div>

        <!--Producto-->
        <div class="row" ng-show="Aux_AddProductoServicio=='P'" ng-hide="Aux_AddProductoServicio=='S' || Aux_AddProductoServicio==''">
          <div class="col-xs-6">
              <div class="input-group" >
                <span class="input-group-addon" ><i class="glyphicon glyphicon-th-list"></i> </span>
                <select class="form-control" ng-model="Aux_AddBodega" ng-change="SearchProducto();">
                  <option value="">Seleccione una bodega</option>
                  <option ng-repeat=" ib in Bodegas" value="{{ib.idbodega}}">{{ib.nombrebodega}}</option>
                </select>
              </div>
            </div>
            <div class="col-xs-12">
              <table class="table table-bordered table-striped table-condensend">
                <thead>
                  <tr class="bg-info">
                    <th></th>
                    <th>Detalle</th>
                    <th>Cantidad</th>
                    <th>F. Ingreso</th>
                  </tr>
                </thead>
                <tbody>
                  <tr ng-repeat="ip in ProductoPorBodega">
                    <td>
                      <input type="checkbox" ng-click="AgregarIntemTem(ip);" >
                    </td>
                    <td>{{ip.nombreproducto}}</td>
                    <td>{{ip.cantidadproductobodega}}</td>
                    <td>{{ip.fechaingreso}}</td>
                  </tr>
                </tbody>
              </table>
            </div>
        </div>
        <!--Servicio-->
        <div class="row" ng-show="Aux_AddProductoServicio=='S'" ng-hide="Aux_AddProductoServicio=='P' || Aux_AddProductoServicio==''">
          <div class="col-xs-12">
            <table class="table table-bordered table-condensend table-striped">
              <thead>
                <tr class="bg-success">
                  <th></th>
                  <th>Codigo</th>
                  <th>Descripcion</th>
                </tr>
              </thead>
              <tbody>
                <tr ng-repeat=" iser in AllServiciosV">
                  <td>
                    <input type="checkbox" ng-click="AgregarIntemTemSer(iser)" >
                  </td>
                  <td>{{iser.idservicio}}</td>
                  <td>{{iser.nombreservicio}}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>


      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" ng-click="ConfirItemProduc();" >Cargar</button>
      </div>
    </div>
  </div>
</div>





<div class="modal fade" id="Msm" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div id="titulomsm" class="modal-header ">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Informacion</h4>
      </div>
      <div class="modal-body">
        <strong>{{Mensaje}}</strong>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="AnularVenta" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div id="titulomsm" class="modal-header btn-danger ">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Informacion</h4>
      </div>
      <div class="modal-body">
        <strong>Esta seguro que dese anular la facura</strong>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" ng-click="ConfirAnulacion();" >Aceptar</button>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="CobrarVenta" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div id="titulomsm" class="modal-header btn-primary ">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Informacion</h4>
      </div>
      <div class="modal-body">
        <strong>Esta seguro que dese cobrar la facura</strong>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" ng-click="ConfirmarCobro();" >Aceptar</button>
      </div>
    </div>
  </div>
</div>



<div class="modal fade"  id="WPrint" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header btn-primary">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Documento de venta</h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-xs-12" id="bodyprint">
            
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
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

    
    <script src="<?= asset('app/controllers/facturacionventa.js') ?>"></script>



    


    

    


    <script type="text/javascript">
        /* $(function() {
         $(document).keydown(function(e){
         var code = (e.keyCode ? e.keyCode : e.which);
         if(code == 116) {
         e.preventDefault();
         alert('no puedes we');
         }
         });
         });*/
    </script>

    </body>
</html>
