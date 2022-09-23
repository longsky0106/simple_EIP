$(document).ready(function(){

	$(document)[0].addEventListener("keyup", function(event) {

	if (event.getModifierState("CapsLock")) {
		$('#CapsLock_msg').css("display","block");
	  } else {
		$('#CapsLock_msg').css("display","none");
	  }
	});

	
	// Attach a submit handler to the form
	$( "#login_form" ).submit(function( event ) {
	 alert('asdasd');
	  // Stop form from submitting normally
	  event.preventDefault();
	 
	  // Get some values from elements on the page:
	  var $form = $( this ),
		term = $form.find( "input[name='s']" ).val(),
		url = $form.attr( "action" );
	 
	  // Send the data using post
	  var posting = $.post( url, { s: term } );
	 
	  // Put the results in a div
	  posting.done(function( data ) {
		var content = $( data ).find( "#content" );
		$( "#result" ).empty().append( content );
	  });
	});

});