<?php
class Template_custom_Menu extends WP_Widget {

    function __construct() {
    // setting the widget
        $widget_ops = array( 'description' => __('Use this widget to add one of your custom menus as a widget.',"mytheme") );
        parent::__construct( 'nav_menu', __('Custom Menu',"mytheme"), $widget_ops );
    }

    function widget($args, $instance){
        //dislpay the widget
        // Get menu
        global $Template_cssprefix;
        global $Template_mhicon;
        global $Template_magmenu, $Template_menuh, $Template_vmenuh, $Template_ocmenu;
        global $Template_fontSize1, $Template_style1, $Template_sidebarmenuheading;
        $Template_style1="";
        $Template_sidebarmenuheading = Template_theme_option('ttr_sidebarmenuheading');
        $Template_fontSize1 = Template_theme_option('ttr_font_size_sidebarmenu');
        if(!empty($Template_sidebarmenuheading)){
            $Template_style1 .= "color:".$Template_sidebarmenuheading.";";
        }
        if(!empty($Template_fontSize1)){
            $Template_style1 .= "font-size:".$Template_fontSize1."px;";
        }

        $nav_menu = wp_get_nav_menu_object( $instance['nav_menu'] );
        $alignment=$instance['alignment'];
        $style=$instance['style'];
        $menustyle=$instance['menustyle'];
        $custom_menu_tag = Template_theme_option('ttr_heading_tag_sidebarmenu');
		    if(empty($custom_menu_tag) || is_null($custom_menu_tag)){
			    $custom_menu_tag = "h3";
		    }
        

        if ( !$nav_menu )
            return;

        if($style == 'block')
        {
	        if($alignment=='nostyle'){
	        echo  '<div id="'.$args['widget_id'].'" class="'.$Template_cssprefix.'verticalmenu"><div class="margin_collapsetop"></div>';
	        }
	        else
	        {
	        echo  '<div class="'.$Template_cssprefix.'verticalmenu"><div class="margin_collapsetop"></div>';
	        }
	        
            if ( !empty($instance['title']) )
            {
                if($Template_mhicon)
                {
                    echo '<div class="'.$Template_cssprefix.'block_header">'.'<'.$custom_menu_tag.' style="'.$Template_style1.'" class="'.$Template_cssprefix.'block_heading">
                    <img src="'.get_template_directory_uri().'/images/verticalmenuicon.png" alt="Verticalmenuicon" />'. $instance['title'] .'</'.$custom_menu_tag.'></div>';
                }
                else
                {
                    echo '<div class="'.$Template_cssprefix.'block_header">'.'<'.$custom_menu_tag.' style="'.$Template_style1.'" class="'.$Template_cssprefix.'block_heading">'. $instance['title'] .'</'.$custom_menu_tag.'></div>';
                }
            }
        }

        elseif($style == 'default')
        {
	        if($alignment=='nostyle')
	        {
	        echo  '<div id="'.$args['widget_id'].'" class="'.$Template_cssprefix.'verticalmenu"><div class="margin_collapsetop"></div>';
	        }
	        else
	        {
	        echo '<div class="'.$Template_cssprefix.'verticalmenu"><div class="margin_collapsetop"></div>';
	        }
            if ( !empty($instance['title']) )
            {
                if($Template_mhicon)
                {
                    echo '<div class="'.$Template_cssprefix.'verticalmenu_header">'.'<'.$custom_menu_tag.' style="'.$Template_style1.'" class="'.$Template_cssprefix.'verticalmenu_heading">
                <img src="'.get_template_directory_uri().'/images/verticalmenuicon.png" alt="Verticalmenuicon" />' . $instance['title'] .'</'.$custom_menu_tag.'></div>';
                }
                else
                {
                    echo '<div class="'.$Template_cssprefix.'verticalmenu_header">'.'<'.$custom_menu_tag.' style="'.$Template_style1.'" class="'.$Template_cssprefix.'verticalmenu_heading">'. $instance['title'] .'</'.$custom_menu_tag.'></div>';
                }
            }
        }
        else
        {
          echo '<div class="box widget">';
          if ( !empty($instance['title']) )
          {
          echo '<div class="widget-title">'.$args['before_title'] . $instance['title'] . $args['after_title'].'</div>';
          }                               
        }

      /* 
        if ( !$nav_menu )
                    return;
             echo $args['before_widget'];
        
         if ( isset($instance['title']) )
             echo $args['before_title'] . $instance['title'] . $args['after_title'];
         */   

        if($alignment=='nostyle'){
            if(!isset($args['widget_id']))
                $args['widget_id'] ='';
           
           if($menustyle=='hmenu')
           {
	           wp_nav_menu( array(
	           'menu' => $nav_menu,
	           'theme_location' => 'primary', 			// Add this to remove error when heck theme with Theme Check plugin
	           'menu_class'      => $Template_cssprefix.'hmenu_items nav nav-pills nav-stacked',
	           'class_name'	=>$args['widget_id'], 
	           'walker'        => new Template_Walker_Sidebar_Horizontal_NoStyle_Nav_Menu(),
	           ) );
           }
           else
           {
	            wp_nav_menu( array( 
	            'menu' 			=> $nav_menu,
	            'class_name'	=>$args['widget_id'], 
	            'walker'        => new Template_Walker_Sidebar_Vertical_NoStyle_Nav_Menu(),
	            ) );
           }
        }
        else if($alignment=='default')
        {
            if($style=='default')
            {
                if($menustyle=='hmenu')
                {
                    wp_nav_menu( array(
                    'walker'          => new Template_Walker_Sidebar_Horizontal_Nav_Menu(),
                    'menu_class'      => $Template_cssprefix.'menu_items nav nav-pills nav-stacked',
                    'container_class' => $Template_cssprefix.'verticalmenu_content navbar',
					'container'       => 'nav',
                    'menu' => $nav_menu,
                    ) );
                }
                else if($menustyle=='vmenu')
                {
                    if ($Template_magmenu)
                    {
	                    wp_nav_menu( array(
	                    'walker'          => new Template_Walker_Sidebar_Verticalm_Nav_Menu(),
	                    'menu_class'      => $Template_cssprefix.'vmenu_items nav nav-pills nav-stacked',
	                    'container_class' => $Template_cssprefix.'verticalmenu_content navbar',
					 	'container'       => 'nav',
	                    'menu' => $nav_menu,
                    	) );
                    }
                    else 
                    {
                    if ($Template_vmenuh)
                    {
	                    wp_nav_menu( array(
	                    'walker'          => new Template_Walker_Sidebar_Verticalh_Nav_Menu(),
	                    'menu_class'      => $Template_cssprefix.'vmenu_items nav nav-pills nav-stacked',
	                    'container_class' => $Template_cssprefix.'verticalmenu_content navbar',
					 	'container'       => 'nav',
	                    'menu' => $nav_menu,
	                    ) );
                    }
                    else if($Template_menuh)
                    {
	                    wp_nav_menu( array(
	                    'walker'          => new Template_Walker_Sidebar_Vertical_Nav_Menu(),
	                    'menu_class'      => $Template_cssprefix.'vmenu_items nav nav-pills nav-stacked',
	                    'container_class' => $Template_cssprefix.'verticalmenu_content navbar',
					 	'container'       => 'nav',
	                    'menu' => $nav_menu,
	                    ) );
                    }
                    else
                    {
	                    wp_nav_menu( array(
	                    'walker'          => new Template_Walker_Sidebar_EVertical_Nav_Menu(),
	                    'menu_class'      => $Template_cssprefix.'vmenu_items nav nav-pills nav-stacked',
	                    'container_class' => $Template_cssprefix.'verticalmenu_content navbar',
					    'container'       => 'nav',
	                    'menu' => $nav_menu,
	                    ) );
                    }
                    }
                }
            }
            elseif($style=='block')
            {
                if($menustyle=='hmenu')
                {
                    wp_nav_menu( array(
                    'walker'          => new Template_Walker_Sidebar_Horizontal_Nav_Menu(),
                    'menu_class'      => $Template_cssprefix.'menu_items nav nav-pills nav-stacked',
                    'container_class' => $Template_cssprefix.'block_content',
                    'container_id'    => '%1$s',
                    'menu' => $nav_menu,
                    ) );
                }
                else if($menustyle=='vmenu')
                {
                    wp_nav_menu( array(
                    'walker'          => new Template_Walker_Sidebar_Vertical_Nav_Menu(),
                    'menu_class'      => $Template_cssprefix.'vmenu_items nav nav-pills nav-stacked',
                    'container_class' => $Template_cssprefix.'verticalmenu_content navbar',
					'container'       => 'nav',
                    'menu_id'         => '%1$s',
                    'menu' => $nav_menu,
                    ) );
                }
            }
            else
            {
            	if($menustyle=='hmenu')
                {
                    wp_nav_menu( array(		
                    'walker'          => new Template_Walker_Sidebar_Horizontal_Nav_Menu(),
                    'menu_class'      => $Template_cssprefix.'menu_items nav nav-pills nav-stacked',
                    'container_class' => '',
                    'menu' => $nav_menu,                    
                    ) );	
                 }
                else if($menustyle=='vmenu')
                {
                    wp_nav_menu( array(	
                    'walker'         => new Template_Walker_Sidebar_Vertical_Nav_Menu(),
                    'menu_class'     => $Template_cssprefix.'menu_items nav nav-pills nav-stacked',
                    'container_class' => '',
                    'menu' => $nav_menu,
                    ) );	
                }		
			}

        }
            
      // echo $args['after_widget'];
       echo '</div>';
        
    }
    
    // update the widget
    function update($new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = strip_tags( stripslashes($new_instance['title']) );
        $instance['nav_menu'] = (int) $new_instance['nav_menu'];
        $instance['alignment'] = $new_instance['alignment'];
        $instance['menustyle'] = $new_instance['menustyle'];
        $instance['style'] = $new_instance['style'];
        $instance['color1'] = $new_instance['color1'];
        $instance['color2'] = $new_instance['color2'];
        $instance['color3'] = $new_instance['color3'];

        return $instance;
    }

    function form( $instance) {//form of the widget
        $instance = wp_parse_args( (array) $instance, array( 'style' => 'default','menustyle' => 'hmenu','color1'=>'#ffffff','color2'=>'#ffffff','color3'=>'#ffffff','alignment' => 'nostyle') );
        $title = isset( $instance['title'] ) ? $instance['title'] : '';
        $nav_menu = isset( $instance['nav_menu'] ) ? $instance['nav_menu'] : '';
        wp_register_script('menucolorpicker', get_template_directory_uri() . '/js/menucolorpicker.js', array('jquery', 'wp-color-picker'), '1.0.0', true);
        wp_enqueue_script('menucolorpicker', get_template_directory_uri() . '/js/menucolorpicker.js', array('jquery', 'wp-color-picker'), '1.0.0', true);
        wp_enqueue_style('wp-color-picker');


        if ( !isset($instance['style']) )
            $instance['style'] = null;
        if ( !isset($instance['menustyle']) )
            $instance['menustyle'] = null;
        if ( !isset($instance['alignment']) )
            $instance['alignment'] = null;
        // Get menus
        $menus = get_terms( 'nav_menu', array( 'hide_empty' => false ) );

        // If no menus exists, direct the user to go and create some.
        if ( !$menus ) {
            echo '<p>'. sprintf( __('No menus have been created yet. <a href="%s">Create some</a>.',"mytheme"), admin_url('nav-menus.php') ) .'</p>';
            return;
        }

        ?>
        <p>
          <label for ="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', "mytheme") ?></label>
          <input type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $title; ?>"/>
        </p>

        <p>
          <label for ="<?php echo $this->get_field_id('nav_menu'); ?>"><?php _e('Select Menu:', "mytheme"); ?></label>
          <select class="fullwidth" id="<?php echo $this->get_field_id('nav_menu'); ?>" name="<?php echo $this->get_field_name('nav_menu'); ?>">
               <?php
                foreach ( $menus as $menu ) {
                    $selected = $nav_menu == $menu->term_id ? ' selected="selected"' : '';
                    echo '<option'. $selected .' value="'. $menu->term_id .'">'. $menu->name .'</option>';
                }
                ?>
          </select>
        </p>

        <div class="menuoptions">

            <label for="<?php echo $this->get_field_id('alignment'); ?>"><?php echo(__('Menu Display:',"mytheme"));?>
            </label>
            <select class ="fullwidth" onchange="color_display(this);" id="<?php echo $this->get_field_id('alignment'); ?>" name="<?php echo $this->get_field_name('alignment'); ?>">
                <option <?php selected($instance['alignment'], 'nostyle');?> value="nostyle"><?php _e('No Style',"mytheme"); ?>
                </option>
                <option <?php selected($instance['alignment'], 'default');?>value="default"><?php _e('TT Default',"mytheme"); ?>
                </option>
            </select>
            <br />
            <?php if($instance['alignment']=='nostyle')
            { ?>
             <label class= "cmenu_style" for="<?php echo $this->get_field_id('menustyle'); ?>" style="display:none;"><?php echo(__('Menu Style:',"mytheme"));?>
            </label>
            <select class ="fullwidth cmenu_style" id="<?php echo $this->get_field_id('menustyle'); ?>" name="<?php echo $this->get_field_name('menustyle'); ?>" style="display:none;">
           <?php }
            else
            { ?>
            <label class="cmenu_style" for="<?php echo $this->get_field_id('menustyle'); ?>"><?php echo(__('Menu Style:',"mytheme"));?>
            </label>
            <select class ="fullwidth cmenu_style" id="<?php echo $this->get_field_id('menustyle'); ?>" name="<?php echo $this->get_field_name('menustyle'); ?>">
          <?php } ?>
                <option <?php selected($instance['menustyle'], 'hmenu');?>value="hmenu"><?php _e('Horizontal Menu',"mytheme"); ?>
                </option>
                <option <?php selected($instance['menustyle'], 'vmenu');?>value="vmenu"><?php _e('Vertical Menu',"mytheme"); ?>
                </option>
            </select>
            <?php if($instance['alignment']=='default'){
                echo ' <div class="menucolorpickers" style="display:none;">';
            }
            else
            {
                echo  '<div class="menucolorpickers" >';
            }
            ?>
          <div class="colorpickercontainer">
            <label for="wp-picker-container">
              <?php _e('Active Color', "mytheme"); ?>
            </label>
            <input class="cw-color-picker" type="text"
                   name="<?php echo $this->get_field_name('color1'); ?>"
            id="<?php echo $this->get_field_name('color1'); ?>"
            value="<?php echo $instance['color1']; ?>"/><br/>
          </div>

          <div class="colorpickercontainer">
            <label for="wp-picker-container">
              <?php _e('Hover Color', "mytheme"); ?>
            </label>
            <input class="cw-color-picker" type="text"
                   name="<?php echo $this->get_field_name('color2'); ?>"
            id="<?php echo $this->get_field_name('color2'); ?>"
            value="<?php if (isset($instance['color2'])) {
                           echo $instance['color2'];
                       } else {
                           echo '#ffffff';
                       } ?>"/><br/>
          </div>

          <div class="colorpickercontainer">
            <label for="wp-picker-container">
              <?php _e('Normal Color', "mytheme"); ?>
            </label>
            <input class="cw-color-picker" type="text"
                   name="<?php echo $this->get_field_name('color3'); ?>"
            id="<?php echo $this->get_field_name('color3'); ?>"
            value="<?php if (isset($instance['color3'])) {
                           echo $instance['color3'];
                       } else {
                           echo '#ffffff';
                       } ?>"/><br/>
          </div>
        </div>
        </div>            
        <?php

        return $instance;
    }
}

// register the widget
function Template_register_widgets() {
    register_widget( 'Template_custom_Menu' );
}

add_action( 'widgets_init', 'Template_register_widgets' );//function to load my widget


if (!function_exists('Template_unregister_default_wp_widgets')) {
    function Template_unregister_default_wp_widgets() {
        unregister_widget('WP_Nav_Menu_Widget');
    }
    add_action('widgets_init', 'Template_unregister_default_wp_widgets', 1);
}


function Template_style() {
    global $wp_registered_sidebars,$wp_registered_widgets;

    $sidebars_widgets = wp_get_sidebars_widgets();
    //print_r($wp_registered_sidebars);
    foreach((array) $wp_registered_sidebars as $index => $value)
    {


        if ( empty($wp_registered_sidebars[$index]) || !array_key_exists($index, $sidebars_widgets) || !is_array($sidebars_widgets[$index]) || empty($sidebars_widgets[$index]) )
            continue;
        $sidebar = $wp_registered_sidebars[$index];

        foreach ( (array) $sidebars_widgets[$index] as $id ) {
        
            if(empty($wp_registered_widgets[$id]['name']) || is_null($wp_registered_widgets[$id]['name']))
        	{
        		continue;
        	}
            
            $params = array_merge(
                array( array_merge( (array)$sidebar, array('widget_id' => $id, 'widget_name' => $wp_registered_widgets[$id]['name']) ) ),
                (array) $wp_registered_widgets[$id]['params']);
            $widget_id = $params[0]['widget_id'];

            $widget_obj = $wp_registered_widgets[$widget_id];

            $widget_opt = get_option($widget_obj['callback'][0]->option_name);

            $widget_num = $widget_obj['params'][0]['number'];

            if(isset($widget_opt[$widget_num]['alignment']))
                $alignment = $widget_opt[$widget_num]['alignment'];
            else
                $alignment = '';

            if(isset($widget_opt[$widget_num]['menustyle']))
            {
                $menustyle = $widget_opt[$widget_num]['menustyle'];
            }
            else
                $menustyle = '';

            if(isset($widget_opt[$widget_num]['color1']))
                $color1 = $widget_opt[$widget_num]['color1'];
            else
                $color1 = '';
            if(isset($widget_opt[$widget_num]['color2']))
                $color2 = $widget_opt[$widget_num]['color2'];
            else
                $color2 = '';
            if(isset($widget_opt[$widget_num]['color3']))
                $color3 = $widget_opt[$widget_num]['color3'];
            else
                $color3 = '';

            if($params[0]['widget_name']==__('Custom Menu',"mytheme")){

                if($alignment=='nostyle'){

                    echo '<style>';

                    echo '#'.$widget_id.' ul li a{color:'.$color3.' !important;}';

                    echo'#'.$widget_id.' ul li.current-menu-item a{color:'.$color1.' !important;}';

                    echo '#'.$widget_id.' ul li a:hover{color:'.$color2.' !important;}';
                    if($menustyle=='hmenu')
                    {
                        echo '#'.$widget_id.' ul li {display:inline !important;}';
                    }
                    else
                    {
                        echo '#'.$widget_id.' ul li {list-style:none !important;}';
                    }

                    echo '#'.$widget_id.' li a:visited{color:'.$color3.' !important;}</style>';
                }
            }
        }
    }
}

add_action( 'wp_head', 'Template_style' );
?>