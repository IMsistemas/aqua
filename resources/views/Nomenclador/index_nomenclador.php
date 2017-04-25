<!doctype html>
<html lang="en" ng-app="softver-aqua">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Configuracion de Nomencladores</title>

    <link type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" />
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.1.1.min.js"></script>



</head>

<body>
<div ng-controller="NomencladorController" ng-init="CargadataProvincia();">
    <div id="dvTab" style="margin-top: 5px;">
        <ul class="nav nav-tabs" role="tablist">
            <li ng-click="CargadataProvincia()" role="presentation" class="active tabs"><a href="#empresa" aria-controls="empresa" role="tab" data-toggle="tab"> Empresa</a></li>
            <li ng-click="CargadataFormaPago()" role="presentation" class="tabs"><a href="#contabilidad" aria-controls="contabilidad" role="tab" data-toggle="tab"> Contabilidad</a></li>
            <li ng-click="CargadataTPdoc()" role="presentation" class="tabs"><a href="#sri" aria-controls="sri" role="tab" data-toggle="tab"> SRI</a></li>
        </ul>
        <div class="tab-content">

            <div role="tabpanel" class="tab-pane fade active in" id="empresa" >

                <div id="dvTab1" style="margin-top: 5px;">
                    <ul class="nav nav-tabs" role="tablist">
                        <li ng-click="CargadataProvincia()" role="presentation" class="active tabs"><a href="#provincia" aria-controls="provincia" role="tab" data-toggle="tab">Provincias</a></li>
                        <li ng-click="CargadataCanton(); CargadataProvinciaEX();" role="presentation" class="tabs"><a href="#canton" aria-controls="canton" role="tab" data-toggle="tab">Cantones</a></li>
                        <li ng-click="CargadataParroquia(); CargadataCantonA()" role="presentation" class="tabs"><a href="#parroquia" aria-controls="parroquia" role="tab" data-toggle="tab">Parroquias</a></li>
                    </ul>
                    <!-- Tab panels -->
                    <div class="tab-content" style="padding-top: 10px;">
                        <div role="tabpanel" class="tab-pane fade active in" id="provincia">
                            <div class="col-xs-12">
                                <div class="col-xs-6">
                                    <div class="form-group has-feedback">
                                        <input type="text" class="form-control" placeholder="BUSCAR..." ng-model="busqueda" ng-change="CargadataProvincia(1)" >
                                        <span class="glyphicon glyphicon-search form-control-feedback" aria-hidden="true"></span>
                                    </div>
                                </div>
                                <div class="col-xs-6 text-right">
                                    <button type="button" class="btn btn-primary" style="float: right;" ng-click="toggle('add', 0,'prov')">
                                        Agregar <span class="glyphicon glyphicon glyphicon-plus" aria-hidden="true"></span>
                                    </button>
                                </div>
                            </div>
                            <div class="col-xs-12">
                                <table class="table table-responsive table-striped table-hover table-condensed">
                                    <thead class="bg-primary">
                                    <tr>
                                        <td style="width: 70%;">Provincia</td>
                                        <td>Acciones</td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr dir-paginate="provi in provincia | orderBy:sortKey:reverse | itemsPerPage:8" total-items="totalItemsprv" pagination-id="provinciapg" current-page="currentPage" ng-cloak">
                                    <td>{{provi.nameprovincia}}</td>
                                    <td>
                                        <button type="button" class="btn btn-warning" ng-click="toggle('edit',provi.idprovincia,'prov')">
                                            Editar <span class="glyphicon glyphicon glyphicon-edit" aria-hidden="true"></span>
                                        </button>
                                        <button type="button" class="btn btn-danger" ng-click="showModalConfirm(provi,'prov')">
                                            Eliminar <span class="glyphicon glyphicon glyphicon-trash" aria-hidden="true"></span>
                                        </button>
                                    </td>
                                    </tr>
                                    </tbody>
                                </table>
                                <dir-pagination-controls pagination-id="provinciapg"
                                                         on-page-change="CargadataProvincia(newPageNumber)"
                                                         class="pull-right"
                                                         max-size="8"
                                                         direction-links="true"
                                                         boundary-links="true" >

                                </dir-pagination-controls>
                            </div>
                        </div>

                        <div role="tabpanel" class="tab-pane fade" id="canton">
                            <div class="col-xs-12">
                                <div class="col-xs-6">
                                    <div class="form-group has-feedback">
                                        <input type="text" class="form-control" placeholder="BUSCAR..." ng-model="busqueda" ng-change="CargadataCanton(1)" >
                                        <span class="glyphicon glyphicon-search form-control-feedback" aria-hidden="true"></span>
                                    </div>
                                </div>
                                <div class="col-xs-6 text-right">
                                    <button type="button" class="btn btn-primary" style="float: right;" ng-click="toggle('add', 0,'canton')">
                                        Agregar <span class="glyphicon glyphicon glyphicon-plus" aria-hidden="true"></span>
                                    </button>
                                </div>
                            </div>
                            <div class="col-xs-12">
                                <table class="table table-responsive table-striped table-hover table-condensed">
                                    <thead class="bg-primary">
                                    <tr>
                                        <td style="width: 35%;">Provincia</td>
                                        <td style="width: 35%;">Cantón</td>
                                        <td>Acciones</td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr dir-paginate="cant in canton | orderBy:sortKey:reverse | itemsPerPage:8" total-items="totalItemscanton" current-page="currentPage" pagination-id="cantonpg" ng-cloak >
                                        <td>{{cant.nameprovincia}}</td>
                                        <td>{{cant.namecanton}}</td>
                                        <td>
                                            <button type="button" class="btn btn-warning" ng-click="toggle('edit',cant.idcanton,'canton')">
                                                Editar <span class="glyphicon glyphicon glyphicon-edit" aria-hidden="true"></span>
                                            </button>
                                            <button type="button" class="btn btn-danger" ng-click="showModalConfirm(cant,'canton')">
                                                Eliminar <span class="glyphicon glyphicon glyphicon-trash" aria-hidden="true"></span>
                                            </button>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                                <dir-pagination-controls pagination-id="cantonpg"
                                                         on-page-change="CargadataCanton(newPageNumber)"
                                                         class="pull-right"
                                                         max-size="8"
                                                         direction-links="true"
                                                         boundary-links="true" >

                                </dir-pagination-controls>
                            </div>
                        </div>

                        <div role="tabpanel" class="tab-pane fade" id="parroquia">
                            <div class="col-xs-12">
                                <div class="col-xs-6">
                                    <div class="form-group has-feedback">
                                        <input type="text" class="form-control" placeholder="BUSCAR..." ng-model="busqueda" ng-change="CargadataParroquia(1)"
                                        <span class="glyphicon glyphicon-search form-control-feedback" aria-hidden="true"></span>
                                    </div>
                                </div>
                                <div class="col-xs-6 text-right">
                                    <div class="col-xs-6 text-right">
                                        <button type="button" class="btn btn-primary" style="float: right;" ng-click="toggle('add', 0,'parroquia')">
                                            Agregar <span class="glyphicon glyphicon glyphicon-plus" aria-hidden="true"></span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12">
                                <table class="table table-responsive table-striped table-hover table-condensed">
                                    <thead class="bg-primary">
                                    <tr>
                                        <td style="width: 35%;">Cantón</td>
                                        <td style="width: 35%;">Parroquía</td>
                                        <td>Acciones</td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr dir-paginate="parq in parroquia | orderBy:sortKey:reverse | itemsPerPage:10" total-items="totalItemsparroquia" current-page="currentPage" pagination-id="parroquiapg" ng-cloak >
                                        <td>{{parq.namecanton}}</td>
                                        <td>{{parq.nameparroquia}}</td>
                                        <td>
                                            <button type="button" class="btn btn-warning" ng-click="toggle('edit',parq.idparroquia,'parroquia')">
                                                Editar <span class="glyphicon glyphicon glyphicon-edit" aria-hidden="true"></span>
                                            </button>
                                            <button type="button" class="btn btn-danger" ng-click="showModalConfirm(parq,'parroquia')">
                                                Eliminar <span class="glyphicon glyphicon glyphicon-trash" aria-hidden="true"></span>
                                            </button>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                                <dir-pagination-controls pagination-id="parroquiapg"
                                                         on-page-change="CargadataParroquia(newPageNumber)"
                                                         class="pull-right"
                                                         max-size="8"
                                                         direction-links="true"
                                                         boundary-links="true" >

                                </dir-pagination-controls>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div role="tabpanel" class="tab-pane fade" id="contabilidad">

                <div id="dvTab2" style="margin-top: 5px;">
                    <ul class="nav nav-tabs" role="tablist">
                        <li  ng-click="CargadataFormaPago()" role="presentation" class="active tabs"><a href="#cont_formapago" aria-controls="cont_formapago" role="tab" data-toggle="tab">Forma Pago</a></li>
                    </ul>
                    <!-- Tab panels -->
                    <div class="tab-content" style="padding-top: 10px;">
                        <div role="tabpanel" class="tab-pane fade active in" id="cont_formapago">
                            <div class="col-xs-12">
                                <div class="col-xs-6">
                                    <div class="form-group has-feedback">
                                        <input type="text" class="form-control" placeholder="BUSCAR..." ng-model="busqueda" ng-change="CargadataFormaPago(1)" >
                                        <span class="glyphicon glyphicon-search form-control-feedback" aria-hidden="true"></span>
                                    </div>
                                </div>
                                <div class="col-xs-6 text-right">
                                    <button type="button" class="btn btn-primary" style="float: right;" ng-click="toggle('add', 0,'formapago')">
                                        Agregar <span class="glyphicon glyphicon glyphicon-plus" aria-hidden="true"></span>
                                    </button>
                                </div>
                            </div>
                            <div class="col-xs-12">
                                <table class="table table-responsive table-striped table-hover table-condensed">
                                    <thead class="bg-primary">
                                    <tr>
                                        <td style="width: 35%;">Forma de Pago</td>
                                        <td style="width: 20%;">Código SRI</td>
                                        <td style="width: 15%;">Estado</td>
                                        <td>Acciones</td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr dir-paginate="FormaPago in Con_FormaPago | orderBy:sortKey:reverse | itemsPerPage:8" total-items="totalItemsformapago" current-page="currentPage" pagination-id="formapagopg" ng-cloak>
                                        <td>{{FormaPago.nameformapago}}</td>
                                        <td>{{FormaPago.codigosri}}</td>
                                        <td>
                                            <div ng-if="FormaPago.estado">
                                                Activo
                                            </div>
                                            <div ng-if="!FormaPago.estado">
                                                No Activo
                                            </div>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-warning" ng-click="toggle('edit', FormaPago.idformapago,'formapago')">
                                                Editar <span class="glyphicon glyphicon glyphicon-edit" aria-hidden="true"></span>
                                            </button>
                                            <button type="button" class="btn btn-danger" ng-click="showModalConfirm(FormaPago,'formapago')">
                                                Eliminar <span class="glyphicon glyphicon glyphicon-trash" aria-hidden="true"></span>
                                            </button>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                                <dir-pagination-controls pagination-id="formapagopg"
                                                         on-page-change="CargadataFormaPago(newPageNumber)"
                                                         class="pull-right"
                                                         max-size="8"
                                                         direction-links="true"
                                                         boundary-links="true" >

                                </dir-pagination-controls>
                            </div>
                        </div>
                    </div>
                </div>

            </div>



            <div role="tabpanel" class="tab-pane fade" id="sri" >
                <div id="dvTab3" style="margin-top: 5px;">
                    <ul class="nav nav-tabs" role="tablist">
                        <li ng-click="CargadataTPdoc()" role="presentation" class="active tabs"><a href="#sri_tiposdocumentos" aria-controls="sri_tiposdocumentos" role="tab" data-toggle="tab">Tipos de Documentos</a></li>
                        <li ng-click="CargadataTPident()" role="presentation" class="tabs"><a href="#sri_tiposidentificacion" aria-controls="sri_tiposidentificacion" role="tab" data-toggle="tab">Tipos de Identificación</a></li>
                        <li ng-click="CargadataTPimp()" role="presentation" class="tabs"><a href="#sri_tiposimpuestos" aria-controls="sri_tiposimpuestos" role="tab" data-toggle="tab">Tipos de Impuestos</a></li>
                        <li ng-click="CargadataImpIVA(); CargadataTPimpEx()" role="presentation" class="tabs"><a href="#sri_tiposimpuestosiva" aria-controls="sri_tiposimpuestosiva" role="tab" data-toggle="tab">Impuestos IVA</a></li>
                        <li ng-click="CargadataImpICE(); CargadataTPimpEx()"role="presentation" class="tabs"><a href="#sri_tiposimpuestosice" aria-controls="sri_tiposimpuestosice" role="tab" data-toggle="tab">Impuestos ICE</a></li>
                        <li ng-click="CargadataTipoImpRetenc()" role="presentation" class="tabs"><a href="#sri_tiposimpuestosretencion" aria-controls="sri_tiposimpuestosretencion" role="tab" data-toggle="tab"> Impuestos Retención</a></li>
                        <li ng-click="CargadataImpIVARENTA(); CargadataTipoImpRetenc()" role="presentation" class="tabs"><a href="#sri_tiposimpuestosretencioniva_renta" aria-controls="sri_tiposimpuestosretencioniva_renta" role="tab" data-toggle="tab">Imp. Retención IVA-RENTA</a></li>
                        <li ng-click="CargadataSustentoTrib()" role="presentation" class="tabs"><a href="#sri_sustentotributario" aria-controls="sri_sustentotributario" role="tab" data-toggle="tab">Sustentos Tributarios</a></li>
                        <li ng-click="CargadataComprobante(); CargadataSustentoTribEX()" role="presentation" class="tabs"><a href="#sri_tiposcomprobantes" aria-controls="sri_tiposcomprobantes" role="tab" data-toggle="tab">Tipos de Comprobantes</a></li>
                        <li ng-click="CargadataPagoResidente()" role="presentation" class="tabs"><a href="#sri_tipospagoresidentes" aria-controls="sri_tipospagoresidentes" role="tab" data-toggle="tab">Tipos de Pago Residente</a></li>
                        <li ng-click="CargadataPagoPais()" role="presentation" class="tabs"><a href="#sri_pagopais" aria-controls="sri_pagopais" role="tab" data-toggle="tab">País Pago</a></li>
                    </ul>
                    <!-- Tab panels -->
                    <div class="tab-content" style="padding-top: 10px;">
                        <div role="tabpanel" class="tab-pane fade active in" id="sri_tiposdocumentos">
                            <div class="col-xs-12">
                                <div class="col-xs-6">
                                    <div class="form-group has-feedback">
                                        <input type="text" class="form-control" placeholder="BUSCAR..." ng-model="busqueda" ng-change="CargadataTPdoc(1)" >
                                        <span class="glyphicon glyphicon-search form-control-feedback" aria-hidden="true"></span>
                                    </div>
                                </div>
                                <div class="col-xs-6 text-right">
                                    <button type="button" class="btn btn-primary" style="float: right;" ng-click="toggle('add', 0,'tpdocsri')">
                                        Agregar <span class="glyphicon glyphicon glyphicon-plus" aria-hidden="true"></span>
                                    </button>
                                </div>
                            </div>
                            <div class="col-xs-12">
                                <table class="table table-responsive table-striped table-hover table-condensed">
                                    <thead class="bg-primary">
                                    <tr>
                                        <td style="width: 35%;">Tipo de Documento</td>
                                        <td style="width: 20%;">Código SRI</td>
                                        <td style="width: 15%;">Estado</td>
                                        <td>Acciones</td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr dir-paginate="tipodocumento in sri_tipodocumento | orderBy:sortKey:reverse | itemsPerPage:8" total-items="totalItemstpdoc" current-page="currentPage" pagination-id="tpdocpg01" ng-cloak>
                                        <td>{{tipodocumento.nametipodocumento}}</td>
                                        <td>{{tipodocumento.codigosri}} </td>
                                        <td>
                                            <div ng-if="tipodocumento.estado">
                                                Activo
                                            </div>
                                            <div ng-if="!tipodocumento.estado">
                                                No Activo
                                            </div>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-warning" ng-click="toggle('edit', tipodocumento.idtipodocumento,'tpdocsri')">
                                                Editar <span class="glyphicon glyphicon glyphicon-edit" aria-hidden="true"></span>
                                            </button>
                                            <button type="button" class="btn btn-danger" ng-click="showModalConfirm(tipodocumento,'tpdocsri')">
                                                Eliminar <span class="glyphicon glyphicon glyphicon-trash" aria-hidden="true"></span>
                                            </button>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                                <dir-pagination-controls pagination-id="tpdocpg01"
                                                         on-page-change="CargadataTPdoc(newPageNumber)"
                                                         class="pull-right"
                                                         max-size="8"
                                                         direction-links="true"
                                                         boundary-links="true" >
                                </dir-pagination-controls>
                            </div>
                        </div>



                        <div role="tabpanel" class="tab-pane fade" id="sri_tiposidentificacion">
                            <div class="col-xs-12">
                                <div class="col-xs-6">
                                    <div class="form-group has-feedback">
                                        <input type="text" class="form-control" placeholder="BUSCAR..." ng-model="busqueda" ng-change="CargadataTPident(1)" >
                                        <span class="glyphicon glyphicon-search form-control-feedback" aria-hidden="true"></span>
                                    </div>
                                </div>
                                <div class="col-xs-6 text-right">
                                    <button type="button" class="btn btn-primary" style="float: right;" ng-click="toggle('add', 0,'tpidentsri')">
                                        Agregar <span class="glyphicon glyphicon glyphicon-plus" aria-hidden="true"></span>
                                    </button>
                                </div>
                            </div>
                            <div class="col-xs-12">
                                <table class="table table-responsive table-striped table-hover table-condensed">
                                    <thead class="bg-primary">
                                    <tr>
                                        <td style="width: 35%;">Tipo de Identificación</td>
                                        <td style="width: 20%;">Código SRI</td>
                                        <td style="width: 15%;">Estado</td>
                                        <td>Acciones</td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr dir-paginate="tipoidentificacion in sri_tipoidentificacion | orderBy:sortKey:reverse | itemsPerPage:8" total-items="totalItemstpident" current-page="currentPage" pagination-id="tpidentpg" ng-cloak>
                                        <td>{{tipoidentificacion.nameidentificacion}}</td>
                                        <td>{{tipoidentificacion.codigosri}}</td>
                                        <td>
                                            <div ng-if="tipoidentificacion.estado">
                                                Activo
                                            </div>
                                            <div ng-if="!tipoidentificacion.estado">
                                                No Activo
                                            </div>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-warning" ng-click="toggle('edit',tipoidentificacion.idtipoidentificacion,'tpidentsri')">
                                                Editar <span class="glyphicon glyphicon glyphicon-edit" aria-hidden="true"></span>
                                            </button>
                                            <button type="button" class="btn btn-danger" ng-click="showModalConfirm(tipoidentificacion,'tpidentsri')">
                                                Eliminar <span class="glyphicon glyphicon glyphicon-trash" aria-hidden="true"></span>
                                            </button>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                                <dir-pagination-controls pagination-id="tpidentpg"
                                                         on-page-change="CargadataTPident(newPageNumber)"
                                                         class="pull-right"
                                                         max-size="8"
                                                         direction-links="true"
                                                         boundary-links="true" >
                                </dir-pagination-controls>
                            </div>
                        </div>


                        <div role="tabpanel" class="tab-pane fade" id="sri_tiposimpuestos">
                            <div class="col-xs-12">
                                <div class="col-xs-6">
                                    <div class="form-group has-feedback">
                                        <input type="text" class="form-control" placeholder="BUSCAR..." ng-model="busqueda" ng-change="CargadataTPimp(1)" >
                                        <span class="glyphicon glyphicon-search form-control-feedback" aria-hidden="true"></span>
                                    </div>
                                </div>
                                <div class="col-xs-6 text-right">
                                    <button type="button" class="btn btn-primary" style="float: right;" ng-click="toggle('add', 0,'timpsri')">
                                        Agregar <span class="glyphicon glyphicon glyphicon-plus" aria-hidden="true"></span>
                                    </button>
                                </div>
                            </div>
                            <div class="col-xs-12">
                                <table class="table table-responsive table-striped table-hover table-condensed">
                                    <thead class="bg-primary">
                                    <tr>
                                        <td style="width: 35%;">Tipo de Impuesto</td>
                                        <td style="width: 20%;">Código SRI</td>
                                        <td style="width: 15%;">Estado</td>
                                        <td>Acciones</td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr dir-paginate="tipoimpuesto in sri_tipoimpuesto | orderBy:sortKey:reverse | itemsPerPage:8" total-items="totalItemstpimp" current-page="currentPage" pagination-id="tpimppg" ng-cloak >
                                        <td>{{tipoimpuesto.nameimpuesto}}</td>
                                        <td>{{tipoimpuesto.codigosri}}</td>
                                        <td>
                                            <div ng-if="tipoimpuesto.estado">
                                                Activo
                                            </div>
                                            <div ng-if="!tipoimpuesto.estado">
                                                No Activo
                                            </div>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-warning" ng-click="toggle('edit',tipoimpuesto.idtipoimpuesto,'timpsri')">
                                                Editar <span class="glyphicon glyphicon glyphicon-edit" aria-hidden="true"></span>
                                            </button>
                                            <button type="button" class="btn btn-danger" ng-click="showModalConfirm(tipoimpuesto,'timpsri')">
                                                Eliminar <span class="glyphicon glyphicon glyphicon-trash" aria-hidden="true"></span>
                                            </button>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                                <dir-pagination-controls pagination-id="tpimppg"
                                                         on-page-change="CargadataTPimp(newPageNumber)"
                                                         class="pull-right"
                                                         max-size="8"
                                                         direction-links="true"
                                                         boundary-links="true" >
                                </dir-pagination-controls>
                            </div>
                        </div>

                        <div role="tabpanel" class="tab-pane fade" id="sri_tiposimpuestosiva">
                            <div class="col-xs-12">
                                <div class="col-xs-6">
                                    <div class="form-group has-feedback">
                                        <input type="text" class="form-control" placeholder="BUSCAR..." ng-model="busqueda" ng-change="CargadataImpIVA(1)" >
                                        <span class="glyphicon glyphicon-search form-control-feedback" aria-hidden="true"></span>
                                    </div>
                                </div>
                                <div class="col-xs-6 text-right">
                                    <button type="button" class="btn btn-primary" style="float: right;" ng-click="toggle('add', 0,'tpimpivasri')">
                                        Agregar <span class="glyphicon glyphicon glyphicon-plus" aria-hidden="true"></span>
                                    </button>
                                </div>
                            </div>
                            <div class="col-xs-12">
                                <table class="table table-responsive table-striped table-hover table-condensed">
                                    <thead class="bg-primary">
                                    <tr>
                                        <td style="width: 35%;">Tipo de Impuesto IVA</td>
                                        <td style="width: 10%;">Código SRI</td>
                                        <td style="width: 10%;">Porcentaje</td>
                                        <td style="width: 15%;">Estado</td>
                                        <td>Acciones</td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr dir-paginate="tipoimpuestoiva in sri_tipoimpuestoIVA | orderBy:sortKey:reverse | itemsPerPage:8" total-items="totalItemstpimpiva" current-page="currentPage" pagination-id="tpimpivapg" ng-cloak >
                                        <td>{{tipoimpuestoiva.nametipoimpuestoiva}}</td>
                                        <td>{{tipoimpuestoiva.codigosri}}</td>
                                        <td>{{tipoimpuestoiva.porcentaje}}</td>
                                        <td>
                                            <div ng-if="tipoimpuestoiva.estado">
                                                Activo
                                            </div>
                                            <div ng-if="!tipoimpuestoiva.estado">
                                                No Activo
                                            </div>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-warning" ng-click="toggle('edit',tipoimpuestoiva.idtipoimpuestoiva,'tpimpivasri')">
                                                Editar <span class="glyphicon glyphicon glyphicon-edit" aria-hidden="true"></span>
                                            </button>
                                            <button type="button" class="btn btn-danger" ng-click="showModalConfirm(tipoimpuestoiva,'tpimpivasri')">
                                                Eliminar <span class="glyphicon glyphicon glyphicon-trash" aria-hidden="true"></span>
                                            </button>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                                <dir-pagination-controls pagination-id="tpimpivapg"
                                                         on-page-change="CargadataImpIVA(newPageNumber)"
                                                         class="pull-right"
                                                         max-size="8"
                                                         direction-links="true"
                                                         boundary-links="true" >
                                </dir-pagination-controls>
                            </div>
                        </div>

                        <div role="tabpanel" class="tab-pane fade" id="sri_tiposimpuestosice">
                            <div class="col-xs-12">
                                <div class="col-xs-6">
                                    <div class="form-group has-feedback">
                                        <input type="text" class="form-control" placeholder="BUSCAR..." ng-model="busqueda" ng-change="CargadataImpICE(1)" >
                                        <span class="glyphicon glyphicon-search form-control-feedback" aria-hidden="true"></span>
                                    </div>
                                </div>
                                <div class="col-xs-6 text-right">
                                    <button type="button" class="btn btn-primary" style="float: right;" ng-click="toggle('add', 0,'tpimpicesri')">
                                        Agregar <span class="glyphicon glyphicon glyphicon-plus" aria-hidden="true"></span>
                                    </button>
                                </div>
                            </div>
                            <div class="col-xs-12">
                                <table class="table table-responsive table-striped table-hover table-condensed">
                                    <thead class="bg-primary">
                                    <tr>
                                        <td style="width: 35%;">Tipo de Impuesto ICE</td>
                                        <td style="width: 10%;">Código SRI</td>
                                        <td style="width: 10%;">Porcentaje</td>
                                        <td style="width: 10%;">Tarifa</td>
                                        <td style="width: 15%;">Estado</td>
                                        <td>Acciones</td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr dir-paginate="tipoimpuestoice in sri_tipoimpuestoICE | orderBy:sortKey:reverse | itemsPerPage:8" total-items="totalItemstpimpicepg" current-page="currentPage" pagination-id="tpimpicepg" ng-cloak  >
                                        <td>{{tipoimpuestoice.nametipoimpuestoice}}</td>
                                        <td>{{tipoimpuestoice.codigosri}}</td>
                                        <td>{{tipoimpuestoice.porcentaje}}</td>
                                        <td>{{tipoimpuestoice.tarifaespecifica}}</td>
                                        <td>
                                            <div ng-if="tipoimpuestoice.estado">
                                                Activo
                                            </div>
                                            <div ng-if="!tipoimpuestoice.estado">
                                                No Activo
                                            </div>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-warning" ng-click="toggle('edit',tipoimpuestoice.idtipoimpuestoice,'tpimpicesri')">
                                                Editar <span class="glyphicon glyphicon glyphicon-edit" aria-hidden="true"></span>
                                            </button>
                                            <button type="button" class="btn btn-danger" ng-click="showModalConfirm(tipoimpuestoice,'tpimpicesri')">
                                                Eliminar <span class="glyphicon glyphicon glyphicon-trash" aria-hidden="true"></span>
                                            </button>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                                <dir-pagination-controls pagination-id="tpimpicepg"
                                                         on-page-change="CargadataImpICE(newPageNumber)"
                                                         class="pull-right"
                                                         max-size="8"
                                                         direction-links="true"
                                                         boundary-links="true" >
                                </dir-pagination-controls>
                            </div>
                        </div>

                        <div role="tabpanel" class="tab-pane fade" id="sri_tiposimpuestosretencion">
                            <div class="col-xs-12">
                                <div class="col-xs-6">
                                    <div class="form-group has-feedback">
                                        <input type="text" class="form-control" placeholder="BUSCAR..." ng-model="busqueda" ng-change="CargadataTipoImpRetenc(1)" >
                                        <span class="glyphicon glyphicon-search form-control-feedback" aria-hidden="true"></span>
                                    </div>
                                </div>
                                <div class="col-xs-6 text-right">
                                    <button type="button" class="btn btn-primary" style="float: right;" ng-click="toggle('add', 0,'tpimpretsri')">
                                        Agregar <span class="glyphicon glyphicon glyphicon-plus" aria-hidden="true"></span>
                                    </button>
                                </div>
                            </div>
                            <div class="col-xs-12">
                                <table class="table table-responsive table-striped table-hover table-condensed">
                                    <thead class="bg-primary">
                                    <tr>
                                        <td style="width: 35%;">Tipo de Impuesto Retención</td>
                                        <td style="width: 20%;">Código SRI</td>
                                        <td style="width: 15%;">Estado</td>
                                        <td>Acciones</td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr dir-paginate="tipoimpuestoRten in sri_tipoimpuestoRten | orderBy:sortKey:reverse | itemsPerPage:8" total-items="totalItemstpimpretpg01" current-page="currentPage" pagination-id="tpimpretcpg" ng-cloak" >
                                    <td>{{tipoimpuestoRten.nametipoimpuestoretencion}}</td>
                                    <td>{{tipoimpuestoRten.codigosri}}</td>
                                    <td>
                                        <div ng-if="tipoimpuestoRten.estado">
                                            Activo
                                        </div>
                                        <div ng-if="!tipoimpuestoRten.estado">
                                            No Activo
                                        </div>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-warning" ng-click="toggle('edit',tipoimpuestoRten.idtipoimpuestoretencion,'tpimpretsri')">
                                            Editar <span class="glyphicon glyphicon glyphicon-edit" aria-hidden="true"></span>
                                        </button>
                                        <button type="button" class="btn btn-danger" ng-click="showModalConfirm(tipoimpuestoRten,'tpimpretsri')">
                                            Eliminar <span class="glyphicon glyphicon glyphicon-trash" aria-hidden="true"></span>
                                        </button>
                                    </td>
                                    </tr>
                                    </tbody>
                                </table>
                                <dir-pagination-controls pagination-id="tpimpretcpg"
                                                         on-page-change="CargadataTipoImpRetenc(newPageNumber)"
                                                         class="pull-right"
                                                         max-size="8"
                                                         direction-links="true"
                                                         boundary-links="true" >
                                </dir-pagination-controls>
                            </div>
                        </div>

                        <div role="tabpanel" class="tab-pane fade" id="sri_tiposimpuestosretencioniva_renta">
                            <div class="col-xs-12">
                                <div class="col-xs-6">
                                    <div class="form-group has-feedback">
                                        <input type="text" class="form-control" placeholder="BUSCAR..." ng-model="busqueda" ng-change="CargadataImpIVARENTA(1)" >
                                        <span class="glyphicon glyphicon-search form-control-feedback" aria-hidden="true"></span>
                                    </div>
                                </div>
                                <div class="col-xs-6 text-right">
                                    <button type="button" class="btn btn-primary" style="float: right;" ng-click="toggle('add', 0,'tpimpivaretsri')">
                                        Agregar <span class="glyphicon glyphicon glyphicon-plus" aria-hidden="true"></span>
                                    </button>
                                </div>
                            </div>
                            <div class="col-xs-12">
                                <table class="table table-responsive table-striped table-hover table-condensed">
                                    <thead class="bg-primary">
                                    <tr>
                                        <td style="width: 30%;">Tipo de Impuesto Retención</td>
                                        <td style="width: 15%;">Detalle Retención</td>
                                        <td style="width: 10%;">Código SRI</td>
                                        <td style="width: 10%;">Porcentaje</td>
                                        <td style="width: 15%;">Estado</td>
                                        <td>Acciones</td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr dir-paginate=" ImpuestoIVARENTA in sri_ImpuestoIVARENTA | orderBy:sortKey:reverse | itemsPerPage:8" total-items="totalItemstpimpretcivapg" current-page="currentPage" pagination-id="tpimpretcivapg" ng-cloak >
                                        <td>{{ImpuestoIVARENTA.nametipoimpuestoretencion}}</td>
                                        <td>{{ImpuestoIVARENTA.namedetalleimpuestoretencion}}</td>
                                        <td>{{ImpuestoIVARENTA.codigosri}}</td>
                                        <td>{{ImpuestoIVARENTA.porcentaje}}</td>
                                        <td>
                                            <div ng-if="ImpuestoIVARENTA.estado">
                                                Activo
                                            </div>
                                            <div ng-if="!ImpuestoIVARENTA.estado">
                                                No Activo
                                            </div>

                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-warning" ng-click="toggle('edit',ImpuestoIVARENTA.iddetalleimpuestoretencion,'tpimpivaretsri')">
                                                Editar <span class="glyphicon glyphicon glyphicon-edit" aria-hidden="true"></span>
                                            </button>
                                            <button type="button" class="btn btn-danger" ng-click="showModalConfirm(ImpuestoIVARENTA,'tpimpivaretsri')">
                                                Eliminar <span class="glyphicon glyphicon glyphicon-trash" aria-hidden="true"></span>
                                            </button>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                                <dir-pagination-controls pagination-id="tpimpretcivapg"
                                                         on-page-change="CargadataImpIVARENTA(newPageNumber)"
                                                         class="pull-right"
                                                         max-size="8"
                                                         direction-links="true"
                                                         boundary-links="true" >
                                </dir-pagination-controls>
                            </div>
                        </div>

                        <div role="tabpanel" class="tab-pane fade" id="sri_sustentotributario">
                            <div class="col-xs-12">
                                <div class="col-xs-6">
                                    <div class="form-group has-feedback">
                                        <input type="text" class="form-control" placeholder="BUSCAR..." ng-model="busqueda" ng-change="CargadataSustentoTrib(1)" >
                                        <span class="glyphicon glyphicon-search form-control-feedback" aria-hidden="true"></span>
                                    </div>
                                </div>
                                <div class="col-xs-6 text-right">
                                    <button type="button" class="btn btn-primary" style="float: right;" ng-click="toggle('add', 0,'sustrib')">
                                        Agregar <span class="glyphicon glyphicon glyphicon-plus" aria-hidden="true"></span>
                                    </button>
                                </div>
                            </div>
                            <div class="col-xs-12">
                                <table class="table table-responsive table-striped table-hover table-condensed">
                                    <thead class="bg-primary">
                                    <tr>
                                        <td style="width: 35%;">Sustento Tributario</td>
                                        <td style="width: 20%;">Código SRI</td>
                                        <td style="width: 15%;">Estado</td>
                                        <td>Acciones</td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr dir-paginate="SustentoTributario in sri_SustentoTributario | orderBy:sortKey:reverse | itemsPerPage:8" total-items="totalItemstpimpsustpg" current-page="currentPage" pagination-id="tpimpsustpg" ng-cloak>
                                        <td>{{SustentoTributario.namesustento}}</td>
                                        <td>{{SustentoTributario.codigosrisustento}}</td>
                                        <td>
                                            <div ng-if="SustentoTributario.estado">
                                                Activo
                                            </div>
                                            <div ng-if="!SustentoTributario.estado">
                                                No Activo
                                            </div>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-warning" ng-click="toggle('edit',SustentoTributario.idsustentotributario,'sustrib')">
                                                Editar <span class="glyphicon glyphicon glyphicon-edit" aria-hidden="true"></span>
                                            </button>
                                            <button type="button" class="btn btn-danger" ng-click="showModalConfirm(SustentoTributario,'sustrib')">
                                                Eliminar <span class="glyphicon glyphicon glyphicon-trash" aria-hidden="true"></span>
                                            </button>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                                <dir-pagination-controls pagination-id="tpimpsustpg"
                                                         on-page-change="CargadataSustentoTrib(newPageNumber)"
                                                         class="pull-right"
                                                         max-size="8"
                                                         direction-links="true"
                                                         boundary-links="true" >
                                </dir-pagination-controls>
                            </div>
                        </div>

                        <div role="tabpanel" class="tab-pane fade" id="sri_tiposcomprobantes">
                            <div class="col-xs-12">
                                <div class="col-xs-6">
                                    <div class="form-group has-feedback">
                                        <input type="text" class="form-control" placeholder="BUSCAR..." ng-model="busqueda" ng-change="CargadataComprobante(1)" >
                                        <span class="glyphicon glyphicon-search form-control-feedback" aria-hidden="true"></span>
                                    </div>
                                </div>
                                <div class="col-xs-6 text-right">
                                    <button type="button" class="btn btn-primary" style="float: right;" ng-click="toggle('add', 0,'compsust')">
                                        Agregar <span class="glyphicon glyphicon glyphicon-plus" aria-hidden="true"></span>
                                    </button>
                                </div>
                            </div>
                            <div class="col-xs-12">
                                <table class="table table-responsive table-striped table-hover table-condensed">
                                    <thead class="bg-primary">
                                    <tr>
                                        <td style="width: 30%;">Sustento Tributario</td>
                                        <td style="width: 15%;">Tipo de Comprobante</td>
                                        <td style="width: 10%;">Código SRI</td>
                                        <td style="width: 15%;">Estado</td>
                                        <td>Acciones</td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr dir-paginate="Comprobante in sri_Comprobante | orderBy:sortKey:reverse | itemsPerPage:8" total-items="totalItemstpcomppg" current-page="currentPage" pagination-id="tpcomppg" ng-cloak>
                                        <td>{{Comprobante.namesustento}}</td>
                                        <td>{{Comprobante.namecomprobante}}</td>
                                        <td>{{Comprobante.codigosri}}
                                        </td>
                                        <td>
                                            <div ng-if="Comprobante.estado">
                                                Activo
                                            </div>
                                            <div ng-if="!Comprobante.estado">
                                                No Activo
                                            </div>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-warning" ng-click="toggle('edit',Comprobante.idtipocomprobante,'compsust')">
                                                Editar <span class="glyphicon glyphicon glyphicon-edit" aria-hidden="true"></span>
                                            </button>
                                            <button type="button" class="btn btn-danger" ng-click="showModalConfirm(Comprobante,'compsust')">
                                                Eliminar <span class="glyphicon glyphicon glyphicon-trash" aria-hidden="true"></span>
                                            </button>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                                <dir-pagination-controls pagination-id="tpcomppg"
                                                         on-page-change="CargadataComprobante(newPageNumber)"
                                                         class="pull-right"
                                                         max-size="8"
                                                         direction-links="true"
                                                         boundary-links="true" >
                                </dir-pagination-controls>
                            </div>
                        </div>

                        <div role="tabpanel" class="tab-pane fade" id="sri_tipospagoresidentes">
                            <div class="col-xs-12">
                                <div class="col-xs-6">
                                    <div class="form-group has-feedback">
                                        <input type="text" class="form-control" placeholder="BUSCAR..." ng-model="busqueda" ng-change="CargadataPagoResidente(1)" >
                                        <span class="glyphicon glyphicon-search form-control-feedback" aria-hidden="true"></span>
                                    </div>
                                </div>
                                <div class="col-xs-6 text-right">
                                    <button type="button" class="btn btn-primary" style="float: right;" ng-click="toggle('add', 0,'tppagores')">
                                        Agregar <span class="glyphicon glyphicon glyphicon-plus" aria-hidden="true"></span>
                                    </button>
                                </div>
                            </div>
                            <div class="col-xs-12">
                                <table class="table table-responsive table-striped table-hover table-condensed">
                                    <thead class="bg-primary">
                                    <tr>
                                        <td style="width: 70%;">Pago Residente</td>
                                        <td>Acciones</td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr dir-paginate="PagoResidente in sri_PagoResidente | orderBy:sortKey:reverse | itemsPerPage:8" total-items="totalItemstpresindentpgs" current-page="currentPage" pagination-id="tpresindentpg" ng-cloak >
                                        <td>{{PagoResidente.tipopagoresidente}}</td>
                                        <td>
                                            <button type="button" class="btn btn-warning" ng-click="toggle('edit',PagoResidente.idpagoresidente,'tppagores')">
                                                Editar <span class="glyphicon glyphicon glyphicon-edit" aria-hidden="true"></span>
                                            </button>
                                            <button type="button" class="btn btn-danger" ng-click="showModalConfirm(PagoResidente,'tppagores')">
                                                Eliminar <span class="glyphicon glyphicon glyphicon-trash" aria-hidden="true"></span>
                                            </button>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                                <dir-pagination-controls pagination-id="tpresindentpg"
                                                         on-page-change="CargadataPagoResidente(newPageNumber)"
                                                         class="pull-right"
                                                         max-size="8"
                                                         direction-links="true"
                                                         boundary-links="true" >
                                </dir-pagination-controls>
                            </div>
                        </div>

                        <div role="tabpanel" class="tab-pane fade" id="sri_pagopais">
                            <div class="col-xs-12">
                                <div class="col-xs-6">
                                    <div class="form-group has-feedback">
                                        <input type="text" class="form-control" placeholder="BUSCAR..." ng-model="busqueda" ng-change="CargadataPagoPais(1)" >
                                        <span class="glyphicon glyphicon-search form-control-feedback" aria-hidden="true"></span>
                                    </div>
                                </div>
                                <div class="col-xs-6 text-right">
                                    <button type="button" class="btn btn-primary" style="float: right;" ng-click="toggle('add', 0,'pagopais')">
                                        Agregar <span class="glyphicon glyphicon glyphicon-plus" aria-hidden="true"></span>
                                    </button>
                                </div>
                            </div>
                            <div class="col-xs-12">
                                <table class="table table-responsive table-striped table-hover table-condensed">
                                    <thead class="bg-primary">
                                    <tr>
                                        <td style="width: 70%;">País Pago</td>
                                        <td style="width: 10%;">Código SRI</td>
                                        <td>Acciones</td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr dir-paginate="pagopais in sri_pagopais | orderBy:sortKey:reverse | itemsPerPage:10" total-items="totalItemstppaispg" current-page="currentPage" pagination-id="tppaispg" ng-cloak>
                                        <td>{{pagopais.pais}}</td>
                                        <td>{{pagopais.codigosri}}</td>
                                        <td>
                                            <button type="button" class="btn btn-warning" ng-click="toggle('edit',pagopais.idpagopais,'pagopais')">
                                                Editar <span class="glyphicon glyphicon glyphicon-edit" aria-hidden="true"></span>
                                            </button>
                                            <button type="button" class="btn btn-danger" ng-click="showModalConfirm(pagopais,'pagopais')">
                                                Eliminar <span class="glyphicon glyphicon glyphicon-trash" aria-hidden="true"></span>
                                            </button>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                                <dir-pagination-controls pagination-id="tppaispg"
                                                         on-page-change="CargadataPagoPais(newPageNumber)"
                                                         class="pull-right"
                                                         max-size="8"
                                                         direction-links="true"
                                                         boundary-links="true" >
                                </dir-pagination-controls>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>

        <!-- Modal  tipo documento Nativo-->


        <div class="modal fade" tabindex="-1" role="dialog" id="modalActionTipoDoc">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header modal-header-primary">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">{{form_title}}</h4>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal" name="formsritipodoc" novalidate="">
                            <div class="row">
                                <div class="col-xs-12 error">
                                    <div class="input-group">
                                        <span class="input-group-addon">Nombre del Tipo Documento: </span>
                                        <input type="text" class="form-control" name="nametipodocumento" id="nametipodocumento" ng-model="nametipodocumento" placeholder=""
                                               ng-required="true" ng-maxlength="50">
                                    </div>
                                    <span class="help-block error"
                                          ng-show="formsritipodoc.nametipodocumento.$invalid && formsritipodoc.nametipodocumento.$touched">El nombre del Tipo Documento es requerido</span>
                                    <span class="help-block error"
                                          ng-show="formsritipodoc.nametipodocumento.$invalid && formsritipodoc.nametipodocumento.$error.maxlength">La longitud máxima es de 50 caracteres</span>
                                </div>
                                <div class="col-xs-12 error">
                                    <div class="input-group">
                                        <span class="input-group-addon">Código SRI: </span>
                                        <input type="text" class="form-control" name="codigosri" id="codigosri" ng-model="codigosri" placeholder=""
                                               ng-required="true" ng-maxlength="2">
                                    </div>

                                    <div class="input-group">
                                        <span class="input-group-addon">Estado: </span>
                                        <select class="selectpicker form-control" id="estado" ng-model="estado">
                                            <option value="true">Activo</option>
                                            <option value="false">Inactivo</option>
                                        </select>

                                    </div>
                                    <span class="help-block error"
                                          ng-show="formsritipodoc.codigosri.$invalid && formsritipodoc.codigosri.$touched">El código SRI es requerido</span>
                                    <span class="help-block error"
                                          ng-show="formsritipodoc.codigosri.$invalid && formsritipodoc.codigosri.$error.maxlength">La longitud máxima es de 2 caracteres</span>
                                </div>


                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            Cancelar <span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span>
                        </button>
                        <button type="button" class="btn btn-success" id="btn-save" ng-click="Save('tpdocsri')" ng-disabled="formsritipodoc.$invalid">
                            Guardar <span class="glyphicon glyphicon-floppy-saved" aria-hidden="true"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>


        <!-- Modal  tipo identificacion Nativo-->


        <div class="modal fade" tabindex="-1" role="dialog" id="modalActionTipoIdent">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header modal-header-primary">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">{{form_title}}</h4>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal" name="formsritipoident" novalidate="">
                            <div class="row">
                                <div class="col-xs-12 error">
                                    <div class="input-group">
                                        <span class="input-group-addon">Nombre del Tipo Identificación: </span>
                                        <input type="text" class="form-control" name="nametipoident" id="nametipoident" ng-model="nametipoident" placeholder=""
                                               ng-required="true" ng-maxlength="50">
                                    </div>
                                    <span class="help-block error"
                                          ng-show="formsritipoident.nametipoident.$invalid && formsritipoident.nametipoident.$touched">El nombre del Tipo Identificacion es requerido</span>
                                    <span class="help-block error"
                                          ng-show="formsritipoident.nametipoident.$invalid && formsritipoident.nametipoident.$error.maxlength">La longitud máxima es de 50 caracteres</span>
                                </div>
                                <div class="col-xs-12 error">
                                    <div class="input-group">
                                        <span class="input-group-addon">Código SRI: </span>
                                        <input type="text" class="form-control" name="codigosri" id="codigosri" ng-model="codigosri" placeholder=""
                                               ng-required="true" ng-maxlength="2">
                                    </div>
                                    <div class="input-group">
                                        <span class="input-group-addon">Estado: </span>
                                        <select class="selectpicker form-control" id="estado" ng-model="estado">
                                            <option value="true">Activo</option>
                                            <option value="false">Inactivo</option>
                                        </select>
                                    </div>

                                    <span class="help-block error"
                                          ng-show="formsritipoident.codigosri.$invalid && formsritipoident.codigosri.$touched">El código SRI es requerido</span>
                                    <span class="help-block error"
                                          ng-show="formsritipoident.codigosri.$invalid && formsritipoident.codigosri.$error.maxlength">La longitud máxima es de 2 caracteres</span>
                                </div>


                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            Cancelar <span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span>
                        </button>
                        <button type="button" class="btn btn-success" id="btn-save" ng-click="Save('tpidentsri')" ng-disabled="formsritipoident.$invalid">
                            Guardar <span class="glyphicon glyphicon-floppy-saved" aria-hidden="true"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>



        <!-- Modal  tipo impuesto Nativo-->

        <div class="modal fade" tabindex="-1" role="dialog" id="modalActionTipoImp">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header modal-header-primary">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">{{form_title}}</h4>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal" name="formsritipoimpuesto" novalidate="">
                            <div class="row">
                                <div class="col-xs-12 error">
                                    <div class="input-group">
                                        <span class="input-group-addon">Nombre del Tipo Impuesto: </span>
                                        <input type="text" class="form-control" name="nametipoimpuest" id="nametipoimpuest" ng-model="nametipoimpuest" placeholder=""
                                               ng-required="true" ng-maxlength="10">
                                    </div>
                                    <span class="help-block error"
                                          ng-show="formsritipoimpuesto.nametipoimpuest.$invalid && formsritipoimpuesto.nametipoimpuest.$touched">El nombre del Tipo Impuesto es requerido</span>
                                    <span class="help-block error"
                                          ng-show="formsritipoimpuesto.nametipoimpuest.$invalid && formsritipoimpuesto.nametipoimpuest.$error.maxlength">La longitud máxima es de 10 caracteres</span>
                                </div>
                                <div class="col-xs-12 error">
                                    <div class="input-group">
                                        <span class="input-group-addon">Código SRI: </span>
                                        <input type="text" class="form-control" name="codigosri" id="codigosri" ng-model="codigosri" placeholder=""
                                               ng-required="true" ng-maxlength="2">
                                    </div>
                                    <div class="input-group">
                                        <span class="input-group-addon">Estado: </span>
                                        <select class="selectpicker form-control" id="estado" ng-model="estado">
                                            <option value="true">Activo</option>
                                            <option value="false">Inactivo</option>
                                        </select>
                                    </div>

                                    <span class="help-block error"
                                          ng-show="formsritipoimpuesto.codigosri.$invalid && formsritipoimpuesto.codigosri.$touched">El código SRI es requerido</span>
                                    <span class="help-block error"
                                          ng-show="formsritipoimpuesto.codigosri.$invalid && formsritipoimpuesto.codigosri.$error.maxlength">La longitud máxima es de 2 caracteres</span>
                                </div>


                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            Cancelar <span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span>
                        </button>
                        <button type="button" class="btn btn-success" id="btn-save" ng-click="Save('timpsri')" ng-disabled="formsritipoimpuesto.$invalid">
                            Guardar <span class="glyphicon glyphicon-floppy-saved" aria-hidden="true"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>






        <!-- Modal  Impuesto Iva Nativo-->

        <div class="modal fade" tabindex="-1" role="dialog" id="modalActionImpuestoIva">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header modal-header-primary">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">{{form_title}}</h4>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal" name="formsriImpuestoIva" novalidate="">
                            <div class="row">
                                <div class="col-xs-12 error">
                                    <div class="input-group">
                                        <span class="input-group-addon">Nombre del Impuesto Iva: </span>
                                        <input type="text" class="form-control" name="nameimpuestoiva" id="nameimpuestoiva" ng-model="nameimpuestoiva" placeholder=""
                                               ng-required="true" ng-maxlength="25">
                                    </div>
                                    <span class="help-block error"
                                          ng-show="formsriImpuestoIva.nameimpuestoiva.$invalid && formsriImpuestoIva.nameimpuestoiva.$touched">El nombre del Tipo Impuesto Iva es requerido</span>
                                    <span class="help-block error"
                                          ng-show="formsriImpuestoIva.nameimpuestoiva.$invalid && formsriImpuestoIva.nameimpuestoiva.$error.maxlength">La longitud máxima es de 25 caracteres</span>
                                </div>
                                <div class="col-xs-12 error">
                                    <div class="input-group">
                                        <span class="input-group-addon">Porcentaje: </span>
                                        <input type="text" class="form-control" name="porcentaje" id="porcentaje" ng-model="porcentaje" placeholder=""
                                               ng-required="true" ng-maxlength="10">
                                    </div>
                                    <span class="help-block error"
                                          ng-show="formsriImpuestoIva.porcentaje==''">El porcentaje es requerido</span>

                                </div>
                                <div class="col-xs-12 error">
                                    <div class="input-group">
                                        <span class="input-group-addon">Código SRI: </span>
                                        <input type="text" class="form-control" name="codigosri" id="codigosri" ng-model="codigosri" placeholder=""
                                               ng-required="true" ng-maxlength="2">
                                    </div>
                                    <span class="help-block error"
                                          ng-show="formsriImpuestoIva.codigosri.$invalid && formsriImpuestoIva.codigosri.$touched">El código SRI es requerido</span>
                                    <span class="help-block error"
                                          ng-show="formsriImpuestoIva.codigosri.$invalid && formsriImpuestoIva.codigosri.$error.maxlength">La longitud máxima es de 2 caracteres</span>
                                </div>

                                <div class="col-xs-12 error">
                                    <div class="input-group">
                                        <span class="input-group-addon">Tipo Impuesto: </span>
                                        <select class="selectpicker form-control" id="estado" ng-model="TipoImpuesto" ng-required="true"  disabled>
                                            <option ng-repeat="tipoimpuesto in sri_tipoimpuesto" value="{{tipoimpuesto.idtipoimpuesto}}">{{tipoimpuesto.nameimpuesto}}</option>
                                        </select>
                                    </div>
                                    <span class="help-block error"
                                          ng-show="formsriImpuestoIva.TipoImpuesto==''">El Tipo Impuesto es requerido</span>

                                </div>

                                <div class="col-xs-12">
                                    <div class="input-group">
                                        <span class="input-group-addon">Estado: </span>
                                        <select class="selectpicker form-control" id="estado" ng-model="estado">
                                            <option value="true">Activo</option>
                                            <option value="false">Inactivo</option>
                                        </select>
                                    </div>


                                </div>

                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            Cancelar <span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span>
                        </button>
                        <button type="button" class="btn btn-success" id="btn-save" ng-click="Save('tpimpiva')" ng-disabled="formsriImpuestoIva.$invalid">
                            Guardar <span class="glyphicon glyphicon-floppy-saved" aria-hidden="true"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>


        <!-- Modal  Impuesto Ice Nativo-->

        <div class="modal fade" tabindex="-1" role="dialog" id="modalActionImpuestoIce">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header modal-header-primary">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">{{form_title}}</h4>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal" name="formsriImpuestoIce" novalidate="">
                            <div class="row">
                                <div class="col-xs-12 error">
                                    <div class="input-group">
                                        <span class="input-group-addon">Nombre del Impuesto Ice: </span>
                                        <input type="text" class="form-control" name="nameimpuestoice" id="nameimpuestoice" ng-model="nameimpuestoice" placeholder=""
                                               ng-required="true" ng-maxlength="200">
                                    </div>
                                    <span class="help-block error"
                                          ng-show="formsriImpuestoIce.nameimpuestoice.$invalid && formsriImpuestoIce.nameimpuestoiva.$touched">El nombre del Tipo Impuesto Ice es requerido</span>
                                    <span class="help-block error"
                                          ng-show="formsriImpuestoIce.nameimpuestoice.$invalid && formsriImpuestoIce.nameimpuestoice.$error.maxlength">La longitud máxima es de 200 caracteres</span>
                                </div>
                                <div class="col-xs-12 error">
                                    <div class="input-group">
                                        <span class="input-group-addon">Porcentaje: </span>
                                        <input type="text" class="form-control" name="porcentaje" id="porcentaje" ng-model="porcentaje" placeholder=""
                                               ng-required="true" ng-maxlength="10">
                                    </div>
                                    <span class="help-block error"
                                          ng-show="formsriImpuestoIva.porcentaje==''">El porcentaje es requerido</span>

                                </div>
                                <div class="col-xs-12 error">
                                    <div class="input-group">
                                        <span class="input-group-addon">Código SRI: </span>
                                        <input type="text" class="form-control" name="codigosri" id="codigosri" ng-model="codigosri" placeholder=""
                                               ng-required="true" ng-maxlength="5">
                                    </div>
                                    <span class="help-block error"
                                          ng-show="formsriImpuestoIce.codigosri.$invalid && formsriImpuestoIce.codigosri.$touched">El código SRI es requerido</span>
                                    <span class="help-block error"
                                          ng-show="formsriImpuestoIce.codigosri.$invalid && formsriImpuestoIce.codigosri.$error.maxlength">La longitud máxima es de 5 caracteres</span>
                                </div>

                                <div class="col-xs-12 error">
                                    <div class="input-group">
                                        <span class="input-group-addon">Tipo Impuesto: </span>
                                        <select class="selectpicker form-control" id="estado" ng-model="TipoImpuesto" ng-required="true"  disabled>
                                            <option ng-repeat="tipoimpuesto in sri_tipoimpuesto" value="{{tipoimpuesto.idtipoimpuesto}}">{{tipoimpuesto.nameimpuesto}}</option>
                                        </select>
                                    </div>
                                    <span class="help-block error"
                                          ng-show="formsriImpuestoIce.TipoImpuesto==''">El Tipo Impuesto es requerido</span>

                                </div>

                                <div class="col-xs-12">
                                    <div class="input-group">
                                        <span class="input-group-addon">Estado: </span>
                                        <select class="selectpicker form-control" id="estado" ng-model="estado">
                                            <option value="true">Activo</option>
                                            <option value="false">Inactivo</option>
                                        </select>
                                    </div>
                                </div>

                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            Cancelar <span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span>
                        </button>
                        <button type="button" class="btn btn-success" id="btn-save" ng-click="Save('tpimpice')" ng-disabled="formsriImpuestoIce.$invalid">
                            Guardar <span class="glyphicon glyphicon-floppy-saved" aria-hidden="true"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>


        <!-- Modal  impuesto retencion a la renta Nativo-->

        <div class="modal fade" tabindex="-1" role="dialog" id="modalActionTipoImpRetRenta">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header modal-header-primary">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">{{form_title}}</h4>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal" name="formsritipoimpuestoRetRenta" novalidate="">
                            <div class="row">
                                <div class="col-xs-12 error">
                                    <div class="input-group">
                                        <span class="input-group-addon">Nombre del Impuesto de Retención: </span>
                                        <input type="text" class="form-control" name="nametipoimpuestoret" id="nametipoimpuestoret" ng-model="nametipoimpuestoret" placeholder=""
                                               ng-required="true" ng-maxlength="25">
                                    </div>
                                    <span class="help-block error"
                                          ng-show="formsritipoimpuestoRetRenta.nametipoimpuestoret.$invalid && formsritipoimpuestoRetRenta.nametipoimpuestoret.$touched">El nombre del Tipo Impuesto es requerido</span>
                                    <span class="help-block error"
                                          ng-show="formsritipoimpuestoRetRenta.nametipoimpuestoret.$invalid && formsritipoimpuestoRetRenta.nametipoimpuestoret.$error.maxlength">La longitud máxima es de 50 caracteres</span>
                                </div>
                                <div class="col-xs-12 error">
                                    <div class="input-group">
                                        <span class="input-group-addon">Código SRI: </span>
                                        <input type="text" class="form-control" name="codigosri" id="codigosri" ng-model="codigosri" placeholder=""
                                               ng-required="true" ng-maxlength="2">
                                    </div>
                                    <div class="input-group">
                                        <span class="input-group-addon">Estado: </span>
                                        <select class="selectpicker form-control" id="estado" ng-model="estado">
                                            <option value="true">Activo</option>
                                            <option value="false">Inactivo</option>
                                        </select>
                                    </div>

                                    <span class="help-block error"
                                          ng-show="formsritipoimpuestoRetRenta.codigosri.$invalid && formsritipoimpuestoRetRenta.codigosri.$touched">El código SRI es requerido</span>
                                    <span class="help-block error"
                                          ng-show="formsritipoimpuesto.codigosri.$invalid && formsritipoimpuestoRetRenta.codigosri.$error.maxlength">La longitud máxima es de 2 caracteres</span>
                                </div>


                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            Cancelar <span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span>
                        </button>
                        <button type="button" class="btn btn-success" id="btn-save" ng-click="Save('tpimpretsri')" ng-disabled="formsritipoimpuestoRetRenta.$invalid">
                            Guardar <span class="glyphicon glyphicon-floppy-saved" aria-hidden="true"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>


        <!-- Modal  Impuesto de Iva - Rtencion Nativo-->

        <div class="modal fade" tabindex="-1" role="dialog" id="modalActionTipoImpIvaRetRenta">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header modal-header-primary">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">{{form_title}}</h4>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal" name="formsritipoimpuestoIvaRetRenta" novalidate="">
                            <div class="row">
                                <div class="col-xs-12 error">
                                    <div class="input-group">
                                        <span class="input-group-addon">Tipo Impuesto: </span>
                                        <select class="selectpicker form-control" id="estado" ng-model="TipoImpuesto" ng-required="true" >
                                            <option ng-repeat="tipoimpuestoRten in sri_tipoimpuestoRten" value="{{tipoimpuestoRten.idtipoimpuestoretencion}}">{{tipoimpuestoRten.nametipoimpuestoretencion}}</option>
                                        </select>
                                    </div>
                                    <span class="help-block error"
                                          ng-show="formsriImpuestoIce.TipoImpuesto.$invalid && formsriImpuestoIce.TipoImpuesto==''">El Tipo Impuesto es requerido</span>

                                </div>
                                <div class="col-xs-12 error">
                                    <div class="input-group">
                                        <span class="input-group-addon">Nombre del Impuesto de Iva - Retencion: </span>
                                        <input type="text" class="form-control" name="nametipoimpuestoivaret" id="nametipoimpuestoivaret" ng-model="nametipoimpuestoivaret" placeholder=""
                                               ng-required="true" ng-maxlength="10">
                                    </div>
                                    <span class="help-block error"
                                          ng-show="formsritipoimpuestoIvaRetRenta.nametipoimpuestoivaret.$invalid && formsritipoimpuestoIvaRetRenta.nametipoimpuestoivaret.$touched">El nombre del Tipo Impuesto es requerido</span>
                                    <span class="help-block error"
                                          ng-show="formsritipoimpuestoIvaRetRenta.nametipoimpuestoivaret.$invalid && formsritipoimpuestoIvaRetRenta.nametipoimpuestoivaret.$error.maxlength">La longitud máxima es de 10 caracteres</span>
                                </div>
                                <div class="col-xs-12 error">
                                    <div class="input-group">
                                        <span class="input-group-addon">Porcentaje: </span>
                                        <input type="text" class="form-control" name="porcentaje" id="porcentaje" ng-model="porcentaje" placeholder=""
                                               ng-required="true" ng-maxlength="10">
                                    </div>
                                    <span class="help-block error"
                                          ng-show="formsritipoimpuestoIvaRetRenta.porcentaj.$invalid && formsritipoimpuestoIvaRetRenta.porcentaje==''">El porcentaje es requerido</span>

                                </div>
                                <div class="col-xs-12 error">
                                    <div class="input-group">
                                        <span class="input-group-addon">Código SRI: </span>
                                        <input type="text" class="form-control" name="codigosri" id="codigosri" ng-model="codigosri" placeholder=""
                                               ng-required="true" ng-maxlength="5">
                                    </div>
                                    <div class="input-group">
                                        <span class="input-group-addon">Estado: </span>
                                        <select class="selectpicker form-control" id="estado" ng-model="estado">
                                            <option value="true">Activo</option>
                                            <option value="false">Inactivo</option>
                                        </select>
                                    </div>

                                    <span class="help-block error"
                                          ng-show="formsritipoimpuestoIvaRetRenta.codigosri.$invalid && formsritipoimpuestoIvaRetRenta.codigosri.$touched">El código SRI es requerido</span>
                                    <span class="help-block error"
                                          ng-show="formsritipoimpuestoIvaRetRenta.$invalid && formsritipoimpuestoIvaRetRenta.$error.maxlength">La longitud máxima es de 5 caracteres</span>
                                </div>


                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            Cancelar <span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span>
                        </button>
                        <button type="button" class="btn btn-success" id="btn-save" ng-click="Save('tpimpivaretsri')" ng-disabled="formsritipoimpuestoIvaRetRenta.$invalid">
                            Guardar <span class="glyphicon glyphicon-floppy-saved" aria-hidden="true"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>


        <!-- Modal  Sustento Tributario Nativo-->

        <div class="modal fade" tabindex="-1" role="dialog" id="modalActionSustentoTributario">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header modal-header-primary">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">{{form_title}}</h4>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal" name="formsriSustentoTributario" novalidate="">
                            <div class="row">

                                <div class="col-xs-12 error">
                                    <div class="input-group">
                                        <span class="input-group-addon">Nombre del Sutento Tributario: </span>
                                        <input type="text" class="form-control" name="nameSustentoTributario" id="nameSustentoTributario" ng-model="nameSustentoTributario" placeholder=""
                                               ng-required="true" ng-maxlength="50">
                                    </div>
                                    <span class="help-block error"
                                          ng-show="formsriSustentoTributario.nameSustentoTributario.$invalid && formsriSustentoTributario.nameSustentoTributario.$touched">El nombre Sustento Tributario es requerido</span>
                                    <span class="help-block error"
                                          ng-show="formsriSustentoTributario.nameSustentoTributario.$invalid && formsriSustentoTributario.nameSustentoTributario.$error.maxlength">La longitud máxima es de 50 caracteres</span>
                                </div>

                                <div class="col-xs-12 error">
                                    <div class="input-group">
                                        <span class="input-group-addon">Código SRI: </span>
                                        <input type="text" class="form-control" name="codigosrisustento" id="codigosrisustento" ng-model="codigosrisustento" placeholder=""
                                               ng-required="true" ng-maxlength="3">
                                    </div>
                                    <div class="input-group">
                                        <span class="input-group-addon">Estado: </span>
                                        <select class="selectpicker form-control" id="estado" ng-model="estado">
                                            <option value="true">Activo</option>
                                            <option value="false">Inactivo</option>
                                        </select>
                                    </div>

                                    <span class="help-block error"
                                          ng-show="formsriSustentoTributario.codigosrisustento.$invalid && formsriSustentoTributario.codigosrisustento.$touched">El código SRI es requerido</span>
                                    <span class="help-block error"
                                          ng-show="formsriSustentoTributario.codigosrisustento.$invalid && formsriSustentoTributario.codigosrisustento.$error.maxlength">La longitud máxima es de 3 caracteres</span>
                                </div>


                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            Cancelar <span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span>
                        </button>
                        <button type="button" class="btn btn-success" id="btn-save" ng-click="Save('sustrib')" ng-disabled="formsriSustentoTributario.$invalid">
                            Guardar <span class="glyphicon glyphicon-floppy-saved" aria-hidden="true"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>


        <!-- Modal  Comprobante de Sustento Tributario-->

        <div class="modal fade" tabindex="-1" role="dialog" id="modalActionCompSustentoTributario">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header modal-header-primary">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">{{form_title}}</h4>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal" name="formsriCompSustTributario" novalidate="">
                            <div class="row">
                                <div class="col-xs-12 error">
                                    <div class="input-group">
                                        <span class="input-group-addon">Tipo Sustento: </span>
                                        <select class="selectpicker form-control" id="estado" ng-model="TipoSustento" ng-required="true" >
                                            <option ng-repeat="SustentoTributario in sri_SustentoTributario" value="{{SustentoTributario.idsustentotributario}}">{{SustentoTributario.namesustento}}</option>
                                        </select>
                                    </div>
                                    <span class="help-block error"
                                          ng-show="formsriImpuestoIce.TipoImpuesto.$invalid && formsriImpuestoIce.TipoImpuesto==''">El Tipo Impuesto es requerido</span>

                                </div>
                                <div class="col-xs-12 error">
                                    <div class="input-group">
                                        <span class="input-group-addon">Nombre del Comprobante: </span>
                                        <input type="text" class="form-control" name="namecomprobante" id="namecomprobante" ng-model="namecomprobante" placeholder=""
                                               ng-required="true" ng-maxlength="50">
                                    </div>
                                    <span class="help-block error"
                                          ng-show="formsriCompSustTributario.nametipoimpuestoivaret.$invalid && formsriCompSustTributario.nametipoimpuestoivaret.$touched">El nombre Comprobante de pago es requerido</span>
                                    <span class="help-block error"
                                          ng-show="formsriCompSustTributario.nametipoimpuestoivaret.$invalid && formsriCompSustTributario.nametipoimpuestoivaret.$error.maxlength">La longitud máxima es de 50 caracteres</span>
                                </div>

                                <div class="col-xs-12 error">
                                    <div class="input-group">
                                        <span class="input-group-addon">Código SRI: </span>
                                        <input type="text" class="form-control" name="codigosri" id="codigosri" ng-model="codigosri" placeholder=""
                                               ng-required="true" ng-maxlength="3">
                                    </div>
                                    <div class="input-group">
                                        <span class="input-group-addon">Estado: </span>
                                        <select class="selectpicker form-control" id="estado" ng-model="estado">
                                            <option value="true">Activo</option>
                                            <option value="false">Inactivo</option>
                                        </select>
                                    </div>

                                    <span class="help-block error"
                                          ng-show="formsriCompSustTributario.codigosri.$invalid && formsriCompSustTributario.codigosri.$touched">El código SRI es requerido</span>
                                    <span class="help-block error"
                                          ng-show="formsriCompSustTributario.$invalid && formsriCompSustTributario.$error.maxlength">La longitud máxima es de 3 caracteres</span>
                                </div>


                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            Cancelar <span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span>
                        </button>
                        <button type="button" class="btn btn-success" id="btn-save" ng-click="Save('compsust')" ng-disabled="formsriCompSustTributario.$invalid">
                            Guardar <span class="glyphicon glyphicon-floppy-saved" aria-hidden="true"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal pago residente Nativo-->

        <div class="modal fade" tabindex="-1" role="dialog" id="modalActionPagoResidente">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header modal-header-primary">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">{{form_title}}</h4>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal" name="formsriPagoResidente" novalidate="">
                            <div class="row">
                                <div class="col-xs-12 error">
                                    <div class="input-group">
                                        <span class="input-group-addon">Nombre Tipo pago Residente: </span>
                                        <input type="text" class="form-control" name="tipopagoresidente" id="tipopagoresidente" ng-model="tipopagoresidente" placeholder=""
                                               ng-required="true" ng-maxlength="150">
                                    </div>
                                    <span class="help-block error"
                                          ng-show="formsriPagoResidente.tipopagoresidente.$invalid && formsriPagoResidente.tipopagoresidente.$touched">El nombre Residente es requerido</span>
                                    <span class="help-block error"
                                          ng-show="formsriPagoResidente.tipopagoresidente.$invalid && formsriPagoResidente.tipopagoresidente.$error.maxlength">La longitud máxima es de 150 caracteres</span>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">

                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            Cancelar <span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span>
                        </button>
                        <button type="button" class="btn btn-success" id="btn-save" ng-click="Save('tppagores')" ng-disabled="formsriPagoResidenteo.$invalid">
                            Guardar <span class="glyphicon glyphicon-floppy-saved" aria-hidden="true"></span>
                        </button>


                    </div>
                </div>
            </div>
        </div>


        <!-- Modal  Pais Pago Nativo-->
        <div class="modal fade" tabindex="-1" role="dialog" id="modalActionPaisPago">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header modal-header-primary">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">{{form_title}}</h4>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal" name="formsriPaisPago" novalidate="">
                            <div class="row">
                                <div class="col-xs-12 error">
                                    <div class="input-group">
                                        <span class="input-group-addon">Nombre Pais: </span>
                                        <input type="text" class="form-control" name="pais" id="pais" ng-model="pais" placeholder=""
                                               ng-required="true" ng-maxlength="100">
                                    </div>
                                    <span class="help-block error"
                                          ng-show="formsriPaisPago.pais.$invalid && formsriPaisPago.pais.$touched">El nombre Pais es requerido</span>
                                    <span class="help-block error"
                                          ng-show="formsriPaisPago.pais.$invalid && formsriPaisPago.pais.$error.maxlength">La longitud máxima es de 100 caracteres</span>
                                </div>
                                <div class="col-xs-12 error">
                                    <div class="input-group">
                                        <span class="input-group-addon">Código SRI: </span>
                                        <input type="text" class="form-control" name="codigosri" id="codigosri" ng-model="codigosri" placeholder=""
                                               ng-required="true" ng-maxlength="4">
                                    </div>

                                    <span class="help-block error"
                                          ng-show="formsriPaisPago.codigosri.$invalid && formsriPaisPago.codigosri.$touched">El código SRI es requerido</span>
                                    <span class="help-block error"
                                          ng-show="formsriPaisPago.codigosri.$invalid && formsriPaisPago.codigosri.$error.maxlength">La longitud máxima es de 4 caracteres</span>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            Cancelar <span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span>
                        </button>
                        <button type="button" class="btn btn-success" id="btn-save" ng-click="Save('pagopais')" ng-disabled="formsriPaisPago.$invalid">
                            Guardar <span class="glyphicon glyphicon-floppy-saved" aria-hidden="true"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>


        <!-- Modal Forma Pago Nativo-->
        <div class="modal fade" tabindex="-1" role="dialog" id="modalActionFormapago">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header modal-header-primary">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">{{form_title}}</h4>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal" name="formFormapago" novalidate="">
                            <div class="row">
                                <div class="col-xs-12 error">
                                    <div class="input-group">
                                        <span class="input-group-addon">Nombre Forma Pago: </span>
                                        <input type="text" class="form-control" name="nameformapago" id="nameformapago" ng-model="nameformapago" placeholder=""
                                               ng-required="true" ng-maxlength="50">
                                    </div>
                                    <span class="help-block error"
                                          ng-show="formFormapago.nameformapago.$invalid && formFormapago.nameformapago.$touched">El nombre del Forma de Pago es requerido</span>
                                    <span class="help-block error"
                                          ng-show="formFormapago.nameformapago.$invalid && formFormapago.nameformapago.$error.maxlength">La longitud máxima es de 50 caracteres</span>
                                </div>
                                <div class="col-xs-12 error">
                                    <div class="input-group">
                                        <span class="input-group-addon">Código SRI: </span>
                                        <input type="text" class="form-control" name="codigosri" id="codigosri" ng-model="codigosri" placeholder=""
                                               ng-required="true" ng-maxlength="3">
                                    </div>

                                    <div class="input-group">
                                        <span class="input-group-addon">Estado: </span>
                                        <select class="selectpicker form-control" id="estado" ng-model="estado">
                                            <option value="true">Activo</option>
                                            <option value="false">Inactivo</option>
                                        </select>
                                    </div>

                                    <span class="help-block error"
                                          ng-show="nameformapago.codigosri.$invalid && nameformapago.codigosri.$touched">El código SRI es requerido</span>
                                    <span class="help-block error"
                                          ng-show="nameformapago.codigosri.$invalid && nameformapago.codigosri.$error.maxlength">La longitud máxima es de 3 caracteres</span>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            Cancelar <span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span>
                        </button>
                        <button type="button" class="btn btn-success" id="btn-save" ng-click="Save('formapago')" ng-disabled="nameformapago.$invalid">
                            Guardar <span class="glyphicon glyphicon-floppy-saved" aria-hidden="true"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>


        <!-- Modal Provincia Nativo-->
        <div class="modal fade" tabindex="-1" role="dialog" id="modalActionProvincia">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header modal-header-primary">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">{{form_title}}</h4>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal" name="formProvicia" novalidate="">
                            <div class="row">
                                <div class="col-xs-12 error">
                                    <div class="input-group">
                                        <span class="input-group-addon">Nombre Provincia: </span>
                                        <input type="text" class="form-control" name="nameprovincia" id="nameprovincia" ng-model="nameprovincia" placeholder=""
                                               ng-required="true" ng-maxlength="50">
                                    </div>
                                    <span class="help-block error"
                                          ng-show="formProvicia.nameprovincia.$invalid && formProvicia.nameprovincia.$touched">El nombre provincia es requerido</span>
                                    <span class="help-block error"
                                          ng-show="formProvicia.nameprovincia.$invalid && formProvicia.nameprovincia.$error.maxlength">La longitud máxima es de 50 caracteres</span>
                                </div>

                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            Cancelar <span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span>
                        </button>
                        <button type="button" class="btn btn-success" id="btn-save" ng-click="Save('prov')" ng-disabled="formProvicia.$invalid">
                            Guardar <span class="glyphicon glyphicon-floppy-saved" aria-hidden="true"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>



        <!-- Modal canton Nativo-->

        <div class="modal fade" tabindex="-1" role="dialog" id="modalActioncanton">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header modal-header-primary">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">{{form_title}}</h4>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal" name="formsricanton" novalidate="">
                            <div class="row">
                                <div class="col-xs-12 error">
                                    <div class="input-group">
                                        <span class="input-group-addon">Provincia: </span>
                                        <select class="selectpicker form-control" id="SLprovincia" ng-model="SLprovincia" ng-required="true">
                                            <option ng-repeat="provi in provincia" value="{{provi.idprovincia}}">{{provi.nameprovincia}}</option>
                                        </select>
                                    </div>
                                    <span class="help-block error"
                                          ng-show=" formsricanton.SLprovincia.$invalid && formsricanton.SLprovincia==''">La provincia es requerida</span>

                                </div>

                                <div class="col-xs-12 error">
                                    <div class="input-group">
                                        <span class="input-group-addon">Nombre del Canton: </span>
                                        <input type="text" class="form-control" name="namecanton" id="namecanton" ng-model="namecanton" placeholder=""
                                               ng-required="true" ng-maxlength="50">
                                    </div>
                                    <span class="help-block error"
                                          ng-show="formsricanton.namecanton.$invalid && formsricanton.namecanton.$touched">El nombre del Canton es requerido</span>
                                    <span class="help-block error"
                                          ng-show="formsricanton.namecanton.$invalid && formsricanton.namecanton.$error.maxlength">La longitud máxima es de 50 caracteres</span>
                                </div>

                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            Cancelar <span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span>
                        </button>
                        <button type="button" class="btn btn-success" id="btn-save" ng-click="Save('canton')" ng-disabled="formsricanton.$invalid">
                            Guardar <span class="glyphicon glyphicon-floppy-saved" aria-hidden="true"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal canton Nativo-->

        <div class="modal fade" tabindex="-1" role="dialog" id="modalActionParroquia">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header modal-header-primary">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">{{form_title}}</h4>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal" name="formsriparroquia" novalidate="">
                            <div class="row">
                                <div class="col-xs-12 error">
                                    <div class="input-group">
                                        <span class="input-group-addon">Canton: </span>
                                        <select class="selectpicker form-control" id="SLcanton" ng-model="SLcanton" ng-required="true">
                                            <option ng-repeat="cant in canton" value="{{cant.idcanton}}">{{cant.namecanton}}</option>
                                        </select>
                                    </div>
                                    <span class="help-block error"
                                          ng-show="formsriparroquia.SLCanton.$invalid && formsricanton.SLCanton==''">La provincia es requerida</span>

                                </div>

                                <div class="col-xs-12 error">
                                    <div class="input-group">
                                        <span class="input-group-addon">Nombre Parroquia: </span>
                                        <input type="text" class="form-control" name="nameparroquia" id="nameparroquia" ng-model="nameparroquia" placeholder=""
                                               ng-required="true" ng-maxlength="50">
                                    </div>
                                    <span class="help-block error"
                                          ng-show="formsriparroquia.nameparroquia.$invalid && formsriparroquia.nameparroquia.$touched">El nombre Parroquia es requerido</span>
                                    <span class="help-block error"
                                          ng-show="formsriparroquia.nameparroquia.$invalid && formsriparroquia.nameparroquia.$error.maxlength">La longitud máxima es de 50 caracteres</span>
                                </div>

                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            Cancelar <span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span>
                        </button>
                        <button type="button" class="btn btn-success" id="btn-save" ng-click="Save('parroquia')" ng-disabled="formsriparroquia.$invalid">
                            Guardar <span class="glyphicon glyphicon-floppy-saved" aria-hidden="true"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>



        <!-- Modal Confirmacion Borrado -->


        <div class="modal fade" tabindex="-1" role="dialog" id="modalConfirmDelete">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header modal-header-danger">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Confirmación</h4>
                    </div>
                    <div class="modal-body">
                        <span>Realmente desea eliminar el Regsitro: <span style="font-weight: bold;">{{tipodocument_seleccionado}}</span></span>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            Cancelar <span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span>
                        </button>
                        <button type="button" class="btn btn-danger" id="btn-save" ng-click="delete(TipoAccion)">
                            Eliminar <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                        </button>                    </div>
                </div>
            </div>
        </div>

    </div>



    <!-- Modal Mensajes -->


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


    <!-- Modal Provincia -->
    <div id="myProvincia" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"> Datos Provincia</h4>
                </div>
                <div class="modal-body">
                    <div class = "row">
                        <div class="col-xs-11" style ="margin-left:20px;">
                            <div class="input-group">
                                <span class="input-group-addon">Nombre: </span>
                                <input type="text" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class ="row">
                        <div class="col-xs-7"></div>
                        <div class="col-xs-2" style ="margin-left:-10px;">
                            <button type="button" class="btn btn-success">
                                Guardar <span class="glyphicon glyphicon glyphicon-floppy-saved" aria-hidden="true"></span>
                            </button>
                        </div>
                        <div class="col-xs-2">
                            <button type="button" class="btn btn-default" data-dismiss="modal">
                                Cancelar <span class="glyphicon glyphicon glyphicon-ban-circle" aria-hidden="true"></span>
                            </button>

                        </div>
                    </div>
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
<script src="<?= asset('app/controllers/nomencladorController.js') ?>"></script>



</html>