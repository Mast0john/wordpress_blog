<?php
class login_form extends WP_Widget{
	
	function __construct() {//settings for widget
		$widget_ops = array( 'description' => __('Use this widget to add login form',"mytheme") );
		parent::__construct( 'login_form', __('Log in Form',"mytheme"), $widget_ops );
	}
	
	function widget($args, $instance)
	{ 
	  global $Template_cssprefix;
        extract($args);
        $title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
        echo $before_widget;
        if ( $title )
            echo $before_title . $title . $after_title;
        echo '<div id="%1$s" class="'.$Template_cssprefix.'block_content">';
        if(!is_user_logged_in()){
            $args = array(
                'echo' => true,
                'redirect' => site_url(),
                'form_id' => 'loginform',
                'label_username' => __( 'Username',"mytheme" ),
                'label_password' => __( 'Password',"mytheme" ),
                'label_remember' => __( 'Remember Me!',"mytheme" ),
                'label_log_in' => $instance['loginbutton'] ,
                'id_username' => 'user_login',
                'id_password' => 'user_pass',
                'id_remember' => 'rememberme',
                'id_submit' => 'wp-submit',
                'remember' => true,
                'value_username' => NULL,
                'value_remember' => false );
            wp_login_form($args);
            ?>
            <a href="<?php echo wp_lostpassword_url();?>" title="Lost Password"><?php echo __('Forgot Your Password?',"mytheme"); ?></a>
        <?php } else { ?>
            <p>
                <a href="<?php echo wp_logout_url( home_url() ); ?>"><input class="btn btn-default" type="button" name="login_button" value="<?php echo $instance['logoutbutton']; ?>" /></a>
            </p>
        <?php }
        echo '</div>';
        echo $after_widget;

	}
	
	function update($new_instance, $old_instance )
	{
		$instance = $old_instance;
        $instance['title'] = $new_instance['title'];
		$instance['loginbutton'] = $new_instance['loginbutton'];
		$instance['logoutbutton'] = $new_instance['logoutbutton'];
		return $instance;
	}
	
	function form( $instance)
	{
		$instance = wp_parse_args( (array) $instance, array('login_button_title'=>__('Log In',"mytheme"), 'logout_button_title'=>__('Log Out',"mytheme"),'title'=>__('',"mytheme")));
		//print_r($instance);
		if ( !isset($instance['login_button_title']) )
			$instance['login_button_title'] = null;
		
		if ( !isset($instance['logout_button_title']) )
			$instance['logout_button_title'] = null;

        if ( !isset($instance['title']) )
            $instance['title'] = null;
		
		?>

        <?php echo __('Title:',"mytheme");?>
        <input style="width:100%;" class="upload" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php if(isset($instance['title'])) { echo $instance['title']; } else { echo __('','mytheme'); } ?>" />
        <?php echo __('Log in Button Text:',"mytheme");?>
		<input style="width:100%;" class="upload" id="<?php echo $this->get_field_id('loginbutton'); ?>" name="<?php echo $this->get_field_name('loginbutton'); ?>" type="text" value="<?php if(isset($instance['loginbutton'])) { echo $instance['loginbutton']; } else { echo __('Log In','mytheme'); } ?>" />
		<?php echo __('Log out Button Text:',"mytheme");?>
		<input style="width:100%;" class="upload" id="<?php echo $this->get_field_id('logoutbutton'); ?>" name="<?php echo $this->get_field_name('logoutbutton'); ?>" type="text" value="<?php if(isset($instance['logoutbutton'])) { echo $instance['logoutbutton']; } else { echo __('Log Out','mytheme'); } ?>" />
		<?php

	//	return $instance;
	}
}
function login_form_widgets() {//register my widget
	register_widget( 'login_form' );
}

add_action( 'widgets_init', 'login_form_widgets' );//function to load my widget

?>