

        <div class="col-xs-12" ng-controller="rolPagoController" ng-init="initLoad()">

            <div class="col-xs-12">

                <h4>Rol de Pago</h4>

                <hr>

            </div>

            <div class="col-xs-6">

                <fieldset>
                    <legend>Datos de la Empresa</legend>

                    <div class="col-sm-12 col-xs-12" style="margin-top: 5px;">
                        <div class="input-group">
                            <span class="input-group-addon">Razón Social: </span>
                            <input type="text" class="form-control" disabled name="razonsocial" id="razonsocial" ng-model="razonsocial" required/>
                        </div>
                    </div>

                    <div class="col-sm-12 col-xs-12" style="margin-top: 5px;">
                        <div class="input-group">
                            <span class="input-group-addon">Nombre Comercial: </span>
                            <input type="text" class="form-control" disabled name="nombrecomercial" id="nombrecomercial" ng-model="nombrecomercial" required/>
                        </div>
                    </div>
                    <div class="col-sm-12 col-xs-12" style="margin-top: 5px;">
                        <div class="input-group">
                            <span class="input-group-addon">Dirección: </span>
                            <input type="text" class="form-control" disabled name="direccion" id="direccion" ng-model="direccion" required />
                        </div>

                    </div>
                    <div class="col-sm-12 col-xs-12" style="margin-top: 5px;">
                        <div class="input-group">
                            <span class="input-group-addon">RUC: </span>
                            <span class="input-group-btn" style="width: 15%;">
                                            <input type="text" class="form-control" disabled id="establ" name="establ" ng-model="establ" ng-keypress="onlyNumber($event, 3, 't_establ')" ng-blur="calculateLength('t_establ', 3)" />
                                        </span>
                            <span class="input-group-btn" style="width: 15%;" >
                                            <input type="text" class="form-control" disabled id="pto" name="pto" ng-model="pto" ng-keypress="onlyNumber($event, 3, 't_pto')" ng-blur="calculateLength('t_pto', 3)" />
                                        </span>
                            <input type="text" class="form-control" id="secuencial" disabled name="secuencial" ng-model="secuencial" ng-keypress="onlyNumber($event, 13, 't_secuencial')" ng-blur="calculateLength('t_secuencial', 13)" />
                        </div>
                    </div>
                </fieldset>
            </div>

            <div class="col-xs-6" style="margin-top: 5px;">
                <fieldset>
                    <legend>Datos del Empleado</legend>
                    <div class="col-sm-12 col-xs-12" style="margin-top: 5px;">
                        <div class="input-group">
                            <span class="input-group-addon">Empleado: </span>
                            <select class="form-control" name="empleado" id="empleado" ng-model="empleado"
                                    ng-options="value.id as value.label for value in empleados" ng-onchange="fillDataEmpleado()" required></select>
                        </div>
                    </div>
                    <div class="col-sm-12 col-xs-12" style="margin-top: 5px;">
                        <div class="input-group">
                            <span class="input-group-addon">Identificacion: </span>
                            <input type="text" disabled class="form-control" name="identificacion" id="identificacion" ng-model="identificacion" required/>
                        </div>
                    </div>
                    <div class="col-sm-12 col-xs-12" style="margin-top: 5px;">
                        <div class="input-group">
                            <span class="input-group-addon">Cargo: </span>
                            <input type="text" disabled class="form-control" name="cargo" id="cargo" ng-model="cargo" required/>
                        </div>
                    </div>
                    <div class="col-sm-12 col-xs-12" style="margin-top: 5px;">
                        <div class="input-group">
                            <span class="input-group-addon">Sueldo Basico: </span>
                            <input type="text" disabled class="form-control" name="sueldo" id="sueldo" ng-model="sueldo" required/>
                        </div>
                    </div>
                </fieldset>
            </div>

            <div class="col-xs-12" style="margin-top: 15px;">

                <fieldset>
                    <legend>Datos del Rol de Pago</legend>
                    <div class="col-sm-3 col-xs-12" style="margin-top: 5px;">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-tags" aria-hidden="true"></i> Dias Calculo: </span>
                            <input type="text" disabled class="form-control" value="30" name="diascalculo" id="diascalculo" ng-model="diascalculo" required/>
                        </div>
                    </div>
                    <div class="col-sm-3 col-xs-12" style="margin-top: 5px;">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-time" aria-hidden="true"></i> Horas Calculo: </span>
                            <input type="text" disabled class="form-control" value="240" name="horascalculo" id="horascalculo" ng-model="horascalculo" required/>
                        </div>
                    </div>
                    <div class="col-sm-3 col-xs-12" style="margin-top: 5px;">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-calendar" aria-hidden="true"></i> Fecha: </span>
                            <input type="text" disabled class="form-control" name="fecha" id="fecha" ng-model="fecha" required/>
                        </div>
                    </div>
                    <div class="col-sm-3 col-xs-12" style="margin-top: 5px;">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-usd" aria-hidden="true"></i> Base Aporte IESS: </span>
                            <input type="text" disabled class="form-control" name="baseiess" id="baseiess" ng-model="baseiess" required/>
                        </div>
                    </div>

                </fieldset>
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
