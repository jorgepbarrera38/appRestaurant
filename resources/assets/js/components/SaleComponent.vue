<template>
    <div class="container">
        <div class="row">
            <div class="col-md-4" v-for="table in tables">
                <div class="card">
                    <div class="card-header">
                        {{ table.name }} <span class="badge badge-success float-right">{{ table.state == 'active' ? 'Libre' : 'Ocupado' }}</span>
                    </div>
                    <div class="card-body">
                            <button class="btn btn-default btn-sm" v-if="table.state=='active'">Asignar mesa</button>
                            <button type="submit" class="btn btn-success btn-sm" disabled='true'>Pagar</button>
                            <button type="submit" class="btn btn-info btn-sm" v-if="table.state=='inactive'">Agregar plato</button>
                    </div>
                </div><br>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        mounted(){
            this.getTables();
        },
        data(){
            return{
                tables:null,
            }
        },
        methods: {
            getTables: function(){
                axios.get('tables').then(response=>{
                    this.tables = response.data;
                });
            }
        }
    }
</script>
