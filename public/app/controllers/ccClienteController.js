

app.controller('ccClienteController', function($scope, $http, API_URL) {

    $scope.cuentascobrar = [];

    $scope.initLoad = function(){

        $http.get(API_URL + 'cuentascobrarcliente/getAll').success(function(response){
            $scope.cuentascobrar = response;
        });

    }

    $scope.search = function(){

        var filters = {
            text: $scope.t_search
        }

        $http.get(API_URL + 'cuentascobrarcliente/getByFilter/' + JSON.stringify(filters)).success(function(response){
            $scope.cuentascobrar = response;
        });

    }

    $scope.initLoad();

});
