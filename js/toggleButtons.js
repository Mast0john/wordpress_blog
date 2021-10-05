/**
 * toggleButtons.js 1.0.0
 * @author Template
 * GPL Licensed
 */

jQuery(document).ready(function () {
    jQuery(".normal-toggle-button").toggleButtons({
        on_text: passed_data.on,
        off_text: passed_data.off
    });

});


//jQuery(window).on('load', adjustIframes);

function adjustIframes() {
    var pageClass = passed_data.pageClass;
    var actual_w = 0;
    var viewportWidth = jQuery(window).width();
    var pagename = jQuery('#title').val();
    jQuery('iframe').each(function () {
        $this = jQuery(this),
        actual_w = $this.width();
        $this.css('width', viewportWidth);
        $this.parent().css('overflow', 'hidden');
        $this.contents().find('html').css('width', actual_w + 'px');
        $this.contents().find('body').css('background', '#fff');
        $this.contents().find('body').addClass(pageClass);
    });
}
