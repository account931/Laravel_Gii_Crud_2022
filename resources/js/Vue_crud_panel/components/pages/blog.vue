
<template>
	<div class="blog">
		<h3>{{title}}</h3>
		
		        
		<button class="btn btn-danger" v-on:click="wtf"> Test </button>
		
				
				
		<!--------- Unauthorized/unlogged Section ------> 
        <div v-if="this.$store.state.passport_api_tokenY == null" class="col-sm-12 col-xs-12 alert alert-info"> <!--auth check if Passport Token is set, i.e user is logged -->
            
            <!-- Display subcomponent/you_are_not_logged.vue -->
            <you-are-not-logged-page></you-are-not-logged-page>         
        </div>
        
        
        
        <!--------- Authorized/Logged Section ----------> 
        <div v-else-if="this.$store.state.passport_api_tokenY != null">
        
		    <div class="contact">
		    <!--<h3> Blog Vue,  <b> </b> <p>Token (from Vuex STORE): {{this.$store.state.passport_api_tokenY}}</p> </h3>-->
            
		    </div>
         
        
		    <!-- If there is no blog records so far -----> <!-- amendment 30.03.2022 -->
		    <div v-if="this.$store.state.posts.length == 0"> 
                <hr>			
			    <p class="text-danger">No records found so far</p>
		    </div>
	        <!-- End If there is no blog records so far -->
			
			
			
            <div class="row">
        
                <!-- Original part, Displays post articles from Vuex Store /store/index.js -->
                <div v-for="(post, i) in this.$store.state.posts " :key=i> <!-- or this.$store.state.posts -->
                    <p>{{ post.wpBlog_title }}</p>
                
                </div>
	         </div> <!-- end class="row"-->
	
	    </div>
		
	</div>
	
</template>

<script>
    import $ from "jquery";
    import { mapState } from 'vuex';
    //using other sub-component 
    import youAreNotLogged  from '../subcomponents/you_are_not_logged.vue';
	export default{
		name:'blog',
		//using other sub-component 
	    components: {
            'you-are-not-logged-page' : youAreNotLogged,
        },
		
		data (){
			return{
				title:'Blog',
				title:'Vue Crud',
                postDialogVisible: false,
                currentPost: '',
                ifMakeAjax: true,
				//testDate: [3,4,5],
			}
		},
		
		computed: {
            ...mapState(['posts']), //is needed for Vuex store, after it u may address Vuex Store value as {posts} instead of {this.$store.state.posts}
	        checkStore() {
		        return this.$store.state.posts;
			},
			
			checkStore2() {
		        return this.$store.state.posts[0]['wpBlog_title'];
			},
		},
		
		//before mount
        beforeMount() {
		
            //Passport token check
			/*
            if(this.$store.state.passport_api_tokenY == null){
                swal("Blog_2021 says: Access denied", "You are not logged", "error");
                return false;
            } */
            console.log("beforeMount");
			
            if(Object.keys(this.$store.state.posts).length < 1){ //if {posts} already exists in Vuex Store
               //run ajax in Vuex store
                console.log('BeforeMount: Making ajax is authorized');
                this.$store.dispatch('getAllPosts'); //trigger ajax function getAllPosts(), which is executed in Vuex store to REST Endpoint => /public/post/get_all
	        } else{
                console.log("BeforeMount: Alreday loaded");
            }
            
        },
		
		
		methods: {
		    //function to switch CSS to show/hide HighLighted Errors text
		    wtf: function () {
		        alert('finally works');
			}
		},
		
		
	}
</script>

<style scoped>
	
</style>