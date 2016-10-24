app.controller('recaudacionController', function($scope, $http, API_URL) {
    //retrieve employees listing from API
 
    $scope.ahora = new Date();
    
    $scope.initLoad = function(){
        $http.get(API_URL + "recaudacion/cobroagua/cuentas")
            .success(function(response) {
                $scope.estaVacio = response.length;
                $scope.cuentas = response;
        });
    }
    
    $scope.initLoad();

    
    $scope.generarFacturasPeriodo = function(){
       $http.get(API_URL + "recaudacion/cobroagua/generar")
            .success(function(response) {
                $scope.seGenero = response;
                $scope.initLoad();
        });
    }


    $scope.ingresoValores = function(numeroCuenta){
        var totalRubrosFijos = 0;
        var totalRubrosVariables = 0;
        var valorConsumo = 0;
        var valorExcedente = 0;
        var valorMesesAtrasados = 0;

        $http.get(API_URL + "recaudacion/cobroagua/cuentas/"+numeroCuenta)
            .success(function(response) {
                console.log(response[0]);
               $scope.cuenta = response[0];
               $scope.rubrosFijosCuenta = response[0].rubrosfijos;
               $scope.rubrosVariablesCuenta = response[0].rubrosvariables;
                angular.forEach($scope.rubrosFijosCuenta, function(rubroFijo,key){
                    totalRubrosFijos += parseFloat(rubroFijo.pivot.costorubro == null ?  0 : rubroFijo.pivot.costorubro);
                });

                angular.forEach($scope.rubrosVariablesCuenta, function(rubroVariable,key){
                    totalRubrosVariables += parseFloat(rubroVariable.pivot.costorubro  == null ?  0 : rubroVariable.pivot.costorubro);
                });

                valorConsumo = parseFloat($scope.cuenta.valorconsumo == null ?  0 : $scope.cuenta.valorconsumo);
                valorExcedente = parseFloat($scope.cuenta.valorexcedente == null ?  0 : $scope.cuenta.valorexcedente);
                valorMesesAtrasados = parseFloat($scope.cuenta.valormesesatrasados == null ?  0 : $scope.cuenta.valormesesatrasados);

                $scope.totalCuenta = totalRubrosFijos + totalRubrosVariables + valorConsumo + valorExcedente + valorMesesAtrasados;
                $scope.initLoad();
               $('#ingresarValores').modal('show');
            }).error(function(response){
                $scope.errorMessage = "Error al cargar la cuenta";
                $('#modalError').modal('show');
                setTimeout("$('#modalError').modal('hide')",3000);
            });    
    };

    $scope.guardarOtrosRubros = function(numeroCuenta){

        $http.get(API_URL + "recaudacion/cobroagua/cuentas/"+numeroCuenta)
            .success(function(response) {
               $scope.cuenta = response[0];
               var inputsRV = $(".rubrosVariables");
               var inputsRF = $(".rubrosFijos");
               
               $scope.rubrosFijosCuenta = $scope.cuenta.rubrosfijos;
               $scope.rubrosVariablesCuenta = $scope.cuenta.rubrosvariables;

               angular.forEach($scope.rubrosFijosCuenta, function(rubroFijo,key){
                    for(var i = 0; i<inputsRF.length; i++){
                        var nombreActual = $(inputsRF[i]).attr('id');
                        if(rubroFijo.nombrerubrovariable == nombreActual){
                            $scope.rubrosFijosCuenta[key].costorubro = $(inputsRV[i]).val();
                        }
                    }
               });

               angular.forEach($scope.rubrosVariablesCuenta, function(rubroVariable, key){
                    for(var i = 0; i<inputsRV.length; i++){
                        var nombreActual = $(inputsRV[i]).attr('id');
                        if(rubroVariable.nombrerubrovariable == nombreActual){
                            $scope.rubrosVariablesCuenta[key].costorubro = $(inputsRV[i]).val();
                        }

                    }
               });

               $http({
                    method: 'POST',
                    url: API_URL+'recaudacion/cobroagua/guardarrubros/'+numeroCuenta,
                    data: $.param($scope.cuenta),
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                }).success(function(response){
                    $scope.message = "Se guardaron los rubros de la cuenta";
                    $scope.initLoad();
                    $('#ingresarValores').modal('hide');
                    $('#modalConfirmacion').modal('show');
                    setTimeout("$('#modalConfirmacion').modal('hide')",3000);
                }).error(function(response){
                    $scope.ErrorMessage = "Error al guardar los rubros";
                    $('#modalError').modal('show');
                    setTimeout("$('#modalError').modal('hide')",3000);
                });
               
        
            });    
    }

    $scope.pagarFactura = function(numeroCuenta){
        console.log(API_URL + "recaudacion/cobroagua/cuentas/pagar/"+numeroCuenta);
        $http.get(API_URL + "recaudacion/cobroagua/cuentas/pagar/"+numeroCuenta)
            .success(function(response) {
                $scope.ingresoValores(numeroCuenta);
                $scope.message = "Se pagó la factura";
                $('#ingresarValores').modal('hide');
                $('#modalConfirmacion').modal('show');
                $scope.initLoad();
               // setTimeout("$('#modalConfirmacion').modal('hide')",3000);

        }).error(function(response){
            $scope.ErrorMessage = "Error al pagar la factura";
            $('#modalError').modal('show');
           // setTimeout("$('#modalError').modal('hide')",3000);
        });

    }

    $scope.fechasPeriodo = function(){

        fechaActual = new Date();
        yearActual = fechaActual.getFullYear();
        fechasCuenta = $scope.cuentas;
        alert(fechasCuenta);

    }

    $scope.generarPDF = function(numerocuenta){              
            window.open(API_URL+"recaudacion/cobroagua/cuentas/pdf/"+numerocuenta);
});



/*$scope.onKeyDown = function ($event) {
      $scope.onKeyDownResult = getKeyboardEventResult($event, "Key down");
    };

    var getKeyboardEventResult = function (keyEvent, keyEventDesc)
    {
      return keyEventDesc + " (keyCode: " + (window.event ? keyEvent.keyCode : keyEvent.which) + ")";
    };*/

    /*$scope.modalInformacionCuenta = function(numeroCuenta){
        $scope.cuenta = $scope.cuentas[numeroCuenta-1];
        $('#modalInfoCuenta').modal('show');

    };*/

    /*
    //save new record / update existing record
    $scope.save = function(modalstate, id) {
        var url = API_URL + "employees";
        
        //append employee id to the URL if the form is in edit mode
        if (modalstate === 'edit'){
            url += "/" + id;
        }
        
        $http({
            method: 'POST',
            url: url,
            data: $.param($scope.employee),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function(response) {
            console.log(response);
            location.reload();
        }).error(function(response) {
            console.log(response);
            alert('This is embarassing. An error has occured. Please check the log for details');
        });
    }

    //delete record
    $scope.confirmDelete = function(id) {
        var isConfirmDelete = confirm('Are you sure you want this record?');
        if (isConfirmDelete) {
            $http({
                method: 'DELETE',
                url: API_URL + 'employees/' + id
            }).
                    success(function(data) {
                        console.log(data);
                        location.reload();
                    }).
                    error(function(data) {
                        console.log(data);
                        alert('Unable to delete');
                    });
        } else {
            return false;
        }
    } */