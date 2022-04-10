<!-- This is a Sub-component of Login_component.vue, used in \resources\assets\js\Wp_Login_Register_Rest\components -->
<!-- Uses Vuex store: this.$store.state.loggedUser + this.$store.state.passport_api_tokenY -->

<template>

    <div class="col-sm-12 col-xs-12 alert alert-info borderX">
        <center>
           <p> click to Log Out </br> <button class="btn btn-success" @click="logMeOut"> Log out </button> </p>
		   <h3> Your name is..... {{this.$store.state.loggedUser.name}} </h3>
           <p> Your email is <b> {{this.$store.state.loggedUser.email}} </b></p>
           <p style="word-wrap: break-word"> Token is  {{this.$store.state.passport_api_tokenY }} </p>
        </center>
    </div>

</template>

<script>

export default {
    name: 'all-posts',
    data() {
        return {
            //postDialogVisible: false,
        };
    },
  
    //computed property is used to declaratively describe a value that depends on other values. When you data-bind to a computed property inside the template, Vue knows when to update the DOM when any of the values depended upon by the computed property has changed.
    computed: { 
    },
    beforeMount() {
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