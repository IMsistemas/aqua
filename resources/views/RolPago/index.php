

<div class="container" ng-controller="rolPagoController" ng-init="initLoad()">

    <div class="col-xs-12">
        <div class="col-xs-6">
            <h2>Rol de Pago</h2>
        </div>

    </div>



    <div class="col-xs-12">
        <hr>
    </div>

    <!-- Listado -->

    <div class="container1" ng-show="listado">

        <div class="col-xs-12" style="margin-top: 5px; margin-bottom: 2%">

            <div class="col-sm-4 col-xs-6">
                <div class="form-group has-feedback">
                    <input type="text" class="form-control" id="search" placeholder="BUSCAR..."
                           ng-model="search" ng-change="searchByFilter()">
                    <span class="glyphicon glyphicon-search form-control-feedback" aria-hidden="true"></span>
                </div>
            </div>
            <div class="col-sm-3 col-xs-3">
                <select class="form-control" name="empleadoFiltro" id="empleadoFiltro" ng-model="empleadoFiltro"
                        ng-change="searchByFilter()" ng-options="value.id as value.label for value in empleados" >
                </select>
            </div>

            <div class="col-sm-3 col-xs-3">
                <div class="form-group has-feedback">
                    <select class="form-control" name="estado" id="estado" ng-model="estadoFiltro"
                            ng-change="searchByFilter()">
                        <option value="">ACTIVOS</option>
                        <option value="0">ANULADOS</option>
                    </select>
                </div>
            </div>


            <div class="col-sm-2 col-xs-6">
                <button type="button" class="btn btn-primary" style="float: right;" ng-click="activeForm(0)">
                    Agregar <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                </button>
            </div>

            <div class="col-xs-12" style="font-size: 12px !important;">
                <table class="table table-responsive table-striped table-hover table-condensed table-bordered">
                    <thead class="bg-primary">
                    <tr>
                        <!--<th style="text-align: center; width: 4%;" ng-click="sort('codigocompra')">
                            NO
                            <span class="glyphicon sort-icon" ng-show="sortKey=='codigocompra'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                        </th>
                        <th style="text-align: center; width: 10%;" ng-click="sort('fecharegistrocompra')">
                            FECHA INGRESO
                            <span class="glyphicon sort-icon" ng-show="sortKey=='fecharegistrocompra'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                        </th>
                        <th style="text-align: center;" ng-click="sort('razonsocialproveedor')">
                            PROVEEDOR
                            <span class="glyphicon sort-icon" ng-show="sortKey=='razonsocialproveedor'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                        </th>-->
                        <th style="text-align: center; width: 5%;">NO.</th>
                        <th style="text-align: left;">EMPLEADO</th>
                        <th style="text-align: center; width: 12%;">FECHA REGISTRO</th>
                        <th style="text-align: center; width: 10%;">PERIODO</th>
                        <th style="text-align: center; width: 15%;">VALOR LIQUIDO</th>
                        <th class="text-center" style="width: 10%;">ACCIONES</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr ng-repeat="item in roles">
                    <td style="text-align: center;">{{$index + 1}}</td>
                    <td class="text-left">{{item.razonsocial}}</td>
                    <td class="text-center">{{item.fecha}}</td>
                    <td class="text-center">{{item.periodo}}</td>
                    <td class="text-right">$ {{item.valormoneda}}</td>
                    <td class="text-center">
                        <button type="button" class="btn btn-info" ng-click="viewInfoRol(item)"
                                data-toggle="tooltip" data-placement="bottom" title="Información">
                                <span class="glyphicon glyphicon-info-sign" aria-hidden="true">
                        </button>

                        <button type="button" class="btn btn-default" ng-click="showModalConfirm(item,0)"
                                data-toggle="tooltip" data-placement="bottom" title="Anular"  ng-disabled="item.estadoanulado==true" title="Anular">
                            <span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span>
                        </button>

                    </td>
                    </tr>
                    </tbody>
                </table>
                <dir-pagination-controls

                        on-page-change="pageChanged(newPageNumber)"

                        template-url="dirPagination.html"

                        class="pull-right"
                        max-size="8"
                        direction-links="true"
                        boundary-links="true" >

                </dir-pagination-controls>

            </div>

        </div>

    </div>

    <!-- Nomina -->
    <div class="col-xs-12" ng-show="!listado">

        <form class="form-horizontal" name="formRolPago" novalidate="">

            <div class="col-xs-4" style="padding-right: 0;">

                <fieldset>
                    <legend>Datos de la Empresa</legend>

                    <div class="col-sm-12 col-xs-12" style="padding: 0;">
                        <div class="input-group">
                            <span class="input-group-addon">Razón Social: </span>
                            <input type="text" class="form-control" disabled name="razonsocial" id="razonsocial" ng-model="razonsocial" required/>
                        </div>
                    </div>

                    <div class="col-sm-12 col-xs-12" style="margin-top: 5px; padding: 0;">
                        <div class="input-group">
                            <span class="input-group-addon">Nombre Comercial: </span>
                            <input type="text" class="form-control" disabled name="nombrecomercial" id="nombrecomercial" ng-model="nombrecomercial" required/>
                        </div>
                    </div>
                    <div class="col-sm-12 col-xs-12" style="margin-top: 5px; padding: 0;">
                        <div class="input-group">
                            <span class="input-group-addon">Dirección: </span>
                            <input type="text" class="form-control" disabled name="direccion" id="direccion" ng-model="direccion" required />
                        </div>

                    </div>
                    <div class="col-sm-12 col-xs-12" style="margin-top: 5px; padding: 0;">
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

            <div class="col-xs-4" style="padding-right: 0;">
                <fieldset>
                    <legend>Datos del Empleado</legend>
                    <div class="col-sm-12 col-xs-12" style=" padding: 0;">
                        <div class="input-group">
                            <span class="input-group-addon">Empleado: </span>
                            <select class="form-control" name="empleado" id="empleado" ng-model="empleado"
                                    ng-options="value.id as value.label for value in empleados" ng-change="fillDataEmpleado()" required></select>
                        </div>
                    </div>
                    <div class="col-sm-12 col-xs-12" style="margin-top: 5px; padding: 0;">
                        <div class="input-group">
                            <span class="input-group-addon">Identificación: </span>
                            <input type="text" disabled class="form-control" name="identificacion" id="identificacion" ng-model="identificacion" required/>
                        </div>
                    </div>
                    <div class="col-sm-12 col-xs-12" style="margin-top: 5px; padding: 0;">
                        <div class="input-group">
                            <span class="input-group-addon">Cargo: </span>
                            <input type="text" disabled class="form-control" name="cargo" id="cargo" ng-model="cargo" required/>
                        </div>
                    </div>
                    <div class="col-sm-12 col-xs-12" style="margin-top: 5px; padding: 0;">
                        <div class="input-group">
                            <span class="input-group-addon">Sueldo Básico: </span>
                            <input type="text" disabled class="form-control" name="sueldo" id="sueldo" ng-model="sueldo" required/>
                        </div>
                    </div>
                </fieldset>
            </div>

            <div class="col-xs-4" >

                <fieldset>
                    <legend>Datos del Rol de Pago</legend>
                    <div class="col-sm-12 col-xs-12" style="padding: 0;">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-tags" aria-hidden="true"></i> Días Cálculo: </span>
                            <input type="text" disabled class="form-control" name="diascalculo" id="diascalculo" ng-model="diascalculo" required/>
                        </div>
                    </div>
                    <div class="col-sm-12 col-xs-12" style="margin-top: 5px; padding: 0;">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-time" aria-hidden="true"></i> Horas Cálculo: </span>
                            <input type="text" disabled class="form-control" name="horascalculo" id="horascalculo" ng-model="horascalculo" required/>
                        </div>
                    </div>
                    <div class="col-sm-12 col-xs-12" style="margin-top: 5px; padding: 0;">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-calendar" aria-hidden="true"></i> Periodo: </span>
                            <input type="text" class="form-control datepickerP" name="periodo" id="periodo" ng-model="periodo" ng-blur="valuePeriodo()" required/>
                        </div>
                        <span class="help-block error"
                              ng-show="formRolPago.periodo.$invalid && formRolPago.periodo.$touched">El Periodo es requerido</span>
                    </div>
                    <div class="col-sm-12 col-xs-12" style="margin-top: 5px; padding: 0;">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-calendar" aria-hidden="true"></i> Fecha Registro: </span>
                            <input type="text" class="form-control datepicker" name="fecha" id="fecha" ng-model="fecha" ng-blur="valueFecha()" required/>
                        </div>
                        <span class="help-block error"
                              ng-show="formRolPago.fecha.$invalid && formRolPago.fecha.$touched">La Fecha de Registro es requerida</span>
                    </div>
                    <div class="col-sm-12 col-xs-12" style="margin-top: 5px; padding: 0;">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-usd" aria-hidden="true"></i> Base Aporte IESS: </span>
                            <input type="text" disabled class="form-control" name="baseiess" id="baseiess" ng-model="baseiess" required/>
                        </div>
                    </div>
                </fieldset>
            </div>

            <div class="row" style="margin-top: 10px;">
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
                                    <td><input type="text" class="form-control" ng-disabled="empleado=='' " ng-model="item.cantidad" ng-blur="calcValores(item)" /></td>
                                    <td><input type="text" disabled class="form-control"  ng-model="item.valor1"/></td>
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
                                    <td class="bg-info"><input type="text" class="form-control" ng-model="item.cantidad" ng-blur="calcValores(item)" /></td>
                                    <td class="bg-info"><input type="text" disabled class="form-control" ng-model="item.valor1"/></td>
                                    <td class="bg-info"><input type="text" disabled class="form-control" ng-model="item.valorTotal"/></td>
                                    <td class="bg-info"><textarea class="form-control" ng-model="item.observacion" rows="1"></textarea></td>
                                </tr>
                                <tr ng-repeat="item in ingresos3" ng-cloak >
                                    <td class="bg-success">{{item.name_conceptospago}}</td>
                                    <td class="bg-success"><input type="text" disabled class="form-control" ng-model="item.cantidad"/></td>
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

            <div class="row">
                <div class="col-xs-6" style="margin-top: 10px;">
                    <fieldset>
                        <legend>Deducciones</legend>
                        <div class="col-xs-12" style="padding: 0px;">
                            <table style="padding-top: 0px; margin-top: 0px;" class="table table-responsive table-striped table-hover table-condensed table-bordered">
                                <thead class="btn-danger">
                                <tr>
                                    <th style="width: 20%;">Concepto</th>
                                    <th style="width: 8%;">%</th>
                                    <th style="width: 8%;">Valor</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr ng-repeat="item in deducciones" ng-cloak >
                                    <td>{{item.name_conceptospago}}</td>
                                    <td><input type="text" class="form-control" ng-model="item.cantidad" /></td>
                                    <td><input type="text" class="form-control" ng-model="item.valorTotal" ng-blur="calcValores(item)"/></td>
                                </tr>
                                <tr>
                                    <td class="btn-danger" colspan="2">Total Deducciones:</td>
                                    <td class="btn-danger" colspan="1">{{total_deducciones}}</td>
                                </tr>
                                <tr>
                                    <td class="btn-danger" colspan="2">Ingreso Bruto (-) Deducciones:</td>
                                    <td class="btn-danger" colspan="1">{{ingresoBruto_deducciones}}</td>
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
                                <thead class="btn-warning">
                                <tr>
                                    <th style="width: 20%;">Concepto</th>
                                    <th style="width: 8%;">%</th>
                                    <th style="width: 8%;">Valor</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr ng-repeat="item in beneficios" ng-cloak >
                                    <td>{{item.name_conceptospago}}</td>
                                    <td><input type="text" class="form-control" ng-model="item.cantidad" /></td>
                                    <td><input type="text" class="form-control" ng-model="item.valorTotal" ng-blur="calcValores(item)"/></td>
                                </tr>
                                <tr>
                                    <td class="btn-warning" colspan="2">Total Beneficios de Ley:</td>
                                    <td class="btn-warning" colspan="1">{{total_beneficios}}</td>
                                </tr>
                                <tr>
                                    <td class="btn-warning" colspan="2">Subtotal mensual + Beneficios de Ley:</td>
                                    <td class="btn-warning" colspan="1">{{ingresoBruto_beneficios}}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </fieldset>

                    <div class="col-sm-12 col-xs-12" style="margin-top: 5px; padding: 0px;">

                        <div class="col-xs-12" style="padding: 0px;">
                            <div class="input-group">
                                <span class="input-group-addon">Total Sueldo Liquido: </span>
                                <input type="text" class="form-control" disabled name="sueldoliquido" id="sueldoliquido" ng-model="sueldoliquido" />
                                <span class="input-group-addon"> $ </span>
                            </div>
                        </div>

                        <div class="col-xs-12" style="margin-top: 5px; padding: 0px;">
                            <div class="input-group">
                                <input type="text" class="form-control" name="sueldo_liquido" id="sueldo_liquido" ng-model="sueldo_liquido" placeholder="Cuenta Contable"
                                       readonly required />
                                <input type="hidden" name="sueldo_liquido_h" id="sueldo_liquido_h" ng-model="sueldo_liquido_h">
                                <span class="input-group-btn" role="group">
                                        <button type="button" class="btn btn-info" id="btn-liquido" ng-click="showPlanCuenta('sueldo_liquido', 'sueldo_liquido_h')">
                                            <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                        </button>
                                    </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 text-center" style="margin-top: 15px;">
                        <button type="button" ng-show="!listado" class="btn btn-primary" ng-click="InicioList();">
                            Registros <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                        </button>

                        <button type="button" ng-show="!listado" class="btn btn-default" id="btn-anular" ng-disabled="numdocumento == 0 || estadoanulado == true" ng-click="showModalConfirm(item,0)">
                            Anular <span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span>
                        </button>

                        <button type="button" ng-show="!listado" class="btn btn-success" id="btn-save"  ng-click="save()" ng-disabled="formRolPago.$invalid" >
                            Guardar <span class="glyphicon glyphicon-floppy-saved" aria-hidden="true"></span>
                        </button>

                        <button type="button" ng-show="!listado" class="btn btn-info" id="btn-print" ng-disabled="numdocumento == 0" ng-click="printRol()">
                            Imprimir <span class="glyphicon glyphicon-print" aria-hidden="true"></span>
                        </button>

                    </div>

                </div>
            </div>

            <div class="col-xs-6" style="margin-top: 10px;">
                <fieldset>
                    <legend>Beneficios Adicionales</legend>
                    <div class="col-xs-12" style="padding: 0px; margin: 0px;">
                        <table style="padding-top: 0px; margin-top: 0px;" class="table table-responsive table-striped table-hover table-condensed table-bordered">
                            <thead class="btn-success">
                            <tr>
                                <th style="width: 20%;">Concepto</th>
                                <th style="width: 8%;">%</th>
                                <th style="width: 8%;">Valor</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr ng-repeat="item in benefadicionales" ng-cloak >
                                <td>{{item.name_conceptospago}}</td>
                                <td><input type="text" class="form-control" ng-model="item.cantidad" /></td>
                                <td><input type="text" class="form-control" ng-model="item.valorTotal" ng-blur="calcValores(item)"/></td>
                            </tr>
                            <tr>
                                <td class="btn-success" colspan="2">Total Beneficios Adicionales:</td>
                                <td class="btn-success" colspan="1">{{total_adicionales}}</td>
                            </tr>
                            <tr>
                                <td class="btn-success" colspan="2">Total Gasto Empresarial:</td>
                                <td class="btn-success" colspan="1">{{total_empresarial}}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </fieldset>
            </div>

        </form>

    </div>

    <!----------MODALES---------->

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

                        <div class="col-xs-12" style="height:380px; overflow: auto;">
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

    <div class="modal fade" tabindex="-1" role="dialog" id="modalMessageInfo"  style="z-index: 999999;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header modal-header-info">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Información</h4>
                </div>
                <div class="modal-body">
                    <span>{{message_info}}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" id="modalConfirmAnular">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header modal-header-danger">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Confirmación</h4>
                </div>
                <div class="modal-body">
                    <span>Está seguro que desea Anular el Rol de Pago: <strong>"{{numdocumento}}"</strong> seleccionado?</span>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        Cancelar <span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span>
                    </button>
                    <button type="button" class="btn btn-danger" id="btn-save" ng-click="anularRol()">
                        Anular
                    </button>
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

    <div class="modal fade" id="WPrint" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header btn-primary">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="WPrint_head"></h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xs-12" id="bodyprint">

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar <i class="glyphicon glyphicon glyphicon-ban-circle"></i> </button>
                </div>
            </div>
        </div>
    </div>

</div>
