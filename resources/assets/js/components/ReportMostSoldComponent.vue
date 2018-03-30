<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        Lo más vendido
                    </div>
                    <div class="card-body">
                        <form v-on:submit.prevent="findMostSold()">
                            <div class="row">
                                <div class="col-lg-3 col-md-4 col-sm-6">
                                    <input type="date" name="bdaytime" v-model="dateFrom" class="form-control mb-2" id="inlineFormInput">
                                </div>
                                <div class="col-lg-3 col-md-4 col-sm-6">
                                    <input type="date" name="bdaytime" v-model="dateTo" class="form-control mb-2" id="inlineFormInput">
                                </div>
                                <div class="col-md-3">
                                    <button class="btn btn-primary mb-2" type="submit">Buscar</button>
                                </div>
                            </div>
                        </form>
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
            return {
                dateFrom:'',
                dateTo:''
            }
        },
        methods:{
            findMostSold: function(){
                if(this.dateFrom && this.dateTo){
                    if(this.dateFrom>this.dateTo){
                        toastr.error('Hay error en el formato de las fechas');
                    }else{
                        axios.get('reports/foodmostsold?datefrom='+this.dateFrom+'&dateto='+this.dateTo).then(response=>{
                            console.log(response.data);
                        });
                    }
                }else{
                    toastr.error('Debe especificar un rango de fechas');
                }
            }
        }
    }
</script>
