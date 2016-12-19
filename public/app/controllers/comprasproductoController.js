var appPage = angular.module('softver-erp-page', ['angularUtils.directives.dirPagination','softver-erp']);

appPage.controller('comprasproductoController',  function($scope, $http, API_URL) {

    $scope.compras = [];   
    $scope.proveedores = [];    
   
    $scope.compra_anular = 0;
    
    $scope.searchByFilter = function(){

        var t_search = null;
        var t_proveedorId = null;
        var t_estado = null;
        
        if($scope.search != undefined && $scope.search != ''){
            t_search = $scope.search;
            var last = t_search.substring(t_search.length -1);            
            if (last === "."){ 
            	t_search = t_search.substring(0,t_search.length -1);
            }
        }
        
        if($scope.proveedorFiltro != undefined && $scope.proveedorFiltro != ''){
        	t_proveedorId = $scope.proveedorFiltro;            
        }
        
        if($scope.estadoFiltro != undefined && $scope.estadoFiltro != ''){
        	t_estado = $scope.estadoFiltro;            
        }
        
        var filter = {
            text: t_search,
            proveedorId: t_proveedorId,
            estado: t_estado
        };

        $http.get(API_URL + 'compras/getCompras/' + JSON.stringify(filter)).success(function(response){
            $scope.compras = response;            
        });
    }
    
    $scope.initLoad = function(){
    	$scope.searchByFilter();
        $http.get(API_URL + 'compras/getProveedores').success(function(response){
            $scope.proveedoresFiltro = response;
            $scope.estados = [{id: 1, nombre: "Pagado"},{id: 0, nombre: "No Pagado"}]
           
        });
       
    }
    
    $scope.initLoad();   
    
    $scope.sort = function(keyname){
        $scope.sortKey = keyname;   
        $scope.reverse = !$scope.reverse; 
    }
    
    $scope.sumar = function(v1,v2){
    	return $scope.roundToTwo(parseFloat(v1) + parseFloat(v2)).toFixed(2);
    }
    
    $scope.roundToTwo = function (num) {    
	    return +(Math.round(num + "e+2")  + "e-2");
	}
    
    
    
    $scope.anularCompra = function(){	
    	var anular = $scope.compra_anular;
	        $http.get(API_URL + 'compras/anularCompra/' + $scope.compra_anular).success(function(response) {
	        	$scope.initLoad(); 
	        	$scope.compra_anular = 0;
	        	$scope.message = 'La compra se ha Anulado.';	        	 
	        	if(!response.success){
	        		$scope.compra_anular = anular;
       			$scope.message = 'Ocurrio un error intentelo mas tarde';
       		}	
	        	$('#modalConfirmAnular').modal('hide'); 
	            $('#modalMessage').modal('show');
	            setTimeout("$('#modalMessage').modal('hide')",3000);
	        });
	    }
	 
   $scope.showModalConfirm = function(id){   
	   $scope.compra_anular = id;
           $('#modalConfirmAnular').modal('show');       
   }
   
   $scope.formatoFecha = function(fecha){
	      	if(typeof fecha != 'undefined'){
	       		var t = fecha.split('-');
	           	var meses = ['ene','feb','mar','abr','may','jun','jul','ago','sep','oct','nov','dic'];
	               return t[2] + '-' + meses[t[1]-1] + '-' + t[0];
	       	} else {
	       		return '';
	       	}
	       	
	       }
   
   $scope.loadPage = function(id){
	   window.open('compras/formulario/'+id, '_blank');
   }
    
});

function defaultImage (obj){
	obj.src = 'img/empleado.png';
}

