
app.controller('proveedoresController', function($scope, $http, API_URL, Upload) {

    $scope.proveedores = [];
    $scope.proveedor_del = 0;
    $scope.idpersona = 0;
    $scope.idpersona_edit = 0;
    $scope.id = 0;
    $scope.select_cuenta = null;
    $scope.idproveedor = null;

    $scope.objectPerson = {
        idperson: 0,
        identify: ''
    };


    $scope.contactos = [];


    $scope.pageChanged = function(newPage) {
        $scope.initLoad(newPage);
    };

    $scope.initLoad = function(pageNumber){

        if ($scope.busqueda == undefined) {
            var search = null;
        } else var search = $scope.busqueda;

        var filtros = {
            search: search
        };

        $http.get(API_URL + 'proveedor/getProveedores?page=' + pageNumber + '&filter=' + JSON.stringify(filtros)).success(function(response){
            $scope.proveedores = response.data;
            $scope.totalItems = response.total;
            console.log(response);
            console.log($scope.proveedores);
            console.log($scope.totalItems);
        });

    };

    $scope.showDataPurchase = function (object) {
        if (object != undefined && object.originalObject != undefined) {

            $scope.documentoidentidadempleado = object.originalObject.numdocidentific;
            $scope.razonsocial = object.originalObject.razonsocial;
            $scope.direccion = object.originalObject.direccion;
            $scope.celular = object.originalObject.celphone;
            $scope.correo = object.originalObject.email;
            $scope.tipoidentificacion = object.originalObject.idtipoidentificacion;
            $scope.idpersona = object.originalObject.idpersona;

            $scope.objectPerson = {
                idperson: object.originalObject.idpersona,
                identify: object.originalObject.numdocidentific
            };
        }
    };

    $scope.toggle = function(modalstate, item) {
        $scope.modalstate = modalstate;
        switch (modalstate) {
            case 'add':

                $http.get(API_URL + 'proveedor/getTipoIdentificacion').success(function(response){
                    var longitud = response.length;
                    var array_temp = [{label: '-- Seleccione --', id: ''}];
                    for(var i = 0; i < longitud; i++){
                        array_temp.push({label: response[i].nameidentificacion, id: response[i].idtipoidentificacion})
                    }
                    $scope.idtipoidentificacion = array_temp;
                    $scope.tipoidentificacion = '';
                });

                $http.get(API_URL + 'proveedor/getProvincias').success(function(response){
                    var longitud = response.length;
                    var array_temp = [{label: '-- Seleccione --', id: ''}];
                    for(var i = 0; i < longitud; i++){
                        array_temp.push({label: response[i].nameprovincia, id: response[i].idprovincia})
                    }
                    $scope.provincias = array_temp;
                    $scope.provincia = '';
                });

                $http.get(API_URL + 'proveedor/getImpuestoIVA').success(function(response){
                    var longitud = response.length;
                    var array_temp = [{label: '-- Seleccione --', id: ''}];
                    for(var i = 0; i < longitud; i++){
                        array_temp.push({label: response[i].nametipoimpuestoiva, id: response[i].idtipoimpuestoiva})
                    }
                    $scope.imp_iva = array_temp;
                    $scope.iva = '';

                    $http.get(API_URL + 'proveedor/getTipoEmpresa').success(function(response){

                        var longitud = response.length;
                        var array_temp = [{label: '-- Seleccione --', id: ''}];
                        for(var i = 0; i < longitud; i++){
                            array_temp.push({label: response[i].nametipoempresa, id: response[i].idtipoempresa})
                        }
                        $scope.listtipoempresaats = array_temp;
                        $scope.tipoempresaats = '';

                        $http.get(API_URL + 'proveedor/getTipoParte').success(function(response){

                            var longitud = response.length;
                            var array_temp = [];
                            for(var i = 0; i < longitud; i++){
                                array_temp.push({label: response[i].nameparte, id: response[i].idparte})
                            }
                            $scope.listtipoparte = array_temp;
                            $scope.tipoparte = array_temp[0].id;

                            $http.get(API_URL + 'proveedor/getIVADefault').success(function(response){

                                var longitud = response.length;

                                for (var i = 0; i < longitud; i++) {
                                    if (response[i].optionname === 'SRI_IVA_DEFAULT') {

                                        if (response[i].optionvalue !== null && response[i].optionvalue !== '') {
                                            $scope.iva = parseInt(response[i].optionvalue);
                                        }

                                    } else if (response[i].optionname === 'CONT_PROV_DEFAULT') {

                                        $scope.cuenta_employee = response[i].concepto;

                                        $scope.select_cuenta = {
                                            idplancuenta: response[i].optionvalue,
                                            concepto: response[i].concepto
                                        };

                                    }
                                }



                                $scope.documentoidentidadempleado = '';
                                $('#documentoidentidadempleado').val('');
                                $scope.$broadcast('angucomplete-alt:changeInput', 'documentoidentidadempleado', '');
                                $scope.$broadcast('angucomplete-alt:clearInput', 'documentoidentidadempleado');
                                $scope.razonsocial = '';
                                $scope.telefonoprincipal = '';
                                $scope.celular = '';
                                $scope.direccion = '';
                                $scope.correo = '';

                                $scope.fechaingreso = fecha();

                                /*$scope.cuenta_employee = '';
                                $scope.select_cuenta = null;*/

                                $scope.form_title = "Ingresar Nuevo Proveedor";

                                var array_temp = [{label: '-- Seleccione --', id: ''}];
                                $scope.cantones = array_temp;
                                $scope.canton = '';
                                $scope.parroquias = array_temp;
                                $scope.parroquia = '';

                                $('#modalAction').modal('show');

                            });

                        });

                    });

                });

                break;
            case 'edit':
                $scope.form_title = "Editar Proveedor";
                $scope.id = item.idproveedor;

                $http.get(API_URL + 'proveedor/getProvincias').success(function(response){
                    var longitud = response.length;
                    var array_temp = [{label: '-- Seleccione --', id: ''}];
                    for(var i = 0; i < longitud; i++){
                        array_temp.push({label: response[i].nameprovincia, id: response[i].idprovincia})
                    }
                    $scope.provincias = array_temp;
                    $scope.provincia = item.idprovincia;

                    var array_cantones = [{label: '-- Seleccione --', id: ''}];
                    $http.get(API_URL + 'proveedor/getCantones/' + item.idprovincia).success(function(response){
                        var longitud = response.length;
                        for(var i = 0; i < longitud; i++){
                            array_cantones.push({label: response[i].namecanton, id: response[i].idcanton})
                        }
                        $scope.cantones = array_cantones;
                        $scope.canton = item.idcanton;

                        var array_parroquias = [{label: '-- Seleccione --', id: ''}];
                        $http.get(API_URL + 'proveedor/getParroquias/' + item.idcanton).success(function(response0){
                            var longitud0 = response0.length;
                            for(var i = 0; i < longitud0; i++){
                                array_parroquias.push({label: response0[i].nameparroquia, id: response0[i].idparroquia})
                            }
                            $scope.parroquias = array_parroquias;
                            $scope.parroquia = item.idparroquia;
                        });

                    });

                });

                $http.get(API_URL + 'proveedor/getTipoIdentificacion').success(function(response){

                    var longitud = response.length;
                    var array_temp = [{label: '-- Seleccione --', id: ''}];
                    for(var i = 0; i < longitud; i++){
                        array_temp.push({label: response[i].nameidentificacion, id: response[i].idtipoidentificacion})
                    }
                    $scope.idtipoidentificacion = array_temp;
                    $scope.tipoidentificacion = '';

                    $http.get(API_URL + 'proveedor/getImpuestoIVA').success(function(response){
                        var longitud = response.length;
                        var array_temp = [{label: '-- Seleccione --', id: ''}];
                        for(var i = 0; i < longitud; i++){
                            array_temp.push({label: response[i].nametipoimpuestoiva, id: response[i].idtipoimpuestoiva})
                        }
                        $scope.imp_iva = array_temp;
                        $scope.iva = '';



                        $http.get(API_URL + 'proveedor/getTipoEmpresa').success(function(response){

                            var longitud = response.length;
                            var array_temp = [{label: '-- Seleccione --', id: ''}];
                            for(var i = 0; i < longitud; i++){
                                array_temp.push({label: response[i].nametipoempresa, id: response[i].idtipoempresa})
                            }
                            $scope.listtipoempresaats = array_temp;
                            $scope.tipoempresaats = item.idtipoempresa;

                            $http.get(API_URL + 'proveedor/getTipoParte').success(function(response){

                                var longitud = response.length;
                                var array_temp = [];
                                for(var i = 0; i < longitud; i++){
                                    array_temp.push({label: response[i].nameparte, id: response[i].idparte})
                                }
                                $scope.listtipoparte = array_temp;
                                $scope.tipoparte = item.idparte;


                                $scope.fechaingreso = convertDatetoDB(item.fechaingreso, true);
                                $scope.documentoidentidadempleado = item.numdocidentific;

                                $scope.$broadcast('angucomplete-alt:changeInput', 'documentoidentidadempleado', item.numdocidentific);

                                $scope.razonsocial = item.razonsocial;
                                $scope.telefonoprincipal = item.telefonoprincipal;
                                $scope.celular = item.celphone;
                                $scope.direccion = item.direccion;
                                $scope.correo = item.email;
                                $scope.salario = item.salario;

                                $scope.idpersona = item.idpersona;
                                $scope.idpersona_edit = item.idpersona;

                                $scope.cuenta_employee = item.concepto;

                                $scope.tipoidentificacion = item.idtipoidentificacion;

                                $scope.iva = item.idtipoimpuestoiva;

                                var objectPlan = {
                                    idplancuenta: item.idplancuenta,
                                    concepto: item.concepto
                                };

                                $scope.select_cuenta = objectPlan;

                                $('#modalAction').modal('show');

                            });

                        });

                    });

                });


                break;
            case 'info':

                $http.get(API_URL + 'proveedor/getContactos/' + item.idproveedor).success(function(response){
                    $scope.contactos = [];

                    var longitud = response.length;

                    for(var i = 0; i < longitud; i++){
                        var object = {
                            nombrecontacto: response[i].namecontacto,
                            idcontacto: response[i].idcontactoproveedor,
                            telefonoprincipalcont: response[i].telefonoprincipal,
                            telefonosecundario: response[i].telefonosecundario,
                            celular: response[i].celular,
                            observacion: response[i].observacion,
                            idproveedor: response[i].idproveedor
                        };

                        $scope.contactos.push(object);
                    }

                    $scope.razonsocial_contacto = item.razonsocial;
                    $scope.idproveedor = item.idproveedor;

                    $('#modalContactos').modal('show');
                });

                break;
            default:
                break;
        }
    };

    $scope.getCantones = function () {
        var idprovincia = $scope.provincia;
        var array_temp = [{label: '-- Seleccione --', id: ''}];

        if (idprovincia != '' && idprovincia != undefined) {
            $http.get(API_URL + 'proveedor/getCantones/' + idprovincia).success(function(response){
                var longitud = response.length;
                for(var i = 0; i < longitud; i++){
                    array_temp.push({label: response[i].namecanton, id: response[i].idcanton})
                }

            });
        }

        $scope.cantones = array_temp;
        $scope.canton = '';
        $scope.parroquias = array_temp;
        $scope.parroquia = '';
    };

    $scope.getParroquias = function () {
        var idcanton = $scope.canton;
        var array_temp = [{label: '-- Seleccione --', id: ''}];

        if (idcanton != '' && idcanton != undefined) {
            $http.get(API_URL + 'proveedor/getParroquias/' + idcanton).success(function(response){
                var longitud = response.length;
                for(var i = 0; i < longitud; i++){
                    array_temp.push({label: response[i].nameparroquia, id: response[i].idparroquia})
                }

            });
        }

        $scope.parroquias = array_temp;
        $scope.parroquia = '';
    };

    $scope.focusOut = function () {

        if ($scope.documentoidentidadempleado !== null && $scope.documentoidentidadempleado !== '' && $scope.documentoidentidadempleado !== undefined) {

            $http.get(API_URL + 'proveedor/searchDuplicate/' + $scope.documentoidentidadempleado).success(function(response){

                if (response.success === false) {

                    $http.get(API_URL + 'empleado/getPersonaByIdentify/' + $scope.documentoidentidadempleado).success(function(response){

                        var longitud = response.length;

                        if (longitud > 0) {
                            $scope.idpersona = response[0].idpersona;
                        } else {
                            $scope.idpersona = 0;
                        }

                    });

                    $scope.$broadcast('angucomplete-alt:changeInput', 'documentoidentidadempleado', $scope.documentoidentidadempleado);

                } else {

                    $scope.message_error = 'Ya existe un proveedor insertado con el mismo Número de Identificación';
                    $('#modalMessageError').modal('show');

                }

            });


        }

    };

    $scope.inputChanged = function (str) {
        $scope.documentoidentidadempleado = str;
    };

    $scope.save = function() {
        var url = API_URL + 'proveedor';

        var fechaingreso = $('#fechaingreso').val();

        var identify = ($scope.documentoidentidadempleado).trim();

        var data = {
            fechaingreso: convertDatetoDB(fechaingreso),
            telefonoprincipal: $scope.telefonoprincipal,
            celular: $scope.celular,
            direccion: $scope.direccion,
            correo: $scope.correo,
            documentoidentidadempleado: identify,
            razonsocial: $scope.razonsocial,
            idpersona:  $scope.idpersona,
            idpersona_edit:  $scope.idpersona_edit,
            tipoidentificacion: $scope.tipoidentificacion,
            cuentacontable: $scope.select_cuenta.idplancuenta,
            impuesto_iva: $scope.iva,
            parroquia: $scope.parroquia,
            idtipoempresa: $scope.tipoempresaats,
            idparte: $scope.tipoparte,
        };

        console.log(data);

        if ($scope.modalstate == 'add') {
            $http.post(url, data ).success(function (response) {
                if (response.success == true) {
                    $scope.initLoad(1);
                    $scope.message = 'Se guardó correctamente la información del Proveedor...';
                    $('#modalAction').modal('hide');
                    $('#modalMessage').modal('show');
                    $scope.hideModalMessage();
                }
                else {

                    if (response.type_error_exists != undefined) {
                        $scope.message_error = 'Ya existe un proveedor insertado con ese mismo Número de Identificación';
                    } else {
                        $('#modalAction').modal('hide');
                        $scope.message_error = 'Ha ocurrido un error..';

                    }

                    $('#modalMessageError').modal('show');

                }
            });
        } else {
            $http.put(url + '/' + $scope.id, data ).success(function (response) {
                if (response.success == true) {
                    $scope.idpersona = 0;
                    $scope.idpersona_edit = 0;
                    $scope.id = 0;
                    $scope.initLoad(1);
                    $scope.message = 'Se editó correctamente la información del Proveedor...';
                    $('#modalAction').modal('hide');
                    $('#modalMessage').modal('show');
                    $scope.hideModalMessage();
                }
                else {
                    $('#modalAction').modal('hide');
                    $scope.message_error = 'Ha ocurrido un error..';
                    $('#modalMessageError').modal('show');
                }
            }).error(function (res) {

            });
        }

    };

    $scope.showModalConfirm = function(item){
        $scope.proveedor_del = item.idproveedor;
        $scope.empleado_seleccionado = item.razonsocial;
        $('#modalConfirmDelete').modal('show');
    };

    $scope.destroy = function(){
        $http.delete(API_URL + 'proveedor/' + $scope.proveedor_del).success(function(response) {

            $('#modalConfirmDelete').modal('hide');

            if (response.success == true) {
                $scope.initLoad(1);
                $scope.proveedor_del = 0;
                $scope.message = 'Se eliminó correctamente el Proveedor seleccionado';
                $('#modalMessage').modal('show');
                $scope.hideModalMessage();
            } else {

                if (response.exists != undefined) {
                    $scope.message_error = 'No se puede eliminar el proveedor seleccionado, ya que esa siendo usado en el sistema...';
                } else {
                    $scope.message_error = 'Ha ocurrido un error..';
                }

                $('#modalMessageError').modal('show');
            }


        });
    };

    $scope.showPlanCuenta = function () {

        $http.get(API_URL + 'empleado/getPlanCuenta').success(function(response){
            $scope.cuentas = response;
            $('#modalPlanCuenta').modal('show');
        });

    };

    $scope.orden_plan_cuenta = function(orden){
        var aux_orden=orden.split(".");
        var aux_numero_orden="";
        var aux_numero_completar="";
        var tam=aux_orden.length;
        if(tam>0){
            for(var x=0;x<tam;x++){
                if(x<3){
                    aux_numero_orden+=aux_orden[x];
                }else if(x>=3){
                    if(x==3){
                        aux_numero_completar=aux_orden[x];
                        if( aux_numero_completar.length==1){
                            aux_numero_completar="0"+aux_numero_completar;
                        }
                        aux_numero_orden+=aux_numero_completar;
                    }else if(x>3){
                        aux_numero_orden+=aux_orden[x];
                    }

                }
            }
        }else{
            aux_numero_orden=orden;
        }
        return aux_numero_orden;
    };

    $scope.selectCuenta = function () {
        var selected = $scope.select_cuenta;

        $scope.cuenta_employee = selected.concepto;

        $('#modalPlanCuenta').modal('hide');
    };

    $scope.click_radio = function (item) {
        $scope.select_cuenta = item;
    };

    /*
     *   ACCIONES DE CONTACTO (INICIO)
     */

    $scope.addcontacto = function  () {

        var object = {
            nombrecontacto: '',
            idcontacto: '',
            telefonoprincipalcont: '',
            telefonosecundario: '',
            celular: '',
            observacion: '',
            idproveedor: $scope.idproveedor
        };

        $scope.contactos.push(object);
    };

    app.directive('focusMe', function () {
        return {
            link: function(scope, element, attrs) {
                scope.$watch(attrs.focusMe, function(value) {
                    if(value === true) {
                        element[0].focus();
                        element[0].select();
                    }
                });
            }
        };
    });

    $scope.saveAllContactos = function() {

        var data = {
            contactos: $scope.contactos
        };

        $http.post(API_URL + 'proveedor/storeContactos', data ).success(function (response) {
            if (response.success == true) {

                $scope.message = 'Se guardó correctamente la información de los Contactos del Proveedor...';
                //$('#modalContactos').modal('hide');
                $('#modalMessage').modal('show');
                $scope.hideModalMessage();
            }
            else {
                $('#modalContactos').modal('hide');
                $scope.message_error = 'Ha ocurrido un error..';
                $('#modalMessageError').modal('show');
            }
        });
    };

    $scope.eliminarContacto = function (contacto) {

        $scope.idcontacto = contacto.idcontacto;
        $scope.message = 'Está seguro que desea eliminar el Contacto: ' + contacto.nombrecontacto;

        $('#modalMessageDeleteContacto').modal('show');
    };

    $scope.destroyContacto = function () {

        if($scope.idcontacto == 0) {

            $http.get(API_URL + 'proveedor/getContactos/' + $scope.idproveedor).success(function(response){
                $scope.contactos = [];

                var longitud = response.length;
                for(var i = 0; i < longitud; i++){
                    var object = {
                        nombrecontacto: response[i].namecontacto,
                        idcontacto: response[i].idcontactoproveedor,
                        telefonoprincipalcont: response[i].telefonoprincipal,
                        telefonosecundario: response[i].telefonosecundario,
                        celular: response[i].celular,
                        observacion: response[i].observacion,
                        idproveedor: response[i].idproveedor
                    };

                    $scope.contactos.push(object);
                }
                $('#modalMessageDeleteContacto').modal('hide');
            });

        } else {

            $http.delete(API_URL + 'proveedor/destroyContacto/' + $scope.idcontacto).success(function (data) {
                $http.get(API_URL + 'proveedor/getContactos/' + $scope.idproveedor).success(function(response){
                    $scope.contactos = [];

                    var longitud = response.length;
                    for(var i = 0; i < longitud; i++){
                        var object = {
                            nombrecontacto: response[i].namecontacto,
                            idcontacto: response[i].idcontactoproveedor,
                            telefonoprincipalcont: response[i].telefonoprincipal,
                            telefonosecundario: response[i].telefonosecundario,
                            celular: response[i].celular,
                            observacion: response[i].observacion,
                            idproveedor: response[i].idproveedor
                        };

                        $scope.contactos.push(object);
                    }

                    $('#modalMessageDeleteContacto').modal('hide');
                    $scope.message = 'Contacto eliminado satisfactoriamente';
                    $scope.idcontacto = 0;
                    $('#modalMessage').modal('show');
                    setTimeout("$('#modalMessage').modal('hide')",3000);

                });
            }).error(function (res) {

            });
        }

    };

    /*
     *   ACCIONES DE CONTACTO (FIN)
     */

    $scope.hideModalMessage = function () {
        setTimeout("$('#modalMessage').modal('hide')", 3000);
    };

    $scope.onlyNumber = function ($event, length, field) {

        if (length != undefined) {
            var valor = $('#' + field).val();
            if (valor.length == length) $event.preventDefault();
        }

        var k = $event.keyCode;
        if (k == 8 || k == 0) return true;
        var patron = /\d/;
        var n = String.fromCharCode(k);

        if (n == ".") {
            return true;
        } else {

            if(patron.test(n) == false){
                $event.preventDefault();
            }
            else return true;
        }
    };

    $scope.initLoad(1);

    $('.datepicker').datetimepicker({
        locale: 'es',
        format: 'DD/MM/YYYY'
    });

    $(function () {
        $('[data-toggle="tooltip"]').tooltip();
    })

});

$(function () {
    $('[data-toggle="tooltip"]').tooltip();
});

function fecha(){
    var f = new Date();
    var fecha = "";
    var dd = f.getDate();
    if (dd < 10) dd = '0' + dd;
    var mm = f.getMonth() + 1;
    if (mm < 10) mm = '0' + mm;
    var yyyy = f.getFullYear();
    fecha = dd + "\/" + mm + "\/" + yyyy;

    return fecha;
}

function convertDatetoDB(now, revert){
    if (revert == undefined){
        var t = now.split('/');
        return t[2] + '-' + t[1] + '-' + t[0];
    } else {
        var t = now.split('-');
        return t[2] + '/' + t[1] + '/' + t[0];
    }
}
