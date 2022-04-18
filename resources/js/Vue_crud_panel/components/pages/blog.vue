
<template>
	<div class="blog">
		<h3>{{title}}</h3>
		
		        
		<button class="btn btn-danger" v-on:click="wtf"> Test </button>
		<hr>
		
				
				
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
         
        
		    <!-- button to create new record -->
			<router-link class="nav-link" to="/create-new">  
				<button class="btn btn-info">Create new </button> 
			</router-link> 
								
								
			
		    <!-- If there is no blog records so far -----> <!-- amendment 30.03.2022 -->
		    <div v-if="this.$store.state.posts.length == 0"> 
                <hr>			
			    <p class="text-danger">No records found so far</p>
		    </div>
	        <!-- End If there is no blog records so far -->
			
			
			
			
			<!------------- Vue Crud Panel ---------->
            <div class="row">
			
			    <div class="col-sm-12 col-xs-12 row div-striped over-scroll">
						
				    <!-- Table headers -->
				    <div class="col-sm-3 col-xs-2  card-header my-head"> <b>Name   </b></div> <!-- Migrating from BStrap v3 to v4: changed "hidden-xs" to "d-none" -->
				    <div class="col-sm-3 col-xs-2  card-header my-head"> <b>Text   </b></div>
				    <div class="col-sm-3 col-xs-2  card-header my-head"> <b>Image  </b></div>
				    <div class="col-sm-3 col-xs-2  card-header my-head"> <b>Action </b></div>
							
        
                    <!-- Original part, Displays post articles from Vuex Store /store/index.js -->
                    <div v-for="(post, i) in this.$store.state.posts " :key=i class="col-sm-12 col-xs-12 row one-row"> <!-- or this.$store.state.posts -->
					
						
							
							 <!-- Title -->
						    <div class="col-sm-3 col-xs-2 card-header"> <!-- Migrating from BStrap v3 to v4: changed .panel-heading to .card-header. Migrating from BStrap v3 to v4 -->
								{{ post.wpBlog_title }} <br>
							    <p v-html ="getIfPublished(post.wpBlog_status)"> </p>  <!-- unescaped vue --> <!-- "published"/"not_published" based on column 'wpBlog_status'-->
						    </div>
							
							<!-- Text -->
							<div class="col-sm-3 col-xs-2 card-header"> 
							    {{ truncateText(post.wpBlog_text, 6) }}
						    </div>
							
							
							
							<!-- Image -->
							<div class="col-sm-3 col-xs-2 card-header">
							    <!-- Show 1st image if exists. HasMany Relation. {get_images} is a model {function getImages()}  HasMany Relation -->		 
                                <!--<img v-if="post.get_images.length" class="card-img-top my-img" :src="`images/wpressImages/${post.get_images[0].wpImStock_name}`" />-->
		
		                        <!-- Image with LightBox -->
	                            <a v-if="post.get_images.length" :href="`images/wpressImages/${post.get_images[0].wpImStock_name}`"   title="image" :data-lightbox="`roadtrip${post.wpBlog_id}`" > <!-- roadtrip + currentID, to create a unique data-lightbox name, so in modal LightBox will show images related to this article only, not all -->
                                    <img v-if="post.get_images.length" class="card-img-top image-main-gii" :src="`images/wpressImages/${post.get_images[0].wpImStock_name}`" @error="imageUrlAlt" />       <!-- @error - is a method to run if image url is invalid or broken or image physically not available in folder -->
	                            </a>
                                <!-- End Image with LightBox -->
		
                                <!-- If image does not exist (no image connected via hasOne relation). ELSE -->
                                <img v-else class="card-img-top image-main-gii" :src="`images/no-image-found.png`" />
						    </div>
										
							

                            <!-- Action buttons -->
						    <div class="col-sm-3 col-xs-2 card-header">
																					
							    <!-- View btn  -->
							    <button class="btn btn-info" @click="goToViewDetail(post.wpBlog_id -1 )"> 
								    <i class="fa fa-eye" style="font-size:0.7em" onclick="return confirm('Are you sure to view?')"></i> 
								</button>
								
											
								<!-- Edit btn  -->
								<button class="btn btn-success" @click="goToEditDetail(post.wpBlog_id -1 )" >  
									<i class="fa fa-pencil" style="font-size:0.7em" onclick="return confirm('Are you sure to edit?')"></i> 
								</button> 
								
											
								<!-- Delete btn -->
								<router-link to="/edit">  <!-- removed class="nav-link" in order btn to be on the same line, like <span> not <p> -->
								    <button class="btn btn-danger"><i class="fa fa-trash-o" style="font-size:0.7em" onclick="return confirm('Are you sure to delete?')"></i> </button> 
								</router-link> 
								
								
                                <!-- End Delete bt -->
											
						    </div> 
						    <!-- End Action buttons -->							
						
						
                    </div>
					
				</div>
				
	        </div> <!-- end class="row"-->
	        <!------------- End  Vue Crud Panel ---------->
			
			
			
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
            if(this.$store.state.passport_api_tokenY == null){
                swal("Vue crud panel says: Access denied", "You are not logged", "error");
                return false;
            } 
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
			},
			
			
            truncateText(text, length) {
                if (text.length > length) {
                    return `${text.substr(0, length)}...`;
                }
                return text;
            },
			
			getIfPublished(status){
			    if(status == '1'){
		            return '<span class="text-success small"> published </span>';
	            } else {
		            return '<span class="text-danger small"> not published </span>';
	            }
			},
			
			//Router to go to edit one item page
            goToEditDetail(prodId) {
                let proId = prodId+1;
                this.$router.push({name:'edit-one',params:{Pidd:proId}}) //creates route like "/wpBlogVueFrameWork#/details/3"
            },
			
			//Router to go to view one itempage
            goToViewDetail(prodId) {
                let proId = prodId+1;
                this.$router.push({name:'view-one',params:{Pidd:proId}}) //creates route like "/wpBlogVueFrameWork#/details/3"
            },
			
			
		  /*|--------------------------------------------------------------------------
            | If image url is invalid or broken or image physically not available in folder, then use 'images/image-corrupted.jpg"
            |--------------------------------------------------------------------------
            |
            |
            */
		    imageUrlAlt(event) {
                event.target.src = "images/image-corrupted.jpg"
            },			
    
	
			
		},
		
		
	}
</script>

<style scoped>
	
</style>