//Vuex store
import Vue from 'vue';
import Vuex from 'vuex';
import axios from 'axios';


Vue.use(Vuex);
const debug = process.env.NODE_ENV !== 'production';

//Vuex store itself
export default new Vuex.Store({
    state: {
	    //posts used in Vue blog
	    posts              : [], //posts: [{"wpBlog_id":1,"wpBlog_title":"Guadalupe Runolfsdottir", "wpBlog_text":"Store text 1", ,"wpBlog_category":4,"wpBlog_status":"1", "get_images":[{"wpImStock_id":16,"wpImStock_name":"product6.png","wpImStock_postID":1,"created_at":null,"updated_at":null}],"author_name":{"id":1,"name":"Admin","email":"admin@ukr.net","created_at":null,"updated_at":null},"category_names":{"wpCategory_id":4,"wpCategory_name":"Geeks","created_at":null,"updated_at":null}}, {"wpBlog_id":2,"wpBlog_title":"New", "wpBlog_text":"Store text 2"}],
        //api_tokenY       : localStorage.getItem('tokenZ') || '' , //api_token is passed from php in view as <vue-router-menu-with-link-content-display v-bind:current-user='{!! Auth::user()->toJson() !!}'>  and uplifted here to this store in VueRouterMenu in beforeMount() Section. Was true in prev project
        adm_posts_qunatity : 0, //quantity of posts found
        loggedUser         : JSON.parse(localStorage.getItem('loggedStorageUser_4279')) || {name: 'not set', email: 'errorMail'}, //logged user data (JS type:Object), set by Login ajax, {name: '', email: ''}  use {JSON.parse} to convert string to JS type: OBJECT
        passport_api_tokenY: localStorage.getItem('tokenZ') || null , // is set by ajax in /subcomponents/login.vue {thatX.$store.dispatch('changeVuexStoreLogged', data); and mutated here by { changeVuexStoreLogged({ commit }, dataTestX) } }
        //ifLogged           : this.getters.fruitsCount,//true,  //() =>ifTokenExists(), //state based on computed //false, //flag whether user logged or not (Passport changes here)
        test: 'mmmm',
        
	    //products are used in Router example. NOT USED IN CLEANSED Version. Set via seeder to DB and extracted via store/index.js ajax
        /*	 
        products:[
	        {productTitle:"ABCN", image: 'product1.png', productId:1},
            {productTitle:"KARMA",image: 'product2.png', productId:2},
            {productTitle:"Tino", image: 'product3.png', productId:3},
            {productTitle:"EFG",  image: 'product4.png', productId:4},
            {productTitle:"MLI",  image: 'product5.png', productId:5},
            {productTitle:"Banan",image: 'product6.png', productId:6}
        ],
        */
    },
  
    
    getters: {
        //minor getter, can delete (both from Login_component)
         getCart: function (state) { //getCart(state) {
            return state.passport_api_tokenY;
        },
        
        isLoggedIn: state => !!state.passport_api_tokenY, //get value (true/false) based on other state
        /*
        fruitsCount (state) { 
            
            if(state.passport_api_tokenY !=''){
                return true;
            } else {
                return true;
            }
             //(state.passport_api_tokenY !='') ?  true :  false;
            
        },
        */
		
		
    },
    
 

    /*
    computed: {
        //not used here
        BASE_URL () {
            return this.$store.state.passport_api_tokenY;  
        },
        
     
        //compute state.ifLogged based on state.passport_api_tokenY
        /*
        ifTokenExists(){
            if(state.passport_api_tokenY !=''){ 
                alert('good');
                return true;
            } else { 
                alert('bad');
                return false; 
            }            
        }
        
    }, 
    */
    
    /*
    created() {
        //state.ifLogged = (state.passport_api_tokenY !='') ? true : false;
        return commit('Fire', 'dataTestX' ); //fire muation
    },
    /*
    watch: {
        myZZZ() {
           //state.ifLogged = (state.passport_api_tokenY !='') ? true : false;
           return commit('Fire', 'dataTestX' ); //fire muation
        }
    },
    */
	
	//does not work
	/*
	watch: {
       //'state.passport_api_tokenY': function(newValue, oldValue) {
		getCart(oldValue, newValue) {
               alert(`Field changed for player from ${oldValue} to ${newValue}`);
        },
		'state.passport_api_tokenY': function() {
            alert("bbbbbbbbbbbbbbbbbbbbbbbbbbbbbbb");
        },
    },
	*/

	


    
    actions: {
		
		/* USED!!!!
        |--------------------------------------------------------------------------
        | Ajax request, get REST API located at => WpBlog_VueContoller/ function getAllPosts(), get all blog posts (non-admin section)
        |--------------------------------------------------------------------------
        |
        |
        */
	    getAllPosts({ commit, state  }) {  //state is a fix
        
            var thatX = this; //to fix context issue

	        $('.loader-x').fadeIn(800); //show loader
            //alert('start (True) Disable 2nd alert in AllPosts beforeMount');
            //alert( "Vuex store Passport token " + state.passport_api_tokenY);
            
            
            // Fetch method http variant (100% working)
            //Main differences between Axios method and Fetch method is:
            //1. that Axios wraps back-end REST API response in additional {data}, so where in Fetch we used {dataZ}, in Axios we have to use {dataZ.data} (in then(dataZ => {})
            //2. In Axios we do unlogging for Unauthenticated 401 requests in fail section {.catch(function(err){ }, while for Fetch in success {.then(dataZ => { if(dataZ.error == true|| dataZ.error == "Unauthenticated."){
                //In other words, when RST API return {Unauthenticated 401 requests }, Axios gets it in fail section, while Fetch in success

            /*
            fetch('api/post/get_all', { //fetch('api/post/get_all'?token=' + state.api_tokenY //http://localhost/Laravel+Yii2_comment_widget/blog_Laravel/public/post/get_all
                method: 'get',
                //pass Bearer token in headers ()
                headers: { 'Content-Type': 'application/json', 'Authorization': 'Bearer ' + state.passport_api_tokenY },
                //contentType: 'application/json',

            }).then(response => {
                $('.loader-x').fadeOut(800);  //hide loader
                return response.json(); //important Fetch feature //The .json method returns a promise to the parsed JSON, not the parsed JSON itself.
            }).then(dataZ => {
                console.log("Here STORE => " + dataZ);
		        //core rewritten, async getAllPosts, trigger mutation setPosts()
              
                if(dataZ.error == true|| dataZ.error == "Unauthenticated."){ //if Rest endpoint returns any predefined error
                    console.log(dataZ.data);
                    swal("Unauthenticated2", "Check Bearer Token2", "error");
                    
                    //Unlog the user if  dataZ.error == "Unauthenticated." || 401, otherwise if user has wrong password token saved in Locals storage, he will always recieve error and neber log out                  
                    //store.dispatch('LogUserOut');//this.$store.dispatch('LogUserOut'); //trigger Vuex function LogUserOut(), which is executed in Vuex store
                    //so far  can't fire store.dispatch('LogUserOut'), so do manually
                    alert('Vuex log out');
                    localStorage.removeItem('tokenZ'); //clear localStorage
                    localStorage.removeItem('loggedStorageUser_4279');
                    commit('LogOutMutation'); //reset state vars to store via mutation
                  
                } else if(dataZ.error == false){
              
                    swal("Done", "Articles are loaded (Vuex store).", "success");
	                return commit('setPosts', dataZ ); //sets ajax results to store via mutation
                }
            })
	        .catch(function(err){ 
                console.log("Getting articles failed ( in store/index.js). Check if ure logged =>  " + err);
                swal("Crashed", "You are in catch", "error");
            }); // catch any error
            */
            //End Fetch http variant (100% working)
            
            
            
            
            
            //Axios method http variant (100% working)
            //Main differences between Axios method and Fetch method is:
            //1. that Axios wraps back-end REST API response in additional {data}, so where in Fetch we used {dataZ}, in Axios we have to use {dataZ.data} (in then(dataZ => {})
            //2. In Axios we do unlogging for Unauthenticated 401 requests in fail section {.catch(function(err){ }, while for Fetch in success {.then(dataZ => { if(dataZ.error == true|| dataZ.error == "Unauthenticated."){
                 //In other words, when RST API return {Unauthenticated 401 requests }, Axios gets it in fail section, while Fetch in success
            
            axios({
                method: 'get', //you can set what request you want to be
                url: 'api/vue-crud/get_all',
                //data: {id: varID},
                headers: {
                    'Content-Type': 'application/json', 'Authorization': 'Bearer ' + state.passport_api_tokenY
                },
            })
            /*.then(response => {
                $('.loader-x').fadeOut(800);  //hide loader
                //return response.json(); //Fetch feature //In Axios responses are already served as javascript object, no need to parse, simply get response and access data.
                alert(1);
            }) */
            .then(dataZ => {
                //var dataZ = JSON.stringify(dataVV);
                console.log(dataZ);
                console.log("type is => " + typeof(dataZ));

                $('.loader-x').fadeOut(800);  //hide loader
                //alert(2);
                console.log("Here STORE => "   + dataZ.data.data[0].wpBlog_title);
                //console.log("Here STORE 2 => " + JSON.stringify(dataZ.data.data)); //works
		        //core rewritten, async getAllPosts, trigger mutation setPosts()
                //alert(3);
                console.log("dataZ.error " + dataZ.data.error);
                /*
                if(dataZ.data.error == true ||  dataZ.data.error == "Unauthenticated."){ //if Rest endpoint returns any predefined error
                    console.log("dataZ.data.error 2 " + dataZ.data.error);
                    swal("Unauthenticated2", "Check Bearer Token2", "error");
                    
                    //Unlog the user if  dataZ.error == "Unauthenticated." || 401, otherwise if user has wrong password token saved in Locals storage, he will always recieve error and neber log out                  
                    //store.dispatch('LogUserOut');//this.$store.dispatch('LogUserOut'); //trigger Vuex function LogUserOut(), which is executed in Vuex store
                    //so far  can't fire store.dispatch('LogUserOut'), so do manually
                    alert('Vuex log out');
                    localStorage.removeItem('tokenZ'); //clear localStorage
                    localStorage.removeItem('loggedStorageUser_4279');
                    commit('LogOutMutation'); //reset state vars to store via mutation
                  
                } else if(dataZ.data.error == false){ 
                    alert('dataZ.data.error 4 ' + dataZ.data.error);
                    swal("Done", "Articles are loaded (axios) (Vuex store).", "success");
	                return commit('setPosts', dataZ.data ); //sets ajax results to store via mutation
                }
                */
                
                //change for Axios
                if (dataZ.data.error == false){  //All Is OK
                    alert('dataZ.data.error 4 ' + dataZ.data.error + " Means all is OK");
                    swal("Done", "Articles are loaded (axios) (Vuex store).", "success");
	                return commit('setPosts', dataZ.data ); //sets ajax results to store via mutation
                }
				
				if (dataZ.data.error == true){  //If predefined error fires
                    swal("Stopped", dataZ.data.data, "error");
	                return false
                }
            })
	        .catch(function(err){ 
                $('.loader-x').fadeOut(800);  //hide loader
                console.log("Getting articles failed ( in store/index.js). Check if ure logged =>  " + err);
                swal("Crashed", "You are in catch", "error");
                alert("err " + err);
                
                //changes for Axios //Unlogg the user 
                if(err == "Error: Request failed with status code 401" ||  err == "Unauthenticated."){ //if Rest endpoint returns any predefined error
                    console.log("dataZ.data.error 2 " + err.error);
                    swal("Unauthenticated2", "Check Bearer Token2", "error");
                    
                    //Unlog the user if  dataZ.error == "Unauthenticated." || 401, otherwise if user has wrong password token saved in Locals storage, he will always recieve error and neber log out                  
                    //store.dispatch('LogUserOut');//this.$store.dispatch('LogUserOut'); //trigger Vuex function LogUserOut(), which is executed in Vuex store
                    //so far  can't fire store.dispatch('LogUserOut'), so do manually
                    alert('Vuex log out');
                    localStorage.removeItem('tokenZ'); //clear localStorage
                    localStorage.removeItem('loggedStorageUser_4279');
                    commit('LogOutMutation'); //reset state vars (state.passport_api_tokenY + state.loggedUser) via mutation
                    state.passport_api_tokenY = null;
           
                }
            }); // catch any error
 
            //End Axios http variant (% working)
      
        },
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		//not used below 
		/*
	    async getAllPosts({ commit }) { 
	        return commit('setPosts', await fetch('http://localhost/Laravel+Yii2_comment_widget/blog_Laravel/public/post/get_all') )
            //return commit('setPosts', await api.get('/post/get_all'))
        }, */
	  
        //on Login success save data to Store (trigger mutation)
        changeVuexStoreLogged({ commit }, dataTestX) { 
            return commit('setLoginResults', dataTestX ); //sets dataTestX to store via mutation
        },
        
        //NOT USED HERE??????      
        //working example how to change Vuex store from child component //Catch a passed api token from VueRouterMenu, triggered in beforeMount()
	    changeVuexStoreTokenFromChild({ commit }, dataTestX) { 
	        //var dataTest = {"error":false,"data":[{"wpBlog_id":1,"wpBlog_title":"Dima", "wpBlog_text":"Store 1", "get_images":[]}, {"wpBlog_id":2,"wpBlog_title":"Dima 2", "wpBlog_text":"Store 2", "get_images":[]}]};
	        alert('store token ' + dataTestX);
		    return commit('setApiToken', dataTestX ); //sets dataTestX to store via mutation
	    },
      
      
           
       
        
        
       /*
        |--------------------------------------------------------------------------
        | Logging user out, triggered in /subcomponents/logged.vue (subcomponent of Login_component.vue )
        |--------------------------------------------------------------------------
        |
        |
        */
        
        LogUserOut ({ commit }) { 
            alert('Vuex log out');
            localStorage.removeItem('tokenZ'); //clear localStorage
            localStorage.removeItem('loggedStorageUser_4279');
            commit('LogOutMutation'); //reset state vars to store via mutation
            
        },
      
      
      
      
      
        //For mutation to set a quantity of found posts(in Admin Part). Fired in list_all. passedArgument is an arg passed in list_all.vue
        setPostsQuantity ({ commit, state  }, passedArgument) {  //state is a fix
            return commit('setQuantMutations', passedArgument ); //to store via mutation
        },
	  
	    //working example how to change Vuex store from child component
	    /*
	    changeVuexStoreFromChild({ commit }, dataTestX) { 
	        //var dataTest = {"error":false,"data":[{"wpBlog_id":1,"wpBlog_title":"Dima", "wpBlog_text":"Store 1", "get_images":[]}, {"wpBlog_id":2,"wpBlog_title":"Dima 2", "wpBlog_text":"Store 2", "get_images":[]}]};
	        console.log(dataTestX);
		    return commit('setPosts', dataTestX ); //sets dataTestX to store via mutation
	    } 
	    */
      
	  
	},

    
	/*
     |--------------------------------------------------------------------------
     | Mutation section
     |--------------------------------------------------------------------------
     |
     |
     */

    mutations: {
		//USED!!
        setPosts(state, response) {  
            console.log('Set posts mutation successfully');
            state.posts = response.data/*.data*/;
	        console.log('setPosts executed in store ' + response.data[0]['wpBlog_title'] + " state=> " + state.posts[0]['wpBlog_title'] + " id: " + state.posts[0]['wpBlog_id'] );
        },
     
        //mutation to set api token to STORE. NOT USED?????
        setApiToken(state, response) {   
            state.api_tokenY = response;
	        console.log('setApiToken executed in store' + response + ' Store => ' + state.api_tokenY);
            console.log('set apiToken mutation is done');
        },
    
        //mutation to quantity of Blog to STORE
        setQuantMutations(state, myPassedArg) {
            state.adm_posts_qunatity = myPassedArg;        
        },
        
        
        
        //on Login success save data to Store (trigger mutation)
        setLoginResults (state, response) { 
            
            //sets user's array to Vuex store object(state.state.loggedUser). Is gotten from /subcomponents/login.vue ajax 
            localStorage.setItem('loggedStorageUser_4279', JSON.stringify(response.user)); //use {JSON.stringify} to save JS type:Object (i.e converts Object to string) //saves to localStorage to not reset data on every F5        
            state.loggedUser = response.user;  //sets Vuex user Object (JS type:Object) {name: '', email: ''} 


            //sets the passport api token to Vuex store(state.passport_api_tokenY). Is gotten from /subcomponents/login.vue ajax 
            //Vue.set(state, passport_api_token, response.token);
            localStorage.setItem('tokenZ', response.token); //saves to localStorage to not reset data on every F5        
            state.passport_api_tokenY = response.token;
			//Vue.set(state.passport_api_tokenY, response.token);

            
            alert('Logged successfully');
            alert("passport_api_tokenY type is " + typeof state.passport_api_tokenY);
	        console.log('setApiToken executed in store' + response + ' Store => ' + state.passport_api_token);
            console.log('set apiToken mutation is done. localStorage is ' + localStorage.getItem('tokenZ'));
        },
        
        
        
        
        //Log out mutation (clear state.passport_api_tokenY +  state.loggedUser vars) 
        LogOutMutation(state) {
            state.passport_api_tokenY = null;
            state.loggedUser          = {}; 
            alert("passport_api_tokenY type is logged out " + typeof state.passport_api_tokenY);            
        },
    },
    strict: debug
});




  


	 
 
 
