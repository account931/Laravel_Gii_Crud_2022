@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-sm-8 col-xs-12 col-md-8 col-md-offset-2 panel panel-default"> <!-- .panel panel-default to .card. Migrating from BStrap v3 to v4 -->
		
            <div class="panel-heading">Edit your WpBlog:  <b> {{$articleOne[0]->wpBlog_id}} </b>
			    <!--<img class="img-responsive my-cph" src="{{URL::to("/")}}/images/edit.png"  alt="a"/>-->
			</div>
			
			<p><a href="{{ route('crud-simple') }}"><button>Back to blogs</button></a></p>
				
				
			
					
			<div class="col-sm-12 col-xs-12">
					    
				<!-- Display errors var 1 -->
				@if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div><br />
                @endif
						
						
						
						
				<!-- Display errors var 2 -->
				<!--@if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif-->
                <!-- Display errors var 2 -->

						
				<!-- Flash message -->
				@if(session()->has('success'))
                    <div class="alert alert-success">
                        {{ session()->get('success') }}
                    </div>
               @endif
			   
			</div>


			 <!------------------------------- FORM ----------------------------------->		
            <div class="row">
			    <?php $thisID = $articleOne[0]->wpBlog_id; ?>
				
                <form method="post" action='{{ url("/update-post" ) }}'>  <!-- not url("/update-post/$thisID" ) for PUT-->
				{{-- Form::open(array('url' => 'storeNewWpress')) --}}
				
				   <!-- Note: Since HTML forms only support POST and GET, PUT and DELETE methods will be spoofed by automatically adding a _method hidden field to your form. -->
					@method('PUT') <!-- Fix for PUT in Laravel-->	<!--  <input type="hidden" name="_method" value="PUT"> -->
					
                    <div class="form-group">
                        <input type="hidden" value="{{csrf_token()}}" name="_token" /> <!-- csrf -->
						
						<!-- post id, hidden input -->
						<input type="hidden" value="{{ $articleOne[0]->wpBlog_id }}"  name="blog_id_field" /> 
						
						
						<!-- hidden input, images which user opted to delete (images prev loaded in DB) -->
						<input type="hidden" value="" id="array_with_images_to_delete" name="images_to_delete" /> 

                        <label for="title">Article Title:</label>
                        <input type="text" class="form-control" name="title" value="{{old('title', $articleOne[0]->wpBlog_title)}}"/>
                    </div>
								
                    <div class="form-group">
                        <label for="description">Article Text:</label>
                        <textarea cols="5" rows="5" class="form-control" name="description">{{old('description', $articleOne[0]->wpBlog_text)}}</textarea>
                    </div>
								
				    <!-- Category dropdown -->
					<div class="form-group">
                        <select name="category_sel" class="mdb-select md-form">
						    <option  disabled="disabled"  selected="selected">Choose category</option>
		                        @foreach ($categories as $a)
									@php
								    //gets to know what select <option> to make selected 
			                        if( $a->wpCategory_id == $articleOne[0]->wpBlog_category){
				                        $selectStatus = 'selected="selected"';
			                        } else {
					                    $selectStatus = '';
								    }
									//$a->wpCategory_id
								    @endphp
									 
							        <option value={{ $a->wpCategory_id }} {{$selectStatus }} > {{ $a->wpCategory_name}} </option>
					            @endforeach
						</select>
				    </div>	
									
									
									
									
									    	
				    <!-- Images upload -->
				    <div class="form-group input-group control-group increment {{ $errors->has('filename') ? ' has-error' : '' }}" > <!-- .increment is crucial for  populating <input type="file">-->
                        <input type="file" name="filename[]" class="form-control my-img-input-x" id="imgPrimary">
                        <!--populate field input via JS -->
						<div class="input-group-btn"> 
                            <button class="btn btn-success btn-style btn-populate-x" type="button"><i class="glyphicon glyphicon-plus"></i>Add</button>
                        </div>
                    </div>
		            <!-- Images upload -->
		
		
		            <!-- Hidden Div with Image/file input to copy and generate on ++/-- -->
					<!-- Hidden Div to populate <input type="file"> with JS (on click "+", adds a new <input> -->
		            <div class="clone hide" style="display:none;">
                        <div class="control-group input-group" style="margin-top:10px">
                            <input type="file" name="filename[]" class="form-control my-img-input-x">
									   
						    <div class="input-group-btn"> <!-- col-sm-2 col-xs-12 -->
                                <button class="btn btn-danger btn-style remove-populated" type="button"><i class="glyphicon glyphicon-remove"></i> Remove</button>
						    </div>
                        </div>
                    </div>
				    <!-- Hidden Div with Image/file input to copy and generate on ++/-- -->
								
								
				    <!-- Shows Preview of an image before it is uploaded (when u select image in <input type="file">). Images are JQ appended -->
					<div id="previewDiv">
					</div>
								 
									
									
									
									
								
                                
								
                    <button type="submit" class="btn btn-primary">Update</button>
				{{-- Form::close() --}}
                </form>
				</br>
            </div>
					
		    <!------------------------------- END FORM ----------------------------------->

					 
			<!-- hasMany Relation. Displays prev loaded images (from DB) with option to deelete(sets the hidden input with images ids). Images from table {wpressimage_imagesstock}. -->
			<div class="col-sm-12 col-xs-12">
				<p> Connected images </p>
				<?php $i = 0; ?>
				{{-- Check if relation Does not exist --}}
				@if( $articleOne[0]->getImages->isEmpty() )
				    No connected images 
					<!--<p><img class="image-main" src="{{URL::to("/")}}/images/no-image-found.png"  alt="a"/><p>-->
						
				{{-- Check if relation exists, if True, foreach it --}}
				@else
                      							
					@foreach ($articleOne[0]->getImages as $x) {{--hasMany must be inside second foreach--}}
						{{-- If it is first image --}}
					    {{-- @if($i > 0) --}}
									
						    <div class="col-sm-12 col-xs-12">
								<!-- Image with LightBox -->
						            <a href="{{URL::to("/")}}/images/wpressImages/{{$x->wpImStock_name}}"  title="" data-lightbox="roadtrip{{$x->wpBlog_id}}"> <!-- roadtrip + currentID, to create a unique data-lightbox name, so in modal LightBox will show images related to this article only, not all -->
								        <img class="image-others" src="{{URL::to("/")}}/images/wpressImages/{{$x->wpImStock_name}}"  alt="img"/>
									</a>
										
								    <span class="alert alert-danger image-to-remove-from-db" id="{{$x->wpImStock_id}}"> Remove image ?? </span>
							    <!-- End Image with LightBox -->
							</div><br>
						{{-- @endif --}}
		                   
						<?php $i++; ?>
	                @endforeach
				@endif
			</div>
			<!-- End hasMany Relation. Displays all images. Images from table {wpressimage_imagesstock}. -->
						
					
					
					
					
					
					
           <!-- </div>-->
        </div>
	</div>
</div>

@endsection
