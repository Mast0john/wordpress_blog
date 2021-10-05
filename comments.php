<?php
/**
* The Template for displaying comments.
*
* The area of the page that contains both current comments and the comment form.
*
* @package Template
*/
?>
<div id="comments">
<?php if ( post_password_required() ) : ?>
<p class="nopassword"><?php _e( 'This post is password protected. Enter the password to view any comments.', 'mytheme' ); ?></p>
</div>
<?php
return;
endif;
?>
<?php
$comment_form_show_on = Template_theme_option('ttr_comment_list_form');
if (Template_theme_option('ttr_comment_list_form') == 'choice2') {
Template_theme_comment_form($Template_cssprefix = 'ttr_');
} 
?>
<?php if(Template_theme_option('ttr_comments_list')): ?>
<?php if ( have_comments() ) : ?>
<h2 id="comments-title">
<?php
printf( _n( 'One thought on &ldquo;%2$s&rdquo;', '%1$s thoughts on &ldquo;%2$s&rdquo;', get_comments_number(), 'mytheme' ),
number_format_i18n( get_comments_number() ), '<span>' . get_the_title() . '</span>' );
?>
</h2>
<?php endif; ?>
<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :?>
<nav id="comment-nav-above">
<h1 class="assistive-text"><?php _e( 'Comment navigation', 'mytheme' ); ?></h1>
<div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'mytheme' ) ); ?></div>
<div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'mytheme' ) ); ?></div>
</nav>
<?php endif;?>
<ol class="commentlist">
<?php
wp_list_comments( array(
'style'       => 'ol',
'short_ping'  => true,
'avatar_size' => Template_theme_option('ttr_avatar_size'),
'callback' => 'Template_comment_call',
) );
?>
</ol>
<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :
?>
<nav id="comment-nav-below">
<h1 class="assistive-text"><?php _e( 'Comment navigation', 'mytheme' ); ?></h1>
<div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'mytheme' ) ); ?></div>
<div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'mytheme' ) ); ?></div>
</nav>
<?php endif;?>
<?php
elseif ( ! comments_open() && ! is_page() && post_type_supports( get_post_type(), 'comments' ) ) :
?>
<?php if(Template_theme_option('ttr_comments_closed_text')){?>
<p class="nocomments"><?php _e( 'Comments are closed.', 'mytheme' ); ?></p>
<?php } ?>
<?php endif; ?>
<?php if (Template_theme_option('ttr_comment_list_form') == 'choice1') {
Template_theme_comment_form($Template_cssprefix = 'ttr_');
} ?>
</div>