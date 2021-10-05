<?php
/**
 * Nav Menu API: Template_Walker_Nav_Menu class
 *
 * @package WordPress
 * @subpackage Nav_Menus
 * @since 4.6.0
 */

/**
 * Core class used to implement an HTML list of nav menu items.
 *
 * @since 3.0.0
 *
 * @see Walker
 */
class Template_Walker_Vertical_Nav_Menu extends Walker {
	
	public $tree_type = array( 'post_type', 'taxonomy', 'custom' );

	public $db_fields = array( 'parent' => 'menu_item_parent', 'id' => 'db_id' );

	public function start_lvl( &$output, $depth = 0, $args = array() ) {
		
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent = str_repeat( $t, $depth );
		
		if ($depth == 0) 
		{
		$output .= "{$n}{$indent}<ul class=\"child dropdown-menu\">{$n}";
		}
		else
		{
			$output .= "{$n}{$indent}<ul class=\"sub-menu dropdown-menu menu-dropdown-styles\">{$n}";
		}
	}

	public function end_lvl( &$output, $depth = 0, $args = array() ) {
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent = str_repeat( $t, $depth );
		$output .= "$indent</ul>{$n}";
	}

	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent = ( $depth ) ? str_repeat( $t, $depth ) : '';

		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;
		global $Template_cssprefix;
		if ($item->menu_item_parent == 0)
		{
        	$classes[] = $Template_cssprefix.'menu_items_parent';
	   	}
	   	else
	   	{
			$classes[] = 'dropdown-submenu';
		}
		
		$classes[] = 'dropdown';
		
	    // Comment this code to solve menu case when change Permalink setting at backend. We compare this code with wordpress core Walker-nav-menu class code but this code is not there. This is the custom code which caused the issue.We tested all menu cases after removing this code. 
	/*	$current_url = set_url_scheme( 'http://' . $_SERVER['HTTP_HOST'] . untrailingslashit( $_SERVER['REQUEST_URI'] ) . '/' );
		if ($item->url != $current_url && in_array('current-menu-item', $classes)) {
			unset($classes[array_search('current-menu-item', $classes)]);
		} */
		
   		if (in_array('current-menu-item', $classes) )
		{
			$classes[] = 'active';
		}
	
		$args = apply_filters( 'nav_menu_item_args', $args, $item, $depth );

		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args, $depth );
		$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

		$output .= $indent . '<li' . $id . $class_names .'>';

		$atts = array();
		$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target'] = ! empty( $item->target )     ? $item->target     : '';
		$atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';
		$atts['href']   = ! empty( $item->url )        ? $item->url        : '';

		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( ! empty( $value ) ) {
				$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}

		/** This filter is documented in wp-includes/post-template.php */
		$title = apply_filters( 'the_title', $item->title, $item->ID );

		$title = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );
		
		$item_output = $args->before;
		
		if ($item->menu_item_parent != 0)
		{
			if(in_array('menu-item-has-children', $classes))
	   		{
			$item_output .= '<a'. $attributes .' class="subchild dropdown toggle" data-toggle="dropdown">';
			}
			else
			{
				$item_output .= '<a'. $attributes .'>';	
			}
		}
		else
		{
		if ((in_array('menu-item-has-children', $classes)) && in_array('current-menu-item', $classes) )
		{
			$item_output .= '<a'. $attributes .' class="'.$Template_cssprefix.'menu_items_parent_link_active_arrow dropdown-toggle" data-toggle="dropdown">';
		}
		else if (in_array('menu-item-has-children', $classes) )
		{
			$item_output .= '<a'. $attributes .' class="'.$Template_cssprefix.'menu_items_parent_link_arrow dropdown-toggle" data-toggle="dropdown">';
		}
		else if (in_array('current-menu-item', $classes) )
		{
			$item_output .= '<a'. $attributes .' class="'.$Template_cssprefix.'menu_items_parent_link_active">';
		}
		else
		{
			$item_output .= '<a'. $attributes .' class="'.$Template_cssprefix.'menu_items_parent_link">';
		}
		}
		
		$item_output .= ('<span class="menuchildicon"></span>');
		$item_output .= $args->link_before . $title . $args->link_after;
		
		if (!in_array('last', $classes) )
		{
			if ($item->menu_item_parent != 0)
			{
				$item_output .= ('<span class="menuarrowicon"></span></a><hr class="separator" />');
	        }
	        else
	        {
				$item_output .= ('<span class="menuarrowicon"></span></a><hr class="horiz_separator" />');
			}
		}
		else
		{
			$item_output .= ('<span class="menuarrowicon"></span></a>');
		}
		
		//$item_output .= '</a>';
		$item_output .= $args->after;

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}

	public function end_el( &$output, $item, $depth = 0, $args = array() ) {
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$output .= "</li>{$n}";
	}

} // Walker_Nav_Menu

class Template_Walker_Horizontal_Nav_Menu extends Walker {

	public $tree_type = array( 'post_type', 'taxonomy', 'custom' );

	public $db_fields = array( 'parent' => 'menu_item_parent', 'id' => 'db_id' );

	public function start_lvl( &$output, $depth = 0, $args = array() ) {
		
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent = str_repeat( $t, $depth );
		
		if ($depth == 0) 
		{
		$output .= "{$n}{$indent}<ul class=\"child dropdown-menu\">{$n}";
		}
		else
		{
			$output .= "{$n}{$indent}<ul class=\"sub-menu\">{$n}";
		}
	}

	public function end_lvl( &$output, $depth = 0, $args = array() ) {
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent = str_repeat( $t, $depth );
		$output .= "$indent</ul>{$n}";
	}

	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent = ( $depth ) ? str_repeat( $t, $depth ) : '';

		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;
		global $Template_cssprefix;
		if ($item->menu_item_parent == 0)
		{
        	$classes[] = $Template_cssprefix.'menu_items_parent';
	   	}
	   	else
	   	{
			$classes[] = 'dropdown-submenu';
		}
		
		if(in_array('menu-item-has-children', $classes))
	   	{
		$classes[] = 'dropdown';
		}
		
	  	// Comment this code to solve menu case when change Permalink setting at backend. We compare this code with wordpress core Walker-nav-menu class code but this code is not there. This is the custom code which caused the issue.We tested all menu cases after removing this code.
	/*	$current_url = set_url_scheme( 'http://' . $_SERVER['HTTP_HOST'] . untrailingslashit( $_SERVER['REQUEST_URI'] ) . '/' );
		if ($item->url != $current_url && in_array('current-menu-item', $classes)) {
			unset($classes[array_search('current-menu-item', $classes)]);
		} */
		
   		if (in_array('current-menu-item', $classes) )
		{
			$classes[] = 'active';
		}
	
		$args = apply_filters( 'nav_menu_item_args', $args, $item, $depth );

		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args, $depth );
		$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

		$output .= $indent . '<li' . $id . $class_names .'>';

		$atts = array();
		$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target'] = ! empty( $item->target )     ? $item->target     : '';
		$atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';
		$atts['href']   = ! empty( $item->url )        ? $item->url        : '';

		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( ! empty( $value ) ) {
				$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}

		/** This filter is documented in wp-includes/post-template.php */
		$title = apply_filters( 'the_title', $item->title, $item->ID );

		$title = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );
		
		$item_output = $args->before;
		
		if ($item->menu_item_parent != 0)
		{
			if(in_array('menu-item-has-children', $classes))
	   		{
			$item_output .= '<a'. $attributes .' class="subchild dropdown toggle" data-toggle="dropdown">';
			}
			else
			{
				$item_output .= '<a'. $attributes .'>';	
			}
		}
		else
		{
		if ((in_array('menu-item-has-children', $classes)) && in_array('current-menu-item', $classes) )
		{
			$item_output .= '<a'. $attributes .' class="'.$Template_cssprefix.'menu_items_parent_link_active_arrow dropdown-toggle" data-toggle="dropdown">';
		}
		else if (in_array('menu-item-has-children', $classes) )
		{
			$item_output .= '<a'. $attributes .' class="'.$Template_cssprefix.'menu_items_parent_link_arrow dropdown-toggle" data-toggle="dropdown">';
		}
		else if (in_array('current-menu-item', $classes) )
		{
			$item_output .= '<a'. $attributes .' class="'.$Template_cssprefix.'menu_items_parent_link_active">';
		}
		else
		{
			$item_output .= '<a'. $attributes .' class="'.$Template_cssprefix.'menu_items_parent_link">';
		}
		}
		
		$item_output .= ('<span class="menuchildicon"></span>');
		$item_output .= $args->link_before . $title . $args->link_after;
		
		if (!in_array('last', $classes) )
		{
			if ($item->menu_item_parent != 0)
			{
				$item_output .= ('<span class="menuarrowicon"></span></a><hr class="separator" />');
	        }
	        else
	        {
				$item_output .= ('<span class="menuarrowicon"></span></a><hr class="horiz_separator" />');
			}
		}
		else
		{
			$item_output .= ('<span class="menuarrowicon"></span></a>');
		}
		
		//$item_output .= '</a>';
		$item_output .= $args->after;

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}

	public function end_el( &$output, $item, $depth = 0, $args = array() ) {
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$output .= "</li>{$n}";
	}

} // Walker_Nav_Menu

class Template_Walker_Mega_Nav_Menu extends Walker {
	
	public $tree_type = array( 'post_type', 'taxonomy', 'custom' );

	public $db_fields = array( 'parent' => 'menu_item_parent', 'id' => 'db_id' );

	public function start_lvl( &$output, $depth = 0, $args = array() ) {
		
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent = str_repeat( $t, $depth );
		
		if ($depth == 0) 
		{
		$output .= "{$n}{$indent}<ul class=\"child dropdown-menu\">{$n}";
		}
		else
		{
			$output .= "{$n}{$indent}<ul>{$n}";
		}
	}

	public function end_lvl( &$output, $depth = 0, $args = array() ) {
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent = str_repeat( $t, $depth );
		$output .= "$indent</ul>{$n}";
	}

	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent = ( $depth ) ? str_repeat( $t, $depth ) : '';

		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;
		global $Template_cssprefix;
		if ($item->menu_item_parent == 0)
		{
        	$classes[] = $Template_cssprefix.'menu_items_parent';
	   	}
	   	else
	   	{
	   		if(in_array('menu-item-has-children', $classes) || $depth == 1)
	   		{
				$classes[] = 'span1 unstyled dropdown dropdown-submenu';
			}
			else
			{
			$classes[] = '';
			}
		}
		
		$classes[] = 'dropdown';
	   
	   	// Comment this code to solve menu case when change Permalink setting at backend. We compare this code with wordpress core Walker-nav-menu class code but this code is not there. This is the custom code which caused the issue.We tested all menu cases after removing this code.
	/*	$current_url = set_url_scheme( 'http://' . $_SERVER['HTTP_HOST'] . untrailingslashit( $_SERVER['REQUEST_URI'] ) . '/' );
		if ($item->url != $current_url && in_array('current-menu-item', $classes)) {
			unset($classes[array_search('current-menu-item', $classes)]);
		}*/
		
   		if (in_array('current-menu-item', $classes) )
		{
			$classes[] = 'active';
		}
	
		$args = apply_filters( 'nav_menu_item_args', $args, $item, $depth );

		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args, $depth );
		$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

		$output .= $indent . '<li' . $id . $class_names .'>';

		$atts = array();
		$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target'] = ! empty( $item->target )     ? $item->target     : '';
		$atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';
		$atts['href']   = ! empty( $item->url )        ? $item->url        : '';

		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( ! empty( $value ) ) {
				$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}

		/** This filter is documented in wp-includes/post-template.php */
		$title = apply_filters( 'the_title', $item->title, $item->ID );

		$title = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );
		
		$item_output = $args->before;
		
		if ($item->menu_item_parent != 0)
		{
			if(in_array('menu-item-has-children', $classes))
	   		{
			$item_output .= '<a'. $attributes .' class="subchild dropdown toggle" data-toggle="dropdown">';
			}
			else
			{
				$item_output .= '<a'. $attributes .'>';	
			}
		}
		else
		{
		if ((in_array('menu-item-has-children', $classes)) && in_array('current-menu-item', $classes) )
		{
			$item_output .= '<a'. $attributes .' class="'.$Template_cssprefix.'menu_items_parent_link_active_arrow dropdown-toggle" data-toggle="dropdown">';
		}
		else if (in_array('menu-item-has-children', $classes) )
		{
			$item_output .= '<a'. $attributes .' class="'.$Template_cssprefix.'menu_items_parent_link_arrow dropdown-toggle" data-toggle="dropdown">';
		}
		else if (in_array('current-menu-item', $classes) )
		{
			$item_output .= '<a'. $attributes .' class="'.$Template_cssprefix.'menu_items_parent_link_active">';
		}
		else
		{
			$item_output .= '<a'. $attributes .' class="'.$Template_cssprefix.'menu_items_parent_link">';
		}
		}
		
		$item_output .= ('<span class="menuchildicon"></span>');
		$item_output .= $args->link_before . $title . $args->link_after;
		
		if (!in_array('last', $classes) )
		{
			if ($item->menu_item_parent != 0)
			{
				$item_output .= ('<span class="menuarrowicon"></span></a><hr class="separator" />');
	        }
	        else
	        {
				$item_output .= ('<span class="menuarrowicon"></span></a><hr class="horiz_separator" />');
			}
		}
		else
		{
			$item_output .= ('<span class="menuarrowicon"></span></a>');
		}
		
		//$item_output .= '</a>';
		$item_output .= $args->after;

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}

	public function end_el( &$output, $item, $depth = 0, $args = array() ) {
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$output .= "</li>{$n}";
	}

} // Walker_Nav_Menu

class Template_Walker_Sidebar_Vertical_Nav_Menu extends Walker {
	
	public $tree_type = array( 'post_type', 'taxonomy', 'custom' );

	public $db_fields = array( 'parent' => 'menu_item_parent', 'id' => 'db_id' );

	public function start_lvl( &$output, $depth = 0, $args = array() ) {
		
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent = str_repeat( $t, $depth );
		
		if ($depth == 0) 
		{
		$output .= "{$n}{$indent}<ul class=\"child dropdown-menu menu-dropdown-styles\">{$n}";
		}
		else
		{
			$output .= "{$n}{$indent}<ul class=\"sub-menu dropdown-menu menu-dropdown-styles\">{$n}";
		}
	}

	public function end_lvl( &$output, $depth = 0, $args = array() ) {
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent = str_repeat( $t, $depth );
		$output .= "$indent</ul>{$n}";
	}

	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent = ( $depth ) ? str_repeat( $t, $depth ) : '';

		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;
		global $Template_cssprefix;
		if ($item->menu_item_parent == 0)
		{
        	$classes[] = $Template_cssprefix.'menu_items_parent';
	   	}
	   	else
	   	{
			$classes[] = 'dropdown-submenu';
		}
		
		$classes[] = 'dropdown';
	   
	     // Comment this code to solve menu case when change Permalink setting at backend. We compare this code with wordpress core Walker-nav-menu class code but this code is not there. This is the custom code which caused the issue.We tested all menu cases after removing this code.
		/* $current_url = set_url_scheme( 'http://' . $_SERVER['HTTP_HOST'] . untrailingslashit( $_SERVER['REQUEST_URI'] ) . '/' );
		if ($item->url != $current_url && in_array('current-menu-item', $classes)) {
			unset($classes[array_search('current-menu-item', $classes)]);
		}*/
		
   		if (in_array('current-menu-item', $classes) )
		{
			$classes[] = 'active';
		}
		
		$args = apply_filters( 'nav_menu_item_args', $args, $item, $depth );

		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args, $depth );
		$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

		$output .= $indent . '<li' . $id . $class_names .'>';

		$atts = array();
		$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target'] = ! empty( $item->target )     ? $item->target     : '';
		$atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';
		$atts['href']   = ! empty( $item->url )        ? $item->url        : '';

		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( ! empty( $value ) ) {
				$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}

		/** This filter is documented in wp-includes/post-template.php */
		$title = apply_filters( 'the_title', $item->title, $item->ID );

		$title = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );
		
		$item_output = $args->before;
		
		if ($item->menu_item_parent != 0)
		{
			if(in_array('menu-item-has-children', $classes))
	   		{
			$item_output .= '<a'. $attributes .' class="subchild dropdown toggle" data-toggle="dropdown">';
			}
			else
			{
				$item_output .= '<a'. $attributes .'>';	
			}
		}
		else
		{
		if ((in_array('menu-item-has-children', $classes)) && in_array('current-menu-item', $classes) )
		{
			$item_output .= '<a'. $attributes .' class="'.$Template_cssprefix.'vmenu_items_parent_link_active_arrow dropdown-toggle" data-toggle="dropdown">';
		}
		else if (in_array('menu-item-has-children', $classes) )
		{
			$item_output .= '<a'. $attributes .' class="'.$Template_cssprefix.'vmenu_items_parent_link_arrow dropdown-toggle" data-toggle="dropdown">';
		}
		else if (in_array('current-menu-item', $classes) )
		{
			$item_output .= '<a'. $attributes .' class="'.$Template_cssprefix.'vmenu_items_parent_link_active">';
		}
		else
		{
			$item_output .= '<a'. $attributes .' class="'.$Template_cssprefix.'vmenu_items_parent_link">';
		}
		}
		
		$item_output .= ('<span class="menuchildicon"></span>');
		$item_output .= $args->link_before . $title . $args->link_after;
		
		if (!in_array('last', $classes) )
		{
			if ($item->menu_item_parent != 0)
			{
				$item_output .= ('<span class="menuarrowicon"></span></a><hr class="separator" />');
	        }
	        else
	        {
				$item_output .= ('<span class="menuarrowicon"></span></a><hr class="horiz_separator" />');
			}
		}
		else
		{
			$item_output .= ('<span class="menuarrowicon"></span></a>');
		}
		
		//$item_output .= '</a>';
		$item_output .= $args->after;

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}

	public function end_el( &$output, $item, $depth = 0, $args = array() ) {
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$output .= "</li>{$n}";
	}

} // Walker_Nav_Child_Ver_Menu

class Template_Walker_Sidebar_Horizontal_NoStyle_Nav_Menu extends Walker {

	public $tree_type = array( 'post_type', 'taxonomy', 'custom' );

	public $db_fields = array( 'parent' => 'menu_item_parent', 'id' => 'db_id' );

	public function start_lvl( &$output, $depth = 0, $args = array() ) {
		
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent = str_repeat( $t, $depth );
		
		if ($depth == 0) 
		{
		$output .= "{$n}{$indent}<ul class=\"child dropdown-menu\">{$n}";
		}
		else
		{
			$output .= "{$n}{$indent}<ul>{$n}";
		}
	}

	public function end_lvl( &$output, $depth = 0, $args = array() ) {
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent = str_repeat( $t, $depth );
		$output .= "$indent</ul>{$n}";
	}

	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent = ( $depth ) ? str_repeat( $t, $depth ) : '';

		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;
		global $Template_cssprefix;
		if ($item->menu_item_parent == 0)
		{
        	$classes[] = $Template_cssprefix.'menu_items_parent';
	   	}
	   	else
	   	{
			$classes[] = 'dropdown-submenu';
		}
		
		$classes[] = 'dropdown';
	    
	     // Comment this code to solve menu case when change Permalink setting at backend. We compare this code with wordpress core Walker-nav-menu class code but this code is not there. This is the custom code which caused the issue.We tested all menu cases after removing this code.
	/* 	$current_url = set_url_scheme( 'http://' . $_SERVER['HTTP_HOST'] . untrailingslashit( $_SERVER['REQUEST_URI'] ) . '/' );
		if ($item->url != $current_url && in_array('current-menu-item', $classes)) {
			unset($classes[array_search('current-menu-item', $classes)]);
		}*/
		
   		if (in_array('current-menu-item', $classes) )
		{
			$classes[] = 'active';
		}
		
		$args = apply_filters( 'nav_menu_item_args', $args, $item, $depth );

		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args, $depth );
		$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

		$output .= $indent . '<li' . $id . $class_names .'>';

		$atts = array();
		$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target'] = ! empty( $item->target )     ? $item->target     : '';
		$atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';
		$atts['href']   = ! empty( $item->url )        ? $item->url        : '';

		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( ! empty( $value ) ) {
				$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}

		/** This filter is documented in wp-includes/post-template.php */
		$title = apply_filters( 'the_title', $item->title, $item->ID );

		$title = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );
		
		$item_output = $args->before;
		
		if ($item->menu_item_parent != 0)
		{
			if(in_array('menu-item-has-children', $classes))
	   		{
			$item_output .= '<a'. $attributes .' class="subchild dropdown toggle" data-toggle="dropdown">';
			}
			else
			{
				$item_output .= '<a'. $attributes .'>';	
			}
		}
		else
		{
		if ((in_array('menu-item-has-children', $classes)) && in_array('current-menu-item', $classes) )
		{
			$item_output .= '<a'. $attributes .' class="'.$Template_cssprefix.'menu_items_parent_link_active_arrow dropdown-toggle" data-toggle="dropdown">';
		}
		else if (in_array('menu-item-has-children', $classes) )
		{
			$item_output .= '<a'. $attributes .' class="'.$Template_cssprefix.'menu_items_parent_link_arrow dropdown-toggle" data-toggle="dropdown">';
		}
		else if (in_array('current-menu-item', $classes) )
		{
			$item_output .= '<a'. $attributes .' class="'.$Template_cssprefix.'menu_items_parent_link_active">';
		}
		else
		{
			$item_output .= '<a'. $attributes .' class="'.$Template_cssprefix.'menu_items_parent_link">';
		}
		}
		
		$item_output .= $args->link_before . $title . $args->link_after;
		
		if ($item->menu_item_parent != 0)
		{
			$item_output .= ('<span class="menuarrowicon"></span></a>');
        }
        else
        {
			$item_output .= ('<span class="menuarrowicon"></span></a>');
		}
		
		//$item_output .= '</a>';
		$item_output .= $args->after;

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}

	public function end_el( &$output, $item, $depth = 0, $args = array() ) {
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$output .= "</li>{$n}";
	}

} // Walker_Nav_no_style_Hori_Menu

class Template_Walker_Sidebar_Vertical_NoStyle_Nav_Menu extends Walker {
	
	public $tree_type = array( 'post_type', 'taxonomy', 'custom' );

	public $db_fields = array( 'parent' => 'menu_item_parent', 'id' => 'db_id' );

	public function start_lvl( &$output, $depth = 0, $args = array() ) {
		
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent = str_repeat( $t, $depth );
		
		if ($depth == 0) 
		{
		$output .= "{$n}{$indent}<ul class=\"child dropdown-menu menu-dropdown-styles\">{$n}";
		}
		else
		{
			$output .= "{$n}{$indent}<ul class=\"sub-menu dropdown-menu\">{$n}";
		}
	}

	public function end_lvl( &$output, $depth = 0, $args = array() ) {
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent = str_repeat( $t, $depth );
		$output .= "$indent</ul>{$n}";
	}

	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent = ( $depth ) ? str_repeat( $t, $depth ) : '';

		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;
		global $Template_cssprefix;
		if ($item->menu_item_parent == 0)
		{
        	$classes[] = $Template_cssprefix.'menu_items_parent';
	   	}
	   	else
	   	{
			$classes[] = 'dropdown-submenu';
		}
		
		$classes[] = 'dropdown';
	    
	    // Comment this code to solve menu case when change Permalink setting at backend. We compare this code with wordpress core Walker-nav-menu class code but this code is not there. This is the custom code which caused the issue.We tested all menu cases after removing this code.
	/*	$current_url = set_url_scheme( 'http://' . $_SERVER['HTTP_HOST'] . untrailingslashit( $_SERVER['REQUEST_URI'] ) . '/' );
		if ($item->url != $current_url && in_array('current-menu-item', $classes)) {
			unset($classes[array_search('current-menu-item', $classes)]);
		}*/
		
   		if (in_array('current-menu-item', $classes) )
		{
			$classes[] = 'active';
		}
		
		$args = apply_filters( 'nav_menu_item_args', $args, $item, $depth );

		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args, $depth );
		$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

		$output .= $indent . '<li' . $id . $class_names .'>';

		$atts = array();
		$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target'] = ! empty( $item->target )     ? $item->target     : '';
		$atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';
		$atts['href']   = ! empty( $item->url )        ? $item->url        : '';

		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( ! empty( $value ) ) {
				$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}

		/** This filter is documented in wp-includes/post-template.php */
		$title = apply_filters( 'the_title', $item->title, $item->ID );

		$title = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );
		
		$item_output = $args->before;
		
		if ($item->menu_item_parent != 0)
		{
			if(in_array('menu-item-has-children', $classes))
	   		{
			$item_output .= '<a'. $attributes .' class="subchild dropdown toggle" data-toggle="dropdown">';
			}
			else
			{
				$item_output .= '<a'. $attributes .'>';	
			}
		}
		else
		{
		if ((in_array('menu-item-has-children', $classes)) && in_array('current-menu-item', $classes) )
		{
			$item_output .= '<a'. $attributes .' class="'.$Template_cssprefix.'vmenu_items_parent_link_active_arrow dropdown-toggle" data-toggle="dropdown">';
		}
		else if (in_array('menu-item-has-children', $classes) )
		{
			$item_output .= '<a'. $attributes .' class="'.$Template_cssprefix.'vmenu_items_parent_link_arrow dropdown-toggle" data-toggle="dropdown">';
		}
		else if (in_array('current-menu-item', $classes) )
		{
			$item_output .= '<a'. $attributes .' class="'.$Template_cssprefix.'vmenu_items_parent_link_active">';
		}
		else
		{
			$item_output .= '<a'. $attributes .' class="'.$Template_cssprefix.'vmenu_items_parent_link">';
		}
		}
		
		$item_output .= $args->link_before . $title . $args->link_after;
		
		if ($item->menu_item_parent != 0)
		{
			$item_output .= ('<span class="menuarrowicon"></span></a>');
        }
        else
        {
			$item_output .= ('<span class="menuarrowicon"></span></a>');
		}
		
		//$item_output .= '</a>';
		$item_output .= $args->after;

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}

	public function end_el( &$output, $item, $depth = 0, $args = array() ) {
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$output .= "</li>{$n}";
	}

} // Walker_Nav_no_style_Ver_Menu

class Template_Walker_Sidebar_Verticalh_Nav_Menu extends Walker {
	
	public $tree_type = array( 'post_type', 'taxonomy', 'custom' );

	public $db_fields = array( 'parent' => 'menu_item_parent', 'id' => 'db_id' );

	public function start_lvl( &$output, $depth = 0, $args = array() ) {
		
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent = str_repeat( $t, $depth );
		
		if ($depth == 0) 
		{
		$output .= "{$n}{$indent}<ul class=\"child dropdown-menu menu-dropdown-styles\">{$n}";
		}
		else
		{
			$output .= "{$n}{$indent}<ul class=\"sub-menu\">{$n}";
		}
	}

	public function end_lvl( &$output, $depth = 0, $args = array() ) {
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent = str_repeat( $t, $depth );
		$output .= "$indent</ul>{$n}";
	}

	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent = ( $depth ) ? str_repeat( $t, $depth ) : '';

		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;
		global $Template_cssprefix;
		if ($item->menu_item_parent == 0)
		{
        	$classes[] = $Template_cssprefix.'menu_items_parent';
	   	}
	   	else
	   	{
			$classes[] = 'dropdown-submenu';
		}
		
		$classes[] = 'dropdown';
	    
	     // Comment this code to solve menu case when change Permalink setting at backend. We compare this code with wordpress core Walker-nav-menu class code but this code is not there. This is the custom code which caused the issue.We tested all menu cases after removing this code.
		/* $current_url = set_url_scheme( 'http://' . $_SERVER['HTTP_HOST'] . untrailingslashit( $_SERVER['REQUEST_URI'] ) . '/' );
		if ($item->url != $current_url && in_array('current-menu-item', $classes)) {
			unset($classes[array_search('current-menu-item', $classes)]);
		}*/
		
   		if (in_array('current-menu-item', $classes) )
		{
			$classes[] = 'active';
		}
		
		$args = apply_filters( 'nav_menu_item_args', $args, $item, $depth );

		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args, $depth );
		$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

		$output .= $indent . '<li' . $id . $class_names .'>';

		$atts = array();
		$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target'] = ! empty( $item->target )     ? $item->target     : '';
		$atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';
		$atts['href']   = ! empty( $item->url )        ? $item->url        : '';

		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( ! empty( $value ) ) {
				$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}

		/** This filter is documented in wp-includes/post-template.php */
		$title = apply_filters( 'the_title', $item->title, $item->ID );

		$title = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );
		
		$item_output = $args->before;
		
		if ($item->menu_item_parent != 0)
		{
			if(in_array('menu-item-has-children', $classes))
	   		{
			$item_output .= '<a'. $attributes .' class="subchild dropdown toggle" data-toggle="dropdown">';
			}
			else
			{
				$item_output .= '<a'. $attributes .'>';	
			}
		}
		else
		{
		if ((in_array('menu-item-has-children', $classes)) && in_array('current-menu-item', $classes) )
		{
			$item_output .= '<a'. $attributes .' class="'.$Template_cssprefix.'vmenu_items_parent_link_active_arrow dropdown-toggle" data-toggle="dropdown">';
		}
		else if (in_array('menu-item-has-children', $classes) )
		{
			$item_output .= '<a'. $attributes .' class="'.$Template_cssprefix.'vmenu_items_parent_link_arrow dropdown-toggle" data-toggle="dropdown">';
		}
		else if (in_array('current-menu-item', $classes) )
		{
			$item_output .= '<a'. $attributes .' class="'.$Template_cssprefix.'vmenu_items_parent_link_active">';
		}
		else
		{
			$item_output .= '<a'. $attributes .' class="'.$Template_cssprefix.'vmenu_items_parent_link">';
		}
		}
		
		$item_output .= ('<span class="menuchildicon"></span>');
		$item_output .= $args->link_before . $title . $args->link_after;
		
		if (!in_array('last', $classes) )
		{
			if ($item->menu_item_parent != 0)
			{
				$item_output .= ('<span class="menuarrowicon"></span></a><hr class="separator" />');
	        }
	        else
	        {
				$item_output .= ('<span class="menuarrowicon"></span></a><hr class="horiz_separator" />');
			}
		}
        else
        {
			$item_output .= ('<span class="menuarrowicon"></span></a>');
		}
		
		//$item_output .= '</a>';
		$item_output .= $args->after;

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}

	public function end_el( &$output, $item, $depth = 0, $args = array() ) {
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$output .= "</li>{$n}";
	}

} // Walker_Nav_Child_Hor_Menu

class Template_Walker_Sidebar_Verticalm_Nav_Menu extends Walker {
	
	public $tree_type = array( 'post_type', 'taxonomy', 'custom' );

	public $db_fields = array( 'parent' => 'menu_item_parent', 'id' => 'db_id' );

	public function start_lvl( &$output, $depth = 0, $args = array() ) {
		
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent = str_repeat( $t, $depth );
		
		if ($depth == 0) 
		{
		$output .= "{$n}{$indent}<ul class=\"child dropdown-menu menu-dropdown-styles\">{$n}";
		}
		else
		{
			$output .= "{$n}{$indent}<ul>{$n}";
		}
	}

	public function end_lvl( &$output, $depth = 0, $args = array() ) {
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent = str_repeat( $t, $depth );
		$output .= "$indent</ul>{$n}";
	}

	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent = ( $depth ) ? str_repeat( $t, $depth ) : '';

		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;
		global $Template_cssprefix;
		if ($item->menu_item_parent == 0)
		{
        	$classes[] = $Template_cssprefix.'menu_items_parent';
	   	}
	   	else
	   	{
	   		if(in_array('menu-item-has-children', $classes) || $depth == 1)
	   		{
				$classes[] = 'span1 unstyled dropdown dropdown-submenu';
			}
			else
			{
			$classes[] = '';
			}
		}
		
		$classes[] = 'dropdown';
	    
	    // Comment this code to solve menu case when change Permalink setting at backend. We compare this code with wordpress core Walker-nav-menu class code but this code is not there. This is the custom code which caused the issue.We tested all menu cases after removing this code.
	/*	$current_url = set_url_scheme( 'http://' . $_SERVER['HTTP_HOST'] . untrailingslashit( $_SERVER['REQUEST_URI'] ) . '/' );
		if ($item->url != $current_url && in_array('current-menu-item', $classes)) {
			unset($classes[array_search('current-menu-item', $classes)]);
		}*/
		
   		if (in_array('current-menu-item', $classes) )
		{
			$classes[] = 'active';
		}
		
		$args = apply_filters( 'nav_menu_item_args', $args, $item, $depth );

		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args, $depth );
		$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

		$output .= $indent . '<li' . $id . $class_names .'>';

		$atts = array();
		$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target'] = ! empty( $item->target )     ? $item->target     : '';
		$atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';
		$atts['href']   = ! empty( $item->url )        ? $item->url        : '';

		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( ! empty( $value ) ) {
				$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}

		/** This filter is documented in wp-includes/post-template.php */
		$title = apply_filters( 'the_title', $item->title, $item->ID );

		$title = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );
		
		$item_output = $args->before;
		
		if ($item->menu_item_parent != 0)
		{
			if(in_array('menu-item-has-children', $classes))
	   		{
			$item_output .= '<a'. $attributes .' class="subchild dropdown toggle" data-toggle="dropdown">';
			}
			else
			{
				$item_output .= '<a'. $attributes .'>';	
			}
		}
		else
		{
		if ((in_array('menu-item-has-children', $classes)) && in_array('current-menu-item', $classes) )
		{
			$item_output .= '<a'. $attributes .' class="'.$Template_cssprefix.'vmenu_items_parent_link_active_arrow dropdown-toggle" data-toggle="dropdown">';
		}
		else if (in_array('menu-item-has-children', $classes) )
		{
			$item_output .= '<a'. $attributes .' class="'.$Template_cssprefix.'vmenu_items_parent_link_arrow dropdown-toggle" data-toggle="dropdown">';
		}
		else if (in_array('current-menu-item', $classes) )
		{
			$item_output .= '<a'. $attributes .' class="'.$Template_cssprefix.'vmenu_items_parent_link_active">';
		}
		else
		{
			$item_output .= '<a'. $attributes .' class="'.$Template_cssprefix.'vmenu_items_parent_link">';
		}
		}
		
		$item_output .= ('<span class="menuchildicon"></span>');
		$item_output .= $args->link_before . $title . $args->link_after;
		
		if (!in_array('last', $classes) )
		{
			if ($item->menu_item_parent != 0)
			{
				$item_output .= ('<span class="menuarrowicon"></span></a><hr class="separator" />');
	        }
	        else
	        {
				$item_output .= ('<span class="menuarrowicon"></span></a><hr class="horiz_separator" />');
			}
		}
        else
        {
			$item_output .= ('<span class="menuarrowicon"></span></a><hr class="horiz_separator" />');
		}
		
		//$item_output .= '</a>';
		$item_output .= $args->after;

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}

	public function end_el( &$output, $item, $depth = 0, $args = array() ) {
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$output .= "</li>{$n}";
	}

} // Walker_Nav_child_mega_Menu

class Template_Walker_Sidebar_EVertical_Nav_Menu extends Walker {
	
	public $tree_type = array( 'post_type', 'taxonomy', 'custom' );

	public $db_fields = array( 'parent' => 'menu_item_parent', 'id' => 'db_id' );

	public function start_lvl( &$output, $depth = 0, $args = array() ) {
		
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent = str_repeat( $t, $depth );
		
		if ($depth == 0) 
		{
		$output .= "{$n}{$indent}<ul class=\"child collapse menu-dropdown-styles dropdown-menu\">{$n}";
		}
		else
		{
			$output .= "{$n}{$indent}<ul class=\"sub-menu dropdown-menu\">{$n}";
		}
	}

	public function end_lvl( &$output, $depth = 0, $args = array() ) {
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent = str_repeat( $t, $depth );
		$output .= "$indent</ul>{$n}";
	}

	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent = ( $depth ) ? str_repeat( $t, $depth ) : '';

		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;
		global $Template_cssprefix;
		if ($item->menu_item_parent == 0)
		{
        	$classes[] = $Template_cssprefix.'menu_items_parent';
	   	}
	   	else
	   	{
			$classes[] = 'dropdown-submenu';
		}
		
		$classes[] = 'dropdown';
	    
	     // Comment this code to solve menu case when change Permalink setting at backend. We compare this code with wordpress core Walker-nav-menu class code but this code is not there. This is the custom code which caused the issue.We tested all menu cases after removing this code.
		/* $current_url = set_url_scheme( 'http://' . $_SERVER['HTTP_HOST'] . untrailingslashit( $_SERVER['REQUEST_URI'] ) . '/' );
		if ($item->url != $current_url && in_array('current-menu-item', $classes)) {
			unset($classes[array_search('current-menu-item', $classes)]);
		}*/
		
   		if (in_array('current-menu-item', $classes) )
		{
			$classes[] = 'active';
		}
		
		$args = apply_filters( 'nav_menu_item_args', $args, $item, $depth );

		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args, $depth );
		$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

		$output .= $indent . '<li' . $id . $class_names .'>';

		$atts = array();
		$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target'] = ! empty( $item->target )     ? $item->target     : '';
		$atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';
		$atts['href']   = ! empty( $item->url )        ? $item->url        : '';

		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( ! empty( $value ) ) {
				$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}

		/** This filter is documented in wp-includes/post-template.php */
		$title = apply_filters( 'the_title', $item->title, $item->ID );

		$title = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );
		
		$item_output = $args->before;
		
		if ($item->menu_item_parent != 0)
		{
			if(in_array('menu-item-has-children', $classes))
	   		{
			$item_output .= '<a'. $attributes .' class="subchild dropdown toggle" data-toggle="dropdown">';
			}
			else
			{
				$item_output .= '<a'. $attributes .'>';	
			}
		}
		else
		{
		if ((in_array('menu-item-has-children', $classes)) && in_array('current-menu-item', $classes) )
		{
			$item_output .= '<a'. $attributes .' class="'.$Template_cssprefix.'vmenu_items_parent_link_active_arrow dropdown-toggle" data-toggle="dropdown">';
		}
		else if (in_array('menu-item-has-children', $classes) )
		{
			$item_output .= '<a'. $attributes .' class="'.$Template_cssprefix.'vmenu_items_parent_link_arrow dropdown-toggle" data-toggle="dropdown">';
		}
		else if (in_array('current-menu-item', $classes) )
		{
			$item_output .= '<a'. $attributes .' class="'.$Template_cssprefix.'vmenu_items_parent_link_active">';
		}
		else
		{
			$item_output .= '<a'. $attributes .' class="'.$Template_cssprefix.'vmenu_items_parent_link">';
		}
		}
		
		$item_output .= ('<span class="menuchildicon"></span>');
		$item_output .= $args->link_before . $title . $args->link_after;
		
		if (!in_array('last', $classes) )
		{
			if ($item->menu_item_parent != 0)
			{
				$item_output .= ('<span class="menuarrowicon"></span></a><hr class="separator" />');
	        }
	        else
	        {
				$item_output .= ('<span class="menuarrowicon"></span></a><hr class="horiz_separator" />');
			}
		}
        else
        {
			$item_output .= ('<span class="menuarrowicon"></span></a>');
		}
		
		//$item_output .= '</a>';
		$item_output .= $args->after;

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}

	public function end_el( &$output, $item, $depth = 0, $args = array() ) {
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$output .= "</li>{$n}";
	}

} // Walker_Nav_Child_inner_Menu

class Template_Walker_Sidebar_Horizontal_Nav_Menu extends Walker {
	
	public $tree_type = array( 'post_type', 'taxonomy', 'custom' );

	public $db_fields = array( 'parent' => 'menu_item_parent', 'id' => 'db_id' );

	public function start_lvl( &$output, $depth = 0, $args = array() ) {
		
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent = str_repeat( $t, $depth );
		
		if ($depth == 0) 
		{
		$output .= "{$n}{$indent}<ul class=\"child dropdown-menu \">{$n}";
		}
		else
		{
			$output .= "{$n}{$indent}<ul class=\"sub-menu dropdown-menu menu-dropdown-styles \">{$n}";
		}
	}

	public function end_lvl( &$output, $depth = 0, $args = array() ) {
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent = str_repeat( $t, $depth );
		$output .= "$indent</ul>{$n}";
	}

	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent = ( $depth ) ? str_repeat( $t, $depth ) : '';

		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;
		global $Template_cssprefix;
		if ($item->menu_item_parent == 0)
		{
        	$classes[] = $Template_cssprefix.'menu_items_parent';
	   	}
	   	else
	   	{
			$classes[] = 'dropdown-submenu';
		}
		
		$classes[] = 'dropdown';
		
	    // Comment this code to solve menu case when change Permalink setting at backend. We compare this code with wordpress core Walker-nav-menu class code but this code is not there. This is the custom code which caused the issue.We tested all menu cases after removing this code.
	/*	$current_url = set_url_scheme( 'http://' . $_SERVER['HTTP_HOST'] . untrailingslashit( $_SERVER['REQUEST_URI'] ) . '/' );
		if ($item->url != $current_url && in_array('current-menu-item', $classes)) {
			unset($classes[array_search('current-menu-item', $classes)]);
		} */
		
   		if (in_array('current-menu-item', $classes) )
		{
			$classes[] = 'active';
		}
		
		$args = apply_filters( 'nav_menu_item_args', $args, $item, $depth );

		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args, $depth );
		$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

		$output .= $indent . '<li' . $id . $class_names .'>';

		$atts = array();
		$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target'] = ! empty( $item->target )     ? $item->target     : '';
		$atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';
		$atts['href']   = ! empty( $item->url )        ? $item->url        : '';

		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( ! empty( $value ) ) {
				$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}

		/** This filter is documented in wp-includes/post-template.php */
		$title = apply_filters( 'the_title', $item->title, $item->ID );

		$title = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );
		
		$item_output = $args->before;
		
		if ($item->menu_item_parent != 0)
		{
			if(in_array('menu-item-has-children', $classes))
	   		{
			$item_output .= '<a'. $attributes .' class="subchild dropdown toggle" data-toggle="dropdown">';
			}
			else
			{
				$item_output .= '<a'. $attributes .'>';	
			}
		}
		else
		{
		if ((in_array('menu-item-has-children', $classes)) && in_array('current-menu-item', $classes) )
		{
			$item_output .= '<a'. $attributes .' class="'.$Template_cssprefix.'menu_items_parent_link_active_arrow dropdown-toggle" data-toggle="dropdown">';
		}
		else if (in_array('menu-item-has-children', $classes) )
		{
			$item_output .= '<a'. $attributes .' class="'.$Template_cssprefix.'menu_items_parent_link_arrow dropdown-toggle" data-toggle="dropdown">';
		}
		else if (in_array('current-menu-item', $classes) )
		{
			$item_output .= '<a'. $attributes .' class="'.$Template_cssprefix.'menu_items_parent_link_active">';
		}
		else
		{
			$item_output .= '<a'. $attributes .' class="'.$Template_cssprefix.'menu_items_parent_link">';
		}
		}
		
		$item_output .= $args->link_before . $title . $args->link_after;
		
		if (!in_array('last', $classes) )
		{
			if ($item->menu_item_parent != 0)
			{
				$item_output .= ('<span class="menuarrowicon"></span></a><hr class="separator" />');
	        }
	        else
	        {
				$item_output .= ('<span class="menuarrowicon"></span></a><hr class="horiz_separator" />');
			}
		}
		else
		{
			$item_output .= '<span class="menuarrowicon"></span></a>';
		}
		
		$item_output .= $args->after;

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}

	public function end_el( &$output, $item, $depth = 0, $args = array() ) {
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$output .= "</li>{$n}";
	}

} // Walker_Horizontal_Nav_Child_Menu