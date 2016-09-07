(function(){

app.controller('mainController', function($scope, $http, API_URL) {

	$scope.titulo = "Inicio";
	$scope.toModulo = "";


	$scope.toModuloEmpleado = function(){		
		$scope.titulo = "Empleados";
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

	$scope.toModuloCanton = function(){		
		$scope.titulo = "Cantones";
		$scope.toModulo = "cantones";
	}

	$scope.toModuloParroquia = function(idcanton){
		$scope.idcanton = idcanton;		
		$scope.titulo = "Parroquias";
		$scope.toModulo = "parroquias";
	}

	$scope.toModuloBarrio = function(){		
		$scope.titulo = "Barrios";
		$scope.toModulo = "barrios";

	}
	$scope.toModuloCalle = function(){		
		$scope.titulo = "Calles";
		$scope.toModulo = "calles";
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

	
});
})();