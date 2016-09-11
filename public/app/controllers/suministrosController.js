app.controller('suministrosController', function($scope, $http, API_URL) {

	var existeSuministro = false;
	
	$scope.initLoad = function(){	
		$http.get(API_URL + "suministros/suministros")
            .success(function(response) {
                $scope.suministros = response;
    	});
	}

	$scope.initLoad();
	

	$scope.getSuministro = function(numeroSuministro){
		$http.get(API_URL + "suministros/suministros/"+numeroSuministro)
            .success(function(response) {
                $scope.suministro = response[0];
                $('#modalVerSuministro').modal('show');	
    	}).error(function(response){
    		$scope.mensajeError = "Error al cargar el suministro";
     		$('#modalError').modal('show');
    	});
	}

    $scope.editarSuministro = function(){
    	var url = API_URL +"suministros/suministros/editar";

    	$http.get(API_URL+"tarifas/tarifas")
            .success(function (response) {
                $scope.tarifas = response;
        });

        $http.get(API_URL+"barrios/gestion/concalles")
            .success(function (response) {
                $scope.barrios = response;
        });

        $http.get(API_URL+"clientes/gestion")
            .success(function (response) {
                $scope.clientes = response;
        });

        

        $http({
            method: 'POST',
            url: url,
            data: $.param($scope.suministro),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function(response) {
             
        }).error(function(response) {
            $scope.messageError = 'Error al procesar solicitud';
           $('#modalMessageError').modal('show');           
        });
    
    }
});