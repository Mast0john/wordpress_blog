/**
 * Expand.js 1.0.0
 * @author Template
 * GPL Licensed
 */

function expand() {
    (function () {
        var flip = 0;
        var button = document.getElementById('expand_options');
        if (button == null)
            return false;
        button.onclick = function () {
            if (flip == 0) {
                flip = 1;
                jQuery('#aq_container #aq-nav').hide();
                jQuery('#aq_container #content').width(755);

                jQuery(this).removeClass('expand');
                jQuery(this).addClass('close');
                jQuery(this).text('Close');

            } else {
                flip = 0;
                jQuery('#aq_container #aq-nav').show();
                jQuery('#aq_container #content').width(595);

                jQuery(this).removeClass('close');
                jQuery(this).addClass('expand');
                jQuery(this).text('Expand');

            }
        };
    })();
}
jQuery(document).ready(function ($) {
    expand();
}
		);