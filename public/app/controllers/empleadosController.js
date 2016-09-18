
app.controller('empleadosController', function($scope, $http, API_URL) {

    $scope.empleados = [];
    $scope.empleado_del = 0;

    $scope.initLoad = function(verifyPosition){

        if (verifyPosition != undefined){
            $scope.searchPosition();
        }

        $http.get(API_URL + 'empleado/getEmployees').success(function(response){
            $scope.empleados = response;
            $('[data-toggle="tooltip"]').tooltip();
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

 
    $scope.initLoad(true);

    $scope.toggle = function(modalstate, id) {
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

                    $scope.fechaingreso = fecha();

                    $scope.form_title = "Ingresar nuevo Empleado";

                    $('#modalAction').modal('show');
                });

                break;
            case 'edit':
                $scope.form_title = "Editar Empleado";
                $scope.id = id;

                $http.get(API_URL + 'empleado/getAllPositions').success(function(response){
                    var longitud = response.length;
                    var array_temp = [];
                    for(var i = 0; i < longitud; i++){
                        array_temp.push({label: response[i].nombrecargo, id: response[i].idcargo})
                    }
                    $scope.idcargos = array_temp;

                    $http.get(API_URL + 'empleado/' + id)
                        .success(function(response) {
                            $scope.fechaingreso = convertDatetoDB(response[0].fechaingreso, true);
                            $scope.documentoidentidadempleado = response[0].documentoidentidadempleado;
                            $scope.idcargo = response[0].idcargo;
                            $scope.apellido = response[0].apellido;
                            $scope.nombre = response[0].nombre;
                            $scope.telefonoprincipal = response[0].telefonoprincipal;
                            $scope.telefonosecundario = response[0].telefonosecundario;
                            $scope.celular = response[0].celular;
                            $scope.direccion = response[0].direccion;
                            $scope.correo = response[0].correo;

                            $('#modalAction').modal('show');

                        });

                });

                break;

            case 'info':

                $http.get(API_URL + 'empleado/' + id)
                    .success(function(response) {
                        $scope.name_employee = response[0].apellido + ' ' + response[0].nombre;
                        $scope.cargo_employee = response[0].nombrecargo;
                        $scope.date_registry_employee = convertDatetoDB(response[0].fechaingreso, true);
                        $scope.phones_employee = response[0].telefonoprincipal + '/' + response[0].telefonosecundario;
                        $scope.cel_employee = response[0].celular;
                        $scope.address_employee = response[0].direccion;
                        $scope.email_employee = response[0].correo;

                        $('#modalInfoEmpleado').modal('show');
                    });

                break;

            default:
                break;
        }


    }

    $scope.save = function(modalstate, id) {

        var url = API_URL + "empleado";

        //append employee id to the URL if the form is in edit mode
        if (modalstate === 'edit'){
            url += "/" + id;
        }

        $scope.empleado={
            fechaingreso: convertDatetoDB($scope.fechaingreso),
            documentoidentidadempleado: $scope.documentoidentidadempleado,
            idcargo: $scope.idcargo,
            apellido: $scope.apellido,
            nombre: $scope.nombre,
            telefonoprincipal: $scope.telefonoprincipal,
            telefonosecundario: $scope.telefonosecundario,
            celular: $scope.celular,
            direccion: $scope.direccion,
            correo: $scope.correo,
        };
 
        if (modalstate === 'add'){

            $http.post(url,$scope.empleado ).success(function (data) {
                $scope.initLoad();
                $('#modalAction').modal('hide');
                $scope.message = 'Se inserto correctamente el Empleado';
                $('#modalMessage').modal('show');
            }).error(function (res) {

                console.log(res);

            });

        } else {
            $http.put(url, $scope.empleado ).success(function (data) {
                $scope.initLoad();
                $('#modalAction').modal('hide');
                $scope.message = 'Se edito correctamente el Empleado seleccionado';
                $('#modalMessage').modal('show');
            }).error(function (res) {

            });
        }

    }

    $scope.searchByFilter = function(){

        var t_search = null;

        if($scope.search != undefined && $scope.search != ''){
            t_search = $scope.search;
        }

        var filter = {
            text: t_search
        };

        $http.get(API_URL + 'empleado/getByFilter/' + JSON.stringify(filter)).success(function(response){
            $scope.empleados = response;
        });
    }

    $scope.showModalConfirm = function(id){
        $scope.empleado_del = id;
        $http.get(API_URL + 'empleado/' + id).success(function(response) {
            $scope.empleado_seleccionado = response[0].apellido + ' ' + response[0].nombre;
            $('#modalConfirmDelete').modal('show');
        });
    }

    $scope.destroyCargo = function(){
        $http.delete(API_URL + 'empleado/' + $scope.empleado_del).success(function(response) {
            $scope.initLoad();
            $('#modalConfirmDelete').modal('hide');
            $scope.empleado_del = 0;
            $scope.message = 'Se elimino correctamente el Empleado seleccionado';
            $('#modalMessage').modal('show');
        });
    }
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
