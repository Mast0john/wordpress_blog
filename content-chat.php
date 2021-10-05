<?php global $Template_classes_post;?>
<article id="post-<?php the_ID(); ?>"<?php post_class( $Template_classes_post ); ?>>
<?php if ( has_post_thumbnail() && ! post_password_required() ) : ?>
<div class="entry-thumbnail">
<?php the_post_thumbnail(); ?>
</div>
<?php endif; ?>
<div class="ttr_post_content_inner">
<?php $postid = ( isset( $post->ID ) ? get_the_ID() : NULL );
$var = get_post_meta($postid, 'ttr_post_title_checkbox',true);
 $var_all=Template_theme_option('ttr_all_post_title');
if($var != 'false' && $var_all):?>
<div class="ttr_post_inner_box">
 <h2 class="ttr_post_title entry-title">
<?php if(has_post_format( array( 'link' ))):?>
<a href="<?php echo esc_url( Template_get_link_url() ); ?>"><?php the_title(); ?></a></h1>
<?php else: ?>
<?php $postid = ( isset( $post->ID ) ? get_the_ID() : NULL );
if(get_post_meta($postid,'ttr_post_link_enable_checkbox',true)!= 'false'):?>
<a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'mytheme' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php endif; ?><?php the_title(); ?></a></h2>
<?php endif; ?>
</div>
<?php endif; ?>
<div class="ttr_article">
<?php if ( 'post' == get_post_type() ) : ?>
<?php if ( has_post_format( array( 'chat' ) ) ):
Template_entry_meta(); ?>
<?php else: ?>
<?php Template_posted_on(True,True); ?>
<?php endif; ?>
<?php endif; ?>
<?php if ( is_search() ) : ?>
<div class="entry-summary">
<?php the_excerpt(); ?>
</div>
<?php else : ?>
<div class="postcontent">
<div class="entry-content">
<?php if(Template_theme_option('ttr_read_more_button')):
the_content( '<span class="button">'.Template_theme_option('ttr_read_more').'</span>' );
else:
the_content( Template_theme_option('ttr_read_more') );
endif;?>
<div style="clear: both;"></div>
</div>
</div>
<?php wp_link_pages( array( 'before' => '<span>' . __( 'Pages:', 'mytheme' ) . '</span>', 'after' => '' ) ); ?>
<?php endif; ?>
<?php $show_sep = false; ?>
<div>
<?php if(Template_theme_option('ttr_remove_post_category')):?>
<?php if ( 'post' == get_post_type() ) : ?>
<?php
$categories_list = get_the_category_list( __( ', ', 'mytheme' ) );
if ( $categories_list ):
?>
<?php printf( __( '<span class="meta">Posted in </span> %2$s', 'mytheme' ), '', $categories_list );
$show_sep = true; ?>
<?php endif; ?>
<?php
$tags_list = get_the_tag_list( '', __( ', ', 'mytheme' ) );
if ( $tags_list ):
if ( $show_sep ) : ?>
<span class="meta-sep">|</span>
<?php endif; ?>
<?php printf( __( 'Tagged %2$s', 'mytheme' ), 'entry-utility-prep entry-utility-prep-tag-links', $tags_list );
$show_sep = true; ?>
<?php endif; ?>
<?php endif;  ?>
<?php endif;  ?>
<?php if ( $show_sep ) : ?>
<span class="meta-sep">|</span>
<?php endif; ?>
<?php if ( comments_open() ) : ?>
<?php comments_popup_link( '<span class="leave-reply">' . __( 'Leave a reply', 'mytheme' ) . '</span>', __( '<b>1</b> Reply', 'mytheme' ), __( '<b>%</b> Replies', 'mytheme' ) ); ?>
<?php endif; ?>
<?php $postid = ( isset( $post->ID ) ? get_the_ID() : NULL );
if ( $post = get_post( $id )and $url = get_edit_post_link( $postid ) ) {
$link = __('Edit This', 'mytheme');
$post_type_obj = get_post_type_object( $post->post_type );
$link = '<span class="meta-sep">|</span> <a href="' . $url . '" title="' . esc_attr( $post_type_obj->labels->edit_item ) . '">' . $link . '</a>';
echo '<span class="edit-link">' . apply_filters( 'edit_post_link', $link, $postid ) .  '</span>';
}
?>
</div>
</div>
</div>
</article>