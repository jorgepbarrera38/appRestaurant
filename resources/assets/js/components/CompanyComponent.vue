<template>
  <div class="container">
      <div class="row">
          <div class="col-md-8">
              <div class="card">
                  <div class="card-header">
                      Datos
                  </div>
                  <div class="card-body">
                      <div class="for-group">
                          <label for="">Razón social</label>
                          <input type="text" class="form-control">
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
    export default {
        components:{
            TableComponent
        }
    }
</script>

