
    <div ng-controller="proveedoresController">
        <div class="container">
            
    
        <div class="col-xs-12" style="margin-top: 2%; margin-bottom: 2%">
            <div class="col-sm-4 col-xs-6">
                <div class="form-group has-feedback">
                    <input type="text" class="form-control" id="search" placeholder="BUSCAR..."
                           ng-model="search" ng-change="searchByFilter()">
                    <span class="glyphicon glyphicon-search form-control-feedback" aria-hidden="true"></span>
                </div>
            </div>
            
            <div class="col-sm-8 col-xs-2">
                <button type="button" id='botonagregarproveedor' data-loading-text="Cargando..." class="btn btn-primary pull-right"  ng-click="mostrarmodal()" ng-disabled="button">
                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                </button>
            </div>
            <div class="col-xs-12">
                <table class="table table-responsive table-striped table-hover table-condensed">
                    <thead class="bg-primary">
                    <tr>
                        <th >RUC</th>
                        <th >Razón Social</th>
                        <th >Teléfonos</th>
                        <th >Dirección</th>
                        <th >Correo</th>
                        <th >Estado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr ng-repeat="proveedor in proveedores  | orderBy:'-idproveedor' | filter:search">
                        <td >
                        @{{proveedor.documentoproveedor}}
                        
                        </td>
                        <td>
                            @{{proveedor.razonsocialproveedor}}    
                        </td>
                        <td>
                            @{{proveedor.telefonoproveedor}}    
                        </td>
                        <td>
                            @{{proveedor.direccionproveedor}}    
                        </td>
                        <td>
                            @{{proveedor.correocontactoproveedor}}    
                        </td>
                        <td>
                            @{{proveedor.estado | activo }}
                                

                        </td>
                        <td class="text-center">
                            <button id='botonvercontacto' data-loading-text="Cargando..." ng-click="verContactos(proveedor)" type="button" class="btn btn-info btn-sm">verContacto</button>
                            <button id='botoneditarproveedor' data-loading-text="Cargando..." ng-click="editarProveedor(proveedor)" type="button" class="btn btn-warning btn-sm">Editar</button>
                            <button id="btnanular" ng-click="cambiarEstado(proveedor)" type="button" ng-show="proveedor.estado==1" class="btn btn-danger btn-sm" ng-disabled="proveedor.estado==0">@{{proveedor.estado | botonactivo }}</button>
                            <button id="btnanular" ng-click="cambiarEstado(proveedor)" type="button" ng-show="proveedor.estado==0" class="btn btn-success btn-sm" >@{{proveedor.estado | botonactivo }}</button>
                            
                            
                        
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            
            <div class="modal fade" id="modal-agregar">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header modal-header-primary">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title">Nuevo Proveedor No @{{proveedornuevo.idproveedor}} <span class='pull-right'> Fecha Ingreso: @{{proveedornuevo.fechaingresoproveedor}}</span> </h4>
                        </div>
                        <form name="formProveedores">

                            <div class="modal-body">
                                <div class="container">
                                            <h4>Datos Proveedor</h4>
                                            
                                        </div>
                                         
                                    <div class="row">
                                       
                                                                              

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">RUC</label>
                                                <input type="text" name="ruc" ng-minlength ="10" ng-keypress="onlyNumber($event)" ng-model="proveedornuevo.documentoproveedor" class="form-control" id="ruc" required>
                                                <span class="text-danger" 
                                                        ng-show=" formProveedores.ruc.$error.minlength">La longitud mínima es de 10 caracteres <br></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Razón Social</label>
                                                <input type="text" ng-keypress="onlyCharasterAndSpace($event);" ng-model="proveedornuevo.razonsocialproveedor" class="form-control" id="" required placeholder='Razon Social'>
                                            </div>
                                        </div>

                                       
                                    </div>
                                    <div class="row">
                                       
                                                                              

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Télefono</label>
                                                <input type="text" ng-minlength ="9" id="telefono" name="telefono" ng-keypress="onlyNumber($event)" ng-model="proveedornuevo.telefonoproveedor" class="form-control" id="" placeholder='Telefono'>
                                                <span class="text-danger" ng-show=" formProveedores.telefono.$error.minlength">La longitud mínima es de 9 caracteres <br></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Tipo Identficación</label>
                                                <select name="tipoidnt" ng-model="proveedornuevo.codigotipoid" id="input" class="form-control" required="required">
                                                    <option value="">--Seleccione--</option>
                                                    <option  ng-repeat="tipocontribuyente in tiposcontribuyentes" value="@{{tipocontribuyente.codigotipoid}}">@{{tipocontribuyente.tipoidentificacion}}</option>
                                                </select>
                                                <span ng-show="formProveedores.tipoidnt.$error.required">Seleccione Tipo de Identificación</span>
                                                
                                            </div>
                                        </div>

                                       
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="">Correo</label>
                                                
                                                <input type="email" ng-model="proveedornuevo.correocontactoproveedor" class="form-control" id="email" name="email" ng-pattern="/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/" >
                                                <span class="messages" ng-show="formProveedores.$submitted || formProveedores.email.$touched">
                                                <span class="text-danger" ng-show="formProveedores.email.$error.required">El campo es obligatorio.</span>
                                                <span class="text-danger" ng-show="formProveedores.email.$error.email">Formato de email incorrecto.</span>
                                                      </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="container">
                                            <h4>Ubicación del Proveedor</h4>
                                        </div>
                                    <div class="row"><div class="col-md-4">
                                            <div class="form-group">
                                                <label for="">Provincia</label>
                                                <select ng-change="cargarCiudades(provincia)" ng-model="provincia" name="provincia"  id="input" class="form-control" required="required">
                                                    <option value="">--Seleccione--</option>
                                                    <option ng-repeat="provincia in provincias" value="@{{provincia.idprovincia}}">@{{provincia.nombreprovincia}}</option>
                                                </select>
                                                 <span ng-show="formProveedores.provincia.$error.required">Seleccione una provincia.</span>
                                               
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="">Cantón</label>
                                                <select name="ciudad" ng-change="cargarSectores(ciudad)" ng-model="ciudad" id="input" class="form-control" required="required">
                                                    <option value="">--Seleccione--</option>
                                                    <option ng-repeat="ciudad in ciudades" value="@{{ciudad.idciudad}}">@{{ciudad.nombreciudad}}</option>
                                                </select>
                                                <span ng-show="formProveedores.ciudad.$error.required">Seleccione una ciudad.</span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="">Sector</label>
                                               
                                                <select name="sector" ng-model="proveedornuevo.idsector" id="input" class="form-control" required="required">
                                                     <option value="">--Seleccione--</option>
                                                    <option ng-repeat="sector in sectores" value="@{{sector.idsector}}">@{{sector.nombreparroquia}}</option>
                                                </select>
                                                <span ng-show="formProveedores.sector.$error.required">Seleccione un sector.</span>
                                            </div>
                                        </div>

                                       
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="">Dirección</label>
                                                <input type="text" ng-model="proveedornuevo.direccionproveedor" class="form-control" id="" required placeholder='Direccion'>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="container">
                                            <h4>Datos Contacto</h4>
                                            
                                        </div>
                                         
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Nombre Contacto</label>
                                                <input type="text" onkeypress="return soloLetras(event)" ng-model="proveedornuevo.nombrecontacto" class="form-control" id="" required placeholder='Nombre Contacto'>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Tel. Principal</label>
                                                <input type="text" ng-minlength ="9" id="telefonoprincipal" name="telefonoprincipal" ng-keypress="onlyNumber($event)" ng-model="proveedornuevo.telefonoprincipal" class="form-control" id="" required placeholder='Telefono Principal'>
                                                <span class="text-danger" ng-show=" formProveedores.telefonoprincipal.$error.minlength">La longitud mínima es de 9 caracteres <br></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Telf. Secundario</label>
                                                <input type="text" ng-minlength ="9" id="telefonosec" name="telefonosec" ng-keypress="onlyNumber($event)" ng-model="proveedornuevo.telefonosecundario" class="form-control" id="" placeholder='Telefono Secundario'>
                                                <span class="text-danger" ng-show=" formProveedores.telefonosec.$error.minlength">La longitud mínima es de 9 caracteres <br></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Celular</label>
                                                <input type="text" ng-minlength ="10" name="celular" id="celular" ng-keypress="onlyNumber($event)" ng-model="proveedornuevo.celular" class="form-control" id="" placeholder='Celular'>
                                                <span class="text-danger" ng-show=" formProveedores.celular.$error.minlength">La longitud mínima es de 10 caracteres <br></span> 
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="">Observación</label>
                                                <input type="text" ng-model="proveedornuevo.observacion" class="form-control" id="" Placeholder='Observacion'>
                                            </div>
                                        </div>
                                        
                                    </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                <input type="submit" id='botonaddproveedor' class="btn btn-success" ng-click="addproveedor()" value="Guardar" />
                            </div>
                        </form>

                    </div>
                </div>
            </div>
            <div class="modal fade" id="modal-editar">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header modal-header-primary">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title">Editar Proveedor No @{{proveedornuevo.idproveedor}} <span class='pull-right'> Fecha Ingreso: <strong ng-bind="fecha | date:'dd/MM/yyyy'"></strong></span> </h4>
                        </div>
                        <form name="formProveedoresedit" action="" method="POST" role="form" >

                            <div class="modal-body">
                                <div class="container">
                                            <h4>Datos Proveedor</h4>
                                            
                                        </div>
                                         
                                    <div class="row">
                                       
                                                                              

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">RUC</label>
                                                <input type="text" name="ruc" ng-minlength ="10" ng-keypress="onlyNumber($event)" ng-model="proveedornuevo.documentoproveedor" class="form-control" id="ruc" required>
                                                <span class="help-inline" 
                                                        ng-show=" formProveedoresedit.ruc.$error.minlength">La longitud mínima es de 10 caracteres <br></span>

                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Razón Social</label>
                                                <input type="text" ng-keypress="onlyCharasterAndSpace($event);" ng-model="proveedornuevo.razonsocialproveedor" class="form-control" id="" required>
                                            </div>
                                        </div>

                                       
                                    </div>
                                    <div class="row">
                                       
                                                                              

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Télefono</label>
                                                <input type="text" ng-minlength ="9" id="telefono" name="telefono" ng-keypress="onlyNumber($event)" ng-model="proveedornuevo.telefonoproveedor" class="form-control" id="" placeholder='Telefono'>
                                                <span class="text-danger" ng-show=" formProveedoresedit.telefono.$error.minlength">La longitud mínima es de 9 caracteres <br></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Tipo Identificación</label>
                                                <select name="tipoidnt" ng-model="proveedornuevo.codigotipoid" ng-options="tipocontribuyente.codigotipoid as tipocontribuyente.tipoidentificacion for tipocontribuyente in tiposcontribuyentes" class="form-control" required="required">
                                                </select>
                                                <span ng-show="formProveedoresedit.tipoidnt.$error.required">Seleccione un tipo de identificación.</span>
                                            </div>
                                        </div>

                                       
                                    </div>
                                    <div class="row">
                                       
                                                                              

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="">Correo</label>
                                                <input type="email" ng-model="proveedornuevo.correocontactoproveedor" class="form-control" id="email" name="email" ng-pattern="/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/" >
                                                <span class="messages" ng-show="formProveedoresedit.$submitted || formProveedores.email.$touched">
                                                <span class="text-danger" ng-show="formProveedoresedit.email.$error.required">El campo es obligatorio.</span>
                                                <span class="text-danger" ng-show="formProveedoresedit.email.$error.email">Formato de email incorrecto.</span>
                                                      </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="container">
                                            <h4>Ubicación del Proveedor</h4>
                                            
                                        </div>
                                       
                                    <div class="row">
                                       
                                                                              

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="">Provincia</label>
                                                <select ng-change="cargarCiudades(proveedornuevo.sector.ciudad.idprovincia)" ng-model="proveedornuevo.sector.ciudad.idprovincia" name="provincia"  id="input" class="form-control" required="required" ng-options="provincia.idprovincia as provincia.nombreprovincia for provincia in provincias">
                                                    <option value="">--Seleccione--</option>
                                                </select>
                                                <span ng-show="formProveedoresedit.provincia.$error.required">Seleccione una provincia.</span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="">Cantón</label>
                                                <select name="ciudad" ng-change="cargarSectores(proveedornuevo.sector.idciudad)" ng-model="proveedornuevo.sector.idciudad" id="input" class="form-control" required="required" ng-options="ciudad.idciudad as ciudad.nombreciudad for ciudad in ciudades">
                                                <option value="">--Seleccione--</option>
                                                </select>
                                                <span ng-show="formProveedoresedit.ciudad.$error.required">Seleccione una ciudad.</span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="">Sector</label>
                                                <select name="sector" ng-model="proveedornuevo.idsector" ng-options="sector.idsector as sector.nombreparroquia for sector in sectores" class="form-control" required="required">
                                                <option value="">--Seleccione--</option>
                                                </select>
                                                
                                               <span ng-show="formProveedoresedit.sector.$error.required">Seleccione un secctor.</span>
                                            </div>
                                        </div>

                                       
                                    </div>
                                    <div class="row">
                                       
                                                                              

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="">Dirección</label>
                                                <input type="text" ng-model="proveedornuevo.direccionproveedor" class="form-control" id="" required>
                                            </div>
                                        </div>
                                    </div>
                                
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                <button type="button" id='botonconfirmareditar' ng-click="confirmareditarproveedor()" class="btn btn-success">Guardar</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
            <div class="modal fade" id="modal-contactos">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header modal-header-primary">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title">Contactos del Proveedor No @{{proveedornuevo.idproveedor}}</h4>
                        </div>
                        <form action="" method="POST" role="form" name="formcontactos">

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
                        <th >Nombre</th>
                        <th >Teléfono Principal</th>
                        <th >Teléfono Secundario</th>
                        <th >Celular</th>
                        <th >Observacion</th>
                        <th >Opcion</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr  ng-repeat="contacto in contactos  | orderBy:'-idcontacto' | filter:searchcontato">

                        
                        <td >
                            <input type="text" ng-keypress="onlyCharasterAndSpace($event);" class="form-control" 
                                   ng-model="contacto.nombrecontacto" placeholder='Nombre de Contacto' required>                        
                        </td >
                        <td >
                            <input type="text" ng-minlength ="9" id="telefonoprincipal-@{{ contacto.idcontacto }}" name="telefonoprincipal-@{{ contacto.idcontacto }}" ng-keypress="onlyNumber($event)" class="form-control" 
                                   ng-model="contacto.telefonoprincipal" placeholder='Telefono Principal' required>
                                   <span class="text-danger" ng-show="formcontactos['telefonoprincipal-' + contacto.idcontacto].$error.minlength">La longitud mínima es de 9 caracteres <br></span>
                        </td>
                        <td >
                            <input type="text"ng-minlength ="9" id="telefonosecundario-@{{ contacto.idcontacto }}" name="telefonosecundario-@{{ contacto.idcontacto }}" ng-keypress="onlyNumber($event)" class="form-control" 
                                   ng-model="contacto.telefonosecundario" placeholder='Telefono Secundario'>
                                   <span class="text-danger" ng-show="formcontactos['telefonosecundario-' + contacto.idcontacto].$error.minlength">La longitud mínima es de 9 caracteres <br></span>
                        </td>
                        <td >
                            <input type="text" ng-minlength ="10" id="celular-@{{ contacto.idcontacto }}" name="celular-@{{ contacto.idcontacto }}" ng-keypress="onlyNumber($event)" class="form-control" 
                                   ng-model="contacto.celular" placeholder='Celular'> 
                                   <span class="text-danger" ng-show="formcontactos['celular-' + contacto.idcontacto].$error.minlength">La longitud mínima es de 10 caracteres <br></span>  
                        </td>
                        <td >
                            <input type="text" class="form-control" 
                                   ng-model="contacto.observacion" placeholder='Observacion'>    
                        </td>
                        <td >
                            <button ng-click="eliminarcontacto(contacto)" type="button" class="btn btn-danger"><i class="fa fa-close"></i></button>
                        </td>
                        
                    </tr>

                    </tbody>
                </table>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                <button type="button" id='botonguardarcontactos' ng-click="saveAllContactos()" class="btn btn-success">Guardar</button>
                            </div>
                        </form>

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
                            <span>@{{ message }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" tabindex="-1" role="dialog" id="modalMessage2">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header modal-header-danger">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Confirmación</h4>
                        </div>
                        <div class="modal-body">
                            <span>@{{message}}</span>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btn-cambiarestado" ng-click="confirmarCambiarEstado()" class="btn btn-danger">Aceptar</button>
                        </div>
                    </div>
                </div>
            </div>
             <div class="modal fade" tabindex="-1" role="dialog" id="modalMessage3">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header modal-header-danger">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Confirmación</h4>
                        </div>
                        <div class="modal-body">
                            <span>@{{message}}</span>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btn-eliminarcontacto" ng-click="removecontacto()" class="btn btn-danger">Eliminar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>       

        </div>
        
        


    </div>

    <!-- Load Javascript Libraries (AngularJS, JQuery, Bootstrap) -->
    <script src="{{ asset('app/lib/angular/angular.min.js') }}"></script>
    <script src="{{ asset('app/lib/angular/angular-route.min.js') }}"></script>
       <script src="{{ asset('js/jquery.min.js') }}"></script>
        <script src="{{ asset('js/bootstrap.min.js') }}"></script>
        
        <!-- AngularJS Application Scripts -->
        <script src="{{ asset('app/app.js') }}"></script>
        <script src="{{ asset('app/controllers/proveedoresController.js') }}"></script>


        <script type="text/javascript">
            function soloLetras(e){
               key = e.keyCode || e.which;
               tecla = String.fromCharCode(key).toLowerCase();
               letras = " áéíóúabcdefghijklmnñopqrstuvwxyz";
               especiales = "8-37-39-46";

               tecla_especial = false
               for(var i in especiales){
                    if(key == especiales[i]){
                        tecla_especial = true;
                        break;
                    }
                }

                if(letras.indexOf(tecla)==-1 && !tecla_especial){
                    return false;
                }
            }
            function soloNumeros(e) {
                key = e.keyCode || e.which;
                tecla = String.fromCharCode(key).toLowerCase();
                letras = " 0123456789";
                especiales = [8, 37, 39, 46];

                tecla_especial = false
                for(var i in especiales) {
                    if(key == especiales[i]) {
                        tecla_especial = true;
                        break;
                    }
                }

                if(letras.indexOf(tecla) == -1 && !tecla_especial)
                    return false;
            }
        </script>
    </body>
</html>
    


