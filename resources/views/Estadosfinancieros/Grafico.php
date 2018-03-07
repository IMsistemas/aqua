<div class="container" ng-controller="graficos" ng-cloak>
    <br><br><br>
    <div class="row">
        <div class="col-md-6 col-xs-12">
            <div class="input-group">
                <span class="input-group-addon">Tipo: </span>
                <select class="form-control" ng-model="tipografico" name="tipografico" id="tipografico" ng-change="generar_balance_general_grafico();">
                    <option value="bar">Barras</option>
                    <option value="horizontalBar">Barras Horizontales</option>
                    <!--<option value="doughnut">Dona</option>
                    <option value="pie">Pie</option>
                    <option value="line">Linea</option>-->
                </select>
            </div>
        </div>
        <div class="col-md-6 col-xs-12">
            <div class="input-group">
                <span class="input-group-addon">Tipo: </span>
                <select class="form-control" ng-model="tipografico2" name="tipografico2" id="tipografico2" ng-change="generar_de_estado_resultados_grafico()">
                    <option value="bar">Barras</option>
                    <option value="horizontalBar">Barras Horizontales</option>
                    <!--<option value="doughnut">Dona</option>
                    <option value="pie">Pie</option>
                    <option value="line">Linea</option>-->
                </select>
            </div>
        </div>
    </div>
    <div class="row" style="margin-top: 5px;">
        <dic class="col-md-6 col-xs-12">
            <div class="row">
                <div class="col-md-6 col-xs-12">
                    <div class="input-group container-date">
                        <span class="input-group-addon">Fecha Hasta.: </span>
                        <input type="type" class="form-control datepicker  input-sm" id="FechaF" ng-model="FechaF" >
                    </div>
                </div>
                <div class="col-md-6 col-xs-12 text-right">
                    <button type="button" class="btn btn-primary" ng-click="generar_balance_general_grafico();"><i class="glyphicon glyphicon-search"></i></button>
                </div>
            </div>
        </dic>
        <dic class="col-md-6 col-xs-12">
            <div class="row">
                <div class="col-md-5 col-xs-12">
                    <div class="input-group container-date">
                        <span class="input-group-addon">Fecha Desde.: </span>
                        <input type="type" class="form-control datepicker  input-sm" id="FechaI" ng-model="FechaI" >
                    </div>
                </div>
                <div class="col-md-5 col-xs-12">
                    <div class="input-group container-date">
                        <span class="input-group-addon">Fecha Hasta.: </span>
                        <input type="type" class="form-control datepicker  input-sm" id="FechaF2" ng-model="FechaF2" >
                    </div>
                </div>
                <div class="col-md-2 col-xs-12 text-right">
                    <button type="button" class="btn btn-primary" ng-click="generar_de_estado_resultados_grafico()"><i class="glyphicon glyphicon-search"></i></button>
                </div>
            </div>
        </dic>
    </div>
    <div class="row">
        <dic class="col-md-6 col-xs-12">  <h5><strong>{{balacer}}</strong></h5></dic>
        <dic class="col-md-6 col-xs-12"> <h5><strong>{{estador}}</strong></h5></dic>
    </div>
    <div class="row">
        <div class="col-md-6 col-xs-12" id="stay_canvas1">


        </div>
        <div class="col-md-6 col-xs-12" id="stay_canvas2">

        </div>
    </div>
</div>