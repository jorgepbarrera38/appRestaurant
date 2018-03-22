<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        Productos
                        <div class="float-right">
                            Página {{ currentPage }} de {{ lastPage }}
                            <button class="btn btn-primary btn-sm" v-bind:class="buttonBackIsActive()" v-on:click="getFoods(currentPage-1)">Atrás</button>
                            <button class="btn btn-primary btn-sm" v-bind:class="buttonUpIsActive()" v-on:click="getFoods(currentPage+1)">Adelante</button>

                            <a href="" class="btn btn-success btn-sm" v-on:click.prevent="newFood()">Nuevo</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form class="form-inline" v-on:submit.prevent="addFind()">
                             <div class="form-row align-items-center">
                                <div class="col-auto">
                                    <input type="text" class="form-control mb-2" id="inlineFormInput" placeholder="Buscar coincidencias" v-model="findTemp">
                                </div>
                             </div>
                             <div class="col-auto">
                                <button type="submit" class="btn btn-primary mb-2">Buscar</button>
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
                                    <td>{{ food.description.substring(0, 90) }}</td>
                                    <td>${{ convertMoney(food.price) }}</td>
                                    <td>
                                        <button class="btn btn-info btn-sm">Ver</button>
                                        <button class="btn btn-warning btn-sm" v-on:click="editFood(food.id)">Editar</button>
                                        <button class="btn btn-danger btn-sm" v-on:click="deleteFood(food.id)">Eliminar</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal create Food -->
        <div class="modal fade" id="newFoodModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ editFoodModal ? 'Editar comida' : 'Nuevo' }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger" v-if="errors">
                        <li v-for="error in errors">{{ error[0] }}</li>
                    </div>
                    <form v-on:submit.prevent="editFoodModal ? updateFood(food.id) : saveFood()">
                        <div class="form-group">
                            <label for="">Nombre</label>
                            <input type="text" class="form-control" v-model="food.name" maxlength="50">
                        </div>
                        <div class="form-group">
                            <label for="">Descripción</label>
                            <textarea rows="2" v-model="food.description"  class="form-control" maxlength="150"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="">Precio</label>
                            <input type="number" class="form-control" v-model="food.price">
                        </div>
                        <button type="submit" style="display:none"></button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" v-on:click="cancelNewFood()">Cancelar</button>
                    <button type="button" class="btn btn-primary" v-on:click="editFoodModal ? updateFood(food.id) : saveFood()">Guardar</button>
                </div>
                </div>
            </div>
        </div>
        <!-- End Modal create Food -->
    </div>
</template>
<script>
    import toastr from 'toastr';
    import formatNum from 'format-num';
    export default {
        created(){
            this.getFoods();
        },
        data(){
            return{
                foods: [],
                food:{
                    id:'',
                    name:'',
                    description:'',
                    price:''
                },
                errors:null,
                editFoodModal:false,
                currentPage : 1,
                lastPage:1,
                findTemp:'',
                find:''
            }
        },
        methods:{
            getFoods: function(nextPage=1){
                var page = nextPage;
                if(page<=this.lastPage){
                    axios.get('foods?page='+page+'&name='+this.find).then(response=>{
                    this.foods = response.data.data;
                    this.currentPage = response.data.current_page;
                    this.lastPage = response.data.last_page;
                });
                }
            },
            newFood: function(){
                if(this.editFoodModal){
                    this.cleanFields();
                }
                this.editFoodModal = false;
                $('#newFoodModal').modal('show');
            },
            cancelNewFood: function(){
                $('#newFoodModal').modal('hide');
                this.cleanFields();
            },
            saveFood: function(){
                axios.post('foods', this.food).then(response=>{
                    $('#newFoodModal').modal('hide');
                    toastr.success("Nuevo producto almacenado");
                    this.cleanFields();
                    this.getFoods();
                }).catch(errors=>{
                    this.errors = errors.response.data.errors;
                });      
            },
            editFood: function(foodId){
                axios.get('foods/'+foodId).then(response=>{
                    this.cleanFields();
                    this.editFoodModal = true;
                    $('#newFoodModal').modal('show');
                    this.food.id = response.data.id;
                    this.food.name = response.data.name;
                    this.food.description = response.data.description;
                    this.food.price = response.data.price;
                });
            },
            updateFood: function(foodId){
                axios.put('foods/'+foodId, this.food).then(response=>{
                    $('#newFoodModal').modal('hide');
                    this.getFoods();
                    toastr.success('Comida actualizada');
                    console.log(response.data);
                }).catch(errors=>{
                    this.errors = errors.response.data.errors;
                });
            },
            deleteFood: function(foodId){
                axios.delete('foods/'+foodId).then(response=>{
                    toastr.success('Comida eliminada');
                    this.getFoods();
                }).catch(errors=>{
                    toastr.danger('No se pudo eliminar la comida');
                });
            },
            cleanFields: function(){
                this.errors = null;
                this.food.name = '';
                this.food.description = '';
                this.food.price = '';
            },
            convertMoney: function(price){
                return formatNum(price);
            },
            buttonBackIsActive: function(){
                return this.currentPage > 1 ? '' : 'disabled';
            },
            buttonUpIsActive: function(){
                return this.currentPage === this.lastPage ? 'disabled' : '';
            },
            addFind: function(){
                this.find = this.findTemp;
                this.getFoods();
            }
        }
    }
</script>
