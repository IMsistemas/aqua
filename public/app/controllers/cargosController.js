

app.controller('cargosController', function($scope, $http, API_URL) {

    $scope.cargos = [];
    $scope.idcargo_del = 0;
    $scope.modalstate = '';

    $scope.pageChanged = function(newPage) {
        $scope.initLoad(newPage);
    };

    $scope.initLoad = function(pageNumber){

        $scope.searchDepartament();

        if ($scope.busqueda == undefined) {
            var search = null;
        } else var search = $scope.busqueda;

        var filtros = {
            search: search
        };

        $http.get(API_URL + 'cargo/getCargos?page=' + pageNumber + '&filter=' + JSON.stringify(filtros)).success(function(response){
            $scope.cargos = response.data;
            $scope.totalItems = response.total;
        });
    };

    $scope.searchDepartament = function(){
        $http.get(API_URL + 'cargo/getExistDepartament').success(function(response){

            if(response.success === false){
                $('#btnAgregar').prop('disabled', true);
                $('#message-positions').show();
            } else {
                $('#btnAgregar').prop('disabled', false);
                $('#message-positions').hide();
            }

        });
    };

    $scope.toggle = function(modalstate, id) {
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

                    $scope.form_title = "Nuevo Cargo";
                    $scope.nombrecargo = '';
                    $('#modalActionCargo').modal('show');

                });

                break;
            case 'edit':

                $http.get(API_URL + 'empleado/getDepartamentos').success(function(response){
                    var longitud = response.length;
                    var array_temp = [{label: '-- Seleccione --', id: ''}];
                    for(var i = 0; i < longitud; i++){
                        array_temp.push({label: response[i].namedepartamento, id: response[i].iddepartamento})
                    }
                    $scope.iddepartamentos = array_temp;


                    $scope.form_title = "Editar Cargo";
                    $scope.idc = id;

                    $http.get(API_URL + 'cargo/getCargoByID/' + id).success(function(response) {

                        $scope.departamento = response[0].iddepartamento;
                        $scope.nombrecargo = response[0].namecargo.trim();
                        $('#modalActionCargo').modal('show');
                    });

                });


                    break;
            default:
                break;
        }
    };

    $scope.Save = function (){

        var data = {
            nombrecargo: $scope.nombrecargo,
            iddepartamento: $scope.departamento
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
        $scope.cargo_seleccionado = cargo.namecargo;
        $('#modalConfirmDelete').modal('show');
    };

    $scope.delete = function(){
        $http.delete(API_URL + 'cargo/' + $scope.idcargo_del).success(function(response) {
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
