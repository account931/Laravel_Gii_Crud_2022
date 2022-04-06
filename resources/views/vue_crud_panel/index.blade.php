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
                
						
						
				<!--------------- Header ---------------------->		
                <div class="card-header"> <!-- .panel-heading to .card-header. Migrating from BStrap v3 to v4 -->
                    <div class="row">
					
					    <div class="col-sm-6 col-xs-6">
					        <h3>
                                <i class="fa fa-recycle" style="font-size:36px"></i>  
                                Vue Crud Panel <span class="small text-danger">*</span>
                            </h3> 
					       <p> BootStrap migrated from v3 to v4 </p>
					    </div>
					
					    <!-- Spatie blade directive -->
					    <div class="col-sm-3 col-xs-6">
					        @can(['edit articles', 'delete articles'])
                                <p class="text-danger alert alert-danger">Spatie dynamically Verified* </p>
                            @endcan
					    </div>
					    <!-- End Spatie blade directive -->
					
					</div>
                </div>
                <!-------------- End Header ----------------------->	

                <div class="col-sm-6 col-xs-6"><br>
				    <p><a href="{{ route('home') }}"> <button class="btn btn-success"> Home </button> </a> </p>
                </div>
				
				
                <div id="vueComponentR" class="card-body col-sm-12 col-xs-12">  <!--.panel-body to .card-body. Migrating from BStrap v3 to v4 -->
                    f <show-quantity-of-posts></show-quantity-of-posts>
				
            </div>
        </div>
    </div>
</div>



						
						
						
@endsection