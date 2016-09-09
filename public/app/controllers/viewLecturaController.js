

    app.controller('viewLecturaController', function($scope, $http, API_URL) {

        $scope.lecturas = [];


        $scope.initData = function(){

            $http.get(API_URL + 'verLectura/getLecturas').success(function(response){
                $scope.lecturas = response;
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
