
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

            var longitud_facConsumo = response.length;

            for (var i = 0; i < longitud_facConsumo; i++) {

                var obj = {
                    id: response[i].idcobroagua,
                    total: response[i].total,
                    fecha: response[i].fechacobro,
                    type: 'facConsumo',
                    name: 'Toma Lectura Consumo',
                    idtype : response[i].idcobroagua + '_' + 'facConsumo'
                };

                $scope.listTransacciones.push(obj)

            }

            $http.get(API_URL + 'recaudacionC/getDerechoAcometida/' + idcliente).success(function(response){


                var longitud_derAcometida = response.length;

                for (var i = 0; i < longitud_derAcometida; i++) {

                    if (parseInt(response[i].dividendocredito) === 1) {

                        var obj = {
                            id: response[i].idsuministro,
                            total: response[i].valortotalsuministro,
                            fecha: response[i].fechainstalacion,
                            type: 'derAcometida',
                            name: 'Derecho Acometida No. Suministro: ' + response[i].idsuministro,
                            idtype : response[i].idsuministro + '_' + 'derAcometida'
                        };

                        $scope.listTransacciones.push(obj);

                    } else {

                        var total = parseFloat(response[i].valortotalsuministro) / parseInt(response[i].dividendocredito);

                        for(var j = 0; j < parseInt(response[i].dividendocredito); j++) {

                            var obj = {
                                id: response[i].idsuministro,
                                total: total.toFixed(2),
                                fecha: response[i].fechainstalacion,
                                type: 'derAcometida',
                                name: 'Derecho Acometida (Cuota # ' + (j + 1) + ') No. Suministro: ' + response[i].idsuministro,
                                idtype : response[i].idsuministro + '_' + 'derAcometida'
                            };

                            $scope.listTransacciones.push(obj);

                        }

                    }



                }

                $('#listCobros').modal('show');

            });

        });

    };


});
