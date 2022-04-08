//https://github.com/hayanisaid/Vue-router
import Vue from 'vue';
//import Router from 'vue-router';
import Router from 'vue-router';
Vue.use(Router);

//import VueRouter from 'vue-router';

import blog     from '../components/pages/blog';
import edit     from '../components/pages/edit';
import contact  from '../components/pages/contact';

/*
import home from '../components/pages/home';
import blog from '../components/pages/blog';
*/




//export /*const router = */ default new Router({ 

//export default new Router({
  export default new Router({
  routes: [
  
    {
        path: '/blog',
        name: 'blog', //same as in component return section
        component: blog,  //component itself
    },
	{
        path: '/edit',
        name: 'edit', //same as in component return section
        component: edit  //component itself
    },
	
	{
        path: '/contact',
        name: 'contact', //same as in component return section
        component: contact  //component itself
    },
	

  ]
})