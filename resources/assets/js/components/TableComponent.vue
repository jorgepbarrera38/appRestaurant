<template>
  <div class="container">
      <div class="row">
          <div class="col-md-6">
              <div class="card">
                  <div class="card-header">Mesas 
                      <div class="float-right">
                          <button class="btn btn-primary btn-sm" v-on:click="addTableShowForm=true">Agregar mesa</button>
                      </div>
                  </div>
                  <div class="card-body">
                            <table class="table table-hover table-sm">
                                <thead>
                                    <th>Mesas</th>
                                </thead>
                                <tbody>
                                    <tr v-for="table in tables">
                                        <td>{{ table.name }}</td>
                                    </tr>
                                </tbody>
                            </table>
                  </div>
              </div>
          </div>
          <div class="col-md-6" v-if="addTableShowForm">
              <div class="card">
                  <div class="card-header">Nueva mesa</div>
                  <div class="card-body">
                        <form v-on:submit.prevent="addTable()"> 
                            <div class="alert alert-danger" v-if="errors">
                                <li v-for="error in errors">{{ error[0] }}</li>
                            </div>
                            <div class="form-group">
                                <label for="">Nombre de la mesa</label>
                                <input type="text" class="form-control" v-model="table">
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm">Guardar</button>
                            <a href="" class="btn btn-danger btn-sm" v-on:click.prevent="hideFormNewTable()">Cerrar</a>
                        </form>         
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
            this.getTables();
        },
        data(){
            return {
                tables:null,
                addTableShowForm: false,
                table:'',
                errors: null,
            }
        },
        methods:{
            getTables: function(){
                axios.get('tables').then(response=>{
                    this.tables = response.data;
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
            hideFormNewTable: function(){
                this.addTableShowForm=false;
                this.cleanFieldsAndErrors();
            },
            cleanFieldsAndErrors: function(){
                this.table = '';
                this.errors = null;
            }
        }
    }
</script>

