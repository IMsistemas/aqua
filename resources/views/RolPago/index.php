

        <div class="col-xs-12" ng-controller="rolPagoController">

            <div class="col-xs-12">

                <h4>Rol de Pago</h4>

                <hr>

            </div>

            <div class="col-xs-12">

                <div class="col-sm-6 col-xs-12" style="margin-top: 5px;">
                    <div class="input-group">
                        <span class="input-group-addon">Razón Social: </span>
                        <input type="text" class="form-control" name="t_razonsocial" id="t_razonsocial" ng-model="t_razonsocial" required/>
                    </div>
                </div>

                <div class="col-sm-6 col-xs-12" style="margin-top: 5px;">
                    <div class="input-group">
                        <span class="input-group-addon">Nombre Comercial: </span>
                        <input type="text" class="form-control" name="t_nombrecomercial" id="t_nombrecomercial" ng-model="t_nombrecomercial" required/>
                    </div>
                </div>

            </div>

            <div class="col-xs-12">

                <div class="col-sm-6 col-xs-12" style="margin-top: 5px;">
                    <div class="input-group">
                        <span class="input-group-addon">Empleado: </span>
                        <select class="form-control" name="empleado" id="empleado" ng-model="empleado"
                                ng-options="value.id as value.label for value in empleados" required></select>
                    </div>
                </div>

            </div>


            <div class="modal fade" tabindex="-1" role="dialog" id="modalMessage"  style="z-index: 999999;">
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

        </div>
