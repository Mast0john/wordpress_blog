<div id="ttr_sidebar_left_margin"> 
<div class="remove_collapsing_margins"></div>
<div class="ttr_sidebar_left_padding">
<div class="remove_collapsing_margins"></div>
<?php if(!Template_theme_dynamic_sidebar(1)){
global $Template_theme_widget_args;
extract($Template_theme_widget_args);
echo ($before_widget.$before_title.__('Search','mytheme').$after_title);
get_search_form();
echo substr($after_widget,0,-3);
echo ($before_widget.$before_title.__('Calendar','mytheme').$after_title);
get_calendar();
echo substr($after_widget,0,-3);
}
?>
<div class="remove_collapsing_margins"></div>
 </div> 
</div>
