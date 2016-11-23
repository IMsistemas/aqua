

app.controller('ccClienteController', function($scope, $http, API_URL) {

    $scope.cuentascobrar = [];

    $scope.initLoad = function(){

        $http.get(API_URL + 'cuentascobrarcliente/getAll').success(function(response){
            console.log(response);
            var longitud = response.length;
            for (var i = 0; i < longitud; i++) {
                var complete_name = {
                    value: response[i].cliente.nombres + ' ' + response[i].cliente.apellidos,
                    writable: true,
                    enumerable: true,
                    configurable: true
                };
                Object.defineProperty(response[i].cliente, 'complete_name', complete_name);
            }
            $scope.cuentascobrar = response;

        });
    }

    $scope.initLoad();

});
