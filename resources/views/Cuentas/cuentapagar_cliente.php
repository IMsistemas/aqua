
    <div ng-controller="cpClienteController">

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
                        <th>Nombre y Apellidos</th>
                        <th style="width: 15%;">Fecha</th>
                        <th style="width: 15%;">Valor</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr ng-repeat="cuenta in cuentas" ng-cloak>
                        <td>{{cuenta.documentoidentidad}}</td>
                        <td>{{cuenta.apellido + ' ' + cuenta.nombre}}</td>
                        <td>{{cuenta.fecha}}</td>
                        <td class="text-right">{{cuenta.valor}}</td>
                    </tr>
                    </tbody>
                </table>

            </div>
        </fieldset>
    </div>


</div>

