<template>
    <div>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <a class="navbar-brand" href="#">Navbar</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item" :class="isActiveLink('/foods')">
                        <router-link class="nav-link" :to="{ name: 'foods' }"><span v-on:click="ocultar()">Productos</span></router-link>
                    </li>
                    <li class="nav-item" :class="isActiveLink('/sales')">
                        <router-link class="nav-link" :to="{ name: 'sales' }"><span v-on:click="ocultar()">Ventas</span></router-link>
                    </li>
                    <li class="nav-item" :class="isActiveLink('/gast')">
                        <router-link class="nav-link" :to="{ name: 'expends' }"><span v-on:click="ocultar()">Gastos</span></router-link>
                    </li>
                    <li class="nav-item" :class="isActiveLink('/reports')">
                        <router-link class="nav-link" :to="{ name: 'reports' }"><span v-on:click="ocultar()">Reportes</span></router-link>
                    </li>
                    <li class="nav-item" :class="isActiveLink('/company')">
                        <router-link class="nav-link" :to="{ name: 'company' }"><span v-on:click="ocultar()">Empresa</span></router-link>
                    </li>
                </ul>
            </div>  
            </nav>
            <br>
    </div>
</template>
<script>
    export default {
        methods:{
            isActiveLink(url){
                return url == this.$route.path ? 'active':'';
            },
            ocultar: function(){
                $('.navbar-collapse').collapse('hide');
            }
        }
    }
</script>
