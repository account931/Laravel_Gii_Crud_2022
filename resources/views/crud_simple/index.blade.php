@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 col-md-offset-0">
            <div class="card"> <!-- .panel panel-default to .card. Migrating from BStrap v3 to v4 -->
			
			
			    <!-- Flash message if Success -->
				@if(session()->has('flashMessageX'))
                    <div class="alert alert-success">
                        {!! session()->get('flashMessageX') !!} <!--Displays content without html escaping -->
                    </div>
                @endif
				<!-- Flash message -->
				

                <!-- Flash message if Failed -->
				@if(session()->has('flashMessageFailX'))
                    <div class="alert alert-danger">
                        {!! session()->get('flashMessageFailX') !!} <!--Displays content without html escaping -->
                    </div>
                @endif
				<!-- Flash message if Failed -->				
				

                <!-- Display form validation errors var 2 -->
				@if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <ul>
                        @foreach ($errors->all() as $error)
                            <li> {{ $error }} </li>
                        @endforeach
                        </ul>
                    </div>
                @endif
                <!-- End Display form validation errors var 2 -->		
                
						
						
						
                <div class="card-header"> <!-- .panel-heading to .card-header. Migrating from BStrap v3 to v4 -->
                    <h3>
                        <i class="fa fa-recycle" style="font-size:36px"></i>  
                        CRUD Simple <span class="small text-danger">*</span>
                    </h3> 
					<p> BootStrap migrated from v3 to v4 </p>
                </div>


                <div class="card-body">  <!--.panel-body to .card-body. Migrating from BStrap v3 to v4 -->
				    
					<p><a href="{{ route('home') }}">
                        <button class="btn btn-large btn-success">Go home</button>
						<!-- Button to create new record -->
						&nbsp;<a href="{{ route('createNewWpressImg') }}"><button class="btn btn-large btn-info">Create new</button></a>
                    </a></p>
                    
               
			        <!-- Display Categories Dropdown with Blade -->
					<div class="col-sm-12 col-xs-12"></br>
					    <div class="form-group">
					        <select class="mdb-select md-form" id="dropdownnn">
						        <option value={{ url("/crud-simple") }}  selected="selected">All articles</option>
		                        @foreach ($categories as $a)
								
								    @php
								    //gets to know what select <option> to make selected according to $_GET['']
			                        if(isset($_GET['category']) && $_GET['category'] == $a->wpCategory_id){
				                        $selectStatus = 'selected="selected"';
			                         } else {
					                     $selectStatus = '';
								     }
									 //$a->wpCategory_id
								     @endphp
					 
								    <option value={{ url("/crud-simple?category=$a->wpCategory_id") }}  {{$selectStatus }} > {{ $a->wpCategory_name}} </option>
					            @endforeach
						    </select>
					    </div>
					</div>
					<!-- END Display Categories Dropdown with Blade -->

					
                    <div class="alert alert-info borderX">
					    Aricles found: <span class="badge badge-pill badge-primary padding"> {{ $countArticles->count() }} </span> <!-- Bootstrap 4 -->
					</div>
			   
			   
			   
			   
				    <!---------- CRUD GII PANEL ------------>
					<div class="col-sm-12 col-xs-12 card"></br> <!-- .panel panel-default to .card. Migrating from BStrap v3 to v4 -->
					    
						<?php /* //Test of Active Record
					    @if ($postsAll->count())
                            @foreach ($postsAll as $a)
			                    <p> Article {{ $loop->iteration }}  </p>  <!-- {{ $loop->iteration }} is Blade equivalentof $i++ -->
                                <p>Title {{ $a->wpBlog_title }}</p>
							    <p>Title {{ $a->wpBlog_text }}</p>
						    @endforeach
                        @else
							<p> No Found </p>
                        @endif
					    */ ?>
						
						
						<div class="col-sm-12 col-xs-12 row div-striped over-scroll">
						
						    <!-- Table headers -->
						    <div class="col-sm-3 col-xs-2 d-none card-header my-head"> <b>Name   </b></div> <!-- Migrating from BStrap v3 to v4: changed "hidden-xs" to "d-none" -->
						    <div class="col-sm-3 col-xs-2 d-none card-header my-head"> <b>Text   </b></div>
						    <div class="col-sm-3 col-xs-2 d-none card-header my-head"> <b>Image  </b></div>
						    <div class="col-sm-3 col-xs-2 d-none card-header my-head"> <b>Action </b></div>
						
						
						    @if ($postsAll->count())
                                @foreach ($postsAll as $a)
						        
								    <div class="col-sm-12 col-xs-12 row one-row">
								
								        <!-- Title -->
						                <div class="col-sm-3 col-xs-2 card-header"> <!-- Migrating from BStrap v3 to v4: changed .panel-heading to .card-header. Migrating from BStrap v3 to v4 -->
								            {{ $a->wpBlog_title }} <br>
											{!! $model->getIfPublished( $a->wpBlog_status ) !!} <!-- without escapping --> <!-- "published"/"not_published" based on column 'wpBlog_status'-->
						                </div>
								
								        <!-- Text -->
							            <div class="col-sm-3 col-xs-2 card-header"> 
								           {{ $model->truncateTextProcessor($a->wpBlog_text, 6) }}
						                </div>
								
								
								
								        <!-- Image (displays one 1st image, post can have many connected images) -->
										<!-- hasMany Relation, Displays one 1st Photo as main. Images from table {wpress_image_images_stocks}. -->
								        <div class="col-sm-3 col-xs-2 card-header"> 
										
										    <?php $i = 0; ?>
						                    {{-- Check if relation Does not exist (i.e no images) --}}
								            @if( $a->getImages->isEmpty() )
					                            <p><img class="image-main-gii" src="{{URL::to("/")}}/images/no-image-found.png"  alt="a"/><p>
						
						                    {{-- Check if relation exists (i.e images exist), if True, foreach it --}}
						                    @else
                      							
					                            @foreach ($a->getImages as $x) {{--hasMany must be inside second foreach--}}
						                            {{-- If it is first image --}}
								                    @if($i == 0)
									
								                        @if(!file_exists(public_path('images/wpressImages/' . $x->wpImStock_name))){{-- check if image exists --}}
								    
									                    {!! "<span class='small'>image was likely deleted or missing</span>" !!} {{-- with html unescapped tags --}}
									                @else 
									
									                    <!-- Image with LightBox -->
						                                <a href="{{URL::to("/")}}/images/wpressImages/{{$x->wpImStock_name}}"  title="" data-lightbox="roadtrip{{$a->wpBlog_id}}"> <!-- roadtrip + currentID, to create a unique data-lightbox name, so in modal LightBox will show images related to this article only, not all -->
				                                           <img class="image-main-gii" src="{{URL::to("/")}}/images/wpressImages/{{$x->wpImStock_name}}"  alt="img"/>
									                    </a>
									                    <!-- End Image with LightBox -->
									                @endif 
									
								            @endif
						                    <?php $i++; ?>
	                                         @endforeach
						                     @endif
                                        <!-- End hasMany Relation, Displays 1st Photo as main. Images from table {wpressimage_imagesstock}. -->
						                </div>
										<!-- End Image (displays one 1st image, post can have many connected images) -->
										
								       <!-- Action buttons -->
								        <div class="col-sm-3 col-xs-2 card-header">
										
										    <!-- Edit btn  -->
											<a href="{{route('gii-edit-post', ['id' => $a->wpBlog_id])}}"> 
								                <button class="btn btn-success"> <i class="fa fa-pencil"></i> </button> 
											</a>
											
											<!-- View btn  -->
									        <a href="{{route('wpBlogImagesOne', ['id' => $a->wpBlog_id])}}">        
											    <button class="btn btn-info"> <i class="fa fa-eye"> </i> </button>  
                                            </a>
											
											<!-- Delete-->
									        <button class="btn btn-danger">  <i class="fa fa-trash-o"></i>   </button> <!-- Delete-->  
						            </div> 
									<!-- End Action buttons -->
								
								</div>
						        @endforeach
							
							    <p> {{ $postsAll->links() }} </p><!-- Pagination-->
                            @else
							    <p> No Found </p>
                            @endif
							
					    </div>
						
					</div>
					<!---------- CRUD GII PANEL ------------>



					
                </div>
            </div>
        </div>
    </div>
</div>



						
						
						
@endsection