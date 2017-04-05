app.controller('Kardex', function($scope, $http, API_URL) {
    $scope.FechaK=now();  // Cargar por default el dia actual 
    $scope.FechaF=now();
    $scope.FechaI=first();
    $scope.Bodegas=[]; //listas Bodegas
    $scope.Categoria1=[]; //lista categoria 1
    $scope.Categoria2=[]; //lista categoria 2 (subcategoria)

    $scope.CategoriaItem="";
    $scope.SubCategoriaItem="";
    $scope.BodegaItem="";

    $scope.Inventario=[];
    $scope.Kardex=[];

    ///---
     $scope.pageChanged = function(newPage) {
        $scope.initLoad(newPage);
    };
    $scope.initLoad = function(pageNumber){

        /*if ($scope.busqueda == undefined) {
            var search = null;
        } else var search = $scope.busqueda;

        var filtros = {
            search: search
        };*/
        /*$http.get(API_URL + 'DocumentoVenta/getAllFitros?page=' + pageNumber + '&filter=' + JSON.stringify(filtros))
            .success(function(response){
                /*$scope.Allventas=response;
                console.log(response);*/
                /*$scope.Allventas = response.data;
                $scope.totalItems = response.total;
         });*/

          var filtro={
            Fecha: convertDatetoDB($("#FechaK").val()),
            Categoria: $scope.CategoriaItem,
            SubCategria: $scope.SubCategoriaItem,
            Bodega : $scope.BodegaItem,
            Search: $scope.search,
            Tipo: 'B'
        };
        if($("#FechaK").val()!=""){
            if($scope.BodegaItem!=""){
                $http.get(API_URL + 'procesoskardex/loadinventario/?page=' + pageNumber + '&filter='+JSON.stringify(filtro))
                    .success(function(response){
                        $scope.Inventario=response.data;
                        $scope.totalItems = response.total;
                });
            }else{
                QuitarClasesMensaje();
                $("#titulomsm").addClass("btn-warning");
                $scope.Mensaje="Seleccione Una Bodega";
                $("#msm").modal("show");   
            }
        }else{
            QuitarClasesMensaje();
            $("#titulomsm").addClass("btn-warning");
            $scope.Mensaje="Seleccione Un Fecha";
            $("#msm").modal("show");   
        }
    };
    ///---
    $scope.CargarBodegas=function(){
        $http.get(API_URL + 'procesoskardex/loadbodegas')
        .success(function(response){
            $scope.Bodegas=response;
        });
    };
    ///---
    $scope.CargarCategoriaNivel1=function(){
        $http.get(API_URL + 'procesoskardex/loadcategoria')
        .success(function(response){
            $scope.Categoria1=response;
        });
    };
    ///---
    $scope.CargarCategoriaNivel2=function(){
        if($scope.CategoriaItem!=""){
            $http.get(API_URL + 'procesoskardex/loadsubcategoria/'+$scope.CategoriaItem)
            .success(function(response){
                $scope.Categoria2=response;
            });
        }
    };
    ///---
    $scope.search="";
    $scope.CargarInventario=function () {
        var filtro={
            Fecha: convertDatetoDB($("#FechaK").val()),
            Categoria: $scope.CategoriaItem,
            SubCategria: $scope.SubCategoriaItem,
            Bodega : $scope.BodegaItem,
            Search: $scope.search,
            Tipo: 'B'
        };
        if($("#FechaK").val()!=""){
            if($scope.BodegaItem!=""){
                $scope.EnviarFiltroInventario(filtro);
            }else{
                QuitarClasesMensaje();
                $("#titulomsm").addClass("btn-warning");
                $scope.Mensaje="Seleccione Una Bodega";
                $("#msm").modal("show");   
            }
        }else{
            QuitarClasesMensaje();
            $("#titulomsm").addClass("btn-warning");
            $scope.Mensaje="Seleccione Un Fecha";
            $("#msm").modal("show");   
        }
    };
    $scope.EnviarFiltroInventario=function(data){
        $http.get(API_URL + 'procesoskardex/loadinventario/'+JSON.stringify(data))
            .success(function(response){
                $scope.Inventario=response;
        });
    };
    ///---
    $scope.ActivasInactivas="A";
    $scope.itemproductobodega={};
     $scope.RegistroKardexPP=function (item) {
         $("#RegistroKardePromedioPonderado").modal("show");
         var filtro={
            FechaI: convertDatetoDB($("#FechaI").val()),
            FechaF: convertDatetoDB($("#FechaF").val()),
            idproducto_bodega: item.idproducto_bodega,
            Estado: $scope.ActivasInactivas,
            Tipo: 'B'
        };
        $scope.itemproductobodega=item;
         $http.get(API_URL + 'procesoskardex/loadkardex/'+JSON.stringify(filtro))
            .success(function(response){
                $scope.Kardex=response;
        });
     };
     $scope.RegistroKardexPPActualizar=function () {
         $("#RegistroKardePromedioPonderado").modal("show");
         var filtro={
            FechaI: convertDatetoDB($("#FechaI").val()),
            FechaF: convertDatetoDB($("#FechaF").val()),
            idproducto_bodega: $scope.itemproductobodega.idproducto_bodega,
            Estado: $scope.ActivasInactivas,
            Tipo: 'B'
        };
         $http.get(API_URL + 'procesoskardex/loadkardex/'+JSON.stringify(filtro))
            .success(function(response){
                $scope.Kardex=response;
        });
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