//var appAu = angular.module('softver-aqua-auto', ['angucomplete-alt','softver-aqua']);
app.controller('bodegasController',  function($scope, $http, API_URL) {

    $scope.bodegas = [];
    $scope.bodega_del = 0;    
    $scope.ciudades = $scope.ciudadesFiltro = [];
    $scope.sectores = $scope.sectoresFiltro  =[];
    $scope.personas = [];
    $scope.testObj = [];
    
    
    $scope.searchStr = [];
    
    $scope.searchByFilter = function(){

        var t_search = null;
        var t_provinciaId = null;
        var t_ciudadId = null;
        var t_sectorId = null;
        
        if($scope.search != undefined && $scope.search != ''){
            t_search = $scope.search;
            var last = t_search.substring(t_search.length -1);            
            if (last === "."){ 
            	t_search = t_search.substring(0,t_search.length -1);
            }
        }
        
        if($scope.provinciaFiltro != undefined && $scope.provinciaFiltro != ''){
        	t_provinciaId = $scope.provinciaFiltro;            
        }
        
        if($scope.ciudadFiltro != undefined && $scope.ciudadFiltro != ''){
        	t_ciudadId = $scope.ciudadFiltro;            
        }
        
        if($scope.sectorFiltro != undefined && $scope.sectorFiltro != ''){
        	t_sectorId = $scope.sectorFiltro;            
        }

        var filter = {
            text: t_search,
            provinciaId: t_provinciaId,
            ciudadId: t_ciudadId,
            sectorId: t_sectorId
        };

        $http.get(API_URL + 'bodega/getBodegas/' + JSON.stringify(filter)).success(function(response){
            $scope.bodegas = response;            
        });
    }
    
    $scope.initLoad = function(){
    	$scope.searchByFilter();
        $http.get(API_URL + 'bodega/getProvincias').success(function(response){
            $scope.provinciasFiltro = response;
           
        });
       
    }
    
    $scope.initLoad();   
    
    
    
    $scope.toggle = function(modalstate, id) {
        $scope.modalstate = modalstate;
        $scope.lineas = [];
        $scope.sublineas = [];
        switch (modalstate) {
            case 'add':
            	
            	$scope.form_title = "Nueva Bodega No ";                
                $http.get(API_URL + 'bodega/getLastBodega' )
                .success(function(response) {
                	$scope.bodega = response;                      	
	                $http.get(API_URL + 'bodega/getProvincias').success(function(response){
	                    $scope.provincias = response;                   
	                }); 
	                $scope.provincia = '';
	                $scope.ciudad = '';
	                $('#modalAction').modal('show');
                });

                break;
            case 'edit':
                $scope.form_title = "Editar Bodega No ";
                $scope.id = id;

                $http.get(API_URL + 'bodega/'  + id ).success(function(response){
                	$scope.bodega = response;     
                	$http.get(API_URL + 'bodega/getEmpleadoByBodega/' + $scope.bodega.idbodega).success(function(response){
                		$scope.empleado = response;	                                   		     
	                }); 
                	$http.get(API_URL + 'bodega/getProvincias').success(function(response){
	                    $scope.provincias = response;                   
	                });  
                	$scope.provincia = parseInt($scope.bodega.idprovincia);
	                $scope.loadCiudad($scope.provincia,false);
	                $scope.ciudad = parseInt($scope.bodega.idciudad);
	                $scope.loadSector($scope.ciudad,false);	  
	               
	                 $('#modalAction').modal('show');

                });

                break;

            case 'info':
            	 $scope.empleado = null;
            	 $scope.rutafoto = null;
            	$http.get(API_URL + 'bodega/getEmpleadoByBodega/'  + id ).success(function(response){
                	    $scope.empleado = response;  
                	    $scope.rutafoto = $scope.empleado.rutafoto;
                	    if($scope.rutafoto == null){
                	    	$scope.rutafoto = 'none';
                	    }
                        $('#modalInfoEmpleado').modal('show');
                    });

                break;

            default:
                break;
        }
     }
    
    $scope.loadCiudad = function(padre,filtro) {	
    	
        $http.get(API_URL + 'bodega/getCiudad/' + padre).success(function(response){
        	if(filtro){
        		$scope.searchByFilter();
        		$scope.ciudadesFiltro = response; 
        	}else{
        		$scope.ciudades = response; 
        	}
        	         
        });
    }
    
    $scope.loadSector = function(padre,filtro) {
    	
        $http.get(API_URL + 'bodega/getSector/' + + padre).success(function(response){
        	if(filtro){
        		$scope.searchByFilter();
        		$scope.sectoresFiltro = response;
        	}else{
        		$scope.sectores = response;
        	}
        });       
    }
    
    
    $scope.save = function(modalstate, id) {
    	$scope.bodega.idempleado = $scope.testObj.originalObject.idempleado;    	
    	
    	var url = API_URL + "bodega";

        if (modalstate === 'edit'){
            url += "/" + id;
        }
        
        if (modalstate === 'add'){
            $http.post(url,$scope.bodega ).success(function (data) {
                $scope.initLoad();
                $('#modalAction').modal('hide');
                $scope.message = 'Se insertó correctamente el Item';
                $('#modalMessage').modal('show');
                setTimeout("$('#modalMessage').modal('hide')",3000);

            });
        } else {
        	delete $scope.bodega.idprovincia;
        	delete $scope.bodega.idciudad;
            $http.put(url, $scope.bodega ).success(function (data) {
                $scope.initLoad();
                $('#modalAction').modal('hide');
                $scope.message = 'Se edito correctamente el Item seleccionado';
                $('#modalMessage').modal('show');
                setTimeout("$('#modalMessage').modal('hide')",3000);
            });
            
        }	  	
    	
    }

    

    $scope.showModalConfirm = function(id,option){
        $scope.bodega_del = id;
        $scope.option = option;
        $scope.mensaje = 'Anular';
        $http.get(API_URL + 'bodega/getEmpleadoByBodega/'  + id).success(function(response) {
            $scope.empleado = response;     
            if(option == 1){
            	$scope.mensaje = 'Activar';
            }
            $('#modalConfirmDelete').modal('show');
        });
    }
    
    $scope.anularBodega = function(){
    	
    	var param = {
                id: $scope.bodega_del,
                estado: $scope.option
            }; 	
    	var mens = 'Anulo';
    	if($scope.option==1){
    		mens = 'Activo';
    	}
    	
        $http.get(API_URL + 'bodega/anularBodega/' + JSON.stringify(param)).success(function(response) {
            $scope.initLoad();
            $('#modalConfirmDelete').modal('hide');
            $scope.bodega_del = 0;
            $scope.message = 'Se '+ mens +' correctamente el Item seleccionado';
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
	obj.src = 'img/empleado.png';
}

