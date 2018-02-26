

<div class="container" ng-controller="atsController" ng-init="initLoad()">

    <div class="col-xs-12">

        <h4>Anexo Transaccional Simplificado (ATS)</h4>

        <hr>

    </div>

    <div class="col-xs-12" style="margin-top: 5px;">


        <div class="col-xs-12 text-right">
            <button type="button" class="btn btn-primary" style="float: right;" ng-click="generarShow()">
                Generar <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
            </button>
        </div>

        <div class="col-xs-12">
            <table class="table table-responsive table-striped table-hover table-condensed table-bordered">
                <thead class="bg-primary">
                <tr>
                    <th>ARCHIVO XML</th>
                </tr>
                </thead>
                <tbody>
                    <tr ng-repeat="item in archivos" ng-cloak >
                        <td><a href="{{item.url}}">{{item.name}}</a></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" id="modalAction">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header modal-header-primary">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Generar XML</h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" name="formCargo" novalidate="">
                        <div class="row">
                            <div class="col-xs-12 error"  style="margin-top: 5px;">
                                <div class="input-group container-date">
                                    <span class="input-group-addon">Año: </span>
                                    <input type="text" class="form-control datepickerY" name="year" id="year" ng-model="year" placeholder=""
                                           ng-required="true" ng-blur="reafirmYear()">
                                </div>
                                <span class="help-block error"
                                      ng-show="formCargo.year.$invalid && formCargo.year.$touched">El Año es requerido</span>

                            </div>

                            <div class="col-xs-12 error" style="margin-top: 5px;">
                                <div class="input-group container-date">
                                    <span class="input-group-addon">Mes: </span>
                                    <input type="text" class="form-control datepickerM" name="month" id="month" ng-model="month" placeholder=""
                                           ng-required="true" ng-blur="reafirmMonth()">
                                </div>
                                <span class="help-block error"
                                      ng-show="formCargo.month.$invalid && formCargo.month.$touched">El Mes es requerido</span>
                            </div>

                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        Cancelar <span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span>
                    </button>
                    <button type="button" class="btn btn-success" id="btn-save" ng-click="save()" ng-disabled="formCargo.$invalid">
                        Generar <span class="glyphicon glyphicon-floppy-saved" aria-hidden="true"></span>
                    </button>
                </div>
            </div>
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

    