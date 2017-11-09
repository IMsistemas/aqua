
app.controller('recaudacionCController',  function($scope, $http, API_URL) {

    $scope.pageChanged = function(newPage) {
        $scope.initLoad(newPage);
    };

    $scope.initLoad = function (pageNumber) {

        if ($scope.busqueda == undefined) {
            var search = null;
        } else var search = $scope.busqueda;

        var filtros = {
            search: search,
            estado: $scope.s_estado_search
        };

        $http.get(API_URL + 'recaudacionC/getClientes?page=' + pageNumber + '&filter=' + JSON.stringify(filtros)).success(function(response){

            $scope.clientes = response.data;
            $scope.totalItems = response.total;

        });
    };


});
