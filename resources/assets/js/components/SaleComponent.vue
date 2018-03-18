<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <button class="btn btn-success btn-sm" v-on:click="getTables(); showSuccess()">Actualizar</button>
            </div><br><br>
            <!--Listado de mesas-->
            <div class="col-md-4" v-for="(table, index) in tables">
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
                                <tr v-for="foodTemp in table.foodtabletemps">
                                    <td>{{ foodTemp.food.name }}</td>
                                    <td>${{ foodTemp.food.price }}</td>
                                </tr>
                            </table>
                            <!--Fin Listado de comidas para la mesa actual-->
                        </div>
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-default btn-sm" v-if="table.state" v-on:click="assignTable(table.id, index)">Asignar mesa</button>
                        <button type="button" class="btn btn-info btn-sm" v-if="!table.state" v-on:click="showModalFoods(table.id)">Agregar plato</button>
                        <button type="button" class="btn btn-success btn-sm" v-if="table.foodtabletemps.length>0" v-on:click="pay(table.id)">Pagar</button>
                        <!--<button class="btn btn-danger btn-sm" v-if="!table.state">Cancelar pedido</button>-->
                        <div class="float-right" v-if="table.foodtabletemps.length>0">
                            Total: ${{ calculateTotalTable(index) }}
                        </div>
                    </div>
                </div><br>
            </div>
            <!--Fin Listado de mesas-->
            <!--Modal show foods-->
            <div class="modal fade" id="modalFoods" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Agregar comida</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Buscar" v-model="find" v-on:keyup="getFoods()">
                            </div>
                        </form>
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
                                        <td>{{ food.price }}</td>
                                        <td><button class="btn btn-success btn-sm" v-on:click="addFoodTable(food.id)">Añadir</button></td>
                                    </tr>
                                </tbody>
                            </table>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
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
                            <h3 v-text="'$'+priceTotal"></h3>
                        </div>
                        <div class="form-group">
                            <label for="">Cantidad con que pagan</label>
                            <form v-if="!payShowResult" v-on:submit.prevent="calculateTotal()">
                                    <input type="number" class="form-control" placeholder="" v-model="priceWithPay">
                            </form>
                            <h3 v-if="payShowResult" v-text="'$'+priceWithPay"></h3>
                        </div>
                        <div class="form-group" v-if="payShowResult">
                            <label for="">Devolver</label>
                            <h3 v-text="'$'+priceBack"></h3>
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
                <div class="modal-footer" v-if="confirmPay">
                    <button type="button" class="btn btn-danger" v-on:click="cancelPay()">Cancelar</button>
                    <button type="button" class="btn btn-primary">Confirmar pago</button>
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
    export default {
        mounted(){
            this.getTables();
            this.getFoods();
        },
        data(){
            return{
                tables:null,
                foods:null,

                tableNow:null,
                priceTotal:null,

                find:'',
                payShowResult:false,
                priceWithPay: null,
                priceBack:null,

                confirmPay:false
            }
        },
        methods: {
            getTables: function(){
                axios.get('tables').then(response=>{
                    this.tables = response.data;
                });
            },
            getFoods: function(){
                axios.get('sales/getfoods?name='+this.find).then(response=>{
                    this.foods = response.data.data;
                });
            },
            assignTable: function(tableId, index){
                axios.put('sales/'+tableId, {name:'Alexis'}).then(response=>{
                    this.tables[index].state = !this.tables[index].state;
                });
            },
            showModalFoods: function(tableId){
                this.tableNow = tableId;
                $('#modalFoods').modal('show');
            },
            addFoodTable: function(foodId){
                var data = {table:this.tableNow, food:foodId}
                toastr.success('Comida añadida');
                axios.post('sales', data).then(response=>{
                    this.getTables();
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
                return total;
            },
            pay: function(tableId){
                axios.post('pay', {tableId:tableId}).then(response=>{
                    this.priceTotal = response.data;
                    $('#modalPay').modal({backdrop: 'static', keyboard: false})
                });
            },
            cancelPay: function(){
                this.payShowResult = false;
                this.priceBack = null;
                this.priceWithPay = null;
                this.confirmPay = false;
                $('#modalPay').modal('hide');
            },
            calculateTotal: function(){
                if(this.priceWithPay < this.priceTotal){
                    toastr.warning('El dinero no es suficiente para pagar la cuenta');
                }else{
                    this.priceBack = this.priceWithPay - this.priceTotal;
                    this.payShowResult = true;
                    this.confirmPay = true;
                }
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
            height: 300px;
        }
</style>

