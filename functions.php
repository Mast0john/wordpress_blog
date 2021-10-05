<?php
/**
 *
 * Template functions and definitions
 *
 * @package Template
 */
$iscontactform = True;
$isplugincollection = False;
if($iscontactform || $isplugincollection)
{
require_once (dirname( __FILE__ ) . '/class-tgm-plugin-activation.php');
}
require_once(ABSPATH . 'wp-admin/includes/file.php');
ob_start();
global $Template_classes_post, $Template_cssprefix, $Template_theme_widget_args;
$Template_classes_post = array(
    'ttr_post'
);

/**
 * Template functions and definitions
 *
 * Sets up the theme and provides some helper functions. Some helper functions
 * are used in the theme as custom template tags. Others are attached to action and
 * filter hooks in WordPress to change core functionality.
 *
 * The first function, Template_theme_setup(), sets up the theme by registering support
 * for various features in WordPress, such as post thumbnails, navigation menus, and the like.
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are instead attached
 * to a filter or action hook. The hook can be removed by using remove_action() or
 * remove_filter() and you can attach your own function to the hook.
 *
 * We can remove the parent theme's hook only after it is attached, which means we need to
 * wait until setting up the child theme:
 *
 * <code>
 * add_action( 'after_setup_theme', 'my_child_Template_theme_setup' );
 * function my_child_Template_theme_setup() {
 *     ...
 * }
 * </code>
 *
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */

global $Template_theme_widget_args;

if (!isset($content_width))
    $content_width = 900;

global $impt;

// add_action('after_switch_theme', 'Template_import_option');
if (!function_exists('Template_import_option')) :
    function Template_import_option(){
	 add_option( 'is_import', '0' ); 
	}
	endif; 
	
// add_action('switch_theme', 'Template_setup_import_options');

function Template_setup_import_options () {
  delete_option('is_import'); 
}
/**
 * Tell WordPress to run Template_theme_setup() when the 'after_setup_theme' hook is run.
 */

if (!function_exists('Template_setup')) :
    /**
     * Sets up theme defaults and registers support for various WordPress features.
     *
     * Note that this function is hooked into the after_setup_theme hook, which
     * runs before the init hook. The init hook is too late for some features, such
     * as indicating support for post thumbnails.
     */
    function Template_setup()
    {   
	
     /* 
            * Make theme available for translation.
            * Translations can be filed in the /languages/ directory.
            * If you're building a theme based on Template, use a find and replace
            * to change 'Template' to the name of your theme in all the template files
	 */
	 	
        load_theme_textdomain("mytheme", get_template_directory() . '/languages');
        require_once(get_template_directory() . '/theme-options.php');
        global $Template_options, $Template_cssprefix, $Template_classes_post,$impt;
       // $Template_options = get_option('Template_theme_options');       // Priya:- shift to inside the Template_theme_option function.
		/*$seomode = Template_theme_option('ttr_seo_enable');
	       if($seomode)
	       {
		   	add_filter('wp_title', 'Template_wp_title', 10, 2);
		   }
		   else
		   {*/
		   		add_theme_support("title-tag");
		   // }
        require_once(get_template_directory() . '/widgetinit.php');
        require_once(get_template_directory() . '/custommenu.php');
        require_once(get_template_directory() . '/loginform.php');
        require_once(get_template_directory() . '/shortcodes.php');
        /* include 'seo.php';
        if (extension_loaded('zip')) {
		        include 'backup_recovery.php';
		    }*/
        $Template_classes_post = array(
            $Template_cssprefix . 'post'
        );

        $fileName = get_template_directory() . '/content/imports.php';
        if (file_exists($fileName)) {
	        $http = is_ssl()? "https:" : "http:";
            $current_url = $http . "//" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            $theme_page_url = admin_url( 'themes.php?activated=true' );
            if( $current_url === $theme_page_url ) {
                add_action( 'admin_notices', 'my_admin_notice' );
            }	
        }

		// Push data in required plugins array for contact form 7 plugin if used in theme.
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' ); 
		$plugins = array();
		$iscontactform = True;
 		if($iscontactform)
 		{
 			array_push($plugins, array(
					'name'               => 'Contact Form 7',
					'slug'               => 'contact-form-7',
					'source'             => 'https://downloads.wordpress.org/plugin/contact-form-7.latest-stable.zip',
					'required'           => true,
					'force_deactivation' => true,
                ));
 		}
 		
		// push Recommended Plugins array.
		$recommended_plugins = array();
		if ( ! empty( $recommended_plugins ) ) {
			foreach ( $recommended_plugins as $single ) {
				array_push( $plugins, $single );
			}
		}
        
        // Add alert message for required plugins.
        if ( ! empty( $plugins ) )
        {
            add_action( 'tgmpa_register',
            function() use ( $plugins ) {
                Template_require_plugins( $plugins ); });
        }
 		
        // This theme styles the visual editor with editor-style.css to match the theme style.
        add_editor_style();

        // Load up our theme options page and related code.

        // Add default posts and comments RSS feed links to <head>.
        add_theme_support('automatic-feed-links');    

        /*
        * Enable support for Post Thumbnails on posts and pages.
        *
        * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
        */
        add_theme_support('post-thumbnails');
        register_nav_menus(array(
            'primary' => __('Menu', "mytheme"),
        ));
		
        // Add support for a variety of post formats

        add_theme_support('post-formats', array(
            'aside', 'audio', 'chat', 'gallery', 'image', 'link', 'quote', 'status', 'video'
        ));

        add_filter('use_default_gallery_style', '__return_false');

        // Default custom headers packaged with the theme. %s is a placeholder for the theme template directory URI.
        register_default_headers(array(
            'wheel' => array(
                'url' => '%s/images/headers/wheel.jpg',
                'thumbnail_url' => '%s/images/headers/wheel-thumbnail.jpg',
                /* translators: header image description */
                'description' => __('Wheel', "mytheme")
            ),
            'shore' => array(
                'url' => '%s/images/headers/shore.jpg',
                'thumbnail_url' => '%s/images/headers/shore-thumbnail.jpg',
                /* translators: header image description */
                'description' => __('Shore', "mytheme")
            ),
            'trolley' => array(
                'url' => '%s/images/headers/trolley.jpg',
                'thumbnail_url' => '%s/images/headers/trolley-thumbnail.jpg',
                /* translators: header image description */
                'description' => __('Trolley', "mytheme")
            ),
            'pine-cone' => array(
                'url' => '%s/images/headers/pine-cone.jpg',
                'thumbnail_url' => '%s/images/headers/pine-cone-thumbnail.jpg',
                /* translators: header image description */
                'description' => __('Pine Cone', "mytheme")
            ),
            'chessboard' => array(
                'url' => '%s/images/headers/chessboard.jpg',
                'thumbnail_url' => '%s/images/headers/chessboard-thumbnail.jpg',
                /* translators: header image description */
                'description' => __('Chessboard', "mytheme")
            ),
            'lanterns' => array(
                'url' => '%s/images/headers/lanterns.jpg',
                'thumbnail_url' => '%s/images/headers/lanterns-thumbnail.jpg',
                /* translators: header image description */
                'description' => __('Lanterns', "mytheme")
            ),
            'willow' => array(
                'url' => '%s/images/headers/willow.jpg',
                'thumbnail_url' => '%s/images/headers/willow-thumbnail.jpg',
                /* translators: header image description */
                'description' => __('Willow', "mytheme")
            ),
            'hanoi' => array(
                'url' => '%s/images/headers/hanoi.jpg',
                'thumbnail_url' => '%s/images/headers/hanoi-thumbnail.jpg',
                /* translators: header image description */
                'description' => __('Hanoi Plant', "mytheme")
            )
        ));        
    }
endif; // Template_setup
add_action('after_setup_theme', 'Template_setup');

if (function_exists('is_woocommerce')) 
{
global $post, $product;
	add_action( 'after_setup_theme', 'setup_woocommerce_support' );
	add_theme_support( 'wc-product-gallery-zoom' ); 
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );

	function setup_woocommerce_support()
	{
	  add_theme_support('woocommerce');
	}
	
	add_filter( 'woocommerce_single_product_image_gallery_classes', 'tt_add_productimage_class');
	function tt_add_productimage_class($class)
	{
		$columns = apply_filters( 'woocommerce_product_thumbnails_columns', 4 );
		$class = array(	'pb-left-column', 
						'col-xl-6',
						'col-lg-6',
						'col-sm-12',
						'col-md-6',
						'col-xs-12',
						'col-12',
						'woocommerce-product-gallery',
						'woocommerce-product-gallery--' . ( has_post_thumbnail() ? 'with-images' : 'without-images' ),
						'woocommerce-product-gallery--columns-' . absint( $columns ),
						'images',);
				return $class;
	}
	
	function tt_woocommerce_sale_flash($label) { 
	$label = '<span class="label label-primary sale">' . __( 'Sale!', "mytheme") .'</span>';
	return $label;    
	}; 
	add_filter( 'woocommerce_sale_flash', 'tt_woocommerce_sale_flash');
	add_filter('after_switch_theme', 'shopcolumn');
	
	
	add_filter('woocommerce_cart_item_name','productLink',10, 3);
    $cartid = wc_get_page_id( 'cart' );
    update_post_meta($cartid, '_wp_page_template', 'page-templates/shop_page.php');
    $chckout =  wc_get_page_id( 'checkout' );
    update_post_meta($chckout, '_wp_page_template', 'page-templates/shop_page.php');
    $myaccount =  get_option('woocommerce_myaccount_page_id');
    update_post_meta($myaccount, '_wp_page_template', 'page-templates/shop_page.php');
    
   function woocommerce_before_shop_loop_itemCallback(){ 
   global  $Template_cssprefix; ?>
<ul class="<?php echo $Template_cssprefix ?>post"><li class="<?php echo $Template_cssprefix ?>post_content_inner"><ul class="<?php echo $Template_cssprefix ?>article grid"><li ><?php } ?>
<?php function woocommerce_after_shop_loop_itemCallback() { ?>
</li></ul></li></ul><?php } ?>
<?php 
			remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
				add_action('woocommerce_after_shop_loop_item','Template_woo_loop_add_to_cart');
				if(!function_exists( 'Template_woo_loop_add_to_cart')) {
				function Template_woo_loop_add_to_cart( $args = array() ) {
				global $product;
				echo'<div class="add-to-cart" >';
				if ( $product ) {
				$defaults = array(
				'quantity' => 1,
				'class'    => implode( ' ', array_filter( array(
				'~btn_type',
				'product_type_' . $product->get_type(),
				$product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
				$product->supports( 'ajax_add_to_cart' ) ? 'ajax_add_to_cart' : ''
				) ) ), // add this to solve woocommerece product add to cart issue on page refresh
                'attributes' => array(
					'data-product_id'  => $product->get_id(),
				),
				);
				$args = apply_filters( 'woocommerce_loop_add_to_cart_args', wp_parse_args( $args, $defaults ), $product );
				wc_get_template( 'loop/add-to-cart.php', $args );
				} echo'</div>';
				} }
				remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
				add_action( 'woocommerce_shop_loop_item_title', 'templatetoster_woocommerce_product_title', 10 );
				if (  ! function_exists( 'templatetoster_woocommerce_product_title' ) ) {
            function templatetoster_woocommerce_product_title() {
            global  $Template_cssprefix;
            echo'<div class="' . $Template_cssprefix . 'post_inner_box">';
            echo'<div style="height:0px; width:0px; overflow:hidden; -webkit-margin-top-collapse: separate;"></div>';
             echo'<h2 class="' . $Template_cssprefix . 'post_title">';
            echo '<a href="' . get_the_permalink() . '">' . get_the_title() . '</a>';
            echo'</h2>';
            echo'<div style="height:0px; width:0px; overflow:hidden; -webkit-margin-top-collapse: separate;"></div>';
            echo'</div>';
            } }
			add_action('woocommerce_before_shop_loop_item', 'woocommerce_before_shop_loop_itemCallback',10);
	 add_action('woocommerce_after_subcategory', 'woocommerce_after_shop_loop_itemCallback',50);
			 add_action('woocommerce_before_subcategory', 'woocommerce_before_shop_loop_itemCallback',10);
	 add_action('woocommerce_after_shop_loop_item', 'woocommerce_after_shop_loop_itemCallback',50);
	 remove_action( 'woocommerce_shop_loop_subcategory_title', 'woocommerce_template_loop_category_title', 10 );
     add_action( 'woocommerce_shop_loop_subcategory_title', 'Template_woo_loop_category_title', 10 );
     if (!function_exists( 'Template_woo_loop_category_title')){
     function Template_woo_loop_category_title($category) {
     global  $Template_cssprefix;
     echo'<div class="' . $Template_cssprefix . 'post_inner_box">';
     echo'<h2 class="' . $Template_cssprefix . 'post_title">';
     echo $category->name;
     if ( $category->count > 0 ) {
     echo apply_filters( 'woocommerce_subcategory_count_html', ' <mark class="count">(' . $category->count . ')</mark>', $category );
     } echo'</h2>';
     echo'</div>';
} }
    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
    add_action('woocommerce_single_product_summary', 'Template_woo_template_single_title', 5 );
    if (!function_exists( 'Template_woo_template_single_title')) {
    function Template_woo_template_single_title(){
    global  $Template_cssprefix;
    echo'<h1 class="' . $Template_cssprefix . 'Prodes_Title">';
    echo get_the_title();
    echo'</h1>';
} }
    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
    add_action( 'woocommerce_single_product_summary', 'Template_woo_template_single_price', 5 );
    if ( ! function_exists( 'Template_woo_template_single_price' ) ) {
    function Template_woo_template_single_price() {
    global  $Template_cssprefix;
    echo'<div class="' . $Template_cssprefix . 'prodes_Price">';
    wc_get_template( 'single-product/price.php' );
    echo'</div>';
} }
    remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
	remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );
	
	add_action( 'woocommerce_before_shop_loop_item_title', 'Template_product_thumbnail_wrap_open', 8, 2);
	if (!function_exists('Template_product_thumbnail_wrap_open')) {
	function Template_product_thumbnail_wrap_open() {
	echo '<a class="product-image" href="' . get_the_permalink() . '">';
} }

	add_action( 'woocommerce_before_shop_loop_item_title', 'Template_product_thumbnail_wrap_close', 10, 2);
	if (!function_exists('Template_product_thumbnail_wrap_close')) {
	function Template_product_thumbnail_wrap_close() {
	echo '</a> <!--/.wrap-->';
} }
	add_action( 'woocommerce_before_shop_loop_item_title', 'templatetoster_woocommerce_product_wrap_open', 11 );
	if (!function_exists('templatetoster_woocommerce_product_wrap_open')) {
	function templatetoster_woocommerce_product_wrap_open() {
	echo '<div class="product-shop">';
	echo '<div class="product-shop-margin postcontent">';
} }
	add_action('woocommerce_after_shop_loop_item', 'templatetoster_woocommerce_product_wrap_close');
	if (!function_exists('templatetoster_woocommerce_product_wrap_close')) {
	function templatetoster_woocommerce_product_wrap_close() {
	echo '</div>';
	echo '</div>';
} }
add_action( 'woocommerce_before_subcategory_title', 'templatetoster_woocommerce_product_wrap_open', 11 );//priyanka:- added gap between the product title and border on shop page
add_action('woocommerce_after_subcategory_title', 'templatetoster_woocommerce_product_wrap_close');
}
function productLink( $product_name, $cart_item, $cart_item_key )
    {
    global $Template_cssprefix ;
     $pro_link = get_permalink($cart_item['product_id']);
         return sprintf( '<a class=' . $Template_cssprefix . 'prochec_product_link href="'.$pro_link.'">%s </a>', $product_name );
         }

	
	function shopcolumn()
	{
	update_option( 'woocommerce_catalog_columns', "~shopcolumn" );
	}
	
function my_admin_notice()
{
	// add the theme options page url to string querys
	$theme_option_url = admin_url( 'admin.php?page=mytheme-options#import_content' );
	$msg = sprintf( "You may import content under <a href='%s'>Appearences -> Theme Options -> Import Content</a>",  $theme_option_url ) ;
    echo'<div class="updated notice notice-info is-dismissible"><p>'. $msg . '</p></div>';  
}

add_action( 'wp_ajax_parseXMLContent', 'parseXMLContent' );
function parseXMLContent(){
    $fileName = get_template_directory() . '/content/imports.php';
    if (file_exists($fileName)) {
        require_once(get_template_directory() . '/content/imports.php');
        $parsed_xml = Template_parse_xml();
        print_r($parsed_xml);
    }    
      wp_die();
}

function Template_content_import() {
$fileName = get_template_directory() . '/content/imports.php';
$imported = false;
if (file_exists($fileName) && (isset($_POST['importt']) && isset($_POST['formdata']))) {
	if (($_POST['importt'] != "")) {
	 $filterdata = array();
            foreach( $_POST['formdata']['name'] as $key => $name ){
                    if( array_key_exists($name, $filterdata) ){
                        $i++;
                        $filterdata[$name][$i] = $_POST['formdata']['values'][$key];
                    } else {
                        $i = 0;
                        $filterdata[$name][$i] = $_POST['formdata']['values'][$key];
                    }
                }
        $sidebars = array();
        if(array_key_exists('sidebar1',$filterdata) || array_key_exists('sidebar2',$filterdata)){
            $sidebar1 = array_key_exists('sidebar1',$filterdata) ? $filterdata['sidebar1'] : array();
            $sidebar2 = array_key_exists('sidebar2',$filterdata) ? $filterdata['sidebar2'] : array();
            $sidebars = array_merge($sidebar1,$sidebar2);
        }
		$filteredContent = [ 
	        'pages_info' => array_key_exists('pages',$filterdata) ? $filterdata['pages'] : array(),
	        'menu_info' => array_key_exists('menus',$filterdata) ? $filterdata['menus'] : array(),
            'sidebars_info' => $sidebars,
	        'footers_info' => array_key_exists('footers',$filterdata) ? $filterdata['footers'] : array(),
	        'media_info' => array_key_exists('media',$filterdata) ? $filterdata['media'] : array() // adding media in filter content.
	    ];
	    require_once(get_template_directory() . "/content/imports.php");
	    $imported = Template_import_start($filteredContent);
	}
	if( $imported ) {
		 $msg = __( "Content Imported successfully.", "mytheme" ) ;
         echo $msg; 
        $imported = false; 
	}
	else {
		 $msg = __( "Content Not Imported Sucessfully, import it again", "mytheme") ;
            echo $msg;
            $imported = false; 
	}
	wp_die();    
 }
}
  
add_action( 'wp_ajax_Template_content_import', 'Template_content_import' );

function Template_excerpt_length($length)
{
    return 40;
}

add_filter('excerpt_length', 'Template_excerpt_length');

/**
 * Returns a "Continue Reading" link for excerpts
 */
function Template_continue_reading_link()
{
    if (Template_theme_option('ttr_read_more_button')) {
        return '<br/><br/><a href="' . esc_url(get_permalink()) . '">' . '<span class="btn btn-default">' .__( Template_theme_option('ttr_read_more'), '', "mytheme") . '<span class="meta-nav">&rarr;</span></span>'. '</a>';
    } else {
        return '<br/><br/><a href="' . esc_url(get_permalink()) . '">' . __(Template_theme_option('ttr_read_more'), '', "mytheme") . '<span class="meta-nav">&rarr;</span></a>';

    }
}

/**
 * Replaces "[...]" (appended to automatically generated excerpts) with an ellipsis and Template_continue_reading_link().
 *
 * To override this in a child theme, remove the filter and add your own
 * function tied to the excerpt_more filter hook.
 */
function Template_auto_excerpt_more($more)
{
    return ' &hellip;' . Template_continue_reading_link();
}

add_filter('excerpt_more', 'Template_auto_excerpt_more');

/**
 * Trim the content lenght without deleting tags
 */
function Template_trim_words( $text, $more = null) {
		$length = Template_theme_option('ttr_read_length');
		$tokens = array();
		$out = '';
		$w = 0;
		if (null === $more)
        $more = '&hellip;';

		// Divide the string into tokens; HTML tags, or words, followed by any whitespace
		// (<[^>]+>|[^<>\s]+\s*)
		$lang = get_locale();
		if ($lang == "zh_CN")
		{       
		       preg_match_all( '/(<[^>]+>|[^<>\s]+)\s*/u', $text, $temp_token );
		    		foreach ( $temp_token[0] as $tt )
		    		 { // Parse each token
		    		   if ( $w >= $length) {  
								 break; 
								  } // Limit reached
					     	if ( $tt[0] != '<' ) { 
					   	     preg_match_all('/./u', $tt, $tokens);
					   	     foreach ( $tokens[0] as $ttt ) { // Parse each token
								if ( $w >= $length) {  
								 break; 
								  } // Limit reached
								
								  $out .= $ttt;
			                       $w++;    // Count words
			                   
	   						}
			                  		 
			              }
		      				else
		      				 {
		       					 $out .= $tt; // Append what's left of the token
			                 }
						
	   	             }
	   	             
	   	             if ($w >= $length) {  $out .=$more;  }  
		}
		else{
			preg_match_all( '/(<[^>]+>|[^<>\s]+)\s*/u', $text, $tokens );
			foreach ( $tokens[0] as $t ) { // Parse each token
			if ( $w >= $length) { // Limit reached
								break;
								}
			if ( $t[0] != '<' ) { 
			                       $w++;    // Count words
			                    }
			// Append what's left of the token
			$out .= $t;
	   	}
	   	    $a=0;
	   	 	foreach ( $tokens[0] as $t ) {
	   		if ( $t[0] != '<' ){
				$a++;
			}   
    }
           if ($a > $length) {
    			$out .=$more;}
		}
		
		return force_balance_tags( $out );
	   
		
	}


/**
 * Read more link function on enabling the tag in theme options
 */

function Template_content_filter($content)
{
    $morelink = ' &hellip;' . Template_continue_reading_link();
    if (Template_theme_option('ttr_post1_enable') && !is_single() && !is_page() && empty($post->post_excerpt) && !is_feed()) {
         return Template_trim_words($content,$more = $morelink);
		}
 else if (!empty($post->post_excerpt) && !is_single() && !is_page() && Template_theme_option('ttr_post1_enable') && !is_feed()) {
   		 return "<p>" . $post->post_excerpt .$more = $morelink. "</p>";
		}
		 else {
        return $content;
    }
}

add_filter('the_content', 'Template_content_filter');

/**
 * Adds a pretty "Continue Reading" link to custom post excerpts.
 *
 * To override this link in a child theme, remove the filter and add your own
 * function tied to the get_the_excerpt filter hook.
 */
function Template_custom_excerpt_more($output)
{
    if (has_excerpt() && !is_attachment()) {
        $output .= Template_continue_reading_link();
    }
    return $output;
}

add_filter('get_the_excerpt', 'Template_custom_excerpt_more');

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 */
function Template_page_menu_args($args)
{
    $args['show_home'] = true;
    return $args;
}

add_filter('wp_page_menu_args', 'Template_page_menu_args');

/**
 * Display navigation to next/previous pages when applicable
 */
function Template_content_nav($nav_id)
{
    global $wp_query;

    if ($wp_query->max_num_pages > 1) : ?>
<nav id="<?php echo esc_attr($nav_id); ?>">
<?php if ( ($nav_id == 'nav-above' && Template_theme_option('ttr_post_navigation_above')) || ($nav_id == 'nav-below' && Template_theme_option('ttr_post_navigation_below')) ): ?>
<h3 class="assistive-text">
<?php echo(__('Navigation', "mytheme")); ?>
</h3>
<?php endif; ?>
<?php
            if ( ($nav_id == 'nav-above' && Template_theme_option('ttr_pagination_link_posts_above')) || ($nav_id == 'nav-below' && Template_theme_option('ttr_pagination_link_posts_below')) ){
                global $wp_query;

                $big = 999999999;
                $pge = paginate_links(array(
                    'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
                    'prev_next' => True,
                    'format' => '?paged=%#%',
                    'prev_text' => __('Previous', "mytheme"),
                    'next_text' => __('Next', "mytheme"),
                    'current' => max(1, get_query_var('paged')),
                    'type' => 'array',
                    'total' => $wp_query->max_num_pages
                ));
                if ($wp_query->max_num_pages > 1) :
                    ?>
<div class="woo_pagination">
                    <ul class="pagination">
<?php
                        foreach ($pge as $page) {
                            if (strpos($page, 'current') !== false) {
                                echo '<li class="active">' . $page . '</li>';
                            } else {
                                echo '<li>' . $page . '</li>';
                            }
                        }
                        ?>
</ul>
<?php endif; ?>
</div>
<?php
            }
			if ( ($nav_id == 'nav-above' && Template_theme_option('ttr_older_newer_posts_above')) || ($nav_id == 'nav-below' && Template_theme_option('ttr_older_newer_posts_below')) )
			{ ?>
<div class="nav-previous">
<?php next_posts_link(__('<span class="meta-nav">&larr;</span> Older posts', "mytheme")); ?>
</div>
  <div
      class="nav-next">
<?php previous_posts_link(__('Newer posts <span class="meta-nav">&rarr;</span>', "mytheme")); ?>
</div>
<?php } ?>
</nav>
<!-- #nav-above -->
<?php endif;
}

/**
 * Return the URL for the first link found in the post content.
 * @return string|bool URL or false when no link is present.
 */
function Template_url_grabber()
{
    if (!preg_match('/<a\s[^>]*?href=[\'"](.+?)[\'"]/is', get_the_content(), $matches))
        return false;

    return esc_url_raw($matches[1]);
}

/**
 * Count the number of footer sidebars to enable dynamic classes for the footer
 */
function Template_footer_sidebar_class()
{
    $count = 0;

    if (is_active_sidebar('sidebar-3'))
        $count++;

    if (is_active_sidebar('sidebar-4'))
        $count++;

    if (is_active_sidebar('sidebar-5'))
        $count++;

    $class = '';

    switch ($count) {
        case '1':
            $class = 'one';
            break;
        case '2':
            $class = 'two';
            break;
        case '3':
            $class = 'three';
            break;
    }

    if ($class)
        echo 'class="' . $class . '"';
}

if (!function_exists('Template_comment')) :
    /**
     * Template for comments and pingbacks.
     *
     * To override this walker in a child theme without modifying the comments template
     * simply create your own Template_comment(), and that function will be used instead.
     */
function Template_comment($comment, $args, $depth)
{
        if (is_singular() && comments_open() && get_option('thread_comments'))
            wp_enqueue_script('comment-reply');
$GLOBALS['comment'] = $comment;
        switch ($comment->comment_type) :
case 'pingback' :
case 'trackback' :
?>
<li class="post pingback">
  <p>
<?php _e('Pingback:', "mytheme"); ?>
<?php comment_author_link(); ?>
<?php edit_comment_link(__('Edit', "mytheme"), '<span class="edit-link">', '</span>'); ?>
</p>
<?php
    break;
    default :
    ?>
<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
<!--<div id="comment-<?php comment_ID(); ?>" class="comment">-->
<div>

    <div class="comment-author vcard">
<?php
        $avatar_size = 68;
                            if ('0' != $comment->comment_parent)
            $avatar_size = 39;

                            echo get_avatar($comment, $avatar_size);

        /* translators: 1: comment author, 2: date and time */
                            printf(__('%1$s on %2$s <span class="says">said:</span>', "mytheme"),
                                sprintf('<span class="fn">%s</span>', get_comment_author_link()),
                                sprintf('<a href="%1$s"><time datetime="%2$s">%3$s</time></a>',
                                    esc_url(get_comment_link($comment->comment_ID)),
                                    get_comment_time('c'),
                /* translators: 1: date, 2: time */
                                    sprintf(__('%1$s at %2$s', "mytheme"), get_comment_date(), get_comment_time())
            )
        );
        ?>
<?php edit_comment_link(__('Edit', "mytheme"), '<span class="edit-link">', '</span>'); ?>
</div>
    <!-- .comment-author .vcard -->
<?php if ($comment->comment_approved == '0') : ?>
<em class="comment-awaiting-moderation">
<?php _e('Your comment is awaiting moderation.', "mytheme"); ?>
</em>
<br/>
<?php endif; ?>
<!--
</footer>-->

<div class="comment-content">
<?php comment_text(); ?>
</div>

    <div class="reply">
<?php comment_reply_link(array_merge($args, array('reply_text' => __('Reply <span>&darr;</span>', "mytheme"), 'depth' => $depth, 'max_depth' => $args['max_depth']))); ?>
    </div>
    <!-- .reply -->
  </div>
  <!-- #comment-## -->
<?php
break;
endswitch;
}
endif; // ends check for Template_comment()

if (!function_exists('Template_entry_meta')) :
    /**
     * Prints HTML with meta information for current post: categories, tags, permalink, author, and date.
     *
     * Create your own Template_entry_meta() to override in a child theme.
     * @return void
     */
    function Template_entry_meta()
    {
        if (is_sticky() && is_home() && !is_paged())
            echo '<span class="featured-post">' . __('Sticky', "mytheme") . '</span>';

        if (!has_post_format('link') && 'post' == get_post_type())
            Template_entry_date();

        // Translators: used between list items, there is a space after the comma.
        if (!has_post_format(array('chat', 'status'))):
            $categories_list = get_the_category_list(__(', ', "mytheme"));
            if ($categories_list) {
                if (Template_theme_option('ttr_remove_post_category'))
                    echo '<span class="categories-links"> ' . $categories_list . ' |</span>';
            }
        endif;

        // Translators: used between list items, there is a space after the comma.
        $tag_list = get_the_tag_list('', __(', ', "mytheme"));
        if ($tag_list) {
            echo '<span class="tags-links"> |' . $tag_list . '</span>';
        }

        // Post author
        if (!has_post_format(array('chat', 'status', 'aside', 'quote'))):
            if ('post' == get_post_type()) {
                printf('<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s" rel="author"> %3$s | </a></span>',
                    esc_url(get_author_posts_url(get_the_author_meta('ID'))),
                    esc_attr(sprintf(__('View all posts by %s', "mytheme"), get_the_author())),
                    get_the_author()
                );
            }
        endif;
    }
endif;

if (!function_exists('Template_entry_date')) :
    /**
     * Prints HTML with date information for current post.
     * Create your own Template_entry_date() to override in a child theme.
     * @param boolean $echo Whether to echo the date. Default true.
     * @return string The HTML-formatted post date.
     */
    function Template_entry_date($echo = true)
    {
        if (has_post_format(array('chat', 'status')))
            $format_prefix = _x('%1$s on %2$s ', '1: post format name. 2: date', "mytheme");
        else
            $format_prefix = '%2$s ';

        if (Template_theme_option('ttr_remove_date')):
            $date = sprintf('<span class="date"><a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s">%4$s</time></a></span>',
                esc_url(get_permalink()),
                esc_attr(sprintf(__('Permalink to %s', "mytheme"), the_title_attribute('echo=0'))),
                esc_attr(get_the_date('c')),
                esc_html(sprintf($format_prefix, get_post_format_string(get_post_format()), get_the_date()))
            );


            if (has_post_format(array('chat'))):
                if ($echo)
                    echo $date;

                return $date;
            else:
                if ($echo)
                    echo $date . '|';

                return $date . '|';
            endif;

        endif;
    }
endif;

function Template_get_link_url()
{
    $content = get_the_content();
    $has_url = get_url_in_content($content);

    return ($has_url) ? $has_url : apply_filters('the_permalink', get_permalink());
}

if (!function_exists('Template_posted_on')) :
    /**
     * Prints HTML with meta information for the current post-date/time and author.
     * Create your own Template_posted_on to override in a child theme
     */
    function Template_posted_on($date, $author)
    {
        $post_status = '';
        $time_string = '';
        $var_date = Template_theme_option('ttr_remove_date');
        $var_author = Template_theme_option('ttr_remove_author_name');
        $post_url =  urldecode(esc_url( get_permalink() ) );
        
            echo '<div class="postedon">';
            if (is_sticky() && is_home() && !is_paged()) {
                echo '<span class="featured-post"></span>';
                echo '<span style="clear:both;">' . __('Sticky', "mytheme") . '</span>';
            }
            if ( get_the_time() !== get_the_modified_time() ) 
			{
				if ($date && $author)
				{
					if ($var_date && $var_author)
					{
						$time_string = '<a href="' . $post_url . '" rel="bookmark"><img alt="' . __('date', "mytheme") . '" src="' . get_template_directory_uri() . '/images/datebutton.png"/><time class="updated" datetime="%3$s">%4$s</time><time class="entry-date post_updated published hidden d-none" datetime="%1$s">%2$s</time></a><span class = "meta">  ' . __('by ', "mytheme") . ' </span><span class="author vcard">  <img alt="' . __('author ', "mytheme") . '" src="' . get_template_directory_uri() . '/images/authorbutton.png"/>  <a href="%5$s" title="%6$s" class="url fn n" rel="author">%7$s</a></span>';
					   $post_status= __('Updated on ', "mytheme");
					}
					else if ($var_author)
					{
					  $time_string = '<span class = "meta">  ' . __('by ', "mytheme") . ' </span><span class="author vcard">  <img alt="' . __('author ', "mytheme") . '" src="' . get_template_directory_uri() . '/images/authorbutton.png"/>  <a href="%5$s" title="%6$s" class="url fn n" rel="author">%7$s</a></span>';
					}
					else if ($var_date)
					{
					   $time_string = '<img alt="' . __('date', "mytheme") . '" src="' . get_template_directory_uri() . '/images/datebutton.png"/><time class="updated" datetime="%3$s">%4$s</time><time class="entry-date post_updated published hidden d-none" datetime="%1$s">%2$s</time>';
					  $post_status= __('Updated on ', "mytheme");
					}
				}
				else if ($date && !$author) 
				{
					if ($var_date && $var_author)
					{
						$time_string = '<a href="' . $post_url . '" rel="bookmark"><img alt="' . __('date', "mytheme") . '" src="' . get_template_directory_uri() . '/images/datebutton.png"/><time class="updated" datetime="%3$s">%4$s</time><time class="entry-date post_updated published hidden d-none" datetime="%1$s">%2$s</time></a><span class = "meta">  ' . __('by ', "mytheme") . ' </span><span class="author vcard"> <a href="%5$s" title="%6$s" class="url fn n" rel="author">%7$s</a></span>';
					   $post_status= __('Updated on ', "mytheme");
					}
					else if ($var_author)
					{
					  $time_string = '<span class = "meta">  ' . __('by ', "mytheme") . ' </span><span class="author vcard"><a href="%5$s" title="%6$s" class="url fn n" rel="author">%7$s</a></span>';
					}
					else if ($var_date)
					{
					   $time_string = '<img alt="' . __('date', "mytheme") . '" src="' . get_template_directory_uri() . '/images/datebutton.png"/><time class="updated" datetime="%3$s">%4$s</time><time class="entry-date post_updated published hidden d-none" datetime="%1$s">%2$s</time>';
					  $post_status= __('Updated on ', "mytheme");
					}
				}
				else if (!$date && $author)
				{
					if ($var_date && $var_author)
					{
						$time_string = '<a href="' . $post_url . '" rel="bookmark"><time class="updated" datetime="%3$s">%4$s</time><time class="entry-date post_updated published hidden d-none" datetime="%1$s">%2$s</time></a><span class = "meta">  ' . __('by ', "mytheme") . ' </span><span class="author vcard">  <img alt="' . __('author ', "mytheme") . '" src="' . get_template_directory_uri() . '/images/authorbutton.png"/>  <a href="%5$s" title="%6$s" class="url fn n" rel="author">%7$s</a></span>';
					   $post_status= __('Updated on ', "mytheme");
					}
					else if ($var_author)
					{
					  $time_string = '<span class = "meta">  ' . __('by ', "mytheme") . ' </span><span class="author vcard">  <img alt="' . __('author ', "mytheme") . '" src="' . get_template_directory_uri() . '/images/authorbutton.png"/>  <a href="%5$s" title="%6$s" class="url fn n" rel="author">%7$s</a></span>';
					}
					else if ($var_date)
					{
					   $time_string = '<time class="updated" datetime="%3$s">%4$s</time><time class="entry-date post_updated published hidden d-none" datetime="%1$s">%2$s</time>';
					  $post_status= __('Updated on ', "mytheme");
					}
				}
				else
				{
					if ($var_date && $var_author)
					{
						$time_string = '<a href="' . $post_url . '" rel="bookmark"><time class="updated" datetime="%3$s">%4$s</time><time class="entry-date post_updated published hidden d-none" datetime="%1$s">%2$s</time></a><span class = "meta">  ' . __('by ', "mytheme") . ' </span><span class="author vcard"><a href="%5$s" title="%6$s" class="url fn n" rel="author">%7$s</a></span>';
					   $post_status= __('Updated on ', "mytheme");
					}
					else if ($var_author)
					{
					  $time_string = '<span class = "meta">  ' . __('by ', "mytheme") . ' </span><span class="author vcard"><a href="%5$s" title="%6$s" class="url fn n" rel="author">%7$s</a></span>';
					}
					else if ($var_date)
					{
					   $time_string = '<time class="updated" datetime="%3$s">%4$s</time><time class="entry-date post_updated published hidden d-none" datetime="%1$s">%2$s</time>';
					  $post_status= __('Updated on ', "mytheme");
					}	
				}
			}
			else
			{
				if ($date && $author)
				{
					if ($var_date && $var_author)
					{
						$time_string='<a href="' . $post_url . '" rel="bookmark"><img alt="' . __('date', "mytheme") . '" src="' . get_template_directory_uri() . '/images/datebutton.png"/><time class="entry-date published" datetime="%1$s">%2$s</time><time class="post_published updated hidden d-none" datetime="%3$s">%4$s</time></a><span class = "meta">  ' . __('by ', "mytheme") . ' </span> <span class="author vcard"><img alt="' . __('author ', "mytheme") . '" src="' . get_template_directory_uri() . '/images/authorbutton.png"/>   <a href="%5$s" title="%6$s" class="url fn n" rel="author">%7$s</a></span>';
					$post_status=  __('Posted on ', "mytheme");
					}
					else if ($var_author)
					{
					  $time_string = '<span class = "meta">  ' . __('by ', "mytheme") . ' </span><span class="author vcard">  <img alt="' . __('author ', "mytheme") . '" src="' . get_template_directory_uri() . '/images/authorbutton.png"/>  <a href="%5$s" title="%6$s" class="url fn n" rel="author">%7$s</a></span>';
					}
					else if ($var_date)
					{
					   $time_string='<img alt="' . __('date', "mytheme") . '" src="' . get_template_directory_uri() . '/images/datebutton.png"/><time class="entry-date published" datetime="%1$s">%2$s</time><time class="post_published updated hidden d-none" datetime="%3$s">%4$s</time>';
					$post_status=  __('Posted on ', "mytheme");
					}
				}
				else if ($date && !$author)
				{
					if ($var_date && $var_author)
					{
						$time_string='<a href="' . $post_url . '" rel="bookmark"><img alt="' . __('date', "mytheme") . '" src="' . get_template_directory_uri() . '/images/datebutton.png"/><time class="entry-date published" datetime="%1$s">%2$s</time><time class="post_published updated hidden d-none" datetime="%3$s">%4$s</time></a><span class = "meta">  ' . __('by ', "mytheme") . ' </span> <span class="author vcard"><a href="%5$s" title="%6$s" class="url fn n" rel="author">%7$s</a></span>';
					$post_status=  __('Posted on ', "mytheme");
					}
					else if ($var_author)
					{
					  $time_string = '<span class = "meta">  ' . __('by ', "mytheme") . ' </span><span class="author vcard"><a href="%5$s" title="%6$s" class="url fn n" rel="author">%7$s</a></span>';
					}
					else if ($var_date)
					{
					   $time_string='<img alt="' . __('date', "mytheme") . '" src="' . get_template_directory_uri() . '/images/datebutton.png"/><time class="entry-date published" datetime="%1$s">%2$s</time><time class="post_published updated hidden d-none" datetime="%3$s">%4$s</time>';
					$post_status=  __('Posted on ', "mytheme");
					}
				}
				elseif (!$date && $author)
				{
					if ($var_date && $var_author)
					{
						$time_string='<a href="' . $post_url . '" rel="bookmark"><time class="entry-date published" datetime="%1$s">%2$s</time><time class="post_published updated hidden d-none" datetime="%3$s">%4$s</time></a><span class = "meta">  ' . __('by ', "mytheme") . ' </span> <span class="author vcard"><img alt="' . __('author ', "mytheme") . '" src="' . get_template_directory_uri() . '/images/authorbutton.png"/>   <a href="%5$s" title="%6$s" class="url fn n" rel="author">%7$s</a></span>';
					$post_status=  __('Posted on ', "mytheme");
					}
					else if ($var_author)
					{
					  $time_string = '<span class = "meta">  ' . __('by ', "mytheme") . ' </span><span class="author vcard">  <img alt="' . __('author ', "mytheme") . '" src="' . get_template_directory_uri() . '/images/authorbutton.png"/>  <a href="%5$s" title="%6$s" class="url fn n" rel="author">%7$s</a></span>';
					}
					else if ($var_date)
					{
					   $time_string='<time class="entry-date published" datetime="%1$s">%2$s</time><time class="post_published updated hidden d-none" datetime="%3$s">%4$s</time>';
					$post_status=  __('Posted on ', "mytheme");
					}
				}
				else
				{
					if ($var_date && $var_author)
					{
						$time_string='<a href="' . $post_url . '" rel="bookmark"><time class="entry-date published" datetime="%1$s">%2$s</time><time class="post_published updated hidden d-none" datetime="%3$s">%4$s</time></a><span class = "meta">  ' . __('by ', "mytheme") . ' </span> <span class="author vcard"><a href="%5$s" title="%6$s" class="url fn n" rel="author">%7$s</a></span>';
					$post_status=  __('Posted on ', "mytheme");
					}
					else if ($var_author)
					{
					  $time_string = '<span class = "meta">  ' . __('by ', "mytheme") . ' </span><span class="author vcard"><a href="%5$s" title="%6$s" class="url fn n" rel="author">%7$s</a></span>';
					}
					else if ($var_date)
					{
					   $time_string='<time class="entry-date published" datetime="%1$s">%2$s</time><time class="post_published updated hidden d-none" datetime="%3$s">%4$s</time>';
					$post_status=  __('Posted on ', "mytheme");
					}
				}
			}
			
			$time_string = sprintf( $time_string,
					esc_attr(get_the_date( 'c' )),
					esc_html(get_the_date()),
					esc_attr(get_the_modified_date( 'c' )),
					esc_html(get_the_modified_date()),
					esc_url(get_author_posts_url(get_the_author_meta('ID'))),
					sprintf(esc_attr__('View all posts by %s', "mytheme"), esc_html(get_the_author())),
					esc_html(get_the_author())
					);
					
					
			// Wrap the time string in a link, and preface it with 'Posted on' or 'Updated On'.
			printf(__( '<span class="meta">%s</span>%s', 'mytheme' ),$post_status,$time_string );
           
            echo '</div>';
    }
endif;

/**
 * Adds two classes to the array of body classes.
 * The first is if the site has only had one author with published posts.
 * The second is if a singular post being displayed
 */

 function Template_body_classes($classes)
{

    if (!is_multi_author()) {
        $classes[] = 'single-author';
    }

    if (is_singular() && !is_home() && !is_page_template('showcase.php') && !is_page_template('sidebar-page.php'))
        $classes[] = 'singular';

    return $classes;
}

add_filter('body_class', 'Template_body_classes');

function Template_theme_curPageURL()
{
    $pageURL = 'http';
    if (!empty($_SERVER['HTTPS'])) {
        $pageURL .= "s";
    }
    $pageURL .= "://";
    if ($_SERVER["SERVER_PORT"] != "80" && $_SERVER["SERVER_PORT"] != "81" && $_SERVER["SERVER_PORT"] != "443") {
        $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
    } else {
        $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
    }
    return $pageURL;
}

function Template_theme_getsubmenu($menu_items, $parent)
{
    $submenu = array(); // all menu items under $menuID

    foreach ($menu_items as $key => $item) {
        if ($item->menu_item_parent == $parent->ID) {

            $submenu[] = $item;

        }
    }
    return $submenu;
}

// No longer need this function
/* function Template_generate_menu( $meenu, $Template_magmenu, $Template_menuh, $Template_vmenuh, $Template_ocmenu, $Template_cssprefix = "ttr_")
{
	global $Template_justify;
    $output = '';
    if (is_front_page()) {
        $output .= '<li class="' . $Template_cssprefix . $meenu . '_items_parent dropdown active"><a href="' . get_home_url(null, '/') . '" class="' . $Template_cssprefix . $meenu . '_items_parent_link_active"><span class="menuchildicon"></span>' . __('Home', "mytheme");
       
	  //  $output .= ('</a><hr class="horiz_separator" />'); 
		
        if ($Template_justify) {
              $output .= ('<hr class="horiz_separator" /> </a>');

        } else {
              $output .= ('</a><hr class="horiz_separator" />');
             }
						
        $output .= '</li>';
    } else {
        $output .= '<li class="' . $Template_cssprefix . $meenu . '_items_parent dropdown"><a href="' . get_home_url(null, '/') . '" class="' . $Template_cssprefix . $meenu . '_items_parent_link"><span class="menuchildicon"></span>' . __('Home', "mytheme");
       
	   // $output .= ('</a><hr class="horiz_separator" />'); 
		
        if ($Template_justify) {
              $output .= ('<hr class="horiz_separator" /> </a>');

        } else {
              $output .= ('</a><hr class="horiz_separator" />');
             }
		
        $output .= '</li>';
    }

    $pages = get_pages(array('child_of' => 0, 'hierarchical' => 0, 'parent' => 0, 'sort_column' => 'menu_order,post_title'));
  
   $count = count($pages);

    $count2 = 0;
    foreach ($pages as $key => $pagg) {
        if ($pagg->post_parent == 0)
                continue;
				$count2++;		
	    }
    $count1 = 0;

    foreach ($pages as $key => $pagg) {
        $childs = get_pages(array('child_of' => $pagg->ID, 'hierarchical' => 0, 'parent' => $pagg->ID, 'sort_column' => 'menu_order,post_title'));

         $count1++;
		 
        if (empty($childs)) {
            if (home_url() != untrailingslashit(get_permalink($pagg->ID))) {

                if (get_permalink() === get_permalink($pagg->ID)) {
                    $output .= '<li class="' . $Template_cssprefix . $meenu . '_items_parent dropdown active"><a href="' . get_permalink($pagg->ID) . '" class="' . $Template_cssprefix . $meenu . '_items_parent_link_active"><span class="menuchildicon"></span>' . $pagg->post_title;
				
				// 
              //   if ($key != ($count - 1))
                    //    $output .= ('<hr class="horiz_separator" />'); 
				     
						
                    if ($count1 != $count2) {
                        if ($Template_justify) {
                            $output .= ('<hr class="horiz_separator" /> </a>');

                        } else {
                            $output .= ('</a><hr class="horiz_separator" />');
                        }

                    } else {
					 $output .= ('</a>');
					}        

					$output .= '</li>';
                } else if (function_exists('Template_woocommerce_get_page_id') && (int)Template_woocommerce_get_page_id('shop') === $pagg->ID && is_shop()) {
                    $shop_page = (int)Template_woocommerce_get_page_id('shop');
                    if ($shop_page === $pagg->ID) {
                        $output .= '<li class="' . $Template_cssprefix . $meenu . '_items_parent dropdown active"><a href="' . get_permalink($pagg->ID) . '" class="' . $Template_cssprefix . $meenu . '_items_parent_link_active"><span class="menuchildicon"></span>' . $pagg->post_title;
                     
				 //  if ($key != ($count - 1))
                   //      $output .= ('<hr class="horiz_separator" />'); 
				      
							
                        if ($count1 != $count2) {
                            if ($Template_justify) {
                            $output .= ('<hr class="horiz_separator" /> </a>');

                            } else {
                            $output .= ('</a><hr class="horiz_separator" />');
                        }

                        } else {
					 $output .= ('</a>');
					}
						
                        $output .= '</li>';
                   }

                } else {
                    $output .= '<li class="' . $Template_cssprefix . $meenu . '_items_parent dropdown"><a href="' . get_permalink($pagg->ID) . '" class="' . $Template_cssprefix . $meenu . '_items_parent_link"><span class="menuchildicon"></span>' . $pagg->post_title;
                   
				   // if ($key != ($count - 1))
                      //  $output .= ('<hr class="horiz_separator" />'); 
					    
					
                    if ($count1 != $count2) {
                        if ($Template_justify) {
                            $output .= ('<hr class="horiz_separator" /> </a>');

                        } else {
                            $output .= ('</a><hr class="horiz_separator" />');
                        }

                    } else {
					 $output .= ('</a>');
					}
					
                    $output .= '</li>';
                }
            }
        } else {
            if (home_url() != untrailingslashit(get_permalink($pagg->ID))) {
                if (get_permalink() === get_permalink($pagg->ID)) {
                    $output .= '<li class="' . $Template_cssprefix . $meenu . '_items_parent dropdown active"><a href="' . get_permalink($pagg->ID) . '" class="' . $Template_cssprefix . $meenu . '_items_parent_link_active_arrow dropdown-toggle" data-toggle="dropdown" ><span class="menuchildicon"></span>' . $pagg->post_title;
                } else if (function_exists('Template_woocommerce_get_page_id') && (int)Template_woocommerce_get_page_id('shop') === $pagg->ID && is_shop()) {
                    $shop_page = (int)Template_woocommerce_get_page_id('shop');
                
                    if ($shop_page === $pagg->ID) {
                        $output .= '<li class="' . $Template_cssprefix . $meenu . '_items_parent dropdown active"><a href="' . get_permalink($pagg->ID) . '" class="' . $Template_cssprefix . $meenu . '_items_parent_link_active_arrow dropdown-toggle" data-toggle="dropdown" ><span class="menuchildicon"></span>' . $pagg->post_title;
                    }

                } else {
                    $output .= '<li class="' . $Template_cssprefix . $meenu . '_items_parent dropdown"><a href="' . get_permalink($pagg->ID) . '" class="' . $Template_cssprefix . $meenu . '_items_parent_link_arrow dropdown-toggle" data-toggle="dropdown" ><span class="menuchildicon"></span>' . $pagg->post_title;
                }
                }
           
		   // if ($key != ($count - 1))
               // $output .= ('<hr class="horiz_separator" />'); 
				
				
            if ($count1 != $count2) {
                if ($Template_justify) {
                            $output .= ('<hr class="horiz_separator" /></a>');

                } else {
                            $output .= ('</a><hr class="horiz_separator" />');
                        }

            } else {
					 $output .= ('</a>');
					}		
					
            $output .= Template_generate_level1_children($childs, $meenu, $Template_magmenu, $Template_menuh, $Template_vmenuh);
            $output .= '</li>';
        }
    }

    return $output;
} */

/*
Edit the widgets which are not exported from Template
*/

function filter_dynamic_sidebar_params( $sidebar_params ) {
  if ( is_admin() ) {
        return $sidebar_params;
    }
$widget_id = $sidebar_params[0]['widget_id'];
 $sidebar_params[0]['after_widget'] = str_replace('~tt', '', $sidebar_params[0]['after_widget']);
return $sidebar_params;
}
add_filter( 'dynamic_sidebar_params', 'filter_dynamic_sidebar_params' );   
                   
/**
 * Prints HTML with meta information for the current post-date/time and author.
 * Create your own Template_posted_on to override in a child theme
 */

function Template_theme_dynamic_sidebar($index)
{
 global $wp_registered_sidebars, $wp_registered_widgets, $Template_cssprefix, $params, $menuclass;
    $heading_tag = Template_theme_option('ttr_heading_tag_block');
    if(empty($heading_tag) || $heading_tag == Null){
 	$heading_tag = "h3";
 	}
    /*if ($heading_tag == 'choice1')
        $heading_tag = 'h1';
    elseif ($heading_tag == 'choice2')
        $heading_tag = 'h2';
    elseif ($heading_tag == 'choice3')
        $heading_tag = 'h3';
    elseif ($heading_tag == 'choice4')
        $heading_tag = 'h4';
    elseif ($heading_tag == 'choice5')
        $heading_tag = 'h5';
    elseif ($heading_tag == 'choice6')
        $heading_tag = 'h6';*/
  
    if (is_int($index)) {
        $index = "sidebar-$index";
       $i = 0;
    } else {
       $i = 0;
        $index = sanitize_title($index);
        foreach ((array)$wp_registered_sidebars as $key => $value) {
            if (sanitize_title($value['name']) == $index) {
                $index = $key;
                break;
            }
        }
    }

	$Template_sidebars_widgets = wp_get_sidebars_widgets();
    if (empty($Template_sidebars_widgets))
	        return false;

    if (empty($wp_registered_sidebars[$index]) || !array_key_exists($index, $Template_sidebars_widgets) || !is_array($Template_sidebars_widgets[$index]) || empty($Template_sidebars_widgets[$index]))
        return false;

    $sidebar = $wp_registered_sidebars[$index];

    ob_start();
    if (!dynamic_sidebar($index)) {
        return FALSE;
    }
    $sidebarcontent = ob_get_clean();

    $data = explode("~tt", $sidebarcontent);

    foreach ((array)$Template_sidebars_widgets[$index] as $id) {
      if(empty($wp_registered_widgets[$id]['name']) || is_null($wp_registered_widgets[$id]['name']))
        	{
        		continue;
        	}    
        $params = array_merge(
            array(array_merge((array)$sidebar, array('widget_id' => $id, 'widget_name' => $wp_registered_widgets[$id]['name']))),
            (array)$wp_registered_widgets[$id]['params']);
        if (!isset($data[$i])) {
            continue;
        }

        $classname_ = '';
        foreach ((array)$wp_registered_widgets[$id]['classname'] as $cn) {
            if (is_string($cn))
                $classname_ .= '_' . $cn;
            elseif (is_object($cn))
                $classname_ .= '_' . get_class($cn);
        }
        $classname_ = ltrim($classname_, '_');
        $params[0]['before_widget'] = sprintf($params[0]['before_widget'], $id, $classname_);
        $params = apply_filters('dynamic_sidebar_params', $params);

        $widget =  $data[$i];
		$i++;
       
        if (!is_string($widget) || strlen(str_replace(array('&nbsp;', ' ', "\n", "\r", "\t"), '', $widget)) == 0) continue;
        if (strlen(str_replace(array('&nbsp;', ' ', "\n", "\r", "\t"), '', $params[0]['before_title'])) == 0) {
            $widget = preg_replace('#(\'\').*?(' . $params[0]['after_title'] . ')#', '$1$2', $widget);
        }

        $pos = strpos($widget, $params[0]['after_title']);

        $widget_id = $params[0]['widget_id'];

        $widget_obj = $wp_registered_widgets[$widget_id];

        $widget_opt = get_option($widget_obj['callback'][0]->option_name);

        $widget_num = $widget_obj['params'][0]['number'];

        if (isset($widget_opt[$widget_num]['style'])) {
            $style = $widget_opt[$widget_num]['style'];
        } else
            $style = '';

        if ($style == "block") {
            if ($pos === FALSE) {

                $widget = str_replace($params[0]['before_widget'], '<div class = "' . $Template_cssprefix . 'block"> <div class="remove_collapsing_margins"></div>
			<div class = "' . $Template_cssprefix . 'block_without_header"> </div> <div id="' . $widget_id . '" class="' . $Template_cssprefix . 'block_content">', $widget);
            } else {
                $widget = str_replace($params[0]['before_widget'], '<div class="' . $Template_cssprefix . 'block"><div class="remove_collapsing_margins"></div> <div class="' . $Template_cssprefix . 'block_header">', $widget);
            }
           $params[0]['after_widget'] = str_replace('~tt', '', $params[0]['after_widget']);
            $widget = str_replace($params[0]['after_widget'], '</div></div>', $widget);
            $widget = str_replace($params[0]['after_title'], '</' . $heading_tag . '></div> <div id="' . $widget_id . '" class="' . $Template_cssprefix . 'block_content">', $widget);
            $widget = str_replace($params[0]['before_title'], '<' . $heading_tag . ' style="color:' . Template_theme_option('ttr_blockheading') . '; font-size:' . Template_theme_option('ttr_font_size_block') . 'px;" class="' . $Template_cssprefix . 'block_heading">', $widget);
        } else if ($style == "none") {
            $classname_ = '';
            foreach ((array)$wp_registered_widgets[$id]['classname'] as $cn) {
                if (is_string($cn))
                    $classname_ .= '_' . $cn;
                elseif (is_object($cn))
                    $classname_ .= '_' . get_class($cn);
            }
            $classname_ = ltrim($classname_, '_');
            $widget = str_replace($params[0]['before_widget'], sprintf('<aside id="%1$s" class="widget %2$s">', $id, $classname_), $widget);
            $params[0]['after_widget'] = str_replace('~tt', '', $params[0]['after_widget']);
            $widget = str_replace($params[0]['after_widget'], '</aside>', $widget);
            $widget = str_replace($params[0]['after_title'], '</h3>', $widget);
            $widget = str_replace($params[0]['before_title'], '<h3 class="widget-title">', $widget);
        } else {
            if ($index == 'sidebar-1' || $index == 'sidebar-2') {

                if ($pos === FALSE) {

                    $widget = str_replace($params[0]['before_widget'], '<div class = "' . $Template_cssprefix . 'block"> <div class="remove_collapsing_margins"></div>
			<div class = "' . $Template_cssprefix . 'block_without_header"> </div> <div id="' . $widget_id . '" class="' . $Template_cssprefix . 'block_content">', $widget);
                }
            }
        }

        echo $widget;

    }

    return true;
}

function Template_theme_comment_form($args = array(), $post_id = null)
{
    global $user_identity, $id;
    global $Template_cssprefix;
    
    if (null === $post_id)
        $post_id = $id;
    else
        $id = $post_id;

    $commenter = wp_get_current_commenter();

    $req = get_option('require_name_email');
    $aria_req = ($req ? " aria-required='true'" : '');
    $fields = array(
        'author' => '<p class="comment-form-author">' . '<label for="author">' . __('Name', "mytheme") . '</label> ' . ($req ? '<span class="required">*</span>' : '') . '<br/>' .
            '<input id="author" name="author" type="text" value="' . esc_attr($commenter['comment_author']) . '" size="30"' . $aria_req . ' /></p>',
        'email' => '<p class="comment-form-email"><label for="email">' . __('Email', "mytheme") . '</label> ' . ($req ? '<span class="required">*</span>' : '') . '<br/>' .
            '<input id="email" name="email" type="text" value="' . esc_attr($commenter['comment_author_email']) . '" size="30"' . $aria_req . ' /></p>',
        'url' => '<p class="comment-form-url"><label for="url">' . __('Website', "mytheme") . '</label>' . '<br/>' .
            '<input id="url" name="url" type="text" value="' . esc_attr($commenter['comment_author_url']) . '" size="30" /></p>',
    );

    $required_text = sprintf(' ' . __('Required fields are marked %s', "mytheme"), '<span class="required">*</span>');
    $defaults = array(
        'fields' => apply_filters('comment_form_default_fields', $fields),
        'comment_field' => '<p class="comment-form-comment"><label for="comment">' . _x('Comment', 'noun', "mytheme") . '</label><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea></p>' . '<br/>',
        'must_log_in' => '<p class="must-log-in">' . sprintf(__('You must be <a href="%s">logged in</a> to post a comment.', "mytheme"), wp_login_url(apply_filters('the_permalink', get_permalink($post_id)))) . '</p>',
        'logged_in_as' => '<p class="logged-in-as">' . sprintf(__('Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">Log out?</a>', "mytheme"), admin_url('profile.php'), $user_identity, wp_logout_url(apply_filters('the_permalink', get_permalink($post_id)))) . '</p>',
        'comment_notes_before' => '<p class="comment-notes">' . __('Your email address will not be published.', "mytheme") . ($req ? $required_text : '') . '</p>',
        'comment_notes_after' => '<p class="form-allowed-tags">' . sprintf(__('You may use these <abbr title="HyperText Markup Language">HTML</abbr> tags and attributes: %s', "mytheme"), ' <code>' . allowed_tags() . '</code>') . '</p>',
        'id_form' => 'commentform',
        'id_submit' => 'submit',
        'title_reply' => __('Leave a Reply', "mytheme"),
        'title_reply_to' => __('Leave a Reply to %s', "mytheme"),
        'cancel_reply_link' => __('Cancel reply', "mytheme"),
        'label_submit' => __('Post Comment', "mytheme"),
    );

    $args = wp_parse_args($args, apply_filters('comment_form_defaults', $defaults));

    ?>
<?php if (comments_open()) : ?>
<?php do_action('comment_form_before'); ?>
<!--<div id="respond">-->
<?php if (Template_theme_option('ttr_comments_form')): ?>
<div class="<?php echo esc_attr($Template_cssprefix); ?>comment comment-respond" id="respond">
    <div class="<?php echo esc_attr($Template_cssprefix); ?>comment_header">
      <div class="<?php echo esc_attr($Template_cssprefix); ?>comment_header_left_border_image">
        <div class="<?php echo esc_attr($Template_cssprefix); ?>comment_header_right_border_image">
                        </div>
                    </div>
                </div>
    <div class="<?php echo esc_attr($Template_cssprefix); ?>comment_content">
      <div class="<?php echo esc_attr($Template_cssprefix); ?>comment_content_left_border_image">
        <div class="<?php echo esc_attr($Template_cssprefix); ?>comment_content_right_border_image">

          <div class="<?php echo esc_attr($Template_cssprefix); ?>comment_content_inner">

            <h3 id="reply-title">
<?php comment_form_title($args['title_reply'], $args['title_reply_to']); ?>
<small>
<?php cancel_comment_reply_link($args['cancel_reply_link']); ?>
</small>
            </h3>
<?php if (get_option('comment_registration') && !is_user_logged_in()) : ?>
<?php echo $args['must_log_in']; ?>
<?php do_action('comment_form_must_log_in_after'); ?>
<?php else : ?>
<form action="<?php echo esc_url(site_url('/wp-comments-post.php')); ?>" method="post"
              id="<?php echo esc_attr($args['id_form']); ?>">
<?php do_action('comment_form_top'); ?>
<?php if (is_user_logged_in()) : ?>
<?php echo apply_filters('comment_form_logged_in', $args['logged_in_as'], $commenter, $user_identity); ?>
<?php do_action('comment_form_logged_in_after', $commenter, $user_identity); ?>
<?php else : ?>
<?php echo $args['comment_notes_before']; ?>
<?php
                do_action('comment_form_before_fields');
                foreach ((array)$args['fields'] as $name => $field) {
                    echo apply_filters("comment_form_field_{$name}", $field) . "\n";
                                            }
                do_action('comment_form_after_fields');
                                            ?>
<?php endif; ?>
<?php echo apply_filters('comment_form_field_comment', $args['comment_field']); ?>
<?php echo $args['comment_notes_after']; ?>
<div class="form-submit">
                <span class="<?php echo esc_attr($Template_cssprefix); ?>button"
                  onmouseover="this.className='<?php echo esc_attr($Template_cssprefix); ?>button_hover1';"
                  onmouseout="this.className='<?php echo esc_attr($Template_cssprefix); ?>button';">

                  <input name="ttr_comment_submit" class="btn btn-default" type="submit"
                         id="<?php echo esc_attr($args['id_submit']); ?>"
                  value="<?php echo esc_attr($args['label_submit']); ?>"/>
							</span>

                <div style="clear: both;"></div>
<?php comment_id_fields($post_id); ?>
</div>
<?php do_action('comment_form', $post_id); ?>
</form>
<?php endif; ?>
</div>

                        </div>
                    </div>
                </div>
    <div class="<?php echo esc_attr($Template_cssprefix); ?>comment_footer">
      <div class="<?php echo esc_attr($Template_cssprefix); ?>comment_footer_left_border_image">
        <div class="<?php echo esc_attr($Template_cssprefix); ?>comment_footer_right_border_image">
                        </div>
                    </div>
                </div>

                <!--	</div>--><!-- #respond -->
            </div>
<?php endif; ?>
<?php do_action('comment_form_after'); ?>
<?php else : ?>
<?php do_action('comment_form_comments_closed'); ?>
<?php endif; ?>
<?php
}

function Template_count_sidebar_widgets($sidebar_id)
{
    $the_sidebars = wp_get_sidebars_widgets();
    if (!isset($the_sidebars[$sidebar_id]))
        return FALSE;
    else
        return count($the_sidebars[$sidebar_id]);

}

function Template_add_init()
{
	global $post,$tt_post_type;
    wp_enqueue_script('jquery-ui-tabs');
    wp_enqueue_media();						// enqueue the media js, to be used at upload.js file
    wp_enqueue_style('jquery-ui', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.9/themes/smoothness/jquery-ui.css', false, '1.8.9', false);
    wp_enqueue_style('admin-style', get_template_directory_uri() . '/css/admin-style.css', array(), '1.0.0');
    wp_enqueue_script('import-script', get_template_directory_uri() . '/js/import-script.js', array(), '1.0.0', true);
    wp_localize_script( 'import-script','adminajax',array("ajaxurl"=>admin_url('admin-ajax.php')) );
    $screen = get_current_screen();
    if ($screen->id == 'appearance_page_mytheme-options') {
        wp_enqueue_style('thickbox');
        wp_register_script('upload', get_template_directory_uri() . '/js/upload.js', array('jquery', 'media-upload'));
        
        $tt_upload_data = array('title' => __('Choose or Upload an Image', "mytheme"), 'text' => __('Use this image', "mytheme"));
        wp_localize_script('upload','tt_upload_data',$tt_upload_data);
        wp_enqueue_script('upload');
       
       wp_register_script('addtextbox', get_template_directory_uri() . '/js/addtextbox.js', array(), 1.0, false);
        wp_enqueue_script('addtextbox', get_template_directory_uri() . '/js/addtextbox.js', array(), 1.0, false);

    }
    
   // Template custom ediotr cases managed for Wordpress5 TT imported pages.
    $fileName = get_template_directory() . '/content/imports.php';
    if (file_exists($fileName))
    {
    if(get_bloginfo('version') <  '5.0')
    {
    $id = get_the_ID();
    $page = get_post($id, OBJECT); 
    $page_meta = get_post_meta( $id, 'tt_pageID', true );
    $tt_post_type = get_post_type($id);
    
    if (($tt_post_type == 'page') || ($tt_post_type == 'post')) 
    {
    wp_register_script('ttr_post_button', get_template_directory_uri() . '/js/post_button.js', array('jquery'), '1.0.0', false);
    wp_enqueue_script('ttr_post_button');
    }
    }
		else
		{
		$id = get_the_ID();
		$page = get_post($id, OBJECT); 
		$page_meta = get_post_meta( $id, 'tt_pageID', true );
		$tt_post_type = get_post_type($id);
		$screen = get_current_screen();
		if (($tt_post_type == 'page' && !empty($page_meta)) || ($screen->id == 'appearance_page_mytheme-options'))  {
		add_action('admin_head', 'Template_customAdmin');  // Custom script files embedded for wordpress5 for TT imported pages only
		// removed post_button.js as not required in Wordpress5.
		}
	}
    }
}
add_action('admin_enqueue_scripts', 'Template_add_init');


function Template_options_setup()
{
    global $pagenow;

    if ('media-upload.php' == $pagenow || 'async-upload.php' == $pagenow) {
        // Now we'll replace the 'Insert into Post Button' inside Thickbox
        add_filter('gettext', 'Template_replace_thickbox_text', 1, 3);
    }
}

add_action('admin_init', 'Template_options_setup');

function Template_replace_thickbox_text($translated_text, $text, $domain)
{
    if ('Insert into Post' == $text) {
        $referer = strpos(wp_get_referer(), 'functions.php');
        if ($referer != '') {
            return __('Select this image!', "mytheme");
        }
    }
    return $translated_text;
}

function Template_customAdmin()
{
    global $post, $Template_cssprefix;
    $screen = get_current_screen();
	  $postid = ( isset( $post->ID ) ? get_the_ID() : NULL );
    wp_register_script('togglebutton', get_template_directory_uri() . '/js/jquery.toggle.buttons.js', array('jquery'), '2.8.2', false);
    wp_enqueue_script('togglebutton');
    //wp_register_script('expand', get_template_directory_uri() . '/js/expand.js', array('jquery'), '1.0.0', false);
    //wp_enqueue_script('expand');
    wp_register_script('widgetform', get_template_directory_uri() . '/js/widgetform.js', array('jquery'), '1.0.0', false);
    wp_enqueue_script('widgetform');
    wp_enqueue_script('toggleButtons', get_template_directory_uri() . '/js/toggleButtons.js', array('jquery'), '1.0.0', false);
    $fileName = get_template_directory() . '/content/imports.php';
    if (file_exists($fileName)) {
    $pageClass = get_post_meta($postid, 'tt_pageClass', true);
    $passed_data = array('on' => __('ON', "mytheme"), 'off' => __('OFF', "mytheme"), 'pageClass' => $pageClass);
        }
        else
	  $passed_data = array('on' => __('ON', "mytheme"), 'off' => __('OFF', "mytheme"));
    wp_localize_script('toggleButtons', 'passed_data', $passed_data);
    wp_register_style('bootstrap', get_template_directory_uri() . '/css/bootstrap-admin.css');
    wp_enqueue_style('bootstrap');
    wp_register_style('bootstrap-toggle-buttons', get_template_directory_uri() . '/css/bootstrap-toggle-buttons.css');
    wp_enqueue_style('bootstrap-toggle-buttons');
    
   // wp_register_script('jquery_tinymce_script', get_template_directory_uri() . '/js/jquery.tinymce.min.js', array('jquery', 'jquery-ui-core'), '1.2', false);
   // wp_enqueue_script('jquery_tinymce_script');
    wp_register_style('grideditor', get_template_directory_uri() . '/css/grideditor.css');
    wp_enqueue_style('grideditor');

	// Content row and cell css for backend editor.
	$iscontent = True;
	if ($iscontent) {
	    wp_register_style('contenteditor', get_template_directory_uri() . '/css/contenteditor.css');
	    wp_enqueue_style('contenteditor');
    }
	// ul list style image css
	$islisticon = True;
	echo '<style>';
	echo '.wp-admin .editor-styles-wrapper:not(.' . $Template_cssprefix . 'ecommerce) #' . $Template_cssprefix . 'content ul, .wp-admin .editor-styles-wrapper ul, .wp-admin .editor-styles-wrapper:not(.' . $Template_cssprefix . 'ecommerce) ul';
	if ($islisticon) {
		echo '{list-style-image:url('. get_stylesheet_directory_uri() . '/images/1935447439listimg.png);}';
	} else {
		echo '{list-style-image:none;}';
	}
	echo '</style>';
    }

// Custom script files check managed for Wordpress older version as for w5 embedded above based on page type condition
// enqueueing custom script for w5 for styling of post page theme options. 
add_action('admin_head', 'Template_customAdmin');

function Template_wordpress_breadcrumbs()
            {

    $name = __('Home', "mytheme"); //text for the 'Home' link
    $currentBefore = '<li><span class="current">';
    $currentAfter = '</span></li>';

    if (!is_home() && !is_front_page() || is_paged()) {

        echo '<ol class="breadcrumb">';

        global $post;
        $home = home_url();
        echo get_option("ttr_breadcrumb_text");
        echo '<li><a href="' . $home . '">' . $name . '</a></li>';

        if (is_category()) {
            global $wp_query;
            $cat_obj = $wp_query->get_queried_object();
            $thisCat = $cat_obj->term_id;
            $thisCat = get_category($thisCat);
            $parentCat = get_category($thisCat->parent);
            if ($thisCat->parent != 0) echo '<li>' . (get_category_parents($parentCat, TRUE, '')) . '</li>';
            echo $currentBefore . __('Archive by category &#39;', "mytheme");
            single_cat_title();
            echo '&#39;' . $currentAfter;

        } elseif (is_day()) {
            echo '<li><a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a></li>';
            echo '<li><a href="' . get_month_link(get_the_time('Y'), get_the_time('m')) . '">' . get_the_time('F') . '</a></li>';
            echo $currentBefore . get_the_time('d') . $currentAfter;

        } elseif (is_month()) {
            echo '<li><a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a></li>';
            echo $currentBefore . get_the_time('F') . $currentAfter;

        } elseif (is_year()) {
            echo $currentBefore . get_the_time('Y') . $currentAfter;

        } elseif (is_single()) {
            $cat = get_the_category();
            if (isset($cat) && !empty($cat)) {
                $cat = $cat[0];
                echo '<li>' . get_category_parents($cat, TRUE, '') . '</li>';
                echo $currentBefore;
                the_title();
                echo $currentAfter;
                }

        } elseif (is_page() && !$post->post_parent) {
            echo $currentBefore;
            the_title();
            echo $currentAfter;

        } elseif (is_page() && $post->post_parent) {
            $parent_id = $post->post_parent;
            $breadcrumbs = array();
            while ($parent_id) {
                $page = get_page($parent_id);
                $breadcrumbs[] = '<li><a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a></li>';
                $parent_id = $page->post_parent;
            }
            $breadcrumbs = array_reverse($breadcrumbs);
            foreach ($breadcrumbs as $crumb) echo $crumb;
            echo $currentBefore;
            the_title();
            echo $currentAfter;

        } elseif (is_search()) {
            echo $currentBefore . __('Search results for &#39;', "mytheme") . get_search_query() . '&#39;' . $currentAfter;

        } elseif (is_tag()) {
            echo $currentBefore . __('Posts tagged &#39;', "mytheme");
            single_tag_title();
            echo '&#39;' . $currentAfter;

        } elseif (is_author()) {
            global $author;
            $userdata = get_userdata($author);
            echo $currentBefore . __('Articles posted by', "mytheme") . $userdata->display_name . $currentAfter;

        } elseif (is_404()) {
            echo $currentBefore . __('Error 404', "mytheme") . $currentAfter;
		}

        if (get_query_var('paged')) {
            if (is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author()) echo ' (';
            echo __('Page', "mytheme") . ' ' . get_query_var('paged');
            if (is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author()) echo ')';
        }

        echo '</ol>';

    }
}

add_filter('sidebars_widgets', 'Template_sidebars_widgets');
//Add input fields(priority 5, 3 parameters)
add_action('in_widget_form', 'Template_in_widget_form', 5, 3);
//Callback function for options update (priority 5, 3 parameters)
add_filter('widget_update_callback', 'Template_in_widget_form_update', 5, 3);
function Template_sidebars_widgets($sidebars)
{
    if (is_admin()) {
        return $sidebars;
    }

    global $wp_registered_widgets;

    foreach ($sidebars as $s => $sidebar) {
        if ($s == 'wp_inactive_widgets' || strpos($s, 'orphaned_widgets') === 0 || empty($sidebar)) {
            continue;
        }

        foreach ($sidebar as $w => $widget) {
            // $widget is the id of the widget
            if (!isset($wp_registered_widgets[$widget])) {
                continue;
            }

            $opts = $wp_registered_widgets[$widget];
            $id_base = is_array($opts['callback']) ? $opts['callback'][0]->id_base : $opts['callback'];

            if (!$id_base) {
                continue;
            }

            $instance = get_option('widget_' . $id_base);

            if (!$instance || !is_array($instance)) {
                continue;
            }

            if (isset($instance['_multiwidget']) && $instance['_multiwidget']) {
                $number = $opts['params'][0]['number'];
                if (!isset($instance[$number])) {
                    continue;
                }

                $instance = $instance[$number];
                unset($number);
            }

            unset($opts);

            $show = Template_show_widget($instance);

            if (!$show) {
                unset($sidebars[$s][$w]);
            }

            unset($widget);
        }
        unset($sidebar);
    }

    return $sidebars;
}

function Template_show_widget($instance)
{
    global $wp_query;
    $post_id = $wp_query->get_queried_object_id();

    if (is_home()) {
        $show = isset($instance['page-home']) ? ($instance['page-home']) : false;
    } else if (is_front_page()) {
        $show = isset($instance['page-front']) ? ($instance['page-front']) : false;
    } else if (is_archive()) {
        $show = isset($instance['page-archive']) ? ($instance['page-archive']) : false;
    } else if (is_single()) {
        if (function_exists('get_post_type')) {
            $type = get_post_type();
            if ($type != 'page' and $type != 'post')
                $show = isset($instance['page-' . $type]) ? ($instance['page-' . $type]) : false;
        }

        if (!isset($show))
            $show = isset($instance['page-single']) ? ($instance['page-single']) : false;
    } else if (is_404()) {
        $show = isset($instance['page-404']) ? ($instance['page-404']) : false;
    } else if ($post_id) {
        $show = isset($instance['page-' . $post_id]) ? ($instance['page-' . $post_id]) : false;
    }

    if (!isset($show))
        $show = false;

    if ($show)
        return false;

    return $instance;
}

function Template_in_widget_form($t, $return, $instance)
{
    $instance = wp_parse_args((array)$instance, array('style' => 'default'));
    $pages = get_posts(array(
        'post_type' => 'page', 'post_status' => 'publish',
        'numberposts' => -1, 'orderby' => 'title', 'order' => 'ASC'
    ));
    $wp_page_types = array(
        'front' => __('Front', "mytheme"),
        'home' => __('Blog', "mytheme"),
        'archive' => __('Archives', "mytheme"),
        'single' => __('Single Post', "mytheme"),
        '404' => __('404', "mytheme")
    );

    ?>
<br/>
<label>
<?php echo(__('Hide widget on:', "mytheme")); ?>
</label>
  <div class="menupagecontainer">
    <div class="<?php echo $t->get_field_id(''); ?>">
      <button onclick="select_widget(this);" id="select_button" type="button" class="check-all">
        Select All
      </button>
      <button onclick="unselect_widget(this);" id="select_button" type="button" class="uncheck-all">
        UnSelect All
      </button>
<?php foreach ($pages as $page) {
	if ( $page->ID !== (int) get_option( 'page_on_front' ) && $page->ID !== (int)get_option( 'page_for_posts' ) ){
            $instance['page-' . $page->ID] = isset($instance['page-' . $page->ID]) ? $instance['page-' . $page->ID] : false;
        ?>
<div class="menupageelement">
        <input class="<?php echo esc_attr(sanitize_html_class($t->get_field_id(''))); ?> widgetcheckbox"
        type="checkbox" <?php checked($instance['page-' . $page->ID], true) ?>
        id="<?php echo esc_attr($t->get_field_id('page-' . $page->ID)); ?>"
        name="<?php echo esc_attr($t->get_field_name('page-' . $page->ID)); ?>"/>
        <label class="widgetlabel"
               for="<?php echo esc_attr($t->get_field_id('page-' . $page->ID)); ?>"><?php echo $page->post_title ?>
</label>
        </div>
<?php } } ?>
<?php foreach ($wp_page_types as $key => $label){
        $instance['page-'.$key] = isset($instance['page-'.$key]) ? $instance['page-'.$key] : false;
        ?>
<div class="menupageelement">
        <input class="<?php echo esc_attr(sanitize_html_class($t->get_field_id(''))); ?> widgetcheckbox"
        type="checkbox" <?php checked($instance['page-' . $key], true) ?>
        id="<?php echo esc_attr($t->get_field_id('page-' . $key)); ?>"
        name="<?php echo esc_attr($t->get_field_name('page-' . $key)); ?>"/>
        <label class="widgetlabel"
               for="<?php echo esc_attr($t->get_field_id('page-' . $key)); ?>
          "><?php echo $label . ' ' . __('Page', "mytheme") ?>
</label>
        </div>
<?php } ?>
</div>

  </div>
<?php if (!isset($instance['style']))
        $instance['style'] = null;
	?>
<label for="<?php echo $t->get_field_id('style'); ?>"><?php echo(__('Block Style:', "mytheme")); ?>
</label>
  <select id="<?php echo $t->get_field_id('style'); ?>" name="<?php echo $t->get_field_name('style'); ?>">
    <option
      <?php selected($instance['style'], 'default'); ?>value="default"><?php echo(__('Default', "mytheme")); ?>
</option>
    <option
      <?php selected($instance['style'], 'none'); ?>
      value="none"><?php echo(__('None', "mytheme")); ?>
</option>
    <option
      <?php selected($instance['style'], 'block'); ?>value="block"><?php echo(__('Block', "mytheme")); ?>
</option>
        </select>
<?php
    $retrun = null;
    return array($t, $return, $instance);
}

function Template_in_widget_form_update($instance, $new_instance, $old_instance)
{
    $pages = get_posts(array(
        'post_type' => 'page', 'post_status' => 'publish',
        'numberposts' => -1, 'orderby' => 'title', 'order' => 'ASC'
    ));
    if ($pages) {

        foreach ($pages as $page) {

            if (isset($new_instance['page-' . $page->ID])) {
                $instance['page-' . $page->ID] = 1;

            } else if (isset($instance['page-' . $page->ID]))
                unset($instance['page-' . $page->ID]);
            unset($page);
        }
    }

    foreach (array('front', 'home', 'archive', 'single', '404') as $page) {
        if (isset($new_instance['page-' . $page])) {
            $instance['page-' . $page] = 1;

        } else if (isset($instance['page-' . $page]))
            unset($instance['page-' . $page]);
    }
    $instance['style'] = $new_instance['style'];
    return $instance;
}

/**
 * Adds a box to the main column on the Post and Page edit screens.
 */

function Template_post_options_array()
{
    $postoptions = array(
        array("type" => "open"),
        array("name" => __("Display Post Title", "mytheme"),
            "desc" => "Check this box if you would like to DISABLE the Post Title",
            "id" => "ttr_post_title_checkbox",
            "type" => "checkbox",
            "std" => "true"),
        array("name" => __("Enable Post link", "mytheme"),
            "desc" => "Check this box if you would like to ENABLE the 'Post link'.",
            "id" => "ttr_post_link_enable_checkbox",
            "type" => "checkbox",
            "std" => "true"),
        array("type" => "close")
    );
    return $postoptions;
}

function Template_page_options_array()
{
    $pageoptions = array(
        array("type" => "open"),
        array("name" => __("Display Page Title", "mytheme"),
            "desc" => "Check this box if you would like to DISABLE the Page Title",
            "id" => "ttr_page_title_checkbox",
            "type" => "checkbox",
            "std" => "true"),
        array("name" => __("Display Footer", "mytheme"),
            "desc" => "Check this box if you would like to DISABLE the Page Footer",
            "id" => "ttr_page_foot_checkbox",
            "type" => "checkbox",
            "std" => "true"),
        array("name" => __("Header Background Style", "mytheme"),
            "desc" => "Select Box for Header Image size",
            "id" => "ttr_header_size_select",
            "type" => "select",
            "std" => "none",
            "options" => array(__("None", "mytheme"), __("Fill", "mytheme"), __("Horizontal Fill", "mytheme"), __("Vertical Fill", "mytheme"))),
        array("name" => __("Disable header Image Repeat", "mytheme"),
            "desc" => "Check this box if you dont want to repeat image",
            "id" => "ttr_background_repeat_enable_checkbox",
            "type" => "checkbox",
            "std" => "true"),
        array("name" => __("Header  Background Image", "mytheme"),
            "desc" => "Choose Header Image",
            "id" => "ttr_change_header_image_text",
            "type" => "media",
            "std" => ""),
        array("name" => __("Body Background Style", "mytheme"),
            "desc" => "Select Box for background Image size",
            "id" => "ttr_background_size_select",
            "type" => "select",
            "std" => "none",
            "options" => array(__("None", "mytheme"), __("Fill", "mytheme"), __("Horizontal Fill", "mytheme"), __("Vertical Fill", "mytheme"))),
        array("name" => __("Disable background Image Repeat", "mytheme"),
            "desc" => "Check this box if you dont want to repeat image",
            "id" => "ttr_header_repeat_enable_checkbox",
            "type" => "checkbox",
            "std" => "true"),
        array("name" => __("Body Background Image", "mytheme"),
            "desc" => "Text Box for Body Background",
            "id" => "ttr_custom_style_text",
            "type" => "media",
            "std" => ""),
        array("type" => "close")
    );
    return $pageoptions;
}


function Template_add_custom_box() {

    $screens = array( 'post', 'page' );

    foreach($screens as $screen)
    {
        add_meta_box(
            'post_page_options',
            __( 'Theme Options',"mytheme" ),
            'Template_custombox_in_publish',
            $screen,
            'side',
            'high',
            array(
        '__back_compat_meta_box' => false, // the meta box works in the block editor.
        )
        );
   }

}
add_action( 'add_meta_boxes', 'Template_add_custom_box' );


add_action( 'save_post', 'Template_save_postdata' );

function Template_custombox_in_publish()
	{
    global $post;
	$postid = ( isset( $post->ID ) ? get_the_ID() : NULL );
    if (  function_exists( 'Template_page_options_array' ) )
        $pageoptions = Template_page_options_array();
    if (  function_exists( 'Template_post_options_array' ) )
        $postoptions = Template_post_options_array();
    if ('page' != get_post_type($post) && 'post' != get_post_type($post)) return;

    if ('page' == get_post_type($post)):
        foreach ($pageoptions as $value) {
            switch ($value['type']) {

                case "open":
                    ?>
<table class="table table-hover table-bordered">
<?php 	break;

                case "close":
                    ?>
</table>
<?php   break;

                case "select":
                    ?><table class="table table-hover table-bordered">
                    <tr>
                        <td><h6><?php echo $value['name']; ?></h6></td>
                        <td ><select style="width:100px;" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>"><?php foreach ($value['options'] as $option) { ?><option<?php if ( get_post_meta($postid, $value['id'], true) == $option) { echo ' selected="selected"'; } ?>><?php echo $option; ?></option><?php } ?></select></td>
                    </tr>
<?php   break;

                case "checkbox":
                    ?>
<tr>
                        <td ><h6><?php echo $value['name']; ?></h6></td>

                        <td>
<?php
                            $var = get_post_meta($postid, $value['id'],true);?>
<?php if ((isset($var) && $var == 'true') || $var == '')
                            {
                                $checked = 'checked="yes"';
                            }
                            else
                            {
                                $checked = '';
                            }?>
<div class="normal-toggle-button">
                                <input type="checkbox" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" <?php echo $checked; ?> />
                            </div>
                        </td>
                    </tr>
<?php 	break;

                case 'media': ?>
<tr>
                        <td><h6><?php echo $value['name']; ?></h6></td>
                        <td>
                            <div class="uploader">

                                <input type="text" class="upload" style="width:100px;" id="<?php echo $value['id']; ?>" name="<?php  echo $value['id']; ?>" value="<?php if ( get_post_meta($postid, $value['id'], true) != "") { echo get_post_meta($postid, $value['id'], true); }?>" />

                                <input type= "button" style="margin-top:5px;" class="button" name="<?php echo $value['id']; ?>_button" id="<?php echo $value['id']; ?>_button" value="Upload" />
                            </div>
                            <script type="text/javascript">
                                jQuery(document).ready(function()
                                {
                                    var _custom_media = true,
                                        _orig_send_attachment = wp.media.editor.send.attachment;

                                    // ADJUST THIS to match the correct button
                                    jQuery('.uploader .button').click(function(e)
                                    {
                                        var send_attachment_bkp = wp.media.editor.send.attachment;
                                        var button = jQuery(this);
                                        var id = button.attr('id').replace('_button', '');
                                        _custom_media = true;
                                        wp.media.editor.send.attachment = function(props, attachment)
                                        {
                                            if ( _custom_media )
                                            {
                                                jQuery("#"+id).val(attachment.url);
                                            } else {
                                                return _orig_send_attachment.apply( this, [props, attachment] );
                                            };
                                        }

                                        wp.media.editor.open(button);
                                        return false;
                                    });

                                    jQuery('.add_media').on('click', function()
                                    {
                                        _custom_media = false;
                                    });
                                });
                            </script>
                        </td>
                    </tr>
                    </table>
                    <?php break;
            }
        }
        ?>
<?php

    endif;
    if ('post' == get_post_type($post)):
        foreach ($postoptions as $value) {
            switch ($value['type']) {

                case "open":
                    ?>
<table class="table table-hover table-bordered">
<?php 	break;

                case "close":
                    ?>
</table>
<?php   break;

                case "select":
                    ?>
<tr>
                        <td><h6><?php echo $value['name']; ?></h6></td>
                        <td ><select name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>"><?php foreach ($value['options'] as $option) { ?><option<?php if ( get_post_meta($postid, $value['id'], true) == $option) { echo ' selected="selected"'; } ?>><?php echo $option; ?></option><?php } ?></select></td>
                    </tr>

                    <?php   break;

                case "checkbox":
                    ?>
<tr>
                        <td ><h6><?php echo $value['name']; ?></h6></td>

                        <td>
<?php
                            $var = get_post_meta($postid, $value['id'],true);?>
<?php if ((isset($var) && $var == 'true') || $var == '')
                            {
                                $checked = 'checked="yes"';
                            }
                            else
                            {
                                $checked = '';
                            }?>
<div class="normal-toggle-button">
                                <input type="checkbox" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" <?php echo $checked; ?> />
                            </div>
                        </td>
                    </tr>
<?php 	break;
            }
        }
        ?>
<?php
    endif;
}

function Template_save_postdata( $post_id ) {

    if (  function_exists( 'Template_page_options_array' ) )
        $pageoptions = Template_page_options_array();
    if (  function_exists( 'Template_post_options_array' ) )
        $postoptions = Template_post_options_array();
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
        return;

    if ( defined( 'DOING_AJAX' ) && DOING_AJAX )
        return;

    if ( isset($_POST['post_type']) &&'page' != $_POST['post_type'] && 'post' != $_POST['post_type'] )
        return;

    if (isset($_POST['post_type']) && 'post' == $_POST['post_type']):
        foreach ($postoptions as $value) {
         if (isset($value['id']))
            {
            $mydata = $_POST[$value['id']];

            if (strpos($value['id'], "checkbox") !== false)
            {
                if (isset($mydata))
                {
                    $mydata = 'true';
                }
                else
                {
                    $mydata = 'false';
                }

                update_post_meta($post_id, $value['id'], $mydata);
            }
            elseif(strpos($value['id'], "text") !== false)
            {
                update_post_meta($post_id, $value['id'], $mydata);
            }
          }
        }
    endif;

    if (isset($_POST['post_type']) && 'page' == $_POST['post_type']):
        foreach ($pageoptions as $value) {
         if (isset($value['id']))
            {
            $mydata = $_POST[$value['id']];
            if (strpos($value['id'], "checkbox") !== false)
            {
                if (isset($mydata))
                {
                    $mydata = 'true';
                }
                else
                {
                    $mydata = 'false';
                }

                update_post_meta($post_id, $value['id'], $mydata);
            }
            elseif(strpos($value['id'], "text") !== false)
            {
                update_post_meta($post_id, $value['id'], $mydata);
            }
            elseif(strpos($value['id'], "select") !== false)
            {
                update_post_meta($post_id, $value['id'], $mydata);

            }
          }
        }
    endif;
}


add_action('wp_head', 'Template_slide_show');

function Template_slide_show()
{
     global $Template_no_slides, $Template_slide_show_visible, $Template_cssprefix, $post;
    /*if (function_exists('Template_slideshow_options_array'))
       $slideshowoptions = Template_slideshow_options_array();
    else
        $slideshowoptions = "";*/
   $tt_options = new Template_Theme_Options();
	 $slideshowoptions = $tt_options->settings;
    if ($Template_slide_show_visible):
        if (is_array($slideshowoptions)) {
            for ($i = 0; $i < $Template_no_slides; $i++) {
        		if(array_key_exists('ttr_slide_show_image' . $i, $slideshowoptions) && $slideshowoptions['ttr_slide_show_image' . $i]['type']== 'media'){
					  $slideimage = Template_theme_option('ttr_slide_show_image' . $i);
                            if (!empty($slideimage)) {
                            	echo "<style>";
                                if (Template_theme_option('ttr_slide_show_image' . $i) && Template_theme_option('ttr_horizontal_align' . $i) && Template_theme_option('ttr_vertical_align' . $i) && Template_theme_option('ttr_stretch' . $i)) {
                                    $stretch_option = Template_theme_option('ttr_stretch' . $i);

                                    if (strtolower($stretch_option) == strtolower("Uniform")) {
                                    $stretch = "/ contain";
                                    } 
                                    else if (strtolower($stretch_option) == strtolower("Uniform to Fill")) {
                                    $stretch = "/ cover";
                                    } 
                                    else if (strtolower($stretch_option) == strtolower("Fill")) {
                                    $stretch = "/ 100% 100%";
                                    }
                                    else{
									 $stretch = " ";
								    }
                                    echo '#Slide' . $i . '{background:url(' . Template_theme_option('ttr_slide_show_image' . $i) . ') no-repeat ' . Template_theme_option('ttr_horizontal_align' . $i) . ' ' . Template_theme_option('ttr_vertical_align' . $i) . '' . $stretch . ' !important;}';

                                } else if (Template_theme_option('ttr_slide_show_image' . $i)) {
                                    echo '#Slide' . $i . '{background:url(' . Template_theme_option('ttr_slide_show_image' . $i) . ') no-repeat scroll center center / 100% 100% !important;}';
                                } else {
                                    echo '#Slide' . $i . '{background:url(' . Template_theme_option('ttr_slide_show_image' . $i) . ') no-repeat scroll center center / 100% 100% ;}';
                            }
                            echo "</style>";
							}
						}
        		}
        }
    endif;

    if (get_option('ttr_post_title_color'))
     {
         echo '<style>.'.$Template_cssprefix.'post_title,.'.$Template_cssprefix.'post_title a,.'.$Template_cssprefix.'post_title a:visited{color:'. get_option('ttr_post_title_color').' !important}</style>';
     }

     if (get_option('ttr_post_title_hover_color'))
     {
         echo '<style>.'.$Template_cssprefix.'post_title a:hover{color:'. get_option('ttr_post_title_hover_color').' !important}</style>';
     }

	$postid = ( isset( $post->ID ) ? get_the_ID() : NULL );
    if(get_post_meta($postid,"ttr_background_size_select",true)):
        $a=get_post_meta($postid,"ttr_background_size_select",true);
        switch($a)
        {
            case "Fill":

                if(get_post_meta($postid,"ttr_custom_style_text",true)):
                    echo '<style>';
                    echo 'body {';
                    echo 'background:url('.get_post_meta($postid, 'ttr_custom_style_text', true).')';
                    if(get_post_meta($postid, 'ttr_header_repeat_enable_checkbox', true)=="true")
                        echo 'no-repeat';
                    else
                        echo "repeat";
                    echo ' !important;';
                    echo 'background-size:100% 100% !important;';

                    echo ' }</style>';
                endif;
                break;

            case "Horizontal Fill":
                if(get_post_meta($postid,"ttr_custom_style_text",true)):
                    echo '<style>';
                    echo 'body {';
                    echo 'background:url('.get_post_meta($postid, 'ttr_custom_style_text', true).')!important;';
                    echo 'background-size:auto 100% !important;';
                    if(get_post_meta($postid, 'ttr_header_repeat_enable_checkbox', true)=="true")
                        echo 'background-repeat:no-repeat !important;';
                    echo '}</style>';
                endif;
                break;

            case "Vertical Fill":
                if(get_post_meta($postid,"ttr_custom_style_text",true)):
                    echo '<style>';
                    echo 'body {';
                    echo 'background:url('.get_post_meta($postid, 'ttr_custom_style_text', true).')!important;';
                    echo 'background-size:100% auto !important;';
                    if(get_post_meta($postid, 'ttr_header_repeat_enable_checkbox', true)=="true")
                        echo 'background-repeat:no-repeat !important;';
                    echo '}</style>';
                endif;
                break;

            default:
                if(get_post_meta($postid,"ttr_custom_style_text",true)):
                    echo '<style>';
                    echo 'body {';
                    echo 'background:url('.get_post_meta($postid, 'ttr_custom_style_text', true).')!important;';
                    if(get_post_meta($postid, 'ttr_header_repeat_enable_checkbox', true)=="true")
                        echo 'background-repeat:no-repeat !important;';
                    echo '}</style>';
                endif;
        }
    endif;
    if(get_post_meta($postid,"ttr_header_size_select",true)):
        $a=get_post_meta($postid,"ttr_header_size_select",true);
        switch($a)
        {
            case "Fill":
                if(get_post_meta($postid,"ttr_change_header_image_text",true)):
                    echo '<style>';
                    echo 'header #' . $Template_cssprefix . 'header_inner{';  // To manage the priority of theme options styling.
                    echo 'background:url('.get_post_meta($postid, 'ttr_change_header_image_text', true).')!important;';
                    echo 'background-size:100% 100% !important;';
                    if(get_post_meta($postid, 'ttr_background_repeat_enable_checkbox', true)=="true")
                        echo 'background-repeat:no-repeat !important;';
                    echo '}</style>';
                endif;
                break;

            case "Horizontal Fill":
                if(get_post_meta($postid,"ttr_change_header_image_text",true)):
                    echo '<style>';
                    echo 'header #' . $Template_cssprefix . 'header_inner{';  // To manage the priority of theme options styling.
                    echo 'background:url('.get_post_meta($postid, 'ttr_change_header_image_text', true).')!important;';
                    echo 'background-size:auto 100% !important;';
                    if(get_post_meta($postid, 'ttr_background_repeat_enable_checkbox', true)=="true")
                        echo 'background-repeat:no-repeat !important;';
                    echo '}</style>';
                endif;
                break;

            case "Vertical Fill":
                if(get_post_meta($postid,"ttr_change_header_image_text",true)):
                    echo '<style>';
                    echo 'header #' . $Template_cssprefix . 'header_inner{';  // To manage the priority of theme options styling.
                    echo 'background:url('.get_post_meta($postid, 'ttr_change_header_image_text', true).')!important;';
                    echo 'background-size:100% auto !important;';
                    if(get_post_meta($postid, 'ttr_background_repeat_enable_checkbox', true)=="true")
                        echo 'background-repeat:no-repeat !important;';
                    echo '}</style>';
                endif;
                break;

            default:
                if(get_post_meta($postid,"ttr_change_header_image_text",true)):
                    echo '<style>';
                    echo 'header #' . $Template_cssprefix . 'header_inner{';  // To manage the priority of theme options styling.
                    echo 'background:url('.get_post_meta($postid, 'ttr_change_header_image_text', true).')!important;';
                    if(get_post_meta($postid, 'ttr_background_repeat_enable_checkbox', true)=="true")
                        echo 'background-repeat:no-repeat !important;';
                    echo '}</style>';
                endif;
                break;
        }
    endif;

    if (Template_theme_option('ttr_post_title_color')) {
        echo '<style>.' . $Template_cssprefix . 'post_title,.' . $Template_cssprefix . 'post_title a,.' . $Template_cssprefix . 'post_title a:visited{color:' . Template_theme_option('ttr_post_title_color') . ' !important}</style>';
    }

    if (Template_theme_option('ttr_post_title_hover_color')) {
        echo '<style>.' . $Template_cssprefix . 'post_title a:hover{color:' . Template_theme_option('ttr_post_title_hover_color') . ' !important}</style>';
    }

    if (Template_theme_option('ttr_logo_image_width') || Template_theme_option('ttr_logo_image_height')) {
        echo '<style> @media only screen and (min-width:1024px) { .' .  $Template_cssprefix . 'header_logo img{';

        if (Template_theme_option('ttr_logo_image_width')) {
            echo 'width:' . Template_theme_option('ttr_logo_image_width') . 'px !important;';
        }
        if (Template_theme_option('ttr_logo_image_height')) {
            echo 'height:' . Template_theme_option('ttr_logo_image_height') . 'px !important;';
        }
        echo '}}</style>';
    }

    $delimiter = Template_theme_option('ttr_breadcrumb_text_separator');
    if (!empty($delimiter)) {
        echo '<style>.breadcrumb > li + li:before
    {
	content: "' . $delimiter . '" !important;
}</style>';
    }
    }

add_action('wp_head', 'Template_add_custom_css');

function Template_add_custom_css()
    {
    global $Template_cssprefix;
    $post_title_color = Template_theme_option("ttr_post_title_color");
    $post_title_hover_color = Template_theme_option("ttr_post_title_hover_color");
    if (($post_title_color != '#') && !empty($post_title_color)) {
        echo '<style>.' . $Template_cssprefix . 'post_title, .' . $Template_cssprefix . 'post_title a,.' . $Template_cssprefix . 'post_title a:visited{color:' . $post_title_color . ' !important}</style>';
}

    if (($post_title_hover_color != '#') && !empty($post_title_hover_color)) {
        echo '<style>.' . $Template_cssprefix . 'post_title a:hover{color:' . $post_title_hover_color . ' !important}</style>';
    }
    $tt_custom_css = Template_theme_option('ttr_custom_style');
    if (isset($tt_custom_css) && !empty($tt_custom_css))
        echo '<style type="text/css">' . $tt_custom_css . '</style>';

	}
add_action('wp_footer', 'Template_add_googleanalytics');

function Template_add_googleanalytics() {
global $Template_cssprefix;
if(Template_theme_option('ttr_google_analytics_enable')):
    $ga= Template_theme_option('ttr_google_analytics');
	echo $ga;
	endif;
		
	$title = Template_theme_option("ttr_font_size_title");
  	$title_color = Template_theme_option("ttr_title");
  	$slogan = Template_theme_option("ttr_font_size_slogan");
  	$slogan_color = Template_theme_option("ttr_slogan");
   	$block_title = Template_theme_option("ttr_font_size_block");
  	$block_title_color = Template_theme_option("ttr_blockheading");
   	$sidebarmenu_title = Template_theme_option("ttr_font_size_sidebarmenu");
  	$sidebarmenu_title_color = Template_theme_option("ttr_sidebarmenuheading");
  	$copyright = Template_theme_option("ttr_font_size_copyright");
  	$copyright_color = Template_theme_option("ttr_copyright");
   	$designedby = Template_theme_option("ttr_font_size_designedby");
 	$designedby_color = Template_theme_option("ttr_designedby");
  	$designedbylink = Template_theme_option("ttr_font_size_designedbylink");
  	$designedbylink_color = Template_theme_option("ttr_designedbylink");
  		
if(!empty($title) || !empty($title_color) || !empty($slogan) || !empty($slogan_color) || !empty($block_title) || !empty($block_title_color) || !empty($sidebarmenu_title) || !empty($sidebarmenu_title_color) || !empty($copyright) || !empty($copyright_color) || !empty($designedby) || !empty($designedby_color) || !empty($designedbylink) || !empty($designedbylink_color)){

echo '<style type="text/css">
@media only screen
and (min-width:992px) 
{ ';

if(!empty($title) || !empty($title_color)){
echo 'header .'.$Template_cssprefix.'title_style a, header .'.$Template_cssprefix.'title_style a:link, header .'.$Template_cssprefix.'title_style a:visited, header .'.$Template_cssprefix.'_title_style a:hover{';
if(!empty($title)){
        echo   'font-size:'.$title.'px';
    }
    if(!empty($title_color)){
        echo 'color:'. $title_color;
    }
echo '}';
}

if(!empty($slogan) || !empty($slogan_color)){
echo '.'.$Template_cssprefix.'slogan_style{';
    if(!empty($slogan)){
        echo 'font-size:'. $slogan.'px';
    }
    if(!empty($slogan_color)){   
        echo 'color:'. $slogan_color;
    }
echo '}';
}

if(!empty($block_title) || !empty($block_title_color)){
echo 'h1.'.$Template_cssprefix.'block_heading, h2.'.$Template_cssprefix.'block_heading, h3.'.$Template_cssprefix.'block_heading, h4.'.$Template_cssprefix.'block_heading, h5.'.$Template_cssprefix.'block_heading, h6.'.$Template_cssprefix.'block_heading, p.'.$Template_cssprefix.'block_heading{
font-size:'. $block_title .'px;
color:'. $block_title_color.';
}';
}

if(!empty($sidebarmenu_title) || !empty($sidebarmenu_title_color)){
echo 'h1.'.$Template_cssprefix.'verticalmenu_heading, h2.'.$Template_cssprefix.'verticalmenu_heading, h3.'.$Template_cssprefix.'verticalmenu_heading, h4.'.$Template_cssprefix.'verticalmenu_heading, h5.'.$Template_cssprefix.'verticalmenu_heading, h6.'.$Template_cssprefix.'verticalmenu_heading, p.'.$Template_cssprefix.'verticalmenu_heading{
font-size:'. $sidebarmenu_title .'px;
color:'. $sidebarmenu_title_color.';
}';
}

if(!empty($copyright) || !empty($copyright_color)){
echo 'footer#'.$Template_cssprefix.'footer #'.$Template_cssprefix.'copyright a:not(.btn) {
font-size:'. $copyright .'px;
color:'. $copyright_color.';
}';
}

if(!empty($designedby) || !empty($designedby_color)){
echo 'footer#'.$Template_cssprefix.'footer span#'.$Template_cssprefix.'footer_designed_by{
font-size:'. $designedby .'px;
color:'. $designedby_color.';
}';
}

if(!empty($designedbylink) || !empty($designedbylink_color)){
echo 'footer#'.$Template_cssprefix.'footer #'.$Template_cssprefix.'footer_designed_by_links a:not(.btn), #'.$Template_cssprefix.'footer_designed_by_links a:link:not(.btn), #'.$Template_cssprefix.'footer_designed_by_links a:visited:not(.btn), #'.$Template_cssprefix.'footer_designed_by_links a:hover:not(.btn){
font-size:'. $designedbylink .'px;
color:'. $designedbylink_color.';
}';
}

echo '}
</style>';
}
}

add_action('template_redirect', 'Template_m_mode');

function Template_m_mode()
        {
    $mm_mode = Template_theme_option('ttr_mm_enable');
    if (!is_admin() && $mm_mode) {
        if (!is_user_logged_in()) {
            $file = get_template_directory() . '/maintenance-mode.php';
            include($file);
            exit();

        }
    }
}

function Template_theme_option($option)
{
	global $Template_options, $theme_options;
	
	$Template_options = get_option('Template_theme_options');
	
	if (!isset($Template_options) || !is_array($Template_options))
	{
		   if(isset($theme_options->settings[$option]))
			{
				return $theme_options->settings[$option]['std'];
			}
			else
			{
			 return null;	
			}
	}
	else
	{
		if(isset($Template_options[$option]))
		{
		//sanitize text field values in array
		$value = sanitize_text_field($Template_options[$option]);
	        $value_escaped = wp_specialchars_decode($value, 'both');
			return $value_escaped;
		}
	}
}

function Template_unset_options(){
    delete_option('Template_theme_options');
    delete_option( 'contact_form');
}

add_action("switch_theme", "Template_unset_options");

function Template_wp_title($title, $sep)
{
    if (is_feed()) {
		return $title;
    }

    global $page, $paged;

    // Add the blog name
    $title .= get_bloginfo('name', 'display');

    // Add the blog description for the home/front page.
    $site_description = get_bloginfo('description', 'display');
    if ($site_description && (is_home() || is_front_page())) {
        $title .= " $sep $site_description";
    }

    // Add a page number if necessary:
    if (($paged >= 2 || $page >= 2) && !is_404()) {
        $title .= " $sep " . sprintf(__('Page %s', "mytheme"), max($paged, $page));
    }

	return $title;
}

/*add_action('wp_head', 'Template_render_title');

function Template_render_title()
{
    $seomode = Template_theme_option('ttr_seo_enable');
    if ($seomode) {
        echo '<title>';
        $seo_enable = Template_theme_option('ttr_seo_rewrite_titles');
        if ($seo_enable)
            Template_rewrite_titles();
        else
            Template_original_titles();
        echo '</title>';


        $google_webmaster = Template_theme_option('ttr_seo_google_webmaster');
        $bing_webmaster = Template_theme_option('ttr_seo_bing_webmaster');
        $pinterst_webmaster = Template_theme_option('ttr_seo_pinterst_webmaster');
        if (!empty($google_webmaster))
            echo sprintf("<meta name=\"google-site-verification\" content=\"%s\"/>\n", $google_webmaster);
        if (!empty($bing_webmaster))
            echo sprintf("<meta name=\"msvalidate.01\" content=\"%s\"/>\n", $bing_webmaster);
        if (!empty($pinterst_webmaster))
            echo sprintf("<meta name=\"p:domain_verify\" content=\"%s\"/>\n", $pinterst_webmaster);
        if ((is_page() || is_single())) {
            $profile = Template_theme_option('ttr_seo_google_plus');
            if (!empty($profile)) {
                echo '<link href="' . $profile . '" rel="author"/>';
            } */
            /* else {
                $profile = Template_theme_option('googleplus');
                echo '<link href="' . $profile . '" rel="author" />';
            } */
       /* }

        $blog_title = get_option('blogname');
        $blog_desciprtion = get_option('blogdescription');
        $theme_path = get_template_directory_uri();
        $theme_path_content = get_template_directory_uri().'\content';
        if (is_single()) {
            if (Template_theme_option('ttr_seo_nonindex_post')) {
                $noindex = "noindex";
            } else {
                $noindex = "index";
            }
            if (Template_theme_option('ttr_seo_nofollow_post')) {
                $nofollow = "nofollow";
            } else {
                $nofollow = "follow";
            }
            echo sprintf("<!--Add by easy-noindex-nofollow--><meta name=\"robots\" content=\"%s, %s\"/>\n", $noindex, $nofollow);
        } else if (is_attachment()) {
            if (Template_theme_option('ttr_seo_nonindex_media')) {
                $noindex = "noindex";
            } else {
                $noindex = "index";
            }
            if (Template_theme_option('ttr_seo_nofollow_media')) {
                $nofollow = "nofollow";
            } else {
                $nofollow = "follow";
            }
            echo sprintf("<!--Add by easy-noindex-nofollow--><meta name=\"robots\" content=\"%s, %s\"/>\n", $noindex, $nofollow);
        } else if (is_home() || is_page() || is_paged()) {
            if (Template_theme_option('ttr_seo_nonindex_page')) {
                $noindex = "noindex";
            } else {
                $noindex = "index";
            }
            if (Template_theme_option('ttr_seo_nofollow_page')) {
                $nofollow = "nofollow";
            } else {
                $nofollow = "follow";
            }
            echo sprintf("<!--Add by easy-noindex-nofollow--><meta name=\"robots\" content=\"%s, %s\"/>\n", $noindex, $nofollow);
        } else if (is_date()) {
            if (Template_theme_option('ttr_seo_noindex_date_archive')) {
                $noindex = "noindex";
            } else {
                $noindex = "index";
            }
            echo sprintf("<!--Add by easy-noindex--><meta name=\"robots\" content=\"%s\"/>\n", $noindex);
        } else if (is_author()) {
            if (Template_theme_option('ttr_seo_noindex_author_archive')) {
                $noindex = "noindex";
            } else {
                $noindex = "index";
            }
            echo sprintf("<!--Add by easy-noindex--><meta name=\"robots\" content=\"s\"/>\n", $noindex);
        } else if (is_tag()) {
            if (Template_theme_option('ttr_seo_noindex_tag_archive')) {
                $noindex = "noindex";
            } else {
                $noindex = "index";
            }
            echo sprintf("<!--Add by easy-noindex--><meta name=\"robots\" content=\"%s\"/>\n", $noindex);
        }
        if (is_search()) {
            if (Template_theme_option('ttr_seo_noindex_search')) {
                $noindex = "noindex";
            } else {
                $noindex = "index";
            }
            if (Template_theme_option('ttr_seo_nofollow_search')) {
                $nofollow = "nofollow";
            } else {
                $nofollow = "follow";
            }
            echo sprintf("<!--Add by easy-noindex-nofollow--><meta name=\"robots\" content=\"%s, %s\"/>\n", $noindex, $nofollow);
        }
        if (is_category()) {
            if (Template_theme_option('ttr_seo_noindex_categories')) {
                $noindex = "noindex";
            } else {
                $noindex = "index";
            }
            if (Template_theme_option('ttr_seo_nofollow_categories')) {
                $nofollow = "nofollow";
            } else {
                $nofollow = "follow";
            }
            echo sprintf("<!--Add by easy-noindex-nofollow--><meta name=\"robots\" content=\"%s, %s\"/>\n", $noindex, $nofollow);
        }
        $home_header = Template_theme_option('ttr_seo_additional_fpage_header');
        $page_header = Template_theme_option('ttr_seo_additional_post_header');
        $post_header = Template_theme_option('ttr_seo_additional_page_header');
        if (is_home() && !empty($home_header)) {
            echo '<center><h1>' . $home_header . '</h1></center>';
        } else if (is_single() && !empty($page_header)) {
            echo '<center><h1>' . $page_header . '</h1></center>';
        } else if (is_page() && !empty($post_header)) {
            echo '<center><h1>' . $post_header . '</h1></center>';
        }
    } 
}*/

/*
add_filter('script_loader_tag', function ($tag, $handle) {

    if (is_admin()) {
        return $tag;
    }
    return str_replace(' src', ' defer="defer" src', $tag);

}, 10, 2);*/

function Template_comment_call($comment, $args, $depth) {
  global $Template_cssprefix;
    $GLOBALS['comment'] = $comment; ?>
<li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
    <div id="comment-<?php comment_ID(); ?>">
        <div class="<?php echo $Template_cssprefix; ?>comments">
            <div class="<?php echo $Template_cssprefix; ?>comment_author" style="width:<?php echo Template_theme_option('ttr_avatar_size')?>px;">
<?php echo get_avatar( $comment,Template_theme_option('ttr_avatar_size') ); ?>
</div>
            
            <div class="<?php echo $Template_cssprefix; ?>comment_text" style="float:none;width:auto;">
<span class="url"><a class="<?php echo $Template_cssprefix; ?>author_name" href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ) ?>"></a><?php
    echo get_comment_author_link( $comment->comment_ID ); ?>
</span>
                <time datetime="<?php echo get_comment_date("c")?>" class="comment-date">
                    <a class="<?php echo $Template_cssprefix; ?>comment_date" href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ) ?>"><?php printf(__('%1$s at %2$s', "mytheme"), get_comment_date(),  get_comment_time()) ?></a> </time>
                <hr style="color:#fff;"/>
<?php comment_text() ?>
<div style="clear:both"></div>
                <hr style="color:#fff;">
                <div style="clear:both"></div>
                <div class="<?php echo $Template_cssprefix; ?>comment_reply_edit">
                    <span class="<?php echo $Template_cssprefix; ?>reply_edit"><?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?></span>
                    <span class="<?php echo $Template_cssprefix; ?>reply_edit"><?php edit_comment_link(__('(Edit)', "mytheme")) ?></span>
                </div>
<?php if ($comment->comment_approved == '0') : ?>
<br /><em><?php _e('Your comment is awaiting approval.', "mytheme") ?></em>
<?php endif; ?>
</div>
            <div class="<?php echo $Template_cssprefix; ?>comment_author_right">
<?php  echo get_avatar( $comment,Template_theme_option('ttr_avatar_size') ); ?>
</div>
            <div style="clear:both"></div>
        </div>
    </div>
<?php }
require get_template_directory() . '/Template-walker-nav-menu.php';

function add_last_child($items) {
   $item = end($items);
   $item->classes[] = 'last';
   return $items;
}

add_filter('wp_nav_menu_objects', 'add_last_child');

function custom_contact_form_class( $class ) {
  $class .= ' form-horizontal';
  return $class;
}

add_filter( 'wpcf7_form_class_attr', 'custom_contact_form_class' );

function Template_require_plugins( $plugins )
{
$config = array( 'id' => 'Template-tgmpa',
'default_path' => get_stylesheet_directory() . '/lib/plugins/',
'menu' => 'Template-install-required-plugins', // menu slug
'has_notices' => true,
'dismissable' => false,
'is_automatic' => true,
'message' => '<!--Hey there.-->',
'strings' => array()
);
tgmpa( $plugins, $config );
} 

//adding logo to theme          
   function Template_custom_logo_setup() {
    $defaults = array(
        'height'      => Template_theme_option('ttr_logo_image_height'),
        'width'       => Template_theme_option('ttr_logo_image_width'),
        'flex-height' => true,
        'flex-width'  => true,
        'header-text' => array( 'site-title', 'site-description' ),
        
    );
    add_theme_support( 'custom-logo', $defaults );
}

// Add custom color control to theme
function theme_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'header_textcolor' )->transport  = 'refresh';
		/**
	 * Custom colors.
	 */
	$wp_customize->add_setting( 'colorscheme', array(
		'default'           => 'light',
		'transport'         => 'refresh',
		'sanitize_callback' => 'tt_sanitize_colorscheme',
	) );

	$wp_customize->add_control( 'colorscheme', array(
		'type'    => 'radio',
		'label'    => __( 'Color Scheme', 'mytheme' ),
		'choices'  => array(
			'light'  => __( 'Light', 'mytheme' ),
			'custom' => __( 'Custom', 'mytheme' ),
		),
		'section'  => 'colors',
		'priority' => 5,
	) );
	
	
	$wp_customize->add_setting( 'colorscheme_hue', array(
		'default'           =>  250,
		'transport'         => 'refresh',
		'sanitize_callback' => 'absint', // The hue is stored as a positive integer.
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'colorscheme_hue', array(
		'mode' => 'hue',
		'section'  => 'colors',
		'priority' => 6,
	) ) );

    
  }

  add_action( 'customize_register', 'theme_customize_register' );

          
 function theme_get_customizer_css() {
    ob_start();
    
    if ( 'custom' !== get_theme_mod( 'colorscheme' ) ) {
		return;
	}   

    $hue = absint( get_theme_mod( 'colorscheme_hue', 250 ) );

	/**
	 * Filter default saturation level.
	 *
	 *
	 * @param int $saturation Color saturation level.
	 */
	$saturation = absint( apply_filters( 'tt_custom_colors_saturation', 80 ) );
	$reduced_saturation = ( .8 * $saturation ) . '%';
	$saturation = $saturation . '%';
    
    $text_color  = $hue;
    if ( ! empty( $text_color ) ) {
      ?>
.nav.ttr_menu_items li a.ttr_menu_items_parent_link_active,.ttr_slogan_style, .html_content p, .html_content span, .html_content h2, .html_content h1, .html_content h3, .html_content h4, .html_content h5, .html_content h6, footer#ttr_footer #ttr_footer_designed_by_links a:not(.btn), #ttr_footer_designed_by_links a:link:not(.btn), #ttr_footer_designed_by_links a:visited:not(.btn), #ttr_footer_designed_by_links a:hover:not(.btn) {
        color: <?php echo ' hsl( ' . $text_color . ', ' . $saturation . ', 20% ) /* base: #000; */'?>;
      }
<?php
    }
    
    $link_color  = $hue;
    if ( ! empty( $link_color ) ) {
      ?>
a {
        color: <?php echo ' hsl( ' . $link_color . ', ' . $saturation . ', 20% ) /* base: #000; */'?>;
        border-bottom-color: <?php echo ' hsl( ' . $link_color . ', ' . $saturation . ', 20% ) /* base: #000; */'?>;
      }
<?php
    }

    $border_color = $hue;
    if ( ! empty( $border_color ) ) {
      ?>
footer ,.ttr_block_header,<?php echo'#'?>ttr_sidebar_right <?php echo'#'?>newsletter_block_left .block_header,<?php echo'#'?>ttr_sidebar_left <?php echo'#'?>newsletter_block_left .block_header,a:not(.btn), input[type="text"], input[type="search"], input[type="password"], input[type="email"], input[type="url"], input[type="tel"], select, input[type="text"], input[type="search"], input[type="password"], input[type="email"], input[type="url"], input[type="tel"], select, input[type="number"], .input-text.qty, body #ttr_content .cart .ttr_post input.input-text, .input-group input#search, .form-search #searchbox #search_query_top {
        border-color:<?php echo ' hsl( ' . $border_color . ', ' . $saturation . ', 20% ) /* base: #000; */' ?>;
      }
<?php
    }
    
    $accent_color  = $hue;
    if ( ! empty( $accent_color ) ) {
      ?>
a:hover {
         color: <?php echo ' hsl( ' . $accent_color . ', ' . $saturation . ', 20% ) /* base: #000; */'?>;
        border-bottom-color: <?php echo ' hsl( ' . $accent_color . ', ' . $saturation . ', 20% ) /* base: #000; */'?>;
      }

      button,
      input[type="submit"] {
        background-color: <?php echo ' hsl( ' . $accent_color . ', ' . $saturation . ', 20% ) /* base: #000; */'?>;
      }
<?php
    }
    
    $sidebar_background  = $hue;
    if ( ! empty( $sidebar_background ) ) {
      ?>
.sidebar {
        background-color: <?php echo ' hsl( ' . $sidebar_background . ', ' . $saturation . ', 20% ) /* base: #000; */'?>;
      }
<?php
    }

    $css = ob_get_clean();
    return $css;
  }

// Modify our styles registration like so:

function theme_enqueue_styles() {
  $custom_css = theme_get_customizer_css();
  wp_add_inline_style( 'style', $custom_css ); // style :-  handle of existing registered stylesheet
}
    
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles',100 );

/**
 * Load dynamic logic for the customizer controls area.
 */
function tt_panels_js() {
	wp_register_script('tt-customize-controls', get_template_directory_uri().'/js/tt_customize.js', array('jquery'), '1.0.0', false);
    wp_enqueue_script('tt-customize-controls');
}
add_action( 'customize_controls_enqueue_scripts', 'tt_panels_js' );

function tt_sanitize_colorscheme( $input ) {
	$valid = array( 'light', 'dark', 'custom' );

	if ( in_array( $input, $valid, true ) ) {
		return $input;
	}
	return 'light';
}


/**
 * Set up the WordPress core custom header feature.
 *
 * @uses Template_header_style()
 */
function Template_custom_header_setup() {

	/**
	 * Filter custom-header support arguments.
	 *
	 *
	 * @param array $args {
	 *     An array of custom-header support arguments.
	 *
	 *     @type string $default-image     		Default image of the header.
	 *     @type string $default_text_color     Default color of the header text.
	 *     @type int    $width                  Width in pixels of the custom header image. Default 954.
	 *     @type int    $height                 Height in pixels of the custom header image. Default 1300.
	 *     @type string $wp-head-callback       Callback function used to styles the header image and text
	 *                                          displayed on the blog.
	 *     @type string $flex-height     		Flex support for height of header.
	 * }
	 */
		 
add_theme_support( 'custom-header', apply_filters( 'Template_custom_header_args', array(
	'default-image'          => '',
	'flex-height'            => false,
	'flex-width'             => false,
	'uploads'                => true,
	'random-default'         => false,
	'header-text'            => true,
	'default-text-color'     => '',
	'wp-head-callback'       => '',
	'admin-head-callback'    => '',
	'admin-preview-callback' => ''
	) ) );
}

// add custom header image support
$header = True;
$head_logo = True;
if($header)
{
if($head_logo)
{
add_action( 'after_setup_theme', 'Template_custom_logo_setup' );
}
add_action( 'after_setup_theme', 'Template_custom_header_setup' );
}

function custom_video_active_callback() {
  if( !is_user_logged_in() && !is_home() ) {
    return true;
  }
  
  return false;
}
add_filter( 'is_header_video_active', 'custom_video_header_pages' );

function custom_video_header_pages( $active ) {
  if( is_home() || is_page() ) {
    return true;
  }

  return false;
}

// add custom header image support
$header_video = False;
function ttr_video_support(){
	add_theme_support( 'custom-header', array(
 	'video' => true,
  	'video-active-callback' => 'custom_video_active_callback'
	));
  }

if($header_video)
{
add_action( 'after_setup_theme', 'ttr_video_support' );
}

// updating customizer logo and css url to ttropt
function Template_customizersetting_to_themeoption(){
global $Template_options, $theme_options;
$custom_logo_id = get_theme_mod( 'custom_logo' );
$logo = wp_get_attachment_image_src( $custom_logo_id , 'full' );
$Template_options = get_option('Template_theme_options');
$content = wp_get_custom_css();
if (!isset($Template_options) || !is_array($Template_options))
{
	$default_settings = array();
        foreach ($theme_options->settings as $id => $setting) {
            if ($setting['type'] != 'heading')
                $default_settings[$id] = $setting['std'];
        }

		   if(isset($theme_options->settings['ttr_logo']))
			{	 
				 if(has_custom_logo()){
				 	$default_settings['ttr_logo'] = esc_url( $logo[0]);
                    $default_settings['ttr_header_logo_enable'] = "1";
                 }
                 else{
                    $default_settings['ttr_header_logo_enable'] = "0";
                 }				 
			}
			if(isset($theme_options->settings['ttr_favicon_image']))
			{
				 if(has_site_icon()){
				 $default_settings['ttr_favicon_image'] = get_site_icon_url();
				 }
				 else{
				 $default_settings['ttr_favicon_image'] = '';
				 }
			}
			if(isset($theme_options->settings['ttr_site_title_slogan_enable']) && display_header_text())
			{
				$default_settings['ttr_site_title_slogan_enable'] = 1 ;
			}else{
				$default_settings['ttr_site_title_slogan_enable'] = 0 ;
			}
		   if(isset($theme_options->settings['ttr_custom_style']) && $content)
			{
				 $default_settings['ttr_custom_style'] .= $content;	
			}
			
			update_option( 'Template_theme_options', $default_settings);
	}
	else
	{
		if(array_key_exists('ttr_logo', $Template_options))
		{		 
			if(has_custom_logo()){
				$Template_options['ttr_logo'] = esc_url( $logo[0]);
                $Template_options['ttr_header_logo_enable'] = "1";}
            else{
                $Template_options['ttr_header_logo_enable'] = "0";}
		}
		if(array_key_exists('ttr_favicon_image', $Template_options))
		{
		if(has_site_icon()){
			$Template_options['ttr_favicon_image'] = get_site_icon_url();
			 }
			 else{
			 $Template_options['ttr_favicon_image'] = '';
			 }		
		}
		if(array_key_exists('ttr_custom_style', $Template_options))
		{
			$Template_options['ttr_custom_style'] = $content;
		}
		if(display_header_text())
		{
			$Template_options['ttr_site_title_slogan_enable'] = "1";
			$Template_options['ttr_site_title_enable'] = "1";
			$Template_options['ttr_site_slogan_enable'] = "1";
		}else{
			$Template_options['ttr_site_title_slogan_enable'] = "0";
		}
		update_option( 'Template_theme_options', $Template_options);
	}
}
add_action( 'customize_save_after', 'Template_customizersetting_to_themeoption' );

function Template_export_xml()
{
	$theme_options = get_option('Template_theme_options');
	if($theme_options)
	{	
		$doc = new DOMDocument();			
		$xml = $doc->createElement('options');		
		$xml = $doc->appendChild($xml);	
			
		foreach ( $theme_options as $theme_option => $value)   
		{
			$option = $doc->createElement('option');			
			$option->appendChild($doc->createElement('name',$theme_option));
			$option->appendChild($doc->createElement('value',$value));
			$xml->appendChild($option);
		}
			
		$report = $doc->saveXML();
	}
	else
	{
        $report = "false"; 
    }
    
    echo $report;	
	die();
}
add_action('wp_ajax_export_xml','Template_export_xml');
add_action('wp_ajax_nopriv_export_xml','Template_export_xml');

function Template_import_xml()
{
	global $Template_options, $theme_options;
	$tt_options = get_option('Template_theme_options');
	
	if ($_POST['data'] != NULL)
	{
		$options = array();		
		$LoadXML = $_POST['data']['file'];
		$LoadXML1 = stripslashes($LoadXML);
		$LoadXML1 = str_replace('&rsquo;', '&#8217;', $LoadXML1);		
		$load_theme_options=simplexml_load_string($LoadXML1) or die(__('Error: Cannot create object','mytheme'));
		
		if ($load_theme_options->option)
		{
			if ($tt_options != NULL) 
			{
				$options = $tt_options;
			}
			else
			{
				foreach($theme_options->settings as $setting => $settingvalue)
				{
					$options[$setting] = $settingvalue['std'];				
				}	
			}		
		
			foreach($load_theme_options->option as $val)
			{
				$k = (string)$val->name;	
				$v = (string)$val->value;		
						
				if(array_key_exists($k, $options))
				{
				   $options[$k] = $v;
				}
			}	
					
			$result = update_option('Template_theme_options',$options, null);
					
			if($result)
			{
				die(__('Theme options imported successfully','mytheme'));				
			}
			else
			{
				die(__('Problem in importing theme options.','mytheme'));	
			}
		}
		else
		{
			die(__('Incorrect XML file.','mytheme'));
		}	
	}
	else
	{
		die(__('Please, upload an XML file only','mytheme'));
	}
}
add_action('wp_ajax_import_xml','Template_import_xml');
add_action('wp_ajax_nopriv_import_xml','Template_import_xml');

// Remove shop page breadcrumb according to theme option to  on/off breadcrumb on page.
add_action( 'init', 'remove_shop_breadcrumbs' );
function remove_shop_breadcrumbs() {
	$var = Template_theme_option('ttr_page_breadcrumb');
	if(!$var)
	{
		remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );
	}
	
}
function tt_style_load() {
	if ( ! function_exists( 'register_block_type' ) ) {
		// Gutenberg is not active.
		return;
    }
    else{
    // pass array to enqueue style for Gutenberg editor and classic editor
		add_editor_style( array(get_template_directory_uri() . '/css/bootstrap.css ', get_template_directory_uri() .'/style.css', get_template_directory_uri() . '/css/admin-style.css') );
	    add_theme_support( 'editor-styles' );
    }
}
add_action( 'after_setup_theme', 'tt_style_load' );



// uploading attachments 
 function action_add_attachment() { 
 		global $wp_filesystem;
        WP_Filesystem();
        $attachment_arr = array();
        $file_list = ttr_upload_list();
        foreach($file_list as $key => $value){
		$filename = basename($value);
		$parent_post_id = 0;
		$upload_file = wp_upload_bits($filename, null, $wp_filesystem->get_contents($value));
	if (!$upload_file['error']) {
	$wp_filetype = wp_check_filetype($filename, null );
	$test = wp_upload_dir();
	$attachment = array(
		'guid'           => $upload_file['url'],
		'post_mime_type' => $wp_filetype['type'],
		'post_parent' => $parent_post_id,
		'post_title' => preg_replace('/\.[^.]+$/', '', $filename),
		'post_content' => '',
		'post_status' => 'inherit'
	);
	$attachment_id = wp_insert_attachment( $attachment, $upload_file['file'], $parent_post_id );
	if (!is_wp_error($attachment_id)) {
		// Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
		if($key != "h_video"){
			require_once(ABSPATH . 'wp-admin/includes/image.php');
			$attachment_data = wp_generate_attachment_metadata( $attachment_id, $upload_file['file'] );		
			wp_update_attachment_metadata( $attachment_id,  $attachment_data );
		}		
		set_post_thumbnail( $parent_post_id, $attachment_id );
		}	
	  } 
	  $attachment_arr[$key] = $attachment_id ;
	}
	ttr_set_theme_prop($attachment_arr);
} 
 	add_action( 'after_switch_theme', 'action_add_attachment' );
  
// getting theme properties path  
   function ttr_upload_list(){
 		$path_arr = array();
 		$path = wp_upload_dir();
 		$tt_image_path = '';
 		$tt_image_dir = get_template_directory() . '/images/';
 		$tt_content_images = opendir($tt_image_dir);
        while ($tt_read_image = readdir($tt_content_images)) {
        	$file = glob($tt_image_dir."*header.*"); 
			if(!empty($file)) {    	  
            if ($tt_read_image != '.' && $tt_read_image != '..' && $tt_read_image == basename($file[0])) {
                if (!file_exists($tt_read_image)) {
                  $tt_image_path = $tt_image_dir . $tt_read_image;
                  break;   
                }
		 	  }
           }
        }     
       	    
        $tt_video_dir = get_template_directory() . '/video/HeaderVideo/';
        if(file_exists($tt_video_dir)){
			$tt_content_video = opendir($tt_video_dir);
        while ($tt_read_video = readdir($tt_content_video)) {
            if ($tt_read_video != '.' && $tt_read_video != '..') {
                if (!file_exists($tt_read_video)) {
                  $tt_video_path = $tt_video_dir . $tt_read_video;
                   break;   
                }
            }
          }
		}
        
		$tt_logo_path = get_template_directory(). '/logo.png';
		$tt_favicon_path = get_template_directory(). '/favicon.ico'; //// changed extension to small letters to solve favicon issue

		if($tt_image_path){
			$path_arr['h_image'] = $tt_image_path;
		}
		if(file_exists ($tt_logo_path)){
			$path_arr['h_logo'] = $tt_logo_path;
		}	
		if(file_exists ($tt_favicon_path)){
			$path_arr['site_icon'] = $tt_favicon_path;
		}
		if(file_exists ($tt_video_dir)){
			$path_arr['h_video'] = $tt_video_path;
		}	
		
		return $path_arr;	
	}
   
   
 // set theme properties  
 function ttr_set_theme_prop($all_attch_ids){
    						
    foreach($all_attch_ids as $key => $attach_id) {

    if($key == "h_image"){
		$header_image_data = (object) array(
				'attachment_id' => $attach_id,
				'url'           => get_permalink($attach_id),
			);
		
		set_theme_mod( 'header_image_data', $header_image_data );
       
	}  	

    if($key == "h_video"){
        set_theme_mod( 'header_video', $attach_id );
        }
    
     if($key == "h_logo"){
        set_theme_mod( 'custom_logo', $attach_id );
        }
        
    if($key == "site_icon"){
        update_option( 'site_icon', $attach_id);
       }
    }   
	return;		
	} 
	
	// storing old theme positions & widgets.
	add_action('switch_theme', 'old_theme_positions');
    function old_theme_positions () {
        update_option('tt_previous_theme',get_option( 'sidebars_widgets' ));
    }
    
    // inactive and restore widgets according to theme positions.
    add_action('after_switch_theme', 'new_theme_positions');
    function new_theme_positions () {
        // execute only if tt_previous_theme option available in db.
        if(get_option( 'tt_previous_theme' )) {
        	// get sidebars_widgets of previous theme.
	        $old_sidebars_widgets = get_option( 'tt_previous_theme' );
	        
	        // get sidebars_widgets of new theme.
	        $new_sidebars_widgets = get_option( 'sidebars_widgets' );
            
	        if(is_array($old_sidebars_widgets) && is_array($new_sidebars_widgets)){
	        $previous_widgets = array();
            foreach($old_sidebars_widgets as $old_widget_pos => $old_widgets)
            {
                foreach (is_array($old_widgets) ? $old_widgets : array() as $old_widget_id => $oldwidget)
                {
                    $oldwidget = explode("-",$oldwidget); // split widget name in which 0 for type and 1 for id.
                if (count($oldwidget) > 2)
                {
                    $oldarray = array($oldwidget[0], $oldwidget[1]);
                    $oldwidget[0] = implode("-",$oldarray);
                }
                array_push($previous_widgets, $oldwidget[0]);
                }
            }

                foreach($new_sidebars_widgets as $new_widget_pos => $new_widgets)
                {
                    foreach (is_array($new_widgets) ? $new_widgets : array() as $new_widget_id => $newwidget)
                    {
                        $newwidget = explode("-",$newwidget); // split widget name in which 0 for type and 1 for id.
                        if (count($newwidget) > 2)
                        {
                            $newarray = array($newwidget[0], $newwidget[1]);
                            $newwidget[0] = implode("-",$newarray);
                            $newwidget[1] = $newwidget[2];
                            array_pop($newwidget);
                        }
                        if ( in_array( $newwidget[0], $previous_widgets ) )
                        {
                            unset($new_sidebars_widgets[$new_widget_pos][$new_widget_id]);
                        }                        
                }
                }
                
	            // get diff of positions.
	            $diff_sidebar_positions = array_diff_key($old_sidebars_widgets,$new_sidebars_widgets);
	                    
	            // go through all diff position and move widget to wp_inactive_widgets.
	            foreach ($diff_sidebar_positions as $diff_sidebar_position => $diff_widgets) {
	                foreach (is_array($diff_widgets) ? $diff_widgets : array() as $diff_widget_id => $diff_widget) {
	                    foreach ($new_sidebars_widgets as $widgets_position => $widgets) {
	                        if (in_array($diff_widget, is_array($widgets) ? $widgets : array())){
	                            foreach ($widgets as $widget_id => $widget) {
	                                if ($diff_widget == $widget) {
	                                    unset($new_sidebars_widgets[$widgets_position][$widget_id]);
	                                }
	                            }
	                        }
	                    }
	                    if (!in_array($diff_widget, $new_sidebars_widgets['wp_inactive_widgets'])) {
	                        array_push($new_sidebars_widgets['wp_inactive_widgets'],$diff_widget);
	                    }
	                    unset($diff_sidebar_positions[$diff_sidebar_position][$diff_widget_id]);
	                }
	            }
			}
		}
		 else
        {
	        // get sidebars_widgets of new theme.
            $new_sidebars_widgets = get_option( 'sidebars_widgets' );  
            if(is_array($new_sidebars_widgets))
            {
                foreach($new_sidebars_widgets as $new_widget_pos => $new_widgets)
                {
                    foreach (is_array($new_widgets) ? $new_widgets : array() as $new_widget_id => $newwidget)
                    {
                        unset($new_sidebars_widgets[$new_widget_pos][$new_widget_id]);
                    }
                }
            }
        }
        // update sidebar_widgets to db.
	    update_option('sidebars_widgets', $new_sidebars_widgets );
	    update_option('tt_previous_theme', "" );
    }
    
    // Allowing localexplorer Protocol in external link.
    add_filter( 'kses_allowed_protocols', function ( $protocols ) {
        $protocols[] = 'localexplorer';
        return $protocols;
    } );
?>