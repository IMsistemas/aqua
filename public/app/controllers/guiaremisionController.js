app.controller('guiaremisionController', function($scope, $http, API_URL) {

    $scope.guiaremision = [];
    $scope.idguiaremision_del = 0;
    $scope.modalstate = '';
    $scope.ActivaGuia='0';
    $scope.itemguiaretension=[];
    $scope.index=1;
    $scope.edit=0;
    $scope.idguia=null;
    $scope.guiaventa=0;

    
    

    $scope.initLoad = function(){
        $http.get(API_URL + 'guiaremision/getGiaremision').success(function(response){
            $scope.guiaremision = response;
            console.log(response);
        });
    }

     $scope.pageChanged = function(newPage) {
        $scope.initLoad(newPage);
    };
    $scope.initLoad = function(pageNumber){

        if ($scope.busqueda == undefined) {
            var search = null;
        } else var search = $scope.busqueda;

        var filtros = {
            search: search
        };

        $http.get(API_URL + 'cargo/getCargos?page=' + pageNumber + '&filter=' + JSON.stringify(filtros)).success(function(response){
            $scope.cargos = response.data;
            $scope.totalItems = response.total;
        });
    };

    $scope.editarGuia = function(){
        $http.get(API_URL + 'guiaremision/getGiaremision').success(function(response){
            $scope.guiaremision = response;
        });
    }

    $scope.initLoad();

    $scope.createRow = function() {     
        $scope.editar=false;
        $scope.itemguiaretension.push($scope.newRow());                             
    };

    $scope.newRow = function(){
        $scope.read =  false;
        return {cantidad:null,
                tipoempaque:null,
                peso:null,
                largo:null,
                ancho:null,
                altura:null,
                pvolumetrico:null,
                descripcion:null
        }
    };

    $scope.delItemGuiaRetension = function(index) {    
        $scope.itemguiaretension.splice(index, 1);    
    };

    $scope.onlyCharasterAndSpace = function ($event) {

            var k = $event.keyCode;
            if (k == 8 || k == 0) return true;
            var patron = /^([a-zA-Záéíóúñ\s]+)$/;
            var n = String.fromCharCode(k);

            if(patron.test(n) == false){
                $event.preventDefault();
                return false;
            }
            else return true;

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
                            return relleno+text;
                        }
                    };

    $scope.onlyCharasterAndSpace = function ($event) {
        var k = $event.keyCode;
            if (k == 8 || k == 0) return true;
            var patron = /^([a-zA-Záéíóúñ\s]+)$/;
            var n = String.fromCharCode(k);
        if(patron.test(n) == false){
            $event.preventDefault();
            return false;
           }
           else return true;
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
            console.log(field);
        };

    $scope.BuscarVenta=function(){
        $scope.guiaventa=1;
        $http.get(API_URL + 'guiaremision/getventa/'+$scope.venta.title)
        .success(function(response){
            $scope.ventanumautorizacion=response.venta.nroautorizacionventa;
            $scope.ventafecha=new Date(response.venta.fechaemisionventa);
            $scope.t_establ=response.venta.nroguiaremision.substring(0, 3);
            $scope.t_pto=response.venta.nroguiaremision.substring(4, 7);
            $scope.t_sec=response.venta.nroguiaremision.substring(8, 15);
            $scope.guia=response.productos;
            console.log($scope.guia);
        });
    };

    $scope.save = function() {   
        var datas = [];
        $scope.impreso = true;
        $scope.itemguiaretension.forEach(function(itemm) { 
             var row = {
                cantidad:itemm.cantidad,
                tipoempaque:itemm.tipoempaque,
                peso:itemm.peso,
                largo:itemm.largo,
                ancho:itemm.ancho,
                altura:itemm.altura,
                descripcion:itemm.descripcion
            };
             datas.push(row);
         });
         
        var url = API_URL + "guiaremision";
            if ($scope.guiaventa ==0 && $scope.edit==0){
                    var guiaremisionsave = {
                    citransportista: $scope.Transportista.title,
                    cidestinatario: $scope.Destinatario.title,
                    nrodocumentoguiaremision: $scope.t_establ + '-' + $scope.t_pto + '-' + $scope.t_sec,
                    nrodeclaracionaduana: $scope.docaduana,
                    codestablecdestino: $scope.codestablecimiento,
                    ruta: $scope.ruta,
                    fechainiciotransp: $scope.finiciotrans,
                    fechafintransp: $scope.ffintrans,
                    motivotraslado: $scope.motivotraslado,
                    direccdestinatario: $scope.Destinatario.originalObject.direccion,
                    puntopartida: $scope.puntopartida,
                    detallemer: datas
                };
            }else if($scope.guiaventa ==1 && $scope.edit==0){
                    var guiaremisionsave = {
                    citransportista: $scope.Transportista.title,
                    cidestinatario: $scope.Destinatario.title,
                    nrodocventa: $scope.venta.title,
                    nrodocumentoguiaremision: $scope.t_establ + '-' + $scope.t_pto + '-' + $scope.t_sec,
                    nrodeclaracionaduana: $scope.docaduana,
                    codestablecdestino: $scope.codestablecimiento,
                    ruta: $scope.ruta,
                    fechainiciotransp: $scope.finiciotrans,
                    fechafintransp: $scope.ffintrans,
                    motivotraslado: $scope.motivotraslado,
                    direccdestinatario: $scope.Destinatario.originalObject.direccion,
                    puntopartida: $scope.puntopartida,
                    detallemer: datas
                };
            
            }else{
                var guiaremisionsave = {
                    citransportista: $scope.citransportista,
                    cidestinatario: $scope.cidestinatario,
                    nrodocventa: $scope.nroventa,
                    nrodocumentoguiaremision: $scope.t_establ + '-' + $scope.t_pto + '-' + $scope.t_sec,
                    nrodeclaracionaduana: $scope.docaduana,
                    codestablecdestino: $scope.codestablecimiento,
                    ruta: $scope.ruta,
                    fechainiciotransp: $scope.finiciotrans,
                    fechafintransp: $scope.ffintrans,
                    motivotraslado: $scope.motivotraslado,
                    direccdestinatario: $scope.direccion,
                    puntopartida: $scope.puntopartida,
                    detallemer: datas
                };
            }
        console.log(guiaremisionsave);
        if ($scope.edit==0) {
            $http.post(url, guiaremisionsave ).success(function (response) {
            if (response.success == true) {
                $scope.message = 'Se insertó correctamente la Guía de Remisión';
                $('#modalMessage').modal('show');
                $scope.ActivaGuia=0;
                $scope.initLoad();
                $scope.hideModalMessage();
                $scope.BorrarEditar();
                $scope.guiaventa=0;
                }
            else {
                    $scope.message_error = 'Ocurrio un error intentelo mas tarde';
                    $('#modalMessageError').modal('show');
                    $scope.initLoad();
                    $scope.BorrarEditar();
                    $scope.ActivaGuia=0;
                    $scope.guiaventa=0;

                }
            });   
        } else {
            console.log(guiaremisionsave);
            $http.put(url+'/'+$scope.idguia, guiaremisionsave ).success(function (response) {
            if (response.success == true) {
                $scope.message = 'Se actualizó correctamente la Guía de Remisión';
                $('#modalMessage').modal('show');
                idguia=0;
                $scope.ActivaGuia=0;
                 $scope.initLoad();
                 $scope.BorrarEditar();
                 $scope.hideModalMessage();
                $scope.guiaventa=0;
                $scope.edit=0;
                }
            else {
                    $scope.message_error = 'Ocurrio un error intentelo mas tarde';
                    $('#modalMessageError').modal('show');
                    $scope.initLoad();
                    $scope.ActivaGuia=0;
                    $scope.BorrarEditar();
                    $scope.guiaventa=0;
                    $scope.edit=0;
                }
            });  
        }
    };


    $scope.todayinicio= function(fecha){
        $scope.day = new Date();
        $scope.finiciotransaux=$scope.finiciotrans.toDateString();
        $scope.dayaux=$scope.day.toDateString();
        if($scope.finiciotransaux <= $scope.dayaux)
        {
            $scope.Menor=0;
        }else{
            $scope.Menor=1;
        }
    };

    $scope.todayfin= function(fecha){
        $scope.day = new Date();
        $scope.ffintransaux=$scope.ffintrans.toDateString();
        $scope.dayaux=$scope.day.toDateString();
        if($scope.ffintransaux <= $scope.dayaux)
        {
            $scope.menor=0;
        }else{
            $scope.menor=1;
        }
    };

    $scope.delete = function(id){
        $http.delete(API_URL + 'guiaremision/'+id).success(function(response) {
            if(response.success == true){
                $scope.initLoad();
                $scope.idcargo_del = 0;
                $scope.message = 'Se eliminó correctamente la Guía de Remisión';
                $('#modalMessage').modal('show');
                $scope.hideModalMessage();

            } else {
                $scope.message_error = 'La Guía de Remisión no puede ser eliminada porque esta asignado a un colaborador...';
                $('#modalMessageError').modal('show');
                $('#modalConfirmDelete').modal('hide');
            }
        });

    };

    $scope.editarGuia= function(item){
        $scope.editar=true;
        $http.get(API_URL + 'guiaremision/getGuia/'+item.iddocumentoguiaremision)
        .success(function(response){
            console.log(response);
            $scope.t_establ=response.guiaremision.nrodocumentoguiaremision.substring(0,3);
            $scope.t_pto=response.guiaremision.nrodocumentoguiaremision.substring(4,7);
            $scope.t_sec=response.guiaremision.nrodocumentoguiaremision.substring(8,18);
            $scope.puntopartida=response.guiaremision.puntopartida;
            $scope.$broadcast('angucomplete-alt:changeInput', 'citransportista', $scope.citransportista=response.transportista[0].numdocidentific);
            $scope.transrazoncocial=response.transportista[0].razonsocial;
            $scope.placa=response.transportista[0].placa;
            $scope.ruta=response.guiaremision.ruta;
            $scope.finiciotrans = new Date(response.guiaremision.fechainiciotransp);
            $scope.ffintrans = new Date(response.guiaremision.fechafintransp);
            $scope.motivotraslado=response.guiaremision.motivotraslado;
            $scope.codestablecimiento=response.guiaremision.codestablecdestino;
            $scope.docaduana=response.guiaremision.nrodeclaracionaduana;
            $scope.$broadcast('angucomplete-alt:changeInput', 'cidestinatario', $scope.cidestinatario=response.destinatario[0].numdocidentific);
            $scope.destirazonsocial=response.destinatario[0].razonsocial;
            $scope.direccion=response.destinatario[0].direccion;
            $scope.itemguiaretension=response.mercaderia;
            $scope.idguia=response.guiaremision.iddocumentoguiaremision;
            if ($scope.nroventa == "undefined") {
                
            }else{
                $scope.nroventa=response.venta.numdocumentoventa;
                $scope.$broadcast('angucomplete-alt:changeInput', 'nrodocventa', $scope.ventanumautorizacion=response.venta.nroautorizacionventa);
                $scope.ventafecha=response.venta.fechaemisionventa;
                $scope.guia=response.productos;
            } 
        });
        $scope.ActivaGuia=1;
        $scope.edit=1;        
    };

    $scope.hideModalMessage = function () {
        setTimeout("$('#modalMessage').modal('hide')", 3000);
    };

    $scope.BorrarEditar = function () {
        $scope.initLoad();
        $scope.t_establ=null;
        $scope.t_pto=null;
        $scope.t_sec=null;
        $scope.puntopartida=null;
        $scope.citransportista=null;
        $scope.transrazonsocial=null;
        $scope.placa=null;
        $scope.ruta=null;
        $scope.finiciotrans =null; 
        $scope.ffintrans =null; 
        $scope.motivotraslado=null;
        $scope.codestablecimiento=null;
        $scope.docaduana=null;
        $scope.cidestinatario=null;
        $scope.destirazoncocial=null;
        $scope.direccion=null;
        $scope.itemguiaretension=[];
        $scope.ventanumautorizacion=null;
        $scope.ventafecha=null;
        $scope.guia=null;
        $scope.$broadcast('angucomplete-alt:clearInput','citransportista');
        $scope.$broadcast('angucomplete-alt:clearInput','cidestinatario');
        $scope.$broadcast('angucomplete-alt:clearInput','nroventa');
        if ($scope.guiaventa==1) {
            $scope.Transportista.originalObject.razonsocial=null;
            $scope.Transportista,originalObject.placa=null;
            $scope.Destinatario.originalObject.razonsocial=null;
            $scope.Destinatario,originalObject.direccion=null;
        }
    };



    $scope.BloquearGuardar = function () {
        document.guardar.disabled=true;
    };
    
});    
