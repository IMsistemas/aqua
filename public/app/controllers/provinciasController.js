app.controller('provinciasController', function($scope, $http, API_URL) {
    //retrieve provincias listing from API
    $scope.provincias=[];
    $http.get(API_URL + "provincias/gestion")
        .success(function(response) {
                console.log($scope.provincias = response);             

            });
    //show modal form
    $scope.toggle = function(modalstate, idprovincia) {
        $scope.modalstate = modalstate;

        switch (modalstate) {
            case 'add':
                $scope.form_title = "Nueva Provincia";
                $http.get(API_URL + 'provincias/gestion/ultimocodigoprovincia')
                        .success(function(response) {
                            console.log(response);
                            $scope.idprovincia = response.idprovincia;
                        });
                $('#add').modal('show');
                break;
            case 'edit':
                $scope.form_title = "Editar Provincia";
                $scope.idprovincia = idprovincia;
                $http.get(API_URL + 'provincias/gestion/' + idprovincia)
                        .success(function(response) {
                            console.log(response);
                            $scope.provincia = response;
                        });
                $('#edit').modal('show');
                break;
            default:
                break;
        }
     
    }
    

    app.config(function($routeProvider){
        $routeProvider.when("/", {
        templateUrl : "templates/index.html",
        controller : "indexController"
        })
        .when("/home", {
        templateUrl : "templates/home.html",
        controller : "homeController"
        })
        .when("/login", {
        templateUrl : "templates/login.html",
        controller : "loginController"
         })
        //este es digamos, al igual que en un switch el default, en caso que 
        //no hayamos concretado que nos redirija a la página principal
         .otherwise({ reditrectTo : "/" });
    })

   $scope.toModuloCanton = function(idprovincia){        
        $scope.idprovincia = idprovincia;
        $scope.titulo = "Cantones";
        $scope.toModulo = "cantones";
    }

    //al mo mento que le den click al ng-click getInfo() ejecutamos la funcion

    //save new record / update existing record
    $scope.save = function(modalstate, idprovincia) {
        var url = API_URL + "provincias/gestion";    
        console.log(modalstate); 
        
        //append provincia id to the URL if the form is in edit mode
        if (modalstate === 'edit'){
            url += "/actualizarprovincia/" + idprovincia;
        }else{
            url += "/guardarprovincia" ;
        }
        
        $http({
            method: 'POST',
            url: url,
            data: $.param($scope.provincia),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function(response) {
            console.log($scope.provincia);
            console.log(response);
            location.reload();
        }).error(function(response) {
            console.log($scope.provincia);
            console.log(response);
            alert('Ha ocurrido un error');
        });
    }

    //delete record
    $scope.confirmDelete = function(idprovincia) {
        var isConfirmDelete = confirm('¿Seguro que decea guardar el registro?');
        if (isConfirmDelete) {
            $http({
                method: 'POST',
                url: API_URL + 'provincias/gestion/eliminarprovincia/' + idprovincia,
            }).success(function(data) {
                    console.log(data);
                    location.reload();
            }).error(function(data) {
                    console.log(data);
                    alert('Unable to delete');
            });
        } else {
            return false;
        }
    }
});
