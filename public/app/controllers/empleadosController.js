
app.controller('empleadosController', function($scope, $http, API_URL, Upload) {

    $scope.empleados = [];
    $scope.empleado_del = 0;
    $scope.id = 0;

    $scope.initLoad = function(verifyPosition){

        if (verifyPosition != undefined){
            $scope.searchPosition();
        }

        $http.get(API_URL + 'empleado/getEmployees').success(function(response){
            var longitud = response.length;
            for (var i = 0; i < longitud; i++) {
                var complete_name = {
                    value: response[i].nombres + ' ' + response[i].apellidos,
                    writable: true,
                    enumerable: true,
                    configurable: true
                };
                Object.defineProperty(response[i], 'complete_name', complete_name);
            }
            $scope.empleados = response;
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
    }

    $scope.toggle = function(modalstate, item) {
        $scope.modalstate = modalstate;
        switch (modalstate) {
            case 'add':
                $http.get(API_URL + 'empleado/getAllPositions').success(function(response){
                    var longitud = response.length;
                    var array_temp = [];
                    for(var i = 0; i < longitud; i++){
                        array_temp.push({label: response[i].nombrecargo, id: response[i].idcargo})
                    }

                    $scope.idcargos = array_temp;
                    $scope.documentoidentidadempleado = '';
                    $scope.apellido = '';
                    $scope.nombre = '';
                    $scope.telefonoprincipal = '';
                    $scope.telefonosecundario = '';
                    $scope.celular = '';
                    $scope.direccion = '';
                    $scope.correo = '';
                    $scope.salario = '';

                    $scope.fechaingreso = fecha();

                    $scope.form_title = "Ingresar nuevo Empleado";

                    $('#modalAction').modal('show');
                });

                break;
            case 'edit':
                $scope.form_title = "Editar Empleado";
                $scope.id = item.idempleado;

                $http.get(API_URL + 'empleado/getAllPositions').success(function(response){
                    var longitud = response.length;
                    var array_temp = [];
                    for(var i = 0; i < longitud; i++){
                        array_temp.push({label: response[i].nombrecargo, id: response[i].idcargo})
                    }
                    $scope.idcargos = array_temp;

                    console.log(item.fechaingreso);

                           // $scope.fechaingreso = item.fechaingreso;

                            $scope.fechaingreso = convertDatetoDB(item.fechaingreso, true);
                            $scope.documentoidentidadempleado = item.documentoidentidadempleado;
                            $scope.idcargo = item.idcargo;
                            $scope.apellido = item.apellidos;
                            $scope.nombre = item.nombres;
                            $scope.telefonoprincipal = item.telefonoprincipaldomicilio;
                            $scope.telefonosecundario = item.telefonosecundariodomicilio;
                            $scope.celular = item.celular;
                            $scope.direccion = item.direcciondomicilio;
                            $scope.correo = item.correo;
                            $scope.salario = item.salario;
                            $('#modalAction').modal('show');
                        });
                break;

            case 'info':
                        $scope.name_employee = item.nombres + ' ' + item.apellidos;
                        $scope.cargo_employee = item.nombrecargo;
                        $scope.date_registry_employee = convertDatetoDB(item.fechaingreso, true);
                        //$scope.date_registry_employee = response[0].fechaingreso;
                        $scope.phones_employee = item.telefonoprincipaldomicilio + '/' + item.telefonosecundariodomicilio;
                        $scope.cel_employee = item.celular;
                        $scope.address_employee = item.direcciondomicilio;
                        $scope.email_employee = item.correo;
                        $scope.salario_employee = item.salario;

                        if (item.foto != null && item.foto != ''){
                            $scope.url_foto = item.foto;
                        } else {
                            $scope.url_foto = 'img/empleado.png';
                        }

                        $('#modalInfoEmpleado').modal('show');

                break;

            default:
                break;
        }


    };

    $scope.save = function() {
        var url = API_URL + "empleado";
        var method = 'POST';

        if ($scope.modalstate == 'edit'){
            url += "/updateEmpleado/" + $scope.id;
            //method = 'PUT';
        }
        var data ={
            fechaingreso: convertDatetoDB($scope.fechaingreso),
            documentoidentidadempleado: $scope.documentoidentidadempleado,
            idcargo: $scope.idcargo,
            apellidos: $scope.apellido,
            nombres: $scope.nombre,
            telefonoprincipaldomicilio: $scope.telefonoprincipal,
            telefonosecundariodomicilio: $scope.telefonosecundario,
            celular: $scope.celular,
            direcciondomicilio: $scope.direccion,
            correo: $scope.correo,
            salario: $scope.salario,
            file: $scope.file
        };

        Upload.upload({
            url: url,
            method: method,
            data: data
        }).success(function(data, status, headers, config) {
            if (data.success == true) {
                $scope.initLoad();
                $scope.message = 'Se guardó correctamente la información del empleado...';
                $('#modalAction').modal('hide');
                $('#modalMessage').modal('show');
                $scope.hideModalMessage();
            }
            else {
                $('#modalAction').modal('hide');
                $scope.message_error = 'Ya existe ese Colaborador...';
                $('#modalMessageError').modal('show');
            }
        });

    };

    $scope.showModalConfirm = function(item){
        $scope.empleado_del = item.idempleado;
        $scope.empleado_seleccionado = item.nombres + ' ' + item.apellidos;
            $('#modalConfirmDelete').modal('show');
    };

    $scope.destroy = function(){
        $http.delete(API_URL + 'empleado/' + $scope.empleado_del).success(function(response) {
            $scope.initLoad();
            $('#modalConfirmDelete').modal('hide');
            $scope.empleado_del = 0;
            $scope.message = 'Se eliminó correctamente el Empleado seleccionado';
            $('#modalMessage').modal('show');
            $scope.hideModalMessage();
        });
    };


    $scope.hideModalMessage = function () {
        setTimeout("$('#modalMessage').modal('hide')", 3000);
    };


    $scope.initLoad(true);

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
