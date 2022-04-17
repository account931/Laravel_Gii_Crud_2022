//https://github.com/hayanisaid/Vue-router
import Vue from 'vue';
//import Router from 'vue-router';
import Router from 'vue-router';
Vue.use(Router);

//import VueRouter from 'vue-router';

import start      from '../components/pages/start';
import blog       from '../components/pages/blog';
import edit       from '../components/pages/edit';
import view_one   from '../components/pages/view-one';
import contact    from '../components/pages/contact';
import create_new from '../components/pages/load-new';


//Auth
import login_page     from '../components/Wp_Login_Register_Rest/components/Login_component';
import register_page  from '../components/Wp_Login_Register_Rest/components/Registration_component';


/*
import home from '../components/pages/home';
import blog from '../components/pages/blog';
*/




//export /*const router = */ default new Router({ 

//export default new Router({
  export default new Router({
  routes: [
    
	{
        path: '/',
        name: 'start', //same as in component return section
        component: start  //component itself
    },
	
    {
        path: '/blog',
        name: 'blog', //same as in component return section
        component: blog,  //component itself
    },
	
	/*
	{
        path: '/edit',
        name: 'edit', //same as in component return section
        component: edit  //component itself
    },
	*/
	
	{
        path: '/contact',
        name: 'contact', //same as in component return section
        component: contact  //component itself
    },
	
	//Blog 2021, view one item routing
    {
        path: '/view-one/:Pidd', 
        name: 'view-one', //same as in component return section
        component: view_one //component itself
    },
	
	
	//Blog 2021, edit one item routing
    {
        path: '/edit-one/:Pidd', 
        name: 'edit-one', //same as in component return section
        component: edit //component itself
    },
	
	
	//login (Rest Api by Passport)
    {
        path: '/login', 
        name: 'login', //same as in component return section
        component: login_page //component itself
    },
	
	//registration (Rest Api by Passport)
    {
        path: '/register', 
        name: 'register-page', //same as in component return section
        component: register_page //component itself
    },
	
	
	 {
        path: '/create-new', 
        name: 'create_new',   //same as in component return section
        component: create_new //component itself
    },
	
	
	

  ]
})