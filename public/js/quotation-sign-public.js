(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

	$(document).ready(function(){
    
		var canvas = document.getElementById("signature-pad");
		var signaturePad = new SignaturePad(canvas);
		
		$('#clear_signature').on('click', function(e){
			e.preventDefault();
			signaturePad.clear();
		});

		// When click on save button save signature in hidden field
		$('#save_signature, #pay').on('click', function(e){
			e.preventDefault();
			if(signaturePad.isEmpty()){
				alert('Please provide signature first.');
				return false;
			}
			else{
				var data = signaturePad.toDataURL();
				$('#signature').val(data);
				console.log(data);
				// submit the form if click on pay button
				if($(this).attr('id') == 'pay'){
					$('#quotation-sign-form').submit();
				}
				return true;
			}
		});

		// if input type is number then set min value 1
		jQuery('input[type="number"]').attr('min', 1);
		
	});

})( jQuery );
