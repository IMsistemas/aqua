

app.controller('cargosController', function($scope, $http, API_URL) {

    $scope.cargos = [];
    $scope.idcargo_del = 0;

    $scope.initLoad = function(){
        $http.get(API_URL + 'cargo/getCargos').success(function(response){
            $scope.cargos = response;
        });
    }

    $scope.initLoad();

    $scope.toggle = function(modalstate, id) {
        $scope.modalstate = modalstate;

        switch (modalstate) {
            case 'add':

                $http.get(API_URL + 'cargo/lastId').success(function(response){

                    $scope.idcargo = response.lastId;
                    $scope.form_title = "Ingresar nuevo Cargo";
                    $scope.nombrecargo = '';
                    $('#modalActionCargo').modal('show');
                });

                break;
            case 'edit':
                $scope.form_title = "Editar Cargo";
                $scope.id = id;

                $http.get(API_URL + 'cargo/' + id).success(function(response) {
                    $scope.idcargo = (response.idcargo).trim();
                    $scope.nombrecargo = (response.nombrecargo).trim();
                    $('#modalActionCargo').modal('show');
                });

                break;
            default:
                break;
        }


    }

    $scope.save = function(modalstate, id) {

        var url = API_URL + "cargo";

        if (modalstate === 'edit'){
            url += "/" + id;
        }

        $scope.cargo={
            idcargo: $scope.idcargo,
            nombrecargo: $scope.nombrecargo
        };

        if (modalstate === 'add'){
            $http.post(url,$scope.cargo ).success(function (data) {
                $scope.initLoad();

                $('#modalActionCargo').modal('hide');
                $scope.message = 'Se insertó correctamente el Cargo';
                $('#modalMessage').modal('show');

            }).error(function (res) {

            });
        } else {
            $http.put(url, $scope.cargo ).success(function (data) {
                $scope.initLoad();
                $('#modalActionCargo').modal('hide');
                $scope.message = 'Se edito correctamente el Cargo seleccionado';
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

        $http.get(API_URL + 'cargo/getByFilter/' + JSON.stringify(filter)).success(function(response){
            $scope.cargos = response;
        });
    }


    $scope.showModalConfirm = function(id){
        $scope.idcargo_del = id;
        $http.get(API_URL + 'cargo/' + id).success(function(response) {
            $scope.cargo_seleccionado = (response.nombrecargo).trim();
            $('#modalConfirmDelete').modal('show');
        });
    }

    $scope.destroyCargo = function(){
        $http.delete(API_URL + 'cargo/' + $scope.idcargo_del).success(function(response) {
            $scope.initLoad();
            $('#modalConfirmDelete').modal('hide');
            $scope.idcargo_del = 0;
            $scope.message = 'Se elimino correctamente el Cargo seleccionado';
            $('#modalMessage').modal('show');
        });
    }
});
