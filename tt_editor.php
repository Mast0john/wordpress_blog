<?php
/**
* This file is used to remove the deafult wordpress post/page editor and add our Template editor to
* customize the content of the page generated with the Template
* @package Template
*/
function Template_add_editor_styles(){
add_editor_style( get_stylesheet_directory_uri() . '/style.css' );
add_editor_style( get_template_directory_uri() . '/css/admin-style.css' );
}
add_action( 'init', 'Template_add_editor_styles' );
add_action( 'pre_get_posts', 'Template_add_editor_styles' );
// Load the editor for pages
function custom_editor_call()
{
global $pagenow;
add_action('admin_head', 'custom_after_wp_tiny_mce');
// Editor hook before & after
add_action( 'edit_form_after_title','Template_default_editor_tab');
add_action( 'edit_form_after_editor','Template_tt_editor_tab');
}
// Add the Editor Functionality
function custom_after_wp_tiny_mce()
{
global $post, $pagenow;
$url = get_template_directory_uri();
$id = get_the_ID();
$page_meta = get_post_meta( $id, 'tt_pageID', true );
$page_for_posts = get_option( 'page_for_posts' );
$page_type = get_current_screen()->id;
if( (get_bloginfo('version') < '5.0' && ($page_type == 'page' || $page_type == 'post')) || (get_bloginfo('version') >= '5.0' && ($page_type == 'page' && !empty($page_meta))))
{
echo '<link media="all" type="text/css" href="' . $url . '/css/bootstrap.css?ver=1.0.0" rel="stylesheet">';
if($id != $page_for_posts)
{
wp_register_script('jquery-grideditor', get_template_directory_uri() . '/js/jquery.grideditor.js', array('jquery'), '1.0.0', true);
$content  = $post->post_content;
$pass_data = array('ajaxurl' => admin_url('admin-ajax.php'), 'id' => $id, 'content' => $content);
wp_localize_script('jquery-grideditor', 'pass_data', $pass_data);
$fontformats  = explode(",",'Arial');
$fontformats = array_unique($fontformats);
$fontformats = array_values(array_filter($fontformats));
$f_count = count($fontformats);
$fontformat = '';
for($i=0;$i<$f_count;$i++) {
$fontformat .= $fontformats[$i].'='.$fontformats[$i].';';  }
wp_localize_script('jquery-grideditor', 'fontformats', $fontformat);
wp_localize_script('jquery-grideditor', 'google_font', NULL);
wp_enqueue_script('jquery-grideditor');
wp_register_script('bootstrap', get_template_directory_uri() . '/js/bootstrap.js', array('jquery'), '3.0', false);
wp_enqueue_script('bootstrap');
wp_register_script('tt_editor', get_template_directory_uri() . '/js/tt_editor.js', array('jquery'), '1.0.0', false);
wp_enqueue_script('tt_editor');
} } }
function Template_default_editor_tab()
{
global $post, $pagenow;
$url = get_template_directory_uri();
$id = get_the_ID();
$page_meta = get_post_meta( $id, 'tt_pageID', true );
$page_for_posts = get_option( 'page_for_posts' );
$page_type = get_current_screen()->id;
if( (get_bloginfo('version') < '5.0' && ($page_type == 'page' || $page_type == 'post')) || (get_bloginfo('version') >= '5.0' && ($page_type == 'page' && !empty($page_meta))))
{
if($id != $page_for_posts)
{
echo'<div class="tt-editor-wrapper">
<ul class="nav nav-tabs" id="tt_editor_tabs" role="tablist">
<li role="presentation"><a data-toggle="tab" aria-controls="tt_editor_tab1" href="#tt_editor_tab1" role="tab" >' . __( 'Default Editor','mytheme') . '</a></li>
<li class="active" role="presentation"><a data-toggle="tab" aria-controls="tt_editor_tab2" href="#tt_editor_tab2" role="tab">' . __( 'Custom Editor','mytheme') . '</a></li>
</ul>';
echo'<div class="tab-content tt-editor-tab-content">
<div class="tab-pane fade" id="tt_editor_tab1" role="tabpanel">';
}
}
}
function Template_tt_editor_tab()
{
global $post, $pagenow;
$url = get_template_directory_uri();
$id = get_the_ID();
$page_meta = get_post_meta( $id, 'tt_pageID', true );
$page_for_posts = get_option( 'page_for_posts' );
$page_type = get_current_screen()->id;
if( (get_bloginfo('version') < '5.0' && ($page_type == 'page' || $page_type == 'post')) || (get_bloginfo('version') >= '5.0' && ($page_type == 'page' && !empty($page_meta))))
{
if($id != $page_for_posts)
{
echo'</div><div class="tab-pane fade active show" id="tt_editor_tab2" role="tabpanel">';
echo'<div id="myGrid"><div class="row"> </div></div>';
echo'</div></div></div>';
} } }
if(get_bloginfo('version') < '5.0') {
add_action('init', 'custom_editor_call');
}
function tt_disable_gutenberg($is_enabled, $tt_post_type) {
$id = get_the_ID();
$page = get_post($id, OBJECT); 
$page_for_posts = get_option( 'page_for_posts' );
$page_meta = get_post_meta( $id, 'tt_pageID', true );
if (($tt_post_type === 'page'&& $id != $page_for_posts) && !empty($page_meta)) return false; 
return $is_enabled;
}
?>
