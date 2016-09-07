app.controller('solicitudController',function ($scope,$http,API_URL) {

    $scope.ahora = new Date();



	$http.get(API_URL+"suministros/solicitudes/solicitudes")
        .success(function (response) {
            $scope.solicitudes = response;
        });

    $scope.modalNuevaSolicitudCliente = function(documento){
       $http.get(API_URL+'clientes/gestion/'+documento)
        .success(function (response) {
            $scope.clienteActual = response.data;
        });
        $('#nueva-solicitud-cliente').modal('show');
    }

    $scope.modalNuevaSolicitud = function(documento){
       $http.get(API_URL+'clientes/gestion/'+documento)
        .success(function (response) {
            $scope.clienteActual = response.data;
        });
        $('#nueva-solicitud').modal('show');
    }

     $scope.estaProcesada = function(id){
     	$http.get(API_URL+'suministros/solicitudes/'+id)
        .success(function (response) {
        	$scope.getSolicitud = response.data;
        });
       // console.log($scope.estaProcesada);
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
     
      $scope.save = function() {
        var url = API_URL + "clientes/gestion/guardarcliente";    
        
        $http({
            method: 'POST',
            url: url,
            data: $.param($scope.solicitud.cliente),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function(response) {
             guardarSolicitud();
        }).error(function(response) {
           
        });
    }

    guardarSolicitud = function(){
        var url = API_URL + "suministros/solicitudes/nueva";    
        
        $http({
            method: 'POST',
            url: url,
            data: $.param($scope.solicitud),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function(response) {
             $scope.message = 'Se ingreso correctamente la solicitud';
             $('#modalMessage').modal('show');
        }).error(function(response) {
           
        });
    }

});