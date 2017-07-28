

app.controller('departamentosController', function($scope, $http, API_URL) {

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

        $http.get(API_URL + 'departamento/getDepartamentos?page=' + pageNumber + '&filter=' + JSON.stringify(filtros)).
                    success(function(response){
            $scope.departamentos = response.data;
            $scope.totalItems = response.total;
        });
    };

    $scope.toggle = function(modalstate, id) {
        $scope.modalstate = modalstate;

        switch (modalstate) {
            case 'add':
                $scope.form_title = "Nuevo Departamento";
                $scope.nombrecargo = '';
                $scope.centrocosto = "false";
                $('#modalActionCargo').modal('show');

                break;
            case 'edit':

                $scope.form_title = "Editar Departamento";
                $scope.idc = id;

                $http.get(API_URL + 'departamento/getDepartamentoByID/' + id).success(function(response) {
                    $scope.nombrecargo = response[0].namedepartamento.trim();

                    if (response[0].centrocosto === true) {
                        $scope.centrocosto = 'true';
                    } else {
                        $scope.centrocosto = 'false';
                    }

                    $('#modalActionCargo').modal('show');
                });
                break;
            default:
                break;
        }
    };

    $scope.Save = function (){

        var centrocosto = false;

        if ($scope.centrocosto === "true") {
            centrocosto = true;
        }

        var data = {
            namedepartamento: $scope.nombrecargo,
            centrocosto: centrocosto
        };

        switch ( $scope.modalstate) {
            case 'add':
                $http.post(API_URL + 'departamento', data ).success(function (response) {
                    if (response.success == true) {
                        $scope.initLoad(1);
                        $('#modalActionCargo').modal('hide');
                        $scope.message = 'Se insertó correctamente el Departamento...';
                        $('#modalMessage').modal('show');
                        $scope.hideModalMessage();
                    }
                    else {
                        $('#modalActionCargo').modal('hide');
                        $scope.message_error = 'Ya existe ese Departamento...';
                        $('#modalMessageError').modal('show');
                    }
                });
                break;
            case 'edit':
                $http.put(API_URL + 'departamento/'+ $scope.idc, data ).success(function (response) {

                    if (response.success == true) {
                        $scope.initLoad(1);
                        $('#modalActionCargo').modal('hide');
                        $scope.message = 'Se editó correctamente el Departamento seleccionado';
                        $('#modalMessage').modal('show');
                    } else {
                        if (response.repeat == true) {
                            $scope.message_error = 'Ya existe ese Departamento...';
                        } else {
                            $scope.message_error = 'Ha ocurrido un error al intentar editar el departamento seleccionado...';
                        }
                        $('#modalMessageError').modal('show');
                    }

                    $scope.hideModalMessage();
                }).error(function (res) {

                });
                break;
        }
    };

    $scope.showModalConfirm = function (departamento) {
        $scope.idcargo_del = departamento.iddepartamento;
        $scope.cargo_seleccionado = departamento.namedepartamento;
        $('#modalConfirmDelete').modal('show');
    };

    $scope.delete = function(){
        $http.delete(API_URL + 'departamento/' + $scope.idcargo_del).success(function(response) {
            if(response.success == true){
                $scope.initLoad(1);
                $('#modalConfirmDelete').modal('hide');
                $scope.idcargo_del = 0;
                $scope.message = 'Se eliminó correctamente el Departamento seleccionado...';
                $('#modalMessage').modal('show');
                $scope.hideModalMessage();

            } else {

                if (response.exists == true) {
                    $scope.message_error = 'El Departamento no puede ser eliminado porque esta asignado a un Cargo...';
                } else {
                    $scope.message_error = 'Ha ocurrido un error al intentar eliminar el departamento seleccionado...';
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
