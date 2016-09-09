app.controller('provinciasController', function($scope, $http, API_URL) {
    //retrieve provincias listing from API
    $scope.provincias=[];
    $scope.initLoad = function(){
    $http.get(API_URL + "provincias/gestion")
        .success(function(response) {
                console.log($scope.provincias = response);             

            });
    }
    $scope.initLoad();
    //show modal form
    $scope.toggle = function(modalstate, idprovincia) {
        $scope.modalstate = modalstate;

        switch (modalstate) {
            case 'add':
                $scope.form_title = "Nueva Provincia";
                $http.get(API_URL + 'provincias/gestion/ultimoidprovincia')
                        .success(function(response) {
                            $scope.provincia.idprovincia = response.idprovincia;
                            $scope.provincia.nombreprovincia = "";
                        });
                break;
            case 'edit':
                $scope.form_title = "Editar Provincia";
                $scope.idprovincia = idprovincia;
                $http.get(API_URL + 'provincias/gestion/' + idprovincia)
                        .success(function(response) {
                            console.log(response);
                            $scope.provincia.idprovincia = (response.idprovincia).trim();
                            $scope.provincia.nombreprovincia = (response.nombreprovincia).trim();
                        });
                break;
            default:
                break;
        }
         $('#myModal').modal('show');
     
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
        console.log($scope.provincia);
        $http({
            method: 'POST',
            url: url,
            data: $.param($scope.provincia),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function(response) {
            $scope.initLoad();
            console.log($scope.provincia);
            console.log(response);
        }).error(function(response) {
            $scope.initLoad();
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
                    //location.reload();
            }).error(function(data) {
                    console.log(data);
                    alert('Unable to delete');
            });
        } else {
            return false;
        }
    }
});
