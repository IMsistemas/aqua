app.controller('proveedoresController', function($scope, $http, API_URL) {
	$scope.proveedores = [];
	$scope.codigotipoid = [];
	$scope.provincias = [];
	$scope.ciudades = [];
	$scope.sectores = [];
	$scope.proveedornuevo = [];
	$scope.proveedor_del = [];
	$scope.provincia='';
	$scope.ciudad='';
	$scope.contactos = [];
	$scope.contactosguardar = [];
	$scope.cuentaproveedor =[];

	$scope.onlyCharasterAndSpace = function ($event) {

            var k = $event.keyCode;
            if (k == 8 || k == 0) return true;
            var patron = /^([a-zA-Záéíóúñ\s]+)$/;
            var n = String.fromCharCode(k);

            if(patron.test(n) == false){
                $event.preventDefault();
                return false;
            }
            else return true;

        };

        $scope.onlyNumber = function ($event) {

            var k = $event.keyCode;
            if (k == 8 || k == 0) return true;
            var patron = /\d/;
            var n = String.fromCharCode(k);

            if (n == ".") {
                return true;
            } else {

                if(patron.test(n) == false){
                    $event.preventDefault();
                }
                else return true;
            }
        };
	$scope.mostrarmodal = function  () {
		$('#botonagregarproveedor').button('loading');
		fetchprovincias();
		fetchnuevoproveedor();
		
	};

	$scope.addcontacto = function  () {
		$scope.contactos.push({
			nombrecontacto: '',
			idcontacto: '',
			telefonoprincipal: '',
			telefonosecundario: '',
			celular: '',
			observacion: '',
			idproveedor: $scope.proveedornuevo.idproveedor
		});
		
	};
	$scope.removecontacto = function () {

			$http.post(API_URL + 'api/proveedores/contactos/'+$scope.idcontacto).success(function (data) {
	      		 	
	        	console.log(data);
	        	$http.get(API_URL + 'api/proveedores/contactosproveedor/'+$scope.proveedornuevo.idproveedor).success(function(response){
	        	    $scope.contactos = response;
	        	    $('#modalMessage3').modal('hide');
	        	    $scope.message = 'Elemento Eliminado Exitosamente';
	        	    $scope.idcontacto = "";
	                $('#modalMessage').modal('show');
	                setTimeout("$('#modalMessage').modal('hide')",3000);
	        	});
	        	

	            }).error(function (res) {

	    	}); 
		

		
	};

	$scope.updatecontacto = function  (contacto) {

		$http.put(API_URL + 'api/proveedores/contactos/'+contacto.idcontacto, contacto ).success(function (data) {
      		 	
        	console.log(data);
        	$http.get(API_URL + 'api/proveedores/contactosproveedor/'+$scope.proveedornuevo.idproveedor).success(function(response){
        	    $scope.contactos = response;
        	    $scope.message = 'Operación Exitosa';
                $('#modalMessage').modal('show');
                setTimeout("$('#modalMessage').modal('hide')",3000);
        	});
        	

            }).error(function (res) {

    	}); 
	};

	$scope.addproveedor = function () {
		//$('#botonaddproveedor').button('loading');
		$http.post(API_URL + 'api/proveedores',$scope.proveedornuevo ).success(function (data) {
      		$('#modal-agregar').modal('hide');  	
        	fetchproveedor();
			$scope.proveedornuevo = [];
			
			$scope.message = 'Operación Exitosa';
                $('#modalMessage').modal('show');
                setTimeout("$('#modalMessage').modal('hide')",3000);

            }).error(function (res) {

    	});  
	};
	$scope.cargarCiudades = function (idprovincia)
	{
		$http.get(API_URL + 'api/proveedores/ciudades/'+idprovincia).success(function(response){
		    $scope.ciudades = response;            
		});

	};
	$scope.cargarSectores = function (idciudad)
	{
		$http.get(API_URL + 'api/proveedores/sectores/'+idciudad).success(function(response){
		    $scope.sectores = response;            
		});

	};
	
	var cargarSectores = function  () {
		$http.get(API_URL + 'api/proveedores/sectores/').success(function(response){
		    $scope.sectores = response;            
		    
		    
		});
	};
	var cargarCiudades = function  () {
		$http.get(API_URL + 'api/proveedores/ciudades/').success(function(response){
		    $scope.ciudades = response;            
		    
		    
		});
	};

	var fetchproveedor = function  () {
		$http.get(API_URL + 'api/proveedores').success(function(response){
		    $scope.proveedores = response;            
		    
		    
		});
	};
	var fetchtiposcontribuyentes = function  () {
		$http.get(API_URL + 'api/proveedores/tiposcontribuyentes').success(function(response){
		    $scope.tiposcontribuyentes = response;            
		});
	};
	var fetchprovincias = function  () {
		$http.get(API_URL + 'api/proveedores/provincias').success(function(response){
		    $scope.provincias = response;            
		});
	};
	
	var fetchnuevoproveedor = function  () {
		$http.get(API_URL + 'api/proveedores/nuevoproveedor').success(function(response){
		    $scope.proveedornuevo = response;  
		    $('#botonagregarproveedor').button('reset');          
		    $('#modal-agregar').modal('show');
		    
		});
	};


	fetchproveedor();
		fetchtiposcontribuyentes();


	$scope.cambiarEstado = function (proveedor) 
	{
		$scope.proveedor_del = proveedor;
		if (proveedor.estado) {
       		$scope.message = "Está Seguro que Desea Anular el Proveedor: "+proveedor.razonsocialproveedor;      

		}
		else
		{
       		$scope.message = "Está Seguro que Desea Activar el Proveedor: "+proveedor.razonsocialproveedor;      

		}

        $('#modalMessage2').modal('show');

		
	};
	$scope.eliminarcontacto = function (contacto) 
	{
		
			$scope.idcontacto = contacto.idcontacto;			
       		$scope.message = "Está Seguro que Desea Eliminar el Contacto: "+contacto.nombrecontacto;

			$('#modalMessage3').modal('show');

		
	};
	$scope.confirmarCambiarEstado = function ()
	{
		$scope.proveedor_del.estado = !$scope.proveedor_del.estado;
		$('#btn-cambiarestado').button('loading');
		$http.put(API_URL + 'api/proveedores/'+$scope.proveedor_del.idproveedor,$scope.proveedor_del ).success(function (data) {
			$('#btn-cambiarestado').button('reset');
        	
        	$('#modalMessage2').modal('hide');
            $scope.proveedor_del = 0;
            $scope.message = 'Operación Exitosa';
            $('#modalMessage').modal('show');
            setTimeout("$('#modalMessage').modal('hide')",3000);
        	fetchproveedor();

            }).error(function (res) {

    	});  

	}


	$scope.editarProveedor = function (proveedor) 
	{
		$http.get(API_URL + 'api/proveedores/fechacreacioncuenta/'+proveedor.idproveedor).success(function(response){
		    $scope.cuentaproveedor = response; 
		    $scope.fecha = $scope.cuentaproveedor[0].fechacreacioncuenta;
		    
		    $('#botoneditarproveedor').button('loading');
			cargarSectores();
			cargarCiudades();
			fetchprovincias();
			$scope.proveedornuevo = proveedor; 	
		
			$('#modal-editar').modal('show');
			$('#botoneditarproveedor').button('reset');   
		});
		
		 

              
		


	};
	$scope.confirmareditarproveedor = function () {
		$http.put(API_URL + 'api/proveedores/'+$scope.proveedornuevo.idproveedor,$scope.proveedornuevo ).success(function (data) {
      		$('#modal-editar').modal('hide');  	
        	fetchproveedor();
			$scope.proveedornuevo = [];
			$scope.message = 'Operación Exitosa';
			$('#modalMessage').modal('show');

                setTimeout("$('#modalMessage').modal('hide')",3000);


            }).error(function (res) {

    	});  
	};
	$scope.verContactos = function (proveedor)
	{
		$('#botonvercontacto').button('loading');
		$scope.contactosguardar = [];
		$scope.proveedornuevo = proveedor; 	
		$http.get(API_URL + 'api/proveedores/contactosproveedor/'+proveedor.idproveedor).success(function(response){
		    $scope.contactos = response;            
		});
		$('#modal-contactos').modal('show');
		$('#botonvercontacto').button('reset');
	};
	$scope.confirmarguardarcontactos = function () {
		$http.post(API_URL + 'api/proveedores/'+$scope.proveedornuevo.idproveedor+'/contactos',$scope.contactosguardar ).success(function (data) {
      		 	
        	console.log(data);

			$http.get(API_URL + 'api/proveedores/contactosproveedor/'+$scope.proveedornuevo.idproveedor).success(function(response){
			    $scope.contactos = response;  
			    $scope.contactosguardar = [];          
			});

            }).error(function (res) {

    	});  
	};

	$scope.saveAllContactos = function() {  
		$scope.idproveedorcont = $scope.proveedornuevo.idproveedor;   	
    	$http.put(API_URL + 'api/proveedores/contactos/' + JSON.stringify($scope.contactos)).success(function(response){
    		//$scope.initLoad();
    		$http.get(API_URL + 'api/proveedores/contactosproveedor/'+$scope.idproveedorcont).success(function(response){
			    $scope.contactos = response;  
			    $scope.message = 'Se Actualizaron Correctamente los Items.';
            	$('#modalMessage').modal('show');
            	setTimeout("$('#modalMessage').modal('hide')",3000);
			            
			});
            
       
        }).error(function (res) {

        });		       		  
  };

});

app.filter('activo', function () {
  return function (item) {
  	if (item == true) {
  		return 'Activo';
  	}else{
  		return 'Inactivo';
  	}
  };
});
app.filter('botonactivo', function () {
  return function (item) {
  	if (item == true) {

  		return 'Anular';
  	}else{

  		return 'Activar';
  	}
  };
});

