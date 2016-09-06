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

	$scope.toModuloLectura = function(){		
		$scope.titulo = "Lecturas";
		$scope.toModulo = "lecturas";
	}

	$scope.toModuloRecaudacion = function(){		
		$scope.titulo = "Recaudacion";
		$scope.toModulo = "recaudacion";
	}

	$scope.toModuloSolicitud = function(){		
		$scope.titulo = "Solicitudes";
		$scope.toModulo = "suministros/solicitudes";
	}

	
});
})();