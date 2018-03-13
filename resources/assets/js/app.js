
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

Vue.use(VueRouter);

const routes = [
    { path: '/sales', name: 'sales', component: SaleComponent },
    { path: '/foods', name:'foods', component: FoodComponent }
];

const router = new VueRouter({
    routes,
});

const app = new Vue({
    el: '#app',
    router,
});
