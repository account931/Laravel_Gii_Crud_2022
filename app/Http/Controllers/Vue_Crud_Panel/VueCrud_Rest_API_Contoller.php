<?php
//https://medium.com/js-dojo/build-a-simple-blog-with-multiple-image-upload-using-laravel-vue-5517de920796

namespace App\Http\Controllers\Vue_Crud_Panel;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Storage;
use Illuminate\Support\Facades\DB;
use App\models\wpBlogImages\Vue_API_Models\Wpress_images_Posts; //model for all posts
use App\models\wpBlogImages\Vue_API_Models\Wpress_images_Category; //model for all Wpress_images_Category
use App\User; 
use App\Http\Requests\Wpress_Images\Vue_API_Requests\SaveNewWpressImages_ApiRequest;
use App\Http\Requests\Wpress_Images\Vue_API_Requests\UpdateRecordWpressImages_ApiRequest;
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
	
	
	

	

	
	
	/**
     * REST API to /POST (create) a new blog. 
     * Ajax Requst comes by button click from \resources\assets\js\WpBlog_Vue\components\pages\loadnew.vue
     * @param SaveNewWpressImagesRequest $request
     * @return json
     */
	public function createPost(SaveNewWpressImages_ApiRequest $request) //Request Class //SaveNewWpressImagesRequest
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
        //dd(\Auth::user()->id);
		$userX = Auth::user();  //getting the logged user Object, version for Passport (won't work without in /routes/api.php => 'middleware' => ['auth:api', 'myJsonForce' ])
		//dd($userX);
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
        
        $data       = array($request->title, $request->body, $request->category_sel); //$request->all(); //$request->input();
		$imagesData = $request->imagesSet; //uploaded images//$request->myImages
		
        //return response()->json(['error' => false, 'data' => 'Too Good, but process back-end validation : ' . $request->title .  ' / ' .  $request->body . '/UserID:' . $userX->id  . '/' . $request->bearerToken()]);

	    try{
			$ticket = new Wpress_images_Posts();
			if($b = $ticket->saveFieldsRestApi($data, $imagesData, $userX->id)){ //$b = 'image1.jpg, image2.jpg'
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
	
	
	
	
	
	/**
     * Admin REST API endpoint to /GET get one blog/item by ID. Used in edit/update Form.
     * Ajax Requst comes from ........../resources/assets/js/WpBlog_Admin_Part/components/pages/editItem.vue. Triggered automatically in beforeMount()
     * @return \Illuminate\Http\Response
     */
    public function getEditOneItem($idX)
    {   
	    if (!Wpress_images_Posts::where('wpBlog_id', $idX)->exists()) { 
		    return response()->json(['error' => true, 'data'   => "Article does not exist"]);
		}
		
        $posts = Wpress_images_Posts::with('getImages', 'authorName', 'categoryNames')->where('wpBlog_id', $idX)->orderBy('wpBlog_created_at', 'desc')->get(); // hasMany/belongTo Eager Loading
		return response()->json(['error' => false, 'data' => $posts]);
		
    }
	
	
	
	
	
	
	
	 /**
     * Admin REST API endpoint to Update/Edit one blog/item by ID, used via  /PUT . Triggered by Edit/Update Form "Submit" button.
     * Ajax Requst comes from ........../resources/assets/js/WpBlog_Admin_Part/components/pages/editItem.vue. Triggered by clicking Form "Submit" button
     * @param $idX, an id of edited post, set in URL(in editItem.vue) like this 'api/post/admin_get_one_blog/' + idZ 
     * @param $request, example of request => [ "title" => "TTTTTTTTT", "body" => "JavaScript Tutorial", "selectV" => "3", "imageToDelete" => "66", "_method" => "PUT", "imagesZZZ" => array:1 [0 => UploadedFile {#1172, -originalName: "2254.png", -mimeType: "image/png", -size: 30871} ] 
     * @return \Illuminate\Http\Response
     */
    public function AdminUpdateOneItem($idX, UpdateRecordWpressImages_ApiRequest $request)  //Request Class validation //Request $request
    {   
         //Due to overridded {function failedValidation(Validator $validator)} in RequestClass, we can proceed here, even if Validation fails
        if (isset($request->validator) && $request->validator->fails()) {
           //return response()->json($request->validator->messages(), 400);
           return response()->json([
               'error' => true, 
               'data' => 'Was seem to be OK, but validation crashes', 
               'validateErrors'=>  $request->validator->messages()]);
        }
        
        
        //Below is just for testing ------
        /*
        //Old images User clicked to delete (while editing)
        $imageToDelete = ' User while updating requested to delete Images: '; 
        
        if ($request->has('imageToDelete')){
            //convert string {$request->imageToDelete} to array
            $del = explode(" ", $request->imageToDelete); // for bizzare reason {$request->imageToDelete) comes to Server as string not array 
            foreach($del as $d){
                $imageToDelete.= $d;    
            }
        } else {
            $imageToDelete.= ' Zero old images ';
        }
        
        //New images uploaded by User (while editing)
        $imagesNew = ' User Uploaded new Images: '; 
        
        if (isset($request->imagesZZZ)){
            foreach($request->imagesZZZ as $d){
                $imagesNew.= " " . $d;    
            }
        } else {
            $imagesNew.= ' Zero new images ';
        }
        
        return response()->json([
            'error' => false, 
            'data' => 'Update is OK. Implement me further. Your ID ' . $idX . ' TITLE: ' . 
                      $request->title . ' ' . $request->body . ' Category: ' . $request->selectV . ' ' .
                      $imageToDelete . ' / ' .
                      $imagesNew
        ]);
        */
        //End Below is just for testing --------
        
		//check if record with this id exists
        if (!Wpress_images_Posts::where('wpBlog_id', $idX)->exists()) { 
		    return response()->json(['error' => true, 'data'   => "Article u want to update does not exist"]);
		}

        
        $model = new Wpress_images_Posts();
        
        //Updates one post/item in DB 'wpressimages_blog_post'
        $updatePost  = $model ->updatePostItem($idX, $request);
        
        //Saves new images (if any) to DB Wpress_ImagesStock (new images that a user uploaded while updtaing/editing a post)
        $uploadNewImg = $model ->updateNewImages($idX, $request);
        
        //Deletes old images (if any) to DB DB Wpress_ImagesStock (old images that a user wished to delete while updtaing/editing a post)
        $deleteOldImg = $model ->deleteOldImages($idX, $request);
        
        
        return response()->json([
            'error' => false, 
            'data' => 'Successfully updated. </br>' . 
                    $updatePost   . ' </br> ' .  //Title: 'xxx', body: 'xxx', category: 'xxx'
                    $uploadNewImg . ' </br> ' .  //'User Uploaded new Images' || 'User did not loaded new images'
                    $deleteOldImg                //'While updating a user requested to delete Images' || 'User did not opted to delete any old images'
        ]);
        
    }
	
    
    

	
}
