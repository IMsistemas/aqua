app.controller('esperaController',function ($scope,$http,API_URL) {
    $scope.solicitudes=[];
    $scope.ahora = new Date();
    $scope.initLoad = function(){
            $http.get(API_URL+"suministros/espera/espera")
            .success(function (response) {
            $scope.solicitudes = response;
            console.log($scope.solicitudes);
            $scope.cantidadSolicitudes = $scope.solicitudes.length;
            var fecha = $scope.solicitudes.sort(function(a,b){
                return (new Date(a.fechasolicitud) - new Date(b.fechasolicitud));
            });
        });
    }
    $scope.initLoad();

    $scope.ordenarColumna = 'estaprocesada';

	$scope.modalVerSolicitud = function(id){
         $http.get(API_URL+"suministros/solicitudes/"+id)
        .success(function (response) {
            $scope.solicitud = response[0];
            $('#modalInfoSolicitud').modal('show');
        });

    }
    
    $scope.modalNuevaSolicitud = function(){
        $('#nueva-solicitud').modal('show');
    }


    $scope.modalProcesaSolicitud = function(id){
        $http.get(API_URL+"suministros/solicitudes/"+id)
        .success(function (response) {

            $http.get(API_URL+"suministros/suministros")
        .success(function (response) {
            $scope.suministros = response;
            $scope.cantidadSuministros = $scope.suministros.length;
        });

        $http.get(API_URL+"tarifas/tarifas")
            .success(function (response) {
                $scope.tarifas = response;
            });

         $http.get(API_URL+"barrios/gestion/concalles")
            .success(function (response) {
                $scope.barrios = response;
            });

        $http.get(API_URL+"configuracion/configuracion")
            .success(function (response) {
                $scope.configuracion = response[0];
                $scope.nDividendos = [];
                $scope.acometida = (parseInt($scope.configuracion.aaguapotable) + parseInt($scope.configuracion.alcantarillado));
                for(var i = 1; i<=$scope.configuracion.dividendos; i++ ){
                    $scope.nDividendos[i] = i;
                }
                
            });

         $http.get(API_URL+"suministros/productos")
            .success(function (response) {
                $scope.producto = response[0];
            });


            $scope.procesarSolicitud = response[0];
            $('#procesar-solicitud').modal('show');
        });
    }

    $scope.procesaSolicitud = function(id) {

        $scope.suministro.cliente = $scope.procesarSolicitud.cliente;
        $scope.suministro.direccionsuministro = $scope.procesarSolicitud.direccionsuministro;
        $scope.suministro.telefonosuministro = $scope.procesarSolicitud.telefonosuministro;
        $scope.suministro.producto = $scope.producto;

        var url = API_URL +"suministros/solicitudes/procesar/"+id;    
        $http({
            method: 'POST',
            url: url,
            data: $.param($scope.suministro),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function(response) {
             ingresarSuministro();
        }).error(function(response) {
            $scope.messageError = 'Error al procesar solicitud';
           $('#modalMessageError').modal('show');           
        });        
    }

    ingresarSuministro = function(){
        var url = API_URL +"suministros/nuevo";    
         $http({
            method: 'POST',
            url: url,
            data: $.param($scope.suministro),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function(response) {
             $scope.initLoad();
             $scope.message = 'Solicitud procesada con exito';
             $('#procesar-solicitud').modal('hide');
             $('#modalMessage').modal('show');
        }).error(function(response) {
            $scope.messageError = 'Error al ingresar el suministro';
            $('#modalMessage').modal('hide');
           $('#modalMessageError').modal('show');           
        }); 
    }


     
      $scope.guardarNuevoCliente = function() {
        var url = API_URL + "clientes/gestion/guardarcliente";    
        
        $http({
            method: 'POST',
            url: url,
            data: $.param($scope.solicitud.cliente),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function(response) {
             guardarSolicitud();
        }).error(function(response) {
            $scope.messageError = 'Error al ingresar el cliente';
           $('#modalMessageError').modal('show');           
        });
    }

    guardarSolicitud = function(){
        var url = API_URL + "suministros/solicitudes/nueva/solicitud";    
        
        $http({
            method: 'POST',
            url: url,
            data: $.param($scope.solicitud),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function(response) {
             $scope.initLoad();
             $scope.message = 'Se ingreso correctamente la solicitud';
             $('#modalMessage').modal('show');
             $('#nueva-solicitud').modal('hide');

             
        }).error(function(response) {
            $scope.messageError = 'Error al ingresar la solicitud';
            $('#modalMessage').modal('hide');
            $('#modalMessageError').modal('show');
        });
    }


    $scope.modalEliminarSolicitud = function(id){
        $scope.solicitudSeleccionada = id;
        $('#modalConfirmDelete').modal('show');
        
    }


    $scope.eliminarSolicitud = function(){
        
      $http({
                method: 'POST',
                url: API_URL + 'suministros/solicitudes/eliminar/' + $scope.solicitudSeleccionada,
                data: $.param($scope.solicitudSeleccionada),
            }).success(function(data) {
                 $scope.initLoad();
             $scope.message = 'Solicitud eliminada con exito';
             $('#modalConfirmDelete').modal('hide');
             $('#modalMessage').modal('show');
                    
            }).error(function(data) {
                $scope.messageError = 'Error al eliminar a solicitud';
                $('#modalMessageError').modal('show'); 
                  
            });
    }

});

/* $scope.modalNuevaSolicitudCliente = function(documento){
       $http.get(API_URL+'clientes/gestion/'+documento)
        .success(function (response) {
            $scope.clienteActual = response.data;
        });
        $('#nueva-solicitud-cliente').modal('show');
    }*/