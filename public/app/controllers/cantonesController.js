app.controller('cantonesController', function($scope, $http, API_URL) {
    //retrieve cantones listing from API
    $scope.cantones=[];
    $http.get(API_URL + "cantones/gestion")
        .success(function(response) {
                console.log(response);
                $scope.cantones = response;             

            });
    //show modal form
    $scope.toggle = function(modalstate, idcanton) {
        $scope.modalstate = modalstate;

        switch (modalstate) {
            case 'add':
                $scope.form_title = "Nueva Provincia";
                $http.get(API_URL + 'cantones/gestion/ultimocodigocanton')
                        .success(function(response) {
                            console.log(response);
                            $scope.idcanton = response.idcanton;
                        });
                $('#add').modal('show');
                break;
            case 'edit':
                $scope.form_title = "Editar Provincia";
                $scope.idcanton = idcanton;
                $http.get(API_URL + 'cantones/gestion/' + idcanton)
                        .success(function(response) {
                            console.log(response);
                            $scope.canton = response;
                        });
                $('#edit').modal('show');
                break;
            default:
                break;
        }
     
    }

    //al mo mento que le den click al ng-click getInfo() ejecutamos la funcion

    //save new record / update existing record
    $scope.save = function(modalstate, idprovincia) {
        var url = API_URL + "cantones/gestion";    
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
                url: API_URL + 'cantones/gestion/eliminarprovincia/' + idprovincia,
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
