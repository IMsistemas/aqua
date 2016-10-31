
app.controller('empleadosController', function($scope, $http, API_URL) {

    $scope.empleados = [];
    $scope.empleado_del = 0;
    $scope.id = 0;

    $scope.initLoad = function(verifyPosition){

        if (verifyPosition != undefined){
            $scope.searchPosition();
        }

        $http.get(API_URL + 'empleado/getEmployees').success(function(response){
            console.log(response);
            $scope.empleados = response;
            //$('[data-toggle="tooltip"]').tooltip();
        });
    }

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
                        $scope.name_employee = item.apellidos + ' ' + item.nombres;
                        $scope.cargo_employee = item.nombrecargo;
                        $scope.date_registry_employee = convertDatetoDB(item.fechaingreso, true);
                        //$scope.date_registry_employee = response[0].fechaingreso;
                        $scope.phones_employee = item.telefonoprincipaldomicilio + '/' + item.telefonosecundariodomicilio;
                        $scope.cel_employee = item.celular;
                        $scope.address_employee = item.direcciondomicilio;
                        $scope.email_employee = item.correo;
                        $scope.salario_employee = item.salario;

                        $('#modalInfoEmpleado').modal('show');


                break;

            default:
                break;
        }


    }

    $scope.save = function() {
        var url = API_URL + "empleado";
        //append employee id to the URL if the form is in edit mode
        if ($scope.modalstate === 'edit'){
            url += "/" + $scope.id;
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
            salario: $scope.salario
        };

        if ($scope.modalstate === 'add'){
            $http.post(url,data ).success(function (response) {
                $scope.initLoad();
                $('#modalAction').modal('hide');
                $scope.message = 'Se inserto correctamente el Empleado';
                $('#modalMessage').modal('show');
            }).error(function (res) {
                console.log(res);
            });

        } else {

            $http.put(url, data ).success(function (response) {
                $scope.initLoad();
                $('#modalAction').modal('hide');
                $scope.message = 'Se edito correctamente el Empleado seleccionado';
                $('#modalMessage').modal('show');
            }).error(function (res) {

            });
        }

    }

    $scope.showModalConfirm = function(item){
        $scope.empleado_del = item.idempleado;
        $scope.empleado_seleccionado = item.apellidos + ' ' + item.nombres;
            $('#modalConfirmDelete').modal('show');
    }

    $scope.destroy = function(){
        $http.delete(API_URL + 'empleado/' + $scope.empleado_del).success(function(response) {
            $scope.initLoad();
            $('#modalConfirmDelete').modal('hide');
            $scope.empleado_del = 0;
            $scope.message = 'Se elimino correctamente el Empleado seleccionado';
            $('#modalMessage').modal('show');
        });
    }

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
