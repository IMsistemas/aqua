app.controller('barriosController', function($scope, $http, API_URL) {
    //retrieve barrios listing from API
    $scope.barrios=[];
    $http.get(API_URL + "barrios/gestion/"+$scope.idparroquia)
        .success(function(response) {
                $scope.barrios = response;        

            });
    //show modal form
    $scope.toggle = function(modalstate, idbarrio) {
        $scope.modalstate = modalstate;

        switch (modalstate) {
            case 'add':
                $scope.form_title = "Nueva barrio";
                $http.get(API_URL + 'barrios/gestion/ultimocodigobarrio')
                        .success(function(response) {
                            console.log(response);
                            $scope.idbarrio = response.idbarrio;
                        });
                $('#add').modal('show');
                break;
            case 'edit':
                $scope.form_title = "Editar barrio";
                $scope.idbarrio = idbarrio;
                $http.get(API_URL + 'barrios/gestion/' + idbarrio)
                        .success(function(response) {
                            console.log(response);
                            $scope.barrio = response;
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

   $scope.toModuloCanton = function(idbarrio){        
        $scope.idbarrio = idbarrio;
        $scope.titulo = "Cantones";
        $scope.toModulo = "cantones";
    }

    //al mo mento que le den click al ng-click getInfo() ejecutamos la funcion

    //save new record / update existing record
    $scope.save = function(modalstate, idbarrio) {
        var url = API_URL + "barrios/gestion";    
        console.log(modalstate); 
        
        //append barrio id to the URL if the form is in edit mode
        if (modalstate === 'edit'){
            url += "/actualizarbarrio/" + idbarrio;
        }else{
            url += "/guardarbarrio" ;
        }
        
        $http({
            method: 'POST',
            url: url,
            data: $.param($scope.barrio),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function(response) {
            console.log($scope.barrio);
            console.log(response);
            location.reload();
        }).error(function(response) {
            console.log($scope.barrio);
            console.log(response);
            alert('Ha ocurrido un error');
        });
    }

    //delete record
    $scope.confirmDelete = function(idbarrio) {
        var isConfirmDelete = confirm('¿Seguro que decea guardar el registro?');
        if (isConfirmDelete) {
            $http({
                method: 'POST',
                url: API_URL + 'barrios/gestion/eliminarbarrio/' + idbarrio,
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
