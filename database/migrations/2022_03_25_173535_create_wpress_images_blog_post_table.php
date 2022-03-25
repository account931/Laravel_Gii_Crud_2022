<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWpressImagesBlogPostTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('wpress_images_blog_post')) { //my fix for migration     
		    Schema::create('wpress_images_blog_post', function (Blueprint $table) {
                $table->increments('wpBlog_id');
			    $table->string('wpBlog_title', 222)->nullable();  //Эквивалент VARCHAR с длинной 222 // ->nullable()  is a fix
			    $table->text('wpBlog_text')->nullable();        //equivalent for text 
			    $table->integer('wpBlog_author')->nullable();  //Эквивалент INTEGER для базы данных
			    $table->timestamp('wpBlog_created_at')->nullable(); //->default( date('Y-m-d H:i:s') );  //use=> ->default(DB::raw('CURRENT_TIMESTAMP'));   //Эквивалент TIMESTAMP для базы данных
		        //$table->integer('wpBlog_category')->nullable();  //Эквивалент INTEGER для базы 
				
				
			    //Create Foreign key for table {}	
			    $table->integer('wpBlog_category')->unsigned()->nullable()->comment = 'Category';
                $table->foreign('wpBlog_category')->references('wpCategory_id')->on('wpress_image_category')->onUpdate('cascade')->onDelete('cascade');
	            //End Create Foreign key for table {}	
			
			    $table->enum('wpBlog_status', ['0', '1'])->default('1'); //Эквивалент ENUM для базы данных
  
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
        Schema::dropIfExists('wpress_images_blog_post');
    }
}
