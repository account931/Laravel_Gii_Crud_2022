<?php
// Used for Vue_Crud panel (Api requests) only
//Model for wpress_images_blog_post, 
namespace App\models\wpBlogImages\Vue_API_Models;

use Illuminate\Database\Eloquent\Model;
use App\models\wpBlogImages\Vue_API_Models\Wpress_ImagesStock; //table for images
use Illuminate\Support\Facades\File;

class Wpress_images_Posts extends Model
{


    /**
     * Connected DB table name.
     *
     * @var string
     */
    protected $table      = 'wpress_images_blog_post';
    protected $fillable   = ['wpBlog_author', 'wpBlog_title', 'wpBlog_text', 'wpBlog_category', 'wpBlog_created_at'];  //????? protected $fillable = ['wpBlog_author', 'wpBlog_text', 'wpBlog_author', 'wpBlog_category',  'updated_at', 'created_at'];
    public $timestamps    = false; //to override Error "Unknown Column 'updated_at'" that fires when saving new entry
    protected $primaryKey = 'wpBlog_id'; //to show Laravel what id column is 'wpBlog_id' not 'id'        // override in model autoincrement id column name

  
  
  /**
   * BelongsTo Relationship
   * changed from  hasMany to belongsTo  - you're telling Laravel that this table holds the foreign key that connects it to the other table.
   * hasOne => get user name from table {users} based on column {wpBlog_author} in table {wpress_blog_post} .
   * hasOne
   */
  public function authorName()
  {
    return $this->belongsTo('App\User', 'wpBlog_author', 'id'); //return $this->belongsTo('App\modelName', 'foreign_key_that_table', 'parent_id_this_table');
	//return $this->hasOne('App\users', 'id', 'wpBlog_author')->withDefault(['name' => 'Unknown']);     //$this->belongsTo('App\modelName', 'foreign_key_that_table', 'parent_id_this_table');
    //->withDefault(['name' => 'Unknown']) this prevents the crash if this author id does not exist in table User (for example after fresh install and u forget to add users to user table)
  }
  
  
  /**
   * BelongsTo Relationship
   * changed from  hasMany to belongsTo  - you're telling Laravel that this table holds the foreign key that connects it to the other table.
   * hasMany => get category name from table {Wpress_images_Category} based on column {wpBlog_category} in table {wpress_blog_post} .
   * hasMany
   */
  public function categoryNames()
  {
	return $this->belongsTo('App\models\wpBlogImages\Wpress_images_Category', 'wpBlog_category','wpCategory_id');  //return $this->belongsTo('App\modelName', 'parent_id_this_table', 'foreign_key_that_table');
  }
  
  
  
  
   /**
   * hasOne and hasMany - you're telling Laravel that this table does not have the foreign key.
   * hasMany => get category name from table {Wpress_images_Category} based on column {wpBlog_category} in table {wpress_blog_post} .
   * hasMany
   */
  public function getImages(){
	    
        return $this->hasMany('App\models\wpBlogImages\Wpress_ImagesStock', 'wpImStock_postID', 'wpBlog_id'); //->withDefault(['wpImStock_name' => 'Unknown']);  //'foreign_key', 'owner_key' i.e 'this TableColumn', 'that TableColumn'
	}
	
	
	
	
	
 
  
   /**
    * Manual emulation of Laravel getter, gets DB Enum values (0/1) and changed to text "Published/Not Published"
    *
    * @param  string  $value
    * @return string
    */
   public function getIfPublished($value){
       if($value == '1'){
		return '<span class="text-success small"> published </span>';
	} else {
		return '<span class="text-danger small"> not published </span>';
	}
   }
   
   /**
    * truncates/crops the text
    *
    * @param  string  $text, int $maxLength
    * @return string
    */
	public function truncateTextProcessor($text, $maxLength)
	{
        $length = $maxLength; 
		if(strlen($text) > $length){
		    $text = substr($text, 0, $length) . "......";
		} 
	return $text;		
	}
	
 
 
 
 
 
 
 
 

	
	
	
	
	
	
//**********************************************************************************************************************
	
//REST API (Vue Crud Panel) METHODS SECTION =====================================================
	
	
	
   /**
    * saves form inputs to DB (FINAL)
    *
    * @param array $data, contains all form input 
	* @param array $imagesData, contains all form images
    * @param int $userZ
    * @return string $imagesList
    */
	public function saveFieldsRestApi($data, $imagesData, $userZ)
    {
		
		//dd(gettype ($data));
		//dd($imagesData);
		
		$this->wpBlog_author     = $userZ; //auth()->user()->id;
		$this->wpBlog_title      = $data[0]; //$data['title'];
        $this->wpBlog_text       = $data[1]; //$data['body'];
		$this->wpBlog_category   = $data[2];
		$this->wpBlog_created_at = date('Y-m-d H:i:s');
		$this->save();
		$idX = $this->wpBlog_id; //id of a new saved post, db 'wpressimages_blog_post'
		
		if($this->save()){ 
		    $imagesList = '';
		    foreach ($imagesData as $fileImageX){
			
			    //getting Image info for Flash Message
		        $imageName = time(). '_' . $fileImageX->getClientOriginalName();
                $imagesList.=  $imageName . ' ,';
		        //$sizeInByte =     $fileImageX->getSize() . ' byte';
		        //$sizeInKiloByte = round( ($fileImageX->getSize() / 1024), 2 ). ' kilobyte'; //round 10.55364364 to 10.5
		        //$fileExtens =     $fileImageX->getClientOriginalExtension();
		        //getting Image info for Flash Message
		
		    
		        //Move uploaded image to the specified folder 
		        $fileImageX->move(public_path('images/wpressImages'), $imageName);
                //$move = File::move($imageName, public_path('images/wpressImages'));
				//saving images
		        $model = new Wpress_ImagesStock();
			    $model->wpImStock_name    = $imageName; //image
			    $model->wpImStock_postID  = $idX; // just saved article ID
				$model->save();
			
		    } 
            return $imagesList; // true;
		}
	}
    
    
	
   /**
    * Updates one post/item in DB 'wpressimages_blog_post'
    * @param array $request, example of request => [ "title" => "TTTTTTTTT", "body" => "JavaScript Tutorial", "category_sel" => "3", "imageToDelete" => "66", "_method" => "PUT", "imagesSet" => array:1 [0 => UploadedFile {#1172, -originalName: "2254.png", -mimeType: "image/png", -size: 30871} ] ]
	* @param int $idX, id of edited post item
    * @return string
    */
	public function updatePostItem($idX, $request)
    {
        self::where('wpBlog_id', $idX)->update([ 
		    'wpBlog_text'     =>  $request->body, 
			'wpBlog_title'    =>  $request->title,
			'wpBlog_category' =>  $request->category_sel 
		]);
        
        //return string to construct a json response
        return '</br> Title: <i> '   . $request->title   . '.</i> ' .
               '</br> Body: <i> '    . $request->body    . '.</i> ' .
               '</br> Category: <i>' . $request->category_sel . '.</i> ';
        
    }
    
    
   /**
    * Saves new images to DB Wpress_ImagesStock (new images that a user uploaded while updtaing/editing a post)
    * @param array $request, example of request => [ "title" => "TTTTTTTTT", "body" => "JavaScript Tutorial", "selectV" => "3", "imageToDelete" => "66", "_method" => "PUT", "imagesSet" => array:1 [0 => UploadedFile {#1172, -originalName: "2254.png", -mimeType: "image/png", -size: 30871} ] ]
	* @param int $idX, id of edited post item
    * @return 
    */
	public function updateNewImages($idX, $request)
    {
        if (isset($request->imagesSet)){ //if user uploaded new images
            
            foreach ($request->imagesSet as $fileImageX){
			    //getting Image info for Flash Message
		        $imageName = time(). '_' . $fileImageX->getClientOriginalName();
		       
		        //Move uploaded image to the specified folder 
		        $fileImageX->move(public_path('images/wpressImages'), $imageName);
				//saving images
		        $model = new Wpress_ImagesStock();
			    $model->wpImStock_name    = $imageName; //image
			    $model->wpImStock_postID  = $idX; // just saved article ID
				$model->save();
		    }
        }
            
        //Below is just for testing (to construct an informational response)  ----  
        //New images uploaded by User (while editing)
        $imagesNew = ' User Uploaded new Images: '; 
        if (isset($request->imagesSet)){
            foreach($request->imagesSet as $d){
                $imagesNew.= " " . $d;    
            }
        } else {
            $imagesNew = 'User did not loaded new images. ';
        }
        return $imagesNew;
        
    }
    
    
	
	
	
	
	
    
    /**
    * Deletes old images DB Wpress_ImagesStock (old images that a user wished to delete while updtaing/editing a post)
    * @param array $request
	* @param int $idX, id of edited post item
    * @return 
    */
	public function deleteOldImages($idX, $request)
    {   
        if ($request->has('imageToDelete') && $request->imageToDelete != null){ //if user opted some old images to be deleted       
            //convert string {$request->imageToDelete} to array
            $del = explode(" ", $request->imageToDelete); // for bizzare reason {$request->imageToDelete) comes to Server as string not array 
            foreach($del as $d){ //$del = ['45', '56']
                //find the image DB entry to delete
                $data = Wpress_ImagesStock::findOrFail($d); 
                
                //remove the file itself from the folder 'images/wpressImages'
		        if(file_exists(public_path('images/wpressImages/' . $data->wpImStock_name))){
		            \Illuminate\Support\Facades\File::delete('images/wpressImages/' . $data->wpImStock_name);
		        } 
                $data->delete(); //delete the image record from DB 'Wpress_ImagesStock'
            }
        } 
        
        //Below is just for testing (to construct an informational response)  ----  
        $imageToDelete = '. While updating a user requested to delete Images: '; 
        if ($request->has('imageToDelete')){
            //convert string {$request->imageToDelete} to array
            $del = explode(" ", $request->imageToDelete); // {$request->imageToDelete) comes to Server as string not array 
            foreach($del as $d){
                $imageToDelete.= $d;    
            }
        } else {
            $imageToDelete = ' User did not opted to delete any old images. ';
        }
        return $imageToDelete;
    }
	
	
	
	
	
//End REST API SECTION =====================================================
	
	
	
	
	
	
	
	
	
	
	
}