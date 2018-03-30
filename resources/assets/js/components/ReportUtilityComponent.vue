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
                                <form v-on:submit.prevent="find()">
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
                        <div class="row">
                            <div class="col-md-12" v-if="charginResults">
                                <div class="alert alert-success">
                                    Cargando...
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <!--Listado de ventas-->
                            <div class="col-lg-6 col-md-12">
                                <div class="alert alert-danger" v-if="showBlanckSales">
                                    No hay ventas registradas.
                                </div>
                                    <div v-if="sales.length>0">
                                    <div class="card" style="height: 28rem;">
                                        <div class="card-header">
                                            Ventas
                                            <div class="float-right">
                                                Total: <strong>${{ convertToMoney(saleTotal) }}</strong>
                                            </div>
                                            </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-hover table-sm">
                                                    <thead>
                                                        <th>Fecha</th>
                                                        <th>Mesa</th>
                                                        <th>Venta</th>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-for="sale in sales" v-on:click="showDetailsSale(sale.id)">
                                                            <td nowrap>{{ convertDate(sale.created_at) }}</td>
                                                            <td nowrap>{{ sale.table.name }}</td>
                                                            <td nowrap>${{ convertToMoney(sale.pricetotal) }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
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
                                                    <div class="table-responsive">
                                                        <table class="table table-hover table-sm">
                                                            <thead>
                                                                <th>Comida</th>
                                                                <th>Precio</th>
                                                            </thead>
                                                            <tbody>
                                                                <tr v-for="detail in saleDetails">
                                                                    <td nowrap>{{ detail.food.name }}</td>
                                                                    <td nowrap>${{ convertToMoney(detail.foodprice) }}</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                </div>
                                                </div>
                                            </div>
                                            </div>
                                            <!--Modal DetailsSale-->
                                        </div>
                                        <div class="card-footer">
                                            <div class="float-right">
                                                Página {{ pagination.currentPage }} de {{ pagination.lastPage }}
                                                <button class="btn btn-primary btn-sm" v-if="pagination.currentPage>1" v-on:click="pagination.currentPage -=1;findSales()">Atrás</button>
                                                <button class="btn btn-primary btn-sm disabled" v-else>Atrás</button>
                                                <button class="btn btn-primary btn-sm" v-if="pagination.currentPage<pagination.lastPage" v-on:click="pagination.currentPage +=1;findSales()">Adelante</button>
                                                <button class="btn btn-primary btn-sm disabled" v-else>Adelante</button>
                                            </div>
                                        </div>
                                </div>
                                <br>
                                </div>
                            </div>
                            <!--Listado de gastos-->
                            <div class="col-lg-6 col-md-12" >
                                <div class="alert alert-danger" v-if="showBlanckExpends">
                                    No hay gastos registrados.
                                </div>
                                <div class="card" v-if="expends.length>0"  style="height: 28rem;">
                                    <div class="card-header">
                                        Gastos
                                        <div class="float-right">
                                            Total: <strong>${{ convertToMoney(expendTotal) }}</strong>
                                        </div>
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
                                                        <td nowrap>{{ convertDateForExpends(expend.date) }}</td>
                                                        <td nowrap>{{ expend.detail }}</td>
                                                        <td>${{ convertToMoney(expend.val) }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <div class="float-right">
                                            Página {{ paginationExpends.currentPage }} de {{ paginationExpends.lastPage }}
                                            <button class="btn btn-primary btn-sm" v-if="paginationExpends.currentPage>1" v-on:click="paginationExpends.currentPage -=1;findExpends()">Atrás</button>
                                            <button class="btn btn-primary btn-sm disabled" v-else>Atrás</button>
                                            <button class="btn btn-primary btn-sm" v-if="paginationExpends.currentPage<paginationExpends.lastPage" v-on:click="paginationExpends.currentPage +=1;findExpends()">Adelante</button>
                                            <button class="btn btn-primary btn-sm disabled" v-else>Adelante</button>
                                        </div>
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
                showBlanckExpends:false,
                showBlanckSales:false,
                charginResults:false,
                saleTotal:0,
                expendTotal:0,
                pagination:{
                    currentPage:'',
                    lastPage:''
                },
                paginationExpends:{
                    currentPage:'',
                    lastPage:''
                }
            }
        },
        methods: {
            find: function(){
                this.charginResults=true;
                this.sales=[];
                this.expends=[];
                this.showBlanckExpends=false;
                this.showBlanckSales=false;
                this.pagination.currentPage='';
                this.pagination.lastPage='';
                if(this.dateFrom && this.dateTo){
                    if(this.dateFrom > this.dateTo){
                        toastr.error("Las fechas no tienen un formato correcto");
                        this.charginResults=false;
                    }else{
                        this.findSales();
                        this.findExpends();
                    }
                }else{
                    toastr.error("Debe especificar un rango de fechas");
                    this.charginResults=false;
                }
            },
            findSales: function(){  
                axios.get('reports/sales?page='+ this.pagination.currentPage +'&datefrom='+this.dateFrom+'&dateto='+this.dateTo).then(response=>{
                    this.sales = response.data.sales.data;
                    this.saleTotal = response.data.total;
                    this.pagination.currentPage = response.data.sales.current_page;
                    this.pagination.lastPage = response.data.sales.last_page;
                    if(this.sales.length<1){
                        this.showBlanckSales=true;
                    }else{
                        this.showBlanckSales=false;
                    }
                    this.charginResults=false;
                });
            },
            findExpends: function(){
                axios.get('reports/expends?page='+ this.paginationExpends.currentPage +'&datefrom='+this.dateFrom+'&dateto='+this.dateTo).then(response=>{
                    console.log(response.data);
                    this.expends = response.data.expends.data;
                    this.expendTotal = response.data.total;
                    this.paginationExpends.currentPage = response.data.expends.current_page;
                    this.paginationExpends.lastPage = response.data.expends.last_page;
                    if(this.expends.length<1){
                        this.showBlanckExpends=true;
                    }else{
                        this.showBlanckExpends=false;
                    }
                });
            },  
            findResults: function(){
                this.showChargin=true;
                this.sales = [];
                this.expends = [];
                axios.get('reports?datefrom='+this.dateFrom+'&dateto='+this.dateTo).then(response=>{
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
            },
            convertDateForExpends: function(value){
                return moment(value).format('MMMM Do YYYY');
            }
        }
    }
</script>
