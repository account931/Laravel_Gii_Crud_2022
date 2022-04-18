<template>
  <div :key="componentKey" class="card mt-4">
  
    <div class="card-header row">
    
        <div class="col-sm-5 col-xs-5">
            <p style="float:right;"><i class="fa fa-window-restore" style="font-size:84px"></i></p>
        </div>
        
        <div class="col-sm-7 col-xs-7">
            <h3 style="float:left;">Create New Post {{this.isCreatingPost }}</h3>
        </div>
        
    </div>
    
    
    <!--------- Unauthorized/unlogged Section ------> 
    <div v-if="this.$store.state.passport_api_tokenY == null" class="col-sm-12 col-xs-12 alert alert-info"> <!--auth check if Passport Token is set, i.e user is logged -->
        <!-- Display subcomponent/you_are_not_logged.vue -->
        <you-are-not-logged-page></you-are-not-logged-page>         
    </div>
        
        
        
    <!--------- Authorized/Logged Section ----------> 
    <div v-else-if="this.$store.state.passport_api_tokenY != null">
        
        <div class="card-body">
            <div
                 v-if="status_msg"
                 :class="{ 'alert-success': status, 'alert-danger': !status }"
                 class="alert" role="alert"
            >
                {{ status_msg }}
		
            </div>
        
        
        
            <!-- Display Validation errors if any come from Controller Request Php validator -->
            <div v-for="(book, i) in booksGet " :key="i" class="alert alert-danger"> 
                Error: {{ book }} 
            </div>
        
        
	    
	  
            <form id="myFormZZ">
	            <input type="hidden" name="_token" :value="tokenXX" /> <!-- csfr token -->
		        <!--<input type="hidden" name="_token" value="4gyBcsEYlPibNHfhi1r55rRQAZkBWepxCmVLlqAW" />-->
		
		        <!-- Post Title -->
                <div class="form-group">
                    <label for="exampleFormControlInput1">Title</label>
                    <input id="title" v-model="title" type="text" class="form-control" placeholder="Post Title" required>
                </div>
        
                <!-- Post Body -->
                <div class="form-group">
                    <label for="exampleFormControlTextarea1">Post Content</label>
                    <textarea id="post-content" v-model="body" class="form-control" rows="3" placeholder="Body Title" required />
                </div>
        
        
                <!-- Select category -->
                <div class="form-group">
                    <label for="exampleFormControlTextarea1">Category</label>
        
                    <select name="category_sel" class="mdb-select md-form" v-model="selectV">
				        <option  disabled="disabled"  selected="selected">Choose category</option>
                        <!-- Loop -->
				        <option v-for="(book, i) in this.categoriesList " :key="i" :value="book.wpCategory_id"  > {{ book.wpCategory_name}} </option>
		            </select>
		        </div>					
        


        
                                
                <div class>  
                <!-- Element-UI Upload element (contains "+" button to add new image and contains thumbnails views of loaded images) -->
                <!--ref="upload" is used to fire  clearFiles() in <el-upload> on ajax success -->
                    <el-upload
                        action="https://jsonplaceholder.typicode.com/posts/"
                        ref="upload"
                        list-type="picture-card"
                        :on-preview="handlePictureCardPreview"
                        :on-remove="handleRemove"
                        :before-remove="beforeRemove"
                        :on-change="updateImageList"
                        :auto-upload="false"
                    >
                        <i class="el-icon-plus" />
                    </el-upload>
          
                    <!-- Element-UI Preview Uploaded element (if u hover over it, there appears "+"/"delete" icons, if u click "+" icon the full-screen image pop-up'll emerge, pop-up is hidden by dafault) -->
                    <el-dialog :visible.sync="dialogVisible">
                        <img width="100%" :src="dialogImageUrl" alt>
                    </el-dialog>
          
                </div>
            </form>
        </div>
    
        <div class="card-footer">
            <!--Button to submit -->
            <button type="button" class="btn btn-success"
                @click="createPost"
            >
                {{ isCreatingPost ? "Posting..." : "Create Post" }}
            </button>
      
            <!--Button to clear the fields -->
            <button type="button" class="btn btn-success" @click="clearInputFieldsAndFiles">
                Clear
            </button>
      
        </div>
    
    </div>
    <!--------- Authorized/Logged Section ----------> 
    
</div>
</template>

<style>
.avatar-uploader .el-upload {
  border: 1px dashed #d9d9d9;
  border-radius: 6px;
  cursor: pointer;
  position: relative;
  overflow: hidden;
}
.avatar-uploader .el-upload:hover {
  border-color: #409eff;
}
.avatar-uploader-icon {
  font-size: 28px;
  color: #8c939d;
  width: 178px;
  height: 178px;
  line-height: 178px;
  text-align: center;
}
.avatar {
  width: 178px;
  height: 178px;
  display: block;
}
</style>


<script>
import { mapActions } from 'vuex';
import { mapState } from 'vuex';
//using other sub-component 
import youAreNotLogged  from '../subcomponents/you_are_not_logged.vue';
export default {
    name: 'CreatePost',
    //using other sub-component 
	components: {
        'you-are-not-logged-page' : youAreNotLogged,
    },
    //props: ['categorrrv'], //passed in view 
    data () {
        return {
            dialogImageUrl: '',
            dialogVisible: false, //flag
            imageList: [],        //stores uploaded images
            status_msg: '',
            status: '',
            isCreatingPost: false,//flag
            title: '',  //form input "Title"
            body: '',   //form input "Body"
            selectV: '',//form input <select> 
            componentKey: 0,
	        tokenXX:'',
            errroList: ['no validation error1', 'no validation error2'], //list of validations errors of server-side validator
            categoriesList: [], //contains Categories from DB (loaded with ajax)
        }
    },
    computed: {
        //...mapState(['errroList']), 
        booksGet () { //compute Back-end validation errors
            return this.errroList;
        }      
    },
    
    //before mount
    beforeMount() {
        
    },
    
    
    mounted () {
        //Passport token check
        if(this.$store.state.passport_api_tokenY == null){
            swal("LoadNew says: Access denied", "You are not logged", "error");
            return false;
        }
        let token = document.head.querySelector('meta[name="csrf-token"]'); //gets meta tag with csrf //NOT USED?????
        //alert(token.content);
	    this.tokenXX = token.content; //gets csrf token and sets it to data.tokenXX //NOT USED?????
        this.getAjaxCategories(); //get /GET all DB table categories (to build <select> in loadnew.vue)
      
    },
    created(){
    },
  
    methods: {
        ...mapActions(['getAllPosts']),
    
    
    
        // =============== Start of Element-UI Upload element METHODS ============
    
        //on adding new image to form, do update array {this.imageList} (used to store all form uploaded images & appended to form)
        updateImageList (file) {
           this.imageList.push(file.raw);
           console.log(this.imageList);
        },
	
        //if u click "Preview" icon in Element-UI image Layout
        handlePictureCardPreview (file) {
            this.dialogImageUrl = file.url;
            //this.imageList.push(file); //THIS WAS INCORRECT????
            this.dialogVisible = true;//show pop-up with image
        },
    
        //if u click "Delete" icon in Element-UI image Layout
        handleRemove(file){
            alert("Delete " + file.uid); //https://www.programmersought.com/article/73755547037/
        
            for(var i = 0; i < this.imageList.length; i++){
                if(file.uid == this.imageList[i].uid){
                    this.imageList.splice(i, 1);
                }
            }
        
            console.log(this.imageList);
        },
    
        beforeRemove(file){
            //some stuff
        },
	
        // =============== End  of Element-UI Upload element METHODS ============
    
    
    
       
              
           
        /*
        |--------------------------------------------------------------------------
        | When user clicks Form submitting (create new post)
        |--------------------------------------------------------------------------
        |
        |
        */
        createPost (e) {
            e.preventDefault();
            if (!this.validateForm()) {
                return false;
            }
	  
	        //Form //PROBLEM HERE
            this.isCreatingPost = true;
      
            //Use Formdata to bind inpts and images upload
            var that = this; //Explanation => if you use this.data, it is incorrect, because when 'this' reference the vue-app, you could use this.data, but here (ajax success callback function), this does not reference to vue-app, instead 'this' reference to whatever who called this function(ajax call)
            var formData = new FormData(); //new FormData(document.getElementById("myFormZZ"));
            formData.append('title',        this.title);
            formData.append('body',         this.body);
            formData.append('category_sel', this.selectV);
      
            var imagesUploaded = {};
            $.each(this.imageList, function (key, imageV) {
                formData.append(`imagesSet[${key}]`, imageV);
                //imagesUploaded.push(`images[${key}]`, imageV);
                //imagesUploaded.test = imageV;
            });
	        
            console.log(this.imageList)
	        console.log(formData);
	   
	        //SENDING AJAX to create new post item
            /* api.post('/post/create_post', formData, {
                headers: { 'Content-Type': 'multipart/form-data' }
            }) 
		    */
            alert('token is ' + this.$store.state.passport_api_tokenY);
        
       
            //Add Bearer token to headers
            $.ajaxSetup({
                headers: {
                    'Authorization': 'Bearer '  + this.$store.state.passport_api_tokenY
                }
            }); 
      
		    $.ajax({
		        url: 'api/vue-crud/create_post_vue', 
                type: 'POST', //POST is to create a new user
                cache : false,
                dataType    : 'json',
                processData : false,
                contentType: false,
                //contentType:"application/json; charset=utf-8",						  
                //contentType: 'application/x-www-form-urlencoded; charset=utf-8',
                //contentType: 'multipart/form-data',
			    //crossDomain: true,
			    //headers: {'Content-Type': 'application/x-www-form-urlencoded', 'Authorization': 'Bearer ' + this.$store.state.api_tokenY},
                //headers: { 'Content-Type': 'application/json',  },
			    //contentType: false,
			    //dataType: 'json', //In Laravel causes crash!!!!!// without this it returned string(that can be alerted), now it returns object
           
			    //passing the data
                data: formData, //dataX//JSON.stringify(dataX)  ('#createNew').serialize()
		        //Not used below, reassigned to append
                /*{   
                    _token: this.tokenXX, //csrf token	
                    title:    this.title,	
                    body:     this.body,
                    myImages: imagesToSend,	//array of images
               
			    }, */
                success: function(data) {
                    alert("success");            
                    alert("success" + JSON.stringify(data, null, 4));
                
                
                
                    if(data.error == true ){ //if Rest API endpoint returns any predefined validation error
                        var text = data.data;
                        swal("Check", text, "error");
                    
                        //if validation errors (i.e if REST Contoller returns json ['error': true, 'data': 'Good, but validation crashes', 'validateErrors': title['Validation err text'],  body['Validation err text']])
                        if(data.validateErrors){
                            var tempoArray = []; //temporary array
                            for (var key in data.validateErrors) { //Object iterate
                                var t = data.validateErrors[key][0]; //gets validation err message, e.g validateErrors.title[0]
                                tempoArray.push(t);
                            }
                       
                            that.errroList = tempoArray; //change state errroList //{this-that} fix
                        }
                        
                  
                    //if load new is OK
                    } else if(data.error == false){
                        var tempoArray = []; 
                        that.errroList = tempoArray; //clears validationn errors if any. Simple that.errroList = [] won't work
                        swal("Good", "Bearer Token is OK", "success");
                        swal("Good",  data.data, "success");
                        
                        //clear the form fields after successfull saving
                        that.clearInputFieldsAndFiles();
                        
                    }
			        that.isCreatingPost = false; //change button text            
                },  //end success
            
			    error: function (errorZ) {
                    alert("Crashed"); 
			        alert("error" +  JSON.stringify(errorZ, null, 4));
                    console.log("type is => " + typeof(errorZ));
                    console.log(errorZ.responseText);
                    alert(errorZ.responseText);
                    alert("this " + errorZ.responseJSON.error);
                    console.log(errorZ);
                
                    /*
                    if (errorZ.status == 422) {
                        swal("Error", "Validation crashed", "error");  
                    }*/
                    
                    
                    
                    //Unlog (log out) the user if  dataZ.error == "Unauthenticated." || 401, otherwise if user has wrong password token saved in Locals storage, he will always recieve error and neber log out                  
                    //{responseJSON} is an object property, formed by Passport (if no token is sent, ie. unauthorized), i.e response is like =>  responseJSON: {error: "Unauthenticated."}  
                    //use responseJSON as error section does not return my predifinied json, but automatic Laravel
                    
					if(errorZ.responseJSON != null){
                        
						if (errorZ.responseJSON.error == "Error: Request failed with status code 401" ||  errorZ.responseJSON.error == "Unauthenticated."){ //if Rest endpoint returns 401 error
                            console.log(errorZ.error);
                        
                            //Unlog the user if dataZ.error == "Unauthenticated." || 401, otherwise if user has wrong password token saved in Locals storage, he will always recieve error and neber log out                  
                            //store.dispatch('LogUserOut');//this.$store.dispatch('LogUserOut'); //trigger Vuex function LogUserOut(), which is executed in Vuex store
                            //so far  can't fire store.dispatch('LogUserOut'), so do manually
                            alert('Vuex log out - pre');
                            //localStorage.removeItem('tokenZ'); //clear localStorage
                            //localStorage.removeItem('loggedStorageUser');
                            that.$store.dispatch('LogUserOut'); //reset state vars (state.passport_api_tokenY + state.loggedUser) via mutation
							
                            swal("Unauthenticated", "Check Bearer Token", "error");
                        } else { 
                            swal("Error", "Something crashed", "error"); 
                        }
                    }
                    
                    
                    
                                  
                
                    that.isCreatingPost = false; //change button text   
			    }	  
            });                             
            //END AJAXed  part 
            
		    return false; //to prevent /commnet further {fetch part}
		
		
		    //my fix instead of api.post. NOT ENGAGED, reassigned to ajax
		    /*
            fetch('api/post/create_post_vue', formData, { 
                method: 'POST',
			    //headers: { 'Content-Type': 'application/x-www-form-urlencoded'},
                //contentType: 'application/x-www-form-urlencoded; charset=utf-8',
			    //data :   {//'_token': document.head.querySelector('meta[name="csrf-token"]').content,},
		    })
            .then((res) => { 
		        console.log(res);
                this.title = this.body = ''
                this.status = true
                this.showNotification('Post Successfully Created. REWRITE function createPost(Request $request) without transactions')
                this.isCreatingPost = false
                this.imageList = []
                
                //this.getAllPosts() can be used here as well
                //note: "that" has been assigned the value of "this" at the top to avoid context related issues.
                
                that.getAllPosts()
                that.componentKey += 1
            }) 
            */
        },
	
        
        /*
        |--------------------------------------------------------------------------
        |GET all DB table categories (to build <select> in loadnew.vue)
        |--------------------------------------------------------------------------
        |
        |
        */
	    getAjaxCategories(){
            fetch('api/vue-crud/get_categories', { /*  http://localhost/Laravel_Gii_Crud_2022/public/api/vue-crud/get_categories   */
                method: 'get',
                //pass Bearer token in headers ()
                headers: { 'Content-Type': 'application/json', 'Authorization': 'Bearer ' + this.$store.state.passport_api_tokenY },
                //contentType: 'application/json',
            }).then(response => {
                $('.loader-x').fadeOut(800);  //hide loader
                return response.json();
            }).then(dataZ => {
                console.log("Categories => " + dataZ); 
                if(dataZ.error == true|| dataZ.error == "Unauthenticated."){ //if Rest endpoint returns any predefined error
                    alert(dataZ.data);
                    swal("Unauthenticated", "Check Bearer Token", "error");
                  
                } else if(dataZ.error == false){
                    swal("Done", "Categories are loaded.", "success");
                    this.categoriesList = dataZ.data;
                    console.log("Categ " + this.categoriesList[0].wpCategory_name);
                }
            })
	        .catch(/*err => */ function(err){
                alert("Getting categories failed. Check if u're logged =>  " + err);
                swal("Crashed", "You are in catch", "error");
            }); // catch any error
      
        },
    
	
	    /*
        |--------------------------------------------------------------------------
        |Client-side form validation
        |--------------------------------------------------------------------------
        |
        |
        */
        validateForm () {
            // no vaildation for images - it is needed (Done!!!)
            if (!this.title) {
                this.status = false
                this.showNotification('Post title cannot be empty')
                return false
            }
            if (!this.body) {
                this.status = false
                this.showNotification('Post body cannot be empty')
                return false
            }
      
            
            if (!this.selectV) {
                this.status = false;
                this.showNotification('Select cannot be empty');
                return false;
            }
      
	        //validation for images
			 if (!this.imageList.length > 0) {
                this.status = false;
                this.showNotification('Image cannot be empty');
                return false;
            }
			
			
            this.showNotification(''); //clears error messages if any
            return true
        },
        
        
        showNotification (message) {
            this.status_msg = message;
            setTimeout(() => {  //clears message in n seconds
                this.status_msg = ''
            }, 3000 * 155)
        },
        
        //clears inputs including uploaded files
        clearInputFieldsAndFiles(){
            this.title    = '';
            this.body     = '';
            this.selectV  = '';
            this.imageList = '';
            this.$refs.upload.clearFiles(); //clears the <el-upload> uploaded files <el-upload> must contain {ref="upload"}
                        
        }
    },
  
  
    mutations: {
        //not used here
        setErrors (state, dateX) {
            // mutate state
            state.errroList = dateX;
            alert('mutated');
        }
    },
     
}
</script>