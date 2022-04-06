/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

//require('./bootstrap');

window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

//Component => ....
//Vue.component('show-quantity-of-posts', require('./components/Div_with_Quantity.vue').default);
  Vue.component('show-quantity-of-posts', require('./components/Div_with_Quantity.vue')); //register component dispalying qunatity


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

 //Component => Div with Vue 
const appQ = new Vue({
    el: '#vueComponentR',
	//store, //connect Vuex store, must-have
});
