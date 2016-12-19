
app.controller('comprasImprimirController',  function($scope, $http, API_URL,$window) {
	$scope.idcompra = 0;
		 $scope.anularCompra = function(){	
		 
	        $http.get(API_URL + 'compras/anularCompra/' + $scope.idcompra).success(function(response) {
	        	 $scope.message = 'La compra se ha Anulado.';	        	 
	        	if(!response.success){        			
        			$scope.message = 'Ocurrio un error intentelo mas tarde';
        		}	
	        	$('#modalConfirmAnular').modal('hide'); 
	            $('#modalMessage').modal('show');
	            setTimeout("$('#modalMessage').modal('hide')",3000);
	            window.close();
	            
	        });
	    }
		 
		 $scope.imprimirCompra = function(){	
			 
		        $http.get(API_URL + 'compras/imprimirCompra/' + $scope.idcompra).success(function(response) {
		        	 $scope.message = 'La compra se ha cambiado de Estado.';	        	 
		        	if(!response.success){        			
	        			$scope.message = 'Ocurrio un error intentelo mas tarde';
	        		}	
		        	$('#modalConfirmAnular').modal('hide'); 
		            $('#modalMessage').modal('show');
		            setTimeout("$('#modalMessage').modal('hide')",3000);
		            window.close();
		        });
		    }
	 
	 
	 
    $scope.showModalConfirm = function(){       
            $('#modalConfirmAnular').modal('show');       
    }
    
    $scope.imprimir1 = function(){
    	$scope.idcompra = $('#idcompra').val();
    	window.print();
    	//$scope.showModalConfirm();      	
    
    }

        
});



