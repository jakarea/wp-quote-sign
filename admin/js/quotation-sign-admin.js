(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
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

	$(document).ready(function() {
		// submission onclick send request to server to retrieve data
		$('.submission').click(function(e) {
			e.preventDefault();
			// alert($(this).data('id') + ' clicked');
			if ($('.submission').hasClass('message-active')) {
				$('.submission').removeClass('message-active');
				$(this).addClass('message-active');
			} else {
				$(this).addClass('message-active');
			}
			
			var id = $(this).data('id');
			// get the data from the form
			var data = {
				'action': 'quotation_sign_single_list',
				'id': id,
			};
			var ajaxurl = $(this).data('action');
			// send the data to server with ajax post
			$.ajax({
				url: ajaxurl,
				type: 'GET',
				data: data,
				beforeSend: function() {
					// add preloader image get image from assets folder
					$('.giopio-listed-chat').html('<img src="../wp-content/plugins/quotation-sign/assets/pre-loader.gif" alt="preloader" class="preloader">');
				},
				success: function(response) {
				//   console.log(response);
				// Parse the nested JSON string within the "value" property
				var dataObject = JSON.parse(response.data.value);

				$('.header-chat p').html(dataObject['name']);

				  // Make a table with the data
				  var table = '<table class="table table-striped table-bordered">';
				  table += '<tr><th>Label</th><th></th><th>Value</th></tr>';
			  
				  for (var key in dataObject) {
					if (dataObject.hasOwnProperty(key)) {
					  var value = dataObject[key];
					//   table += '<tr><td>' + key + '</td><td>' + value + '</td></tr>';
					// The key should be capitalized and remove underscores
					var label = key.replace(/_/g, ' ');
					label = label.charAt(0).toUpperCase() + label.slice(1);
					// add dollar sign to the amount and dueamount
					if (label == 'Amount' || label == 'Dueamount') {
						value = '€' + value;
					}
					// Show signature as an image
					if (label == 'Signature') {
						value = '<img src="' + value + '" alt="signature" class="signature" width="100">';
					}
					table += '<tr><td>' + label + '</td>'+ '<td>:</td>' +'<td>' + value + '</td></tr>';
					}
				  }
			  
				  table += '</table>';

				  // table width 100
				  table += '<style>table {width: 100%;} table, th, td {border: 1px solid #dddddd; border-collapse: collapse;} th, td {padding: 5px; text-align: left;} table tr:nth-child(even) {background-color: #eee;} table tr:nth-child(odd) {background-color: #fff;} table th {background-color: black; color: white;}</style>';
			  
				  // Display the table in the specified element
				  $('.giopio-listed-chat').html(table);
				},
				error: function(error) {
				  console.log(error);
				}
			  });
			  
		});
		}
	);

})( jQuery );
