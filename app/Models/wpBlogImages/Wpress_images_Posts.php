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
    * saves form inputs to DB (FINAL)
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
	
}