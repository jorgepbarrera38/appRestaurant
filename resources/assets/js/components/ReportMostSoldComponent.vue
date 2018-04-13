<template>
    <div class="container">
        <div class="row" v-if="user == 'administrador'">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        Reporte de lo más vendido
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
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
                            <div class="col-md-12" v-show="showGraphic">
                                <center>
                                    <canvas id="myChart"></canvas>
                                </center>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
         <div class="alert alert-danger" v-else>
            No tienes permisos.
        </div>
    </div>
</template>
<script>
    import toastr from 'toastr';
    import chartJs from 'chart.js';
    export default {
        props:['user'],
        data(){
            return {
                dateFrom:'',
                dateTo:'',
                dataFoodsQuantity:'',
                showGraphic:false,
            }
        },
        methods:{
            findMostSold: function(){
                this.dataFoodsQuantity='';
                if(this.dateFrom && this.dateTo){
                    if(this.dateFrom>this.dateTo){
                        toastr.error('Hay error en el formato de las fechas');
                    }else{
                        axios.get('reports/foodmostsold?datefrom='+this.dateFrom+'&dateto='+this.dateTo).then(response=>{
                            this.dataFoodsQuantity = response.data;
                            if(this.dataFoodsQuantity.length<1){
                                toastr.error('No hay registro de ventas dentro del rango de fechas');
                                this.showGraphic = false;
                            }else{
                            this.showGraphic = true;
                            var namesFood = [];
                            var quantityFood = [];
                            this.dataFoodsQuantity.forEach(element => {
                                namesFood.push(element.food);
                                quantityFood.push(element.quantity);
                            });
                            //Aqui se construye el chart
                            var colors = [
                                            ['rgba(255, 99, 132, 0.2)','rgba(255,99,132,1)'],
                                            ['rgba(54, 162, 235, 0.2)','rgba(54, 162, 235, 1)'],
                                            ['rgba(255, 206, 86, 0.2)','rgba(255, 206, 86, 1)'],
                                            ['rgba(75, 192, 192, 0.2)','rgba(75, 192, 192, 1)'],
                                            ['rgba(153, 102, 255, 0.2)','rgba(153, 102, 255, 1)'],
                                            ['rgba(255, 159, 64, 0.2)','rgba(255, 159, 64, 1)'],
                                            ['rgba(2, 219, 9, 0.2)', 'rgba(2, 219, 9, 1)'],
                                            ['rgba(255, 242, 35, 0.2)', 'rgba(255, 242, 35, 1)'],
                                            ['rgba(21, 176, 68, 0.2)', 'rgba(21, 176, 68, 1)'],
                                            ['rgba(255, 127, 0, 0.2)', 'rgba(255, 127, 0, 1)']
                            ];
                            var colorsRandom = [];
                            var borderColorRandom = [];
                            namesFood.forEach(element => {
                                var colorTemp = colors[Math.floor(Math.random() * colors.length)];
                                colorsRandom.push(colorTemp[0]);
                                borderColorRandom.push(colorTemp[1]);
                            });
                            //Aqui empieza el chart
                            var ctx = document.getElementById("myChart");
                                var myChart = new Chart(ctx, {
                                    type: 'bar',
                                    data: {
                                        labels: namesFood,
                                        datasets: [{
                                            label: 'Total vendidos',
                                            data: quantityFood,
                                            backgroundColor: colorsRandom,
                                            borderColor: borderColorRandom,
                                            borderWidth: 1
                                        }]
                                    },
                                    options: {
                                        //responsive:false,
                                        scales: {
                                            yAxes: [{
                                                ticks: {
                                                    beginAtZero:true
                                                }
                                            }],
                                            xAxes: [{
                                                ticks: {
                                                autoSkip: false
                                                }
                                            }]
                                        }
                                    }
                                });
                            }//Aqui
                        });
                    }
                }else{
                    toastr.error('Debe especificar un rango de fechas');
                }
            }
        }
    }
</script>
