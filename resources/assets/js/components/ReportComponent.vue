<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        Reportes de utilidad
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <form class="form-inline" v-on:submit.prevent="findResults()">
                                    <div class="form-row align-items-center">
                                        <div class="col-auto">
                                            <input type="date" name="bdaytime" v-model="dateFrom" class="form-control mb-2" id="inlineFormInput">
                                        </div>
                                        <div class="col-auto">
                                            <input type="date" name="bdaytime" v-model="dateTo" class="form-control mb-2" id="inlineFormInput">
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <button class="btn btn-primary mb-2" type="submit">Buscar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12" v-if="showChargin">
                                <div class="alert alert-success">
                                    Cargando...
                                </div>
                            </div>
                            <!--Listado de ventas-->
                            <div class="col-md-12" v-if="sales.length>0">
                                    <div class="card">
                                        <div class="card-header">
                                            Ventas
                                            <div class="float-right">
                                                Total: <strong>${{ convertToMoney(calculateTotalSales) }}</strong>
                                            </div>
                                            </div>
                                        <div class="card-body">
                                            <table class="table table-hover table-sm">
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
                                <br>
                            </div>
                            <!--Listado de gastos-->
                            <div class="col-md-12" v-if="expends.length>0">
                                <div class="card">
                                    <div class="card-header">
                                        Gastos
                                        <div class="float-right">
                                            Total: <strong>${{ convertToMoney(calculateTotalExpends) }}</strong>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-hover table-sm" v-if="expends.length>1">
                                            <thead>
                                                <th>Fecha</th>
                                                <th>Detalle</th>
                                                <th>Valor</th>
                                            </thead>
                                            <tbody>
                                                <tr v-for="expend in expends">
                                                    <td>{{ convertDate(expend.date) }}</td>
                                                    <td>{{ expend.detail }}</td>
                                                    <td>{{ expend.val }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
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
                expends:[],
                showChargin:false,
            }
        },
        methods: {
            findResults: function(){
                this.showChargin=true;
                this.sales = [];
                this.expends = [];
                axios.get('reports?datefrom='+this.dateFrom+'&dateto='+this.dateTo).then(response=>{
                    console.log(this.dateFrom);
                   this.sales = response.data.sales;
                   this.expends = response.data.expends;
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
            calculateTotalSales: function(){
                var total = 0;
                if(this.sales.length>0){
                    this.sales.forEach(element => {
                        total += Number(element.pricetotal);
                    });
                }
                return total;
            },
            calculateTotalExpends: function(){
                var total = 0;
                if(this.expends.length>0){
                    this.expends.forEach(element=>{
                        total+=Number(element.val);
                    }); 
                }
                return total;
            }
        }
    }
</script>
