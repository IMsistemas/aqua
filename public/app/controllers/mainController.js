(function(){

app.controller('mainController',['$scope','$route', function($scope, $http, API_URL,$route) {

	$scope.titulo = "Inicio";
	$scope.toModulo = "";



	$scope.toModuloEmpleado = function(){		
		$scope.titulo = "Colaboradores";
		$scope.toModulo = "empleado";
	}

	$scope.toModuloCliente = function(){		
		$scope.titulo = "Clientes";
		$scope.toModulo = "clientes";
	}

	$scope.toModuloProvincia = function(){		
		$scope.titulo = "Provincias";
		$scope.toModulo = "provincias";
	}

	$scope.toModuloCanton = function(idprovincia){		
		$scope.idprovincia = idprovincia;	
		$scope.titulo = "Cantones";
		$scope.toModulo = "cantones";
	}

	$scope.toModuloParroquia = function(idcanton){
		$scope.idcanton = idcanton;		
		$scope.titulo = "Parroquias";
		$scope.toModulo = "parroquias";
	}

	$scope.toModuloBarrio = function(idparroquia){		
		$scope.idparroquia = idparroquia;	
		$scope.titulo = "Zonas";
		$scope.toModulo = "barrios";

	}
	$scope.toModuloCalle = function(idbarrio,nombrebarrio){		
		$scope.idbarrio = idbarrio;	
		$scope.titulo = "Tranversales Barrio: ".concat(nombrebarrio);
		$scope.toModulo = "calles";
	}

	$scope.toModuloCargo = function(){
		$scope.titulo = "Cargos";
		$scope.toModulo = "cargo";
	}


	$scope.toModuloLectura = function(){		
		$scope.titulo = "Lecturas";
		$scope.toModulo = "lecturas";
	}

	$scope.toModuloRecaudacion = function(){		
		$scope.titulo = "Recaudación";
		$scope.toModulo = "recaudacion";
	}

	$scope.toModuloSolicitud = function(){		
		$scope.titulo = "Solicitudes";
		$scope.toModulo = "suministros/solicitudes";
	}

	$scope.toModuloSuministro = function(){		
		$scope.titulo = "suministros";
		$scope.toModulo = "suministros";
	}
	
}]);
})();