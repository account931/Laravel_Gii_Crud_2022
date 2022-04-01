(function(){ //START IIFE (Immediately Invoked Function Expression)
$(document).ready(function(){
	
 
    //WP Blog Dropdown
    // **************************************************************************************
    // **************************************************************************************
    //                                                                                     ** 
	if(document.getElementById("dropdownnn") !== null){ //additional check to avoid errors in console in actions, other than actionShowAllBlogs(), when this id does not exist
	    document.getElementById("dropdownnn").onchange = function() {
            //if (this.selectedIndex!==0) {
            window.location.href = this.value;
            //}        
        };
	}
	// **                                                                                  **
    // **************************************************************************************
    // **************************************************************************************



	
	
	
	
	//code to populate the input file/image field (+/-) (to copy and paste new input on ++/--)
	//to populate <input type="file"> with JS (on click "+", adds a new <input>
	// **************************************************************************************
    // **************************************************************************************
    //                                                                                     ** 
	//user clicks ++ 
	$(".btn-populate-x").click(function(){  
        var html = $(".clone").html();
        $(".increment").after(html);
    });

    //When u click "Remove" -- to remove last populated <input type="file"> 
    $("body").on("click", ".remove-populated",function(){ 
        $(this).parents(".control-group").remove(); //removes last populated <input type="file"> 
		  
		//remove last added image, but only if it is not the 1st added image (i.e it 'll remove only those images, when u click "++" to polulated <input type="file"> and loaded image to this polulated input)
		if($('#previewDiv img').length > 1){
			$('#previewDiv img').last().remove();  //remove last added image
		} else {
			  alert('won"t remove the 1st image');
		}
    });
	  
	  
	  
	  
	  
	
	//Show preview of an image before it is uploaded (when u select image in <input type="file">). Images are appended to $('#previewDiv')
	// **************************************************************************************
    // **************************************************************************************
    //                                                                                     **  
	$(document).on("change", '.my-img-input-x', function(event) {   // this click is used to react to newly generated cicles;

        readURL(this, event);
    });
	 
	 
	 
	function readURL(input, event) { //alert(input.files[0]);
        if (input.files && input.files[0]) {
            var reader = new FileReader();
    
            reader.onload = function(e) {
			    //var s = $('#previewDiv').html(); //gets the content of div with images if there were loaded any prev
			    //<img id="preview" class="preview-x" src="" alt="" />
                //$('#preview').attr('src', e.target.result);
			 
			    //if users has prev selected the 1st <input type="file"> and then wants to change it, remove the 1st old image
			    if (event.target.id == "imgPrimary" && $('#previewDiv img').length > 1) { 
			        $('#previewDiv img').first().remove();  //remove first added image  
				    //prepend to the beginning of $('#previewDiv') new image
				    $('#previewDiv').prepend("<img src='" + e.target.result + "' class='preview-x' alt='' />");
			    //if user selects 1st image(for the first time) or 2nd, 3rd, 4th etc images
			    } else {
			        //append to the end of $('#previewDiv') new image
			        $('#previewDiv').append("<img src='" + e.target.result + "' class='preview-x' alt='' />");
			    }
			 
            }
    
            reader.readAsDataURL(input.files[0]); // convert to base64 string. Is a must
        }
    }
	
	
	
	
	
	
	
	
	
	//When user clicks "Remove image" in order while update/edit to remove some images prev loaded to DB
	// **************************************************************************************
    // **************************************************************************************
    //                                                                                     **  
	$(document).on("click", '.image-to-remove-from-db', function(event) {   // this click is used to react to newly generated cicles;
	
	    let clickedID = this.id; //alert(clickedID);
	    let prevValue    = document.getElementById('array_with_images_to_delete').value;
		let updatedValue = prevValue + ", " + clickedID;
		document.getElementById("array_with_images_to_delete").value = updatedValue;
		$(this).parent().fadeOut(800); //hide deleted image
	    
	});
	
	
	
	
	//NOT USED BELOW SO FAR !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	//on click on cut/truncated text (class .text-truncated) show all text
	// **************************************************************************************
    // **************************************************************************************
    //                                                                                     ** 
	  $(document).on("click", '.text-truncated', function() {   // this click is used to react to newly generated cicles;
	      $('.text-hidden').fadeOut(300);  $('.text-truncated').show(400); //if there is any prev opened texts, hide them all it & show all truncated
		  $(this).fadeOut(300); //hide truncated
		  $(this).next($('.text-hidden')).show(400);
		  
	  });
	
    // **                                                                                  **
    // **************************************************************************************
    // **************************************************************************************
	
	
	
	//on click on Full expended text (class .text-hidden) show cut/truncated text
	// **************************************************************************************
    // **************************************************************************************
    //                                                                                     ** 
	  $(document).on("click", '.text-hidden', function() {   // this  click  is  used  to   react  to  newly generated cicles;
	      $(this).fadeOut(300);
		  $(this).prev($('.text-truncated')).show(400);
		  
	  });
	
    // **                                                                                  **
    // **************************************************************************************
    // **************************************************************************************
	
	
	
	
	/*
	
	//LightBox init
	// **************************************************************************************
    // **************************************************************************************
    //                                                                                     ** 
	
	 lightbox.option({
      'resizeDuration': 200,
	  'fadeDuration': 600,
      'wrapAround': true,
	  'fitImagesInViewport':true, //img fit the screen
	  'alwaysShowNavOnTouchDevices': true
    });

     // **                                                                                  **
    // **************************************************************************************
    // **************************************************************************************
    */




	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
});
// end ready	
	
	
}()); //END IIFE (Immediately Invoked Function Expression)