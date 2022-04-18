<?php
//Used for Vue Crud Panel only (Api)
//used to validate via Request Class (used to edit blog & images in tables {wpress_images_blog_post} & {wpress_image_images_stocks})
//used in WpBlogImages /public function updatePost(UpdateRecordWpressImagesRequest $request)
//Used for validation via Request Class, not via controller

namespace App\Http\Requests\Wpress_Images\Vue_API_Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule; //for in: validation
use App\models\wpBlogImages\Vue_API_Models\Wpress_images_Category; //model for DB table {wpressimage_category} for Range in: validation

class UpdateRecordWpressImages_ApiRequest extends FormRequest   //UpdateRecordWpressImagesRequest
{
	public $validator = null; //must have to override Return validation errors. In this case it will return and exucute code in Controller, even if Request Validation fails
	
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
		    
			//image file is required is only if checkbox 'remember' is unticked
			'imagesSet'   => 'required_without:remember|array', //'required|array',  //'required_without:remember'
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
           //'username.required'         => Lang::get('userpasschange.usernamerequired'),
		   'title.required'              => 'Kindly asking for a title',
	       'body.required'               => 'We need u to specify the article text',
		   'body.min'                    => 'We kindly require more than 5 letters for article text',
		   'category_sel.in'             => 'Category has invalid value range', //range validation
		   //'filename.required'         => 'Image is very much required', 
		   'imagesSet.required_without:remember'   => 'Image is very much required unless you tick selectbox', 
		   'imagesSet.*.image'            => 'Make sure it is an image',
		   'imagesSet.*.mimes'            => 'Images must be .jpeg, .png, .jpg, .gif, .svg file. Max size is 2048',
		   'imagesSet.max'                => 'Sorry! Maximum allowed size for an image is 2MB',
		   //'filename.min'              => 'Your image is too small',
		];
	}
	

	
    /**
     * To override Return validation errors. In this case it will return and exucute code in Controller, even if Request Validation fails
	 * must include in Class code: public $validator = null;
     * @param Validator $validator
     * 
    */
    
    protected function failedValidation(Validator $validator)
    {
        $this->validator = $validator;
        //return response()->json(['error' => true, 'errors' => $validator->errors()->all()]);
    }
     
	 
	 
	 
    /**
     * Return validation errors 
     *
     * @param Validator $validator
     */
	/*
    public function withValidator(Validator $validator)
    {
	    if ($validator->fails()) {
            return redirect('/createNewWpressImg')->withInput()->with('flashMessageFailX', 'Validation Failed!!!' )->withErrors($validator);
        }
	} */
	 
	

   
	 
}
