

app.controller('atsController', function($scope, $http, API_URL) {


    $scope.pageChanged = function(newPage) {
        $scope.initLoad(newPage);
    };

    $scope.initLoad = function(){

        $('.datepickerY').datetimepicker({
            locale: 'es',
            format: 'YYYY',
            //ignoreReadonly: true
        });

        $('.datepickerM').datetimepicker({
            locale: 'es',
            format: 'MM',
            //ignoreReadonly: true
        });

        $http.get(API_URL + 'ats/getFiles').success(function(response){

            response = response.reverse();

            $scope.archivos = [];

            response.forEach(function (value) {

                var t = {

                    name: value,
                    url: 'uploads/ATS/' + value

                };

                $scope.archivos.push(t);

            });

        });

    };

    $scope.reafirmYear = function () {
        $scope.year = $('#year').val();
    };

    $scope.reafirmMonth = function () {
        $scope.month = $('#month').val();
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

            $('#modalAction').modal('hide');

            if (response.success === true) {

                $scope.initLoad();

                $scope.message = 'Se ha generado el XML correspondiente al Periodo solicitado...';

                $('#modalMessage').modal('show');

            } else {

                $scope.message_error = 'Ha ocurrido un error al intentar generar el XML correspondiente al Periodo solicitado...';

                $('#modalMessageError').modal('show');

            }

        });

    };

});
