app.controller('cantonesController', function($scope, $http, API_URL) {
    //retrieve cantones listing from API
    $scope.cantones=[];
    $scope.idcanton="";
    $scope.nombrecanton="";
    $scope.idcanton_del=0;
    $scope.initLoad = function(){
    $http.get(API_URL + "cantones/gestion/"+$scope.idprovincia)
        .success(function(response) {
                console.log(response);
                $scope.cantones = response;             

            });
    }
    $scope.initLoad();
    $scope.ordenarColumna = 'estaprocesada';
    //show modal form
    $scope.toggle = function(modalstate, idcanton, nombrecanton) {
        $scope.modalstate = modalstate;

        switch (modalstate) {
            case 'add':
                $scope.form_title = "Nueva Canton";
                $http.get(API_URL + 'cantones/maxid')
                        .success(function(response) {
                            console.log(response);
                            $scope.idcanton = response;
                            $scope.nombrecanton = "";
                        });                
                break;
            case 'edit':
                $scope.form_title = "Editar Canton";
                $scope.idcanton = idcanton;
                $scope.nombrecanton=nombrecanton.trim();
                        //});
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
        $scope.canton={
            idcanton: $scope.idcanton,
            nombrecanton: $scope.nombrecanton
        };
        console.log($scope.canton);
        $http({
            method: 'POST',
            url: url,
            data: $.param($scope.canton),
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
    $scope.showModalConfirm = function(idcanton,nombrecanton){
        $scope.idcanton_del = idcanton;
        $scope.canton_seleccionado = nombrecanton.trim();
            $('#modalConfirmDelete').modal('show');
    }

    $scope.destroyCanton = function(){
        $http.delete(API_URL + 'cantones/gestion/eliminarcanton/' + $scope.idcanton_del).success(function(response) {
            $scope.initLoad();
            $('#modalConfirmDelete').modal('hide');
            $scope.idcanton_del = 0;
            $scope.message = response;
            $('#modalMessage').modal('show')
            setTimeout("$('#modalMessage').modal('hide')",5000);
            });
    }
});
