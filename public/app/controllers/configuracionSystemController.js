
app.controller('configuracionSystemController', function($scope, $http, $parse, API_URL, Upload) {

    $scope.idestablecimiento = 0;
    $scope.fieldconcepto = '';
    $scope.fieldid = '';

    $scope.initLoad = function () {

        $scope.getDataEmpresa();

        $scope.getImpuestoIVA();

        $scope.getConfigCompra();

        $scope.getConfigVenta();

        $scope.getConfigNC();

        $scope.getConfigEspecifica();

        $scope.getConfigSRI();

        $scope.getListServicio();
    };

    //-----------------------------------------------------------------------------------------------------------------

    $scope.getDataEmpresa = function () {

        var array_temp = [{label: 'SI', id: '1'}, {label: 'NO', id: '0'}];
        $scope.obligadocont = array_temp;
        $scope.s_obligado = '1';

        $scope.url_foto = 'img/empleado.png';

        $http.get(API_URL + '/configuracion/getDataEmpresa').success(function(response){
            console.log(API_URL);
            if(response.length != 0){
                $scope.t_razonsocial = response[0].razonsocial;
                $scope.t_nombrecomercial = response[0].nombrecomercial;
                $scope.t_direccion = response[0].direccionestablecimiento;

                var temp_ruc = (response[0].ruc).split('-');

                $scope.t_establ = temp_ruc[0];
                $scope.t_pto = temp_ruc[1];
                $scope.t_secuencial = temp_ruc[2];

                $scope.t_contribuyente = response[0].contribuyenteespecial;

                if (response[0].obligadocontabilidad == true) {
                    $scope.s_obligado = '1';
                } else $scope.s_obligado = '0';

                $scope.idestablecimiento = response[0].idestablecimiento;

                if (response[0].rutalogo != null && response[0].rutalogo != ''){
                    $scope.url_foto =API_URL+ response[0].rutalogo;
                    $scope.file = API_URL+response[0].rutalogo;
                } else {

                    $scope.url_foto = 'img/empleado.png';
                }

                //$scope.url_foto = 'img/no-image-found.gif';

                $scope.url_foto = 'img/empleado.png';

            } else {

                $scope.t_establ = '000';
                $scope.t_pto = '000';
                $scope.t_secuencial = '0000000000000';


            }
        });
    };

    $scope.saveEstablecimiento = function () {

        //var ruc = $scope.t_establ + '-' + $scope.t_pto + '-' + $scope.t_secuencial;

        var ruc = $('#t_establ').val() + '-' + $('#t_pto').val() + '-' + $('#t_secuencial').val();

        var data = {
            razonsocial: $scope.t_razonsocial,
            nombrecomercial: $scope.t_nombrecomercial,
            direccionestablecimiento: $scope.t_direccion,
            contribuyenteespecial: $scope.t_contribuyente,
            ruc: ruc,
            obligadocontabilidad: $scope.s_obligado,
            rutalogo: $scope.file
        };
        console.log(data);

        var url = API_URL + "/configuracion";

        if ($scope.idestablecimiento != 0){
            url += "/updateEstablecimiento/" + $scope.idestablecimiento;
        }


        Upload.upload({
            url: url,
            method: 'POST',
            data: data
        }).success(function(data, status, headers, config) {
            if (data.success == true) {
                $scope.initLoad();
                $('#modalActionCargo').modal('hide');
                $scope.message = 'Se editó correctamente la configuracion del establecimiento';
                $('#modalMessage').modal('show');
                $scope.hideModalMessage();
            }
            else {
                $('#modalAction').modal('hide');
                $scope.message_error = 'Error...';
                $('#modalMessageError').modal('show');
            }
        });


        /*$http.put(API_URL + '/configuracion/'+ $scope.idestablecimiento, data ).success(function (response) {

            console.log(response);

            if (response.success == true) {
                $scope.initLoad();
                $('#modalActionCargo').modal('hide');
                $scope.message = 'Se editó correctamente los datos de la Empresa';
                $('#modalMessage').modal('show');
                $scope.hideModalMessage();
            } else {

            }

        }).error(function (res) {

        });*/
    };

    //-----------------------------------------------------------------------------------------------------------------

    $scope.getImpuestoIVA = function () {
        $http.get(API_URL + 'configuracion/getImpuestoIVA').success(function(response){
            var longitud = response.length;
            var array_temp = [{label: '-- Seleccione --', id: ''}];
            for(var i = 0; i < longitud; i++){
                array_temp.push({label: response[i].nametipoimpuestoiva, id: response[i].idtipoimpuestoiva})
            }
            $scope.imp_iva = array_temp;
            $scope.iva = '';

            $http.get(API_URL + '/configuracion/getIVADefault').success(function(response){
                console.log(response);

                if(response.length > 0){

                    $scope.idconfiguracionsystem = response[0].idconfiguracionsystem;

                    if (response[0].optionvalue != null && response[0].optionvalue != '') {
                        $scope.iva = parseInt(response[0].optionvalue);
                    }

                }
            });

        });
    };

    $scope.updateIvaDefault = function () {

        var data = {
            optionvalue: $scope.iva
        };
        console.log(data);

        $http.put(API_URL + '/configuracion/updateIvaDefault/'+ $scope.idconfiguracionsystem, data ).success(function (response) {

            console.log(response);

            if (response.success == true) {
                $scope.initLoad();
                $('#modalActionCargo').modal('hide');
                $scope.message = 'Se editó correctamente los datos del IVA por defecto';
                $('#modalMessage').modal('show');
                $scope.hideModalMessage();
            } else {

            }

        }).error(function (res) {

        });
    };

    //-----------------------------------------------------------------------------------------------------------------

    $scope.getConfigCompra = function () {
        $http.get(API_URL + 'configuracion/getConfigCompra').success(function(response){

            console.log(response);

            var longitud = response.length;

            for (var i = 0; i < longitud; i++) {
                if (response[i].optionname == 'CONT_IVA_COMPRA') {
                    $scope.id_iva_compra_h = response[i].idconfiguracionsystem;
                    $scope.iva_compra_h = parseInt(response[i].optionvalue);
                    $scope.iva_compra = response[i].concepto;
                } else if (response[i].optionname == 'CONT_ICE_COMPRA') {
                    $scope.id_ice_compra_h = response[i].idconfiguracionsystem;
                    $scope.ice_compra_h = parseInt(response[i].optionvalue);
                    $scope.ice_compra = response[i].concepto;
                } else if (response[i].optionname == 'CONT_IRBPNR_COMPRA') {
                    $scope.id_irbpnr_compra_h = response[i].idconfiguracionsystem;
                    $scope.irbpnr_compra_h = parseInt(response[i].optionvalue);
                    $scope.irbpnr_compra = response[i].concepto;
                } else if (response[i].optionname == 'CONT_PROPINA_COMPRA') {
                    $scope.id_propina_compra_h = response[i].idconfiguracionsystem;
                    $scope.propina_compra_h = parseInt(response[i].optionvalue);
                    $scope.propina_compra = response[i].concepto;
                } else if (response[i].optionname == 'SRI_RETEN_IVA_COMPRA') {
                    $scope.id_retiva_compra_h = response[i].idconfiguracionsystem;
                    $scope.retiva_compra_h = parseInt(response[i].optionvalue);
                    $scope.retiva_compra = response[i].concepto;
                } else if (response[i].optionname == 'SRI_RETEN_RENTA_COMPRA') {
                    $scope.id_retrenta_compra_h = response[i].idconfiguracionsystem;
                    $scope.retrenta_compra_h = parseInt(response[i].optionvalue);
                    $scope.retrenta_compra = response[i].concepto;
                }
            }

        });
    };

    $scope.saveConfigCompra = function () {
        var iva = {
            idconfiguracionsystem: $scope.id_iva_compra_h,
            optionvalue: $scope.iva_compra_h
        };
        var ice = {
            idconfiguracionsystem: $scope.id_ice_compra_h,
            optionvalue: $scope.ice_compra_h
        };
        var irbpnr = {
            idconfiguracionsystem: $scope.id_irbpnr_compra_h,
            optionvalue: $scope.irbpnr_compra_h
        };

        var retiva = {
            idconfiguracionsystem: $scope.id_retiva_compra_h,
            optionvalue: $scope.retiva_compra_h
        };

        var propina = {
            idconfiguracionsystem: $scope.id_propina_compra_h,
            optionvalue: $scope.propina_compra_h
        };

        var retrenta = {
            idconfiguracionsystem: $scope.id_retrenta_compra_h,
            optionvalue: $scope.retrenta_compra_h
        };

        var data = {
            array_data: [iva, ice, irbpnr, retiva, propina, retrenta]
        };

        $http.put(API_URL + '/configuracion/updateConfigCompra/0', data ).success(function (response) {

            if (response.success == true) {
                $scope.initLoad();
                $scope.message = 'Se editó correctamente los datos de la Configuración de Compras';
                $('#modalMessage').modal('show');
                $scope.hideModalMessage();
            } else {
                $scope.message_error = 'Ha ocurrido un error al actualizar los datos de la Configuración de Compras';
                $('#modalMessageError').modal('show');
                $scope.hideModalMessage();
            }


        }).error(function (res) {

        });
    };

    //-----------------------------------------------------------------------------------------------------------------

    $scope.getConfigVenta = function () {
        $http.get(API_URL + 'configuracion/getConfigVenta').success(function(response){

            console.log(response);

            var longitud = response.length;

            for (var i = 0; i < longitud; i++) {
                if (response[i].optionname == 'CONT_IVA_VENTA') {
                    $scope.id_iva_venta_h = response[i].idconfiguracionsystem;
                    $scope.iva_venta_h = parseInt(response[i].optionvalue);
                    $scope.iva_venta = response[i].concepto;
                }else if (response[i].optionname == 'CONT_ICE_VENTA') {
                    $scope.id_ice_venta_h = response[i].idconfiguracionsystem;
                    $scope.ice_venta_h = parseInt(response[i].optionvalue);
                    $scope.ice_venta = response[i].concepto;
                }else if (response[i].optionname == 'CONT_IRBPNR_VENTA') {
                    $scope.id_irbpnr_venta_h = response[i].idconfiguracionsystem;
                    $scope.irbpnr_venta_h = parseInt(response[i].optionvalue);
                    $scope.irbpnr_venta = response[i].concepto;
                }else if (response[i].optionname == 'CONT_PROPINA_VENTA') {
                    $scope.id_propina_venta_h = response[i].idconfiguracionsystem;
                    $scope.propina_venta_h = parseInt(response[i].optionvalue);
                    $scope.propina_venta = response[i].concepto;
                } else if (response[i].optionname == 'CONT_COSTO_VENTA') {
                    $scope.id_costo_venta_h = response[i].idconfiguracionsystem;
                    $scope.costo_venta_h = parseInt(response[i].optionvalue);
                    $scope.costo_venta = response[i].concepto;
                } else if (response[i].optionname == 'SRI_RETEN_IVA_VENTA') {
                    $scope.id_retiva_venta_h = response[i].idconfiguracionsystem;
                    $scope.retiva_venta_h = parseInt(response[i].optionvalue);
                    $scope.retiva_venta = response[i].concepto;
                } else if (response[i].optionname == 'SRI_RETEN_RENTA_VENTA') {
                    $scope.id_retrenta_venta_h = response[i].idconfiguracionsystem;
                    $scope.retrenta_venta_h = parseInt(response[i].optionvalue);
                    $scope.retrenta_venta = response[i].concepto;
                }
            }

        });
    };

    $scope.saveConfigVenta = function () {
        var iva = {
            idconfiguracionsystem: $scope.id_iva_venta_h,
            optionvalue: $scope.iva_venta_h
        };
        var ice = {
            idconfiguracionsystem: $scope.id_ice_venta_h,
            optionvalue: $scope.ice_venta_h
        };
        var irbpnr = {
            idconfiguracionsystem: $scope.id_irbpnr_venta_h,
            optionvalue: $scope.irbpnr_venta_h
        };

        var retiva = {
            idconfiguracionsystem: $scope.id_retiva_venta_h,
            optionvalue: $scope.retiva_venta_h
        };

        var propina = {
            idconfiguracionsystem: $scope.id_propina_venta_h,
            optionvalue: $scope.propina_venta_h
        };

        var retrenta = {
            idconfiguracionsystem: $scope.id_retrenta_venta_h,
            optionvalue: $scope.retrenta_venta_h
        };

        var costo = {
            idconfiguracionsystem: $scope.id_costo_venta_h,
            optionvalue: $scope.costo_venta_h
        };

        var data = {
            array_data: [iva, ice, irbpnr, retiva, propina, retrenta, costo]
        };

        $http.put(API_URL + '/configuracion/updateConfigVenta/0', data ).success(function (response) {

            if (response.success == true) {
                $scope.initLoad();
                $scope.message = 'Se editó correctamente los datos de la Configuración de Ventas';
                $('#modalMessage').modal('show');
                $scope.hideModalMessage();
            } else {
                $scope.message_error = 'Ha ocurrido un error al actualizar los datos de la Configuración de Ventas';
                $('#modalMessageError').modal('show');
                $scope.hideModalMessage();
            }

        }).error(function (res) {

        });
    };

    //-----------------------------------------------------------------------------------------------------------------

    $scope.getConfigNC = function () {
        $http.get(API_URL + 'configuracion/getConfigNC').success(function(response){

            console.log(response);

            var longitud = response.length;

            for (var i = 0; i < longitud; i++) {
                if (response[i].optionname == 'CONT_IVA_NC') {
                    $scope.id_iva_nc_h = response[i].idconfiguracionsystem;
                    $scope.iva_nc_h = parseInt(response[i].optionvalue);
                    $scope.iva_nc = response[i].concepto;
                }else if (response[i].optionname == 'CONT_ICE_NC') {
                    $scope.id_ice_nc_h = response[i].idconfiguracionsystem;
                    $scope.ice_nc_h = parseInt(response[i].optionvalue);
                    $scope.ice_nc = response[i].concepto;
                }else if (response[i].optionname == 'CONT_IRBPNR_NC') {
                    $scope.id_irbpnr_nc_h = response[i].idconfiguracionsystem;
                    $scope.irbpnr_nc_h = parseInt(response[i].optionvalue);
                    $scope.irbpnr_nc = response[i].concepto;
                } else if (response[i].optionname == 'CONT_PROPINA_NC') {
                    $scope.id_propina_nc_h = response[i].idconfiguracionsystem;
                    $scope.propina_nc_h = parseInt(response[i].optionvalue);
                    $scope.propina_nc = response[i].concepto;
                } else if (response[i].optionname == 'SRI_RETEN_IVA_NC') {
                    $scope.id_retiva_nc_h = response[i].idconfiguracionsystem;
                    $scope.retiva_nc_h = parseInt(response[i].optionvalue);
                    $scope.retiva_nc = response[i].concepto;
                } else if (response[i].optionname == 'SRI_RETEN_RENTA_NC') {
                    $scope.id_retrenta_nc_h = response[i].idconfiguracionsystem;
                    $scope.retrenta_nc_h = parseInt(response[i].optionvalue);
                    $scope.retrenta_nc = response[i].concepto;
                } else if (response[i].optionname == 'CONT_COSTO_NC') {
                    $scope.id_costo_nc_h = response[i].idconfiguracionsystem;
                    $scope.costo_nc_h = parseInt(response[i].optionvalue);
                    $scope.costo_nc = response[i].concepto;
                }
            }

        });
    };

    $scope.saveConfigNC = function () {
        var iva = {
            idconfiguracionsystem: $scope.id_iva_nc_h,
            optionvalue: $scope.iva_nc_h
        };
        var ice = {
            idconfiguracionsystem: $scope.id_ice_nc_h,
            optionvalue: $scope.ice_nc_h
        };
        var irbpnr = {
            idconfiguracionsystem: $scope.id_irbpnr_nc_h,
            optionvalue: $scope.irbpnr_nc_h
        };

        var retiva = {
            idconfiguracionsystem: $scope.id_retiva_nc_h,
            optionvalue: $scope.retiva_nc_h
        };

        var propina = {
            idconfiguracionsystem: $scope.id_propina_nc_h,
            optionvalue: $scope.propina_nc_h
        };

        var retrenta = {
            idconfiguracionsystem: $scope.id_retrenta_nc_h,
            optionvalue: $scope.retrenta_nc_h
        };

        var costo = {
            idconfiguracionsystem: $scope.id_costo_nc_h,
            optionvalue: $scope.costo_nc_h
        };

        var data = {
            array_data: [iva, ice, irbpnr, retiva, propina, retrenta, costo]
        };

        $http.put(API_URL + '/configuracion/updateConfigNC/0', data ).success(function (response) {

            if (response.success == true) {
                $scope.initLoad();
                $scope.message = 'Se editó correctamente los datos de la Configuración de Nota de Crédito';
                $('#modalMessage').modal('show');
                $scope.hideModalMessage();
            } else {
                $scope.message_error = 'Ha ocurrido un error al actualizar los datos de la Configuración de Nota de Crédito';
                $('#modalMessageError').modal('show');
                $scope.hideModalMessage();
            }

        }).error(function (res) {

        });
    };

    //-----------------------------------------------------------------------------------------------------------------

    $scope.getConfigEspecifica = function () {
        $http.get(API_URL + 'configuracion/getConfigEspecifica').success(function(response){

            var longitud = response.length;

            for (var i = 0; i < longitud; i++) {

                //-----PARA SISTEMA PISQUE (RIEGO)------------------------------------------------

                /*if (response[i].optionname == 'PISQUE_CONSTANTE') {
                    $scope.h_pisque_constante = response[i].idconfiguracionsystem;
                    $scope.t_pisque_constante = response[i].optionvalue;
                }*/

                //-----PARA SISTEMA AYORA (POTABLE)-----------------------------------------------

                if (response[i].optionname == 'AYORA_DIVIDENDO') {
                     $scope.h_ayora_dividendos = response[i].idconfiguracionsystem;
                     $scope.t_ayora_dividendos = response[i].optionvalue;
                } else if (response[i].optionname == 'AYORA_TASAINTERES') {
                     $scope.h_ayora_tasainteres = response[i].idconfiguracionsystem;
                     $scope.t_ayora_tasainteres = response[i].optionvalue;
                }
            }

        });
    };

    $scope.saveConfigEspecifica = function () {

        //-----PARA SISTEMA PISQUE (RIEGO)------------------------------------------------

        /*var pisque_constante = {
            idconfiguracionsystem: $scope.h_pisque_constante,
            optionvalue: $scope.t_pisque_constante
        };

        var data = {
            array_data: [pisque_constante]
        };*/

        //-----PARA SISTEMA AYORA (POTABLE)-----------------------------------------------

        var ayora_dividendo = {
            idconfiguracionsystem: $scope.h_ayora_dividendos,
            optionvalue: $scope.t_ayora_dividendos
        };

        var ayora_tasainteres = {
            idconfiguracionsystem: $scope.h_ayora_tasainteres,
            optionvalue: $scope.t_ayora_tasainteres
        };

        var data = {
            array_data: [ayora_dividendo, ayora_tasainteres]
        };

        $http.put(API_URL + '/configuracion/updateConfigEspecifica/0', data ).success(function (response) {

            if (response.success == true) {
                $scope.initLoad();
                $scope.message = 'Se editó correctamente los datos de la Configuración Específica';
                $('#modalMessage').modal('show');
                $scope.hideModalMessage();
            } else {
                $scope.message_error = 'Ha ocurrido un error al actualizar los datos de la Configuración Específica';
                $('#modalMessageError').modal('show');
                $scope.hideModalMessage();
            }

        }).error(function (res) {

        });
    };

    $scope.getListServicio = function () {
        $http.get(API_URL + 'configuracion/getListServicio').success(function(response0){

            var longitud = response0.length;
            var array_temp = [{label: '-- Seleccione --', id: ''}];
            for(var i = 0; i < longitud; i++){
                array_temp.push({label: response0[i].nombreproducto, id: response0[i].idcatalogitem})
            }

            $scope.list_serv = array_temp;

            $scope.serv_lect_tar = '';
            $scope.serv_lect_ex = '';
            $scope.serv_lect_alcant = '';
            $scope.serv_lect_rds = '';
            $scope.serv_lect_ma = '';

            $http.get(API_URL + 'configuracion/getSaveServicio').success(function(response){

                var longitud = response.length;

                for (var i = 0; i < longitud; i++) {
                    if (response[i].optionname === 'SERV_TARIFAB_LECT') {

                        $scope.serv_lect_tar_h = response[i].idconfiguracionsystem;
                        if (response[i].optionvalue !== null){
                            $scope.serv_lect_tar = parseInt(response[i].optionvalue);
                        }

                    } else if (response[i].optionname === 'SERV_EXCED_LECT') {

                        $scope.serv_lect_ex_h = response[i].idconfiguracionsystem;
                        if (response[i].optionvalue !== null){
                            $scope.serv_lect_ex = parseInt(response[i].optionvalue);
                        }

                    } else if (response[i].optionname === 'SERV_ALCANT_LECT') {

                        $scope.serv_lect_alcant_h = response[i].idconfiguracionsystem;
                        if (response[i].optionvalue !== null){
                            $scope.serv_lect_alcant = parseInt(response[i].optionvalue);
                        }

                    } else if (response[i].optionname === 'SERV_RRDDSS_LECT') {

                        $scope.serv_lect_rds_h = response[i].idconfiguracionsystem;
                        if (response[i].optionvalue !== null){
                            $scope.serv_lect_rds = parseInt(response[i].optionvalue);
                        }

                    } else if (response[i].optionname === 'SERV_MEDAMB_LECT') {

                        $scope.serv_lect_ma_h = response[i].idconfiguracionsystem;
                        if (response[i].optionvalue !== null){
                            $scope.serv_lect_ma = parseInt(response[i].optionvalue);
                        }

                    }
                }

            });

        });
    };

    $scope.saveListServicio = function () {
        var tar = {
            idconfiguracionsystem: $scope.serv_lect_tar_h,
            optionvalue: $scope.serv_lect_tar
        };

        var ex = {
            idconfiguracionsystem: $scope.serv_lect_ex_h,
            optionvalue: $scope.serv_lect_ex
        };

        var alcant = {
            idconfiguracionsystem: $scope.serv_lect_alcant_h,
            optionvalue: $scope.serv_lect_alcant
        };

        var rds = {
            idconfiguracionsystem: $scope.serv_lect_rds_h,
            optionvalue: $scope.serv_lect_rds
        };

        var ma = {
            idconfiguracionsystem: $scope.serv_lect_ma_h,
            optionvalue: $scope.serv_lect_ma
        };

        var data = {
            array_data: [tar, ex, alcant, rds, ma]
        };

        $http.put(API_URL + '/configuracion/updateListServicio/0', data ).success(function (response) {

            if (response.success === true) {
                $scope.message = 'Se editó correctamente los datos la estructura de Lectura';
                $('#modalMessage').modal('show');
                $scope.hideModalMessage();
            } else {
                $scope.message_error = 'Ha ocurrido un error al actualizar los datos de la estructura de Lectura';
                $('#modalMessageError').modal('show');
                $scope.hideModalMessage();
            }

        }).error(function (res) {

        });

    };

    //-----------------------------------------------------------------------------------------------------------------

    $scope.getConfigSRI = function () {
        $http.get(API_URL + 'configuracion/getTipoAmbiente').success(function(response){
            var longitud = response.length;
            var array_temp = [{label: '-- Seleccione --', id: ''}];
            for(var i = 0; i < longitud; i++){
                array_temp.push({label: response[i].nametipoambiente, id: response[i].idtipoambiente})
            }
            $scope.tipoambiente = array_temp;
            $scope.s_sri_tipoambiente = '';

            $http.get(API_URL + 'configuracion/getTipoEmision').success(function(response){
                var longitud = response.length;
                var array_temp = [{label: '-- Seleccione --', id: ''}];
                for(var i = 0; i < longitud; i++){
                    array_temp.push({label: response[i].nametipoemision, id: response[i].idtipoemision})
                }
                $scope.tipoemision = array_temp;
                $scope.s_sri_tipoemision = '';

                $http.get(API_URL + 'configuracion/getConfigSRI').success(function(response){

                    var longitud = response.length;

                    for (var i = 0; i < longitud; i++) {
                        if (response[i].optionname == 'SRI_TIPO_AMBIENTE') {

                            $scope.h_sri_tipoambiente = response[i].idconfiguracionsystem;

                            if (response[i].optionvalue != null && response[i].optionvalue != '') {
                                $scope.s_sri_tipoambiente = parseInt(response[i].optionvalue);
                            }

                        } else if (response[i].optionname == 'SRI_TIPO_EMISION') {

                            $scope.h_sri_tipoemision = response[i].idconfiguracionsystem;

                            if (response[i].optionvalue != null && response[i].optionvalue != '') {
                                $scope.s_sri_tipoemision = parseInt(response[i].optionvalue);
                            }

                        }
                    }

                });

            });

        });

    };

    $scope.saveConfigSRI = function () {

        var tipoambiente = {
            idconfiguracionsystem: $scope.h_sri_tipoambiente,
            optionvalue: $scope.s_sri_tipoambiente
        };

        var tipoemision = {
            idconfiguracionsystem: $scope.h_sri_tipoemision,
            optionvalue: $scope.s_sri_tipoemision
        };

        var data = {
            array_data: [tipoambiente, tipoemision]
        };

        $http.put(API_URL + '/configuracion/updateConfigSRI/0', data ).success(function (response) {

            if (response.success == true) {
                $scope.initLoad();
                $scope.message = 'Se editó correctamente los datos de la Configuración SRI';
                $('#modalMessage').modal('show');
                $scope.hideModalMessage();
            } else {
                $scope.message_error = 'Ha ocurrido un error al actualizar los datos de la Configuración SRI';
                $('#modalMessageError').modal('show');
                $scope.hideModalMessage();
            }

        }).error(function (res) {

        });
    };

    //-----------------------------------------------------------------------------------------------------------------

    $scope.showPlanCuenta = function (field_concepto, field_id) {

        $scope.fieldconcepto = field_concepto;
        $scope.fieldid = field_id;

        $http.get(API_URL + 'configuracion/getPlanCuenta').success(function(response){
            $scope.cuentas = response;
            $('#modalPlanCuenta').modal('show');
        });

    };

    $scope.clean = function (field_concepto, field_id) {

        $scope.field1 = field_concepto;
        $scope.field2 = field_id;

        var fieldconcepto = $parse($scope.field1);
        fieldconcepto.assign($scope, '');

        var fieldid = $parse($scope.field2);
        fieldid.assign($scope, '');
    };

    $scope.selectCuenta = function () {

        var selected = $scope.select_cuenta;

        var fieldconcepto = $parse($scope.fieldconcepto);
        fieldconcepto.assign($scope, selected.concepto);

        var fieldid = $parse($scope.fieldid);
        fieldid.assign($scope, selected.idplancuenta);

        $('#modalPlanCuenta').modal('hide');

    };

    $scope.click_radio = function (item) {
        $scope.select_cuenta = item;
    };

    $scope.hideModalMessage = function () {
        setTimeout("$('#modalMessage').modal('hide')", 3000);
    };

    $scope.onlyNumber = function ($event, length, field) {

        if (length != undefined) {
            var valor = $('#' + field).val();
            if (valor.length == length) $event.preventDefault();
        }

        var k = $event.keyCode;
        if (k == 8 || k == 0) return true;
        var patron = /\d/;
        var n = String.fromCharCode(k);

        if (n == ".") {
            return true;
        } else {

            if(patron.test(n) == false){
                $event.preventDefault();
            }
            else return true;
        }
    };

    $scope.onlyDecimal = function ($event) {
        var k = $event.keyCode;
        if (k == 8 || k == 0) return true;
        var patron = /\d/;
        var n = String.fromCharCode(k);
        if (n == ".") {
            return true;
        } else {
            if(patron.test(n) == false){
                $event.preventDefault();
            }
            else return true;
        }
    };

    $scope.calculateLength = function(field, length) {
        var text = $("#" + field).val();
        var longitud = text.length;
        if (longitud == length) {
            $("#" + field).val(text);
        } else {
            var diferencia = parseInt(length) - parseInt(longitud);
            var relleno = '';
            if (diferencia == 1) {
                relleno = '0';
            } else {
                var i = 0;
                while (i < diferencia) {
                    relleno += '0';
                    i++;
                }
            }
            $("#" + field).val(relleno + text);
        }
    };


    $scope.initLoad();


});


$(function () {
    $('[data-toggle="tooltip"]').tooltip();
});

function fecha(){
    var f = new Date();
    var fecha = "";
    var dd = f.getDate();
    if (dd < 10) dd = '0' + dd;
    var mm = f.getMonth() + 1;
    if (mm < 10) mm = '0' + mm;
    var yyyy = f.getFullYear();
    fecha = dd + "\/" + mm + "\/" + yyyy;

    return fecha;
}

function convertDatetoDB(now, revert){
    if (revert == undefined){
        var t = now.split('/');
        return t[2] + '-' + t[1] + '-' + t[0];
    } else {
        var t = now.split('-');
        return t[2] + '/' + t[1] + '/' + t[0];
    }
}

