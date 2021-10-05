/**
 * Upload 1.0.0
 * @author Template
 * GPL Licensed
 */

jQuery(document).ready(function ($) {

    var buttons = jQuery('.ttrbutton').prev('input');

    for (var i = 0; i < buttons.length; i++) {
        var slideimg = jQuery("#ttr_slide_show_image" + i).val();
        var horizontal = jQuery("#ttr_horizontal_align" + i).parent().parent();
        var vertical = jQuery("#ttr_vertical_align" + i).parent().parent();
        var stretch = jQuery("#ttr_stretch" + i).parent().parent();

        if (slideimg == "") {
            horizontal.css("display", "none");
            vertical.css("display", "none");
            stretch.css("display", "none");
        }

    }


    var uploadID = ''; /*setup the var*/
	var file_frame;

    jQuery('.ttrbutton').on('click', function( event ){

        var clicked = $(this);
        uploadID = clicked.prev('input');

        event.preventDefault();

        file_frame = wp.media.frames.file_frame = wp.media({
            title: tt_upload_data.title,
              button: { text:  tt_upload_data.text },
              library: { type: 'image' },
              multiple: false
        });

        file_frame.on( 'select', function() {
            attachment = file_frame.state().get('selection').first().toJSON();
			uploadID.val(attachment.url);
          
				var horizontal = uploadID.parent().parent().next('tr');
				var vertical = uploadID.parent().parent().next('tr').next('tr');
				var stretch = uploadID.parent().parent().next('tr').next('tr').next('tr');

				if (uploadID.val() != "") {
					horizontal.css("display", "");
					vertical.css("display", "");
					stretch.css("display", "");
				}
		  });
		 
		 file_frame.open();
    });

    for (var j = 0; j < buttons.length; j++) {
        jQuery("#ttr_slide_show_image" + j).change(function () {
            //alert("text changed");											

            var horiz = jQuery(this).parent().parent().next('tr');
            var vertiz = jQuery(this).parent().parent().next('tr').next('tr');
            var stretchz = jQuery(this).parent().parent().next('tr').next('tr').next('tr');

            if (jQuery(this).val() != "") {
                horiz.css("display", "");
                vertiz.css("display", "");
                stretchz.css("display", "");
            }
            else {
                horiz.css("display", "none");
                vertiz.css("display", "none");
                stretchz.css("display", "none");
            }

        });
    }

});