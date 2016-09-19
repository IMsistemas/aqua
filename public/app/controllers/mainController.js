(function(){

app.controller('mainController',['$scope','$route', function($scope, $http, API_URL,$route) {

	$scope.titulo = "Inicio";
	$scope.toModulo = "";

	$scope.username = 'Secretaría';

	$scope.list_breadcrumb = [];

	$scope.toModuloEmpleado = function(){		
		$scope.titulo = "Colaboradores";
		$scope.toModulo = "empleado";

		var list = [
			'<li>Personal</li>',
			'<li>Colaboradores</li>'
		];

		$scope.prepareListBreadcrumb(list);
	}

	$scope.toModuloCliente = function(){		
		$scope.titulo = "Clientes";
		$scope.toModulo = "clientes";

		var list = [
			'<li>Clientes</li>',
			'<li>Clientes</li>'
		];

		$scope.prepareListBreadcrumb(list);
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

		var list = [
			'<li>Personal</li>',
			'<li>Cargos</li>'
		];

		$scope.prepareListBreadcrumb(list);
	}


	$scope.toModuloLectura = function(){		
		$scope.titulo = "Lecturas";
		$scope.toModulo = "verLectura";
	}

	$scope.toModuloRecaudacion = function(){		
		$scope.titulo = "Recaudación";
		$scope.toModulo = "recaudacion";
	}

	$scope.toModuloSolicitud = function(){	
		$scope.titulo = "Solicitudes";
		$scope.toModulo = "suministros/solicitudes";
	}
	$scope.toModuloSolicitudEspera = function(){	
		$scope.titulo = "Solicitudes";
		$scope.toModulo = "suministros/espera";
	}

	$scope.toModuloSuministro = function(){		
		$scope.titulo = "suministros";
		$scope.toModulo = "suministros";
	}


	$scope.prepareListBreadcrumb = function (list_module) {
		$scope.list_breadcrumb = [
			"<li><img src='img/ico-aqua.png'></li>",
			"<li>Inicio</li>"
		];

		var breadcrumb = ($scope.list_breadcrumb).concat(list_module);

		$('#list_breadcrumb').html(breadcrumb);

	}

	$scope.prepareListBreadcrumb();
	
}]);
})();

