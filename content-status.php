<article id="post-<?php the_ID(); ?>"<?php post_class(); ?>>
<div class="ttr_post">
<div class="postcontent">
<div class="entry-content">
<?php if(Template_theme_option('ttr_read_more_button')):
the_content( '<span class="button">'.Template_theme_option('ttr_read_more').'</span>' );
else:
the_content( Template_theme_option('ttr_read_more') );
endif;?>
<?php wp_link_pages( array( 'before' => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'mytheme' ) . '</span>', 'after' => '</div>', 'link_before' => '<span>', 'link_after' => '</span>' ) ); ?>
</div><!-- .entry-content -->
</div>
<div class="entry-meta">
<?php Template_entry_meta(); ?>
<?php edit_post_link( __( 'Edit This', 'mytheme' ), '<span class="edit-link">', '</span>' ); ?>
<?php if ( is_single() && get_the_author_meta( 'description' ) && is_multi_author() ) : ?>
<?php get_template_part( 'author-bio' ); ?>
<?php endif; ?>
</div><!-- .entry-meta -->
</div>
</article><!-- #post -->
