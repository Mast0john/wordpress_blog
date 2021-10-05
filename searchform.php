<?php
$options=get_defined_vars();?>
<form method="get" name="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
<input name="s" type="text" value="<?php echo esc_attr(get_search_query()); ?>" class="boxcolor" />
<?php if(Template_theme_option('ttr_search_icon_enable')):?>
<input type="submit" name="search" value="" style="margin-left:-20px;border:none;height:16px;width:16px;background:url('<?php echo esc_url( get_template_directory_uri() );?>/images/search.png');">
<?php else: ?>
<div>
 <input type="submit" class="btn btn-default" name="search" value="<?php  echo esc_attr(__('Search','mytheme')); ?>"/>
<div style="clear: both;"></div>
</div>
<?php endif;?>
</form>
