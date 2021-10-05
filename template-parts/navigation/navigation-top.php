<?php $theme_path_content = get_template_directory_uri().'/content'; ?>
<div id="ttr_menu"> 
<div class="margin_collapsetop"></div>
<nav class="navbar-default navbar-expand-md navbar" role="navigation" aria-label="<?php esc_attr_e( 'Top Menu', 'mytheme' ); ?>">
<div id="ttr_menu_inner_in">
<div class="ttr_images_container">
<div class="ttr_menu_logo">
</div>
</div>
<div id="navigationmenu">
<div class="navbar-header">
<button id="nav-expander" class="navbar-toggle" data-target=".navbar-collapse" type="button" aria-controls="top-menu" aria-expanded="false" data-toggle="collapse">
<span class="ttr_menu_toggle_button">
<span class="sr-only">
</span>
<span class="icon-bar navbar-toggler-icon">
</span>
<span class="icon-bar navbar-toggler-icon">
</span>
<span class="icon-bar navbar-toggler-icon">
</span>
</span>
<span class="ttr_menu_button_text">
Menu
</span>
</button>
</div>
<?php wp_nav_menu( array(
'theme_location'  => 'primary',
'menu_id'         => 'top-menu',
'menu_class'      => 'ttr_menu_items nav navbar-nav navbar-right',
'container_class' => 'menu-center collapse navbar-collapse',
'walker' 			=>  new Template_Walker_Vertical_Nav_Menu()
) ); ?>
<?php if ( ( is_home() && is_front_page() ) && has_custom_header() ) : ?>
<a href="#content" class="menu-scroll-down"><span class="screen-reader-text"><?php _e( 'Scroll down to content', 'mytheme' ); ?></span></a>
<?php endif; ?>
</div>
</div>
<div class="margin_collapsetop"></div>
</nav><!-- #site-navigation --></div>
