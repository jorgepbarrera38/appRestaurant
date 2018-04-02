<template>
  <div class="container">
      <div class="row">
          <div class="col-md-8">
              <div class="card">
                  <div class="card-header">
                      Datos
                      <button v-if="!company" class="btn btn-success btn-sm float-right" v-on:click="showModalRegisterCompany()">Registrar datos</button>
                      <button v-else="company" class="btn btn-success btn-sm float-right" v-on:click="showModalRegisterCompany()">Editar datos</button>
                  </div>
                  <div class="card-body">
                      <ul class="list-group">
                        <li class="list-group-item"><strong>Empresa:</strong> {{ company.name }}
                        </li>
                        <li class="list-group-item"><strong>Eslogan:</strong> {{ company.slogan }}
                        </li>
                        <li class="list-group-item"><strong>Dirección:</strong> {{ company.address }}
                        </li>
                        <li class="list-group-item"><strong>Teléfono:</strong> {{ company.phonenumber }}
                        </li>
                      </ul>
                  </div>
              </div>
          </div>
          <div class="col-md-4">
              <TableComponent></TableComponent>
          </div>
      </div>
      <!-- Modal register company -->
        <div class="modal fade" id="modalRegisterCompany" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Registrar datos</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger" v-if="errors">
                        <li v-for="error in errors">{{ error[0] }}</li>
                    </div>
                    <form v-on:submit.prevent="">
                        <div class="form-group">
                            <label for="">Razón social</label>
                            <input type="text" class="form-control" v-model="newCompany.name">
                        </div>
                        <div class="form-group">
                            <label for="">Eslogan</label>
                            <textarea rows="2" v-model="newCompany.slogan"  class="form-control"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="">Dirección</label>
                            <input type="text" class="form-control" v-model="newCompany.address">
                        </div>
                        <div class="form-group">
                            <label for="">Teléfono(s)</label>
                            <input type="text" class="form-control" v-model="newCompany.phonenumber">
                        </div>
                        <button type="submit" style="display:none"></button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" v-on:click="cancelRegisterCompany()">Cancelar</button>
                    <button type="button" class="btn btn-primary" v-on:click="saveCompany()">Guardar</button>
                </div>
                </div>
            </div>
        </div>
        <!-- End Modal register company -->
  </div>
</template>
<script>
    import TableComponent from './TableComponent';
    import toastr from 'toastr';
    export default {
        components:{
            TableComponent
        },
        mounted(){
            this.getCompany();
        },
        data(){
            return {
                company:[],
                errors:null,
                newCompany:{
                    name:'',
                    slogan:'',
                    address:'',
                    phonenumber:'',
                }
            }
        },
        methods:{
            getCompany: function(){
                axios.get('company').then(response=>{
                    this.company = response.data;
                });
            },
            showModalRegisterCompany: function(){
                $('#modalRegisterCompany').modal('show');
            },
            cancelRegisterCompany: function(){
                $('#modalRegisterCompany').modal('hide');
                this.cleanFields();
                this.errors=null;
            },
            cleanFields: function(){
                this.newCompany.name='';
                this.newCompany.slogan='';
                this.newCompany.address='';
                this.newCompany.phonenumber='';
            },
            saveCompany: function(){
                axios.post('company', this.newCompany).then(response=>{
                    console.log(response.data);
                    this.getCompany();
                    this.cancelRegisterCompany();
                    toastr.success('Datos registrados correctamente');
                }).catch(errors=>{
                    this.errors = errors.response.data.errors;
                });
            }           
        }
    }
</script>

