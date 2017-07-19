
app.controller('loginController', function($scope, $http, API_URL) {

    $(document).keypress(function(e) {
        if(e.which === 13) {
            $scope.verifyLogin();
        }
    });

    $scope.verifyLogin = function () {

        var object = {
            user: $scope.t_usuario,
            pass: $scope.t_password
        };

        $http.post(API_URL, object ).success(function (response) {

            if (response.success == false) {
                $scope.text_failed = 'Upss! Usuario y/o Contraseña incorrecto.';
                $('#view-failed-login').show();
            } else {
                location.reload(true);
            }

        }).error(function (res) {

        });

    };

});


