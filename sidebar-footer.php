<?php
/**
 * The Footer widget areas.
 */
?>

<?php
	/* The footer widget area is triggered if any of the areas
	 * have widgets. So let's check that first.
	 *
	 * If none of the sidebars have widgets, then let's bail early.
	 */
	if (   ! is_active_sidebar( 'first-footer-widget-area'  )
		&& ! is_active_sidebar( 'second-footer-widget-area' )
		&& ! is_active_sidebar( 'third-footer-widget-area'  )
		&& ! is_active_sidebar( 'fourth-footer-widget-area' )
	)
		return;
	// If we get this far, we have widgets. Let do this.
?>
			<div id="footer-widget-area" role="complementary"  class="row">	
			


<?php if ( is_active_sidebar( 'first-footer-widget-area' ) ) : ?>
				<div id="first" class="widget-area  col-xl-4 col-lg-4 col-md-4 col-sm-12 col-xs-12 col-12">
						<?php Template_theme_dynamic_sidebar( 'first-footer-widget-area' ); ?>
				</div><!-- #first .widget-area -->
<?php endif; ?>



<?php if ( is_active_sidebar( 'second-footer-widget-area' ) ) : ?>
				<div id="second" class="widget-area  col-xl-4 col-lg-4 col-md-4 col-sm-12 col-xs-12 col-12">
						<?php Template_theme_dynamic_sidebar( 'second-footer-widget-area' ); ?>
				</div><!-- #second .widget-area -->
<?php endif; ?>
	


	
<?php if ( is_active_sidebar( 'third-footer-widget-area' ) ) : ?>
				<div id="third" class="widget-area  col-xl-4 col-lg-4 col-md-4 col-sm-12 col-xs-12 col-12">
						<?php Template_theme_dynamic_sidebar( 'third-footer-widget-area' ); ?>
				</div><!-- #third .widget-area -->
<?php endif; ?>
	


				
<?php if ( is_active_sidebar( 'fourth-footer-widget-area' ) ) : ?>
				<div id="fourth" class="widget-area  col-xl-4 col-lg-4 col-md-4 col-sm-12 col-xs-12 col-12">
						<?php Template_theme_dynamic_sidebar( 'fourth-footer-widget-area' ); ?>
				</div><!-- #fourth .widget-area -->
<?php endif; ?>
<div style="clear: both;"></div>
			</div><!-- #footer-widget-area -->