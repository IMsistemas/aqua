app.controller('solicitudController',function ($scope,$http,API_URL) {

    $scope.ahora = new Date();

	$http.get(API_URL+"suministros/solicitudes/solicitudes")
        .success(function (response) {
            $scope.solicitudes = response;
        });

    $scope.getCliente = function(documento){
        $http.get(API_URL+'clientes/gestion/'+documento)
        .success(function (response) {
            $scope.getSolicitud = response.data;
        });
    }

     $scope.estaProcesada = function(id){
     	$http.get(API_URL+'suministros/solicitudes/'+id)
        .success(function (response) {
        	$scope.getSolicitud = response.data;
        });
        console.log($scope.estaProcesada);
       // console.log($scope.getSolicitud.estaprocesada);
     	if(true){
     		$scope.procesado = "";
     		$scope.estoyProcesada = "procesada";
     		$("#estaProcesada").removeClass("btn btn-info").addClass("btn btn-danger");
     		$("#estaProcesada i").addClass("fa fa-file-pdf-o");

     	} else{
     		$scope.procesado = "Procesar";
     		$scope.estoyProcesada = "En espera";
     		$("#estaProcesada").removeClass("btn btn-danger").addClass("btn btn-info");
     		$("#estaProcesada i").removeClass("fa fa-file-pdf-o");
     	}
     }
});