 <form style="display:inline;" method="post" action='{{ url("/gii-delete-post" ) }}' enctype="multipart/form-data">  <!-- not url("/update-post/$thisID" ) for PUT-->
	{{-- Form::open(array('url' => 'storeNewWpress')) --}}
				
	<!-- Note: Since HTML forms only support POST and GET, PUT and DELETE methods will be spoofed by automatically adding a _method hidden field to your form. -->
    @method('DELETE') <!-- Fix for PUT in Laravel-->	<!--  <input type="hidden" name="_method" value="PUT"> -->
	<input type="hidden" value="{{csrf_token()}}" name="_token" /> <!-- csrf -->
	
	<!-- post id, hidden input -->
	<input type="hidden" value="{{ $id_passed }}"  name="blog_id_field" /> 
	<button class="btn btn-danger" onclick="return confirm('Are you sure to delete?')">  <i class="fa fa-trash-o"></i> </button> <!-- Delete--> 

</form>