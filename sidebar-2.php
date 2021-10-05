<div id="ttr_sidebar_right_margin"> 
<div class="remove_collapsing_margins"></div>
<div class="ttr_sidebar_right_padding"> 
<div class="remove_collapsing_margins"></div>
<?php if(!Template_theme_dynamic_sidebar(2)){
global $Template_theme_widget_args;
extract($Template_theme_widget_args);
echo ($before_widget.$before_title.__('Category','mytheme').$after_title); ?>
<ul>
<?php wp_list_categories(); ?>
</ul>
<?php echo substr($after_widget,0,-3); ?>
<?php echo ($before_widget.$before_title.__('Archive','mytheme').$after_title); ?>
<ul>
<?php wp_get_archives(); ?>
</ul>
<?php echo substr($after_widget,0,-3);
}
?>
<div class="remove_collapsing_margins"></div>
</div>
</div>
