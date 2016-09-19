

<div ng-controller="ccClienteController">

    <div class="container" style="margin-top: 2%;">
        <fieldset>
            <legend style="padding-bottom: 10px;">


            </legend>

            <div class="col-xs-6">
                <div class="form-group has-feedback">
                    <input type="text" class="form-control" id="search-list-trans" placeholder="BUSCAR..."
                           ng-model="t_search" ng-change="search();" >
                    <span class="glyphicon glyphicon-search form-control-feedback" aria-hidden="true"></span>
                </div>
            </div>

            <div class="col-xs-12">

                <table class="table table-responsive table-striped table-hover table-condensed">
                    <thead class="bg-primary">
                    <tr>
                        <th style="width: 15%;">Doc. Identidad</th>
                        <th style="width: 15%;">Nro Suministro</th>
                        <th>Nombre y Apellidos</th>
                        <th style="width: 10%;">Fecha</th>
                        <th style="width: 10%;">Dividendos</th>
                        <th style="width: 10%;">Pago/Dividendo</th>
                        <th style="width: 10%;">Pago Total</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr ng-repeat="cuenta in cuentascobrar" ng-cloak>
                        <td>{{cuenta.documentoidentidad}}</td>
                        <td>{{cuenta.numerosuministro}}</td>
                        <td>{{cuenta.apellido + ' ' + cuenta.nombre}}</td>
                        <td>{{cuenta.fecha}}</td>
                        <td class="text-right">{{cuenta.dividendos}}</td>
                        <td class="text-right">{{cuenta.pagoporcadadividendo}}</td>
                        <td class="text-right">{{cuenta.pagototal}}</td>
                    </tr>
                    </tbody>
                </table>

            </div>
        </fieldset>
    </div>


</div>

