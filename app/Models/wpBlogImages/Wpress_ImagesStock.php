<?php
//Model for {wpress_image_images_stocks}
namespace App\models\wpBlogImages;

use Illuminate\Database\Eloquent\Model;

class Wpress_ImagesStock extends Model
{
	
   /**
    * Connected DB table name.
    * @var string
    * 
    */
    protected $table      = 'wpress_image_images_stocks'; 
    protected $primaryKey = 'wpImStock_id';  //to show Laravel what id column is 'wpImStock_id' not 'id'  
              

}