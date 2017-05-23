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
            case "2": ///Estados Situacion Finaciera
                $scope.aux_render="2";
                $scope.generar_estado_resultados();
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
        };
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
                $scope.aux_tot_libroD_debe+= parseFloat(item.debe_c);
                $scope.aux_tot_libroD_haber+=parseFloat(item.haber_c);
            });
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
    ///---Fin proceso estado de cambios en el patrimonio


    ///--- Imprimir reportes
    $scope.print_report=function(){
        switch($scope.cmb_generar){
            case "1": ///Estados Cambios Patrimonio
                $scope.print_estado_cambios_patrimonio();
            break;
            case "2": ///Estados Situacion Finaciera
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
        };
    };
    ///---Fin imprimir reportes


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

      var accion = API_URL + "Balance/estado_resultados_print/"+JSON.stringify($scope.filtro_estado_resultado);
        $("#WPrint_head").html("Estado De Situación Finaciera");
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