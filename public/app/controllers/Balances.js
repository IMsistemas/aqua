app.controller('ReportBalanceContabilidad', function($scope, $http, API_URL) {
    $scope.aux_render="0";
    $scope.txt_fechaI=first(); //Cargar por default el primer dia del año actual
    $scope.txt_fechaF=now();  // Cargar por default el dia actual 
    $scope.cmb_generar="2";
    $scope.cmb_estado="1";
    $scope.titulo_head_report="";

    
    $scope.aux_tot_libroD_debe=0;
    $scope.aux_tot_libroD_haber=0;
    $scope.libro_diario=[];


    $scope.aux_plancuentas=[];
    $scope.aux_cuenta_select={};
    $scope.libro_mayor=[];


    $scope.Balance_finaciero=[];
    $scope.Estado_resultados_finaciero=[];
    $scope.Balance_generado={};
    $scope.titulo_balance="";
    $scope.titulo_resultados="";

    $scope.cambio_patrimonio=[];

    $scope.filtro_diario={};
    $scope.filtro_mayor={};
    $scope.filtro_estado_resultado={};
    $scope.filtro_cambios_patrimonio={};

    $scope.list_activo=[];
    $scope.total_activo=0.0;
    $scope.list_pasivo=[];
    $scope.total_pasivo=0.0;
    $scope.list_patrimonio=[];
    $scope.total_patrimonio=0.0;

    $scope.list_ingreso=[];
    $scope.total_ingreso=0.0;
    $scope.list_costo=[];
    $scope.total_costo=0.0;
    $scope.list_gasto=[];
    $scope.total_gasto=0.0;

    $scope.list_balance_comprobacion=[];

    ///---generar reporte segun la opcion que seleecione 
    $scope.genera_report=function() {
        $scope.libro_mayor=[];
        $scope.libro_diario=[];

        $scope.Balance_finaciero=[];
        $scope.Estado_resultados_finaciero=[];

        $("#procesarinfomracion").modal("show");
        switch($scope.cmb_generar){
            case "1": ///Estados Cambios Patrimonio
                $scope.aux_render="1";
                $scope.estado_cambio_patrimonio();
            break;
            case "2": ///Estados Situación Financiera
                $scope.aux_render="2";
                //$scope.generar_estado_resultados();
                $scope.generar_de_estado_resultados();
            break;
            case "3": ///Libro Diario
                $scope.aux_render="3";
                $scope.titulo_head_report="Libro Diario Desde: "+convertDatetoDB($("#txt_fechaI").val())+" Hasta: "+convertDatetoDB($("#txt_fechaF").val());
                $scope.generar_libro_diario();
            break;
            case "4": ///Libro Mayor
                $scope.aux_render="4";
                $scope.titulo_head_report="Libro Mayor Desde: "+convertDatetoDB($("#txt_fechaI").val())+" Hasta: "+convertDatetoDB($("#txt_fechaF").val());
                $scope.BuscarCuentaContable();
            break;
            case "5": // balance general 
                $scope.aux_render="5";
                $scope.titulo_head_report="Estados Situación Financiera Desde: "+convertDatetoDB($("#txt_fechaI").val())+" Hasta: "+convertDatetoDB($("#txt_fechaF").val());
                $scope.generar_balance_general();
            break;
            case "6": // balance comprobacion 
                $scope.aux_render="6";
                $scope.titulo_head_report="Balance De Comprobacion ";
                $scope.generar_balance_de_comprobacion();
            break;
        };
    };
    ///--- Valida numero
    $scope.Valida_numero=function(valor) {
        if(parseFloat(valor)==0){
            return '';
        }else{
            return  valor;
        }
    }
    ///---  generar balance de comprobacion
    $scope.aux_total_debe_balance=0;
    $scope.aux_total_haber_balance=0;
    $scope.aux_total_sdebe_balance=0;
    $scope.aux_total_shaber_balance=0;
    $scope.generar_balance_de_comprobacion=function() {
        $scope.filtro_balance_de_comprobacion={
            FechaI:convertDatetoDB($("#txt_fechaI").val()),
            FechaF:convertDatetoDB($("#txt_fechaF").val()),
            Estado: $scope.cmb_estado
        };
        $http.get(API_URL + 'Balance/balance_de_comprobacion/'+JSON.stringify($scope.filtro_balance_de_comprobacion))
        .success(function(response){
               console.log(response);
               $scope.list_balance_comprobacion=response;
               $scope.list_balance_comprobacion.forEach(function (item) {
                   $scope.aux_total_debe_balance+=parseFloat(item.debe);
                   $scope.aux_total_haber_balance+=parseFloat(item.haber);
                   $scope.aux_total_sdebe_balance+=parseFloat(item.saldo_debe);
                   $scope.aux_total_shaber_balance+=parseFloat(item.saldo_haber);
               });
            $("#procesarinfomracion").modal("hide");
        });
    };
    ///---proceso estado de resultados (ingresos costos gastos)
    $scope.generar_de_estado_resultados=function () {
        $scope.filtro_estado_resultados={
            FechaI:convertDatetoDB($("#txt_fechaI").val()),
            FechaF:convertDatetoDB($("#txt_fechaF").val()),
            Estado: $scope.cmb_estado
        };
        $http.get(API_URL + 'Balance/estado_de_resultados/'+JSON.stringify($scope.filtro_estado_resultados))
        .success(function(response){
               console.log(response);
               $scope.list_ingreso=response.Ingreso;
               if($scope.list_ingreso[0]!=undefined || $scope.list_ingreso[0]!=null){
                $scope.total_ingreso=$scope.list_ingreso[0].balance; 
               }
               $scope.list_costo=response.Costo;
               if($scope.list_costo[0]!=undefined || $scope.list_costo[0]!=null){
                $scope.total_costo=$scope.list_costo[0].balance;
               }
               $scope.list_gasto=response.Gasto;
               if($scope.list_gasto[0]!=undefined || $scope.list_gasto[0]!=null){
                 $scope.total_gasto=$scope.list_gasto[0].balance;
               }
            $("#procesarinfomracion").modal("hide");
        });
    };
    ///---proceso balance general
    $scope.aux_formula_patrimonial=0;
    $scope.generar_balance_general=function () {
        $scope.aux_formula_patrimonial=0;
        $scope.filtro_balance_general={
            FechaI:convertDatetoDB($("#txt_fechaI").val()),
            FechaF:convertDatetoDB($("#txt_fechaF").val()),
            Estado: $scope.cmb_estado
        };
        $http.get(API_URL + 'Balance/balance_general/'+JSON.stringify($scope.filtro_balance_general))
        .success(function(response){
               console.log(response);
               $scope.list_activo=response.Activo;
               if($scope.list_activo[0]!=undefined || $scope.list_activo[0]!= null ){
                $scope.total_activo=$scope.list_activo[0].balance;
               }
               

               $scope.list_pasivo=response.Pasivo;
               if($scope.list_pasivo[0]!=undefined || $scope.list_pasivo[0]!= null ){
                $scope.total_pasivo=$scope.list_pasivo[0].balance;
               }
               
               
               $scope.list_patrimonio=response.Patrimonio;
               if($scope.list_patrimonio[0]!=undefined || $scope.list_patrimonio[0]!= null ){
                $scope.total_patrimonio=$scope.list_patrimonio[0].balance;
               }
               $scope.aux_formula_patrimonial=parseFloat($scope.total_pasivo) + parseFloat($scope.total_patrimonio);
               $scope.aux_formula_patrimonial=$scope.aux_formula_patrimonial.toFixed(4);
            $("#procesarinfomracion").modal("hide");
        });
    };
    ///---proceso libro diario
    $scope.generar_libro_diario=function () {
        $scope.filtro_diario={
            FechaI:convertDatetoDB($("#txt_fechaI").val()),
            FechaF:convertDatetoDB($("#txt_fechaF").val()),
            Estado: $scope.cmb_estado
        };

        $scope.aux_tot_libroD_debe=0;
        $scope.aux_tot_libroD_haber=0;

        $http.get(API_URL + 'Balance/libro_diario/'+JSON.stringify($scope.filtro_diario))
        .success(function(response){
            console.log(response);
            $scope.libro_diario=response;
            $scope.libro_diario.forEach(function(item){
                var total_debe=0;
                var total_haber=0;
                var estado;
                item.cont_registrocontable.forEach(function(reg){
                    total_debe+= parseFloat(reg.debe_c);
                    total_haber+=parseFloat(reg.haber_c);
                    estado=reg.estadoanulado;
                });

                $scope.aux_tot_libroD_debe+= parseFloat(total_debe);
                $scope.aux_tot_libroD_haber+=parseFloat(total_haber);

                var aux_total_debe={
                    debe_c:total_debe.toFixed(4),
                    haber_c:total_haber.toFixed(4),
                    estadoanulado:estado,
                    descripcion:item.descripcion,
                    cont_plancuentas:{jerarquia :''}
                };
                item.cont_registrocontable.push(aux_total_debe);
            });
            console.log($scope.libro_diario);
           /* $scope.libro_diario.forEach(function(item){
                $scope.aux_tot_libroD_debe+= parseFloat(item.debe_c);
                $scope.aux_tot_libroD_haber+=parseFloat(item.haber_c);
            });*/
            $scope.aux_tot_libroD_debe=$scope.aux_tot_libroD_debe.toFixed(4);
            $scope.aux_tot_libroD_haber=$scope.aux_tot_libroD_haber.toFixed(4);
            $("#procesarinfomracion").modal("hide");
        });
    };
    ///---Fin proceso libro diario
    ///---proceso libro mayor
    $scope.BuscarCuentaContable=function(){
        $("#PlanContable").modal("show");
        $http.get(API_URL + 'estadosfinacieros/plancontabletotal')
        .success(function(response){
            $scope.aux_plancuentas=response;
            $("#procesarinfomracion").modal("hide");
        });
    };

    $scope.select_cuenta=function(item) {
        $scope.aux_cuenta_select=item;
        console.log($scope.aux_cuenta_select);
    };
    $scope.aux_total_debe_m=0;
    $scope.aux_total_haber_m=0;
    $scope.generar_libro_mayor=function() {
        $("#PlanContable").modal("show");
        $scope.filtro_mayor={
            FechaI:convertDatetoDB($("#txt_fechaI").val()),
            FechaF:convertDatetoDB($("#txt_fechaF").val()),
            Estado: $scope.cmb_estado ,
            Cuenta:$scope.aux_cuenta_select
        };

        $http.get(API_URL + 'Balance/libro_mayor/'+JSON.stringify($scope.filtro_mayor))
        .success(function(response){
            console.log(response);
            $scope.libro_mayor=response;

            $scope.libro_mayor.forEach(function(i){
                $scope.aux_total_debe_m+=(i.debe_c!="")?parseFloat(i.debe_c):0;
                $scope.aux_total_haber_m+=(i.haber_c!="")?parseFloat(i.haber_c):0;
            });

            $("#procesarinfomracion").modal("hide");
            $("#PlanContable").modal("hide");
        });
    };
    ///---Fin proceso libro mayor

    ///--- proceso estado de resultados
    $scope.generar_estado_resultados=function() {
        $scope.filtro_estado_resultado={
            FechaI:convertDatetoDB($("#txt_fechaI").val()),
            FechaF:convertDatetoDB($("#txt_fechaF").val())
        };
        $scope.titulo_balance="Balance Hasta: "+convertDatetoDB($("#txt_fechaF").val());
        $scope.titulo_resultados="Estado De Resultados Desde : "+convertDatetoDB($("#txt_fechaI").val())+" Hasta: "+convertDatetoDB($("#txt_fechaF").val());
        $http.get(API_URL + 'Balance/estado_resultados/'+JSON.stringify($scope.filtro_estado_resultado))
        .success(function(response){
            console.log(response);
            $scope.Balance_finaciero=response[0];
            $scope.Estado_resultados_finaciero=response[1];
            $scope.Balance_generado=response[2];
            $("#procesarinfomracion").modal("hide");
        });  
    };
    ///---Fin proceso estado de resultados
    ///--- proceso estado de cambios en el patrimonio
    $scope.estado_cambio_patrimonio=function() {
        $scope.filtro_cambios_patrimonio={
            FechaI:convertDatetoDB($("#txt_fechaI").val()),
            FechaF:convertDatetoDB($("#txt_fechaF").val())
        };
        $scope.titulo_head_report="Estado De Cambios En El Patrimonio en el Perdio : "+convertDatetoDB($("#txt_fechaI").val())+" y "+convertDatetoDB($("#txt_fechaF").val());
        $scope.aux_Fecha_I=convertDatetoDB($("#txt_fechaI").val());
        $scope.aux_Fecha_F=convertDatetoDB($("#txt_fechaF").val());

        $http.get(API_URL + 'Balance/estado_cambio_patrimonio/'+JSON.stringify($scope.filtro_cambios_patrimonio))
        .success(function(response){
            console.log(response);
            $scope.cambio_patrimonio=response;
            $("#procesarinfomracion").modal("hide");
        }); 
    };
    $scope.orden_plan_cuenta=function(orden){
        var aux_orden=orden.split(".");
        var aux_numero_orden="";
        var aux_numero_completar="";
        var tam=aux_orden.length;
        if(tam>0){
            for(var x=0;x<tam;x++){
                if(x<3){
                    aux_numero_orden+=aux_orden[x];
                }else if(x>=3){
                    if(x==3){
                        aux_numero_completar=aux_orden[x];
                        if( aux_numero_completar.length==1){
                            aux_numero_completar="0"+aux_numero_completar;
                        }
                        aux_numero_orden+=aux_numero_completar;
                    }else if(x>3){
                        aux_numero_orden+=aux_orden[x];
                    }

                }
            }
        }else{
           aux_numero_orden=orden; 
        }
        return aux_numero_orden;
    };

    ///---Fin proceso estado de cambios en el patrimonio


    ///--- Imprimir reportes
    $scope.print_report=function(){
        switch($scope.cmb_generar){
            case "1": ///Estados Cambios Patrimonio
                $scope.print_estado_cambios_patrimonio();
            break;
            case "2": ///Estados Situación Financiera
                $scope.print_estado_finaciero();
            break;
            case "3": ///Libro Diario
                $scope.print_libro_diario();
            break;
            case "4": ///Libro Mayor
                if($scope.aux_cuenta_select!=undefined & $scope.aux_cuenta_select.idplancuenta!=undefined){
                    $scope.print_libro_mayor();
                }else{
                    //QuitarClasesMensaje();
                    //$("#titulomsm").addClass("btn-warning");
                    $("#msm").modal("show");
                    $scope.Mensaje="Debe generar el reporte para imprimir";
                }
            break;
            case "5":
                $scope.print_balance_reporte();
            break;
            case "6":
                $scope.print_balance_de_comprobacion();
            break;
        };
    };
    ///---Fin imprimir reportes
    ///--- print balance de comprobacion
    $scope.print_balance_de_comprobacion=function() {
        $scope.filtro_balance_de_comprobacion={
            FechaI:convertDatetoDB($("#txt_fechaI").val()),
            FechaF:convertDatetoDB($("#txt_fechaF").val()),
            Estado: $scope.cmb_estado
        };
        var accion = API_URL + "Balance/balance_de_comprobacion_print/"+JSON.stringify($scope.filtro_balance_de_comprobacion);
        $("#WPrint_head").html("Balance De Comprobación");
        $("#WPrint").modal("show");
        $("#bodyprint").html("<object width='100%' height='600' data='"+accion+"'></object>");
    };
    ///---- prit balance 
    $scope.print_balance_reporte=function() {
        $scope.filtro_balance_general={
            FechaI:convertDatetoDB($("#txt_fechaI").val()),
            FechaF:convertDatetoDB($("#txt_fechaF").val()),
            Estado: $scope.cmb_estado
        };
        var accion = API_URL + "Balance/balance_general_print/"+JSON.stringify($scope.filtro_balance_general);
        $("#WPrint_head").html("Estados Situación Financiera");
        $("#WPrint").modal("show");
        $("#bodyprint").html("<object width='100%' height='600' data='"+accion+"'></object>");
    };
    ///---- prit balance 

    ///--- print libro diario
    $scope.print_libro_diario=function () {
        $scope.filtro_diario={
            FechaI:convertDatetoDB($("#txt_fechaI").val()),
            FechaF:convertDatetoDB($("#txt_fechaF").val()),
            Estado: $scope.cmb_estado
        };
        var accion = API_URL + "Balance/libro_diario_print/"+JSON.stringify($scope.filtro_diario);
        $("#WPrint_head").html("Libro Diario");
        $("#WPrint").modal("show");
        $("#bodyprint").html("<object width='100%' height='600' data='"+accion+"'></object>");
    };
    ///---Fin print libro diario
    ///--- print libro mayor
    $scope.print_libro_mayor=function () {
        $scope.filtro_mayor={
            FechaI:convertDatetoDB($("#txt_fechaI").val()),
            FechaF:convertDatetoDB($("#txt_fechaF").val()),
            Estado: $scope.cmb_estado ,
            Cuenta:$scope.aux_cuenta_select
        };
        var accion = API_URL + "Balance/libro_mayor_print/"+JSON.stringify($scope.filtro_mayor);
        $("#WPrint_head").html("Libro Mayor");
        $("#WPrint").modal("show");
        $("#bodyprint").html("<object width='100%' height='600' data='"+accion+"'></object>");
    };
    ///---Fin print libro mayor
    //--- 
    $scope.print_estado_finaciero=function() {
        $scope.filtro_estado_resultado={
            FechaI:convertDatetoDB($("#txt_fechaI").val()),
            FechaF:convertDatetoDB($("#txt_fechaF").val())
        };

      //var accion = API_URL + "Balance/estado_resultados_print/"+JSON.stringify($scope.filtro_estado_resultado);
      var accion = API_URL + "Balance/estado_de_resultados_print/"+JSON.stringify($scope.filtro_estado_resultado);
        //$("#WPrint_head").html("Estado De Situación Finaciera");
        $("#WPrint_head").html("Estado De Resultados");
        $("#WPrint").modal("show");
        $("#bodyprint").html("<object width='100%' height='600' data='"+accion+"'></object>");  
    };
    ///---
    $scope.print_estado_cambios_patrimonio=function() {
       $scope.filtro_cambios_patrimonio={
            FechaI:convertDatetoDB($("#txt_fechaI").val()),
            FechaF:convertDatetoDB($("#txt_fechaF").val())
        };

      var accion = API_URL + "Balance/estado_cambios_patrimonio_print/"+JSON.stringify($scope.filtro_cambios_patrimonio);
        $("#WPrint_head").html("Estado De Cambios En El Patrimonio");
        $("#WPrint").modal("show");
        $("#bodyprint").html("<object width='100%' height='600' data='"+accion+"'></object>");  
    };
    ///---
    $scope.formato_dinero=function(amount, signo){
        if(amount==""){
            return "";
        }
        amount += ''; // por si pasan un numero en vez de un string
        amount = parseFloat(amount.replace(/[^0-9\.-]/g, '')); // elimino cualquier cosa que no sea numero o punto
        // si es mayor o menor que cero retorno el valor formateado como numero
        amount = '' + amount.toFixed(4);
        var amount_parts = amount.split('.'),
            regexp = /(\d+)(\d{3})/;
        while (regexp.test(amount_parts[0]))
            amount_parts[0] = amount_parts[0].replace(regexp, '$1' + ',' + '$2');
        return signo+" "+amount_parts.join('.')
    };
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

function now(){
    var now = new Date();
    var dd = now.getDate();
    if (dd < 10) dd = '0' + dd;
    var mm = now.getMonth() + 1;
    if (mm < 10) mm = '0' + mm;
    var yyyy = now.getFullYear();
    return dd + "\/" + mm + "\/" + yyyy;
}
function first(){
    var now = new Date();
    var yyyy = now.getFullYear();
    return "01/01/"+ yyyy;
}

function completarNumer(valor){
    if(valor.toString().length>0){
        var i=5;
        var completa="0";
        var aux_com=i-valor.toString().length;
        for(x=0;x<aux_com;x++){
            completa+="0";
        }
    }
    return completa+valor.toString();
}

function QuitarClasesMensaje() {
    $("#titulomsm").removeClass("btn-primary");
    $("#titulomsm").removeClass("btn-warning");
    $("#titulomsm").removeClass("btn-success");
    $("#titulomsm").removeClass("btn-info");
    $("#titulomsm").removeClass("btn-danger");
}
$(document).ready(function(){
    $('.datepicker').datetimepicker({
        locale: 'es',
        format: 'DD/MM/YYYY',
        ignoreReadonly: true
    });
});