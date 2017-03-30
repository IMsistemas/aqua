
app.controller('transportistaController', function($scope, $http, API_URL, Upload) {

    $scope.transportistas = [];
    $scope.transportista_del = 0;
    $scope.idpersona = 0;
    $scope.id = 0;

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

        $http.get(API_URL + 'transportista/getTransportista?page=' + pageNumber + '&filter=' + JSON.stringify(filtros)).success(function(response){
            $scope.transportistas = response.data;
            $scope.totalItems = response.total;
        });

    };

    $scope.showDataPurchase = function (object) {

        if (object != undefined && object.originalObject != undefined) {

            $scope.documentoidentidadempleado = object.originalObject.numdocidentific;
            $scope.razonsocial = object.originalObject.razonsocial;
            $scope.celular = object.originalObject.celphone;
            $scope.correo = object.originalObject.email;
            $scope.tipoidentificacion = object.originalObject.idtipoidentificacion;
            $scope.direccion = object.originalObject.direccion;
            $scope.telefonoprincipal = object.originalObject.telefonoprincipal;
            $scope.idpersona = object.originalObject.idpersona;

        }

    };

    $scope.toggle = function(modalstate, item) {
        $scope.modalstate = modalstate;
        switch (modalstate) {
            case 'add':

                $http.get(API_URL + 'transportista/getTipoIdentificacion').success(function(response){
                    var longitud = response.length;
                    var array_temp = [{label: '-- Seleccione --', id: ''}];
                    for(var i = 0; i < longitud; i++){
                        array_temp.push({label: response[i].nameidentificacion, id: response[i].idtipoidentificacion})
                    }
                    $scope.idtipoidentificacion = array_temp;
                    $scope.tipoidentificacion = '';

                    $scope.documentoidentidadempleado = '';
                    $('#documentoidentidadempleado').val('');
                    $scope.$broadcast('angucomplete-alt:changeInput', 'documentoidentidadempleado', ' ');
                    $scope.$broadcast('angucomplete-alt:clearInput', 'documentoidentidadempleado');
                    $scope.razonsocial = '';
                    $scope.placa = '';
                    $scope.celular = '';
                    $scope.correo = '';
                    $scope.fechaingreso = fecha();
                    $scope.direccion = '';
                    $scope.telefonoprincipal = '';

                    $scope.form_title = "Ingresar Nuevo Transportista";

                    $scope.idpersona = 0;

                    $('#modalAction').modal('show');

                });

                break;
            case 'edit':
                $scope.form_title = "Editar Transportista";
                $scope.id = item.idempleado;

                $http.get(API_URL + 'transportista/getTipoIdentificacion').success(function(response){
                    var longitud = response.length;
                    var array_temp = [{label: '-- Seleccione --', id: ''}];
                    for(var i = 0; i < longitud; i++){
                        array_temp.push({label: response[i].nameidentificacion, id: response[i].idtipoidentificacion})
                    }
                    $scope.idtipoidentificacion = array_temp;
                    $scope.tipoidentificacion = '';

                    $scope.fechaingreso = convertDatetoDB(item.fechaingreso, true);
                    $scope.documentoidentidadempleado = item.numdocidentific;
                    $scope.$broadcast('angucomplete-alt:changeInput', 'documentoidentidadempleado', item.numdocidentific);

                    $scope.razonsocial = item.razonsocial;
                    $scope.telefonoprincipal = item.telefonoprincipal;
                    $scope.celular = item.celphone;
                    $scope.direccion = item.direccion;
                    $scope.correo = item.email;
                    $scope.placa = item.placa;
                    $scope.idpersona = item.idpersona;
                    $scope.tipoidentificacion = item.idtipoidentificacion;

                    $scope.id = item.idtransportista;


                    $('#modalAction').modal('show');

                });


                break;

            case 'info':

                $scope.razonsocial_transp = item.razonsocial;
                $scope.date_registry_transp = convertDatetoDB(item.fechaingreso, true);
                $scope.cel_transp = item.celphone;
                $scope.email_transp = item.email;
                $scope.placa_transp = item.placa;
                $scope.address_transp = item.direccion;
                $scope.phones_transp = item.telefonoprincipal;

                $('#modalInfoEmpleado').modal('show');

                break;

            default:
                break;
        }
    };

    $scope.focusOut = function () {

        if ($scope.documentoidentidadempleado != null && $scope.documentoidentidadempleado != '' && $scope.documentoidentidadempleado != undefined) {
            $http.get(API_URL + 'empleado/getPersonaByIdentify/' + $scope.documentoidentidadempleado).success(function(response){

                var longitud = response.length;

                if (longitud > 0) {
                    $scope.idpersona = response[0].idpersona;
                } else {
                    $scope.idpersona = 0;
                }

            });

            $scope.$broadcast('angucomplete-alt:changeInput', 'documentoidentidadempleado', $scope.documentoidentidadempleado);
        }

    };

    $scope.inputChanged = function (str) {
        $scope.documentoidentidadempleado = str;
    };

    $scope.save = function() {
        var url = API_URL + 'transportista';

        var fechaingreso = $('#fechaingreso').val();

        var data ={
            fechaingreso: convertDatetoDB(fechaingreso),
            celular: $scope.celular,
            correo: $scope.correo,
            tipoidentificacion: $scope.tipoidentificacion,
            documentoidentidadempleado: $scope.documentoidentidadempleado,
            idpersona:  $scope.idpersona,
            placa: $scope.placa,
            direccion: $scope.direccion,
            telefonoprincipal: $scope.telefonoprincipal,
            razonsocial: $scope.razonsocial
        };

        console.log(data);

        if ($scope.modalstate == 'add') {
            $http.post(url, data ).success(function (response) {
                if (response.success == true) {
                    $scope.initLoad(1);
                    $scope.message = 'Se guardó correctamente la información del Transportista...';
                    $('#modalAction').modal('hide');
                    $('#modalMessage').modal('show');
                    $scope.hideModalMessage();
                }
                else {
                    $('#modalAction').modal('hide');
                    $scope.message_error = 'Ha ocurrido un error..';
                    $('#modalMessageError').modal('show');
                }
            });
        } else {
            $http.put(url + '/' + $scope.id, data ).success(function (response) {
                if (response.success == true) {
                    $scope.idpersona = 0;
                    $scope.id = 0;
                    $scope.initLoad(1);
                    $scope.message = 'Se editó correctamente la información del Transportista...';
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
        $scope.transportista_del = item.idtransportista;
        $scope.empleado_seleccionado = item.razonsocial;
        $('#modalConfirmDelete').modal('show');
    };

    $scope.destroy = function(){
        $http.delete(API_URL + 'transportista/' + $scope.transportista_del).success(function(response) {
            $scope.initLoad(1);
            $('#modalConfirmDelete').modal('hide');
            $scope.transportista_del = 0;
            $scope.message = 'Se eliminó correctamente el Transportista seleccionado...';
            $('#modalMessage').modal('show');
            $scope.hideModalMessage();
        });
    };

    $scope.showPlanCuenta = function () {

        $http.get(API_URL + 'empleado/getPlanCuenta').success(function(response){
            console.log(response);
            $scope.cuentas = response;
            $('#modalPlanCuenta').modal('show');
        });

    };

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
