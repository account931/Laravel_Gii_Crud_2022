<?php
//For regular web http
//Model for {wpress_image_category}
namespace App\models\wpBlogImages;

use Illuminate\Database\Eloquent\Model;

class Wpress_images_Category extends Model
{
	
   /**
    * Connected DB table name.
    * @var string
    * 
    */
    protected $table      = 'wpress_image_category'; 
    protected $primaryKey = 'wpImStock_id';  //to show Laravel what id column is 'wpImStock_id' not 'id'  
              

}