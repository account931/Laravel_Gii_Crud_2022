<?php

namespace App\Http\Controllers\Vue_Crud_Panel;

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
	
class VueCrudController extends Controller
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
		
		
        return view('vue_crud_panel.index'); //->with(compact('postsAll', 'model', 'countArticles', 'categories'));
    }
	

	
}
