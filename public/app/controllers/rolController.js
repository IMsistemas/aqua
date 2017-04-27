

app.controller('rolController', function($scope, $http, API_URL) {

    $scope.departamentos = [];
    $scope.idcargo_del = 0;
    $scope.modalstate = '';

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

        $http.get(API_URL + 'rol/getRoles?page=' + pageNumber + '&filter=' + JSON.stringify(filtros)).
        success(function(response){
            $scope.roles = response.data;
            $scope.totalItems = response.total;
        });
    };

    $scope.toggle = function(modalstate, id) {
        $scope.modalstate = modalstate;

        switch (modalstate) {
            case 'add':
                $scope.form_title = "Nuevo Rol";
                $scope.nombrerol = '';
                $('#modalActionCargo').modal('show');

                break;
            case 'edit':

                $scope.form_title = "Editar Rol";
                $scope.idc = id;

                $http.get(API_URL + 'rol/getRolByID/' + id).success(function(response) {
                    $scope.nombrerol = response[0].namerol.trim();
                    $('#modalActionCargo').modal('show');
                });
                break;
            case 'perm':

                $http.get(API_URL + 'rol/getPermisos').success(function(response){

                    $scope.permisos = response;
                    $('#modalPermisos').modal('show');

                });

                break;
            default:
                break;
        }
    };

    $scope.Save = function (){

        var data = {
            namerol: $scope.nombrerol
        };

        switch ( $scope.modalstate) {
            case 'add':
                $http.post(API_URL + 'rol', data ).success(function (response) {
                    if (response.success == true) {
                        $scope.initLoad(1);
                        $('#modalActionCargo').modal('hide');
                        $scope.message = 'Se insertó correctamente el Rol...';
                        $('#modalMessage').modal('show');
                        $scope.hideModalMessage();
                    }
                    else {
                        $('#modalActionCargo').modal('hide');
                        $scope.message_error = 'Ya existe ese Rol...';
                        $('#modalMessageError').modal('show');
                    }
                });
                break;
            case 'edit':
                $http.put(API_URL + 'rol/'+ $scope.idc, data ).success(function (response) {

                    if (response.success == true) {
                        $scope.initLoad(1);
                        $('#modalActionCargo').modal('hide');
                        $scope.message = 'Se editó correctamente el Rol seleccionado';
                        $('#modalMessage').modal('show');
                    } else {
                        if (response.repeat == true) {
                            $scope.message_error = 'Ya existe ese Rol...';
                        } else {
                            $scope.message_error = 'Ha ocurrido un error al intentar editar el Rol seleccionado...';
                        }
                        $('#modalMessageError').modal('show');
                    }

                    $scope.hideModalMessage();
                }).error(function (res) {

                });
                break;
        }
    };

    $scope.showModalConfirm = function (rol) {
        $scope.idcargo_del = rol.idrol;
        $scope.cargo_seleccionado = rol.namerol;
        $('#modalConfirmDelete').modal('show');
    };

    $scope.delete = function(){
        $http.delete(API_URL + 'rol/' + $scope.idcargo_del).success(function(response) {
            if(response.success == true){
                $scope.initLoad(1);
                $('#modalConfirmDelete').modal('hide');
                $scope.idcargo_del = 0;
                $scope.message = 'Se eliminó correctamente el Rol seleccionado...';
                $('#modalMessage').modal('show');
                $scope.hideModalMessage();

            } else {

                if (response.exists == true) {
                    $scope.message_error = 'El Rol no puede ser eliminado porque esta asignado a un Usuario...';
                } else {
                    $scope.message_error = 'Ha ocurrido un error al intentar eliminar el rol seleccionado...';
                }

                $('#modalMessageError').modal('show');
                //$('#modalConfirmDelete').modal('hide');
            }
        });

    };

    $scope.hideModalMessage = function () {
        setTimeout("$('#modalMessage').modal('hide')", 3000);
    };

    $scope.initLoad();

});
