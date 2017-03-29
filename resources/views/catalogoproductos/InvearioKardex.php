<!DOCTYPE html>
<html ng-app="softver-aqua">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">

    <title>Inventario</title>

    <link href="<?= asset('css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= asset('css/font-awesome.min.css') ?>" rel="stylesheet">
    <link href="<?= asset('css/index.css') ?>" rel="stylesheet">
    <link href="<?= asset('css/bootstrap-datetimepicker.min.css') ?>" rel="stylesheet">
    <link href="<?= asset('css/style_generic_app.css') ?>" rel="stylesheet">
    <link href="<?= asset('css/angucomplete-alt.css') ?>" rel="stylesheet">
</head>
<body>

	<div class="container-fluid" ng-controller="Kardex" ng-init="CargarBodegas();CargarCategoriaNivel1();" ng-cloak>
        <div class="row">
            <div class="col-xs-3">
                <h3><strong>Inventario (Kardex)</strong></h3>
            </div>
        </div>


        <div class="row">
            <div class="col-xs-2">
                <div class="form-group has-feedback">
                    <input type="text" class="form-control " id="search" placeholder="BUSCAR..." ng-model="search" ng-keyup="CargarInventario()">
                    <span class="glyphicon glyphicon-search form-control-feedback" aria-hidden="true"></span>
                </div>
            </div>
            <div class="col-xs-2">
                <div class="input-group">
                  <span class="input-group-addon">Fecha: </span>
                  <input type="type" class="form-control datepicker  input-sm" id="FechaK" ng-model="FechaK">
                </div>
            </div>
            <div class="col-xs-2">
                <div class="input-group">
                  <span class="input-group-addon">Categoría: </span>
                  <select class="form-control input-sm" id="CategoriaItem" ng-model="CategoriaItem" ng-change="CargarCategoriaNivel2();">
                    <option value="">Seleccione</option>
                    <option ng-repeat="c1 in Categoria1" value="{{c1.idcategoria}}">{{c1.nombrecategoria}}</option>
                  </select>
                </div>
            </div>
            <div class="col-xs-2">
                <div class="input-group">
                  <span class="input-group-addon">Categoría 2: </span>
                  <select class="form-control input-sm" id="SubCategoriaItem" ng-model="SubCategoriaItem">
                  <option value="">Seleccione</option>
                  <option ng-repeat="c2 in Categoria2" value="{{c2.idcategoria}}">{{c2.nombrecategoria}}</option>
                  </select>
                </div>
            </div>
            <div class="col-xs-2">
                <div class="input-group">
                  <span class="input-group-addon">Bodega: </span>
                  <select class="form-control input-sm" id="BodegaItem" ng-model="BodegaItem">
                  <option value="">Seleccione</option>
                  <option ng-repeat="b in Bodegas" value="{{b.idbodega}}">{{b.namebodega}}</option>
                  </select>
                </div>
            </div>
            <div class="col-xs-2">
                <button ng-click="CargarInventario()" class="btn btn-primary btn-sm">Actualizar <i class="glyphicon glyphicon glyphicon-refresh"></i></button>
            </div>
        </div>


        <div class="row">
            <div class="col-xs-12">

                <table class="table ">
                    <thead>
                        <tr class="bg-primary">
                            <th></th>
                            <th></th>
                            <th>Nombre</th>
                            <th>Codigo</th>
                            <th>Precio V.</th>
                            <th>Costo P.</th>
                            <th>Cantidad</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr ng-repeat="item in Inventario">
                            <td>{{$index+1}}</td>
                            <td>
                                <button class="btn btn-info btn-sm" ng-click="RegistroKardexPP(item)" title="Kardex"><i class="glyphicon glyphicon glyphicon-info-sign"></i></button>
                            </td>
                            <td>{{item.nombreproducto}}</td>
                            <td>{{item.codigoproducto}}</td>
                            <td>{{item.precioventa}}</td>
                            <td>{{item.costopromedio}}</td>
                            <td>{{item.cantidad}}</td>
                        </tr>
                    </tbody>
                </table>
                
            </div>
        </div>




    <div class="modal fade" id="RegistroKardePromedioPonderado" tabindex="-1" role="dialog">
      <div class="modal-dialog modal-lg" role="document" style="width:90%">
        <div class="modal-content">
          <div class="modal-header bg-primary" >
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">kardex Promedio Ponderado</h4>
          </div>
          <div class="modal-body">
            <div class="row">
                <div class="col-xs-3">
                    <div class="input-group">
                      <span class="input-group-addon">Fecha I.: </span>
                      <input type="type" class="form-control datepicker  input-sm" id="FechaI" ng-model="FechaI">
                    </div>
                </div>

                <div class="col-xs-3">
                    <div class="input-group">
                      <span class="input-group-addon">Fecha F.: </span>
                      <input type="type" class="form-control datepicker  input-sm" id="FechaF" ng-model="FechaF">
                    </div>
                </div>

                <div class="col-xs-3">
                    <div class="input-group">
                      <span class="input-group-addon">Estado: </span>
                      <select class="form-control input-sm " ng-model="ActivasInactivas">
                          <option value="A">Activas</option>
                          <option value="AN">Anuladas</option>
                      </select>
                    </div>
                </div>

                <div class="col-xs-3">
                    <button class="btn btn-primary btn-sm" ng-click="RegistroKardexPPActualizar()">Actualizar <i class="glyphicon glyphicon glyphicon-refresh"></i></button>
                </div>

            </div>
            <div class="row">
                <div class="col-xs-12">
                    <table class="table table-bordered table-condensend">
                        <thead>
                            <tr class="bg-primary">
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th colspan="3" class="text-center">Entradas</th>
                                <th colspan="3" class="text-center">Salidas</th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                            <tr class="bg-primary">
                                <th></th>
                                <th>Tipo T.</th>
                                <th>Fecha</th>
                                <th>Descripción</th>
                                <th>Cantidad</th>
                                <th>Costo U.</th>
                                <th>Costo T.</th>
                                <th>Cantidad</th>
                                <th>Costo U.</th>
                                <th>Costo T.</th>
                                <th>Total Cant.</th>
                                <th>Costo P.</th>
                                <th>Total Val.</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="k in Kardex">
                                <td>{{$id+1}}</td>
                                <td>{{k.transaccion.cont_tipotransaccion.siglas}}</td>
                                <td>{{k.fecharegistro}}</td>
                                <td>{{k.descripcion}}</td>
                                <td>{{k.cantidadE}}</td>
                                <td>{{k.costounitarioE}}</td>
                                <td>{{k.costototalE}}</td>
                                <td>{{k.cantidadS}}</td>
                                <td>{{k.costounitarioS}}</td>
                                <td>{{k.costototalS}}</td>
                                <td>{{k.CantidadT}}</td>
                                <td>{{k.CostoP}}</td>
                                <td>{{k.TotalV}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>


          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar <i class="glyphicon glyphicon glyphicon-ban-circle"></i></button>
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
    <script src="<?= asset('app/controllers/InvetarioItemKardex.js') ?>"></script>
</body>
</html>