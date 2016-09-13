app.controller('parroquiasController', function($scope, $http, API_URL) {
    //retrieve parroquia listing from API
    $scope.parroquias=[];
     $scope.idparroquia="";
    $scope.nombreparroquia="";
    $scope.idparroquia_del=0;
    $scope.initLoad = function(){
    $http.get(API_URL + "parroquias/gestion/"+$scope.idcanton)
        .success(function(response) {
                $scope.parroquias = response;             

            });
    }
    $scope.initLoad();
    //show modal form
    $scope.toggle = function(modalstate, idparroquia,nombreparroquia) {
        $scope.modalstate = modalstate;

        switch (modalstate) {
            case 'add':
                $scope.form_title = "Nueva Parroquia";
                $http.get(API_URL + 'parroquias/maxid')
                        .success(function(response) {
                            console.log(response);
                            $scope.idparroquia = response;
                        });        
                break;
            case 'edit':
                $scope.form_title = "Editar Parroquia";
                $scope.idparroquia = idparroquia
                $scope.nombreparroquia=nombreparroquia.trim();
                break;
            default:
                break;
        }
         $('#myModal').modal('show');
     
    }

    //al mo mento que le den click al ng-click getInfo() ejecutamos la funcion

    //save new record / update existing record
    $scope.save = function(modalstate, idparroquia) {
        var url = API_URL + "parroquias/gestion";    
        console.log(idparroquia); 
        
        //append parroquia id to the URL if the form is in edit mode
        if (modalstate === 'edit'){
            url += "/actualizar/" + idparroquia;
        }else{
            url += "/guardarparroquia/"+$scope.idcanton ;
        }
         $scope.parroquia={
            idparroquia: $scope.idparroquia,
            nombreparroquia: $scope.nombreparroquia
        };
         console.log($scope.parroquia);
        $http({
            method: 'POST',
            url: url,
            data: $.param($scope.parroquia),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function(response) {
           $scope.initLoad();
            $('#myModal').modal('hide');
                $scope.message = response;
             $('#modalMessage').modal('show');
             setTimeout("$('#modalMessage').modal('hide')",5000);
        }).error(function(response) {
            console.log(response);
            alert('Ha ocurrido un error');
        });
    }

    //delete record
      $scope.showModalConfirm = function(idparroquia,nombreparroquia){
        $scope.idparroquia_del = idparroquia;
        $scope.parroquia_seleccionado = nombreparroquia;
            $('#modalConfirmDelete').modal('show');
    }

    $scope.destroyParroquia = function(){
        $http.delete(API_URL + 'parroquias/gestion/eliminar/' + $scope.idparroquia_del).success(function(response) {
            $scope.initLoad();
            $('#modalConfirmDelete').modal('hide');
            $scope.idparroquia_del = 0;
            $scope.message = response;
            $('#modalMessage').modal('show')
            setTimeout("$('#modalMessage').modal('hide')",5000);
            });
    }
});
