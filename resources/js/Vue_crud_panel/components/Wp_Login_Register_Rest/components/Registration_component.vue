<!-- Uses /subcomponents/register_form.vue  -->


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
                <p>You are now Logged as </p>
                <p> <i class="fa fa-address-card-o" style="font-size:24px"></i> {{ this.$store.state.loggedUser.name }} </p>
                <p> <i class="fa fa-envelope-o" style="font-size:24px"></i>     {{ this.$store.state.loggedUser.email }} </p>

                <p> click to Log Out </br> <button class="btn btn-success" @click="logMeOut"> Log out </button> </p>
                
                
            </div>
            
            <!-- Register Form -->
            <div v-else class="col-sm-12 col-xs-12 alert alert-danger">
                Not Logged
                <registration-auth-page> </registration-auth-page> <!-- Vue subcomponent (Register form) -->                  
                
            </div>
            
        </center>
    </div>

</template>

<script>
//using other sub-component 
import RegistrationPage      from './subcomponents/register_form.vue';


export default {
    name: 'register',
    
    //using other sub-component 
	components: {
          'registration-auth-page' : RegistrationPage,
         
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

    },
    beforeMount() {
    },
    created(){
        alert("passport_api_tokenY type is " + typeof this.$store.state.passport_api_tokenY);
    },
    
    methods: {
        logMeOut(){
            alert("do logging out");
            this.$store.dispatch('LogUserOut'); //trigger Vuex function LogUserOut(), which is executed in Vuex store
            //drop store localStorage.setItem('tokenZ' + localStorage.setItem('loggedStorageUser' + this.$store.state.ifLogged
        },
    
    },
}
</script>