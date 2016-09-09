app.controller('cantonesController', function($scope, $http, API_URL) {
    //retrieve cantones listing from API
    $scope.cantones=[];
    $http.get(API_URL + "cantones/gestion/"+$scope.idprovincia)
        .success(function(response) {
                console.log(response);
                $scope.cantones = response;             

            });
    //show modal form
    $scope.toggle = function(modalstate, idcanton) {
        $scope.modalstate = modalstate;

        switch (modalstate) {
            case 'add':
                $scope.form_title = "Nueva Canton";
                $http.get(API_URL + 'cantones/gestion/ultimocodigocanton')
                        .success(function(response) {
                            console.log(response);
                            $scope.idcanton = response.idcanton;
                        });
                
                break;
            case 'edit':
                $scope.form_title = "Editar Canton";
                $scope.idcanton = idcanton;
                $http.get(API_URL + 'cantones/gestion/' + idcanton)
                        .success(function(response) {
                            console.log(response);
                            $scope.canton = response;
                        });
                
                break;
            default:
                break;
        }
         $('#myModal').modal('show');
     
    }

    //al mo mento que le den click al ng-click getInfo() ejecutamos la funcion

    //save new record / update existing record
    $scope.save = function(modalstate, idcanton) {
        var url = API_URL + "cantones/gestion";     
        console.log(idcanton);
        //append canton id to the URL if the form is in edit mode
        if (modalstate === 'edit'){
            url += "/actualizarcanton/" + idcanton;
        }else{
            url += "/guardarcanton/"+$scope.idprovincia ;
        }
        console.log($scope.canton);
        $http({
            method: 'POST',
            url: url,
            data: $.param($scope.canton),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function(response) {
            console.log(response);
            location.reload();
        }).error(function(response) {
            console.log(response);
            alert('Ha ocurrido un error');
        });
    }

    //delete record
    $scope.confirmDelete = function(idcanton) {
        var isConfirmDelete = confirm('¿Seguro que decea guardar el registro?');
        if (isConfirmDelete) {
            $http({
                method: 'POST',
                url: API_URL + 'cantones/gestion/eliminarprovincia/' + idcanton,
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
