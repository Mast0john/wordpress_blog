<?php
/**
* The template for displaying all single posts.
*
* @package Template
*/
get_header(); ?>
<div id="ttr_content_and_sidebar_container">
<div id="ttr_content">
<div id="ttr_content_margin">
<div class="remove_collapsing_margins"></div>
<?php if (Template_theme_option("ttr_post_breadcrumb")):?>
<?php Template_wordpress_breadcrumbs(); ?>
<?php endif; ?>
<?php while ( have_posts() ) : the_post(); ?>
<nav id="nav-single">
<?php if(Template_theme_option('ttr_post_navigation_post')){?>
<h3 class="assistive-text"><?php _e( 'Post navigation', 'mytheme' ); ?></h3>
<?php } ?>
<?php if(Template_theme_option('ttr_previous_next_links')){?>
<span class="nav-previous"><?php previous_post_link( '%link', __( '<span class="meta-nav">&larr;</span> Previous', 'mytheme' ) ); ?></span>
<span class="nav-next"><?php next_post_link( '%link', __( 'Next <span class="meta-nav">&rarr;</span>', 'mytheme' ) ); ?></span>
<?php } ?>
</nav>
<?php get_template_part( 'content', get_post_format() ); ?>
<?php comments_template( '', true ); ?>
<?php endwhile;?>
<div class="remove_collapsing_margins"></div>
</div>
</div>
<div style="clear: both;">
</div>
</div>
<?php get_footer(); ?>
