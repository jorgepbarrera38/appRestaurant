<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        Listado de comidas <a href="" class="btn btn-primary btn-sm float-right" v-on:click.prevent="newFood()">Nuevo</a>
                    </div>
                    <div class="card-body">

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
                <h5 class="modal-title" id="exampleModalLabel">Nueva comida</h5>
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
                    <textarea rows="2" v-model="food.description" class="form-control"></textarea>
                </div>
                <div class="form-group">
                    <label for="">Price</label>
                    <input type="number" class="form-control" v-model="food.price">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" v-on:click="cancelNewFood()">Cancelar</button>
                <button type="button" class="btn btn-primary" v-on:click="saveFood()">Guardar</button>
            </div>
            </div>
        </div>
        </div>
    </div>
</template>
<script>
    import toastr from 'toastr';
    export default {
        data(){
            return{
                food:{
                    name:'',
                    description:'',
                    price:''
                },
                errors:null,
            }
        },
        methods:{
            newFood: function(){
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
                }).catch(errors=>{
                    this.errors = errors.response.data.errors;
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
