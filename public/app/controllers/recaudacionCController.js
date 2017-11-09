
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

    $scope.getTransacciones = function (idcliente) {

        $scope.listTransacciones = [];

        $http.get(API_URL + 'recaudacionC/getFacConsumo/' + idcliente).success(function(response){

            $scope.clientes = response.data;
            $scope.totalItems = response.total;


            var longitud_facConsumo = response.length;

            for (var i = 0; i < longitud_facConsumo; i++) {

                var obj = {
                    id: response[i].idcobroagua,
                    total: response[i].total,
                    fecha: response[i].fechacobro,
                    type: 'facConsumo',
                    name: 'Fac. Consumo'
                };

                $scope.listTransacciones.push(obj)

            }

            $('#listCobros').modal('show');

        });

    };


});
