/**
 * AddTextBox.js 1.0.0
 * Add and Remove the textboxes in contact us form using theme-options
 * @author Template
 * GPL Licensed
 */

var scripts = document.getElementsByTagName("script");
var src = scripts[scripts.length - 1].getAttribute("src");
src = src.substring(0, src.lastIndexOf('/') - 2);
jQuery(document).ready(function ($) {
	 
			$( 'body' ).on( 'click', '.newfield', function(e) {
				e.preventDefault();
				var now = $.now();
		        
				var button = $( this ),
				insert = button.parent().parent().prev('tr');
				//insert.after('<tr><td><input type="text" id="' + now + '" name= "' + now + '" value="" /> </td><td><select id="' + now + '" name= "' + now + '"><option name= "' + now + '" value="">--Select--</option><option name= "' + now + '" value="">Text</option><option name= "' + now + '" value="">File Upload</option></select></td><td><div class="normal-toggle-button' + now + '">	<input type="checkbox" id="' + now + 'req" name="' + now + 'req" /></div></td><td><input type="image" src="' + src + 'images/cross.png" class="removefield" /></td></tr>');
				insert.after('<tr><td><input type="text" id="' + now + '" name= "' + now + '" value="" /> </td><td><div class="normal-toggle-button' + now + '">	<input type="checkbox" id="' + now + 'req" name="' + now + 'req" /></div></td><td><input type="image" src="' + src + 'images/cross.png" class="removefield" /></td></tr>');
				$('.normal-toggle-button' + now).toggleButtons();
			});
			
			$( 'body' ).on( 'click', '.removefield', function(e) {
				e.preventDefault();
				var row   = $(this).parent().parent( 'tr' ),
				count = row.parent().find( 'tr' ).length - 1;
				if( count > 1 ) {
					$( 'input', row ).val( '' );
					row.fadeOut( 'fast' ).remove();
				} 
			});
	
});
		
