<?php
//Used for Crud simple(Web) only 
//used to validate via Request Class (used to create new blog & images in tables {wpress_images_blog_post} & {wpress_image_images_stocks})
//used in WpBlogImages /public function store(SaveNewWpressImagesRequest $request)
//Used for validation via Request Class, not via controller

namespace App\Http\Requests\Wpress_Images;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule; //for in: validation
use App\models\wpBlogImages\Wpress_images_Category; //model for DB table {wpressimage_category} for Range in: validation

class SaveNewWpressImagesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        //return false; //return False will stop everything
		return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
		
		//getting all existing categories from DB {wpressimage_category}, get from DB only column "id". Used for validation in range {Rule::in(['admin', 'owner']) ]}, ['13', '17']
		$existingRoles = Wpress_images_Category::select('wpCategory_id')->get(); 
		$rolesList  = array(); // array to contain all roles id  from DB in format ['13', '17']
		foreach($existingRoles as $n){
			array_push($rolesList, $n->wpCategory_id);	
		}
		
		//dd($rolesList);
		
        return [
		    'title'        => 'required|string|min:3|max:255',
		    'body'         => 'required|string|min:5|max:255',
			'category_sel' => ['required', 'string', Rule::in($rolesList) ],  //integer];  //'category_sel' => ['required', 'string', Rule::in(['admin', 'second-zone']) ] , //integer];
            
			//image validation https://hdtuto.com/article/laravel-57-image-upload-with-validation-example
			//'filename' => ['required', /*'image',*/ 'mimes:jpeg,png,jpg,gif,svg', 'max:2048' ], // 'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',,
		    
			'imagesSet'   => 'required|array', //'required|array', 
            'imagesSet.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048' //min:2048
		];

		
    }
	
	
	
	
	/**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
		
		
        // use trans instead on Lang 
        return [
           //'username.required'  => Lang::get('userpasschange.usernamerequired'),
		   'title.required'       => 'Kindly asking for a title',
	       'body.required'        => 'We need u to specify the article text',
		   'body.min'             => 'We kindly require more than 5 letters for article text',
		   'category_sel.in'      => 'Category has invalid value range', //range validation
		   'imagesSet.required'   => 'Image is very much required',
		   'imagesSet.*.image'    => 'Make sure it is an image',
		   'imagesSet.*.mimes'    => 'Images must be .jpeg, .png, .jpg, .gif, .svg file. Max size is 2048',
		   'imagesSet.max'        => 'Sorry! Maximum allowed size for an image is 2MB',
		   //'filename.min'       => 'Your image is too small',
		];
	}
	 
	 
	 
    /**
     * Return validation errors. 
     *
     * @param Validator $validator
     */
    public function withValidator(Validator $validator)
    {
	
	    if ($validator->fails()) {
            return redirect('/createNewWpressImg')->withInput()->with('flashMessageFailX', 'Validation Failed!!!' )->withErrors($validator);
        }
	}
	 
	

   
	 
}
