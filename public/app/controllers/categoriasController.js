  app.controller('categoriasController', function($scope, $http, API_URL) {

    $scope.categorias = [];
    $scope.categoriasFiltro = [];
    $scope.button = false;    
    $scope.edit = -1;
    $scope.ordenarColumna = 'estaprocesada';
    
    $scope.searchByFilter = function(){

        var t_search = null;
        var t_catId = null;
        
        if($scope.search != undefined && $scope.search != ''){
            t_search = $scope.search;
            var last = t_search.substring(t_search.length -1);            
            if (last === "."){ 
            	t_search = t_search.substring(0,t_search.length -1);
            }
        }
        
        if($scope.idCategoria != undefined && $scope.idCategoria != ''){
            t_catId = $scope.idCategoria;            
        }

        var filter = {
            text: t_search,
            catId: t_catId 
        };

        $http.get(API_URL + 'categoria/getByFilter/' + JSON.stringify(filter)).success(function(response){
            $scope.categorias = response;            
            $scope.buttonSave = false;
            if($scope.categorias && $scope.categorias.constructor==Array && $scope.categorias.length==0){
            	$scope.buttonSave = true;
            }
        });
    }
   
    
    $scope.initLoad = function(){
    	$scope.searchByFilter();
        $http.get(API_URL + 'categoria/getCategoriasToFilter').success(function(response){
            $scope.categoriasFiltro = response;
           
        });
       
    }

    $scope.initLoad();
    
    $scope.valid = function(value){
    	$scope.buttonSave = true;    	
    	if(!value && !$scope.button){
    		$scope.buttonSave = false;
    	} 
    };
    
    $scope.addSubCategoria = function(nivel) {
    	$http.get(API_URL + 'categoria/lastSubCategoria/' + nivel ).success(function(response) {                		 
    		$scope.inserted = {
                    jerarquia: nivel + "." +response.lastId,
                    nombrecategoria: ''     
            };
    		if(response.lastId > 1){
    			nivel =   nivel + "." + (parseInt(response.lastId)-1);      
    		}    
    		$scope.edit = $scope.buscar($scope.categorias,nivel,'jerarquia')+1;    		
            $scope.categorias.splice($scope.edit,0,$scope.inserted);
            $scope.button = true;  
            $scope.buttonSave = true;           
           
        });    	
        
      };
      
      $scope.addCategoria = function(nivel) {

          $http.get(API_URL + 'categoria/lastCategoria/' + nivel).success(function(response) {
      		$scope.inserted = {
                      jerarquia: response.lastId,
                      nombrecategoria: ''     
              };  
              $scope.categorias.push($scope.inserted);
              $scope.button = true;
              $scope.buttonSave = true;
              $scope.edit = $scope.categorias.length - 1;
          });
          
      };
      
      
      $scope.buscar = function(listado, valor, indice){
    	for(var i = 0, len = listado.length; i < len; i++) {
    		   if (listado[i][indice] === valor) return i;
    	}
    	return -1;
    }
    		
      $scope.cancel = function(index) {
  	    $scope.categorias.splice(index, 1);
  	    $scope.button = false;
  	    $scope.edit = -1;
  	  };
      
      $scope.destroyCategoria = function(index) {
    	   
    	    $http.delete(API_URL + 'categoria/' + $scope.idcategoria_del).success(function(response) {
    	    	$scope.initLoad();
                $('#modalConfirmDelete').modal('hide');
                $scope.idcategoria_del = 0;
                $scope.message = 'Se elimino correctamente el Item seleccionado';
                $('#modalMessage').modal('show');
                setTimeout("$('#modalMessage').modal('hide')",3000);
            });
    	    
    	    
    	  };    
    	  
      $scope.saveCategoria = function(index) {
    		  categoria = $scope.categorias[index];
    		  $scope.button = false;  
    		  $scope.edit = -1;    		  
    		  var url = API_URL + "categoria";
    	        $http.post(url,categoria ).success(function (data) {
    	        	$scope.initLoad();
    	                $scope.message = 'Se insertó correctamente El Item';
    	                $('#modalMessage').modal('show');
    	                setTimeout("$('#modalMessage').modal('hide')",3000);
    	            }).error(function (res) {

    	            });        		  
      };
    	  
      $scope.showModalConfirm = function(id){
          $scope.idcategoria_del = id;
          $http.get(API_URL + 'categoria/getCategoriaTodelete/' + id).success(function(response) {              
        	  $scope.item_seleccionado = (response.nombrecategoria).trim();
        	  $scope.hijos = response.hijos;
              $('#modalConfirmDelete').modal('show');
          });
      }	  
    	    
    $scope.saveAllCategorias = function() {    	
    	$http.put(API_URL + 'categoria/update/' + JSON.stringify($scope.categorias)).success(function(response){
    		$scope.initLoad();
            $scope.message = 'Se Actualizaron Correctamente los Items.';
            $('#modalMessage').modal('show');
            setTimeout("$('#modalMessage').modal('hide')",3000);
        }).error(function (res) {

        });		       		  
  };
    
  
});
  
app.directive('focusMe', function () {
	    return {
	        link: function(scope, element, attrs) {
	            scope.$watch(attrs.focusMe, function(value) {
	                if(value === true) {
	                    element[0].focus();
	                    element[0].select();
	                }
	            });
	        }
	    };
	});

