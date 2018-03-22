<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <button class="btn btn-success btn-sm" v-on:click="getTables(); showSuccess()">Actualizar</button>
            </div><br><br>
            <!--Listado de mesas-->
            <div class="col-lg-3 col-md-6 col-sm-12" v-for="(table, index) in tables">
                <div class="card" style="height: 13rem;">
                    <div class="card-header">
                        {{ table.name }} 
                        <span class="badge float-right" :class="table.state ? 'badge-success' : 'badge-danger'">
                            {{ table.state | convertWord }}
                        </span>
                    </div>
                    <div class="card-body" style="padding:4px">
                        <div id="div1">
                            <!--Listado de comidas para la mesa actual-->
                            <table class="table table-sm" style="font-size:12px">
                                <tr v-for="(foodTemp, index) in table.foodtabletemps">
                                    <td>{{ foodTemp.food.name }}</td>
                                    <td>${{ convertToMoney(foodTemp.food.price) }}</td>
                                    <td><button class="btn  btn-sm" v-on:click="deleteFoodFromTable(foodTemp.id)">Quitar</button></td>
                                </tr>
                            </table>
                            <!--Fin Listado de comidas para la mesa actual-->
                        </div>
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-default btn-sm" v-if="table.state" v-on:click="assignTable(table.id, index)">Asignar mesa</button>
                        <button type="button" class="btn btn-info btn-sm" v-if="!table.state" v-on:click="showModalFoods(table.id)">Realizar pedido</button>
                        <button type="button" class="btn btn-success btn-sm" v-if="table.foodtabletemps.length>0" v-on:click="pay(table.id, index)">Pagar</button>
                        <!--<button class="btn btn-danger btn-sm" v-if="!table.state">Cancelar pedido</button>-->
                        <!--Total del pepido-->
                        <div class="float-right" v-if="table.foodtabletemps.length>0">
                            ${{ calculateTotalTable(index) }}
                        </div>
                        <!--Fin Total del pepido-->
                    </div>
                </div><br>
            </div>
            <!--Fin Listado de mesas-->
            <!--Modal show foods-->
            <div class="modal fade" id="modalFoods" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">{{ seeFoodFromListState ? 'Pedido' : 'Carta' }}</h5>
                    </div>
                    <div class="modal-body" id="modal-body">
                        <!--Listado de comidas para escoger-->
                        <template v-if="!seeFoodFromListState">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Buscar" v-model="find" v-on:keyup="getFoods()">
                            </div>
                            <div id="div2">
                                <table class="table table-hover table-sm">
                                    <thead>
                                        <th>Nombre</th>
                                        <th>Descripción</th>
                                        <th>Precio</th>
                                        <th>#</th>
                                    </thead>
                                    <tbody>
                                        <tr v-for="food in foods">
                                            <td>{{ food.name }}</td>
                                            <td>{{ food.description }}</td>
                                            <td>${{ convertToMoney(food.price) }}</td>
                                            <td><button class="btn btn-success btn-sm" v-on:click="addFoodTable(food.id, food.name, food.price)">Añadir</button></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </template>
                        <!--Listado de comida escogida por el cliente-->
                        <template v-if="seeFoodFromListState">
                            <div id="div3" v-if="foodtabletemps.length>0">
                                <table class="table table-hover table-sm">
                                    <thead>
                                        <th>Nombre</th>
                                        <th>Precio</th>
                                        <th>#</th>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(foodSelected, index) in foodtabletemps">
                                            <td>{{ foodSelected.name }}</td>
                                            <td>${{ convertToMoney(foodSelected.price) }}</td>
                                            <td><button class="btn btn-danger btn-sm" v-on:click="deleteFoodTemp(index)">Eliminar</button></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <p v-else>No has añadido nada al pedido</p>
                        </template>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-success" v-if="foodtabletemps.length>0 && seeFoodFromListState" v-on:click="saveFoodsTemp()">Guardar pedido</button>
                        <button class="btn btn-primary" v-on:click="seeFoodFromListState ? seeFoodFromListState=false : seeFoodFromList()">
                            {{ seeFoodFromListState ? 'Añadir más a la lista' : 'Ver pedido' }}
                        </button>
                        <button type="button" class="btn btn-secondary" v-on:click="closeModalFoods()">Cerrar</button>
                    </div>
                    </div>
                </div>
            </div>
            <!--End Modal show Foods-->
            <!--Modal Pay-->
            <!-- Modal -->
            <div class="modal fade" id="modalPay" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Pagar cuenta</h5>
                </div>
                <div class="modal-body">
                    <center>
                        <div class="form-group">
                            <label for="">Total a pagar</label>
                            <h3 v-text="'$'+convertToMoney(priceTotal)"></h3>
                        </div>
                        <div class="form-group">
                            <label for="">Cantidad con que pagan</label>
                            <form v-if="!payShowResult" v-on:submit.prevent="calculateTotal()">
                                    <div class="form-group">
                                        <input type="number" class="form-control" placeholder="" v-model="priceWithPay">
                                    </div>
                                    <button type="submit" class="btn btn-primary">Pagar</button>
                            </form>
                            <h3 v-if="payShowResult" v-text="'$'+convertToMoney(priceWithPay)"></h3>
                        </div>
                        <div class="form-group" v-if="payShowResult">
                            <label for="">Devolver</label>
                            <h3 v-text="'$'+convertToMoney(priceBack)"></h3>
                        </div>

                        <div class="form-check" v-if="confirmPay">
                            <hr>
                             <input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
                             <label class="form-check-label" for="defaultCheck1">
                                Imprimir factura
                            </label>
                        </div>
                    </center>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" v-on:click="cancelPay()">Cancelar</button>
                    <button type="button" class="btn btn-primary" v-if="confirmPay" v-on:click="payNow()">Confirmar pago</button>
                </div>
                </div>
            </div>
            </div>
            <!--End Modal Pay-->
        </div>
    </div>
</template>
<script>
    import toastr from 'toastr';    
    import FormatNum from 'format-num';
    export default {
        mounted(){
            this.getTables();
            this.getFoods();
        },
        data(){
            return{
                tables:null,
                foods:null,

                priceTotal:null,

                find:'',
                payShowResult:false,
                priceWithPay: null,
                priceBack:null,

                confirmPay:false,

                foodAdd:false,
                foodtabletemps:[],
                seeFoodFromListState:false,
                tableNow:'',
            }
        },
        methods: {
            getTables: function(){
                axios.get('tables').then(response=>{
                    this.tables = response.data;
                    if(this.foodAdd){
                        toastr.success('Comida añadida');
                        this.foodAdd = false;
                    }
                }).catch(errors=>{
                    toastr.warning('Ha ocurrido un error al llamar las mesas, actualiza por favor');
                });
            },
            getFoods: function(){
                axios.get('sales/getfoods?name='+this.find).then(response=>{
                    this.foods = response.data.data;
                });
            },
            assignTable: function(tableId, index){
                this.tables[index].state = !this.tables[index].state;
                axios.put('sales/'+tableId).then(response=>{ 
                }).catch(errors=>{
                    toastr.warning('Ups, algo salió mal al asignar la mesa, actualiza por favor');
                });
            },
            showModalFoods: function(tableId){//ok
                this.tableNow = tableId;
                $('#modalFoods').modal({backdrop: 'static', keyboard: false})
            },
            closeModalFoods: function(){//ok
                $('#modalFoods').modal('hide');
                this.seeFoodFromListState = false;
                this.foodtabletemps = [];
            },
            addFoodTable: function(foodId, foodName, foodPrice){//ok
                this.foodtabletemps.push({ id:foodId, name:foodName, price:foodPrice });
                toastr.success('Comida añadida a la lista');
            },
            seeFoodFromList: function(){//ok
                this.seeFoodFromListState = true;
            },  
            deleteFoodTemp: function(indice){//ok
                this.foodtabletemps.splice(indice, 1);
                toastr.success('Comida eliminada del pedido');
            },
            saveFoodsTemp: function(){//ok
                var foodsId = [];
                this.foodtabletemps.forEach(food => {
                    foodsId.push(food.id);
                });
                var data = {table:this.tableNow, foods:foodsId};
                axios.post('sales', data).then(response=>{
                    this.getTables();
                    $('#modalFoods').modal('hide');
                    toastr.success('El pedido ha sido realizado');
                    this.seeFoodFromListState = false;
                    this.foodtabletemps = [];
                });
            },
            deleteFoodFromTable: function(foodId, index){
                axios.delete('sales/'+foodId).then(response=>{
                    this.getTables();
                    toastr.success('Comida eliminada de la mesa');
                }).catch(errors=>{
                    toastr.warning('Ha ocurrido un error, actualiza por favor');
                });
            },
            showSuccess: function(){
                toastr.success('Actualizado');
            },
            calculateTotalTable: function(index){
                var total = 0;
                this.tables[index].foodtabletemps.forEach(element => {
                    total += Number(element.food.price);
                });
                return this.convertToMoney(total);
            },
            pay: function(tableId, index){
                //Se abre el modal con la información de pago
                axios.post('pay', {tableId:tableId}).then(response=>{
                    this.priceTotal = response.data;
                    this.tableNow = tableId;
                    this.indice = index;
                    $('#modalPay').modal({backdrop: 'static', keyboard: false})
                }).catch(errors=>{
                    toastr.warning('Ha ocurrido un error al mostrar la cantidad a pagar, actualiza por favor');
                });
            },
            cancelPay: function(){
                this.payShowResult = false;
                this.priceBack = null;
                this.priceWithPay = null;
                this.confirmPay = false;
                $('#modalPay').modal('hide');
            },
            payNow: function(){
                this.tables[this.indice].state = true;
                this.tables[this.indice].foodtabletemps = [];
                axios.post('sales/paynow', {tableId:this.tableNow}).then(response=>{
                   $('#modalPay').modal('hide');
                   toastr.success('Cuenta pagada con éxito');
                   this.payShowResult = false;
                   this.priceBack = null;
                   this.priceWithPay = null;
                   this.confirmPay = false;
                   this.getTables();
                });
            },
            calculateTotal: function(){
                if(this.priceWithPay < this.priceTotal){
                    toastr.warning('El dinero no es suficiente para pagar la cuenta');
                }else{
                    this.priceBack = this.priceWithPay - this.priceTotal;
                    this.payShowResult = true;
                    this.confirmPay = true;
                }
            },
            convertToMoney(price){
                return FormatNum(price);
            }
        },
        filters: {
            convertWord:function(state){
                if(state){
                    return 'Libre';
                }
                return 'Ocupado';
            }
        }
    }
</script>
<style>
       #div1 {
            overflow-y:scroll;
            height:100px;
        }
        #div2 {
            overflow-y: scroll;
            height: 270px;
        }
        #div3 {
            overflow-y: scroll;
            height: 323px;
        }
        #modal-body {
            min-height:360px; 
            overflow-y: auto;
        }
</style>

