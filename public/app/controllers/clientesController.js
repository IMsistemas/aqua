    app.filter('formatDate', function(){
        return function(texto){
            return convertDatetoDB(texto, true);
        }
    });

    app.controller('clientesController', function($scope, $http, API_URL) {

        $scope.clientes = [];
        $scope.codigocliente_del = 0;

        $scope.initLoad = function () {
            $http.get(API_URL + 'cliente/getClientes').success(function(response){
                console.log(response);
                $scope.clientes = response;
            });
        };

        $scope.nowDate = function () {
            var now = new Date();
            var dd = now.getDate();
            if (dd < 10) dd = '0' + dd;
            var mm = now.getMonth() + 1;
            if (mm < 10) mm = '0' + mm;
            var yyyy = now.getFullYear();
            return dd + "\/" + mm + "\/" + yyyy;
        };

        $scope.convertDatetoDB = function (now, revert) {
            if (revert == undefined){
                var t = now.split('/');
                return t[2] + '-' + t[1] + '-' + t[0];
            } else {
                var t = now.split('-');
                return t[2] + '/' + t[1] + '/' + t[0];
            }
        };

        /*
         *  ACTION FOR CLIENT-------------------------------------------------------------------
         */

        $scope.edit = function (item) {
            $scope.t_codigocliente = item.codigocliente;
            $scope.t_fecha_ingreso = $scope.convertDatetoDB(item.fechaingreso, true);
            $scope.t_doc_id = item.documentoidentidad;
            $scope.t_email = item.correo;
            $scope.t_apellidos = item.apellidos;
            $scope.t_nombres = item.nombres;
            $scope.t_telf_principal = item.telefonoprincipaldomicilio;
            $scope.t_telf_secundario = item.telefonosecundariodomicilio;
            $scope.t_celular = item.celular;
            $scope.t_direccion = item.direcciondomicilio;
            $scope.t_telf_principal_emp = item.telefonoprincipaltrabajo;
            $scope.t_telf_secundario_emp = item.telefonosecundariotrabajo;
            $scope.t_direccion_emp = item.direcciontrabajo;

            $http.get(API_URL + 'cliente/getTipoCliente').success(function(response) {
                var longitud = response.length;
                var array_temp = [{label: '--Seleccione--', id: 0}];
                //var array_temp = [];
                for (var i = 0; i < longitud; i++) {
                    array_temp.push({label: response[i].nombretipo, id: response[i].id})
                }
                $scope.tipo_cliente = array_temp;

                $scope.t_tipocliente = parseInt(item.id);

                $scope.title_modal_cliente = 'Editar Cliente';

                $('#modalAddCliente').modal('show');
            });

            /*var id =  item.id;
            $http.delete(API_URL + 'cliente/getTipoClienteByID/'+id).success(function(response){
               console.log(response);
               $scope.t_tipocliente = response.id;
            });*/


        };

        $scope.saveCliente = function () {
            if($scope.t_tipocliente > 0) {
                var data = {
                    fechaingreso: $scope.convertDatetoDB($scope.t_fecha_ingreso),
                    codigocliente: $scope.t_doc_id,
                    apellido: $scope.t_apellidos,
                    nombre: $scope.t_nombres,
                    telefonoprincipal: $scope.t_telf_principal,
                    telefonosecundario: $scope.t_telf_secundario,
                    celular: $scope.t_celular,
                    direccion: $scope.t_direccion,
                    telfprincipalemp: $scope.t_telf_principal_emp,
                    telfsecundarioemp: $scope.t_telf_secundario_emp,
                    direccionemp: $scope.t_direccion_emp,
                    tipocliente: $scope.t_tipocliente,
                    email: $scope.t_email
                };

                var url = API_URL + "cliente";

                if ($scope.t_codigocliente == 0) {

                    $http.post(url, data).success(function (response) {
                        $scope.initLoad();
                        $('#modalAddCliente').modal('hide');
                        $scope.message = 'Se insertó correctamente el cliente...';
                        $('#modalMessage').modal('show');
                    }).error(function (res) {
                        console.log(res);
                    });

                } else {
                    url += '/' + $scope.t_codigocliente;

                    $http.put(url, data).success(function (response) {
                        $scope.initLoad();
                        $('#modalAddCliente').modal('hide');
                        $scope.message = 'Se edito correctamente el Cliente seleccionado...';
                        $('#modalMessage').modal('show');
                    }).error(function (res) {

                    });
                }
            }else {
                $scope.message_error = 'Debe seleccionar el tipo de cliente...';
                $('#modalMessageError').modal('show');

            }

        };

        $scope.deleteCliente = function(){
            $http.delete(API_URL + 'cliente/' + $scope.codigocliente_del).success(function(response) {
                $scope.initLoad();
                $('#modalDeleteCliente').modal('hide');
                $scope.codigocliente_del = 0;
                $scope.message = 'Se eliminó correctamente el Cliente seleccionado...';
                $('#modalMessage').modal('show');
            });
        };

        $scope.showModalAddCliente = function ()            {
            $scope.t_codigocliente = 0;
            $scope.t_fecha_ingreso = $scope.nowDate();
            $scope.t_doc_id = '';
            $scope.t_apellidos = '';
            $scope.t_nombres = '';
            $scope.t_telf_principal = '';
            $scope.t_telf_secundario = '';
            $scope.t_celular = '';
            $scope.t_direccion = '';
            $scope.t_telf_principal_emp = '';
            $scope.t_telf_secundario_emp = '';
            $scope.t_direccion_emp = '';
            $scope.t_email = '';
            $scope.t_tipocliente = 0;

            $http.get(API_URL + 'cliente/getTipoCliente').success(function(response) {
                var longitud = response.length;
                var array_temp = [{label: '--Seleccione--', id: 0}];
                //var array_temp = [];
                for (var i = 0; i < longitud; i++) {
                    array_temp.push({label: response[i].nombretipo, id: response[i].id})
                }
                $scope.tipo_cliente = array_temp;
            });

            $scope.title_modal_cliente = 'Nuevo Cliente';

            $('#modalAddCliente').modal('show');
        };

        $scope.showModalDeleteCliente = function (item) {
            $scope.codigocliente_del = item.codigocliente;
            $scope.nom_cliente = item.apellidos + ' ' + item.nombres;
            $('#modalDeleteCliente').modal('show');
        };

        $scope.showModalInfoCliente = function (item) {
            $scope.name_cliente = item.apellidos + ' ' + item.nombres;
            $scope.identify_cliente = item.documentoidentidad;
            $scope.fecha_solicitud = item.fechaingreso;
            $scope.address_cliente = item.direcciondomicilio;
            $scope.email_cliente = item.correo;
            $scope.celular_cliente = item.celular;
            $scope.telf_cliente = item.telefonoprincipaldomicilio + ' / ' + item.telefonosecundariodomicilio;
            $scope.telf_cliente_emp = item.telefonoprincipaltrabajo + ' / ' + item.telefonosecundariotrabajo;

            $scope.tipo_cliente = item.tipocliente.nombretipo;

            if (item.estaactivo == true){
                $scope.estado_solicitud = 'Activo';
            } else {
                $scope.estado_solicitud = 'Inactivo';
            }

             /*var id =  item.id;
             console.log(id);
             $http.delete(API_URL + 'cliente/getTipoClienteByID/'+id).success(function(response){
                 console.log(responde);
            // $scope.tipo_cliente = response.nombretipo;
             });*/

            $('#modalInfoCliente').modal('show');

        };

        $scope.initLoad();


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