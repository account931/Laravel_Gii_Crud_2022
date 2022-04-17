//Module to include div with quantity and Router(linls and router view)
//Maga crash (router links did not update page content, non-working click, vuex crash) was caused by script conflicts. Fixed: commented /js/app.js + jquery in layouts/app.blade.php -> npm repack -> started working -> uncommented back and still working 

/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('../bootstrap'); //includes JQ
window.Vue = require('vue');





import router from './router/index.js';
import store from '../store/index'; //import Vuex Store

//ElementUI Library
import ElementUI from 'element-ui'; //import ElementUI pop-up modal window
//import 'element-ui/lib/theme-chalk/index.css'; //moved as sepearate CSS Fileto css in /layout/app.php, otherwise UI icons loading fails
Vue.use(ElementUI); //connect Vue to use with ElementUI

//import Router from 'vue-router';
//Vue.use(router); //connect Vue to use with VueRouter

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('show-quantity-of-posts-2',  require('./components/Div_with_Quantity.vue').default); //Version for Laravel Mix 2

//vue-router-menu. Will display blogs all, edit page, view page
//Vue.component('vue-router-menu-with-link-content-display',  require('./components/VueRouterMenu.vue').default); //register component dispalying vue-router-menu

//Import Version for Laravel Mix 5 (for Mix 5 u can also use version with .default (see prev line))
import MyRouterComponent from './components/VueRouterMenu.vue';
Vue.component('vue-router-menu-with-link-content-display', MyRouterComponent);

var $ = require( "jquery" );

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

 //Div with quantity
const app = new Vue({
	//router, //must-have for Vue routing,
    el: '#coreDiv',
	store, //connect Vuex store, must-have
});



//Component => Div with Vue route menu and area to dispaly selected menu    
const appMenu = new Vue({
	router, //must-have for Vue routing,
	el: '#vue-menu',
	store, //connect Vuex store, must-have
	
    
});



/*
const appx = new Vue({
  router
}).$mount('#vue-menu')
*/