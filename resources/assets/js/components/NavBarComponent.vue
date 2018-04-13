<template>
    <div>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <a class="navbar-brand" href="#">Navbar</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item" :class="isActiveLink('/foods')" v-if="userrole=='administrador'">
                        <router-link class="nav-link" :to="{ name: 'foods' }"><span v-on:click="ocultar()">Productos</span></router-link>
                    </li>
                    <li class="nav-item" :class="isActiveLink('/sales')">
                        <router-link class="nav-link" :to="{ name: 'sales' }"><span v-on:click="ocultar()">Ventas</span></router-link>
                    </li>
                    <li class="nav-item" :class="isActiveLink('/expends')"  v-if="userrole=='administrador'">
                        <router-link class="nav-link" :to="{ name: 'expends' }"><span v-on:click="ocultar()">Gastos</span></router-link>
                    </li>
                    <li class="nav-item dropdown" v-if="userrole=='administrador'">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Reportes
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <router-link class="dropdown-item" :to="{ name: 'reports.utility' }"><span v-on:click="ocultar()">Reportes de utilidad</span></router-link>
                            <router-link class="dropdown-item" :to="{ name: 'reports.mostsold' }"><span v-on:click="ocultar()">Reporte de lo más vendido</span></router-link>
                        </div>
                    </li>
                    <li class="nav-item" :class="isActiveLink('/company')" v-if="userrole=='administrador'">
                        <router-link class="nav-link" :to="{ name: 'company' }"><span v-on:click="ocultar()">Empresa</span></router-link>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {{ names }}
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a href="#" class="dropdown-item" onclick="getElementById('salir').submit()">Salir</a>
                            <form action="/logout"  method="post" id="salir" style="display:none">
                                <input type="hidden" name="_token" :value="csrf">
                                <button type="submit">Salir</button>
                            </form>
                        </div>
                    </li>
                </ul>
            </div>  
            </nav>
            <br>
    </div>
</template>
<script>
    export default {
        props:['csrf', 'username', 'userrole', 'names'],
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
