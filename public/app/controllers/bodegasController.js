//var appAu = angular.module('softver-aqua-auto', ['angucomplete-alt','softver-aqua']);
app.controller('bodegasController',  function($scope, $http, API_URL) {

    $scope.bodegas = [];
    $scope.bodega_del = 0;    
    $scope.ciudades = $scope.ciudadesFiltro = [];
    $scope.sectores = $scope.sectoresFiltro  =[];
    $scope.personas = [];
    $scope.testObj = [];

    $scope.select_cuenta = null;
    
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

                    $scope.idplancuenta = '';
                    $scope.select_cuenta = null;

	                $scope.$broadcast('angucomplete-alt:clearInput');
	                $('#modalAction').modal('show');
                });
                
                break;
            case 'edit':
                $scope.form_title = "Editar Bodega No ";
                $scope.id = id;

                $http.get(API_URL + 'bodega/'  + id ).success(function(response){

                    console.log(response);

                	$scope.bodega = response;

                	var idparroquia = response.idparroquia;

                	$http.get(API_URL + 'bodega/getEmpleadoByBodega/' + $scope.bodega.idbodega).success(function(response){
                		$scope.empleado = response;	                                   		     
	                }); 
                	/*$http.get(API_URL + 'bodega/getProvincias').success(function(response){
	                    $scope.provincias = response;                   
	                });  
                	$scope.provincia = parseInt($scope.bodega.idprovincia);
	                $scope.loadCiudad($scope.provincia,false);
	                $scope.ciudad = parseInt($scope.bodega.idcanton);
	                $scope.loadSector($scope.ciudad,false,$scope.bodega.idparroquia );*/

                    $http.get(API_URL + 'bodega/getProvincias').success(function(response){
                        $scope.provincias = response;
                        $scope.provincia = parseInt($scope.bodega.idprovincia);

                        $http.get(API_URL + 'bodega/getCiudad/' + $scope.provincia).success(function(response){
                            $scope.ciudades = response;
                            $scope.ciudad = parseInt($scope.bodega.idcanton);
                            $scope.sectores = [];

                            $http.get(API_URL + 'bodega/getSector/' + $scope.ciudad).success(function(response){
                                $scope.sectores = response;
                                $scope.bodega.idparroquia = idparroquia;
                            });

                        });

                    });

                    $scope.select_cuenta = {
                        idplancuenta: $scope.bodega.idplancuenta,
                        concepto: $scope.bodega.concepto
                    };

                    $scope.idplancuenta = $scope.bodega.concepto;

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
    	if(padre != ''){
    		$http.get(API_URL + 'bodega/getCiudad/' + padre).success(function(response){
            	if(filtro){
            		$scope.searchByFilter();
            		$scope.ciudadesFiltro = response; 
            		$scope.ciudadFiltro = "";
            		$scope.sectorFiltro = "";
            	}else{
            		$scope.ciudades = response; 
            		$scope.sectores = []
            	}
            	         
            });
    	} else {
    		$scope.sectores = []
    		if(filtro){
        		
        		$scope.ciudadesFiltro = []; 
        		$scope.sectoresFiltro = [];
        		$scope.ciudadFiltro = "";
        		$scope.sectorFiltro = "";
        		$scope.searchByFilter();
        	}
    	}      
    }
    
    $scope.loadSector = function(padre,filtro,value) {
    	if(padre != ''){
        $http.get(API_URL + 'bodega/getSector/' + padre).success(function(response){
        	if(filtro){
        		$scope.searchByFilter();
        		$scope.sectoresFiltro = response;
        		$scope.sectorFiltro = "";
        	}else{
        		$scope.sectores = response;
        		$scope.bodega.idparroquia = value;
        	}
        });  
    	} else {
    		$scope.sectores = []
    		if(filtro){   			
        		$scope.sectoresFiltro = [];
        		$scope.sectorFiltro = "";
        		$scope.searchByFilter();
        	}
    	}      
    }
    
    
    $scope.save = function(modalstate, id) {
    	$scope.bodega.idempleado = $scope.testObj.originalObject.idempleado;
        $scope.bodega.idplancuenta = $scope.select_cuenta.idplancuenta;

    	console.log($scope.bodega);
    	
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
       
        $http.get(API_URL + 'bodega/'  + id).success(function(response) {
            $scope.bodega = response;     
           
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
    
    
    $scope.eliminarBodega = function(){
        $http.delete(API_URL + 'bodega/' + $scope.bodega_del).success(function(response) {
            if(response.success == true){
                $scope.initLoad();
                $('#modalConfirmDelete').modal('hide');
                $scope.bodega_del = 0;
                $scope.message = 'Se eliminó correctamente la Bodega seleccionada...';
                $('#modalMessage').modal('show');
                $scope.hideModalMessage();

            } else {
                $scope.message_error = 'La bodega no puede ser eliminado porque esta asignada...';
                $('#modalMessageError').modal('show');
                $('#modalConfirmDelete').modal('hide');
            }
        });

    };

    $scope.showPlanCuenta = function () {

        $http.get(API_URL + 'empleado/getPlanCuenta').success(function(response){
            $scope.cuentas = response;
            $('#modalPlanCuenta').modal('show');
        });

    };

    $scope.selectCuenta = function () {
        var selected = $scope.select_cuenta;

        $scope.idplancuenta = selected.concepto;

        $('#modalPlanCuenta').modal('hide');
    };

    $scope.click_radio = function (item) {
        $scope.select_cuenta = item;
    };

    $scope.hideModalMessage = function () {
        setTimeout("$('#modalMessage').modal('hide')", 3000);
    };
    
});

function defaultImage (obj){
	obj.src = 'img/empleado.png';
}

