app.controller('suministrosController', function($scope, $http, API_URL) {

    $scope.initLoad = function () {
        $http.get(API_URL + 'suministros/getsuministros').success(function (response) {
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
            $scope.suministros = response;
        });
    }

    $scope.Filtro = function () {
        $http.get(API_URL + 'calle/getBarrio').success(function (response) {
            var longitud = response.length;
            var array_temp = [{label: '--Zonas --', id: 0}];
            for (var i = 0; i < longitud; i++) {
                array_temp.push({label: response[i].nombrebarrio, id: response[i].idbarrio})
            }
            $scope.zonass = array_temp;
            $scope.s_zona = 0;
        });
        $scope.transversaless = [{label: '--Transversales--', id: 0}];
        $scope.s_transversales = 0;
    };

    $scope.FiltrarPorBarrio = function (){
        $http.get(API_URL + 'suministros/getCallesByBarrio/'+ $scope.s_zona).success(function (response) {
           // console.log(response);
            var longitud = response.length;
            var array_temp = [{label: '--Transversales--', id: 0}];
            for (var i = 0; i < longitud; i++) {
                array_temp.push({label: response[i].nombrecalle, id: response[i].idcalle})
            }
            $scope.transversaless = array_temp;
            $scope.s_transversales = 0;
        });
        $scope.aux = 0;
        $scope.aux = $scope.s_zona;
        if($scope.aux > 0)
        {
        $http.get(API_URL + 'suministros/getSuministrosByBarrio/'+ $scope.aux).success(function(response) {
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
            $scope.suministros = response;

            });
        }
        else {

            $scope.initLoad();
        }
    };

    $scope.FiltrarPorCalle = function (){

        $scope.aux1 = $scope.s_zona;
        $scope.aux2 = $scope.s_transversales;

        if($scope.aux2 > 0)
        {
            $http.get(API_URL + 'suministros/getSuministrosByCalle/'+ $scope.s_transversales).success(function(response) {
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
                $scope.suministros = response;

            });
        }
        else {

            $scope.FiltrarPorBarrio();
        }
    }

    $scope.getSuministro = function (numeroSuministro) {
        $http.get(API_URL + 'suministros/suministroById/' + numeroSuministro).success(function (response) {

            $scope.nombre_apellido = response[0].cliente.nombres + " " + response[0].cliente.apellidos;
            $scope.numerosuministro = response[0].numerosuministro;
            $scope.fechainstalacionsuministro = response[0].fechainstalacionsuministro;
            $scope.zona = response[0].calle.barrio.nombrebarrio;
            $scope.direccionsumnistro = response[0].direccionsumnistro;
            $scope.telefonosuministro = response[0].telefonosuministro;
            $scope.transversal = response[0].calle.nombrecalle;

            $('#modalVerSuministro').modal('show');
        }).error(function (response) {
            $scope.mensajeError = "Error al cargar el suministro";
            $('#modalError').modal('show');
        });
    }

    $scope.modalEditarSuministro = function (id) {
        $http.get(API_URL + 'suministros/suministroById/' + id).success(function (response) {

            console.log(response);

            $scope.suministro = response[0];

            $scope.telefonosuministro = response[0].telefonosuministro;
            $scope.direccionsuministro = response[0].direccionsumnistro;

            $http.get(API_URL + 'suministros/getCalle').success(function (response) {
                var longitud = response.length;
                //var array_temp = [{label: '--Seleccione--', id: 0}];
                var array_temp = [];
                for (var i = 0; i < longitud; i++) {
                    array_temp.push({label: response[i].nombrecalle, id: response[i].idcalle});
                }
                $scope.calles = array_temp;
                $scope.calle = $scope.suministro.calle.idcalle;
            });
            $('#editar-suministro').modal('show');
        });


    }

    $scope.editarSuministro = function () {
        var data = {
            idcalle: $scope.calle,
            direccionsuministro: $scope.direccionsuministro,
            telefonosuministro: $scope.telefonosuministro
        };

        $http.put(API_URL + 'suministros/' + $scope.suministro.numerosuministro, data).success(function (response) {
            $scope.initLoad();
            $('#editar-suministro').modal('hide');
            $scope.message = 'Se editó correctamente el Cliente seleccionado...';
            $('#modalConfirmacion').modal('show');
            $scope.hideModalMessage();
        });
    };

    $scope.hideModalMessage = function () {
        setTimeout("$('#modalConfirmacion').modal('hide')", 3000);
    };

    $scope.initLoad();
    $scope.Filtro();
});