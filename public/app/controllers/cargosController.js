

app.controller('cargosController', function($scope, $http, API_URL) {
    $scope.cargos = [];
    $scope.modalstate =  '';
    $scope.idcargo_del = 0;


    $scope.initLoad = function () {
        $http.get(API_URL + 'cargo/getCargos').success(function (response) {
            $scope.cargos = response;
        });
    }

    $scope.Add = function (modalstate,id) {
        $scope.modalstate = modalstate ;
        switch (modalstate) {
            case 'add':
                $scope.form_title = "Adicionar Cargo";
                $scope.nombrecargo = '';

                $('#modalActionCargo').modal('show');
                break;
            case 'edit':
                $scope.idc = id;
                $http.get(API_URL + 'cargo/getCargoByID/' + id).success(function(response) {
                    console.log(response[0].nombrecargo);
                    $scope.nombrecargo = response[0].nombrecargo;
                    $scope.form_title = "Editar Cargo";
                    $('#modalActionCargo').modal('show');
                });
                break;
        }
    };

    $scope.Save = function (){
        var data = {
            nombrecargo: $scope.nombrecargo
        };

        switch ( $scope.modalstate) {
            case 'add':
                $http.post(API_URL + 'cargo', data ).success(function (response) {
                    if (response.success == true) {
                        $scope.initLoad();
                        $('#modalActionCargo').modal('hide');
                        $scope.message = 'Se insertó correctamente el Cargo...';
                        $('#modalMessage').modal('show');
                        $scope.hideModalMessage();
                    }
                    else {
                        $('#modalActionCargo').modal('hide');
                        $scope.message_error = 'Ya existe ese Cargo...';
                        $('#modalMessageError').modal('show');
                    }

                });

                break;
            case 'edit':
                    $http.put(API_URL + 'cargo/'+ $scope.idc, data ).success(function (response) {
                        $scope.initLoad();
                        $('#modalActionCargo').modal('hide');
                        $scope.message = 'Se editó correctamente el Cargo seleccionado';
                        $('#modalMessage').modal('show');
                        $scope.hideModalMessage();
                    }).error(function (res) {

                    });
                    break;
        }
    };

    $scope.showModalConfirm = function (cargo) {
        $scope.idcargo_del = cargo.idcargo;
        $scope.nom_cargo_delete = cargo.nombrecargo;
        $('#modalConfirmDelete').modal('show');
    };


    $scope.delete = function(){
        $http.delete(API_URL + 'cargo/' + $scope.idcargo_del).success(function(response) {
            console.log(response.success);
            if(response.success == true){
                $scope.initLoad();
                $('#modalConfirmDelete').modal('hide');
                $scope.idcargo_del = 0;
                $scope.message = 'Se eliminó correctamente el Cargo seleccionado...';
                $('#modalMessage').modal('show');
                $scope.hideModalMessage();

            } else {
                $scope.message_error = 'El Cargo no puede ser eliminado porque esta asignado a un colaborador...';
                $('#modalMessageError').modal('show');
                $('#modalConfirmDelete').modal('hide');
            }
        });



    };

    $scope.hideModalMessage = function () {
        setTimeout("$('#modalMessage').modal('hide')", 3000);
    };

    $scope.initLoad();
});

