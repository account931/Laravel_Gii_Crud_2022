<?php

namespace App\Http\Controllers\Crud_Simple;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
//use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller; //to use subfolder
use App\models\wpBlogImages\Wpress_images_Posts; //model for all posts
use App\models\wpBlogImages\Wpress_images_Category; //model for Wpress_images_Category
use App\models\wpBlogImages\Wpress_ImagesStock;    //model for 
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Wpress_Images\SaveNewWpressImagesRequest; //my custom Form validation via Request Class (to create new blog & images in tables {wpressimages_blog_post} & {wpressimage_imagesstock})
use App\Http\Requests\Wpress_Images\UpdateRecordWpressImagesRequest; //my custom Form validation via Request Class (to editw blog & images in tables {wpressimages_blog_post} & {wpressimage_imagesstock})
use Spatie\Permission\Models\Role;       //Spatie RBAC built-in model
use Spatie\Permission\Models\Permission; //Spatie RBAC built-in model
	
class CrudController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
		$model       = new Wpress_images_Posts(); //model
		$categories = Wpress_images_Category::all();//gets categories for dropdown select
		
	    //if no GET find all articles with pagination
	    if (!isset($_GET['category'])){ 
		    //found articles with pagination
            $postsAll      = Wpress_images_Posts::with('getImages', 'authorName', 'categoryNames')->orderBy('wpBlog_created_at', 'desc')->paginate(5);/*->get()*/; //->with('getImages', 'authorName', 'categoryNames') => hasMany/belongTo Eager Loading			//count found articles
			$countArticles = Wpress_images_Posts::where('wpBlog_status', '1')->get();
		}
		
		//if isset GET, found by category, with pagination
		if(isset($_GET['category'])){
			//found articles with pagination
			$postsAll      = Wpress_images_Posts::where('wpBlog_status', '1')->with('getImages', 'authorName', 'categoryNames')->where('wpBlog_category', $_GET['category'] )->orderBy('wpBlog_created_at', 'desc')->paginate(5);/*->get()*/; //->with('getImages', 'authorName', 'categoryNames') => hasMany/belongTo Eager Loading			//count found articles
		    //count found articles
			$countArticles = Wpress_images_Posts::where('wpBlog_status', '1')->where('wpBlog_category', $_GET['category'] )->get();

		}
		
        return view('crud_simple.index')->with(compact('postsAll', 'model', 'countArticles', 'categories'));
    }
	
	
	
	
	/**
     * View One Article
     * @param  integer $id
     * @return \Illuminate\Http\Response
	 *
     */
	public function viewOne($id) {
	   //additional check in case user directly intentionally navigates to  ../blog_Laravel/public/delete/12 to not his record
	   try{
	       $articleOne = Wpress_images_Posts::where('wpBlog_id',$id)->with('getImages', 'authorName', 'categoryNames')->firstOrFail(); //find the article by id  ->firstOrFail();
	   } catch (\Exception $e) {
	   //if(!$articleOne){
	      throw new \App\Exceptions\myException('Article does not exist');
	   }

	   $articleOne = Wpress_images_Posts::where('wpBlog_id',$id)->with('getImages', 'authorName', 'categoryNames')->get(); //dd($articleOne);
	   
	   return view('crud_simple.viewOne',  compact('articleOne'));
	}
	
	
	
	/**
     * Show the form to create new entry.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {    
	    if(!Auth::check()){
			$text = 'You are not logged, <a href="'. route('login') . '"> click here  </a>  to Login first';
		    throw new \App\Exceptions\myException($text); 
		}
	    $categories = Wpress_images_Category::all(); //for dropdown
		return view('crud_simple.create',  compact('categories'));
	}
	
	
	
	
	
	/**
     * Store a newly created resource in storage. Validation via request Class
     *
     * @param  SaveNewWpressImagesRequest $request       \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SaveNewWpressImagesRequest $request)
    {
		//dd($request->all()); return false;
	    
		/*
	    if(!Auth::user()->hasRole('admin')){ //arg $admin_role does not work
           throw new \App\Exceptions\myException('You have No rbac rights to Admin Panel');
		}
		
		//if $_POST['productID'] is not passed. In case the user navigates to this page by enetering URL directly, without submitting from with $_POST
		if(!$request->input('product-desr')){
			throw new \App\Exceptions\myException('Bad request, You are not expected to enter this page.');
		}
		*/
		
		/*
		if (empty($request->filename)) {
            return redirect()->back()->withErrors(['msg', 'The Message']);
        } */
		
		
		//dd($request->filename); //(DONT USE $request->input('filename') as IT WON"T WORK)
	
	    
	    
        $data       = $request->input();
		$imagesData = $request->filename; //uploaded images
		
	    try{
			$ticket = new Wpress_images_Posts();
			$ticket->saveFields($data, $imagesData);
			return redirect('/crud-simple')->with('flashMessageX', "Article was created successfully");
			
		} catch(Exception $e){
			return redirect('/crud-simple')->with('flashMessageFailX', "Operation failed");
		}
		
    }
	
	
	
	
	/**
     * Edit a selected record, displays edit form
     *
     * @param  integer $id
     * @return 
     */
	public function edit($id) {
      
	    //additional check in case user directly intentionally navigates to  ../blog_Laravel/public/delete/12 to not his record
	   try{
	       $articleOne = Wpress_images_Posts::where('wpBlog_id',$id)->with('getImages', 'authorName', 'categoryNames')->firstOrFail(); //find the article by id  ->firstOrFail(); //eager loading relations
	   } catch (\Exception $e) {
	   //if(!$articleOne){
	      throw new \App\Exceptions\myException('Article does not exist');
	   }
	   
	   /*
	   if( !Auth::check() || $articleOne->wpBlog_author!= auth()->user()->id){
		   throw new \App\Exceptions\myException('It is not your article to edit');
	   } */
	   	//additional check in case user directly intentionally navigates to  ../blog_Laravel/public/delete/12 to not his record

			
			
	   $articleOne = Wpress_images_Posts::where('wpBlog_id',$id)->get();
	   $categories = Wpress_images_Category::all(); //for dropdown
	   
	   return view('crud_simple.edit',  compact('articleOne', 'categories'));

	   
    }
	
	
	
	
	
	
	
	/**
     * update a selected record via PUT and return to View one page (or to previous edit form if fail)
     *
     * @param UpdateRecordWpressImagesRequest $request
     * @return 
     */
	public function updatePost(UpdateRecordWpressImagesRequest $request) { /* Request $request, $id */ 
      
	   //dd($request->all());
	   //dd($_POST['blog_id_field']); //id to update
	   //REWORK BELOW!!!
	   
	   
	   
	   //additional check in case user directly intentionally navigates to  ../blog_Laravel/public/delete/12 to not his record
	   try{
	       $articleOne = Wpress_images_Posts::where('wpBlog_id', $_POST['blog_id_field'])->firstOrFail(); //find the article by id  ->firstOrFail();
	   } catch (\Exception $e) {
	   //if(!$articleOne){
	      throw new \App\Exceptions\myException('Article does not exist');
	   }
	   
	   /*
	   if( !Auth::check() || $articleOne->wpBlog_author!= auth()->user()->id){
		   throw new \App\Exceptions\myException('It is not your article to edit');
	   } */
	   	//additional check in case user directly intentionally navigates to  ../blog_Laravel/public/delete/12 to not his record
		
	   /*
	   //validation rules
        $rules = [
			'description' => 'required|string|min:3|max:255',
			'title' => 'required|string|min:3|max:255',
			'category_sel' => 'required|integer'
		];
		
		$validator = Validator::make($request->all(),$rules);
		if ($validator->fails()) {
			return redirect()->back()
			->withInput()
			->withErrors($validator);
		}
		else{
            $data = $request->input();
			try{
			
				
				Wpress_images_Posts::where('wpBlog_id', $id)->update([  'wpBlog_text' => $data['description'], 'wpBlog_title' => $data['title'], 'wpBlog_category' => $data['category_sel'] ]);
                return redirect()->back()->with('success',"Update successfully");
				
			}
			catch(Exception $e){
				return redirect()->back()->with('success',"Update failed");
			}
		}  */
		
		
		$data       = $request->input();   //form inputs, except for <input type="file">, i.e images
		$imagesData = $request->filename; //uploaded images
		//dd($imagesData);
		
	    try{
			$model = new Wpress_images_Posts();
			$r = $model->updateFields($data, $imagesData, $articleOne, $request);
			return redirect('/wpBlogImagesOne/' . $_POST['blog_id_field'] )->with('flashMessageX', "Updated successfully " . $r ); //<a href="{{route('gii-edit-post', ['id' => $a->wpBlog_id])}}">
			
		} catch(Exception $e){
			return redirect()->back()->with('flashMessageFailX', "Update operation failed");
		}

	   
    }
	
	

	
	
	/**
     * Delete a selected record. Used to work via $_GET,  but changed to S_POST due security reason-
     *
     * @param  integer $id
     * @return 
     */
	public function deleteOneItem(/*$id*/) {
       /*DB::delete('delete from Wpress_images_Posts where wpBlog_id = ?',[$id]);
       echo "Record deleted successfully.";
       echo 'Click Here to go back.';
	   */
	   
	   $id = $_POST['blog_id_field'];
	   //dd($id);
	   
	   //additional check in case user directly intentionally navigates to  ../blog_Laravel/public/delete/12 to not his record
	   try{
	       $articleOne = Wpress_images_Posts::where('wpBlog_id', $id)->firstOrFail(); //find the article by id  ->firstOrFail();
	   } catch (\Exception $e) {
	   //if(!$articleOne){
	      throw new \App\Exceptions\myException('Article does not exist');
	   }
	   
	  
	   /*
	   if( !Auth::check() || $articleOne->wpBlog_author!= auth()->user()->id){
		   throw new \App\Exceptions\myException('It is not your article');
	   } */
	   
	    if ($articleOne->getImages->isEmpty()){
            //$text.= ' No images connected to post found. ';
        } else {
            foreach($articleOne->getImages as $f){
                //$text.= " Id to delete: " . $f->wpImStock_id . " ";
                
                
                //Delete relevant images from folder 'images/wpressImages/'
                if(file_exists(public_path('images/wpressImages/' .  $f->wpImStock_name))){
		            \Illuminate\Support\Facades\File::delete('images/wpressImages/' . $f->wpImStock_name);
		        }
                
                //Delete relevant images from DB table {Wpress_ImagesStock} (images connected to post blog). Not much required as due to relation connected images are delete from DB {Wpress_ImagesStock} automatically
                $img = Wpress_ImagesStock::findOrFail($f->wpImStock_id); 
                $img->delete();
                
            }
                
        }
		
		
	   //Wpress_images_Posts::where('wpBlog_id',$id)->delete();
	   $articleOne->delete(); //delete the Post itself from DB  {Wpress_images_Posts}  
	   return redirect('/crud-simple')->with('flashMessageX', "Record " . $id  . " was deleted successfully");

	   
    }
	
	
	
	
	
	
	
	
	
	
	
}
