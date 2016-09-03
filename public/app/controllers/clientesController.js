    app.controller('clientesController', function($scope, $http, API_URL) {
    //retrieve clientes listing from API
    $scope.clientes=[];
    $http.get(API_URL + "clientes")
        .success(function(response) {
                $scope.clientes = response;             

            });
    //show modal form
    $scope.toggle = function(modalstate, documentoidentidad) {
        $scope.modalstate = modalstate;

        switch (modalstate) {
            case 'add':
                $scope.form_title = "Nuevo Cliente";
                break;
            case 'edit':
                $scope.form_title = "Editar Cliente";
                scope.documentoidentidad = documentoidentidad;
                $http.get(API_URL + 'clientes/' + documentoidentidad)
                        .success(function(response) {
                            console.log(response);
                            $scope.cliente = response;
                        });
                break;
            default:
                break;
        }
        console.log(documentoidentidad);
        $('#myModal').modal('show');
    }

    //save new record / update existing record
    $scope.save = function(modalstate, documentoidentidad) {
        var url = API_URL + "clientes";
        
        //append cliente id to the URL if the form is in edit mode
        if (modalstate === 'edit'){
            url += "/" + documentoidentidad;
        }
        
        $http({
            method: 'POST',
            url: url,
            data: $.param($scope.cliente),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function(response) {
            console.log(response);
            location.reload();
        }).error(function(response) {
            console.log(response);
            alert('This is embarassing. An error has occured. Please check the log for details');
        });
    }

    //delete record
    $scope.confirmDelete = function(documentoidentidad) {
        var isConfirmDelete = confirm('Are you sure you want this record?');
        if (isConfirmDelete) {
            $http({
                method: 'DELETE',
                url: API_URL + 'clientes/' + documentoidentidad
            }).
                    success(function(data) {
                        console.log(data);
                        location.reload();
                    }).
                    error(function(data) {
                        console.log(data);
                        alert('Unable to delete');
                    });
        } else {
            return false;
        }
    }
});
