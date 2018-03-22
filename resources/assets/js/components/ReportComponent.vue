<template>
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">
                        Opciónes de búsqueda
                    </div>
                    <div class="card-body">
                        <form v-on:submit.prevent="findResults()">
                            <div class="form-group">
                                <label for="">De: </label>
                                <input type="date" name="bdaytime" v-model="dateFrom" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">A: </label>
                                <input type="date" name="bdaytime" v-model="dateTo" class="form-control">
                            </div>
                            <button class="btn btn-primary btn-block" type="submit">Buscar</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <div class="card">
                    <div class="card-header">
                        
                        <div class="float-right">
                            Total de venta: <strong>${{ convertToMoney(calculateTotal) }}</strong>
                        </div>
                        </div>
                    <div class="card-body">
                        <div class="alert alert-success" v-if="showChargin">
                            Cargando...
                        </div>
                        <table class="table table-hover table-sm" v-if="sales.length>0">
                            <thead>
                                <th>Mesa</th>
                                <th>Venta</th>
                                <th>Fecha</th>
                            </thead>
                            <tbody>
                                <tr v-for="sale in sales" v-on:click="showDetailsSale(sale.id)">
                                    <td>{{ sale.table.name }}</td>
                                    <td>${{ convertToMoney(sale.pricetotal) }}</td>
                                    <td>{{ convertDate(sale.created_at) }}</td>
                                </tr>
                            </tbody>
                        </table>
                        <!--Modal DetailsSale-->
                        <div class="modal fade" id="modalSaleDetail" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Detalles de la venta</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <table class="table table-hover table-sm">
                                    <thead>
                                        <th>Comida</th>
                                        <th>Precio</th>
                                    </thead>
                                    <tbody>
                                        <tr v-for="detail in saleDetails">
                                            <td>{{ detail.food.name }}</td>
                                            <td>${{ convertToMoney(detail.foodprice) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary">Save changes</button>
                            </div>
                            </div>
                        </div>
                        </div>
                        <!--Modal DetailsSale-->
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        Los más vendidos
                    </div>
                    <div class="card-body">
                        <div class="alert alert-success" v-if="showChargin">
                            Cargando...
                        </div>
                        <table class="table table-hover table-sm" v-if="sales.length>0">
                            <thead>
                                <th>Producto</th>
                                <th>Cantidad</th>
                            </thead>
                            <tbody>
                                <tr v-for="food in foodsMostSolds">
                                    <td>{{ food.name }}</td>
                                    <td>{{ food.cant }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import toastr from 'toastr';
    import FormatNum from 'format-num';
    import moment from 'moment';
    moment.locale('es');
    export default {
        data(){
            return {
                dateFrom :'',
                dateTo :'',
                sales:[],
                saleDetails:[],
                foodsMostSolds:[],
                showChargin:false,
            }
        },
        methods: {
            findResults: function(){
                this.showChargin=true;
                this.sales = [];
                axios.get('reports?datefrom='+this.dateFrom+'&dateto='+this.dateTo).then(response=>{
                   this.sales = response.data.sales;
                   this.foodsMostSolds = response.data.foodsMostSold;
                   this.showChargin=false;
                }).catch(errors=>{
                    this.showChargin=false;
                    toastr.error('Hay errores en los parámetros de búsqueda');
                });
            },
            convertToMoney: function(value){
                return FormatNum(value);
            },
            showDetailsSale: function(saleId){
                axios.get('reports/'+saleId).then(response=>{
                    this.saleDetails = response.data;
                    $('#modalSaleDetail').modal('show');
                }).catch(errors=>{
                    toastr.warning('Al ocurrido un error');
                });
            },
            convertDate: function(value){
                return moment(value).format('MMMM Do YYYY, h:mm:ss a');
            }
        },
        computed:{
            calculateTotal: function(){
                var total = 0;
                if(this.sales.length>0){
                    this.sales.forEach(element => {
                        total += Number(element.pricetotal);
                    });
                }
                return total;
            }
        }
    }
</script>
