

app.controller('cpClienteController', function($scope, $http, API_URL) {

    $scope.cuentas = [];

    $scope.initLoad = function(){

        $http.get(API_URL + 'cuentaspagarcliente/getAll').success(function(response){
            $scope.cuentas = response;
        });

    }

    $scope.search = function(){

        var filters = {
            text: $scope.t_search
        }

        $http.get(API_URL + 'cuentaspagarcliente/getByFilter/' + JSON.stringify(filters)).success(function(response){
            $scope.cuentas = response;
        });

    }

    $scope.initLoad();

});
