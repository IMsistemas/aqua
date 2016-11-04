app.controller('facturaController', function($scope, $http, API_URL) {

    $scope.factura = [];



    $scope.initLoad = function () {
        $http.get(API_URL + 'factura/getFacturas').success(function(response){
            // console.log(response);
            $scope.factura = response;
        });
    };


    $scope.FiltrarPorServicio = function () {
        $http.get(API_URL + 'calle/getBarrio').success(function (response) {
            var longitud = response.length;
            var array_temp = [{label: '--Zonas --', id: 0}];
            for (var i = 0; i < longitud; i++) {
                array_temp.push({label: response[i].nombrebarrio, id: response[i].idbarrio})
            }
            $scope.barrioss = array_temp;
            $scope.s_barrio = 0;
        });
    };
    $scope.initLoad();

});