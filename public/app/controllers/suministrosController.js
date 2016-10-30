app.controller('suministrosController', function($scope, $http, API_URL) {

	$scope.initLoad = function(){	
		$http.get(API_URL + 'suministros/getsuministros').success(function(response) {
		    console.log(response);
                $scope.suministros = response;
    	});
	}


	$scope.getSuministro = function(numeroSuministro){
		$http.get(API_URL + 'suministros/suministroById/'+numeroSuministro).success(function(response) {
                $scope.suministro = response[0];
                $('#modalVerSuministro').modal('show');	
    	}).error(function(response){
    		$scope.mensajeError = "Error al cargar el suministro";
     		$('#modalError').modal('show');
    	});
	}

    $scope.modalEditarSuministro = function(id){
        $http.get(API_URL+ 'suministros/suministroById/' +id).success(function (response) {

            console.log(response);

            $scope.suministro = response[0];

            $scope.telefonosuministro = response[0].telefonosuministro;
            $scope.direccionsuministro = response[0].direccionsuministro;

            $http.get(API_URL+ 'suministros/getAguapotable').success(function (response) {
                var longitud = response.length;
                //var array_temp = [{label: '--Seleccione--', id: 0}];
                var array_temp = [];
                for (var i = 0; i < longitud; i++) {
                    array_temp.push({label: response[i].nombreservicio, id: response[i].idaguapotable});
                }
                $scope.agua_potable = array_temp;
                $scope.aguapotable = $scope.suministro.servicioaguapotable.idaguapotable;
            });

            $http.get(API_URL+ 'suministros/getCalle').success(function (response) {
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

    $scope.editarSuministro = function(){
        var data = {
            idaguapotable: $scope.aguapotable,
            idcalle: $scope.calle,
            direccionsuministro: $scope.direccionsuministro,
            telefonosuministro: $scope.telefonosuministro
        };

        $http.put(API_URL + 'suministros/' + $scope.suministro.numerosuministro , data).success(function (response) {
            $scope.initLoad();
            $('#editar-suministro').modal('hide');
            $scope.message = 'Se edito correctamente el Cliente seleccionado...';
            $('#modalConfirmacion').modal('show');
        });
};

    $scope.initLoad();
});