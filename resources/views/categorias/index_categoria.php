
    <div ng-controller="categoriasController">
    
    <div class="container">
        
    
        <div class="col-xs-12" style="margin-top: 2%; margin-bottom: 2%">
            <div class="col-sm-4 col-xs-6">
                <div class="form-group has-feedback">
                    <input type="text" class="form-control" id="search" placeholder="BUSCAR..."
                           ng-model="search" ng-change="searchByFilter()">
                    <span class="glyphicon glyphicon-search form-control-feedback" aria-hidden="true"></span>
                </div>
            </div>
            <div class="col-sm-4 col-xs-6">
                <div class="form-group has-feedback">
                    <select class="form-control" name="idCategoria" id="idCategoria" ng-model="idCategoria"
                        ng-change="searchByFilter()">
                        <option value="">Escoja Categorías</option>
						<option ng-repeat="item in categoriasFiltro"						       
						        value="{{item.idcategoria}}">{{item.nombrecategoria}}     
						</option>                        
                        </select>                    
                </div>
            </div>
            <div class="col-sm-4 col-xs-2">
                <button type="button" class="btn btn-primary" style="float: right;" ng-click="addCategoria(1)" ng-disabled="button">
                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                </button>
            </div>
            <div class="col-xs-12">
                <table class="table table-responsive table-striped table-hover table-condensed">
                    <thead class="bg-primary">
                    <tr>
                        <th style="width: 10%; text-align: center;">
                        <a href="" style="text-decoration:none; color:white;" ng-click="ordenarColumna='idcategoria'; reversa=!reversa;">
                        Código</a></th>
                        <th style="text-align: center;">Nombre</th>
                        <th style="width: 30%;">Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr ng-repeat="categoria in categorias | orderBy:ordenarColumna:reversa">
                        <td >
                        <span ng-class="{'negrita': categoria.idcategoria.indexOf('.') === -1}" >{{categoria.idcategoria}}</span>
                        
                        </td>
                        <td>
	                        <div class="form-group" ng-form name="myForm">
		                        <input type="text" class="form-control" name="nombre" id="nombre" ng-class="{'negrita': categoria.idcategoria.indexOf('.') === -1}"
		                          ng-model="categoria.nombrecategoria" ng-required="true" ng-maxlength="19" ng-pattern="/[a-zA-ZáéíóúñÑ0-9. ]+/" focus-me="$index == edit">
		                          <span class="help-block error"
                                                      ng-show="myForm.nombre.$invalid && myForm.nombre.$touched">El Nombre es requerido</span>
		                          <span class="help-block error"
		                                ng-show="myForm.nombre.$invalid && myForm.nombre.$error.maxlength">La longitud máxima es de 19 caracteres</span>
		                          <span class="help-block error"
		                                ng-show="myForm.nombre.$invalid && myForm.nombre.$error.pattern">El nombre debe ser solo letras, puntos, números, guion y espacios</span>
		                                {{ valid(myForm.$invalid) }}
		                     </div>
                        </td>
                        <td class="text-center">
                        
                        <div class="buttons" ng-show="$index == edit">
					          <button type="button" ng-click="saveCategoria($index)" class="btn btn-primary" ng-disabled="myForm.$invalid">
					            Guardar
					          </button>
					          <button type="button" ng-click="cancel($index)" class="btn btn-default">
					            Cancelar
					          </button>
					     
                        </div>
                        
                        <div class="buttons" ng-show="$index != edit">
                            <button type="button" class="btn btn-warning" ng-click="addSubCategoria(categoria.idcategoria)" ng-disabled="button">
                              {{ (categoria.idcategoria.indexOf('.') === -1)?'Línea': 'Sublínea' }} <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                            </button>
                            <button type="button" class="btn btn-danger" ng-click="showModalConfirm(categoria.idcategoria)">
                               <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                            </button>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        <div class="col-sm-12 col-xs-12">
                <button type="button" class="btn btn-primary" style="float: right;" ng-click="saveAllCategorias()" ng-disabled="buttonSave">
                   Guardar 
                </button>
            </div>
        </div>       

        <div class="modal fade" tabindex="-1" role="dialog" id="modalMessage">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header modal-header-success">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Confirmación</h4>
                    </div>
                    <div class="modal-body">
                        <span>{{message}}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" tabindex="-1" role="dialog" id="modalConfirmDelete">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header modal-header-danger">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Confirmación</h4>
                    </div>
                    <div class="modal-body">
                        <span>Realmente desea eliminar el Item: <span style="font-weight: bold;">{{item_seleccionado}}</span></span><br>
						<span ng-show="hijos>1">El Item tiene : <span style="font-weight: bold;">{{hijos - 1}}</span> subitems asosiado(s) y tambien se eliminarán</span>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            Cancelar <span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span>
                        </button>
                        <button type="button" class="btn btn-danger" id="btn-save" ng-click="destroyCategoria()">
                            Eliminar <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    
    

    