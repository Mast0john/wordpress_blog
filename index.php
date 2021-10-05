<?php
/**
* The main template file.
*
* This is the most generic template file in a WordPress theme
* and one of the two required files for a theme (the other being style.css).
* It is used to display a page when nothing more specific matches a query.
* E.g., it puts together the home page when no home.php file exists.
* Learn more: http://codex.wordpress.org/Template_Hierarchy
*
* @package Template
*/
get_header(); ?>
<div id="ttr_content_and_sidebar_container">
<div id="ttr_content">
<div id="ttr_content_margin">
<div class="remove_collapsing_margins"></div>
<?php if (Template_theme_option("ttr_page_breadcrumb")):?>
<?php Template_wordpress_breadcrumbs(); ?>
<?php endif; ?>
<?php  /*query_posts($query_string. '&showposts='.Template_theme_option('ttr_query_result')); */query_posts($query_string . '&posts_per_page&orderby=modified&order=desc');?>
<?php if ( have_posts() ) : ?>

<?php Template_content_nav( 'nav-above' ); ?>
<?php
$layoutoption=4;
$featuredpost=1;
$themelayoutoption = Template_theme_option('ttr_post_layout');
$themefeaturedpost = Template_theme_option('ttr_featured_post');
if(isset($themelayoutoption))
$layoutoption = Template_theme_option('ttr_post_layout');
if(isset($themefeaturedpost))
$featuredpost = Template_theme_option('ttr_featured_post');
?>
<?php
if($layoutoption==1)
{
while ( have_posts())
{
the_post();
get_template_part( 'content',get_post_format());
}
}
else
{
$columncount=0;
$lastpost=true;
$flag=true;
while( have_posts())
{
$lastpost=true;
if($featuredpost > 0)
{
echo '<div class="row">';
echo '<div class="col-xl-12 col-lg-12 list">';
the_post();
get_template_part( 'content',get_post_format());
echo '</div></div>';
$featuredpost--;
$lastpost=false;
}
else
{
if($flag){
echo '<div class=" row">';
$flag=false;}
$class_suffix_lg  = round((12/$layoutoption));
if(empty($class_suffix_lg)){ 
$class_suffix_lg  =4;
}
 $md =4;
$class_suffix_md  = round((12 / $md));
 $xs =1;
$class_suffix_xs  = round((12 / $xs));
if (is_search() ) { 
echo '<div class="grid col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12">';
 } else { 
echo '<div class="grid col-xl-'.$class_suffix_lg.' col-lg-'.$class_suffix_lg.' col-md-'.$class_suffix_md.' col-sm-'.$class_suffix_xs.' col-xs-'.$class_suffix_xs.' col-'.$class_suffix_xs.'">';
 } 
the_post();
get_template_part( 'content',get_post_format());
echo '</div>';
$columncount++;
if($columncount % $xs == 0 && $columncount != 0){ echo '<div class="visible-xs-block d-block" style="clear:both;"></div>';}
if($columncount % $md == 0 && $columncount != 0){ echo '<div class="visible-md-block d-md-block" style="clear:both;"></div>';
echo '<div class=" visible-lg-block d-lg-block" style="clear:both;"></div>';}
if($columncount % $layoutoption == 0 && $columncount != 0){ echo '<div class=" visible-lg-block d-xl-block d-lg-block" style="clear:both;"></div>';}
$lastpost=true;
}
}
if (!$flag){
echo '</div>';
}
}
?>
<div style="clear: both;">
<?php Template_content_nav( 'nav-below' ); ?>
</div>
<?php else : ?>
<h2 class="ttr_post_title entry-title">
<?php _e( 'Nothing Found', 'mytheme' ); ?></h2>
<div class="postcontent entry-content">
<p><?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'mytheme' ); ?></p>
<?php get_search_form(); ?>
<div style="clear: both;"></div>
</div>
<?php endif; ?>
<div class="remove_collapsing_margins"></div>
</div>
</div>
<div style="clear: both;"></div>
</div>
<?php get_footer(); ?>