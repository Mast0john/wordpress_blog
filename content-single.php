<?php
/**
* File for displaying content.
*
* @package Template
*/
?>
<?php global $Template_classes_post;?>
<article <?php post_class( $Template_classes_post ); ?>>
<?php if ( has_post_thumbnail() && ! post_password_required() ) : ?>
<div class="entry-thumbnail">
<?php the_post_thumbnail('featuredImageCropped'); ?>
</div>
<?php endif; ?>
<div class="ttr_post_content_inner">
<?php $postid = ( isset( $post->ID ) ? get_the_ID() : NULL );
$var = get_post_meta($postid, 'ttr_post_title_checkbox',true);
 $var_all=Template_theme_option('ttr_all_post_title');
if($var != 'false' && $var_all):?>
<div class="ttr_post_inner_box">
<h2 class="ttr_post_title entry-title">
<?php the_title(); ?></h2>
</div>
<?php endif; ?>
<div class="ttr_article">
<?php if ( 'post' == get_post_type() ) : ?>
<?php Template_posted_on(True,True); ?>
<?php endif; ?>
<div class="postcontent entry-content">
<?php the_content(); ?>
<div style="clear: both;"></div>
</div>
<?php wp_link_pages( array( 'before' => '<div class="page-link"><span>' . __( 'Pages:', 'mytheme' ) . '</span>', 'after' => '</div>' ) ); ?>
<div class="postedon">
<?php
$categories_list = get_the_category_list( __( ', ', 'mytheme' ) );
$tag_list = get_the_tag_list( '', __( ', ', 'mytheme' ) );
if ( '' != $tag_list ) {
if(Template_theme_option('ttr_remove_post_category')){
$utility_text = __( 'This entry was posted in %1$s and tagged %2$s by <a href="%6$s">%5$s</a>. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'mytheme' );
}
else{$utility_text = __( 'Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'mytheme' );
}
}
elseif ( '' != $categories_list ) {
if(Template_theme_option('ttr_remove_post_category'))
{
$utility_text = __( 'This entry was posted in %1$s by <a href="%6$s">%5$s</a>. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'mytheme' );
} else {
$utility_text = __( 'Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'mytheme' );
}
}
else{
if(Template_theme_option('ttr_remove_post_category')){
$utility_text = __( 'This entry was posted in %1$s and tagged %2$s by <a href="%6$s">%5$s</a>. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'mytheme' );
}
else{$utility_text = __( 'Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'mytheme' );
}
}
printf(
$utility_text,
$categories_list,
$tag_list,
urldecode(esc_url( get_permalink() )),
the_title_attribute( 'echo=0' ),
get_the_author(),
esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) )
);
?>
<?php if ( $post = get_post( $id )and $url = get_edit_post_link( $post->ID ) ) {
$link = __('Edit This', 'mytheme');
$post_type_obj = get_post_type_object( $post->post_type );
$link = '<a href="' . $url . '" title="' . esc_attr( $post_type_obj->labels->edit_item ) . '">' . $link . '</a>';
echo '<span class="edit-link">' . apply_filters( 'edit_post_link', $link, $post->ID ) .  '</span>';
}
?>
<?php if ( get_the_author_meta( 'description' ) && is_multi_author() ) :?> 
<div id="author-info">
<div id="author-avatar">
<?php echo get_avatar( get_the_author_meta( 'user_email' ), apply_filters( 'Template_author_bio_avatar_size', 68 ) ); ?>
</div>
<div id="author-description">
<h2><?php printf( esc_attr__( 'About %s', 'mytheme' ), get_the_author() ); ?></h2>
<?php the_author_meta( 'description' ); ?>
<div id="author-link">
<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="author">
<?php printf( __( 'View all posts by %s <span class="meta-nav">&rarr;</span>', 'mytheme' ), get_the_author() ); ?>
</a>
</div>
</div>
</div>
<?php endif; ?>
</div>
</div>
</div>
</article>