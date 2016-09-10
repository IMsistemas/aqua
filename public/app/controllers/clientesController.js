    app.controller('clientesController', function($scope, $http, API_URL) {
    //retrieve clientes listing from API
    $scope.clientes=[];
    $scope.telprincipal;
    $scope.telsecundario;
    $scope.celular;
    //$scope.fecha=0;
     $scope.initLoad = function(){
    $http.get(API_URL + "clientes/gestion")
        .success(function(response) {
                $scope.clientes = response;             

            });
    }
    $scope.initLoad();
    //show modal form
    $scope.toggle = function(modalstate, documentoidentidad) {
        $scope.modalstate = modalstate;

        switch (modalstate) {
            case 'add':
                $scope.form_title = "Nuevo Cliente";
                break;
            case 'edit':
                $scope.form_title = "Editar Cliente";
                $scope.documentoidentidad = documentoidentidad;
                $http.get(API_URL + 'clientes/gestion/' + documentoidentidad)
                        .success(function(response) {
                            $scope.cliente = response;
                             $scope.telprincipal=$scope.cliente.telefonoprincipal.trim();
                            $scope.telsecundario=$scope.cliente.telefonosecundario.trim();
                            $scope.celular=$scope.cliente.celular.trim();
                        });
                break;
            default:
                break;
        }
          $('#myModal').modal('show');
     
    } 

    //al mo mento que le den click al ng-click getInfo() ejecutamos la funcion

    //save new record / update existing record
    $scope.save = function(modalstate, documentoidentidad) {
        var url = API_URL + "clientes/gestion";    
        console.log(modalstate); 
        
        //append cliente id to the URL if the form is in edit mode
        if (modalstate === 'edit'){
            url += "/actualizarcliente/" + documentoidentidad;
            $scope.cliente.telefonoprincipal=$scope.telprincipal;
            $scope.cliente.telefonosecundario= $scope.telsecundario;
            $scope.cliente.celular=$scope.celular;
        }else{
            url += "/guardarcliente" ;
        }

        
        $http({
            method: 'POST',
            url: url,
            data: $.param($scope.cliente),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function(response) {
            $scope.initLoad();
             $('#myModal').modal('hide');
                $scope.message = response;
             $('#modalMessage').modal('show');
             setTimeout("$('#modalMessage').modal('hide')",5000);
            console.log(response);
        }).error(function(response) {
            console.log(response);
            alert('This is embarassing. An error has occured. Please check the log for details');
        });
    }

    //delete record
    $scope.confirmDelete = function(documentoidentidad) {
        var isConfirmDelete = confirm('¿Seguro que decea guardar el registro?');
        if (isConfirmDelete) {
            $http({
                method: 'POST',
                url: API_URL + 'clientes/gestion/eliminarcliente/' + documentoidentidad,
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
