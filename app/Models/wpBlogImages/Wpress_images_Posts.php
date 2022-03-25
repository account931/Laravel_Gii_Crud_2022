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
    protected $table = 'wpress_images_blog_post';
    protected $fillable = ['wpBlog_author', 'wpBlog_title', 'wpBlog_text', 'wpBlog_category', 'wpBlog_created_at'];  //????? protected $fillable = ['wpBlog_author', 'wpBlog_text', 'wpBlog_author', 'wpBlog_category',  'updated_at', 'created_at'];
    public $timestamps = false; //to override Error "Unknown Column 'updated_at'" that fires when saving new entry
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
    * Manula emulation of Laravel getter, gets DB Enum values (0/1) and changed to text "Published/Not Published"
    *
    * @param  string  $value
    * @return string
    */
   public function getIfPublished($value){
       if($value == '1'){
		return 'Published';
	} else {
		return 'Not Published';
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
	
 
	
}