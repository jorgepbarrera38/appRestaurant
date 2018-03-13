<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        Listado de comidas <a href="" class="btn btn-primary btn-sm float-right" v-on:click.prevent="newFood()">Nuevo</a>
                    </div>
                    <div class="card-body">
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
                                    <td>{{ food.description.substring(0, 60) }}</td>
                                    <td>${{ food.price }}</td>
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
        <!--MODAL-->
        <!-- Modal -->
        <div class="modal fade" id="newFoodModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ editFoodModal ? 'Editar comida' : 'Nueva comida' }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger" v-if="errors">
                    <li v-for="error in errors">{{ error[0] }}</li>
                </div>
                <div class="form-group">
                    <label for="">Nombre</label>
                    <input type="text" class="form-control" placeholder="Nombre de la comida" v-model="food.name">
                </div>
                <div class="form-group">
                    <label for="">Descripción</label>
                    <textarea rows="2" v-model="food.description" placeholder="Descripción de la comida" class="form-control"></textarea>
                </div>
                <div class="form-group">
                    <label for="">Price</label>
                    <input type="number" class="form-control" v-model="food.price">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" v-on:click="cancelNewFood()">Cancelar</button>
                <button type="button" class="btn btn-primary" v-on:click="editFoodModal ? updateFood(food.id) : saveFood()">Guardar</button>
            </div>
            </div>
        </div>
        </div>
    </div>
</template>
<script>
    import toastr from 'toastr';
    toastr.options.timeOut = 2000; 
    export default {
        mounted(){
            this.getFoods();
        },
        data(){
            return{
                foods: null,
                food:{
                    id:'',
                    name:'',
                    description:'',
                    price:''
                },
                errors:null,
                editFoodModal:false,
            }
        },
        methods:{
            getFoods: function(){
                axios.get('foods').then(response=>{
                    this.foods = response.data;
                });
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
                    toastr.success("Nueva comida almacenada");
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
            }
        }
    }
</script>
