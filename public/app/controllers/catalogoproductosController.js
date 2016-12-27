//var appUp = angular.module('softver-aqua-upload', ['ngFileUpload','softver-aqua']);


app.controller('catalogoproductosController',  function($scope, $http, API_URL,Upload) {

    $scope.productos = [];
    $scope.producto_del = 0;
    $scope.lineas = $scope.lineasFiltro = [];
    $scope.sublineas = $scope.sublineasFiltro  =[];   
    
    $scope.searchByFilter = function(){
    
        var t_search = null;
        var t_catId = null;
        var t_lineaId = null;
        var t_subId = null;
        
        if($scope.search != undefined && $scope.search != ''){
            t_search = $scope.search;
            var last = t_search.substring(t_search.length -1);            
            if (last === "."){ 
            	t_search = t_search.substring(0,t_search.length -1);
            }
        }
        
        if($scope.categoriaFiltro != undefined && $scope.categoriaFiltro != ''){
            t_catId = $scope.categoriaFiltro;            
        }
        
        if($scope.lineaFiltro != undefined && $scope.lineaFiltro != ''){
            t_lineaId = $scope.lineaFiltro;            
        }
        
        if($scope.idCategoria != undefined && $scope.idCategoria != ''){
            t_subId = $scope.idCategoria;            
        }

        var filter = {
            text: t_search,
            catId: t_catId,
            linId: t_lineaId,
            subId: t_subId
        };
        $scope.productos = [];
        $http.get(API_URL + 'catalogoproducto/getCatalogoProductos/' + JSON.stringify(filter)).success(function(response){
            $scope.productos = response;            
        });
    }
    
    $scope.initLoad = function(){
    	$scope.searchByFilter();
        $http.get(API_URL + 'catalogoproducto/getCategoriasToFilter').success(function(response){
            $scope.categoriasFiltro = response;
           
        });
       
    }
    
    $scope.initLoad();   
    $scope.toggle = function(modalstate, id) {
        $scope.modalstate = modalstate;
        $scope.lineas = [];
        $scope.sublineas = [];
        $scope.formProducto.$setPristine();
        $scope.formProducto.$setUntouched();        
        
        switch (modalstate) {
            case 'add':            	
            	$scope.form_title = "Nuevo Producto No ";                
                $http.get(API_URL + 'catalogoproducto/getLastCatalogoProducto' )
                .success(function(response) {
                	$scope.producto = response; 
                	$scope.rutafoto = '';
                	$('#fotoPre').attr('src', '');
	                $http.get(API_URL + 'catalogoproducto/getCategoriasToFilter').success(function(response){
	                    $scope.categorias = response;                   
	                }); 
	                $scope.categoria = '';
	                $scope.linea = '';
	                $('#modalAction').modal('show');
                });

                break;
            case 'edit':
                $scope.form_title = "Editar Producto No ";                
                $scope.id = id;
                $scope.producto = null;              
                $http.get(API_URL + 'catalogoproducto/'  + id ).success(function(response){
                	$scope.producto = response;    
                	$scope.rutafoto = $scope.producto.rutafoto;
                	$http.get(API_URL + 'catalogoproducto/getCategoriasToFilter').success(function(response){
	                    $scope.categorias = response;                   
	                }); 
                	var ids = $scope.producto.idcategoria.split('.');
                	$scope.categoria = ids[0];
	                $scope.linea = ids[0] + '.' + ids[1];
                	
	                $scope.loadLinea($scope.categoria,false);
	                $scope.loadSublinea($scope.categoria,false);
	                         
	                 $('#modalAction').modal('show');

                });

                break;

            case 'info':
            	             	
            	$http.get(API_URL + 'catalogoproducto/'  + id ).success(function(response){                	
                        $scope.nombreproducto = response.nombreproducto;
                        $scope.fechaingreso = response.fechaingreso;
                        $scope.rutafoto = response.rutafoto;
                        var ids = response.idcategoria.split('.');                    	
    	                
    	                $http.get(API_URL + 'categoria/'  + ids[0]).success(function(response){
    	                	$scope.categoria = response.nombrecategoria;
    	                });
    	                $http.get(API_URL + 'categoria/'  + ids[0] + '.' + ids[1]).success(function(response){
    	                	$scope.linea = response.nombrecategoria;
    	                });
    	                $http.get(API_URL + 'categoria/'  + response.idcategoria).success(function(response){
    	                	$scope.sublinea = response.nombrecategoria;
    	                });

                        $('#modalInfoEmpleado').modal('show');
                    });

                break;

            default:
                break;
        }
     }
    
    $scope.loadLinea = function(padre,filtro) {
    	var filter = {
                padre: padre,
                nivel: 2
            };
    	
        $http.get(API_URL + 'catalogoproducto/getCategoriasHijas/' + JSON.stringify(filter)).success(function(response){
        	if(filtro){
        		$scope.searchByFilter();
        		$scope.lineasFiltro = response; 
        	}else{
        		$scope.lineas = response; 
        	}
        	         
        });
    }
    
    $scope.loadSublinea = function(padre,filtro) {
    	var filter = {
                padre: padre,
                nivel: 3
            };
    	
        $http.get(API_URL + 'catalogoproducto/getCategoriasHijas/' + JSON.stringify(filter)).success(function(response){
        	if(filtro){
        		$scope.searchByFilter();
        		$scope.sublineasFiltro = response;
        	}else{
        		$scope.sublineas = response;
        	}
        });       
    }
    

    $scope.save = function(modalstate, id) {

        var url = API_URL + "catalogoproducto";
        if (modalstate === 'edit'){
            url += "/" + id;
            $scope.producto._method= 'PUT'; 
        }    	       	
        	
        $scope.upload = Upload.upload({
      	      url: url,
      	      data: $scope.producto,   
      	      
      	}).success(function(data, status, headers, config) {
      	    	$scope.initLoad();
      	    	$scope.message = 'El item se almaceno correctamente.';
              	$('#modalAction').modal('hide');
              	$('#modalMessage').modal('show');
              	setTimeout("$('#modalMessage').modal('hide')",3000);
       });
        
    }

   

    $scope.showModalConfirm = function(id){
        $scope.empleado_del = id;
        $http.get(API_URL + 'catalogoproducto/'  + id).success(function(response) {
            $scope.producto = response;
            $('#modalConfirmDelete').modal('show');
        });
    }

    $scope.destroyProducto = function(){
        $http.delete(API_URL + 'catalogoproducto/' + $scope.empleado_del).success(function(response) {
            $scope.initLoad();
            $('#modalConfirmDelete').modal('hide');
            $scope.empleado_del = 0;
            $scope.message = 'Se elimino correctamente el Item seleccionado';
            $('#modalMessage').modal('show');
            setTimeout("$('#modalMessage').modal('hide')",3000);
        });
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
    
});

function defaultImage (obj){
	obj.src = 'img/producto.png';
}

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#fotoPre').attr('src', e.target.result);
            console.log(e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}

$("#foto").change(function () {
    readURL(this);
});

$("#fotoedit").change(function () {
    readURL(this);
});

