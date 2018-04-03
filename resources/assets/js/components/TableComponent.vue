<template>
  <div>
              <div class="card">
                  <div class="card-header">Mesas
                      <div class="float-right">
                          <button class="btn btn-primary btn-sm" v-bind:class="pagination.currentPage == 1 ? 'disabled':''"  v-on:click="minPagination()">&lt</button>
                          <button class="btn btn-primary btn-sm" v-bind:class="pagination.currentPage == pagination.lastPage ? 'disabled':''" v-on:click="uploadPagination()">&gt</button>
                          <button class="btn btn-success btn-sm" v-on:click="showModalCreateTable()">Agregar mesa</button>
                      </div>
                  </div>
                  <div class="card-body">
                            <ul class="list-group">
                                <a class="list-group-item list-group-item-action" v-for="table in tables">
                                    {{ table.name }}
                                    <div class="float-right">
                                        <button class="btn btn-warning btn-sm" v-on:click="desactivateTable(table.id)" v-if="table.active">Inhabilitar</button>
                                        <button class="btn btn-primary btn-sm" v-else v-on:click="desactivateTable(table.id)">Habilitar</button>
                                        <button class="btn btn-danger btn-sm" v-on:click="deleteTable(table.id)">Eliminar</button>
                                    </div>
                                </a>
                            </ul>
                  </div>
              </div>
          <!--Modal create Tables-->
            <div class="modal fade" id="ModalCreateTable" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">Crear mesa</h5>
                        </div>
                        <div class="modal-body">
                            <form v-on:submit.prevent="addTable()"> 
                                <div class="alert alert-danger" v-if="errors">
                                    <li v-for="error in errors">{{ error[0] }}</li>
                                </div>
                                <div class="form-group">
                                    <label for="">Nombre de la mesa</label>
                                    <input type="text" class="form-control" v-model="table" id="nameTable">
                                </div>
                            </form>  
                        </div>
                        <div class="modal-footer">
                            <button v-on:click="addTable()" class="btn btn-primary">Guardar</button>
                            <button v-on:click="hideModalCreateTable()" class="btn btn-danger">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
          <!--Fin Modal create Tables-->
  </div>
</template>
<script>
    import toastr from 'toastr';
    toastr.options.timeOut = 2000; 
    export default {
        mounted(){
            this.getTables();
        },
        data(){
            return {
                tables:null,
                addTableShowForm: false,
                table:'',
                errors: null,
                pagination:{
                    currentPage:1,
                    lastPage:1
                },
            }
        },
        methods:{
            uploadPagination: function(){
                if(this.pagination.currentPage < this.pagination.lastPage){
                    this.pagination.currentPage+=1; 
                    this.getTables();
                }
            },
            minPagination: function(){
                if(this.pagination.currentPage>1){
                    this.pagination.currentPage-=1;
                    this.getTables();
                }
            },
            getTables: function(){
                axios.get('tables?page='+this.pagination.currentPage).then(response=>{
                    this.tables = response.data.data;
                    this.pagination.currentPage = response.data.current_page;
                    this.pagination.lastPage = response.data.last_page;
                });
            },
            addTable: function(){
                axios.post('tables', { name: this.table }).then(response=>{
                    this.getTables();
                    this.cleanFieldsAndErrors();
                    toastr.success('Mesa agregada');
                }).catch(errors=>{
                    this.errors = errors.response.data.errors;
                });
            },
            showModalCreateTable: function(){
                $('#ModalCreateTable').modal('show');
            },
            hideModalCreateTable: function(){
                $('#ModalCreateTable').modal('hide');
                this.cleanFieldsAndErrors();
            },
            cleanFieldsAndErrors: function(){
                this.table = '';
                this.errors = null;
            },
            
            deleteTable: function(tableId){
                axios.delete('tables/'+tableId+'/delete').then(response=>{
                    this.getTables();
                    toastr.success('Mesa eliminada');
                }).catch(errors=>{
                    toastr.error('Ocurrió un error al intentar eliminar la mesa, si la mesa ya tiene ventas no la puede eliminar');
                });
            },
            desactivateTable: function(tableId){
                axios.delete('tables/'+tableId).then(response=>{
                    this.getTables();
                });
            }
        }
    }
</script>

