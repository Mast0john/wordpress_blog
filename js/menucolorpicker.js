/**
 * Menucolorpicker.js 1.0.0
 * @author Template
 * GPL Licensed
 */

function color_display(obj) {

    (function () {
        var parentname = document.getElementById(obj.id).parentNode;
        var node = jQuery(parentname).find('> div:first');
        var menustyle = jQuery(parentname).find('.cmenu_style');
        
        if (obj.value == 'nostyle')
	        menustyle.hide();
	        
        else
	        menustyle.show();
        
        
        if (obj.value == 'nostyle')
            node.show();

        else
            node.hide();

    })();
}

jQuery(document).on('widget-updated widget-added', function (event, widget) {
    var id = widget.attr('id');
    jQuery('#' + id + ' .cw-color-picker').wpColorPicker();
});
jQuery(document).ready(function ($) {
    $('#widgets-right .cw-color-picker').wpColorPicker();
});
