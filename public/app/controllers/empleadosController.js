
app.controller('empleadosController', function($scope, $http, API_URL, Upload) {

    $scope.empleados = [];
    $scope.empleado_del = 0;
    $scope.idpersona = 0;
    $scope.idpersona_edit = 0;
    $scope.id = 0;

    $scope.select_cuenta = null;

    $scope.objectPerson = {
        idperson: 0,
        identify: ''
    };

    $scope.pageChanged = function(newPage) {
        $scope.initLoad(newPage);
    };

    $scope.initLoad = function(pageNumber, verifyPosition){

        if (verifyPosition != undefined){
            $scope.searchPosition();
        }

        if ($scope.busqueda == undefined) {
            var search = null;
        } else var search = $scope.busqueda;

        if ($scope.searchCargo == undefined) {
            var cargo = null;
        } else var cargo = $scope.searchCargo;

        var filtros = {
            search: search,
            cargo: cargo
        };

        $http.get(API_URL + 'empleado/getEmployees?page=' + pageNumber + '&filter=' + JSON.stringify(filtros)).success(function(response){
            $scope.empleados = response.data;
            $scope.totalItems = response.total;
        });

    };

    $scope.searchListCargos = function(){
        $http.get(API_URL + 'empleado/getAllPositions').success(function(response){
            var longitud = response.length;
            var array_temp = [{label: '-- Seleccione Cargo --', id: ''}];
            for(var i = 0; i < longitud; i++){
                array_temp.push({label: response[i].namecargo, id: response[i].idcargo})
            }
            $scope.search_cargos = array_temp;
            $scope.searchCargo = '';
        });
    };

    $scope.listCargosForModal = function(idcargo){

        var iddepartamento = $scope.departamento;

        $http.get(API_URL + 'empleado/getCargos/' + iddepartamento).success(function(response){
            var longitud = response.length;
            var array_temp = [{label: '-- Seleccione --', id: ''}];
            for(var i = 0; i < longitud; i++){
                array_temp.push({label: response[i].namecargo, id: response[i].idcargo})
            }
            $scope.idcargos = array_temp;

            if (idcargo != undefined) {
                $scope.idcargo = idcargo;
            } else {
                $scope.idcargo = '';
            }

        });
    };

    $scope.searchPosition = function(){
        $http.get(API_URL + 'empleado/getAllPositions').success(function(response){
            var longitud = response.length;
            if(longitud == 0){
                $('#btnAgregar').prop('disabled', true);
                $('#message-positions').show();
            } else {
                $('#btnAgregar').prop('disabled', false);
                $('#message-positions').hide();
            }
        });
    };

    $scope.showDataPurchase = function (object) {

        if (object != undefined && object.originalObject != undefined) {

            $scope.documentoidentidadempleado = object.originalObject.numdocidentific;
            $scope.apellido = object.originalObject.lastnamepersona;
            $scope.nombre = object.originalObject.namepersona;
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

                $http.get(API_URL + 'empleado/getDepartamentos').success(function(response){
                    var longitud = response.length;
                    var array_temp = [{label: '-- Seleccione --', id: ''}];
                    for(var i = 0; i < longitud; i++){
                        array_temp.push({label: response[i].namedepartamento, id: response[i].iddepartamento})
                    }
                    $scope.iddepartamentos = array_temp;
                    $scope.departamento = '';

                    $scope.idcargos = [{label: '-- Seleccione --', id: ''}];
                    $scope.idcargo = '';
                });

                $http.get(API_URL + 'empleado/getTipoIdentificacion').success(function(response){
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
                    $scope.apellido = '';
                    $scope.nombre = '';
                    $scope.telefonoprincipal = '';
                    $scope.telefonosecundario = '';
                    $scope.celular = '';
                    $scope.direccion = '';
                    $scope.correo = '';
                    $scope.salario = '';
                    $scope.file = '';
                    $scope.fechaingreso = fecha();

                    $scope.cuenta_employee = '';
                    $scope.select_cuenta = null;

                    $scope.form_title = "Ingresar Nuevo Colaborador";

                    $scope.url_foto = 'img/empleado.png';

                    $('#modalAction').modal('show');

                });

                break;
            case 'edit':
                $scope.form_title = "Editar Colaborador";
                $scope.id = item.idempleado;

                $http.get(API_URL + 'empleado/getDepartamentos').success(function(response){
                    var longitud = response.length;
                    var array_temp = [{label: '-- Seleccione --', id: ''}];
                    for(var i = 0; i < longitud; i++){
                        array_temp.push({label: response[i].namedepartamento, id: response[i].iddepartamento})
                    }
                    $scope.iddepartamentos = array_temp;
                    //$scope.departamento = '';

                    $scope.departamento = item.iddepartamento;

                    $scope.listCargosForModal(item.idcargo);

                });

                $http.get(API_URL + 'empleado/getTipoIdentificacion').success(function(response){
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


                    $scope.apellido = item.lastnamepersona;
                    $scope.nombre = item.namepersona;
                    $scope.telefonoprincipal = item.telefprincipaldomicilio;
                    $scope.telefonosecundario = item.telefsecundariodomicilio;
                    $scope.celular = item.celphone;
                    $scope.direccion = item.direccion;
                    $scope.correo = item.email;
                    $scope.salario = item.salario;

                    $scope.idpersona = item.idpersona;
                    $scope.idpersona_edit = item.idpersona;
                    console.log(item);

                    if (item.rutafoto != null && item.rutafoto != ''){
                        $scope.url_foto = API_URL+item.rutafoto;

                    } else {
                        $scope.url_foto = 'img/empleado.png';
                    }



                    $scope.cuenta_employee = item.concepto;

                    $scope.tipoidentificacion = item.idtipoidentificacion;

                    var objectPlan = {
                        idplancuenta: item.idplancuenta,
                        concepto: item.concepto
                    };

                    $scope.select_cuenta = objectPlan;

                    $('#modalAction').modal('show');

                });


                break;

            case 'info':

                $scope.name_employee = item.complete_name;
                $scope.cargo_employee = item.namecargo;
                $scope.date_registry_employee = convertDatetoDB(item.fechaingreso, true);
                //$scope.date_registry_employee = response[0].fechaingreso;
                $scope.phones_employee = item.telefprincipaldomicilio + '/' + item.telefsecundariodomicilio;
                $scope.cel_employee = item.celphone;
                $scope.address_employee = item.direccion;
                $scope.email_employee = item.email;
                $scope.salario_employee = item.salario;



                if (item.rutafoto != null && item.rutafoto != ''){
                    $scope.url_foto = API_URL+item.rutafoto;
                } else {
                    $scope.url_foto = 'img/empleado.png';
                }

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
        var url = API_URL + "empleado";
        var method = 'POST';

        if ($scope.modalstate == 'edit'){
            url += "/updateEmpleado/" + $scope.id;
        }

        var fechaingreso = $('#fechaingreso').val();

        var data ={
            fechaingreso: convertDatetoDB(fechaingreso),
            idcargo: $scope.idcargo,
            apellidos: $scope.apellido,
            nombres: $scope.nombre,
            telefonoprincipaldomicilio: $scope.telefonoprincipal,
            telefonosecundariodomicilio: $scope.telefonosecundario,
            celular: $scope.celular,
            direcciondomicilio: $scope.direccion,
            correo: $scope.correo,
            salario: $scope.salario,
            file: $scope.file,
            documentoidentidadempleado: $scope.documentoidentidadempleado,

            idpersona:  $scope.idpersona,
            idpersona_edit: $scope.idpersona_edit,

            departamento: $scope.departamento,
            tipoidentificacion: $scope.tipoidentificacion,
            cuentacontable: $scope.select_cuenta.idplancuenta
        };

        console.log(data);

        Upload.upload({
            url: url,
            method: method,
            data: data
        }).success(function(data, status, headers, config) {
            if (data.success == true) {
                $scope.idpersona = 0;

                $scope.objectPerson = {
                    idperson: 0,
                    identify: ''
                };

                $scope.initLoad();
                $scope.message = 'Se guardó correctamente la información del Colaborador...';
                $('#modalAction').modal('hide');
                $('#modalMessage').modal('show');
                $scope.hideModalMessage();
            }
            else {

                if (data.type_error_exists != undefined) {
                    $scope.message_error = 'Ya existe un empleado(colaborador) insertado con ese mismo Número de Identificación';
                } else {
                    $('#modalAction').modal('hide');
                    $scope.message_error = 'Ha ocurrido un error..';
                }

                $('#modalMessageError').modal('show');


            }
        });

    };

    $scope.showModalConfirm = function(item){
        $scope.empleado_del = item.idempleado;
        $scope.empleado_seleccionado = item.namepersona + ' ' + item.lastnamepersona;
        $('#modalConfirmDelete').modal('show');
    };

    $scope.destroy = function(){
        $http.delete(API_URL + 'empleado/' + $scope.empleado_del).success(function(response) {

            $('#modalConfirmDelete').modal('hide');

            if (response.success == true) {
                $scope.initLoad(1);

                $scope.empleado_del = 0;
                $scope.message = 'Se eliminó correctamente el Colaborador seleccionado';
                $('#modalMessage').modal('show');
                $scope.hideModalMessage();
            } else {

                if (response.exists != undefined) {
                    $scope.message_error = 'No se puede eliminar el empleado(colaborador) seleccionado, ya que está siendo usado en el sistema...';
                } else {
                    $scope.message_error = 'Ha ocurrido un error..';
                }

                $('#modalMessageError').modal('show');
            }


        });
    };

    $scope.showPlanCuenta = function () {

        $http.get(API_URL + 'empleado/getPlanCuenta').success(function(response){
            console.log(response);
            $scope.cuentas = response;
            $('#modalPlanCuenta').modal('show');
        });

    };

    $scope.selectCuenta = function () {
        var selected = $scope.select_cuenta;

        $scope.cuenta_employee = selected.concepto;

        $('#modalPlanCuenta').modal('hide');
    };

    $scope.click_radio = function (item) {
        $scope.select_cuenta = item;
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

    $scope.initLoad(1, true);

    $scope.searchListCargos();

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
