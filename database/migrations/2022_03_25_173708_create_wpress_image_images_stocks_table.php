<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWpressImageImagesStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('wpress_image_images_stocks')) { //my fix for migration
		    Schema::create('wpress_image_images_stocks', function (Blueprint $table) {
                $table->increments('wpImStock_id');
			    $table->string('wpImStock_name', 77)->nullable();  //Эквивалент VARCHAR с длинной 222 // ->nullable()  is a fix
            
			    //Create Foreign key for table {wpressImages_blog_posts}	
			    $table->integer('wpImStock_postID')->unsigned()->nullable()->comment = 'Author ID';
                $table->foreign('wpImStock_postID')->references('wpBlog_id')->on('wpress_images_blog_post')->onUpdate('cascade')->onDelete('cascade');
	            //End Create Foreign key for table {wpressImages_blog_posts}	
			
			    $table->timestamps();
            });
	    }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wpress_image_images_stocks');
    }
}
