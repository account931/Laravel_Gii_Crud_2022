<?php
//Model for wpress_images_blog_post
namespace App\models\wpBlogImages;

use Illuminate\Database\Eloquent\Model;
use App\models\wpBlogImages\Wpress_ImagesStock; //table for images
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
	
 
     
    /**
    * saves form inputs to DB (FINAL). Simple Crud
    *
    * @param array $data, contains all form input 
	* @param array $imagesData, contains all form images
    * @return 
    */
	public function saveFields($data, $imagesData){
		
		//dd(gettype ($data));
		//dd($imagesData);
		
		$this->wpBlog_author     = auth()->user()->id;
        $this->wpBlog_text       = $data['description'];
        $this->wpBlog_title      = $data['title'];
		$this->wpBlog_category   = $data['category_sel'];
		$this->wpBlog_created_at = date('Y-m-d H:i:s');
		$this->save();
		$idX = $this->wpBlog_id; //$this->id;
		//var_dump($idX); return false;
		
		if($this->save()){
		     //saving images
		    foreach ($imagesData as $fileImageX){
			
			    //getting Image info for Flash Message
		        $imageName = time(). '_' . $fileImageX->getClientOriginalName();
		        $sizeInByte =     $fileImageX->getSize() . ' byte';
		        $sizeInKiloByte = round( ($fileImageX->getSize() / 1024), 2 ). ' kilobyte'; //round 10.55364364 to 10.5
		        $fileExtens =     $fileImageX->getClientOriginalExtension();
		        //getting Image info for Flash Message
		
		
		        //Move uploaded image to the specified folder 
		        $fileImageX->move(public_path('images/wpressImages'), $imageName);
				//saving images
		        $model = new Wpress_ImagesStock();
			    $model->wpImStock_name    = $imageName; //image
			    $model->wpImStock_postID  = $idX; // just saved article ID
				$model->save();
			
		    }
		}
	}
	
	
	
	
	
	
	
	/**
    * updates one record, updates form inputs to DB (FINAL). Simple Crud
    *
    * @param array $data, contains all form input, except for <input type="file">
	* @param array $imagesData, contains all form images
	* @param object $articleOne, an article to update
    * @return 
    */
	public function updateFields($data, $imagesData, $articleOne, $request){
		
		$response = "";
		//dd(gettype ($data));
		//dd($imagesData);
		//dd($request->all());
		//dd($articleOne->wpBlog_author);
		
		$articleOne->wpBlog_author     = $articleOne->wpBlog_author;//auth()->user()->id;  //keep old value
        $articleOne->wpBlog_text       = $data['description'];
        $articleOne->wpBlog_title      = $data['title'];
		$articleOne->wpBlog_category   = $data['category_sel'];
		$articleOne->wpBlog_created_at = date('Y-m-d H:i:s');
		//$articleOne->save();  //for update may use ->save() as well. Instead of Wpress_images_Posts::where('wpBlog_id', $id)->update([  'wpBlog_text' => $data['description'], 'wpBlog_title' => $data['title'], 'wpBlog_category' => $data['category_sel'] ]);

		$idX = $articleOne->wpBlog_id; //$this->id;
		//dd($idX); return false;
		
		if($articleOne->save()){
			$response.= " Article was successfully edited. ";
			
			//upload new images, if user opted this
			if($request->hasFile('filename')) { //if uploaded images 
			    $response.= "User uploaded new images. ";
				
		        //saving images
		        foreach ($imagesData as $fileImageX){
			
			        //getting Image info for Flash Message
		            $imageName = time(). '_' . $fileImageX->getClientOriginalName();
		            $sizeInByte =     $fileImageX->getSize() . ' byte';
		            $sizeInKiloByte = round( ($fileImageX->getSize() / 1024), 2 ). ' kilobyte'; //round 10.55364364 to 10.5
		            $fileExtens =     $fileImageX->getClientOriginalExtension();
					$response.=  "Saved new image " . $imageName . " " . $sizeInKiloByte . " kb. "; 
		            //getting Image info for Flash Message
		
		
		            //Move uploaded image to the specified folder 
		            $fileImageX->move(public_path('images/wpressImages'), $imageName);
				    //saving images
		            $model = new Wpress_ImagesStock();
			        $model->wpImStock_name    = $imageName; //image
			        $model->wpImStock_postID  = $idX; // just saved article ID
				    $model->save();
			
		        }
			
		
			} else {
				$response.= "No new images were uploaded. ";
			}
			
			//dd($response);
			//return $response;
			
			
			//delete old images if user opted to delete some
			//......... work here
			if($request->has('images_to_delete') && $request->images_to_delete != null) { //if request exists
			    $response.= "User decided to delete some prev images. ";
			    
			    //.....
				    /*
				    $idN = $articleOne->wpBlog_author;
				    $data = Wpress_images_Posts::with('getImages')->findOrFail($idN);
					*/
                    //$data = Wpress_images_Posts::with('getImages', 'authorName', 'categoryNames')->where('wpBlog_id', $idN)->orderBy('wpBlog_created_at', 'desc')->get(); //->with('getImages', 'authorName', 'categoryNames') => hasMany/belongTo Eager Loading

                    /*
                    //version for $db->get(), i.e returns array of objects
                    $text = "";
                    foreach($data as $b){
                        if ($b->getImages->isEmpty()){
                            $text.= 'Screw ';
                        } else {
                            foreach($b->getImages as $f){
                                $text.= " Id to delete: " . $f->wpImStock_id . " ";
                            }
                
                        }
                    }
                    */
        
                    $imgDelete = explode(",", $request->images_to_delete); //string to array, hidden input with images ids to delete
					
                    foreach($imgDelete as $f){
                        $response.= "Image Id was deleted: " . $f . ". ";
                
				        $img = Wpress_ImagesStock::findOrFail($f);
                
                        //Delete relevant images from folder 'images/wpressImages/'
                        if(file_exists(public_path('images/wpressImages/' .  $img->wpImStock_name))){
		                    \Illuminate\Support\Facades\File::delete('images/wpressImages/' . $img->wpImStock_name);
		                }
                
                        //Delete relevant images from DB table {Wpress_ImagesStock} (images connected to post blog)
                        //$img = Wpress_ImagesStock::findOrFail($f); 
                        $img->delete();
                
                    }
                
                    
        
                    //$data->delete(); //delete the Post itself from DB  {Wpress_images_Posts}       
        
        
		   
				//.....
			   
			   
			   
			   
			   
			}  else {
				$response.= "No images were opted to delete. ";
			}
		
			
		} else {   
		    /* saving failed */
		}
		return $response;
		
	}
	
	
	
	
	
	
//**********************************************************************************************************************
	
//REST API METHODS SECTION =====================================================
	
	
	
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
        $this->wpBlog_text       = $data[0]; //$data['description'];
        $this->wpBlog_title      = $data[1]; //$data['title'];
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
    
    
	
	
	
//End REST API SECTION =====================================================
	
	
	
	
	
	
	
	
	
	
	
}