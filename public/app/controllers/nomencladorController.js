/**
 * Created by Rafael on 20/02/2017.
 */




app.controller('NomencladorController', function($scope, $http, API_URL, Upload) {

    $scope.sri_tipodocumento = [];
    $scope.idtipodocumento_del = 0;
    $scope.modalstate = '';

    $scope.sri_tipoidentificacion = [];
    $scope.tipoidentificacion_del = 0;

    $scope.sri_tipoimpuesto = [];
    $scope.tipoimpuesto_del = 0;

    $scope.sri_tipoimpuestoIVA = [];
    $scope.tipoimpuestoIVA_del = 0;

    $scope.sri_tipoimpuestoICE = [];
    $scope.tipoimpuestoICE_del = 0;


    $scope.sri_tipoimpuestoRten = [];
    $scope.tipoimpuestoRten_del = 0;


    $scope.sri_ImpuestoIVARENTA = [];
    $scope.ImpuestoIVARENTA_del = 0;


    $scope.sri_SustentoTributario = [];
    $scope.SustentoTributario_del = 0;


    $scope.sri_Comprobante = [];
    $scope.Comprobante_del = 0;

    $scope.sri_pagopais = [];
    $scope.pagopais_del = 0;

    $scope.Con_FormaPago = [];
    $scope.FormaPago_del = 0;

    $scope.provincia = [];
    $scope.provincia_del = 0;

    $scope.canton = [];
    $scope.canton_del = 0;

    $scope.parroquia = [];
    $scope.parroquia_del = 0;

    //$scope.CargadataProvincia();





    /*$scope.pageChanged = function(newPage) {
     console.log(newPage);
     $scope.CargadataProvincia(newPage);
     $scope.CargadataTPident(newPage);
     $scope.CargadataTPimp(newPage);
     $scope.CargadataImpIVA(newPage);
     $scope.CargadataImpICE(newPage);
     $scope.initLoad(newPage);
     $scope.initLoad(newPage);
     $scope.initLoad(newPage);

     };*/


    $scope.CargadataTPdoc = function (pageNumber){



        if ($scope.busqueda == undefined) {
            var search = null;
        } else var search = $scope.busqueda;

        var filtros = {
            search: search
        };


        $http.get(API_URL + 'Nomenclador/getTipoDocumento?page=' + pageNumber + '&filter=' + JSON.stringify(filtros)).success(function(response){
            $scope.sri_tipodocumento = response.data;
            $scope.totalItemstpdoc = response.total;
        });
    };



    $scope.CargadataTPident = function (pageNumber) {

        if ($scope.busqueda == undefined) {
            var search = null;
        } else var search = $scope.busqueda;

        var filtros = {
            search: search
        };


        $http.get(API_URL + 'Nomenclador/gettipoidentificacion?page=' + pageNumber + '&filter=' + JSON.stringify(filtros)).success(function(response){

            $scope.sri_tipoidentificacion = response.data;
            $scope.totalItemstpident = response.total;
            //console.log($scope.sri_tipoidentificacion);
        });

    };

    $scope.CargadataTPimp = function (pageNumber) {

        if ($scope.busqueda == undefined) {
            var search = null;
        } else var search = $scope.busqueda;

        var filtros = {
            search: search
        };


        $http.get(API_URL + 'Nomenclador/getTipoImpuesto?page=' + pageNumber + '&filter=' + JSON.stringify(filtros)).success(function(response){

            $scope.sri_tipoimpuesto = response.data;
            $scope.totalItemstpimp = response.total;

        });
    };

    $scope.CargadataImpIVA = function (pageNumber) {

        if ($scope.busqueda == undefined) {
            var search = null;
        } else var search = $scope.busqueda;

        var filtros = {
            search: search
        };
        $http.get(API_URL + 'Nomenclador/getImpuestoIVA?page=' + pageNumber + '&filter=' + JSON.stringify(filtros)).success(function(response){

            $scope.sri_tipoimpuestoIVA = response.data;
            $scope.totalItemstpimpiva = response.total;

        });
    };

    $scope.CargadataTPimpEx = function (pageNumber) {

        if ($scope.busqueda == undefined) {
            var search = null;
        } else var search = $scope.busqueda;

        var filtros = {
            search: search
        };


        $http.get(API_URL + 'Nomenclador/getTipoImpuestoEx?page=' + pageNumber + '&filter=' + JSON.stringify(filtros)).success(function(response){

            $scope.sri_tipoimpuesto = response.data;
            $scope.totalItemstpimpEx = response.total;


        });
    };


    $scope.CargadataImpICE = function (pageNumber) {

        if ($scope.busqueda == undefined) {
            var search = null;
        } else var search = $scope.busqueda;

        var filtros = {
            search: search
        };
        //console.log("aqui estoy");

        $http.get(API_URL + 'Nomenclador/getImpuestoICE?page=' + pageNumber + '&filter=' + JSON.stringify(filtros)).success(function(response){

            $scope.sri_tipoimpuestoICE = response.data;
            $scope.totalItemstpimpicepg = response.total;

        });
    };


    $scope.CargadataTipoImpRetenc = function (pageNumber) {

        if ($scope.busqueda == undefined) {
            var search = null;
        } else var search = $scope.busqueda;

        var filtros = {
            search: search
        };


        $http.get(API_URL + 'Nomenclador/getTipoImpuestoRetenc?page=' + pageNumber + '&filter=' + JSON.stringify(filtros)).success(function(response){

            $scope.sri_tipoimpuestoRten = response.data;
            $scope.totalItemstpimpretpg01 = response.total;
            //console.log($scope.totalItemstpimpretpg01);

        });
    };

    $scope.CargadataImpIVARENTA = function (pageNumber) {

        if ($scope.busqueda == undefined) {
            var search = null;
        } else var search = $scope.busqueda;

        var filtros = {
            search: search
        };

        $http.get(API_URL + 'Nomenclador/getImpuestoIVARENTA?page=' + pageNumber + '&filter=' + JSON.stringify(filtros)).success(function(response){

            $scope.sri_ImpuestoIVARENTA = response.data;
            $scope.totalItemstpimpretcivapg = response.total;
            //console.log($scope.totalItemstpimpretcivapg);
        });
    };

    $scope.CargadataSustentoTrib = function (pageNumber) {

        if ($scope.busqueda == undefined) {
            var search = null;
        } else var search = $scope.busqueda;

        var filtros = {
            search: search
        };
        $http.get(API_URL + 'Nomenclador/getSustentoTributario?page=' + pageNumber + '&filter=' + JSON.stringify(filtros)).success(function(response){

            $scope.sri_SustentoTributario = response.data;
            $scope.totalItemstpimpsustpg = response.total;
        });
    };

    $scope.CargadataSustentoTribEX = function (pageNumber) {

        if ($scope.busqueda == undefined) {
            var search = null;
        } else var search = $scope.busqueda;

        var filtros = {
            search: search
        };
        $http.get(API_URL + 'Nomenclador/getSustentoTributarioEX?page=' + pageNumber + '&filter=' + JSON.stringify(filtros)).success(function(response){

            $scope.sri_SustentoTributario = response.data;
            $scope.totalItems = response.total;
        });
    };


    $scope.CargadataComprobante = function (pageNumber) {

        if ($scope.busqueda == undefined) {
            var search = null;
        } else var search = $scope.busqueda;

        var filtros = {
            search: search
        };
        //console.log("aqui estoy");

        $http.get(API_URL + 'Nomenclador/getTipoComprobante?page=' + pageNumber + '&filter=' + JSON.stringify(filtros)).success(function(response){


//            console.log(response);
            $scope.sri_Comprobante = response.data;
            $scope.totalItemstpcomppg = response.total;

        });
    };

    $scope.CargadataPagoResidente = function (pageNumber) {

        if ($scope.busqueda == undefined) {
            var search = null;
        } else var search = $scope.busqueda;

        var filtros = {
            search: search
        };

        $http.get(API_URL + 'Nomenclador/getPagoResidente?page=' + pageNumber + '&filter=' + JSON.stringify(filtros)).success(function(response){

            $scope.sri_PagoResidente = response.data;
            $scope.totalItemstpresindentpgs = response.total;

        });
    };

    $scope.CargadataPagoPais = function (pageNumber) {

        if ($scope.busqueda == undefined) {
            var search = null;
        } else var search = $scope.busqueda;

        var filtros = {
            search: search
        };
        $http.get(API_URL + 'Nomenclador/getPagoPais?page=' + pageNumber + '&filter=' + JSON.stringify(filtros)).success(function(response){

            $scope.sri_pagopais = response.data;
            $scope.totalItemstppaispg = response.total;
        });
    };

    $scope.CargadataFormaPago = function (pageNumber) {

        if ($scope.busqueda == undefined) {
            var search = null;
        } else var search = $scope.busqueda;

        var filtros = {
            search: search
        };
        $http.get(API_URL + 'Nomenclador/getContFormaPago?page=' + pageNumber + '&filter=' + JSON.stringify(filtros)).success(function(response){

            $scope.Con_FormaPago = response.data;
            $scope.totalItemsformapago = response.total;
        });
    };

    $scope.CargadataProvincia = function (pageNumber) {

        if ($scope.busqueda == undefined) {
            var search = null;
        } else var search = $scope.busqueda;

        var filtros = {
            search: search
        };
        $http.get(API_URL + 'Nomenclador/getprovincia?page=' + pageNumber + '&filter=' + JSON.stringify(filtros)).success(function(response){
            $scope.provincia = response.data;
            $scope.totalItemsprv = response.total;
        });
    };

    $scope.CargadataProvinciaEX = function (pageNumber) {

        if ($scope.busqueda == undefined) {
            var search = null;
        } else var search = $scope.busqueda;

        var filtros = {
            search: search
        };
        $http.get(API_URL + 'Nomenclador/getprovinciaEX?page=' + pageNumber + '&filter=' + JSON.stringify(filtros)).success(function(response){

            $scope.provincia = response.data;
            $scope.totalItems = response.total;
        });
    };

    $scope.CargadataCanton = function (pageNumber) {

        if ($scope.busqueda == undefined) {
            var search = null;
        } else var search = $scope.busqueda;

        var filtros = {
            search: search
        };

        $http.get(API_URL + 'Nomenclador/getCantonEX?page=' + pageNumber + '&filter=' + JSON.stringify(filtros)).success(function(response){
            $scope.canton = response.data;
            $scope.totalItemscanton = response.total;

        });

    };

    $scope.CargadataCantonA = function (pageNumber) {

        if ($scope.busqueda == undefined) {
            var search = null;
        } else var search = $scope.busqueda;

        var filtros = {
            search: search
        };

        $http.get(API_URL + 'Nomenclador/getCantonEXA?page=' + pageNumber + '&filter=' + JSON.stringify(filtros)).success(function(response){
            //console.log(response);
            $scope.canton = response.data;
            $scope.totalItems = response.total;

        });

    };



    $scope.CargadataParroquia = function (pageNumber) {

        if ($scope.busqueda == undefined) {
            var search = null;
        } else var search = $scope.busqueda;

        var filtros = {
            search: search
        };

        $http.get(API_URL + 'Nomenclador/getParroquiaEX?page=' + pageNumber + '&filter=' + JSON.stringify(filtros)).success(function(response){
            //console.log(response);
            $scope.parroquia = response.data;
            $scope.totalItemsparroquia = response.total;

        });

    };


    $scope.toggle = function(modalstate, id, obafect) {
        $scope.modalstate = modalstate;


        switch (modalstate) {
            case 'add':
                if (obafect == "tpdocsri") {
                    $scope.form_title = "Nuevo Tipo Documento";
                    $scope.nametipodocumento = '';
                    $scope.codigosri = '';
                    $scope.estado = 'true';
                    $('#modalActionTipoDoc').modal('show');
                }
                if (obafect == "tpidentsri") {
                    $scope.form_title = "Nuevo Tipo Identificacion";
                    $scope.nametipoident = '';
                    $scope.codigosri = '';
                    $scope.estado = 'true';
                    $('#modalActionTipoIdent').modal('show');
                }

                if (obafect == "timpsri") {
                    $scope.form_title = "Nuevo Tipo Impuesto";
                    $scope.nametipoimpuest = '';
                    $scope.codigosri = '';
                    $scope.estado = 'true';
                    $('#modalActionTipoImp').modal('show');
                }

                if (obafect == "tpimpivasri") {
                    $scope.form_title = "Nuevo Tipo Impuesto Iva ";
                    $scope.nameimpuestoiva = '';
                    $scope.porcentaje = '0.00';
                    $scope.codigosri = '';
                    $scope.TipoImpuesto = '1'; // iva
                    $scope.estado = 'true';
                    $('#modalActionImpuestoIva').modal('show');
                }

                if (obafect == "tpimpicesri") {
                    $scope.form_title = "Nuevo Tipo Impuesto Ice ";
                    $scope.nameimpuestoice = '';
                    $scope.porcentaje = '0.00';
                    $scope.codigosri = '';
                    $scope.TipoImpuesto = '2'; // ice
                    $scope.estado = 'true';
                    $('#modalActionImpuestoIce').modal('show');
                }

                if (obafect == "tpimpretsri") {
                    $scope.form_title = "Nuevo Tipo Impuesto Retencion Renta";
                    $scope.nametipoimpuestoret = '';
                    $scope.codigosri = '';
                    $scope.estado = 'true';
                    $('#modalActionTipoImpRetRenta').modal('show');
                }

                if (obafect == "tpimpivaretsri") {
                    $scope.form_title = "Nuevo Tipo Impuesto Retencion";
                    $scope.TipoImpuesto = '1';
                    $scope.nametipoimpuestoivaret = '';
                    $scope.porcentaje = '0.00';
                    $scope.codigosri = '';
                    $scope.estado = 'true';
                    $('#modalActionTipoImpIvaRetRenta').modal('show');
                }

                if (obafect == "sustrib") {
                    $scope.form_title = "Nuevo Sustento Tributario";
                    $scope.nameSustentoTributario = '';
                    $scope.codigosrisustento = '';
                    $scope.estado = 'true';
                    $('#modalActionSustentoTributario').modal('show');
                }

                if (obafect == "compsust") {
                    $scope.form_title = "Nuevo Comprobante Sustento Tributario";
                    $scope.TipoSustento = '1';
                    $scope.namecomprobante = '';
                    $scope.codigosri = '';
                    $scope.estado = 'true';
                    $('#modalActionCompSustentoTributario').modal('show');
                }

                if (obafect == "tppagores") {
                    $scope.form_title = "Nuevo Tipo Pago Residente";
                    $scope.tipopagoresidente = '';
                    $('#modalActionPagoResidente').modal('show');
                }

                if (obafect == "pagopais") {
                    $scope.form_title = "Nuevo Pais ";
                    $scope.pais = '';
                    $scope.codigosri = '';
                    $('#modalActionPaisPago').modal('show');
                }

                if (obafect == "formapago") {
                    $scope.form_title = "Nueva Forma Pago ";
                    $scope.nameformapago = '';
                    $scope.codigosri = '';
                    $scope.estado = 'true';
                    $('#modalActionFormapago').modal('show');
                }

                if (obafect == "prov") {
                    $scope.form_title = "Nueva provincia ";
                    $scope.nameprovincia = '';
                    $('#modalActionProvincia').modal('show');
                }

                if (obafect == "canton") {
                    $scope.form_title = "Nuevo Canton ";
                    $scope.SLprovincia = '1';
                    $scope.namecanton = '';
                    $('#modalActioncanton').modal('show');
                }

                if (obafect == "parroquia") {
                    $scope.form_title = "Nueva Parroquia ";
                    $scope.SLcanton = '1';
                    $scope.nameparroquia = '';
                    $('#modalActionParroquia').modal('show');
                }

                break;
            case 'edit':
                $scope.idc = id;
                if (obafect == "tpdocsri") {
                    $scope.form_title = "Editar Tipo Documento";
                    $scope.idtipodocumento = id;
                    $http.get(API_URL + 'Nomenclador/getTipoDocByID/' + id).success(function(response) {
                        //console.log(response);
                        $scope.nametipodocumento = response[0].nametipodocumento.trim();
                        $scope.codigosri = response[0].codigosri.trim();
                        //console.log(response[0].estado);
                        if(response[0].estado){
                            $scope.estado = 'true' ;
                        }
                        else
                        {
                            $scope.estado = 'false'
                        }
                        $('#modalActionTipoDoc').modal('show');
                    });
                }
                if (obafect == "tpidentsri") {
                    $scope.form_title = "Editar Tipo identificacion";
                    $scope.idtipodocumento = id;
                    $http.get(API_URL + 'Nomenclador/getTipoIdentByID/' + id).success(function(response) {
                        $scope.nametipoident = response[0].nameidentificacion.trim();
                        $scope.codigosri = response[0].codigosri.trim();
                        if(response[0].estado){
                            $scope.estado = 'true' ;
                        }
                        else
                        {
                            $scope.estado = 'false'
                        }
                        $('#modalActionTipoIdent').modal('show');
                    });
                }
                if (obafect == "timpsri") {
                    $scope.form_title = "Editar Tipo Impuesto";
                    $scope.idtipodocumento = id;
                    $http.get(API_URL + 'Nomenclador/getTipoImpuestoByID/' + id).success(function(response) {
                        $scope.nametipoimpuest = response[0].nameimpuesto.trim();
                        $scope.codigosri = response[0].codigosri.trim();
                        if(response[0].estado){
                            $scope.estado = 'true' ;
                        }
                        else
                        {
                            $scope.estado = 'false'
                        }
                        $('#modalActionTipoImp').modal('show');
                    });
                }

                if (obafect == "tpimpivasri") {
                    $scope.form_title = "Editar Impuesto Iva";
                    $scope.idtipodocumento = id;

                    $http.get(API_URL + 'Nomenclador/getTipoImpuestoIvaByID/' + id).success(function(response) {
                        $scope.nameimpuestoiva = response[0].nametipoimpuestoiva.trim();
                        $scope.porcentaje = response[0].porcentaje;
                        $scope.codigosri = response[0].codigosri;
                        $scope.TipoImpuesto = response[0].idtipoimpuesto.toString();
                        if(response[0].estado){
                            $scope.estado = 'true' ;
                        }
                        else
                        {
                            $scope.estado = 'false'
                        }
                        $('#modalActionImpuestoIva').modal('show');
                    });
                }

                if (obafect == "tpimpicesri") {
                    $scope.form_title = "Editar Impuesto Ice";
                    $scope.idtipodocumento = id;
                    //console.log(id);
                    $http.get(API_URL + 'Nomenclador/getTipoImpuestoIceByID/' + id).success(function(response) {
                        $scope.nameimpuestoice = response[0].nametipoimpuestoice.trim();
                        $scope.porcentaje = response[0].porcentaje;
                        $scope.codigosri = response[0].codigosri;
                        $scope.TipoImpuesto = response[0].idtipoimpuesto.toString();
                        if(response[0].estado){
                            $scope.estado = 'true' ;
                        }
                        else
                        {
                            $scope.estado = 'false'
                        }
                        $('#modalActionImpuestoIce').modal('show');
                    });
                }

                if (obafect == "tpimpretsri") {

                    $scope.form_title = "Editar Tipo Impuesto Retencion Renta";
                    $scope.idtipodocumento = id;
                    $http.get(API_URL + 'Nomenclador/getTipoImpuestoRetencionRetByID/' + id).success(function(response) {
                        //console.log(response);
                        $scope.nametipoimpuestoret = response[0].nametipoimpuestoretencion.trim();
                        $scope.codigosri = response[0].codigosri.trim();
                        if(response[0].estado){
                            $scope.estado = 'true' ;
                        }
                        else
                        {
                            $scope.estado = 'false'
                        }
                        $('#modalActionTipoImpRetRenta').modal('show');
                    });
                }

                if (obafect == "tpimpivaretsri") {
                    $scope.form_title = "Editar Tipo Impuesto Retencion ";
                    $scope.idtipodocumento = id;
                    $http.get(API_URL + 'Nomenclador/getTipoImpuestoRetencionIvaRetByID/' + id).success(function(response) {
                        $scope.nametipoimpuestoivaret = response[0].namedetalleimpuestoretencion.trim();
                        $scope.TipoImpuesto = response[0].idtipoimpuestoretencion.toString();
                        $scope.porcentaje = response[0].porcentaje;
                        $scope.codigosri = response[0].codigosri;
                        //$scope.estado = response[0].estado;
                        if(response[0].estado){
                            $scope.estado = 'true' ;
                        }
                        else
                        {
                            $scope.estado = 'false'
                        }
                        $('#modalActionTipoImpIvaRetRenta').modal('show');
                    });
                }

                if (obafect == "sustrib") {
                    $scope.form_title = "Editar Sustento Tributario ";
                    $scope.idtipodocumento = id;
                    $http.get(API_URL + 'Nomenclador/getSustentoTributarioByID/' + id).success(function(response) {
                        $scope.nameSustentoTributario = response[0].namesustento.trim();
                        $scope.codigosrisustento = response[0].codigosrisustento;
                        if(response[0].estado){
                            $scope.estado = 'true' ;
                        }
                        else
                        {
                            $scope.estado = 'false'
                        }
                        $('#modalActionSustentoTributario').modal('show');
                    });
                }

                if (obafect == "compsust") {
                    $scope.form_title = "Editar Comprobante Sustento Tributario ";
                    $scope.idtipodocumento = id;
                    $http.get(API_URL + 'Nomenclador/getComprobanteTributarioByID/' + id).success(function(response) {
                        $scope.namecomprobante =  response[0].namecomprobante;
                        $scope.codigosri = response[0].codigosri;

                        if(response[0].estado){
                            $scope.estado = 'true' ;
                        }
                        else
                        {
                            $scope.estado = 'false'
                        }

                        $http.get(API_URL + 'Nomenclador/getSustentoComprobanteByID/' + id).success(function(response) {

                            $scope.TipoSustento = response[0].idsustentotributario.toString();

                        });
                        $('#modalActionCompSustentoTributario').modal('show');
                    });
                }

                if (obafect == "tppagores") {
                    $scope.form_title = "Editar Tipo de Pago Residente ";
                    $scope.idtipodocumento = id;
                    $http.get(API_URL + 'Nomenclador/getPagoResidenteByID/' + id).success(function(response) {
                        $scope.tipopagoresidente =  response[0].tipopagoresidente;

                        $('#modalActionPagoResidente').modal('show');
                    });
                }

                if (obafect == "pagopais") {
                    $scope.form_title = "Editar Pais ";
                    $scope.idtipodocumento = id;
                    $http.get(API_URL + 'Nomenclador/getPaisPagoByID/' + id).success(function(response) {
                        $scope.pais =  response[0].pais;
                        $scope.codigosri = response[0].codigosri;

                        $('#modalActionPaisPago').modal('show');
                    });
                }

                if (obafect == "formapago") {
                    $scope.form_title = "Editar Forma Pago ";
                    $scope.idtipodocumento = id;
                    $http.get(API_URL + 'Nomenclador/getFormaPagoByID/' + id).success(function(response) {
                        $scope.nameformapago =  response[0].nameformapago;
                        $scope.codigosri = response[0].codigosri;
                        if(response[0].estado){
                            $scope.estado = 'true' ;
                        }
                        else
                        {
                            $scope.estado = 'false'
                        }

                        $('#modalActionFormapago').modal('show');
                    });
                }

                if (obafect == "prov") {
                    $scope.form_title = "Editar Provincia ";
                    $scope.idtipodocumento = id;
                    $http.get(API_URL + 'Nomenclador/getprovinciaByID/' + id).success(function(response) {
                        $scope.nameprovincia =  response[0].nameprovincia;
                        $('#modalActionProvincia').modal('show');
                    });
                }

                if (obafect == "canton") {
                    $scope.form_title = "Editar Canton ";
                    $scope.idtipodocumento = id;
                    $http.get(API_URL + 'Nomenclador/getcantonEXByID/' + id).success(function(response) {
                        //console.log(response);
                        $scope.SLprovincia =  response[0].idprovincia.toString();
                        $scope.namecanton =  response[0].namecanton;
                        $('#modalActioncanton').modal('show');
                    });
                }

                if (obafect == "parroquia") {
                    $scope.form_title = "Editar Parroquia ";
                    $scope.idtipodocumento = id;
                    $http.get(API_URL + 'Nomenclador/getparroquiaEXByID/' + id).success(function(response) {
                        //console.log(response);
                        $scope.SLcanton =  response[0].idcanton.toString();
                        $scope.nameparroquia =  response[0].nameparroquia;
                        $('#modalActionParroquia').modal('show');
                    });
                }

                break;
            default:
                break;
        }
    };

    $scope.Save = function (tbafect){

        var url = API_URL + "Nomenclador";
        var method = 'POST';

        if (tbafect == "tpdocsri"){

            if ($scope.estado==undefined) {
                $scope.estado = 'true';
            }

            var data = {
                nametipodocumento: $scope.nametipodocumento,
                codigosri: $scope.codigosri,
                estado: $scope.estado
            };
        }


        if (tbafect == "tpidentsri"){

            if ($scope.estado==undefined) {
                $scope.estado = 'true';
            }

            var data = {
                nameidentificacion: $scope.nametipoident,
                codigosri: $scope.codigosri,
                estado: $scope.estado
            };

        }

        if (tbafect == "timpsri"){

            if ($scope.estado==undefined) {
                $scope.estado = 'true';
            }

            var data = {
                nameimpuesto: $scope.nametipoimpuest,
                codigosri: $scope.codigosri,
                estado: $scope.estado
            };

        }

        if (tbafect == "tpimpiva"){

            if ($scope.estado==undefined) {
                $scope.estado = 'true';
            }

            var data = {
                nameimpuestoiva: $scope.nameimpuestoiva,
                porcentaje: $scope.porcentaje,
                codigosri: $scope.codigosri,
                TipoImpuesto: $scope.TipoImpuesto,
                //TipoImpuesto: 1,
                estado: $scope.estado
            };
        }


        if (tbafect == "tpimpice"){

            if ($scope.estado==undefined) {
                $scope.estado = 'true';
            }

            var data = {
                nameimpuestoice: $scope.nameimpuestoice,
                porcentaje: $scope.porcentaje,
                codigosri: $scope.codigosri,
                TipoImpuesto: $scope.TipoImpuesto,
                //TipoImpuesto: 1,
                estado: $scope.estado
            };
        }

        if (tbafect == "tpimpretsri"){

            if ($scope.estado==undefined) {
                $scope.estado = 'true';
            }

            var data = {
                nametipoimpuestoretencion: $scope.nametipoimpuestoret,
                codigosri: $scope.codigosri,
                estado: $scope.estado
            };
        }

        if (tbafect == "tpimpivaretsri"){

            if ($scope.estado==undefined) {
                $scope.estado = 'true';
            }

            var data = {
                namedetalleimpuestoretencion: $scope.nametipoimpuestoivaret,
                porcentaje: $scope.porcentaje,
                idtipoimpuestoretencion:$scope.TipoImpuesto,
                codigosri: $scope.codigosri,
                estado: $scope.estado
            };
        }

        if (tbafect == "sustrib"){

            if ($scope.estado==undefined) {
                $scope.estado = 'true';
            }

            var data = {
                namesustentotributario: $scope.nameSustentoTributario,
                codigosrisustento: $scope.codigosrisustento,
                estado: $scope.estado
            };

        }

        if (tbafect == "compsust"){

            if ($scope.estado==undefined) {
                $scope.estado = 'true';
            }

            var data = {
                idtSustento: $scope.TipoSustento,
                namecomprobante: $scope.namecomprobante,
                codigosri: $scope.codigosri,
                estado: $scope.estado
            };
        }

        if (tbafect == "tppagores"){
            var data = {
                tipopagoresidente: $scope.tipopagoresidente
            };
        }

        if (tbafect == "pagopais"){
            var data = {
                pais: $scope.pais.toUpperCase(),
                codigosri: $scope.codigosri
            };
        }

        if (tbafect == "formapago"){

            if ($scope.estado==undefined) {
                $scope.estado = 'true';
            }

            var data = {
                nameformapago: $scope.nameformapago,
                codigosri: $scope.codigosri,
                estado: $scope.estado
            };
        }

        if (tbafect == "prov"){
            var data = {
                nameprovincia: $scope.nameprovincia
            };
        }

        if (tbafect == "canton"){
            var data = {
                namecanton: $scope.namecanton,
                idprovincia: $scope.SLprovincia

            };
        }

        if (tbafect == "parroquia"){
            var data = {
                nameparroquia: $scope.nameparroquia,
                idcanton: $scope.SLcanton

            };
        }


        switch ( $scope.modalstate) {
            case 'add':

                if (tbafect == "tpdocsri"){

                    $http.post(API_URL + 'Nomenclador', data ).success(function (response) {
                        if (response.success == true) {
                            $scope.CargadataTPdoc();
                            $('#modalActionTipoDoc').modal('hide');
                            $scope.message = 'Se insertó correctamente el Tipo Documento...';
                            $('#modalMessage').modal('show');
                            $scope.hideModalMessage();
                            //console.log("true");
                        }
                        else {
                            $('#modalActionTipoDoc').modal('hide');
                            $scope.message_error = 'Ya existe ese Tipo Documento...';
                            $('#modalMessageError').modal('show');
                        }
                    });
                    break;
                }


                if (tbafect == "tpidentsri"){
                    $http.post(API_URL + 'Nomenclador/storeTipoIdent', data ).success(function (response) {
                        if (response.success == true) {

                            $scope.CargadataTPident ();
                            $('#modalActionTipoIdent').modal('hide');
                            $scope.message = 'Se insertó correctamente el Tipo Identificacion...';
                            $('#modalMessage').modal('show');
                            $scope.hideModalMessage();
                        }
                        else {

                            $('#modalActionTipoIdent').modal('hide');
                            $scope.message_error = 'Ya existe ese Tipo Identificacion...';
                            $('#modalMessageError').modal('show');
                        }
                    });
                    break;
                }

                if (tbafect == "timpsri"){

                    $http.post(API_URL + 'Nomenclador/storeTipoImpuesto', data ).success(function (response) {
                        if (response.success == true) {
                            $scope.CargadataTPimp ();
                            $('#modalActionTipoImp').modal('hide');
                            $scope.message = 'Se insertó correctamente el Tipo Impuesto...';
                            $('#modalMessage').modal('show');
                            $scope.hideModalMessage();                            }
                        else {

                            $('#modalActionTipoImp').modal('hide');
                            $scope.message_error = 'Ya existe ese Tipo Impuesto...';
                            $('#modalMessageError').modal('show');
                        }
                    });
                    break;
                }

                if (tbafect == "tpimpiva"){
                    $http.post(API_URL + 'Nomenclador/storeTipoImpuestoiva', data ).success(function (response) {
                        if (response.success == true) {

                            $scope.CargadataImpIVA();
                            $('#modalActionImpuestoIva').modal('hide');
                            $scope.message = 'Se insertó correctamente el Impuesto Iva...';
                            $('#modalMessage').modal('show');
                            $scope.hideModalMessage();
                        }
                        else {

                            $('#modalActionImpuestoIva').modal('hide');
                            $scope.message_error = 'Ya existe ese Impuesto Iva...';
                            $('#modalMessageError').modal('show');
                        }
                    });
                    break;
                }

                if (tbafect == "tpimpice"){
                    $http.post(API_URL + 'Nomenclador/storeTipoImpuestoice', data ).success(function (response) {
                        if (response.success == true) {

                            $scope.CargadataImpICE();
                            $('#modalActionImpuestoIce').modal('hide');
                            $scope.message = 'Se insertó correctamente el Impuesto Ice...';
                            $('#modalMessage').modal('show');
                            $scope.hideModalMessage();
                        }
                        else {

                            $('#modalActionImpuestoIce').modal('hide');
                            $scope.message_error = 'Ya existe ese Impuesto Ice...';
                            $('#modalMessageError').modal('show');
                        }
                    });

                    break;
                }

                if (tbafect == "tppagores"){
                    $http.post(API_URL + 'Nomenclador/storeTipoPagoResidente', data ).success(function (response) {
                        if (response.success == true) {

                            $scope.CargadataPagoResidente();
                            $('#modalActionPagoResidente').modal('hide');
                            $scope.message = 'Se insertó correctamente el Registro...';
                            $('#modalMessage').modal('show');
                            $scope.hideModalMessage();
                        }
                        else {

                            $('#modalActionPagoResidente').modal('hide');
                            $scope.message_error = 'Ya existe ese Impuesto Ice...';
                            $('#modalMessageError').modal('show');
                        }
                    });

                    break;
                }

                if (tbafect == "tpimpretsri"){
                    $http.post(API_URL + 'Nomenclador/storeTipoImpuestoReten', data ).success(function (response) {
                        if (response.success == true) {

                            $scope.CargadataTipoImpRetenc();
                            $('#modalActionTipoImpRetRenta').modal('hide');
                            $scope.message = 'Se insertó correctamente el Registro Correctamente...';
                            $('#modalMessage').modal('show');
                            $scope.hideModalMessage();
                        }
                        else {

                            $('#modalActionTipoImpRetRenta').modal('hide');
                            $scope.message_error = 'Ya existe ese  Registro...';
                            $('#modalMessageError').modal('show');
                        }
                    });

                    break;
                }


                if (tbafect == "tpimpivaretsri"){
                    $http.post(API_URL + 'Nomenclador/storeTipoImpuestoIvaReten', data ).success(function (response) {
                        if (response.success == true) {

                            $scope.CargadataImpIVARENTA();
                            $('#modalActionTipoImpIvaRetRenta').modal('hide');
                            $scope.message = 'Se insertó correctamente el Registro Correctamente...';
                            $('#modalMessage').modal('show');
                            $scope.hideModalMessage();
                        }
                        else {

                            $('#modalActionTipoImpIvaRetRenta').modal('hide');
                            $scope.message_error = 'Ya existe ese  Registro...';
                            $('#modalMessageError').modal('show');
                        }
                    });

                    break;
                }


                if (tbafect == "sustrib"){

                    $http.post(API_URL + 'Nomenclador/storeSustentoTrib', data ).success(function (response) {
                        if (response.success == true) {

                            $scope.CargadataSustentoTrib();
                            $('#modalActionSustentoTributario').modal('hide');
                            $scope.message = 'Se insertó correctamente el Registro Correctamente...';
                            $('#modalMessage').modal('show');
                            $scope.hideModalMessage();
                        }
                        else {

                            $('#modalActionSustentoTributario').modal('hide');
                            $scope.message_error = 'Ya existe ese  Registro...';
                            $('#modalMessageError').modal('show');
                        }
                    });

                    break;
                }

                if (tbafect == "compsust"){
                    $http.post(API_URL + 'Nomenclador/storeComprobanteSustento', data ).success(function (response) {
                        if (response.success == true) {

                            $scope.CargadataComprobante();
                            $('#modalActionCompSustentoTributario').modal('hide');
                            $scope.message = 'Se insertó correctamente el Registro Correctamente...';
                            $('#modalMessage').modal('show');
                            $scope.hideModalMessage();
                        }
                        else {

                            $('#modalActionCompSustentoTributario').modal('hide');
                            $scope.message_error = 'Ya existe ese  Registro...';
                            $('#modalMessageError').modal('show');
                        }
                    });

                    break;
                }


                if (tbafect == "pagopais"){
                    $http.post(API_URL + 'Nomenclador/storepagopais', data ).success(function (response) {
                        if (response.success == true) {

                            $scope.CargadataPagoPais();
                            $('#modalActionPaisPago').modal('hide');
                            $scope.message = 'Se insertó correctamente el Registro Correctamente...';
                            $('#modalMessage').modal('show');
                            $scope.hideModalMessage();
                        }
                        else {

                            $('#modalActionPaisPago').modal('hide');
                            $scope.message_error = 'Ya existe ese  Registro...';
                            $('#modalMessageError').modal('show');
                        }
                    });

                    break;
                }

                if (tbafect == "formapago"){

                    $http.post(API_URL + 'Nomenclador/storeformapago', data ).success(function (response) {
                        if (response.success == true) {

                            $scope.CargadataFormaPago();
                            $('#modalActionFormapago').modal('hide');
                            $scope.message = 'Se insertó correctamente el Registro Correctamente...';
                            $('#modalMessage').modal('show');
                            $scope.hideModalMessage();
                        }
                        else {

                            $('#modalActionFormapago').modal('hide');
                            $scope.message_error = 'Ya existe ese  Registro...';
                            $('#modalMessageError').modal('show');
                        }
                    });

                    break;
                }

                if (tbafect == "prov"){

                    $http.post(API_URL + 'Nomenclador/storeprovincia', data ).success(function (response) {
                        if (response.success == true) {

                            $scope.CargadataProvincia();
                            $('#modalActionProvincia').modal('hide');
                            $scope.message = 'Se insertó correctamente el Registro Correctamente...';
                            $('#modalMessage').modal('show');
                            $scope.hideModalMessage();
                        }
                        else {

                            $('#modalActionProvincia').modal('hide');
                            $scope.message_error = 'Ya existe ese  Registro...';
                            $('#modalMessageError').modal('show');
                        }
                    });

                    break;
                }

                if (tbafect == "canton"){

                    $http.post(API_URL + 'Nomenclador/storecantonEX', data ).success(function (response) {
                        if (response.success == true) {

                            $scope.CargadataCanton();
                            $('#modalActioncanton').modal('hide');
                            $scope.message = 'Se insertó correctamente el Registro Correctamente...';
                            $('#modalMessage').modal('show');
                        }
                        else {

                            $('#modalActioncanton').modal('hide');
                            $scope.message_error = 'Ya existe ese  Registro...';
                            $('#modalMessageError').modal('show');
                        }
                    });

                    break;
                }

                if (tbafect == "parroquia"){

                    $http.post(API_URL + 'Nomenclador/storeparroquiaEX', data ).success(function (response) {
                        if (response.success == true) {

                            $scope.CargadataParroquia();
                            $('#modalActionParroquia').modal('hide');
                            $scope.message = 'Se insertó correctamente el Registro Correctamente...';
                            $('#modalMessage').modal('show');
                        }
                        else {

                            $('#modalActionparroquia').modal('hide');
                            $scope.message_error = 'Ya existe ese  Registro...';
                            $('#modalMessageError').modal('show');
                        }
                    });

                    break;
                }

            case 'edit':

                if (tbafect == "tpdocsri"){

                    $http.put(API_URL + 'Nomenclador/'+ $scope.idc, data ).success(function (response) {
                        $scope.CargadataTPdoc();
                        $('#modalActionTipoDoc').modal('hide');
                        $scope.message = 'Se editó correctamente el Registro seleccionado';
                        $('#modalMessage').modal('show');
                        $scope.hideModalMessage();
                    }).error(function (res) {

                    });
                    break;
                }
                if (tbafect == "tpidentsri"){
                    url += "/updatetpidentsri/" + $scope.idc
                    Upload.upload({
                        url: url,
                        method: method,
                        data: data
                    }).success(function(data, status, headers, config) {
                        if (data.success == true) {
                            $scope.idpersona = 0;

                            $scope.objectPerson = {
                                idperson: 0,
                                identify: ''
                            };

                            $scope.CargadataTPident();
                            $('#modalActionTipoIdent').modal('hide');
                            $scope.message = 'Se editó correctamente el Registro seleccionado';
                            $('#modalMessage').modal('show');
                            $scope.hideModalMessage();
                        }
                        else {
                            $('#modalActionTipoIdent').modal('hide');
                            $scope.message_error = 'Ya existe el Registro...';
                            $('#modalMessage').modal('show');
                            $scope.hideModalMessage();
                        }
                    });

                    break;

                }
                if (tbafect == "timpsri"){
                    url += "/updatetpimpsri/" + $scope.idc
                    Upload.upload({
                        url: url,
                        method: method,
                        data: data
                    }).success(function(data, status, headers, config) {
                        if (data.success == true) {

                            $scope.CargadataTPimp();
                            $('#modalActionTipoImp').modal('hide');
                            $scope.message = 'Se editó correctamente el Registro seleccionado';
                            $('#modalMessage').modal('show');
                            $scope.hideModalMessage();
                        }
                        else {
                            $('#modalActionTipoImp').modal('hide');
                            $scope.message_error = 'Ya existe el Registro...';
                            $('#modalMessage').modal('show');
                            $scope.hideModalMessage();
                        }
                    });

                    break;
                }

                if (tbafect == "tpimpiva"){
                    url += "/updatetpimpIvasri/" + $scope.idc
                    Upload.upload({
                        url: url,
                        method: method,
                        data: data
                    }).success(function(data, status, headers, config) {
                        if (data.success == true) {

                            $scope.CargadataImpIVA();
                            $('#modalActionImpuestoIva').modal('hide');
                            $scope.message = 'Se editó correctamente el Registro seleccionado';
                            $('#modalMessage').modal('show');
                            $scope.hideModalMessage();
                        }
                        else {
                            $('#modalActionImpuestoIva').modal('hide');
                            $scope.message_error = 'Ya existe el Registro...';
                            $('#modalMessage').modal('show');
                            $scope.hideModalMessage();
                        }
                    });

                    break;

                }

                if (tbafect == "tpimpice"){
                    url += "/updatetpimpIcesri/" + $scope.idc
                    Upload.upload({
                        url: url,
                        method: method,
                        data: data
                    }).success(function(data, status, headers, config) {
                        if (data.success == true) {

                            $scope.CargadataImpICE();
                            $('#modalActionImpuestoIce').modal('hide');
                            $scope.message = 'Se editó correctamente el Registro seleccionado';
                            $('#modalMessage').modal('show');
                            $scope.hideModalMessage();
                        }
                        else {
                            $('#modalActionImpuestoIce').modal('hide');
                            $scope.message_error = 'Ya existe el Registro...';
                            $('#modalMessage').modal('show');
                            $scope.hideModalMessage();
                        }
                    });

                    break;
                }

                if (tbafect == "tpimpretsri"){
                    url += "/updatetpimpRetensri/" + $scope.idc
                    Upload.upload({
                        url: url,
                        method: method,
                        data: data
                    }).success(function(data, status, headers, config) {
                        if (data.success == true) {

                            $scope.CargadataTipoImpRetenc();
                            $('#modalActionTipoImpRetRenta').modal('hide');
                            $scope.message = 'Se editó correctamente el Registro seleccionado';
                            $('#modalMessage').modal('show');
                            $scope.hideModalMessage();
                        }
                        else {
                            $('#modalActionTipoImpRetRenta').modal('hide');
                            $scope.message_error = 'Ya existe el Registro...';
                            $('#modalMessage').modal('show');
                            $scope.hideModalMessage();
                        }
                    });

                    break;
                }

                if (tbafect == "tpimpivaretsri"){
                    url += "/updatetpimpIvaRetensri/" + $scope.idc
                    Upload.upload({
                        url: url,
                        method: method,
                        data: data
                    }).success(function(data, status, headers, config) {
                        if (data.success == true) {

                            $scope.CargadataImpIVARENTA();
                            $('#modalActionTipoImpIvaRetRenta').modal('hide');
                            $scope.message = 'Se editó correctamente el Registro seleccionado';
                            $('#modalMessage').modal('show');
                            $scope.hideModalMessage();
                        }
                        else {
                            $('#modalActionTipoImpIvaRetRenta').modal('hide');
                            $scope.message_error = 'Ya existe el Registro...';
                            $('#modalMessage').modal('show');
                            $scope.hideModalMessage();
                        }
                    });

                    break;
                }

                if (tbafect == "sustrib"){
                    url += "/updateSustentoTributario/" + $scope.idc
                    Upload.upload({
                        url: url,
                        method: method,
                        data: data
                    }).success(function(data, status, headers, config) {
                        if (data.success == true) {

                            $scope.CargadataSustentoTrib();
                            $('#modalActionSustentoTributario').modal('hide');
                            $scope.message = 'Se editó correctamente el Registro seleccionado';
                            $('#modalMessage').modal('show');
                            $scope.hideModalMessage();
                        }
                        else {
                            $('#modalActionSustentoTributario').modal('hide');
                            $scope.message_error = 'Ya existe el Registro...';
                            $('#modalMessage').modal('show');
                            $scope.hideModalMessage();
                        }
                    });

                    break;
                }

                if (tbafect == "compsust"){
                    url += "/updateSustento_Comprobante/" + $scope.idc
                    //console.log($scope.idc);
                    Upload.upload({
                        url: url,
                        method: method,
                        data: data
                    }).success(function(data, status, headers, config) {
                        if (data.success == true) {

                            $scope.CargadataComprobante();
                            $('#modalActionCompSustentoTributario').modal('hide');
                            $scope.message = 'Se editó correctamente el Registro seleccionado';
                            $('#modalMessage').modal('show');
                            $scope.hideModalMessage();
                        }
                        else {
                            $('#modalActionCompSustentoTributario').modal('hide');
                            $scope.message_error = 'Ya existe el Registro...';
                            $('#modalMessage').modal('show');
                            $scope.hideModalMessage();
                        }
                    });

                    break;
                }

                if (tbafect == "tppagores"){
                    url += "/updatePagoResidente/" + $scope.idc
                    //console.log($scope.idc);
                    Upload.upload({
                        url: url,
                        method: method,
                        data: data
                    }).success(function(data, status, headers, config) {
                        if (data.success == true) {

                            $scope.CargadataPagoResidente();
                            $('#modalActionPagoResidente').modal('hide');
                            $scope.message = 'Se editó correctamente el Registro seleccionado';
                            $('#modalMessage').modal('show');
                            $scope.hideModalMessage();
                        }
                        else {
                            $('#modalActionPagoResidente').modal('hide');
                            $scope.message_error = 'Ya existe el Registro...';
                            $('#modalMessage').modal('show');
                            $scope.hideModalMessage();
                        }
                    });

                    break;
                }

                if (tbafect == "pagopais"){
                    url += "/updatePagoPais/" + $scope.idc
                    //console.log($scope.idc);
                    Upload.upload({
                        url: url,
                        method: method,
                        data: data
                    }).success(function(data, status, headers, config) {
                        if (data.success == true) {

                            $scope.CargadataPagoPais();
                            $('#modalActionPaisPago').modal('hide');
                            $scope.message = 'Se editó correctamente el Registro seleccionado';
                            $('#modalMessage').modal('show');
                            $scope.hideModalMessage();
                        }
                        else {
                            $('#modalActionPaisPago').modal('hide');
                            $scope.message_error = 'Ya existe el Registro...';
                            $('#modalMessage').modal('show');
                            $scope.hideModalMessage();
                        }
                    });

                    break;
                }

                if (tbafect == "formapago"){
                    url += "/updateFormaPago/" + $scope.idc
                    //console.log($scope.idc);
                    Upload.upload({
                        url: url,
                        method: method,
                        data: data
                    }).success(function(data, status, headers, config) {
                        if (data.success == true) {

                            $scope.CargadataFormaPago();
                            $('#modalActionFormapago').modal('hide');
                            $scope.message = 'Se editó correctamente el Registro seleccionado';
                            $('#modalMessage').modal('show');
                            $scope.hideModalMessage();
                        }
                        else {
                            $('#modalActionFormapago').modal('hide');
                            $scope.message_error = 'Ya existe el Registro...';
                            $('#modalMessage').modal('show');
                            $scope.hideModalMessage();
                        }
                    });

                    break;
                }

                if (tbafect == "prov"){
                    url += "/updateprovincia/" + $scope.idc

                    Upload.upload({
                        url: url,
                        method: method,
                        data: data
                    }).success(function(data, status, headers, config) {
                        if (data.success == true) {

                            $scope.CargadataProvincia();
                            $('#modalActionProvincia').modal('hide');
                            $scope.message = 'Se editó correctamente el Registro seleccionado';
                            $('#modalMessage').modal('show');
                            $scope.hideModalMessage();
                        }
                        else {
                            $('#modalActionProvincia').modal('hide');
                            $scope.message_error = 'Ya existe el Registro...';
                            $('#modalMessage').modal('show');
                            $scope.hideModalMessage();
                        }
                    });

                    break;
                }



                if (tbafect == "canton"){
                    url += "/updatecantonEX/" + $scope.idc

                    Upload.upload({
                        url: url,
                        method: method,
                        data: data
                    }).success(function(data, status, headers, config) {
                        if (data.success == true) {

                            $scope.CargadataCanton();
                            $('#modalActioncanton').modal('hide');
                            $scope.message = 'Se editó correctamente el Registro seleccionado';
                            $('#modalMessage').modal('show');
                            $scope.hideModalMessage();
                        }
                        else {
                            $('#modalActioncanton').modal('hide');
                            $scope.message_error = 'Ya existe el Registro...';
                            $('#modalMessage').modal('show');
                            $scope.hideModalMessage();
                        }
                    });

                    break;
                }


                if (tbafect == "parroquia"){
                    //console.log($scope.idc);
                    url += "/updateparroquiaEX/" + $scope.idc
                    console.log(data);

                    Upload.upload({
                        url: url,
                        method: method,
                        data: data
                    }).success(function(data, status, headers, config) {
                        if (data.success == true) {

                            $scope.CargadataParroquia();
                            $('#modalActionParroquia').modal('hide');
                            $scope.message = 'Se editó correctamente el Registro seleccionado';
                            $('#modalMessage').modal('show');
                            $scope.hideModalMessage();
                        }
                        else {
                            $('#modalActionParroquia').modal('hide');
                            $scope.message_error = 'Ya existe el Registro...';
                            $('#modalMessage').modal('show');
                            $scope.hideModalMessage();
                        }
                    });

                    break;
                }
        }
    };

    $scope.showModalConfirm = function(tipodocumento,tbafect){

        if (tbafect == "tpdocsri"){

            $scope.idtipodocumento_del = tipodocumento.idtipodocumento;
            $scope.tipodocument_seleccionado = tipodocumento.nametipodocumento;
            $scope.TipoAccion =  "tpdocsri";
            $('#modalConfirmDelete').modal('show');
        }

        if (tbafect == "tpidentsri"){

            $scope.idtipodocumento_del = tipodocumento.idtipoidentificacion;
            $scope.tipodocument_seleccionado = tipodocumento.nameidentificacion;
            $scope.TipoAccion =  "tpidentsri";
            $('#modalConfirmDelete').modal('show');
        }

        if (tbafect == "timpsri"){

            $scope.idtipodocumento_del = tipodocumento.idtipoimpuesto;
            $scope.tipodocument_seleccionado = tipodocumento.nameimpuesto;
            $scope.TipoAccion =  "timpsri";
            $('#modalConfirmDelete').modal('show');
        }
        if (tbafect == "tpimpivasri"){

            $scope.idtipodocumento_del = tipodocumento.idtipoimpuestoiva;
            $scope.tipodocument_seleccionado = tipodocumento.nametipoimpuestoiva;
            $scope.TipoAccion =  "tpimpivasri";
            $('#modalConfirmDelete').modal('show');
        }

        if (tbafect == "tpimpicesri"){

            $scope.idtipodocumento_del = tipodocumento.idtipoimpuestoice;
            $scope.tipodocument_seleccionado = tipodocumento.nametipoimpuestoice;
            $scope.TipoAccion =  "tpimpicesri";
            $('#modalConfirmDelete').modal('show');
        }

        if (tbafect == "tpimpretsri"){

            $scope.idtipodocumento_del = tipodocumento.idtipoimpuestoretencion;
            $scope.tipodocument_seleccionado = tipodocumento.nametipoimpuestoretencion;
            $scope.TipoAccion =  "tpimpretsri";
            $('#modalConfirmDelete').modal('show');
        }

        if (tbafect == "tpimpivaretsri"){

            $scope.idtipodocumento_del = tipodocumento.iddetalleimpuestoretencion;
            $scope.tipodocument_seleccionado = tipodocumento.namedetalleimpuestoretencion;
            $scope.TipoAccion =  "tpimpivaretsri";
            $('#modalConfirmDelete').modal('show');
        }

        if (tbafect == "sustrib"){

            $scope.idtipodocumento_del = tipodocumento.idsustentotributario;
            $scope.tipodocument_seleccionado = tipodocumento.namesustento;
            $scope.TipoAccion =  "sustrib";
            $('#modalConfirmDelete').modal('show');
        }

        if (tbafect == "compsust"){

            $scope.idtipodocumento_del = tipodocumento.idtipocomprobante;
            $scope.tipodocument_seleccionado = tipodocumento.namecomprobante;
            $scope.TipoAccion =  "compsust";
            $('#modalConfirmDelete').modal('show');
        }

        if (tbafect == "tppagores"){

            $scope.idtipodocumento_del = tipodocumento.idpagoresidente;
            $scope.tipodocument_seleccionado = tipodocumento.tipopagoresidente;
            $scope.TipoAccion =  "tppagores";
            $('#modalConfirmDelete').modal('show');
        }

        if (tbafect == "pagopais"){

            $scope.idtipodocumento_del = tipodocumento.idpagopais;
            $scope.tipodocument_seleccionado = tipodocumento.pais;
            $scope.TipoAccion =  "pagopais";
            $('#modalConfirmDelete').modal('show');
        }

        if (tbafect == "formapago"){

            $scope.idtipodocumento_del = tipodocumento.idformapago;
            $scope.tipodocument_seleccionado = tipodocumento.nameformapago;
            $scope.TipoAccion =  "formapago";
            $('#modalConfirmDelete').modal('show');
        }

        if (tbafect == "prov"){

            $scope.idtipodocumento_del = tipodocumento.idprovincia;
            $scope.tipodocument_seleccionado = tipodocumento.nameprovincia;
            $scope.TipoAccion =  "prov";
            $('#modalConfirmDelete').modal('show');
        }

        if (tbafect == "canton"){

            $scope.idtipodocumento_del = tipodocumento.idcanton;
            $scope.tipodocument_seleccionado = tipodocumento.namecanton;
            $scope.TipoAccion =  "canton";
            $('#modalConfirmDelete').modal('show');
        }

        if (tbafect == "parroquia"){

            $scope.idtipodocumento_del = tipodocumento.idparroquia;
            $scope.tipodocument_seleccionado = tipodocumento.nameparroquia;
            $scope.TipoAccion =  "parroquia";
            $('#modalConfirmDelete').modal('show');
        }
    };

    $scope.delete = function(tbafect){


        var data = {
            id: $scope.idtipodocumento_del
        };


        if (tbafect == "tpdocsri"){

            $http.delete(API_URL + 'Nomenclador/' + $scope.idtipodocumento_del).success(function(response) {


                if(response.success == true){

                    $scope.CargadataTPdoc();
                    $('#modalConfirmDelete').modal('hide');
                    $scope.idtipodocumento_del = 0;
                    $scope.message = 'Se eliminó correctamente el Tipo Documento seleccionado...';
                    $('#modalMessage').modal('show');
                    $scope.hideModalMessage();

                } else {
                    $scope.message_error = 'El Tipo Documento no puede ser eliminado porque esta asignado a un colaborador...';
                    $('#modalMessageError').modal('show');
                    $('#modalConfirmDelete').modal('hide');
                }
            });

        }


        if (tbafect == "tpidentsri"){


            $http.post(API_URL + 'Nomenclador/deleteTipoIdentSRI', data).success(function(response) {


                if(response.success == true){
                    //$scope.initLoad();
                    $scope.CargadataTPident();
                    $('#modalConfirmDelete').modal('hide');
                    $scope.tipoidentificacion_del = 0;
                    $scope.message = 'Se eliminó correctamente el Tipo de Identificacion seleccionado...';
                    $('#modalMessage').modal('show');
                    $scope.hideModalMessage();

                } else {
                    $scope.message_error = 'El Tipo de Identificacion no puede ser eliminado porque esta asignado a un colaborador...';
                    $('#modalMessageError').modal('show');
                    $('#modalConfirmDelete').modal('hide');
                }
            });
        }


        if (tbafect == "timpsri"){

            $http.post(API_URL + 'Nomenclador/deleteTipoImpuesto', data).success(function(response) {


                if(response.success == true){

                    $scope.CargadataTPimp();
                    $('#modalConfirmDelete').modal('hide');
                    $scope.tipoidentificacion_del = 0;
                    $scope.message = 'Se eliminó correctamente el Tipo Impuesto seleccionado...';
                    $('#modalMessage').modal('show');
                    $scope.hideModalMessage();

                } else {
                    $scope.message_error = 'El Tipo Impuesto no puede ser eliminado porque esta asignado a un colaborador...';
                    $('#modalMessageError').modal('show');
                    $('#modalConfirmDelete').modal('hide');
                }
            });
        }

        if (tbafect == "tpimpivasri"){

            $http.post(API_URL + 'Nomenclador/deleteTipoImpuestoIva', data).success(function(response) {


                if(response.success == true){

                    $scope.CargadataImpIVA();
                    $('#modalConfirmDelete').modal('hide');
                    $scope.tipoidentificacion_del = 0;
                    $scope.message = 'Se eliminó correctamente el Impuesto Iva seleccionado...';
                    $('#modalMessage').modal('show');
                    $scope.hideModalMessage();

                } else {
                    $scope.message_error = 'El Impuesto Iva no puede ser eliminado porque esta asignado a un colaborador...';
                    $('#modalMessageError').modal('show');
                    $('#modalConfirmDelete').modal('hide');
                }
            });
        }

        if (tbafect == "tpimpicesri"){

            $http.post(API_URL + 'Nomenclador/deleteTipoImpuestoIce', data).success(function(response) {


                if(response.success == true){

                    $scope.CargadataImpICE();
                    $('#modalConfirmDelete').modal('hide');
                    $scope.tipoidentificacion_del = 0;
                    $scope.message = 'Se eliminó correctamente el Impuesto Ice seleccionado...';
                    $('#modalMessage').modal('show');
                    $scope.hideModalMessage();

                } else {
                    $scope.message_error = 'El Impuesto Ice no puede ser eliminado porque esta asignado a un colaborador...';
                    $('#modalMessageError').modal('show');
                    $('#modalConfirmDelete').modal('hide');
                }
            });
        }

        if (tbafect == "tpimpretsri"){

            $http.post(API_URL + 'Nomenclador/deleteTipoImpuestoRetencion', data).success(function(response) {


                if(response.success == true){

                    $scope.CargadataTipoImpRetenc();
                    $('#modalConfirmDelete').modal('hide');
                    $scope.tipoidentificacion_del = 0;
                    $scope.message = 'Se eliminó correctamente el Registro seleccionado...';
                    $('#modalMessage').modal('show');
                    $scope.hideModalMessage();

                } else {
                    $scope.message_error = 'El registro no puede ser eliminado porque esta asignado a un colaborador...';
                    $('#modalMessageError').modal('show');
                    $('#modalConfirmDelete').modal('hide');
                }
            });
        }

        if (tbafect == "tpimpivaretsri"){

            $http.post(API_URL + 'Nomenclador/deleteTipoImpuestoIvaRetencion', data).success(function(response) {


                if(response.success == true){

                    $scope.CargadataImpIVARENTA();
                    $('#modalConfirmDelete').modal('hide');
                    $scope.tipoidentificacion_del = 0;
                    $scope.message = 'Se eliminó correctamente el Registro seleccionado...';
                    $('#modalMessage').modal('show');
                    $scope.hideModalMessage();

                } else {
                    $scope.message_error = 'El registro no puede ser eliminado porque esta asignado a un colaborador...';
                    $('#modalMessageError').modal('show');
                    $('#modalConfirmDelete').modal('hide');
                }
            });
        }

        if (tbafect == "sustrib"){

            $http.post(API_URL + 'Nomenclador/deleteSustentoTrib', data).success(function(response) {


                if(response.success == true){

                    $scope.CargadataSustentoTrib();
                    $('#modalConfirmDelete').modal('hide');
                    $scope.tipoidentificacion_del = 0;
                    $scope.message = 'Se eliminó correctamente el Registro seleccionado...';
                    $('#modalMessage').modal('show');
                    $scope.hideModalMessage();

                } else {
                    $scope.message_error = 'El registro no puede ser eliminado porque esta asignado a un colaborador...';
                    $('#modalMessageError').modal('show');
                    $('#modalConfirmDelete').modal('hide');
                }
            });
        }

        //compsust

        if (tbafect == "compsust"){

            $http.post(API_URL + 'Nomenclador/deleteSustentoComprobante', data).success(function(response) {


                if(response.success == true){

                    $scope.CargadataComprobante();
                    $('#modalConfirmDelete').modal('hide');
                    $scope.tipoidentificacion_del = 0;
                    $scope.message = 'Se eliminó correctamente el Registro seleccionado...';
                    $('#modalMessage').modal('show');
                    $scope.hideModalMessage();

                } else {
                    $scope.message_error = 'El registro no puede ser eliminado porque esta asignado a un colaborador...';
                    $('#modalMessageError').modal('show');
                    $('#modalConfirmDelete').modal('hide');
                }
            });
        }

        if (tbafect == "tppagores"){

            $http.post(API_URL + 'Nomenclador/deleteTipoPagoResidente', data).success(function(response) {


                if(response.success == true){

                    $scope.CargadataPagoResidente();
                    $('#modalConfirmDelete').modal('hide');
                    $scope.tipoidentificacion_del = 0;
                    $scope.message = 'Se eliminó correctamente el Registro seleccionado...';
                    $('#modalMessage').modal('show');
                    $scope.hideModalMessage();

                } else {
                    $scope.message_error = 'El registro no puede ser eliminado porque esta asignado a un colaborador...';
                    $('#modalMessageError').modal('show');
                    $('#modalConfirmDelete').modal('hide');
                }
            });
        }

        if (tbafect == "pagopais"){

            $http.post(API_URL + 'Nomenclador/deletepagopais', data).success(function(response) {


                if(response.success == true){

                    $scope.CargadataPagoPais();
                    $('#modalConfirmDelete').modal('hide');
                    $scope.tipoidentificacion_del = 0;
                    $scope.message = 'Se eliminó correctamente el Registro seleccionado...';
                    $('#modalMessage').modal('show');
                    $scope.hideModalMessage();

                } else {
                    $scope.message_error = 'El registro no puede ser eliminado porque esta asignado a un colaborador...';
                    $('#modalMessageError').modal('show');
                    $('#modalConfirmDelete').modal('hide');
                }
            });
        }

        if (tbafect == "formapago"){

            $http.post(API_URL + 'Nomenclador/deleteformapago', data).success(function(response) {


                if(response.success == true){

                    $scope.CargadataFormaPago();
                    $('#modalConfirmDelete').modal('hide');
                    $scope.tipoidentificacion_del = 0;
                    $scope.message = 'Se eliminó correctamente el Registro seleccionado...';
                    $('#modalMessage').modal('show');
                    $scope.hideModalMessage();

                } else {
                    $scope.message_error = 'El registro no puede ser eliminado porque esta asignado a un colaborador...';
                    $('#modalMessageError').modal('show');
                    $('#modalConfirmDelete').modal('hide');
                }
            });
        }

        if (tbafect == "prov"){

            $http.post(API_URL + 'Nomenclador/deleteprovincia', data).success(function(response) {


                if(response.success == true){

                    $scope.CargadataProvincia();
                    $('#modalConfirmDelete').modal('hide');
                    $scope.tipoidentificacion_del = 0;
                    $scope.message = 'Se eliminó correctamente el Registro seleccionado...';
                    $('#modalMessage').modal('show');
                    $scope.hideModalMessage();

                } else {
                    $scope.message_error = 'El registro no puede ser eliminado porque esta asignado a un colaborador...';
                    $('#modalMessageError').modal('show');
                    $('#modalConfirmDelete').modal('hide');
                }
            });
        }

        if (tbafect == "canton"){

            $http.post(API_URL + 'Nomenclador/deletecantonEX', data).success(function(response) {

                //console.log(response);
                if(response.success == true){

                    $scope.CargadataCanton();
                    $('#modalConfirmDelete').modal('hide');
                    $scope.tipoidentificacion_del = 0;
                    $scope.message = 'Se eliminó correctamente el Registro seleccionado...';
                    $('#modalMessage').modal('show');
                    $scope.hideModalMessage();

                } else {
                    $scope.message_error = 'El registro no puede ser eliminado porque esta asignado a un colaborador...';
                    $('#modalMessageError').modal('show');
                    $('#modalConfirmDelete').modal('hide');
                }
            });
        }

        if (tbafect == "parroquia"){

            $http.post(API_URL + 'Nomenclador/deleteParroquiaEX', data).success(function(response) {


                if(response.success == true){

                    $scope.CargadataParroquia();
                    $('#modalConfirmDelete').modal('hide');
                    $scope.tipoidentificacion_del = 0;
                    $scope.message = 'Se eliminó correctamente el Registro seleccionado...';
                    $('#modalMessage').modal('show');
                    $scope.hideModalMessage();

                } else {
                    $scope.message_error = 'El registro no puede ser eliminado porque esta asignado a un colaborador...';
                    $('#modalMessageError').modal('show');
                    $('#modalConfirmDelete').modal('hide');
                }
            });
        }
    };


    $scope.hideModalMessage = function () {
        setTimeout("$('#modalMessage').modal('hide')", 3000);
    };



});

