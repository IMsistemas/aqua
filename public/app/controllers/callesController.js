app.controller('callesController', function($scope, $http, API_URL) {
    //retrieve calles listing from API
    $scope.calles=[];
    $http.get(API_URL + "calles/gestion/"+$scope.idbarrio)
        .success(function(response) {
                $scope.calles = response;             

            });
    //show modal form
    $scope.toggle = function(modalstate, idcalle) {
        $scope.modalstate = modalstate;

        switch (modalstate) {
            case 'add':
                $scope.form_title = "Nueva calle";
                $http.get(API_URL + 'calles/gestion/ultimocodigocalle')
                        .success(function(response) {
                            console.log(response);
                            $scope.idcalle = response.idcalle;
                        });
                $('#add').modal('show');
                break;
            case 'edit':
                $scope.form_title = "Editar calle";
                $scope.idcalle = idcalle;
                $http.get(API_URL + 'calles/gestion/' + idcalle)
                        .success(function(response) {
                            console.log(response);
                            $scope.calle = response;
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

   $scope.toModuloCanton = function(idcalle){        
        $scope.idcalle = idcalle;
        $scope.titulo = "Cantones";
        $scope.toModulo = "cantones";
    }

    //al mo mento que le den click al ng-click getInfo() ejecutamos la funcion

    //save new record / update existing record
    $scope.save = function(modalstate, idcalle) {
        var url = API_URL + "calles/gestion";    
        console.log(modalstate); 
        
        //append calle id to the URL if the form is in edit mode
        if (modalstate === 'edit'){
            url += "/actualizarcalle/" + idcalle;
        }else{
            url += "/guardarcalle" ;
        }
        
        $http({
            method: 'POST',
            url: url,
            data: $.param($scope.calle),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function(response) {
            console.log($scope.calle);
            console.log(response);
            location.reload();
        }).error(function(response) {
            console.log($scope.calle);
            console.log(response);
            alert('Ha ocurrido un error');
        });
    }

    //delete record
    $scope.confirmDelete = function(idcalle) {
        var isConfirmDelete = confirm('¿Seguro que decea guardar el registro?');
        if (isConfirmDelete) {
            $http({
                method: 'POST',
                url: API_URL + 'calles/gestion/eliminarcalle/' + idcalle,
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
