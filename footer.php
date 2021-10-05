<?php
/**
* The template for displaying the footer.
*
* @package Template
*/
?>
<?php $theme_path = get_template_directory_uri(); 
$theme_path_content = get_template_directory_uri().'/content'; ?>
<div class="footer-widget-area" role="complementary">
<div class="footer-widget-area_inner">
<?php
if( is_active_sidebar( 'footerabovecolumn1'  ) || is_active_sidebar( 'footerabovecolumn2'  ) || is_active_sidebar( 'footerabovecolumn3'  ) || is_active_sidebar( 'footerabovecolumn4'  )):
?>
<div class="ttr_footer-widget-area_inner_above_widget_container"> <!-- _widget_container-->
<div class="ttr_footer-widget-area_inner_above0 container row">
<?php if ( is_active_sidebar('footerabovecolumn1') ) : ?>
<div class="cell1 col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12 col-12">
<div>
<div class="footerabovecolumn1">
<?php Template_theme_dynamic_sidebar( 'FAWidgetArea00'); ?>
</div>
</div> <!-- top child column close-->
</div>  <!-- topcell column-->
<?php else: ?>
<div class="cell1 col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12 col-12 transparent">
&nbsp;
</div> <!-- topcell 2 column-->
<?php endif; ?>
<div class=" visible-sm-block d-sm-block visible-xs-block d-block" style="clear:both;"></div>
<?php if ( is_active_sidebar('footerabovecolumn2') ) : ?>
<div class="cell2 col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12 col-12">
<div>
<div class="footerabovecolumn2">
<?php Template_theme_dynamic_sidebar( 'FAWidgetArea01'); ?>
</div>
</div> <!-- top child column close-->
</div>  <!-- topcell column-->
<?php else: ?>
<div class="cell2 col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12 col-12 transparent">
&nbsp;
</div> <!-- topcell 2 column-->
<?php endif; ?>
<div class=" visible-md-block d-md-block visible-sm-block d-sm-block visible-xs-block d-block" style="clear:both;"></div>
<?php if ( is_active_sidebar('footerabovecolumn3') ) : ?>
<div class="cell3 col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12 col-12">
<div>
<div class="footerabovecolumn3">
<?php Template_theme_dynamic_sidebar( 'FAWidgetArea02'); ?>
</div>
</div> <!-- top child column close-->
</div>  <!-- topcell column-->
<?php else: ?>
<div class="cell3 col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12 col-12 transparent">
&nbsp;
</div> <!-- topcell 2 column-->
<?php endif; ?>
<div class=" visible-sm-block d-sm-block visible-xs-block d-block" style="clear:both;"></div>
<?php if ( is_active_sidebar('footerabovecolumn4') ) : ?>
<div class="cell4 col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12 col-12">
<div>
<div class="footerabovecolumn4">
<?php Template_theme_dynamic_sidebar( 'FAWidgetArea03'); ?>
</div>
</div> <!-- top child column close-->
</div>  <!-- topcell column-->
<?php else: ?>
<div class="cell4 col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12 col-12 transparent">
&nbsp;
</div> <!-- topcell 2 column-->
<?php endif; ?>
<div class=" visible-lg-block d-xl-block d-lg-block visible-md-block d-md-block visible-sm-block d-sm-block visible-xs-block d-block" style="clear:both;"></div>
<div class=" visible-lg-block d-xl-block d-lg-block visible-md-block d-md-block visible-sm-block d-sm-block visible-xs-block d-block" style="clear:both;"></div>
</div> <!-- top column-->
</div> <!-- _widget_container-->
<div style="clear: both;"></div>
<?php endif; ?>
</div>
</div>
<div class="remove_collapsing_margins"></div>
<?php $postid = ( isset( $post->ID ) ? get_the_ID() : NULL );
$var = get_post_meta ( $postid, 'ttr_page_foot_checkbox', true );
if($var == "true" || $var == ""):?>
<footer id="ttr_footer">
<div class="margin_collapsetop"></div>
<div id="ttr_footer_inner">
<?php
if( is_active_sidebar( 'footercellcolumn1'  ) || is_active_sidebar( 'footercellcolumn2'  ) || is_active_sidebar( 'footercellcolumn3'  ) || is_active_sidebar( 'footercellcolumn4'  )):
?>
<div class="ttr_footer-widget-cell_inner_widget_container"> <!-- _widget_container-->
<div class="ttr_footer-widget-cell_inner0 container row">
<?php if ( is_active_sidebar('footercellcolumn1') ) : ?>
<div class="cell1 col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12 col-12">
<div>
<div class="footercellcolumn1">
<?php Template_theme_dynamic_sidebar( 'FooterColumn00'); ?>
</div>
</div> <!-- top child column close-->
</div>  <!-- topcell column-->
<?php else: ?>
<div class="cell1 col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12 col-12 transparent">
&nbsp;
</div> <!-- topcell 2 column-->
<?php endif; ?>
<div class=" visible-sm-block d-sm-block visible-xs-block d-block" style="clear:both;"></div>
<?php if ( is_active_sidebar('footercellcolumn2') ) : ?>
<div class="cell2 col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12 col-12">
<div>
<div class="footercellcolumn2">
<?php Template_theme_dynamic_sidebar( 'FooterColumn11'); ?>
</div>
</div> <!-- top child column close-->
</div>  <!-- topcell column-->
<?php else: ?>
<div class="cell2 col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12 col-12 transparent">
&nbsp;
</div> <!-- topcell 2 column-->
<?php endif; ?>
<div class=" visible-md-block d-md-block visible-sm-block d-sm-block visible-xs-block d-block" style="clear:both;"></div>
<?php if ( is_active_sidebar('footercellcolumn3') ) : ?>
<div class="cell3 col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12 col-12">
<div>
<div class="footercellcolumn3">
<?php Template_theme_dynamic_sidebar( 'FooterColumn22'); ?>
</div>
</div> <!-- top child column close-->
</div>  <!-- topcell column-->
<?php else: ?>
<div class="cell3 col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12 col-12 transparent">
&nbsp;
</div> <!-- topcell 2 column-->
<?php endif; ?>
<div class=" visible-sm-block d-sm-block visible-xs-block d-block" style="clear:both;"></div>
<?php if ( is_active_sidebar('footercellcolumn4') ) : ?>
<div class="cell4 col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12 col-12">
<div>
<div class="footercellcolumn4">
<?php Template_theme_dynamic_sidebar( 'FooterColumn33'); ?>
</div>
</div> <!-- top child column close-->
</div>  <!-- topcell column-->
<?php else: ?>
<div class="cell4 col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12 col-12 transparent">
&nbsp;
</div> <!-- topcell 2 column-->
<?php endif; ?>
<div class=" visible-lg-block d-xl-block d-lg-block visible-md-block d-md-block visible-sm-block d-sm-block visible-xs-block d-block" style="clear:both;"></div>
<div class=" visible-lg-block d-xl-block d-lg-block visible-md-block d-md-block visible-sm-block d-sm-block visible-xs-block d-block" style="clear:both;"></div>
</div> <!-- top column-->
</div> <!-- _widget_container-->
<div style="clear: both;"></div>
<?php endif; ?>
<div id="ttr_footer_top_for_widgets">
<div class="ttr_footer_top_for_widgets_inner">
<?php get_sidebar( 'footer' ); ?>
</div>
</div>
<div class="ttr_footer_bottom_footer">
<div class="ttr_footer_bottom_footer_inner">
<div class="ttr_footer_element_alignment container">
</div>
<div id="ttr_footer_designed_by_links">
<?php $footer_designedbylink_URL = Template_theme_option('ttr_designedby_link_url');
$footer_designedby_link = Template_theme_option('ttr_designedby_link'); ?>
<?php $footer_link="https://Template.com"?>
<?php  if((empty( $footer_link)) && (empty($footer_designedbylink_URL))){ ?>
<span>
<?php echo $footer_designedby_link; ?>
</span>
<?php }else { ?>
<a href="<?php echo $footer_designedbylink_URL;   ?>">
<?php echo $footer_designedby_link; ?>
</a>
<?php } ?>
<span id="ttr_footer_designed_by">
<?php  $footer_designedby_text = Template_theme_option('ttr_deisgnedby_text');
echo $footer_designedby_text;
?>
</span>
</div>
</div>
</div>
</div>
</footer>
<?php endif; ?>
<div class="remove_collapsing_margins"></div>
<div class="footer-widget-area" role="complementary">
<div class="footer-widget-area_inner">
<?php
if( is_active_sidebar( 'footerbelowcolumn1'  ) || is_active_sidebar( 'footerbelowcolumn2'  ) || is_active_sidebar( 'footerbelowcolumn3'  ) || is_active_sidebar( 'footerbelowcolumn4'  )):
?>
<div class="ttr_footer-widget-area_inner_below_widget_container"> <!-- _widget_container-->
<div class="ttr_footer-widget-area_inner_below0 container row">
<?php if ( is_active_sidebar('footerbelowcolumn1') ) : ?>
<div class="cell1 col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12 col-12">
<div>
<div class="footerbelowcolumn1">
<?php Template_theme_dynamic_sidebar( 'FBWidgetArea00'); ?>
</div>
</div> <!-- top child column close-->
</div>  <!-- topcell column-->
<?php else: ?>
<div class="cell1 col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12 col-12 transparent">
&nbsp;
</div> <!-- topcell 2 column-->
<?php endif; ?>
<div class=" visible-sm-block d-sm-block visible-xs-block d-block" style="clear:both;"></div>
<?php if ( is_active_sidebar('footerbelowcolumn2') ) : ?>
<div class="cell2 col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12 col-12">
<div>
<div class="footerbelowcolumn2">
<?php Template_theme_dynamic_sidebar( 'FBWidgetArea01'); ?>
</div>
</div> <!-- top child column close-->
</div>  <!-- topcell column-->
<?php else: ?>
<div class="cell2 col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12 col-12 transparent">
&nbsp;
</div> <!-- topcell 2 column-->
<?php endif; ?>
<div class=" visible-md-block d-md-block visible-sm-block d-sm-block visible-xs-block d-block" style="clear:both;"></div>
<?php if ( is_active_sidebar('footerbelowcolumn3') ) : ?>
<div class="cell3 col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12 col-12">
<div>
<div class="footerbelowcolumn3">
<?php Template_theme_dynamic_sidebar( 'FBWidgetArea02'); ?>
</div>
</div> <!-- top child column close-->
</div>  <!-- topcell column-->
<?php else: ?>
<div class="cell3 col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12 col-12 transparent">
&nbsp;
</div> <!-- topcell 2 column-->
<?php endif; ?>
<div class=" visible-sm-block d-sm-block visible-xs-block d-block" style="clear:both;"></div>
<?php if ( is_active_sidebar('footerbelowcolumn4') ) : ?>
<div class="cell4 col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12 col-12">
<div>
<div class="footerbelowcolumn4">
<?php Template_theme_dynamic_sidebar( 'FBWidgetArea03'); ?>
</div>
</div> <!-- top child column close-->
</div>  <!-- topcell column-->
<?php else: ?>
<div class="cell4 col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12 col-12 transparent">
&nbsp;
</div> <!-- topcell 2 column-->
<?php endif; ?>
<div class=" visible-lg-block d-xl-block d-lg-block visible-md-block d-md-block visible-sm-block d-sm-block visible-xs-block d-block" style="clear:both;"></div>
<div class=" visible-lg-block d-xl-block d-lg-block visible-md-block d-md-block visible-sm-block d-sm-block visible-xs-block d-block" style="clear:both;"></div>
</div> <!-- top column-->
</div> <!-- _widget_container-->
<div style="clear: both;"></div>
<?php endif; ?>
</div>
</div>
</div>
<?php wp_footer(); ?>
</body>
</html>