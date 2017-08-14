
<div ng-controller="proveedoresController">

    <div class="col-xs-12">

        <h4>Gestión de Proveedores</h4>

        <hr>

    </div>

    <div class="col-xs-12" style="margin-top: 5px;">

        <div class="col-sm-6 col-xs-8">
            <div class="form-group has-feedback">
                <input type="text" class="form-control" id="busqueda" placeholder="BUSCAR..." ng-model="busqueda" ng-keyup="initLoad(1)">
                <span class="glyphicon glyphicon-search form-control-feedback" aria-hidden="true"></span>
            </div>
        </div>

        <div class="col-sm-6 col-xs-4">
            <button type="button" class="btn btn-primary" id="btnAgregar" style="float: right;" ng-click="toggle('add', 0)">Agregar  <span class="glyphicon glyphicon-plus" aria-hidden="true"></button>
        </div>

        <div class="col-xs-12" style="font-size: 12px !important;">

            <table class="table table-responsive table-striped table-hover table-condensed table-bordered">
                <thead class="bg-primary">
                <tr>
                    <th style="width: 4%">NO</th>
                    <th style="width: 10%">RUC / CI</th>
                    <th>RAZON SOCIAL</th>
                    <th>DIRECCION</th>
                    <th style="width: 8%">TELEFONO</th>
                    <th style="width: 8%">CELULAR</th>
                    <th style="width: 24%;">ACCIONES</th>
                </tr>
                </thead>
                <tbody>
                    <tr dir-paginate="proveedor in proveedores|orderBy:sortKey:reverse| itemsPerPage:10"  total-items="totalItems" ng-cloak >
                        <td>{{$index + 1}}</td>
                        <td>{{proveedor.numdocidentific}}</td>
                        <td>{{proveedor.razonsocial}}</td>
                        <td>{{proveedor.direccion}}</td>
                        <td>{{proveedor.telefonoprincipal}}</td>
                        <td>{{proveedor.celphone}}</td>
                        <td class="text-center">

                            <div class="btn-group" role="group" aria-label="...">
                                <button type="button" class="btn btn-info" ng-click="toggle('info', proveedor)"
                                        data-toggle="tooltip" data-placement="bottom" title="Contactos">
                                    Información <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                                </button>
                                <button type="button" class="btn btn-warning" ng-click="toggle('edit', proveedor)"
                                        data-toggle="tooltip" data-placement="bottom" title="Editar" >
                                    Editar <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                                </button>
                                <button type="button" class="btn btn-danger" ng-click="showModalConfirm(proveedor)"
                                        data-toggle="tooltip" data-placement="bottom" title="Eliminar">
                                    Eliminar <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                                </button>
                            </div>

                        </td>
                    </tr>
                </tbody>
            </table>
            <dir-pagination-controls
                on-page-change="pageChanged(newPageNumber)"

                template-url="dirPagination.html"

                class="pull-right"
                max-size="10"
                direction-links="true"
                boundary-links="true" >
            </dir-pagination-controls>

        </div>

    </div>


    <div class="modal fade" tabindex="-1" role="dialog" id="modalAction">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form class="form-horizontal" name="formEmployee" novalidate="">
                    <div class="modal-header modal-header-primary">
                        <div class="col-md-6 col-xs-12">
                            <h4 class="modal-title">{{form_title}}</h4>
                        </div>
                        <div class="col-md-5 col-xs-12">
                            <div class="input-group">
                                <span class="input-group-addon">Fecha de Ingreso:</span>
                                <input type="text" class="datepicker form-control" name="fechaingreso" id="fechaingreso" ng-model="fechaingreso" ng-required="true">
                            </div>
                            <span class="help-block error"
                                  ng-show="formEmployee.fechaingreso.$invalid && formEmployee.fechaingreso.$touched">La Fecha de Ingreso es requerida</span>
                        </div>
                        <div class="col-md-1 col-xs-12 text-right" style="padding: 0;">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        </div>
                    </div>
                    <div class="modal-body">

                        <div class="row">

                            <div class="col-xs-12" style="margin-top: 5px;">

                                <div class="col-md-6 col-xs-12">
                                    <div class="input-group">
                                        <span class="input-group-addon">Tipo Identificación: </span>
                                        <select class="form-control" name="tipoidentificacion" id="tipoidentificacion" ng-model="tipoidentificacion"
                                                ng-options="value.id as value.label for value in idtipoidentificacion" required></select>
                                    </div>
                                    <span class="help-block error"
                                          ng-show="formEmployee.tipoidentificacion.$invalid && formEmployee.tipoidentificacion.$touched">El Tipo de Identificación es requerido</span>
                                </div>


                                <div class="col-md-6 col-xs-12">
                                    <div class="input-group">
                                        <span class="input-group-addon">RUC / CI:</span>

                                        <angucomplete-alt
                                                id = "documentoidentidadempleado"
                                                pause = "200"
                                                selected-object = "showDataPurchase"

                                                input-changed="inputChanged"

                                                remote-url = "{{API_URL}}proveedor/getIdentify/"

                                                focus-out="focusOut()"


                                                title-field="numdocidentific"

                                                minlength="1"
                                                input-class="form-control form-control-small small-input"
                                                match-class="highlight"
                                                field-required="true"
                                                input-name="documentoidentidadempleado"
                                                disable-input="guardado"
                                                text-searching="Buscando Identificaciones Personas"
                                                text-no-results="Persona no encontrada"

                                        > </angucomplete-alt>

                                    </div>
                                    <span class="help-block error"
                                          ng-show="formEmployee.documentoidentidadempleado.$invalid && formEmployee.documentoidentidadempleado.$touched">La Identificación es requerido</span>
                                    <span class="help-block error"
                                          ng-show="formEmployee.documentoidentidadempleado.$invalid && formEmployee.documentoidentidadempleado.$error.maxlength">La longitud máxima es de 13 caracteres</span>
                                </div>

                            </div>

                            <div class="col-xs-12" style="margin-top: 5px;">
                                <div class="col-xs-12">
                                    <div class="input-group">
                                        <span class="input-group-addon">Razón Social: </span>
                                        <input type="text" class="form-control" name="razonsocial" id="razonsocial"
                                               ng-model="razonsocial" ng-required="true" ng-maxlength="200" >
                                    </div>
                                    <span class="help-block error"
                                          ng-show="formEmployee.razonsocial.$invalid && formEmployee.razonsocial.$touched">La Razón Social es requerida</span>
                                    <span class="help-block error"
                                          ng-show="formEmployee.razonsocial.$invalid && formEmployee.razonsocial.$error.maxlength">La longitud máxima es de 200 caracteres</span>
                                </div>
                            </div>

                            <div class="col-xs-12" style="margin-top: 5px;">
                                <div class="col-md-6 col-xs-12">
                                    <div class="input-group">
                                        <span class="input-group-addon">Teléfono Principal: </span>
                                        <input type="text" class="form-control" name="telefonoprincipal" id="telefonoprincipal"
                                               ng-model="telefonoprincipal" ng-minlength="9" ng-maxlength="16" ng-pattern="/^([0-9-\(\)]+)$/" >
                                    </div>
                                    <span class="help-block error"
                                          ng-show="formEmployee.telefonoprincipal.$invalid && formEmployee.telefonoprincipal.$error.maxlength">La longitud máxima es de 16 números</span>
                                    <span class="help-block error"
                                          ng-show="formEmployee.telefonoprincipal.$invalid && formEmployee.telefonoprincipal.$error.pattern">El Teléfono debe ser solo números </span>
                                    <span class="help-block error"
                                          ng-show="formEmployee.telefonoprincipal.$invalid && formEmployee.telefonoprincipal.$error.minlength">La longitud mínima es de 9 caracteres</span>
                                </div>

                                <div class="col-md-6 col-xs-12">
                                    <div class="input-group">
                                        <span class="input-group-addon">Celular: </span>
                                        <input type="text" class="form-control" name="celular" id="celular"
                                               ng-model="celular" ng-minlength="10" ng-maxlength="16" ng-pattern="/^([0-9-\(\)]+)$/">
                                    </div>
                                    <span class="help-block error"
                                          ng-show="formEmployee.celular.$invalid && formEmployee.celular.$error.maxlength">La longitud máxima es de 16 números</span>
                                    <span class="help-block error"
                                          ng-show="formEmployee.celular.$invalid && formEmployee.celular.$error.pattern">El Teléfono debe ser solo números, guion y espacios</span>
                                    <span class="help-block error"
                                          ng-show="formEmployee.celular.$invalid && formEmployee.celular.$error.minlength">La longitud mínima es de 10 caracteres</span>
                                </div>
                            </div>

                            <div class="col-xs-12" style="margin-top: 5px;">

                                <div class="col-md-6 col-xs-12">
                                    <div class="input-group">
                                        <span class="input-group-addon">E-mail: </span>
                                        <input type="text" class="form-control" name="correo" id="correopersona" ng-model="correo" ng-pattern="/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/" placeholder="" >
                                    </div>
                                    <span class="help-block error"
                                          ng-show="formEmployee.correo.$invalid && formEmployee.correo.$error.pattern">Formato de email no es correcto</span>
                                </div>

                                <div class="col-md-6 col-xs-12">

                                </div>

                            </div>

                            <div class="col-xs-12" style="margin-top: 5px;">
                                <div class="col-sm-4 col-xs-12">
                                    <div class="input-group">
                                        <span class="input-group-addon">Provincia: </span>
                                        <select class="form-control" name="provincia" id="provincia" ng-model="provincia"
                                                ng-options="value.id as value.label for value in provincias" ng-change="getCantones()" required></select>
                                    </div>
                                    <span class="help-block error"
                                          ng-show="formEmployee.provincia.$invalid && formEmployee.provincia.$touched">La Provincia es requerida</span>
                                </div>
                                <div class="col-sm-4 col-xs-12">
                                    <div class="input-group">
                                        <span class="input-group-addon">Cantón: </span>
                                        <select class="form-control" name="canton" id="canton" ng-model="canton"
                                                ng-options="value.id as value.label for value in cantones" ng-change="getParroquias()" required></select>
                                    </div>
                                    <span class="help-block error"
                                          ng-show="formEmployee.canton.$invalid && formEmployee.canton.$touched">El Cantón es requerido</span>
                                </div>
                                <div class="col-sm-4 col-xs-12">
                                    <div class="input-group">
                                        <span class="input-group-addon">Parroquia: </span>
                                        <select class="form-control" name="parroquia" id="parroquia" ng-model="parroquia"
                                                ng-options="value.id as value.label for value in parroquias" required></select>
                                    </div>
                                    <span class="help-block error"
                                          ng-show="formEmployee.parroquia.$invalid && formEmployee.parroquia.$touched">La Parroquia es requerido</span>
                                </div>
                            </div>

                            <div class="col-xs-12" style="margin-top: 5px;">
                                <div class="col-xs-12">
                                    <div class="input-group">
                                        <span class="input-group-addon">Dirección: </span>
                                        <input type="text" class="form-control" name="direccion" id="direccion" ng-model="direccion" ng-maxlength="256">
                                    </div>
                                    <span class="help-block error"
                                          ng-show="formEmployee.direccion.$invalid && formEmployee.direccion.$error.maxlength">La longitud máxima es de 256 caracteres</span>
                                </div>
                            </div>

                            <div class="col-xs-12" style="margin-top: 5px;">

                                <div class="col-sm-6 col-xs-12">
                                    <div class="input-group">
                                        <span class="input-group-addon">C. Contab.: </span>
                                        <input type="text" class="form-control" name="cuenta_employee" id="cuenta_employee" ng-model="cuenta_employee" placeholder=""
                                               ng-required="true" readonly>
                                        <span class="input-group-btn" role="group">
                                                <button type="button" class="btn btn-info" id="btn-pcc" ng-click="showPlanCuenta()">
                                                    <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                                </button>
                                            </span>

                                    </div>
                                    <span class="help-block error" ng-show="formEmployee.cuenta_employee.$error.required">La asignación de una cuenta es requerida</span>
                                </div>

                                <div class="col-sm-6 col-xs-12">
                                    <div class="input-group">
                                        <span class="input-group-addon">Impuesto IVA: </span>
                                        <select class="form-control" name="iva" id="iva" ng-model="iva"
                                                ng-options="value.id as value.label for value in imp_iva" required></select>
                                    </div>
                                    <span class="help-block error"
                                          ng-show="formEmployee.iva.$invalid && formEmployee.iva.$touched">El Impuesto IVA es requerido</span>
                                </div>

                            </div>

                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            Cancelar <span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span>
                        </button>
                        <button type="button" class="btn btn-success" id="btn-save" ng-click="save(modalstate)" ng-disabled="formEmployee.$invalid">
                            Guardar <span class="glyphicon glyphicon-floppy-saved" aria-hidden="true"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" id="modalMessage" style="z-index: 99999;">
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

    <div class="modal fade" tabindex="-1" role="dialog" id="modalConfirmDelete">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header modal-header-danger">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Confirmación</h4>
                </div>
                <div class="modal-body">
                    <span>Realmente desea eliminar el Proveedor: <span style="font-weight: bold;">{{empleado_seleccionado}}</span></span>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        Cancelar <span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span>
                    </button>
                    <button type="button" class="btn btn-danger" id="btn-save" ng-click="destroy()">
                        Eliminar<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                    </button>
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

    <div class="modal fade" tabindex="-1" role="dialog" id="modalContactos">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header modal-header-primary">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Contactos del Proveedor {{razonsocial_contacto}}</h4>
                </div>
                <form role="form" name="formcontactos">

                    <div class="modal-body">
                        <div class="col-sm-4 col-xs-6">
                            <div class="form-group has-feedback">
                                <input type="text" class="form-control" id="search" placeholder="BUSCAR..."
                                       ng-model="searchcontato" >
                                <span class="glyphicon glyphicon-search form-control-feedback" aria-hidden="true"></span>
                            </div>
                        </div>
                        <div class="col-sm-8 col-xs-2">
                            <button type="button" class="btn btn-primary pull-right"  ng-click="addcontacto()" ng-disabled="button">
                                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                            </button>
                        </div>
                        <table class="table table-responsive table-striped table-hover table-condensed">
                            <thead class="bg-primary">
                            <tr>
                                <th>Nombre</th>
                                <th>Teléfono Principal</th>
                                <th>Teléfono Secundario</th>
                                <th>Celular</th>
                                <th>Observación</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr  ng-repeat="contacto in contactos  | filter:searchcontato">

                                <td >
                                    <input type="text" ng-keypress="onlyCharasterAndSpace($event);" name='contacto{{$index}}' class="form-control"
                                           ng-model="contacto.nombrecontacto" ng-pattern="/^([a-zA-ZáéíóúñÑ ])+$/" focus-me="contacto{{$index}} == edit" required />
                                    <span class="help-block error"
                                          ng-show="formcontactos.contacto{{$index}}.$invalid && formcontactos.contacto{{$index}}.$touched">Requerido</span>
                                    <span class="help-block error"
                                          ng-show="formcontactos.contacto{{$index}}.$error.pattern">Sólo se permite letras y espacios</span>
                                </td >
                                <td>
                                        <input type="text" class="form-control"  name='telefonoprincipalcont{{$index}}' ng-model="contacto.telefonoprincipalcont"
                                               required ng-minlength="9" ng-maxlength="9" ng-pattern="/^([0-9]+)$/"/>
                                        <span class="help-block error"
                                              ng-show="formcontactos.telefonoprincipalcont{{$index}}.$invalid && formcontactos.telefonoprincipalcont{{$index}}.$touched">Requerido</span>
                                        <span class="help-block error"
                                              ng-show="formcontactos.telefonoprincipalcont{{$index}}.$invalid && formcontactos.telefonoprincipalcont{{$index}}.$error.maxlength">La longitud máxima es de 9</span>
                                        <span class="help-block error"
                                              ng-show="formcontactos.telefonoprincipalcont{{$index}}.$invalid && formcontactos.telefonoprincipalcont{{$index}}.$error.minlength">La longitud mínima es de 9</span>
                                        <span class="help-block error"
                                          ng-show="formcontactos.telefonoprincipalcont{{$index}}.$invalid && formcontactos.telefonoprincipalcont{{$index}}.$error.pattern">
                                                Solo números</span>
                                </td>
                                <td >
                                    <input type="text" class="form-control" name='telefonosecundario{{$index}}' ng-model="contacto.telefonosecundario"
                                           ng-minlength="9" ng-maxlength="9" ng-pattern="/^([0-9]+)$/"/>
                                    <span class="help-block error"
                                          ng-show="formcontactos.telefonosecundario{{$index}}.$invalid && formcontactos.telefonosecundario{{$index}}.$error.maxlength">La longitud máxima es de 9</span>
                                    <span class="help-block error"
                                          ng-show="formcontactos.telefonosecundario{{$index}}.$invalid && formcontactos.telefonosecundario{{$index}}.$error.minlength">La longitud mínima es de 9</span>
                                    <span class="help-block error"
                                          ng-show="formcontactos.telefonosecundario{{$index}}.$invalid && formcontactos.telefonosecundario{{$index}}.$error.pattern">
                                                Solo números</span>
                                </td>
                                <td >
                                    <input type="text" class="form-control" ng-model="contacto.celular" name="celularcontacto{{$index}}" ng-minlength="10" ng-maxlength="10" ng-pattern="/^([0-9]+)$/"/>
                                    <span class="help-block error"
                                          ng-show="formcontactos.celularcontacto{{$index}}.$invalid && formcontactos.celularcontacto{{$index}}.$error.maxlength">La longitud máxima es de 10</span>
                                    <span class="help-block error"
                                          ng-show="formcontactos.celularcontacto{{$index}}.$invalid && formcontactos.celularcontacto{{$index}}.$error.minlength">La longitud mínima es de 10</span>
                                    <span class="help-block error"
                                          ng-show="formcontactos.celularcontacto{{$index}}.$invalid && formcontactos.celularcontacto{{$index}}.$error.pattern">
                                                Solo números</span>
                                </td>
                                <td >
                                    <input type="text" class="form-control" ng-model="contacto.observacion" />
                                </td>
                                <td >
                                    <button ng-click="eliminarContacto(contacto)" type="button" class="btn btn-danger"><i class="fa fa-trash"></i></button>
                                </td>

                            </tr>

                            </tbody>
                        </table>
                    </div>

                </form>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        Cancelar <span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span>
                    </button>
                    <button type="button" class="btn btn-success" id="botonguardarcontactos" ng-click="saveAllContactos()" ng-disabled="formcontactos.$invalid">
                        Guardar <span class="glyphicon glyphicon-floppy-saved" aria-hidden="true"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" id="modalMessageDeleteContacto" style="z-index: 99999;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header modal-header-danger">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Confirmación</h4>
                </div>
                <div class="modal-body">
                    <span>{{message}}</span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        Cancelar <span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span>
                    </button>
                    <button type="button" class="btn btn-danger" id="btn-eliminarcontacto" ng-click="destroyContacto()">
                        Eliminar<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>


</div>

