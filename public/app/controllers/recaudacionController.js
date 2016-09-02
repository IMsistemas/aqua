app.controller('recaudacionController', function($scope, $http, API_URL) {
    //retrieve employees listing from API
    $http.get(API_URL + "recaudacion/cobroagua/cuentas")
            .success(function(response) {
                $scope.cuentas = response;
            });

     $http.get(API_URL + "recaudacion/cobroagua/rubrosfijos")
            .success(function(response) {
                $scope.rubrosFijos = response;
            });

      $http.get(API_URL + "recaudacion/cobroagua/rubrosvariables")
            .success(function(response) {
                $scope.rubrosVariables = response;
            });
     
    $scope.modalIngresoOtrosRubros = function(numeroCuenta){
        $scope.cuenta = $scope.cuentas[numeroCuenta-1];
        $('#ingresar-otros-rubros').modal('show');
    };

/*
    //save new record / update existing record
    $scope.save = function(modalstate, id) {
        var url = API_URL + "employees";
        
        //append employee id to the URL if the form is in edit mode
        if (modalstate === 'edit'){
            url += "/" + id;
        }
        
        $http({
            method: 'POST',
            url: url,
            data: $.param($scope.employee),
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
    $scope.confirmDelete = function(id) {
        var isConfirmDelete = confirm('Are you sure you want this record?');
        if (isConfirmDelete) {
            $http({
                method: 'DELETE',
                url: API_URL + 'employees/' + id
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
    } */
});