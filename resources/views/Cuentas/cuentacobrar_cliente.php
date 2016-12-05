

<div ng-controller="ccClienteController">

    <div class="container" style="margin-top: 2%;">
        <fieldset>
            <legend style="padding-bottom: 10px;">


            </legend>

            <div class="col-sm-6 col-xs-12">
                <div class="form-group has-feedback">
                    <input type="text" class="form-control" id="t_busqueda" placeholder="BUSCAR..." ng-model="t_busqueda">
                    <span class="glyphicon glyphicon-search form-control-feedback" aria-hidden="true"></span>
                </div>
            </div>

            <div class="col-xs-12">

                <table class="table table-responsive table-striped table-hover table-condensed">
                    <thead class="bg-primary">
                    <tr>
                        <th style="width: 15%;">CI/RUC</th>
                        <th style="width: 15%;">Nro Suministro</th>
                        <th>Nombres y Apellidos</th>
                        <th style="width: 10%;">Fecha</th>
                        <th style="width: 10%;">Dividendos</th>
                        <th style="width: 10%;">Pago/Dividendo</th>
                        <th style="width: 10%;">Pago Total</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr dir-paginate="cuenta in cuentascobrar | orderBy:sortKey:reverse |itemsPerPage:10 | filter : t_busqueda" ng-cloak>
                        <td>{{cuenta.cliente.documentoidentidad}}</td>
                        <td>{{cuenta.numerosuministro}}</td>
                        <td>{{cuenta.cliente.complete_name}}</td>
                        <td>{{cuenta.fecha}}</td>
                        <td class="text-right">{{cuenta.dividendos}}</td>
                        <td class="text-right">{{cuenta.pagoporcadadividendo}}</td>
                        <td class="text-right">{{cuenta.pagototal}}</td>
                    </tr>
                    </tbody>
                </table>
                <dir-pagination-controls
                    max-size="5"
                    direction-links="true"
                    boundary-links="true" >
                </dir-pagination-controls>

            </div>
        </fieldset>
    </div>


</div>

