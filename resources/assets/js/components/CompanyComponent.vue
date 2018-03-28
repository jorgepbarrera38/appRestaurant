<template>
  <div class="container">
      <div class="row">
          <div class="col-md-8">
              <div class="card">
                  <div class="card-header">
                      Datos
                  </div>
                  <div class="card-body">
                      <div class="form-group">
                          <strong>Razón social</strong>
                          <p>{{ company.name }}</p>

                      </div>
                      <div class="form-group">
                          <strong>Eslogan</strong>
                          <p>{{ company.slogan }}</p>
                      </div>
                      <div class="form-group">
                          <strong>Dirección</strong>
                          <p>{{ company.address }}</p>
                      </div>
                      <div class="form-group">
                          <strong>Teléfono</strong>
                          <p>{{ company.phonenumber }}</p>
                      </div>
                  </div>
              </div>
          </div>
          <div class="col-md-4">
              <TableComponent></TableComponent>
          </div>
      </div>
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
                company:[]
            }
        },
        methods:{
            getCompany: function(){
                axios.get('company').then(response=>{
                    this.company = response.data;
                });
            }            
        }
    }
</script>

