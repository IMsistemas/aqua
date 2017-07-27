<style type="text/css">
    .form-control {
        height: 30px;
        padding: 2px 12px;
    }
    textarea.form-control {
        height: 30px;
    }
    .btn {
        padding: 4px 10px;
    }
</style>


        <div class="col-xs-12" ng-controller="rolPagoController" ng-init="initLoad()">

            <div class="col-xs-12">
                <div class="col-xs-6">
                    <h4>Rol de Pago</h4>
                </div>

                <div class="col-xs-6 text-right" style="margin-top: 5px;">

                    <button type="button" class="btn btn-success" id="btn-save" ng-click="save()" >
                        Guardar <span class="glyphicon glyphicon-floppy-saved" aria-hidden="true"></span>
                    </button>

                    <button type="button" class="btn btn-info" ng-click="getConfigNomina()">
                        Imprimir <span class="glyphicon glyphicon-print" aria-hidden="true"></span>
                    </button>

                </div>

            </div>
            <div class="col-xs-12">
                <hr>
            </div>


            <div class="col-xs-4">

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

            <div class="col-xs-4">
                <fieldset>
                    <legend>Datos del Empleado</legend>
                    <div class="col-sm-12 col-xs-12" style="margin-top: 5px;">
                        <div class="input-group">
                            <span class="input-group-addon">Empleado: </span>
                            <select class="form-control" name="empleado" id="empleado" ng-model="empleado"
                                    ng-options="value.id as value.label for value in empleados" ng-change="fillDataEmpleado()" required></select>
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

            <div class="col-xs-4">

                <fieldset>
                    <legend>Datos del Rol de Pago</legend>
                    <div class="col-sm-12 col-xs-12" style="margin-top: 5px;">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-tags" aria-hidden="true"></i> Dias Calculo: </span>
                            <input type="text" disabled class="form-control" name="diascalculo" id="diascalculo" ng-model="diascalculo" required/>
                        </div>
                    </div>
                    <div class="col-sm-12 col-xs-12" style="margin-top: 5px;">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-time" aria-hidden="true"></i> Horas Calculo: </span>
                            <input type="text" disabled class="form-control" name="horascalculo" id="horascalculo" ng-model="horascalculo" required/>
                        </div>
                    </div>
                    <div class="col-sm-12 col-xs-12" style="margin-top: 5px;">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-calendar" aria-hidden="true"></i> Fecha: </span>
                            <input type="text" class="form-control" name="fecha" id="fecha" ng-model="fecha" required/>
                        </div>
                    </div>
                    <div class="col-sm-12 col-xs-12" style="margin-top: 5px;">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-usd" aria-hidden="true"></i> Base Aporte IESS: </span>
                            <input type="text" disabled class="form-control" name="baseiess" id="baseiess" ng-model="baseiess" required/>
                        </div>
                    </div>
                </fieldset>
            </div>

            <div class="col-xs-12" style="margin-top: 15px;">
                <div class="col-xs-12" style="margin-top: 10px;">
                    <fieldset>
                        <legend>Ingresos</legend>
                        <div class="col-xs-12">
                            <table style="padding-top: 0px; margin-top: 0px;" class="table table-responsive table-striped table-hover table-condensed table-bordered">
                                <thead class="bg-primary">
                                <tr>
                                    <th style="width: 30%;">Concepto</th>
                                    <th style="width: 8%;">Cantidad</th>
                                    <th style="width: 8%;">Valor</th>
                                    <th style="width: 8%;">Valor Total</th>
                                    <th style="width: 40%;">Observacion</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr ng-repeat="item in ingresos1" ng-cloak >
                                    <td>{{item.name_conceptospago}}</td>
                                    <td><input type="text" class="form-control" ng-disabled="empleado=='' " ng-model="item.cant" ng-blur="calcValores(item)" required/></td>
                                    <td><input type="text" disabled class="form-control"  ng-model="item.valor"/></td>
                                    <td><input type="text" disabled class="form-control" ng-model="item.valorTotal"/></td>
                                    <td><textarea class="form-control" ng-model="item.observacion" rows="1"></textarea></td>
                                </tr>
                                <tr>
                                    <td class="bg-primary" colspan="1">Valor Sueldo Basico:</td>
                                    <td class="bg-primary" colspan="1">{{valortotalCantidad}}</td>
                                    <td class="bg-primary" colspan="1"></td>
                                    <td class="bg-primary" colspan="1">{{valortotalIngreso}}</td>
                                    <td class="bg-primary" colspan="1"></td>
                                </tr>
                                <tr ng-repeat="item in ingresos2" ng-cloak >
                                    <td class="bg-info">{{item.name_conceptospago}}</td>
                                    <td class="bg-info"><input type="text" class="form-control" ng-model="item.cant" ng-blur="calcValores(item)" required/></td>
                                    <td class="bg-info"><input type="text" disabled class="form-control" ng-model="item.valor"/></td>
                                    <td class="bg-info"><input type="text" disabled class="form-control" ng-model="item.valorTotal"/></td>
                                    <td class="bg-info"><textarea class="form-control" ng-model="item.observacion" rows="1"></textarea></td>
                                </tr>
                                <tr ng-repeat="item in ingresos3" ng-cloak >
                                    <td class="bg-success">{{item.name_conceptospago}}</td>
                                    <td class="bg-success"><input type="text" disabled class="form-control" ng-model="item.cant"/></td>
                                    <td class="bg-success"><input type="text" disabled class="form-control" ng-model="item.valormax"/></td>
                                    <td class="bg-success"><input type="text" class="form-control" ng-model="item.valorTotal" ng-blur="calcValores(item)" /></td>
                                    <td class="bg-success"><textarea class="form-control" ng-model="item.observacion" rows="1"></textarea></td>
                                </tr>
                                <tr>
                                    <td class="bg-primary" colspan="3">Total Ingreso Bruto:</td>
                                    <td class="bg-primary" colspan="1">{{valortotalIngresoBruto}}</td>
                                    <td class="bg-primary" colspan="1"></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </fieldset>
                </div>
            </div>

            <div class="col-xs-12">
                <div class="col-xs-6" style="margin-top: 10px;">
                    <fieldset>
                        <legend>Deducciones</legend>
                        <div class="col-xs-12" style="padding: 0px;">
                            <table style="padding-top: 0px; margin-top: 0px;" class="table table-responsive table-striped table-hover table-condensed table-bordered">
                                <thead class="bg-danger">
                                <tr>
                                    <th style="width: 20%;">Concepto</th>
                                    <th style="width: 8%;">%</th>
                                    <th style="width: 8%;">Valor</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr ng-repeat="item in deducciones" ng-cloak >
                                    <td>{{item.name_conceptospago}}</td>
                                    <td><input type="text" class="form-control" ng-model="item.cant" required/></td>
                                    <td><input type="text" class="form-control" ng-model="item.valorTotal" ng-blur="calcValores(item)"/></td>
                                </tr>
                                <tr>
                                    <td class="bg-danger" colspan="2">Total Deducciones:</td>
                                    <td class="bg-danger" colspan="1">{{total_deducciones}}</td>
                                </tr>
                                <tr>
                                    <td class="bg-danger" colspan="2">Ingreso Bruto (-) Deducciones:</td>
                                    <td class="bg-danger" colspan="1">{{ingresoBruto_deducciones}}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </fieldset>
                </div>

                <div class="col-xs-6" style="margin-top: 10px;">
                    <fieldset>
                        <legend>Beneficios de Ley</legend>
                        <div class="col-xs-12" style="padding: 0px; margin: 0px;">
                            <table style="padding-top: 0px; margin-top: 0px;" class="table table-responsive table-striped table-hover table-condensed table-bordered">
                                <thead class="bg-warning">
                                <tr>
                                    <th style="width: 20%;">Concepto</th>
                                    <th style="width: 8%;">%</th>
                                    <th style="width: 8%;">Valor</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr ng-repeat="item in beneficios" ng-cloak >
                                    <td>{{item.name_conceptospago}}</td>
                                    <td><input type="text" class="form-control" ng-model="item.cant" required/></td>
                                    <td><input type="text" class="form-control" ng-model="item.valorTotal" ng-blur="calcValores(item)"/></td>
                                </tr>
                                <tr>
                                    <td class="bg-warning" colspan="2">Total Beneficios de Ley:</td>
                                    <td class="bg-warning" colspan="1">{{total_beneficios}}</td>
                                </tr>
                                <tr>
                                    <td class="bg-warning" colspan="2">Subtotal mensual + Beneficios de Ley:</td>
                                    <td class="bg-warning" colspan="1">{{ingresoBruto_beneficios}}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </fieldset>
                    <div class="col-sm-12 col-xs-12" style="margin-top: 5px; padding: 0px;">
                        <div class="col-sm-6 col-sm-12" style="padding: 0px;">
                            <div class="input-group">
                                <span class="input-group-addon">Total Sueldo Liquido: </span>
                                <input type="text" class="form-control" disabled name="sueldoliquido" id="sueldoliquido" ng-model="sueldoliquido" />
                                <span class="input-group-addon"> $ </span>
                            </div>
                        </div>

                        <div class="col-sm-6 col-xs-12" style="margin-top: 0px; padding: 0px;">
                            <div class="input-group">
                                <input type="text" class="form-control" name="sueldo_liquido" id="sueldo_liquido" ng-model="sueldo_liquido" placeholder="Cuenta Contable"
                                       readonly>
                                <input type="hidden" name="sueldo_liquido_h" id="sueldo_liquido_h" ng-model="sueldo_liquido_h">
                                <span class="input-group-btn" role="group">
                                    <button type="button" class="btn btn-info" id="btn-liquido" ng-click="showPlanCuenta('sueldo_liquido', 'sueldo_liquido_h')">
                                        <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xs-6" style="margin-top: 10px;">
                <fieldset>
                    <legend>Beneficios Adicionales</legend>
                    <div class="col-xs-12" style="padding: 0px; margin: 0px;">
                        <table style="padding-top: 0px; margin-top: 0px;" class="table table-responsive table-striped table-hover table-condensed table-bordered">
                            <thead class="bg-success">
                            <tr>
                                <th style="width: 20%;">Concepto</th>
                                <th style="width: 8%;">%</th>
                                <th style="width: 8%;">Valor</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr ng-repeat="item in benefadicionales" ng-cloak >
                                <td>{{item.name_conceptospago}}</td>
                                <td><input type="text" class="form-control" ng-model="item.cant" required/></td>
                                <td><input type="text" class="form-control" ng-model="item.valorTotal" ng-blur="calcValores(item)"/></td>
                            </tr>
                            <tr>
                                <td class="bg-success" colspan="2">Total Beneficios Adicionales:</td>
                                <td class="bg-success" colspan="1">{{total_adicionales}}</td>
                            </tr>
                            <tr>
                                <td class="bg-success" colspan="2">Total Gasto Empresarial:</td>
                                <td class="bg-success" colspan="1">{{total_empresarial}}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </fieldset>
            </div>

            <div class="modal fade" tabindex="-1" role="dialog" id="modalPlanCuenta">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header modal-header-primary">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Plan de Cuenta</h4>
                        </div>
                        <div class="modal-body">
                            <div class="row">

                                <div class="col-xs-12">
                                    <div class="form-group  has-feedback">
                                        <input type="text" class="form-control" id="" ng-model="searchContabilidad" placeholder="BUSCAR..." >
                                        <span class="glyphicon glyphicon-search form-control-feedback" ></span>
                                    </div>
                                </div>

                                <div class="col-xs-12">
                                    <table class="table table-responsive table-striped table-hover table-condensed table-bordered">
                                        <thead class="bg-primary">
                                        <tr>
                                            <th style="width: 15%;">ORDEN</th>
                                            <th>CONCEPTO</th>
                                            <th style="width: 10%;">CODIGO</th>
                                            <th style="width: 4%;"></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr ng-repeat="item in cuentas | filter:searchContabilidad" ng-cloak >
                                            <td>{{item.jerarquia}}</td>
                                            <td>{{item.concepto}}</td>
                                            <td>{{item.codigosri}}</td>
                                            <td>
                                                <input ng-show="item.madreohija=='1'" ng-hide="item.madreohija!='1'" type="radio" name="select_cuenta"  ng-click="click_radio(item)">
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">
                                Cancelar <span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span>
                            </button>
                            <button type="button" class="btn btn-primary" id="btn-ok" ng-click="selectCuenta()">
                                Aceptar <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                            </button>
                        </div>
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

            <div class="modal fade" tabindex="-1" role="dialog" id="modalError"  style="z-index: 999999;">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header modal-header-danger">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Error</h4>
                        </div>
                        <div class="modal-body">
                            <span>{{message_error}}</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
