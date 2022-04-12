<?php
//https://medium.com/js-dojo/build-a-simple-blog-with-multiple-image-upload-using-laravel-vue-5517de920796

namespace App\Http\Controllers\Vue_Crud_Panel;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Storage;
use Illuminate\Support\Facades\DB;
use App\models\wpBlogImages\Wpress_images_Posts; //model for all posts
use App\models\wpBlogImages\Wpress_images_Category; //model for all Wpress_images_Category
use App\User; 
use App\Http\Requests\SaveNewArticleRequest;
use App\Http\Controllers\Controller; //to use subfolder

class VueCrud_Rest_API_Contoller extends Controller
{
    public function __construct(){
		//$this->middleware('auth');
        //dd(auth()->user()->id);
                
	}
	
	
	
	/**
     * REST API endpoint to /GET all posts
     * Ajax Requst comes automatically onLoad from /assets/js/store/index.js. Triggered in beforeMount(this.$store.dispatch('getAllPosts');) in \resources\assets\js\WpBlog_Vue\components\pages\blog_2021.vue
     * @return json
     */
	public function getAllPosts(Request $request) //http://localhost/Laravel+Yii2_comment_widget/blog_Laravel/public/post/get_all
    {   
        //dd(\Auth::user()->id); //works
        
        
        //VERSION_1 when u pass token in ajax URL as ?token=XXXX (in Vuex store /store/index.js)
        /*if($_GET['token'] == ''){ 
            return response()->json(['error' => true, 'data' =>  ' Token is missing' ]);
        }
        
        if (!User::where('api_token', $_GET['token'])->exists()){
            return response()->json(['error' => true, 'data' =>  ' Token is not valid' ]);
        } */           
        
        
        
        
        //VERSION_2(MAIN) when u pass Bearer token in Headers via (either in ajax or php middleware/AccsessToken Middleware)(in final version pass it in ajax in /store/index.js)
        //When use in /routes/api Route::group(['middleware' => ['auth:api'] + middleware/MyForceJsonResponse below checking won't work(except for positive one) as it is done automatically
        //When use in /routes/api Route::group(['middleware' => ['api'] below checking will work (will be no automatical check)
        //gets the Bearer token
        
        /*
        $token = ($request->bearerToken() != null) ? $request->bearerToken() : "no tokennnn";//works
        //dd($token);
        
        
        if($token == ''){
            return response()->json(['error' => true, 'data' =>  'Bearer Token is  missing' ]);
        }
        
        
        
        //verify if Bearer token is correct. Works
        if(!Auth::guard('api')->check()){
            return response()->json(['error' => true, 'data' => 'Bearer ' .$token . ' is  wrong' ]);
        } 
        
       
        
        if(Auth::guard('api')->check()){
            //dd($token . " is  correct");
            return response()->json(['error' => true, 'data' => 'Bearer ' . $token . ' is  correct' ]);
        } else {
            return response()->json(['error' => true, 'data' => 'Bearer ' . $token . ' is  wrong' ]);
            //dd($token . " is  wrong"); //works only if 'auth:api' middleware is off
        }
        */
        
        
        //var_dump(getallheaders());
        //dd($_GET);
        /*
        if(!isset($_GET['api_token'])){
            return response()->json(['error' => true, 'data' =>'Where is the token? ' ]);
        }
        
        $one = User::where('api_token', '=', $_GET['api_token'])->first();
        if(!$one){
            return response()->json(['error' => true, 'data' =>"Token is not correct"] );
        } else {
             return response()->json(['error' => true, 'data' =>"Token is Good" ]);
        }
        */
        
        $posts = Wpress_images_Posts::with('getImages', 'authorName', 'categoryNames')->orderBy('wpBlog_created_at', 'desc')->get(); //->with('getImages', 'authorName', 'categoryNames') => hasMany/belongTo Eager Loading
        return response()->json(['error' => false, 'data' => $posts]);
    }
	
	
	
	
	
		
	/**
     * REST API endpoint to /GET all DB table categories (to build <select> in loadnew.vue)
     * Ajax Requst comes automatically onLoad (is in section {mounted ()}) from \resources\assets\js\WpBlog_Vue\components\pages\loadnew.vue
     * @return json
     */
	public function getAllCategories() 
    { 
        $posts =  Wpress_images_Category::all();//gets categories for dropdown select
        return response()->json(['error' => false, 'data' => $posts]);
    }
	
	
	
	
	
	
	
	//NOT USED BELOW!!!!!!!
	
	
	
	
	
	
	
	
	
	
	
	/**
     * REST API to /POST (create) a new blog. 
     * Ajax Requst comes by button click from \resources\assets\js\WpBlog_Vue\components\pages\loadnew.vue
     * @param SaveNewArticleRequest $request
     * @return json
     */
	public function createPost(SaveNewArticleRequest $request) //Request Class //SaveNewArticleRequest
    {
        
        //var_dump($request->imagesZZZ[0]->getClientOriginalName(), true);  //$request->all()
        //die();
        
        /*
        //Getting info of uploaded images (for test purpose). Working.
        if($request->has('imagesZZZ')) { //(for test purpose)
            $b = 'Image is isset';
        } else {
            $b = 'Image not isset';
        }
      
        //Getting info of uploaded images (for test purpose). Working.
        $tt = '';
        if($request->hasFile('imagesZZZ')) {
            foreach($request->imagesZZZ as $z) { 
                $tt.= $z->getClientOriginalName() . ', ';
                $tt.= round( ($z->getSize() / 1024), 2 ). ' kilobyte. /';
                $tt.= $z->getClientOriginalExtension() . ' / ';
            }
        } else {
            $tt = "Images files are not sent";
        }
        return response()->json(['error' => false, 'data' => 'Too Good  : ' . $b . " My FILES: " . $tt ]);
        */
        
        
        //Due to overridded {function failedValidation(Validator $validator)} in RequestClass, we can proceed here, even if Validation fails
        if (isset($request->validator) && $request->validator->fails()) {
           //return response()->json($request->validator->messages(), 400);
           return response()->json([
               'error' => true, 
               'data' => 'Good, but validation crashes', 
               'validateErrors'=>  $request->validator->messages()]);
        }
        
		/*
		header('Access-Control-Allow-Origin:  *');
        header('Access-Control-Allow-Methods:  POST, GET, OPTIONS, PUT, DELETE');
        header('Access-Control-Allow-Headers:  Content-Type, X-Auth-Token, Origin, Authorization');
	    */
       
        //find User Id by his sent token. NB: was used (& 100% worked) in pre-Passport version {Laravel_Vue_Blog}, now reassigned to Passport
        //$userX = User::where('api_token', '=', $request->bearerToken())->first(); //$request->bearerToken() is an access token sent in headers in ajax
        
		$userX = Auth::user();  //getting the logged user Object, version for Passport
		
		//return response()->json(['error' => false, 'data' => 'Too Good, but process back-end validation : ' . $request->title .  ' / ' .  $request->body . '/UserID:' . $userX->id  . '/' . $request->bearerToken()]);
	    //return response()->json(['error' => false, 'data' => 'Too Good, but process back-end validation : ' . $request->bearerToken()]);

        //dd($request->all());
        
        
        
        /*
        //just for test, get uploaded images from array
        $requestText = '';
        foreach($request->myImages as $v){
            $requestText.= $v . ", " ;
        } 
      
        return response()->json(['error' => false, 'data' => 'Too Good, back-end validation is OK. Imaged : ' . $requestText ]);
        */
        
        $data       = array($request->title, $request->body, $request->selectV); //$request->all(); //$request->input();
		$imagesData = $request->imagesZZZ; //uploaded images//$request->myImages
		
        //return response()->json(['error' => false, 'data' => 'Too Good, but process back-end validation : ' . $request->title .  ' / ' .  $request->body . '/UserID:' . $userX->id  . '/' . $request->bearerToken()]);

	    try{
			$ticket = new Wpress_images_Posts();
			if($b = $ticket->saveFields($data, $imagesData, $userX->id)){ //$b = 'image1.jpg, image2.jpg'
			   return response()->json(['error' => false, 'data' => 'Post was saved successfully with connected images: ' . $b]);
            } else {
                return response()->json(['error' => true, 'data' => 'Saving failed']);
            }
			
		} catch(Exception $e){
			return response()->json(['error' => true, 'data' => 'Saving failed2']);
		}
        
        
        
               
        /*
        $data       = $request->input();
		$imagesData = $request->filename; //uploaded images
		
	    try{
			$ticket = new Wpress_images_Posts();
			$ticket->saveFields($data, $imagesData);
			//return redirect('/wpBlogImages')->with('flashMessage',"Created successfully");
            return response()->json(['error' => false, 'data' => 'Saved successfully']);
			
		} catch(Exception $e){
			//return redirect('/createNewWpressImg')->with('success',"Operation failed");
            return response()->json(['error' => true, 'data' => 'Error while saving']);

		}
        */
        
        //dd($request->all());
		
		/*
        DB::transaction(function () use ($request) {
            $user = Auth::user();
            $title = $request->title;
            $body = $request->body;
            $images = $request->images;

            $post = Wpress_images_Posts::create([
                'title' => $title,
                'body' => $body,
                'user_id' => $user->id,
            ]);
            // store each image
            foreach($images as $image) {
                $imagePath = Storage::disk('uploads')->put($user->email . '/posts/' . $post->id, $image);
                PostImage::create([
                    'post_image_caption' => $title,
                    'post_image_path' => '/uploads/' . $imagePath,
                    'post_id' => $post->id
                ]);
            }
        });
        return response()->json(200);
		*/
    }
	
	
	

	
}
