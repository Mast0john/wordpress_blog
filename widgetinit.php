<?php
global $wp_scripts;
global $Template_justify;
global $Template_magmenu;
global $Template_menuh;
global $Template_vmenuh;
global $Template_ocmenu;
global $Template_no_slides, $Template_slide_show_visible;
$Template_no_slides = 3;
$Template_slide_show_visible = false;
$Template_magmenu = false;
$Template_menuh = false;
$Template_justify = false;
global $Template_cssprefix;
$Template_cssprefix="ttr_";
global $Template_fontSize1, $Template_style1, $sidebarmenuheading;
$Template_style1="";
$sidebarmenuheading = Template_theme_option('ttr_sidebarmenuheading');
$Template_fontSize1 = Template_theme_option('ttr_font_size_sidebarmenu');
add_shortcode( 'widget', 'my_widget_shortcode' );
function my_widget_shortcode( $atts ) {
global $Template_cssprefix;
extract( shortcode_atts(
array(
'type'  => '',
'title' => '',
'style' => '',
),
$atts));
if($Template_style=='block'):
$args = array(
'before_widget' => '<div class="'.$Template_cssprefix.'block %2$s"><div class="remove_collapsing_margins"></div>',
'after_widget'  => '</div>',
'before_title'  => '<div class="'.$Template_cssprefix.'block_header"><h3 class="'.$Template_cssprefix.'block_heading widget_before_title">',
'after_title'   => '</h3></div>',
);
else:
$args = array(
'before_widget' => '<div class="box widget">',
'after_widget'  => '</div>',
'before_title'  => '<div class="wcidget-title">',
'after_title'   => '</div>',
);
endif;
the_widget( $type, $atts, $args );
}
add_action('wp_enqueue_scripts', 'Template_scripts_method');
function Template_scripts_method() {
$Template_js_enable =  Template_theme_option('ttr_theme_bootstrap_enable');
if($Template_js_enable)
{
wp_register_script( 'popperfront', get_template_directory_uri() . '/js/popper.min.js', array( 'jquery' ), '1.11.0', true );
wp_enqueue_script( 'popperfront' );
wp_register_script('bootstrapfront', get_template_directory_uri() . '/js/bootstrap.min.js', array('jquery'), '4.1.3', true);
wp_enqueue_script('bootstrapfront');
}
wp_register_script('customscripts', get_template_directory_uri() . '/js/customscripts.js', array('jquery'), '1.0.0', true);
 wp_enqueue_script('customscripts');
wp_register_script('totop', get_template_directory_uri() . '/js/totop.js', array('jquery'), '1.0.0', true);
wp_enqueue_script('totop');
wp_register_script('tt_animation', get_template_directory_uri() . '/js/tt_animation.js', array('jquery'), '1.0.0', true);
wp_enqueue_script('tt_animation');
wp_register_script('tt_googlemaps', '//maps.googleapis.com/maps/api/js',  '1.0.0', true);
wp_enqueue_style('menuie', get_stylesheet_directory_uri() . '/menuie.css');
wp_style_add_data('menuie', 'conditional', 'if lte IE 8');
wp_enqueue_style('vmenuie', get_stylesheet_directory_uri() . '/vmenuie.css');
wp_style_add_data('vmenuie', 'conditional', 'if lte IE 8');
wp_register_style('bootstrap', get_template_directory_uri() . '/css/bootstrap.css');
wp_enqueue_style('bootstrap');
wp_register_style('rtl', get_stylesheet_directory_uri() . '/rtl.css');
wp_register_style('style', get_stylesheet_directory_uri() . '/style.css');
wp_enqueue_style('style', get_stylesheet_directory_uri() . '/style.css');
}
function Template_widgets_init() {
global $Template_cssprefix;
$Template_cssprefix="ttr_";
global $Template_theme_widget_args;
global $Template_fontSize, $Template_style;
$heading_tag = Template_theme_option('ttr_heading_tag_block');
 if(empty($heading_tag) || $heading_tag == Null){
$heading_tag = 'h3';
}
$Template_style="";
 $blockheading = Template_theme_option('ttr_blockheading');
 $Template_fontSize = Template_theme_option('ttr_font_size_block');
 if(!empty($blockheading)){
 $Template_style .= "color:".$blockheading.";";
 }
 if(!empty($Template_fontSize)){
 $Template_style .= "font-size:".$Template_fontSize."px;";
 }
$Template_theme_widget_args = array('before_widget' => '<div class="'.$Template_cssprefix.'block %2$s"><div class="remove_collapsing_margins"></div> <div class="'.$Template_cssprefix.'block_header">',
'after_widget' => '</div></div>~tt',
'before_title' => '<'.Template_theme_option('ttr_heading_tag_block').' class="'.$Template_cssprefix.'block_heading">
',
'after_title' => '</'.$heading_tag.'></div> <div id="%1$s" class="'.$Template_cssprefix.'block_content">',
);
extract($Template_theme_widget_args);
register_sidebar( array(
'name' => __( 'CAWidgetArea00', 'mytheme' ),
'id' => 'contenttopcolumn1',
'description' => __( 'An optional widget area for your site content', 'mytheme' ),
'before_widget' => '<aside id="%1$s" class="widget %2$s">',
'after_widget' => "</aside>~tt",
'before_title' => '<h3 class="widget-title">',
'after_title' => '</h3>',
) );
register_sidebar( array(
'name' => __( 'CAWidgetArea01', 'mytheme' ),
'id' => 'contenttopcolumn2',
'description' => __( 'An optional widget area for your site content', 'mytheme' ),
'before_widget' => '<aside id="%1$s" class="widget %2$s">',
'after_widget' => "</aside>~tt",
'before_title' => '<h3 class="widget-title">',
'after_title' => '</h3>',
) );
register_sidebar( array(
'name' => __( 'CAWidgetArea02', 'mytheme' ),
'id' => 'contenttopcolumn3',
'description' => __( 'An optional widget area for your site content', 'mytheme' ),
'before_widget' => '<aside id="%1$s" class="widget %2$s">',
'after_widget' => "</aside>~tt",
'before_title' => '<h3 class="widget-title">',
'after_title' => '</h3>',
) );
register_sidebar( array(
'name' => __( 'CAWidgetArea03', 'mytheme' ),
'id' => 'contenttopcolumn4',
'description' => __( 'An optional widget area for your site content', 'mytheme' ),
'before_widget' => '<aside id="%1$s" class="widget %2$s">',
'after_widget' => "</aside>~tt",
'before_title' => '<h3 class="widget-title">',
'after_title' => '</h3>',
) );
register_sidebar( array(
'name' => __( 'HAWidgetArea00', 'mytheme' ),
'id' => 'headerabovecolumn1',
'description' => __( 'An optional widget area for your site header', 'mytheme' ),
'before_widget' => '<aside id="%1$s" class="widget %2$s">',
'after_widget' => "</aside>~tt",
'before_title' => '<h3 class="widget-title">',
'after_title' => '</h3>',
) );
register_sidebar( array(
'name' => __( 'HAWidgetArea01', 'mytheme' ),
'id' => 'headerabovecolumn2',
'description' => __( 'An optional widget area for your site header', 'mytheme' ),
'before_widget' => '<aside id="%1$s" class="widget %2$s">',
'after_widget' => "</aside>~tt",
'before_title' => '<h3 class="widget-title">',
'after_title' => '</h3>',
) );
register_sidebar( array(
'name' => __( 'HAWidgetArea02', 'mytheme' ),
'id' => 'headerabovecolumn3',
'description' => __( 'An optional widget area for your site header', 'mytheme' ),
'before_widget' => '<aside id="%1$s" class="widget %2$s">',
'after_widget' => "</aside>~tt",
'before_title' => '<h3 class="widget-title">',
'after_title' => '</h3>',
) );
register_sidebar( array(
'name' => __( 'HAWidgetArea03', 'mytheme' ),
'id' => 'headerabovecolumn4',
'description' => __( 'An optional widget area for your site header', 'mytheme' ),
'before_widget' => '<aside id="%1$s" class="widget %2$s">',
'after_widget' => "</aside>~tt",
'before_title' => '<h3 class="widget-title">',
'after_title' => '</h3>',
) );
register_sidebar( array(
'name' => __( 'HBWidgetArea00', 'mytheme' ),
'id' => 'headerbelowcolumn1',
'description' => __( 'An optional widget area for your site header', 'mytheme' ),
'before_widget' => '<aside id="%1$s" class="widget %2$s">',
'after_widget' => "</aside>~tt",
'before_title' => '<h3 class="widget-title">',
'after_title' => '</h3>',
) );
register_sidebar( array(
'name' => __( 'HBWidgetArea01', 'mytheme' ),
'id' => 'headerbelowcolumn2',
'description' => __( 'An optional widget area for your site header', 'mytheme' ),
'before_widget' => '<aside id="%1$s" class="widget %2$s">',
'after_widget' => "</aside>~tt",
'before_title' => '<h3 class="widget-title">',
'after_title' => '</h3>',
) );
register_sidebar( array(
'name' => __( 'HBWidgetArea02', 'mytheme' ),
'id' => 'headerbelowcolumn3',
'description' => __( 'An optional widget area for your site header', 'mytheme' ),
'before_widget' => '<aside id="%1$s" class="widget %2$s">',
'after_widget' => "</aside>~tt",
'before_title' => '<h3 class="widget-title">',
'after_title' => '</h3>',
) );
register_sidebar( array(
'name' => __( 'HBWidgetArea03', 'mytheme' ),
'id' => 'headerbelowcolumn4',
'description' => __( 'An optional widget area for your site header', 'mytheme' ),
'before_widget' => '<aside id="%1$s" class="widget %2$s">',
'after_widget' => "</aside>~tt",
'before_title' => '<h3 class="widget-title">',
'after_title' => '</h3>',
) );
register_sidebar( array(
'name' => __( 'MAWidgetArea00', 'mytheme' ),
'id' => 'menuabovecolumn1',
'description' => __( 'An optional widget area for your site menu', 'mytheme' ),
'before_widget' => '<aside id="%1$s" class="widget %2$s">',
'after_widget' => "</aside>~tt",
'before_title' => '<h3 class="widget-title">',
'after_title' => '</h3>',
) );
register_sidebar( array(
'name' => __( 'MAWidgetArea01', 'mytheme' ),
'id' => 'menuabovecolumn2',
'description' => __( 'An optional widget area for your site menu', 'mytheme' ),
'before_widget' => '<aside id="%1$s" class="widget %2$s">',
'after_widget' => "</aside>~tt",
'before_title' => '<h3 class="widget-title">',
'after_title' => '</h3>',
) );
register_sidebar( array(
'name' => __( 'MAWidgetArea02', 'mytheme' ),
'id' => 'menuabovecolumn3',
'description' => __( 'An optional widget area for your site menu', 'mytheme' ),
'before_widget' => '<aside id="%1$s" class="widget %2$s">',
'after_widget' => "</aside>~tt",
'before_title' => '<h3 class="widget-title">',
'after_title' => '</h3>',
) );
register_sidebar( array(
'name' => __( 'MAWidgetArea03', 'mytheme' ),
'id' => 'menuabovecolumn4',
'description' => __( 'An optional widget area for your site menu', 'mytheme' ),
'before_widget' => '<aside id="%1$s" class="widget %2$s">',
'after_widget' => "</aside>~tt",
'before_title' => '<h3 class="widget-title">',
'after_title' => '</h3>',
) );
register_sidebar( array(
'name' => __( 'MBWidgetArea00', 'mytheme' ),
'id' => 'menubelowcolumn1',
'description' => __( 'An optional widget area for your site menu', 'mytheme' ),
'before_widget' => '<aside id="%1$s" class="widget %2$s">',
'after_widget' => "</aside>~tt",
'before_title' => '<h3 class="widget-title">',
'after_title' => '</h3>',
) );
register_sidebar( array(
'name' => __( 'MBWidgetArea01', 'mytheme' ),
'id' => 'menubelowcolumn2',
'description' => __( 'An optional widget area for your site menu', 'mytheme' ),
'before_widget' => '<aside id="%1$s" class="widget %2$s">',
'after_widget' => "</aside>~tt",
'before_title' => '<h3 class="widget-title">',
'after_title' => '</h3>',
) );
register_sidebar( array(
'name' => __( 'MBWidgetArea02', 'mytheme' ),
'id' => 'menubelowcolumn3',
'description' => __( 'An optional widget area for your site menu', 'mytheme' ),
'before_widget' => '<aside id="%1$s" class="widget %2$s">',
'after_widget' => "</aside>~tt",
'before_title' => '<h3 class="widget-title">',
'after_title' => '</h3>',
) );
register_sidebar( array(
'name' => __( 'MBWidgetArea03', 'mytheme' ),
'id' => 'menubelowcolumn4',
'description' => __( 'An optional widget area for your site menu', 'mytheme' ),
'before_widget' => '<aside id="%1$s" class="widget %2$s">',
'after_widget' => "</aside>~tt",
'before_title' => '<h3 class="widget-title">',
'after_title' => '</h3>',
) );
register_sidebar( array(
'name' => __( 'CBWidgetArea00', 'mytheme' ),
'id' => 'contentbottomcolumn1',
'description' => __( 'An optional widget area for your site content', 'mytheme' ),
'before_widget' => '<aside id="%1$s" class="widget %2$s">',
'after_widget' => "</aside>~tt",
'before_title' => '<h3 class="widget-title">',
'after_title' => '</h3>',
) );
register_sidebar( array(
'name' => __( 'CBWidgetArea01', 'mytheme' ),
'id' => 'contentbottomcolumn2',
'description' => __( 'An optional widget area for your site content', 'mytheme' ),
'before_widget' => '<aside id="%1$s" class="widget %2$s">',
'after_widget' => "</aside>~tt",
'before_title' => '<h3 class="widget-title">',
'after_title' => '</h3>',
) );
register_sidebar( array(
'name' => __( 'CBWidgetArea02', 'mytheme' ),
'id' => 'contentbottomcolumn3',
'description' => __( 'An optional widget area for your site content', 'mytheme' ),
'before_widget' => '<aside id="%1$s" class="widget %2$s">',
'after_widget' => "</aside>~tt",
'before_title' => '<h3 class="widget-title">',
'after_title' => '</h3>',
) );
register_sidebar( array(
'name' => __( 'CBWidgetArea03', 'mytheme' ),
'id' => 'contentbottomcolumn4',
'description' => __( 'An optional widget area for your site content', 'mytheme' ),
'before_widget' => '<aside id="%1$s" class="widget %2$s">',
'after_widget' => "</aside>~tt",
'before_title' => '<h3 class="widget-title">',
'after_title' => '</h3>',
) );
register_sidebar( array(
'name' => __( 'FAWidgetArea00', 'mytheme' ),
'id' => 'footerabovecolumn1',
'description' => __( 'An optional widget area for your site footer', 'mytheme' ),
'before_widget' => '<aside id="%1$s" class="widget %2$s">',
'after_widget' => "</aside>~tt",
'before_title' => '<h3 class="widget-title">',
'after_title' => '</h3>',
) );
register_sidebar( array(
'name' => __( 'FAWidgetArea01', 'mytheme' ),
'id' => 'footerabovecolumn2',
'description' => __( 'An optional widget area for your site footer', 'mytheme' ),
'before_widget' => '<aside id="%1$s" class="widget %2$s">',
'after_widget' => "</aside>~tt",
'before_title' => '<h3 class="widget-title">',
'after_title' => '</h3>',
) );
register_sidebar( array(
'name' => __( 'FAWidgetArea02', 'mytheme' ),
'id' => 'footerabovecolumn3',
'description' => __( 'An optional widget area for your site footer', 'mytheme' ),
'before_widget' => '<aside id="%1$s" class="widget %2$s">',
'after_widget' => "</aside>~tt",
'before_title' => '<h3 class="widget-title">',
'after_title' => '</h3>',
) );
register_sidebar( array(
'name' => __( 'FAWidgetArea03', 'mytheme' ),
'id' => 'footerabovecolumn4',
'description' => __( 'An optional widget area for your site footer', 'mytheme' ),
'before_widget' => '<aside id="%1$s" class="widget %2$s">',
'after_widget' => "</aside>~tt",
'before_title' => '<h3 class="widget-title">',
'after_title' => '</h3>',
) );
register_sidebar( array(
'name' => __( 'FBWidgetArea00', 'mytheme' ),
'id' => 'footerbelowcolumn1',
'description' => __( 'An optional widget area for your site footer', 'mytheme' ),
'before_widget' => '<aside id="%1$s" class="widget %2$s">',
'after_widget' => "</aside>~tt",
'before_title' => '<h3 class="widget-title">',
'after_title' => '</h3>',
) );
register_sidebar( array(
'name' => __( 'FBWidgetArea01', 'mytheme' ),
'id' => 'footerbelowcolumn2',
'description' => __( 'An optional widget area for your site footer', 'mytheme' ),
'before_widget' => '<aside id="%1$s" class="widget %2$s">',
'after_widget' => "</aside>~tt",
'before_title' => '<h3 class="widget-title">',
'after_title' => '</h3>',
) );
register_sidebar( array(
'name' => __( 'FBWidgetArea02', 'mytheme' ),
'id' => 'footerbelowcolumn3',
'description' => __( 'An optional widget area for your site footer', 'mytheme' ),
'before_widget' => '<aside id="%1$s" class="widget %2$s">',
'after_widget' => "</aside>~tt",
'before_title' => '<h3 class="widget-title">',
'after_title' => '</h3>',
) );
register_sidebar( array(
'name' => __( 'FBWidgetArea03', 'mytheme' ),
'id' => 'footerbelowcolumn4',
'description' => __( 'An optional widget area for your site footer', 'mytheme' ),
'before_widget' => '<aside id="%1$s" class="widget %2$s">',
'after_widget' => "</aside>~tt",
'before_title' => '<h3 class="widget-title">',
'after_title' => '</h3>',
) );
register_sidebar( array(
'name' => __( 'first-footer-widget-area', 'mytheme' ),
'id' => 'first-footer-widget-area',
'description' => __( 'An optional widget area for your site footer', 'mytheme' ),
'before_widget' => '<aside id="%1$s" class="widget %2$s">',
'after_widget' => "</aside>~tt",
'before_title' => '<h3 class="widget-title">',
'after_title' => '</h3>',
) );
register_sidebar( array(
'name' => __( 'second-footer-widget-area', 'mytheme' ),
'id' => 'second-footer-widget-area',
'description' => __( 'An optional widget area for your site footer', 'mytheme' ),
'before_widget' => '<aside id="%1$s" class="widget %2$s">',
'after_widget' => "</aside>~tt",
'before_title' => '<h3 class="widget-title">',
'after_title' => '</h3>',
) );
register_sidebar( array(
'name' => __( 'third-footer-widget-area', 'mytheme' ),
'id' => 'third-footer-widget-area',
'description' => __( 'An optional widget area for your site footer', 'mytheme' ),
'before_widget' => '<aside id="%1$s" class="widget %2$s">',
'after_widget' => "</aside>~tt",
'before_title' => '<h3 class="widget-title">',
'after_title' => '</h3>',
) );
register_sidebar( array(
'name' => __( 'FooterColumn00', 'mytheme' ),
'id' => 'footercellcolumn1',
'description' => __( 'An optional widget area for your site footer', 'mytheme' ),
'before_widget' => '<aside id="%1$s" class="widget %2$s">',
'after_widget' => "</aside>~tt",
'before_title' => '<h3 class="widget-title">',
'after_title' => '</h3>',
) );
register_sidebar( array(
'name' => __( 'FooterColumn11', 'mytheme' ),
'id' => 'footercellcolumn2',
'description' => __( 'An optional widget area for your site footer', 'mytheme' ),
'before_widget' => '<aside id="%1$s" class="widget %2$s">',
'after_widget' => "</aside>~tt",
'before_title' => '<h3 class="widget-title">',
'after_title' => '</h3>',
) );
register_sidebar( array(
'name' => __( 'FooterColumn22', 'mytheme' ),
'id' => 'footercellcolumn3',
'description' => __( 'An optional widget area for your site footer', 'mytheme' ),
'before_widget' => '<aside id="%1$s" class="widget %2$s">',
'after_widget' => "</aside>~tt",
'before_title' => '<h3 class="widget-title">',
'after_title' => '</h3>',
) );
register_sidebar( array(
'name' => __( 'FooterColumn33', 'mytheme' ),
'id' => 'footercellcolumn4',
'description' => __( 'An optional widget area for your site footer', 'mytheme' ),
'before_widget' => '<aside id="%1$s" class="widget %2$s">',
'after_widget' => "</aside>~tt",
'before_title' => '<h3 class="widget-title">',
'after_title' => '</h3>',
) );
}
add_action( 'widgets_init', 'Template_widgets_init' );?>
