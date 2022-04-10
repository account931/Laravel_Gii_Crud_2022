<!-- Uses /subcomponents/login.vue + /subcomponents/logged.vue -->


<!-- Uses Vuex store: this.$store.state.passport_api_tokenY -->
<!-- Uses Vuex store: this.$store.state.ifLogged => FALSE -->

<template>

    <div class="col-sm-12 col-xs-12 alert alert-info borderX">
        <center> 
            
            <p> Vuex Getter: {{this.$store.getters.isLoggedIn}}</p>
            
            <p> My comp: {{this.isLoggedInZ}}</p>
		    <p> Login(Vuex state): {{ this.ifPassportTokenSet }} <!-- {{ this.$store.state.passport_api_tokenY }} -->  </p> <!--{{ this.$store.state.posts.length }}-->
            
            <!-- If user is logged View -->
            <div v-if="this.$store.state.passport_api_tokenY !== null"> <!--auth check if Passport Token is set, i.e user is logged -->
                Logged 
                <logged-user-page></logged-user-page>
            </div>
            
            <!-- Login Form -->
            <div v-else class="col-sm-12 col-xs-12 alert alert-danger">
                <div class="col-sm-4 col-xs-4 alert alert-danger col-sm-offset-4 col-xs-offset-4">
                    Not Logged 
                </div>
                
                <login-auth-page> </login-auth-page> <!-- Vue subcomponent (Login Form) -->
            </div>
            
        </center>
    </div>

</template>

<script>
//using other sub-component 
import loginPage      from './subcomponents/login.vue';
import loggedUserPage from './subcomponents/logged.vue';

export default {
    name: 'all-posts',
    
    //using other sub-component 
	components: {
          'login-auth-page' : loginPage,
          'logged-user-page': loggedUserPage,
    },
    data() {
        return {
            //postDialogVisible: false,
        };
    },
  
    //computed property is used to declaratively describe a value that depends on other values. When you data-bind to a computed property inside the template, Vue knows when to update the DOM when any of the values depended upon by the computed property has changed.
    computed: { 
       
        //just to form text information
        ifPassportTokenSet () {
            if(this.$store.state.passport_api_tokenY != null){
                return "Computed: Passport Token is set, User logged";
            } else {
                return "Computed: Passport Token is not set, login first";
            }
        },
        
        isLoggedInZ(){ return this.$store.getters.isLoggedIn },
		
		//change menu Login if user is logged (has passport_api_tokenY)., DOES NOT WORK
		/*
		whatMenuToFire(){
		    if(this.$store.state.passport_api_tokenY !== null){
		        $("#changeMenu").html('My dashBoard');
		    } else { 
		        $("#changeMenu").html('Log inn');
		    }
		}, */

    },
	
	//DOES NOT WORK
	watch: {
	   /*
       'this.$store.state.passport_api_tokenY': function(newValue, oldValue) {
           alert(`Field changed for player from ${oldValue} to ${newValue}`)
       } */
	   
    },
	



    beforeMount() {
    },
	
    created(){
	    //just for information
        alert("Login_component.vue says : passport_api_tokenY type is " + typeof this.$store.state.passport_api_tokenY + " . If Type is Object -> it means Null(no token), type String means Passport token is set ");
	    
		//this.$store.state.passport_api_tokenY !== null ? $("#changeMenu").html('My dashBoard2') : $("#changeMenu").html('Log inn2');
	},
    
    methods: {
    
    },
}
</script>