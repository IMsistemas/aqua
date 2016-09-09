

    app.controller('viewLecturaController', function($scope, $http, API_URL) {

        $scope.lecturas = [];


        $scope.initData = function(){

            $scope.loadBarrios();

            $http.get(API_URL + 'verLectura/getLecturas').success(function(response){
                $scope.lecturas = response;
            });

        }        

        $scope.loadBarrios = function(){
            $http.get(API_URL + 'verLectura/getBarrios').success(function(response){
                var longitud = response.length;
                var array_temp = [];
                for(var i = 0; i < longitud; i++){
                    array_temp.push({label: response[i].nombrebarrio, id: response[i].idbarrio})
                }
                $scope.barrios = array_temp;
            });
        }

        $scope.loadCalles = function(){
            var idbarrio = $scope.s_barrio;

            $http.get(API_URL + 'verLectura/getCalles/' + idbarrio).success(function(response){
                var longitud = response.length;
                var array_temp = [];
                for(var i = 0; i < longitud; i++){
                    array_temp.push({label: response[i].nombrecalle, id: response[i].idcalle})
                }
                $scope.calles = array_temp;
            });
        }

        $scope.initData();

    });

    function convertDatetoDB(now, revert){
        if (revert == undefined){
            var t = now.split('/');
            return t[2] + '-' + t[1] + '-' + t[0];
        } else {
            var t = now.split('-');
            return t[2] + '/' + t[1] + '/' + t[0];
        }
    }
