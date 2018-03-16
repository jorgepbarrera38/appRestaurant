<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <button class="btn btn-success btn-sm" v-on:click="getTables(); showSuccess()">Actualizar</button>
            </div><br><br>
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
                            <table class="table table-sm" style="font-size:12px">
                                <tr v-for="foodTemp in table.foodtabletemps">
                                    <td>{{ foodTemp.food.name }}</td>
                                    <td>{{ foodTemp.food.price }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-default btn-sm" v-if="table.state" v-on:click="assignTable(table.id, index)">Asignar mesa</button>
                        <button type="submit" class="btn btn-info btn-sm" v-if="!table.state" v-on:click="showModalFoods(table.id)">Agregar plato</button>
                        <button type="submit" class="btn btn-success btn-sm" v-if="table.foodtabletemps.length>0">Pagar</button>
                        <div class="float-right" v-if="table.foodtabletemps.length>0">
                            Total: ${{ calculateTotalTable(index) }}
                        </div>
                    </div>
                </div><br>
            </div>
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
                                <input type="text" class="form-control" placeholder="Buscar">
                            </div>
                        </form>
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
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    </div>
                    </div>
                </div>
            </div>
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
            }
        },
        methods: {
            getTables: function(){
                axios.get('tables').then(response=>{
                    this.tables = response.data;
                });
            },
            getFoods: function(){
                axios.get('foods').then(response=>{
                    this.foods = response.data;
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
       .modal-body {
    min-height:440px; 
    overflow-y: auto;
       }
       #div1 {
    overflow-y:scroll;
    height:100px;
}
</style>

