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
					<p> Migrated BootStrap from v3 to v4 </p>
                </div>


                <div class="card-body">  <!--.panel-body to .card-body. Migrating from BStrap v3 to v4 -->
				    
					<p><a href="{{ route('home') }}">
                        <button class="btn btn-large btn-success">Go home</button>
                    </a></p>
                    
               
				    <!---------- CRUD GII PANEL ------------>
					<div class="col-sm-12 col-xs-12"></br>
					    @if ($postsAll->count())
                            @foreach ($postsAll as $a)
			                    <p> Article {{ $loop->iteration }}  </p>  <!-- {{ $loop->iteration }} is Blade equivalentof $i++ -->
                                <p>Title {{ $a->wpBlog_title }}</p>
							    <p>Title {{ $a->wpBlog_text }}</p>
						    @endforeach
                        @else
							<p> No Found </p>
                        @endif
					   
					</div>
					<!---------- CRUD GII PANEL ------------>



					
                </div>
            </div>
        </div>
    </div>
</div>
@endsection