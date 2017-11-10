
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


                $http.get(API_URL + 'recaudacionC/getOtrosCargos/' + idcliente).success(function(response){

                    var longitud_otrosCargos = response.length;

                    for (var i = 0; i < longitud_otrosCargos; i++) {

                        var obj = {
                            id: response[i].idsolicitudservicio,
                            total: 0,
                            fecha: response[i].fechaprocesada,
                            type: 'otrosCargos',
                            name: 'Otros Cargos',
                            idtype : response[i].idsolicitudservicio + '_' + 'otrosCargos'
                        };

                        $scope.listTransacciones.push(obj)

                    }

                    $('#listCobros').modal('show');

                });



            });

        });

    };

    $scope.createFactura = function () {

        var selected = [];

        $(".transfer:checked").each(function() {



            var a = ($(this).val()).split('_');

            var o = {

                id: a[0],
                type: a[1]

            };

            selected.push(o);

        });

        console.log(selected);

        $http.get(API_URL + 'recaudacionC/createFactura?data=' + JSON.stringify(selected)).success(function(response){

            $scope.currentProjectUrl = '';

            $scope.currentProjectUrl = API_URL + 'DocumentoVenta?flag_suministro=1';
            $("#aux_venta").html("<object height='450px' width='100%' data='"+$scope.currentProjectUrl+"'></object>");
            $('#modalFactura').modal('show');

        });

    };

    $scope.generate = function () {

        var result_agua = false;
        var result_servicio = false;

        $http.get(API_URL + 'factura/generate').success(function(response){

            //console.log(response);

            if (response.success === true) {
                result_agua = true;
            }

            $http.get(API_URL + 'cobroservicio/generate').success(function(response){

                //console.log(response);

                if (response.success === true) {
                    result_servicio = true;
                }

                if (result_agua === true && result_servicio === true) {

                    $scope.initLoad();

                    $scope.message = 'Se ha generado los cobros de Lecturas/Servicios del mes actual correctamente...';
                    $('#modalMessage').modal('show');
                } else {
                    $scope.message_error = 'Ha ocurrido un error al intentar generar los Cobros respectivos al mes...';
                    $('#modalMessageError').modal('show');
                }


            });

        });



    };

});
