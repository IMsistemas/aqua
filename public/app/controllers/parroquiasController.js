app.controller('parroquiasController', function($scope, $http, API_URL, idcanton) {
    //retrieve parroquia listing from API
    $scope.parroquias=[];
    $http.get(API_URL + "parroquias/gestion/"+idcanton)
        .success(function(response) {
                $scope.parroquias = response;             

            });
    //show modal form
    $scope.toggle = function(modalstate, idparroquia) {
        $scope.modalstate = modalstate;

        switch (modalstate) {
            case 'add':
                $scope.form_title = "Nueva Parroquia";
                $http.get(API_URL + 'parroquias/gestion/ultimocodigoparroquia')
                        .success(function(response) {
                            console.log(response);
                            //$scope.idparroquia = response.idparroquia;
                        });
                $('#add').modal('show');
                break;
            case 'edit':
                $scope.form_title = "Editar Parroquia";
                $scope.idparroquia = idparroquia;
                $http.get(API_URL + 'parroquias/gestion/' + idparroquia)
                        .success(function(response) {
                            console.log(response);
                            $scope.parroquia = response;
                        });
                $('#edit').modal('show');
                break;
            default:
                break;
        }
     
    }

    //al mo mento que le den click al ng-click getInfo() ejecutamos la funcion

    //save new record / update existing record
    $scope.save = function(modalstate, idparroquia) {
        var url = API_URL + "parroquias/gestion";    
        console.log(modalstate); 
        
        //append parroquia id to the URL if the form is in edit mode
        if (modalstate === 'edit'){
            url += "/actualizarparroquia/" + idprovincia;
        }else{
            url += "/guardarparroquia" ;
        }
        
        $http({
            method: 'POST',
            url: url,
            data: $.param($scope.parroquia),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function(response) {
            console.log($scope.parroquia);
            console.log(response);
            location.reload();
        }).error(function(response) {
            console.log($scope.parroquia);
            console.log(response);
            alert('Ha ocurrido un error');
        });
    }

    //delete record
    $scope.confirmDelete = function(idparroquia) {
        var isConfirmDelete = confirm('¿Seguro que decea guardar el registro?');
        if (isConfirmDelete) {
            $http({
                method: 'POST',
                url: API_URL + 'parroquias/gestion/eliminarparroquia/' + idprovincia,
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
