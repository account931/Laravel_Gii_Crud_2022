
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
							    <button class="btn btn-info" @click="goToViewDetail(post.wpBlog_id - 1 )"> 
								    <i class="fa fa-eye" style="font-size:0.7em" onclick="return confirm('Are you sure to view?')"></i> 
								</button>
								
											
								<!-- Edit btn  -->
								<button class="btn btn-success" @click="goToEditDetail(post.wpBlog_id - 1 )" >  
									<i class="fa fa-pencil" style="font-size:0.7em" onclick="return confirm('Are you sure to edit?')"></i> 
								</button> 
								
											
								<!-- Delete btn -->
								<!-- <router-link to="/edit"> --> <!-- removed class="nav-link" in order btn to be on the same line, like <span> not <p> -->
								<!-- <button class="btn btn-danger"><i class="fa fa-trash-o" style="font-size:0.7em" onclick="return confirm('Are you sure to delete?')"></i> </button> 
								</router-link> -->
								
								<button class="btn btn-danger"  @click="deletePost(post.wpBlog_id)">
								    <i class="fa fa-trash-o" style="font-size:0.7em" onclick="return confirm('Are you sure to delete?')"></i>
								</button>
								
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


              
           /*
            |--------------------------------------------------------------------------
            | Ajax to Delete one item
            |--------------------------------------------------------------------------
            |
            |
            */
            deletePost(item){
            
                if(!confirm('Sure to delete Post ' + item + '?')){
                    return false;
                }
                $('.loader-x').fadeIn(800); //show loader
                var that = this; //to fix context issue
                this.selectedItem = item;
                alert('Delete ' + this.selectedItem + " Implement REST API delete function");
                
                //Add Bearer token to headers
                $.ajaxSetup({
                    headers: {
                        'Authorization': 'Bearer ' + this.$store.state.passport_api_tokenY //PASSPORT api_tokenY
                    }
                }); 
      
		        $.ajax({
                          
		            url: 'api/vue-crud/admin_delete_item/' + this.selectedItem, 
                    type: 'DELETE', //
                    cache : false,
                    dataType    : 'json',
                    processData : false,
                    contentType: false,
			        //crossDomain: true,
			        //headers: {'Content-Type': 'application/x-www-form-urlencoded', 'Authorization': 'Bearer ' + this.$store.state.passport_api_tokenY},  //this.$store.state.api_tokenY
                    //headers: { 'Content-Type': 'application/json',  },
			        //contentType: false,
			        //dataType: 'json', //In Laravel causes crash!!!!!// without this it returned string(that can be alerted), now it returns object
           
			        //passing the data
                    data: {},
		    
                    success: function(data) {
                        alert("success");            
                        alert("success" + JSON.stringify(data, null, 4));
                
                        if(data.error == true ){ //if Rest endpoint returns any predefined error
                            var text = data.data;
                            swal("Check", text, "error");
                    
                            //if validation errors (NOT THE CASE FOR DELETE) (i.e if REST Contoller returns json ['error': true, 'data': 'Good, but validation crashes', 'validateErrors': title['Validation err text'],  body['Validation err text']])
                            if(data.validateErrors){
                                var tempoArray = []; //temporary array
                                for (var key in data.validateErrors) { //Object iterate
                                    var t = data.validateErrors[key][0]; //gets validation err message, e.g validateErrors.title[0]
                                    tempoArray.push(t);
                                }
                            }
                        //if REST endpoint returns OK  
                        } else if(data.error == false){
                            swal("Good", "Bearer Token is OK", "success");
                            swal({html:true, title: "Deletion was OK", text: data.data, type: "success"});
                            that.runAjaxToGetPosts(); //renew the list
                        }
						$('.loader-x').fadeOut(800); //hide loader
                    },  //end success
            
			        error: function (errorZ) {
                        alert("Crashed"); 
			            alert("error" +  JSON.stringify(errorZ, null, 4));
                        console.log(errorZ.responseText);
                        console.log(errorZ);
                
                        if(errorZ.responseJSON != null){
                            if(errorZ.responseJSON.error == "Error: Request failed with status code 401" ||  errorZ.responseJSON.error == "Unauthenticated."){ //if Rest endpoint returns any predefined error
                                swal("Error: Unauthenticated", "Check Bearer Token", "error"); 
                                alert('Vuex log out - pre'); 
								
                                //Unlog the user if dataZ.error == "Unauthenticated." || 401, otherwise if user has wrong password token saved in Locals storage, he will always recieve error and neber log out                  
                                that.$store.dispatch('LogUserOut'); //reset state vars (state.passport_api_tokenY + state.loggedUser) via mutation							   
                            } else {  
                               swal("Error", "Something else crashed", "error"); 
                            }
                        }
                        //swal("Error", "Something crashed", "error"); //Commented or it will overleap and prevent to appear  swal("Error: Unauthenticated
                        $('.loader-x').fadeOut(800); //hide loader						
			        }	  
                });                             
                //END AJAXed  part 
                
            },
			
			
			
			
			//to renew list
			runAjaxToGetPosts(){
			    this.$store.dispatch('getAllPosts'); //trigger ajax function getAllPosts(), which is executed in Vuex store to REST Endpoint => /public/post/get_all
			},
            			
    
	
			
		},
		
		
	}
</script>

<style scoped>
	
</style>