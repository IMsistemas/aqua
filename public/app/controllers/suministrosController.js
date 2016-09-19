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

    $scope.modalEditarSuministro = function(id){
        $http.get(API_URL+"suministros/suministros/"+id)
        .success(function (response) {
            $scope.suministro = response[0];  
       
        $http.get(API_URL+"tarifas/tarifas")
            .success(function (response) {
                $scope.tarifas = response;
            });

         $http.get(API_URL+"barrios/gestion/concalles")
            .success(function (response) {
                $scope.barrios = response;
            });

        $http.get(API_URL+"barrios/gestion/"+$scope.suministro.calle.idbarrio)
            .success(function (response) {
                $scope.barrio = response[0];
            });

        $http.get(API_URL+"calles/gestion/"+$scope.suministro.calle.idcalle)
            .success(function (response) {
                $scope.calle = response[0];
                console.log($scope.calle);
            });

           //$scope.editarSuministro = response[0];
            $('#editar-suministro').modal('show');
        });
    }

    $scope.editarSuministro = function(idsuministro){
    	var url = API_URL +"suministros/editar/"+idsuministro;
        console.log($scope.suministro);
        $http({
            method: 'POST',
            url: url,
            data: $.param($scope.suministro),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function(response) {
            $scope.initLoad();
             $('#editar-suministro').modal('hide');
            $scope.message = response;
             $('#modalConfirmacion').modal('show');
             setTimeout("$('#modalConfirmacion').modal('hide')",5000);
        }).error(function(response) {
            $scope.messageError = 'Error al procesar solicitud';
           $('#modalMessageError').modal('show');           
        });
    
    }
});