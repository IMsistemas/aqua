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
                break;
            case 'edit':
                $scope.form_title = "Editar calle";
                $scope.idcalle = idcalle;
                $http.get(API_URL + 'calles/gestion/' + idcalle)
                        .success(function(response) {
                            console.log(response);
                            $scope.calle = response;
                        });
                break;
            default:
                break;
        }
         $('#myModal').modal('show');
     
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
            url += "/guardarcalle/"+$scope.idbarrio ;
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
