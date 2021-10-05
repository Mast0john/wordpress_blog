/**
 * tt_customize.js 1.0.0
 * TT customize file used to set the wordpress customize settings
 * @author Template
 * GPL Licensed
 */
 /* --------- hue show hide script ------*/
jQuery(document).ready(function () {
wp.customize.bind( 'ready', function() {

wp.customize( 'colorscheme', function( setting ) {
wp.customize.control( 'colorscheme_hue', function( control ) {
var visibility = function() {
if ( 'custom' === setting.get() ) {
control.container.slideDown( 180 );
} else {
control.container.slideUp( 180 );
}
};
visibility();
setting.bind( visibility );
});
});
// Detect when the front page sections section is expanded (or closed) so we can adjust the preview accordingly.
		wp.customize.section( 'theme_options', function( section ) {
			section.expanded.bind( function( isExpanding ) {

				// Value of isExpanding will = true if you're entering the section, false if you're leaving it.
				wp.customize.previewer.send( 'section-highlight', { expanded: isExpanding });
			} );
		} );
	});
});
