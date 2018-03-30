<template>
  <div class="container">
      <div class="row">
          <div class="col-md-12">
              <div class="card">
                    <div class="card-header">
                        Gastos
                        <button v-on:click="showModalNewExpend()" class="btn btn-success btn-sm float-right">Registrar nuevo gasto</button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-sm">
                                <thead>
                                    <th>Fecha</th>
                                    <th>Detalle</th>
                                    <th>Valor</th>
                                </thead>
                                <tbody>
                                    <tr v-for="expend in expends">
                                        <td nowrap>{{ convertDate(expend.date) }}</td>
                                        <td nowrap>{{ expend.detail }}</td>
                                        <td nowrap>${{ convertToMoney(expend.val) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- Modal create Expend -->
                        <div class="modal fade" id="modalNewExpend" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Registrar gasto</h5>
                                </div>
                                <div class="modal-body">
                                      <div class="row">
                                          <div class="col-md-12" v-if="errors">
                                              <div class="alert alert-danger">
                                                  <li v-for="error in errors">{{ error[0] }}</li>
                                              </div>
                                          </div>
                                          <div class="col-md-6">
                                              <div class="form-group">
                                                <label>Fecha</label>
                                                <input type="date" class="form-control" v-model="expend.date">
                                             </div>
                                          </div>
                                          <div class="col-md-6">
                                              <div class="form-group">
                                                <label>Valor</label>
                                                <input type="number" class="form-control" v-model="expend.val">
                                             </div>
                                          </div>
                                          <div class="col-md-12">
                                              <div class="form-group">
                                                <label>Detalle</label>
                                                <textarea class="form-control" rows="2" v-model="expend.detail"></textarea>
                                             </div>
                                          </div>
                                      </div>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-danger" v-on:click="cancelNewExpend()">Cancelar</button>
                                    <button class="btn btn-primary" v-on:click="saveExpend()">Guardar</button>
                                </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Modal create Expend -->
                    </div>
              </div>
          </div>
      </div>
  </div>
</template>
<script>
  import toastr from 'toastr';
  import formatNum from 'format-num';
  import moment from 'moment';
  export default {
      mounted: function(){
        this.getExpends();
      },
      data(){
        return {
            expends:[],
            expend:{
              date:null,
              val:null,
              detail:null
            },
            errors:null
        }
      },
      methods:{
        getExpends: function(){
          axios.get('expends').then(response=>{
            this.expends = response.data.data;
          }).catch(errors=>{
            toastr.error('Ha ocurrido un error al llamar los gastos');
          });
        }, 
        showModalNewExpend: function(){
          $('#modalNewExpend').modal('show');
        },
        saveExpend: function(){
          axios.post('expends', this.expend).then(response=>{
              this.cancelNewExpend();   
              this.getExpends();
              toastr.success('Gasto registrado');              
          }).catch(errors=>{
            this.errors = errors.response.data.errors;
          });
        },
        cleanFields: function(){
            this.expend.date=null;
            this.expend.val=null;
            this.expend.detail=null;
            this.errors = null;
        },
        cancelNewExpend: function(){
          $('#modalNewExpend').modal('hide');    
          this.cleanFields();
        },
        convertToMoney: function(value){
          return formatNum(value);
        },
        convertDate: function(date){
            return moment(date).format('MMMM Do YYYY');
        }
      }
  }
</script>
