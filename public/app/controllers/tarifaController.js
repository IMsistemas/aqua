

app.controller('tarifaController', function($scope, $http, API_URL) {

    $scope.tarifas = [];
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

        $http.get(API_URL + 'tarifa/getTarifas?page=' + pageNumber + '&filter=' + JSON.stringify(filtros)).
                    success(function(response){
            $scope.tarifas = response.data;
            $scope.totalItems = response.total;
        });
    };

    $scope.toggle = function(modalstate, id) {
        $scope.modalstate = modalstate;

        switch (modalstate) {

            case 'add':
                $scope.form_title = "Nueva Tarifa";
                $scope.nombretarifa = '';
                $('#modalActionCargo').modal('show');

                break;

            case 'edit':

                $scope.form_title = "Editar Tarifa";
                $scope.idc = id;

                $http.get(API_URL + 'tarifa/getTarifaByID/' + id).success(function(response) {

                    console.log(response);

                    $scope.nombretarifa = response[0].nametarifaaguapotable.trim();

                    $('#modalActionCargo').modal('show');
                });
                break;

            case 'action':

                $scope.listbasica = [];
                $scope.listexcedente = [];
                $scope.alcantarillado = '';
                $scope.ddss = '';
                $scope.ma = '';

                $scope.idc = id;

                $http.get(API_URL + 'tarifa/getTarifaBasica/' + id).success(function(response) {

                    $scope.listbasica = response;

                    $http.get(API_URL + 'tarifa/getExcedente/' + id).success(function(response) {

                        $scope.listexcedente = response;

                        $http.get(API_URL + 'tarifa/getRubro/' + id).success(function(response) {

                            if (response.length !== 0) {
                                $scope.alcantarillado = response[0].alcantarillado;
                                $scope.ddss = response[0].desechosolido;
                                $scope.ma = response[0].medioambiente;
                            }

                            $('#modalAction').modal('show');
                        });
                    });
                });

                break;

            default:
                break;
        }
    };

    $scope.createRowBasica = function () {

        var item = {
            apartirdenm3: '',
            valortarifa: ''
        };

        $scope.listbasica.push(item);

    };

    $scope.deleteRowBasica = function (item) {

        var posicion = $scope.listbasica.indexOf(item);
        $scope.listbasica.splice(posicion,1);

    };

    $scope.createRowExcedente = function () {

        var item = {
            desdenm3: '',
            valorexcedente: ''
        };

        $scope.listexcedente.push(item);

    };

    $scope.deleteRowExcedente = function (item) {

        var posicion = $scope.listexcedente.indexOf(item);
        $scope.listexcedente.splice(posicion,1);

    };


    $scope.Save = function (){

        var data = {
            nametarifaaguapotable: $scope.nombretarifa
        };

        switch ( $scope.modalstate) {
            case 'add':
                $http.post(API_URL + 'tarifa', data ).success(function (response) {
                    if (response.success == true) {
                        $scope.initLoad(1);
                        $('#modalActionCargo').modal('hide');
                        $scope.message = 'Se insertó correctamente la Tarifa...';
                        $('#modalMessage').modal('show');
                        $scope.hideModalMessage();
                    }
                    else {
                        $('#modalActionCargo').modal('hide');
                        $scope.message_error = 'Ya existe esa Tarifa...';
                        $('#modalMessageError').modal('show');
                    }
                });
                break;
            case 'edit':
                $http.put(API_URL + 'tarifa/'+ $scope.idc, data ).success(function (response) {

                    if (response.success == true) {
                        $scope.initLoad(1);
                        $('#modalActionCargo').modal('hide');
                        $scope.message = 'Se editó correctamente la Tarifa seleccionada';
                        $('#modalMessage').modal('show');
                    } else {
                        if (response.repeat == true) {
                            $scope.message_error = 'Ya existe esa Tarifa...';
                        } else {
                            $scope.message_error = 'Ha ocurrido un error al intentar editar la Tarifa seleccionada...';
                        }
                        $('#modalMessageError').modal('show');
                    }

                    $scope.hideModalMessage();
                }).error(function (res) {

                });
                break;
        }
    };

    $scope.saveParams = function () {

        var data = {
            basica: $scope.listbasica,
            excedente: $scope.listexcedente,
            alcantarillado: $scope.alcantarillado,
            ddss: $scope.ddss,
            ma: $scope.ma
        };

        console.log(data);

        $http.put(API_URL + 'tarifa/saveParams/'+ $scope.idc, data ).success(function (response) {

            $('#modalAction').modal('hide');

            if (response.success === true) {

                $scope.message = 'Se editó correctamente los parametros de la Tarifa seleccionada';
                $('#modalMessage').modal('show');

            } else {

                $scope.message_error = 'Ha ocurrido un error al intentar editar los parametros de la Tarifa seleccionada...';
                $('#modalMessageError').modal('show');
            }

            $scope.hideModalMessage();
        }).error(function (res) {

        });

    };

    $scope.showModalConfirm = function (item) {
        $scope.idcargo_del = item.idtarifaaguapotable;
        $scope.cargo_seleccionado = item.nametarifaaguapotable;
        $('#modalConfirmDelete').modal('show');
    };

    $scope.delete = function(){
        $http.delete(API_URL + 'tarifa/' + $scope.idcargo_del).success(function(response) {
            if(response.success == true){
                $scope.initLoad(1);
                $('#modalConfirmDelete').modal('hide');
                $scope.idcargo_del = 0;
                $scope.message = 'Se eliminó correctamente la Tarifa seleccionada...';
                $('#modalMessage').modal('show');
                $scope.hideModalMessage();

            } else {

                if (response.exists == true) {
                    $scope.message_error = 'La Tarifa no puede ser eliminada porque esta en uso...';
                } else {
                    $scope.message_error = 'Ha ocurrido un error al intentar eliminar la Tarifa seleccionada...';
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
