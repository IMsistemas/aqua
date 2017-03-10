//var appUp = angular.module('softver-aqua-upload', ['ngFileUpload','softver-aqua']);


app.controller('catalogoproductosController',  function($scope, $http, API_URL,Upload,$timeout) {

    $scope.producto_del = 0;    
    $scope.items = [];
    $scope.select_cuenta = null;
    $scope.lineas = $scope.sublineasFiltro  =[];   
    $scope.select_cuentaC = null;
    $scope.opcion = 0;
    
    $scope.searchByFilter = function(){
    
        var t_search = null;
        var t_lineaId = null;
        var t_subId = null;
        
        if($scope.search != undefined && $scope.search != ''){
            t_search = $scope.search;
            var last = t_search.substring(t_search.length -1);            
            if (last === "."){ 
            	t_search = t_search.substring(0,t_search.length -1);
            }
        }
        
        if($scope.idCategoria != undefined && $scope.idCategoria != ''){
            t_subId = $scope.idCategoria;            
        }

        var filter = {
            text: t_search,
            subId: t_subId
        };
        $scope.productos = [];
        $http.get(API_URL + 'catalogoproducto/getCatalogoProductos/' + JSON.stringify(filter)).success(function(response){
            $scope.items = response;            
        });
    }
    
    $scope.initLoad = function(){
    	$scope.searchByFilter();
        $http.get(API_URL + 'catalogoproducto/getCategoriasToFilter').success(function(response){
            $scope.lineasFiltro = response;
           
        });
       
    }
    
    $scope.initLoad();   
	
	
	
	
	
	
	
    

/*
    $scope.pageChanged = function(newPage) {
        $scope.initLoad(newPage);
    };

    $scope.initLoad = function(pageNumber){

        if ($scope.busqueda == undefined) {
            var search = null;
        } else var search = $scope.busqueda;

        var filtros = {
            search: search
        };

        $http.get(API_URL + 'catalogoproducto/getCatalogoItems?page=' + pageNumber + '&filter=' + JSON.stringify(filtros)).success(function(response){
            $scope.items = response.data;
            $scope.totalItems = response.total;
        });
    };

    $scope.initLoad(1);
*/

    $scope.toggle = function(modalstate, id) {

    	$scope.modalstate = modalstate;
    	$scope.formProducto.$setPristine();
        $scope.formProducto.$setUntouched(); 
        switch (modalstate) {
            case 'add':
            	$scope.thumbnail = {
        	        dataUrl: ''
        	    };
                $scope.form_title = 'Nuevo Item';
                $scope.producto = null;
                $scope.t_cuentacontableingreso = '';
                $scope.t_cuentacontable = '';
                $http.get(API_URL + 'catalogoproducto/getLastCatalogoProducto' )
                .success(function(response) {
                	$scope.producto = response; 
                	$http.get(API_URL + 'catalogoproducto/getTipoItem').success(function(response){

                        var longitud = response.length;
                        var array_temp = [{label: '-- Seleccione --', id: ''}];
                        for(var i = 0; i < longitud; i++){
                            array_temp.push({label: response[i].nameclaseitem, id: response[i].idclaseitem})
                        }
                        $scope.tipo = array_temp;
                        $scope.producto.idclaseitem = '';

                    });

                    $http.get(API_URL + 'catalogoproducto/getImpuestoICE').success(function(response){

                        var longitud = response.length;
                        var array_temp = [{label: '-- Seleccione --', id: ''}];
                        for(var i = 0; i < longitud; i++){
                            array_temp.push({label: response[i].nametipoimpuestoice, id: response[i].idtipoimpuestoice})
                        }
                        $scope.imp_ice = array_temp;
                        $scope.producto.idtipoimpuestoice = '';

                    });

                    $http.get(API_URL + 'catalogoproducto/getImpuestoIVA').success(function(response){

                        var longitud = response.length;
                        var array_temp = [{label: '-- Seleccione --', id: ''}];
                        for(var i = 0; i < longitud; i++){
                            array_temp.push({label: response[i].nametipoimpuestoiva, id: response[i].idtipoimpuestoiva})
                        }
                        $scope.imp_iva = array_temp;
                        $scope.producto.idtipoimpuestoiva = '';

                    });
                    $scope.sublineas = [{label: '-- Seleccione --', id: ''}];
                     
                    $http.get(API_URL + 'catalogoproducto/getCategoriasToFilter').success(function(response){
                    	var longitud = response.length;
                        var array_temp = [{label: '-- Seleccione --', id: ''}];
                        for(var i = 0; i < longitud; i++){
                            array_temp.push({label: response[i].nombrecategoria, id: response[i].jerarquia})
                        }
                        $scope.lineas = array_temp;
                        $scope.s_linea = ''; 
                        $scope.producto.idcategoria = ''; 
                        $('#modalAction').modal('show');
                    });
                });

                

                break;
            case 'edit':

            	$scope.form_title = "Editar Producto";                
                $scope.id = id;
                $scope.producto = null;              
                $http.get(API_URL + 'catalogoproducto/'  + id ).success(function(response){
                  	$scope.producto = response;    	
 
                	$http.get(API_URL + 'catalogoproducto/getTipoItem').success(function(response){

                        var longitud = response.length;
                        var array_temp = [{label: '-- Seleccione --', id: ''}];
                        for(var i = 0; i < longitud; i++){
                            array_temp.push({label: response[i].nameclaseitem, id: response[i].idclaseitem})
                        }
                        $scope.tipo = array_temp;                       

                    });

                    $http.get(API_URL + 'catalogoproducto/getImpuestoICE').success(function(response){

                        var longitud = response.length;
                        var array_temp = [{label: '-- Seleccione --', id: ''}];
                        for(var i = 0; i < longitud; i++){
                            array_temp.push({label: response[i].nametipoimpuestoice, id: response[i].idtipoimpuestoice})
                        }
                        $scope.imp_ice = array_temp;
                        

                    });

                    $http.get(API_URL + 'catalogoproducto/getImpuestoIVA').success(function(response){

                        var longitud = response.length;
                        var array_temp = [{label: '-- Seleccione --', id: ''}];
                        for(var i = 0; i < longitud; i++){
                            array_temp.push({label: response[i].nametipoimpuestoiva, id: response[i].idtipoimpuestoiva})
                        }
                        $scope.imp_iva = array_temp;
                       

                    });
                    $scope.sublineas = [{label: '-- Seleccione --', id: ''}];
                     
                    $http.get(API_URL + 'catalogoproducto/getCategoriasToFilter').success(function(response){
                    	var longitud = response.length;
                        var array_temp = [{label: '-- Seleccione --', id: ''}];
                        for(var i = 0; i < longitud; i++){
                            array_temp.push({label: response[i].nombrecategoria, id: response[i].jerarquia})
                        }
                        $scope.lineas = array_temp;           
                       
                        
                    });
                    
                    
                    var ids = $scope.producto.jerarquia.split('.');
                	$scope.s_linea = ids[0];	  
                	 
	                $scope.loadSubLinea($scope.s_linea,false, true);
	                 
	                $scope.t_cuentacontable = $scope.producto.concepto;	                
	                $scope.t_cuentacontableingreso = $scope.producto.c2;
	                
	                
	                $scope.thumbnail = {
	            	        dataUrl: $scope.producto.foto
	            	    };
	                
	                $('#modalAction').modal('show');
                    
                    
                });

                break;
            case 'info':

            	$http.get(API_URL + 'catalogoproducto/'  + id ).success(function(response){                	
                    $scope.producto = response;                                       
                    var ids = $scope.producto.jerarquia.split('.');    	
                    var filter = {
                            padre:  ids[0],
                            nivel: 1
                        };
                	
                    $http.get(API_URL + 'catalogoproducto/getCategoriasHijas/' + JSON.stringify(filter)).success(function(response){
	                     	$scope.linea = response[0].nombrecategoria;
	                });
	                
	                console.log($scope.producto);
                    $('#modalInfoEmpleado').modal('show');
                });


                break;

            default:
                break;
        }
    }



    $scope.showPlanCuenta = function (opcion) {
    	$scope.opcion = opcion;
        $http.get(API_URL + 'empleado/getPlanCuenta').success(function(response){
            $scope.cuentas = response;
            $('#modalPlanCuenta').modal('show');
        });

    };

    $scope.selectCuenta = function () {
    	
        var selected = $scope.select_cuenta;
        if($scope.opcion == 1){
        	$scope.t_cuentacontable = selected.concepto;
            $scope.producto.idplancuenta = selected.idplancuenta;
        } else {
        	$scope.t_cuentacontableingreso = selected.concepto;
            $scope.producto.idplancuenta_ingreso = selected.idplancuenta;
        }
        

        $('#modalPlanCuenta').modal('hide');
    };

    $scope.click_radio = function (item) {
        $scope.select_cuenta = item;
    };

    $scope.hideModalMessage = function () {
        setTimeout("$('#modalMessage').modal('hide')", 3000);
    };
    
    $scope.loadSubLinea = function(padre,filtro,inicio) {
    	var filter = {
                padre: padre,
                nivel: 2
            };
    	
        $http.get(API_URL + 'catalogoproducto/getCategoriasHijas/' + JSON.stringify(filter)).success(function(response){
        	if(filtro){
        		$scope.sublineasFiltro = response; 
        		$scope.idCategoria = '';
        	}else{
        		var longitud = response.length;
                var array_temp = [{label: '-- Seleccione --', id: 0}];
                for(var i = 0; i < longitud; i++){
                    array_temp.push({label: response[i].nombrecategoria, id: response[i].idcategoria})
                }
                $scope.sublineas = array_temp;
                
                if(!inicio){
                	$scope.producto.idcategoria = 0; 
                	console.log("test ", $scope.producto.idcategoria);
                }
                
        	}
        	         
        });
    }
    
    $scope.save = function(modalstate, id) {

        var url = API_URL + "catalogoproducto";

        if (modalstate === 'edit'){
            url += "/" + id;
            $scope.producto._method= 'PUT'; 

        }    	       	
        	
        
        console.log($scope.producto);
        
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
    
    $scope.formatoFecha = function(fecha){
    	if(typeof fecha != 'undefined'){
    		var t = fecha.split('-');
        	var meses = ['ene','feb','mar','abr','may','jun','jul','ago','sep','oct','nov','dic'];
            return t[2] + '-' + meses[t[1]-1] + '-' + t[0];
    	} else {
    		return '';
    	}
    	
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
    

    $scope.formatDate = function(date){
        var dateOut = new Date(date);
        return dateOut;
  };
  
    /**
     * -----------------------------------------------------------------------------------------------------------------
     */


    /*
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

                $('#modalAction').modal('show');

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
    

    

   

    

    
    */
  
  
  
  $scope.thumbnail = {
	        dataUrl: ''
	    };
	    $scope.fileReaderSupported = window.FileReader != null;
	    $scope.photoChanged = function(files){
	        if (files != null) {
	            var file = files[0];
	        if ($scope.fileReaderSupported && file.type.indexOf('image') > -1) {
	            $timeout(function() {
	                var fileReader = new FileReader();
	                fileReader.readAsDataURL(file);
	                fileReader.onload = function(e) {
	                    $timeout(function(){
	 $scope.thumbnail.dataUrl = e.target.result;
	                    });
	                }
	            });
	        }
	    }
	    };
  
  
  
  
    
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

