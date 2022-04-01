@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-sm-12 col-xs-12 col-md-8 col-md-offset-2 panel panel-default"> <!-- col-md-offset-2 -->
            <div class="panel-heading alert-success"><i class="fa fa-book" style="font-size:26px"></i> Create new WpBlog with Images </div>
			    <p><br><a href="{{ route('crud-simple') }}"><button class="btn btn-large btn-success">Back to blogs</button></a></p>
				
				
				<!------------------------------- FORM ----------------------------------->
					
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
						

						
				    <!-- Flash message -->
					@if(session()->has('success'))
                        <div class="alert alert-success">
                           {{ session()->get('success') }}
                        </div>
                    @endif
						
						
                    <div class="row">
                        <form method="post" action="{{url('/storeNewWpressImg')}}"  enctype="multipart/form-data">
							{{-- Form::open(array('url' => 'storeNewWpressImg')) --}}

							<input type="hidden" value="{{csrf_token()}}" name="_token" />
								
                            <div class="form-group {{ $errors->has('title') ? ' has-error' : '' }}">

                                <label for="title">Article Title:</label>
                                <input type="text" class="form-control" name="title" value="{{old('title','')}}"/>
									
							    @if ($errors->has('title'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('title') }}</strong>
                                    </span>
                                @endif
									
                            </div>
								
								
								
						    <!-- description -->
                            <div class="form-group {{ $errors->has('description') ? ' has-error' : '' }}">
                                <label for="description">Article Text:</label>
                                <textarea cols="5" rows="5" class="form-control" name="description">{{old('description','')}}</textarea>
                                   
							    @if ($errors->has('description'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('description') }}</strong>
                                    </span>
                                @endif
									
							</div>
								
								
							<div class="form-group {{ $errors->has('category_sel') ? ' has-error' : '' }}">
                                <select name="category_sel" class="mdb-select md-form">
						            <option  disabled="disabled"  selected="selected">Choose category</option>
		                            @foreach ($categories as $a)
									    <!-- Saves the old value of select if submit failed due to Validation -->
					                    <option value={{ $a->wpCategory_id }} {{ old('category_sel')!=null && old('category_sel') == $a->wpCategory_id  ?  ' selected="selected"' : '' }} > {{ $a->wpCategory_name}} </option>

									@endforeach
						        </select>
									
								@if ($errors->has('category_sel'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('category_sel') }}</strong>
                                    </span>
                                @endif
                            </div>
								
								
								
						    <!-- Images upload -->
							<div class="form-group input-group control-group increment {{ $errors->has('filename') ? ' has-error' : '' }}" > <!-- .increment is crucial for  populating <input type="file">-->
                                <input type="file" name="filename[]" class="form-control my-img-input-x" id="imgPrimary">
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
								 
								 
                            <button type="submit" class="btn btn-primary">Create</button>
								{{-- Form::close() --}}
                        </form>
					    </br>
                    </div>
					
					<!------------------------------- END FORM ----------------------------------->
					
            </div>
        </div>
	</div>
</div>

@endsection