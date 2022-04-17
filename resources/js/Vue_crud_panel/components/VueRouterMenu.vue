<!-- https://github.com/hayanisaid/Vue-router -->
<!-- Vue-Router Main menu with Links -->

<template>
    <div id="appDemo">
        
        
        <p>I am in VueRouterMenu.vue</p>
        

        <!--- Menu Variant 3 --->
        <div class=" fix-for-non-working-click-in-mobile"> <!-- .fix-for-non-working-click-in-mobile is a fix for non-working click in mobile -->

            <ul class="nav">
				
                <li class="nav-item">
                    <router-link class="nav-link" to="/start"> Start </router-link>
                </li>
				
				
                <li class="nav-item">
                    <router-link class="nav-link" to="/blog">Blog(Gii)</router-link>
                </li>
						
			    <li class="nav-item">
                    <router-link class="nav-link" to="/edit"> Edit(Del) </router-link>
                </li>
                
				<li class="nav-item">
                    <router-link class="nav-link" to="/contact"> Contact </router-link>
                </li>
				
				<li class="nav-item">
                    <router-link class="nav-link" to="/create-new"> Create-new </router-link>
                </li>
				
				
				<li class="nav-item">
                    <router-link class="nav-link" to="/login"> 
                         {{ (this.$store.state.passport_api_tokenY == null) ? "Login" : "Dashboard"	 }}	
					</router-link>
                </li>
				
				<li class="nav-item"  v-if="this.$store.state.passport_api_tokenY == null"> <!-- Visible if Passport token not set -->
                    <router-link class="nav-link" to="/register"> Register </router-link>
                </li>
				
				
            </ul>
        </div>
	    <!--- End Menu Variant 3 --->


	 
	  
	  
	    <!------ Shows the rendered page (home, services, etc) based on selected menu item. Uses animation ------>
        <div class="col-sm-12 col-xs-12 container">
            <transition name="moveInUp">
                 <router-view></router-view> <!-- built-in Vue component -->
            </transition>
        </div>
	    <!------ End Shows the rendered page (home, services, etc) based on selected menu item ------>
    
    </div>
</template>

<script>
    export default {
        name: 'Router',
        //props: ['currentUser', 'categoriesxx'],
        
        
        
        //before mount
        beforeMount() { 
        },
		
        watch:{
            $route (to, from){ 
			    //alert(5);
			    //router.push({ name: 'Edit' })
                //this.show = false;
           }
        },  


    }
</script>




<style>
/* mine */
.nav-item   {margin-left:2em; }
.nav-item a {color:black;}
.navbar-brand {color:black}

#appDemo {
  font-family: 'Avenir', Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
  margin-top: 20px;
}
.moveInUp-enter-active{
  animation: fadeIn 2s ease-in;
}
@keyframes fadeIn{
  0%{
    opacity: 0;
  }
  50%{
    opacity: 0.5;
  }
  100%{
    opacity: 1;
  }
}
.moveInUp-leave-active{
  animation: moveInUp .3s ease-in;
}
@keyframes moveInUp{
 0%{
  transform: translateY(0);
 }
  100%{
  transform: translateY(-400px);
 }
}


/* Fix => Class with z-index is a fix for non-working click in mobile  */
.fix-for-non-working-click-in-mobile {
	position:relative; z-index:9;
}
</style>