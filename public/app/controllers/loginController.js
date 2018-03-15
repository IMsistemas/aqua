
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

            if (response.success === false) {
                $scope.text_failed = 'Upss! Usuario y/o Contraseña incorrecto.';
                $('#view-failed-login').show();
            } else {
                location.reload(true);
            }

        }).error(function (res) {

        });

    };


    $scope.showConfirm = function () {

        $scope.user_reset = '';

        $('#modalResetPassword').modal('show');

    };


    $scope.resetPassword = function () {

        $('#modalResetPassword').modal('hide');

        $('#myModalTest').modal('show');

        var object = {
            user: $scope.user_reset
        };

        $http.post(API_URL + 'resetPassword', object ).success(function (response) {

            $('#myModalTest').modal('hide');

            if (response.success === true) {

                $scope.message = 'Se ha enviado por Email registrado la solicitud de actualización de Contraseña...';

                $('#modalMessage').modal('show');

            } else {

                if (response.email !== undefined) {

                    $scope.message_error = 'No se puede generar un password nuevo ya que el usuario no tiene email registrado...';

                } else if (response.user !== undefined) {

                    $scope.message_error = 'No se puede generar un password nuevo ya que el usuario no existe...';

                } else {

                    $scope.message_error = 'Ha ocurrido un error al intentar generar un password nuevo...';

                }

                $('#modalMessageError').modal('show');

            }

        }).error(function (res) {



        });

    };

});


