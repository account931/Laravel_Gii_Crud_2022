
<template>
	<div class="blog">
	    <hr>
		<h1>{{title}} number {{this.currentDetailID + 1}} </h1>
		
					
		<!--------- Unauthorized/unlogged Section ------> 
        <div v-if="this.$store.state.passport_api_tokenY == null" class="col-sm-12 col-xs-12 alert alert-info"> <!--auth check if Passport Token is set, i.e user is logged -->
            <!-- Display subcomponent/you_are_not_logged.vue -->
            <you-are-not-logged-page></you-are-not-logged-page>         
        </div>
        
        
        
        <!--------- Authorized/Logged Section ----------> 
        <div v-else-if="this.$store.state.passport_api_tokenY != null">
				
		
		    <div class="row">
			    
			
			
		        <!-- If no records -->
                <div v-if="this.$store.state.posts.length == 0" class="col-sm-12 col-xs-12"> 
                    <hr>			
			        <p class="text-danger"> No data fetched for yout Post {{this.currentDetailID + 1 }} , visit first <router-link class="nav-link" to="/blog"> <button class="btn btn-success"> Vue Crud panel </button> </router-link> </p>
		        </div>
			
			
			    <!-- If  records are available || records are not null -->
		        <div v-else>
		

			
		            <!-- Show one product, based on URL ID. Gets values from Vuex store in "/store/index.js" -->
		            <!-- {{  this.$store.state.posts[this.currentDetailID].wpBlog_id }} == same ==> {{  checkStore[this.currentDetailID].productId }} == same as(if used {...mapState(['products']),}) ==> products[this.currentDetailID].productId-->
		            <div>
		                <hr>
            
                        <!-- Nav Link go back -->
                        <p class="z-overlay-fix-2"> 
                            <router-link class="nav-link" to="/blog">
                                <button class="btn btn-info">Back to Vue Crud Panel <i class="fa fa-tag" style="font-size:14px"></i></button>
                            </router-link>
                        </p>
                        <!-- End Nav Link go back -->
		    
                        <p> One product {{this.currentDetailID + 1 }} </p>
					
					
					    <!-- Id  -->     
		                <p> <b><i class="fa fa-book"></i> Article id:         {{ this.$store.state.posts[this.currentDetailID].wpBlog_id }}  </b></p>
             
			            <!-- Title -->
				        <p> <b> <i class="fa fa-calendar-check-o"></i> Title: {{ this.$store.state.posts[this.currentDetailID].wpBlog_title }} </b></p> <!-- title -->
					


			
			
                        <!-- Show the first image -->
			            <!-- Simple image -->
                        <p> 
					
					        <!-- Image with LightBox -->
						    <div v-if="this.$store.state.posts[this.currentDetailID].get_images.length">
						        <a :href="`images/wpressImages/${this.$store.state.posts[this.currentDetailID].get_images[0].wpImStock_name}`"  title="text" :data-lightbox="`roadtrip${ this.$store.state.posts[this.currentDetailID].wpBlog_id }`">  <!-- roadtrip{{"this.currentDetailID}}" -->   <!-- roadtrip + currentID, to create a unique data-lightbox name, so in modal LightBox will show images related to this article only, not all -->
			                        <img  :src="`images/wpressImages/${this.$store.state.posts[this.currentDetailID].get_images[0].wpImStock_name}`"  class="card-img-top image-main"> 
			                    </a>
						    </div>
						
						    <!-- No image -->
						    <div v-else>
			                    <!-- If image does not exist (no image connected via hasOne relation).  ELSE -->
                                <img class="card-img-top my-img-small" :src="`images/no-image-found.png`" />
						    </div>
			            <p>

					
					

			
                        <p> Text:     {{ this.$store.state.posts[this.currentDetailID].wpBlog_text }} </p>
					 
                        <p> Author:   {{ (this.$store.state.posts[this.currentDetailID].author_name != null)  ? this.$store.state.posts[this.currentDetailID].author_name.name  : "No author" }} </p>
                        <p> Email:    {{ (this.$store.state.posts[this.currentDetailID].author_name != null)  ? this.$store.state.posts[this.currentDetailID].author_name.email : "No email"}} </p>
                    
					    <p class='smallX font-italic'> <i class="fa fa-archive"></i>     {{ this.$store.state.posts[this.currentDetailID].category_names.wpCategory_name }} </p>
					
					    <p class='smallX'> <i class="fa fa-bank"></i> <span v-html ="getIfPublished(this.$store.state.posts[this.currentDetailID].wpBlog_status)"></span> </p>
					
					    <p class='smallX'>Created:   {{ this.$store.state.posts[this.currentDetailID].wpBlog_created_at    }}</p>   <!-- Time -->
           
		   
		   
                        <!-- Show all article images via FOR LOOP except for first. HasMany Relation -->
                        <div class="col-md-12" v-for="(img, i) in this.$store.state.posts[this.currentDetailID].get_images" :key=i>
                            <div v-if="i > 0">
							
							    <!-- Image with LightBox -->
						        <a :href="`images/wpressImages/${img.wpImStock_name}}`"  title="text" :data-lightbox="`roadtrip${img.wpImStock_id}`">  <!-- roadtrip{{"this.currentDetailID}}" -->   <!-- roadtrip + currentID, to create a unique data-lightbox name, so in modal LightBox will show images related to this article only, not all -->
			                        <img :src="`images/wpressImages/${img.wpImStock_name}}`"  class="card-img-top image-others"> 
			                    </a>
							
                            </div>
                        </div>
                        <!-- End Show all article images via FOR LOOP except for first. HasMany Relation -->
          
          
                    </div>
		            <!-- Show one product, based on URL ID -->
		
		
		
		        </div><!-- else -->
		
			
			
			
			
		    </div>
	    </div>
	<br><br>
</div>
	
</template>

<script>
	export default{
		name:'edit',
		data (){
			return{
				title:'View',
				currentDetailID: 1, 
			}
		},
		
		//before mount
        beforeMount() {
		    
			//Passport token check
            if(this.$store.state.passport_api_tokenY == null){
                swal("View one page says: Access denied", "You are not logged", "error");
                return false;
            } 
            console.log("beforeMount");
			
			
            //console.log(this.$store.state.posts);
	        //getting route ID => e.g "wpBlogVueFrameWork#/details/2", gets 2. {Pid} is set in 'pages/home' in => this.$router.push({name:'details',params:{Pid:proId}})
	        var ID = this.$route.params.Pidd; //gets 2, id of blog
	        //ID = ID - 1; //to comply with Vuex Store array, that starts with 0
			
			//MegaFIX
			//find the array position of object with "wpBlog_id" === ID in this.$store.state.posts
			let position = this.$store.state.posts.findIndex(x => x.wpBlog_id === ID);
			//alert(position);
			this.currentDetailID = position; //set to this.state
	        //this.currentDetailID = ID; //set to this.state
        },
		
		methods:{
		    getIfPublished(status){
			    if(status == '1'){
		            return '<span class="text-success small"> published </span>';
	            } else {
		            return '<span class="text-danger small"> not published </span>';
	            }
			},
		},
	}
</script>

<style scoped>
	
</style>