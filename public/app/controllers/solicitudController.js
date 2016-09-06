app.controller('solicitudController',function ($scope,$http,API_URL) {

	$http.get(API_URL+"suministros/solicitudes/solicitudes")
        .success(function (response) {
            $scope.solicitudes = response;
        });


     $scope.estaProcesada = function(id){
     	$http.get(API_URL+'suministros/solicitudes/'+id)
        .success(function (response) {
        	
            $scope.getSolicitud = response.data;
        });
        console.log($scope.getSolicitud.estaprocesada);
     	if(true){
     		$scope.procesado = "";
     		$("#estaProcesada").removeClass("btn btn-info").addClass("btn btn-danger");
     		$("#estaProcesada i").addClass("fa fa-file-pdf-o");

     	} else{
     		$scope.procesado = "Procesar";
     		$("#estaProcesada").removeClass("btn btn-danger").addClass("btn btn-info");
     		$("#estaProcesada i").removeClass("fa fa-file-pdf-o");

     	}

     }
});