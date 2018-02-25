

app.controller('atsController', function($scope, $http, API_URL) {


    $scope.pageChanged = function(newPage) {
        $scope.initLoad(newPage);
    };

    $scope.initLoad = function(pageNumber){


    };


    $scope.generarShow = function () {

        $scope.year = '';
        $scope.month = '';

        $('#modalAction').modal('show');

    };

    $scope.save = function () {

        var data = {

            year: $scope.year,
            month: $scope.month

        };

        $http.post(API_URL + 'ats', data ).success(function (response) {

            console.log(response);

            $('#modalAction').modal('hide');

            if (response.success === true) {

                $scope.message = 'Se ha generado el XML correspondiente al Periodo solicitado...';

                $('#modalMessage').modal('show');

            } else {

                $scope.message_error = 'Ha ocurrido un error al intentar generar el XML correspondiente al Periodo solicitado...';

                $('#modalMessageError').modal('show');

            }

        });

    };

});
