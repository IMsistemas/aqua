app.controller('provinciasController', function($scope, $http, API_URL) {
    //retrieve provincias listing from API
    $scope.provincias=[];
    $scope.idprovincia="";
    $scope.nombreprovincia="";
    $scope.idprovincia_del=0;
    $scope.initLoad = function(){
    $http.get(API_URL + "provincias/gestion")
        .success(function(response) {
                console.log($scope.provincias = response);             

            });
    }
    $scope.initLoad();
     $scope.ordenarColumna = 'estaprocesada';
    //show modal form
    $scope.toggle = function(modalstate, idprovincia ,nombreprovincia) {
        $scope.modalstate = modalstate;

        switch (modalstate) {
            case 'add':
                $scope.form_title = "Nueva Provincia";
                $http.get(API_URL + 'provincias/maxid')
                        .success(function(response) {
                            console.log(response);
                            $scope.idprovincia = response;
                        });
                break;
            case 'edit':
                $scope.form_title = "Editar Provincia";
                $scope.idprovincia = idprovincia
                $scope.nombreprovincia=nombreprovincia.trim();
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
        $scope.provincia={
            idprovincia: $scope.idprovincia,
            nombreprovincia: $scope.nombreprovincia
        };
        console.log($scope.provincia);
        $http({
            method: 'POST',
            url: url,
            data: $.param($scope.provincia),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function(response) {
            $scope.initLoad();
            $('#myModal').modal('hide');
                $scope.message = response;
             $('#modalMessage').modal('show');
             setTimeout("$('#modalMessage').modal('hide')",5000);
        }).error(function(response) {
            $scope.initLoad();
            console.log($scope.provincia);
            console.log(response);
            alert('Ha ocurrido un error');
        });
    }

    //delete record
    $scope.showModalConfirm = function(idprovincia,nombreprovincia){
        $scope.idprovincia_del = idprovincia;
        $scope.provincia_seleccionado = nombreprovincia.trim();
            $('#modalConfirmDelete').modal('show');
    }

    $scope.destroyProvincia = function(){
        $http.delete(API_URL + 'provincias/gestion/eliminarprovincia/' + $scope.idprovincia_del).success(function(response) {
            $scope.initLoad();
            $('#modalConfirmDelete').modal('hide');
            $scope.idprovincia_del = 0;
            $scope.message = response;
            $('#modalMessage').modal('show')
            setTimeout("$('#modalMessage').modal('hide')",5000);
            });
    }
});
