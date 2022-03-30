<?php

namespace App\Http\Controllers\Crud_Simple;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller; //to use subfolder
use App\models\wpBlogImages\Wpress_images_Posts; //model for all posts
use App\models\wpBlogImages\Wpress_images_Category; //model for all Wpress_images_Category

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
		$model = new Wpress_images_Posts();
		
		$postsAll = Wpress_images_Posts::with('getImages', 'authorName', 'categoryNames')->orderBy('wpBlog_created_at', 'desc')->paginate(5);/*->get()*/; //->with('getImages', 'authorName', 'categoryNames') => hasMany/belongTo Eager Loading
        return view('crud_simple.index')->with(compact('postsAll', 'model'));
    }
}
