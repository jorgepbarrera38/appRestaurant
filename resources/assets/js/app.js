
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

import Vue from 'vue';
import VueRouter from 'vue-router';
import SaleComponent from './components/SaleComponent';
import FoodComponent from './components/FoodComponent';
import ReportComponent from './components/ReportComponent';
import ExpendComponent from './components/ExpendComponent';
import CompanyComponent from './components/CompanyComponent';


Vue.use(VueRouter);

const routes = [
    { path: '/sales', name: 'sales', component: SaleComponent },
    { path: '/foods', name:'foods', component: FoodComponent },
    { path: '/reports', name: 'reports', component: ReportComponent },
    { path: '/expends', name: 'expends', component: ExpendComponent },
    { path: '/company', name: 'company', component: CompanyComponent }
];

const router = new VueRouter({
    routes,
});

Vue.component('navbar-component', require('./components/NavBarComponent'));

const app = new Vue({
    el: '#app',
    router,
});
