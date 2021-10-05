<?php
/**
 * This file is used to add the static theme-options to the wordpress dashboard tab.
 * @package Template
 */
 
require_once(get_template_directory() . '/dynamic-options.php');
global $theme_options;
class Template_Theme_Options extends Template_Theme_Options_Dynamic
{

    public function __construct()
    {
        parent::__construct();
        $this->get_static_options();
        $this->sections['error'] = __('Error Page', "mytheme");
        $this->sections['maintenance'] = __('Maintenance', "mytheme");
        $this->sections['shortcodes'] = __('Shortcode', "mytheme");
        $this->sections['googlemap'] = __('GoogleMap', "mytheme");
        //$this->sections['contactus'] = __('Contact Us', "mytheme");
		// $this->sections['seo'] = __('SEO', "mytheme");
        $this->sections['backuppage'] = __('Backup/Recovery', "mytheme");
        $this->sections['export_import'] = __('Export/Import Theme Options', "mytheme");
        $fileName = get_template_directory() . '/content/imports.php';
		if ( file_exists( $fileName ) ) { 
        $this->sections['import_content'] = __('Import Content', "mytheme"); 
        }
        
        add_action('admin_menu', array(&$this, 'add_pages'));
        add_action('admin_init', array(&$this, 'register_settings'));

        if (!get_option('Template_theme_options')) {
            $this->initialize_settings();
        }

    }

    /**
     * Add options page
     *
     * @since 1.0
     */
    public function add_pages()
    {

        $admin_page = add_theme_page(__('Theme Options', "mytheme"), __('Theme Options', "mytheme"), 'manage_options', 'mytheme-options', array(&$this, 'display_page'));
        add_action('admin_print_scripts-' . $admin_page, array(&$this, 'scripts'));
        add_action('admin_print_styles-' . $admin_page, array(&$this, 'styles'));        
        /*$contactvar=get_option('contact_form',"ttr_test");  Remove contact form
    	  if( $contactvar == "ttr_test" ) {
        $adminmail=get_option('admin_email');
        $default=array(0=>array('ttr_email'=>$adminmail),1=>array('ttr_captcha_public_key'=>''),2=>array('ttr_captcha_private_key'=>''),3=>array('ttr_contact_us_error_message'=>'Message was not sent. Try Again.'),4=>array('ttr_contact_us_success_message'=>'Thanks! Your message has been sent.'),5=>array('ttr_name'=>__('Name',"mytheme") ,'ttr_namereq' => 'on' ), 6=>array('ttr_subject' => __('Subject',"mytheme") , 'ttr_subjectreq' => 'on'));
        update_option( 'contact_form', $default );
        }*/
    }

    /**
     * Create settings field
     *
     * @since 1.0
     */
    public function create_setting($args = array())
    {

        $defaults = array(
            'id' => 'default_field',
            'title' => __('Default Field', "mytheme"),
            'desc' => __('This is a default description.', "mytheme"),
            'std' => '',
            'type' => 'text',
            'section' => 'general',
            'choices' => array(),
            'pattern' => '',
            'class' => '',
            'onclick' => ''
        );

        extract(wp_parse_args($args, $defaults));

        $field_args = array(
            'type' => $type,
            'id' => $id,
            'desc' => $desc,
            'std' => $std,
            'choices' => $choices,
            'label_for' => $id,
            'pattern' => $pattern,
            'class' => $class,
            'onclick' => $onclick
        );

        if ($type == 'checkbox')
            $this->checkboxes[] = $id;
        if ($type == 'media')
            $this->media[] = $id;
        add_settings_field($id, $title, array($this, 'display_setting'), 'mytheme-options', $section, $field_args);
    }

    /**
     *
     * Display options page
     *
     * @since 1.0
     */
    public function display_page()
    {
        if (get_bloginfo('version') >= 3.4) {
            $themename = wp_get_theme();
        }
		 
       //display URl error message 
        $error = get_settings_errors('Template_theme_options');
        if(!empty($error))
        {
            echo'<span id="errorMsg">'.settings_errors('Template_theme_options').'</span>';
        }
        elseif (isset($_GET['settings-updated']) && $_GET['settings-updated'] == true)
        {
            echo '<div class="updated fade"><p>' . __('Theme options updated.', "mytheme") . '</p></div>';
		}
        echo '<div id="admin_container" class="wrap">
	            <div class="icon-option"> </div>
	            <h1>' . __('Theme Options For', "mytheme") . '&nbsp;' . $themename . '</h1><br/>';

        echo '<form action="options.php" method="post">';
        settings_fields('Template_theme_options');
        echo '<div class="ui-tabs">
			<ul class="ui-tabs-nav">';

        foreach ($this->sections as $section_slug => $section) {
            echo '<li><a href="#' . $section_slug . '">' . $section . '</a></li>';
        }

        echo '</ul>';
        do_settings_sections($_GET['page']);
		echo '</div>';
		echo '<p class="submit">' .
            get_submit_button(__('Save Options', "mytheme"), 'button-primary', 'ttr_submit', false). '</p>';			
		echo '</form>';
        echo '</div>';
    }

    /**
     * Description for section
     *
     * @since 1.0
     */
    public function display_menu_section()
    {

        // code
    }
	 public function display_colors_section()
    {
       
    }
	/* Remove Contact form
    public function display_contactus_section()
    {?>
		
       <div id="content">
                <?php
                $value_contact = get_option('contact_form');
                ?>
                    <table class="table">
                        <tbody>
                        <tr>
                            <td colspan="3"><?php echo(__("To use CONTACT FORM, apply shortcode [contact_us_form]","mytheme"));?></td>

                        </tr>
                        <tr>
                            <td><?php echo(__('Admin Email Address',"mytheme"));?></td>
                            <td colspan="2">
                                <input type="email" id="ttr_email" <?php if($value_contact[0]['ttr_email']){ ?> value="<?php print_r($value_contact[0]['ttr_email']); ?>" <?php } else {?> value="<?php print_r(get_option('admin_email')); ?>"<?php } ?> name="ttr_email" />

                            </td>

                        </tr>

                        <!-- Google Captcha -->
                        <tr>
                            <td><?php echo(__('Public Key For Google Captcha',"mytheme"));?></td>
                            <td colspan="2">
                                <input type="text" id="ttr_captcha_public_key" <?php if($value_contact[1]['ttr_captcha_public_key']){ ?> value="<?php print_r($value_contact[1]['ttr_captcha_public_key']); ?>" <?php }  ?>  name="ttr_captcha_public_key" />

                            </td>

                        </tr>

                        <tr>
                            <td><?php echo(__('Private Key For Google Captcha',"mytheme"));?></td>
                            <td colspan="2">
                                <input type="text" id="ttr_captcha_private_key" <?php if($value_contact[2]['ttr_captcha_private_key']){ ?> value="<?php print_r($value_contact[2]['ttr_captcha_private_key']); ?>" <?php }  ?>  name="ttr_captcha_private_key" />

                            </td>

                        </tr>

                        <!-- Contact Us Error Message -->
                        <tr>
                            <td><?php echo(__('Error Message',"mytheme"));?></td>
                            <td colspan="2">
                                <input type="textarea" id="ttr_contact_us_error_message" <?php if($value_contact[3]['ttr_contact_us_error_message']){ ?> value="<?php print_r($value_contact[3]['ttr_contact_us_error_message']); ?>" <?php } else {?> value="<?php echo(__("Message was not sent. Try Again.","mytheme"));?>"<?php } ?> name="ttr_contact_us_error_message" />

                            </td>

                        </tr>

                        <tr>
                            <td><?php echo(__('Success Message',"mytheme"));?></td>
                            <td colspan="2">
                                <input type="textarea" id="ttr_contact_us_success_message" <?php if($value_contact[4]['ttr_contact_us_success_message']){ ?> value="<?php print_r($value_contact[4]['ttr_contact_us_success_message']); ?>" <?php } else {?> value="<?php echo(__("Thanks! Your message has been sent.","mytheme")); ?>"<?php } ?>  name="ttr_contact_us_success_message" />

                            </td>

                        </tr>
                        <tr>
                            <td><?php echo(__('Field Name:',"mytheme"));?></td>
                           <!-- <td><?php echo(__('Field Type:',"mytheme"));?></td>-->
                            <td><?php echo(__('Required',"mytheme"));?></td>
                            <td><?php echo(__('Remove',"mytheme"));?></td>
                        </tr>

                        <?php if (is_array($value_contact)): ?>

                            <?php foreach($value_contact as $key=>$i)
                            {
                                foreach($value_contact[$key] as $newkey=>$j)
                                {
                                    if($newkey == 'ttr_email' || $newkey == 'ttr_emailreq' || $newkey == 'ttr_captcha_public_key' || $newkey == 'ttr_captcha_public_keyreq' || $newkey == 'ttr_captcha_private_key' || $newkey == 'ttr_captcha_private_keyreq' || $newkey == 'ttr_contact_us_error_message' || $newkey == 'ttr_contact_us_error_messagereq' || $newkey == 'ttr_contact_us_success_message' || $newkey == 'ttr_contact_us_success_messagereq')
                                        continue;
                                    ?>

                                    <?php 	if(strpos($newkey,'req')==false) { ?>

                                    <td><input name="<?php echo $newkey; ?>" id="<?php echo $newkey; ?>" type="<?php echo 'text'; ?>" value="<?php if ( $value_contact[$key][$newkey] != "") { print_r($value_contact[$key][$newkey]); } ?>" /></td>
                                <?php }?>

                                    <?php 	if(strpos($newkey,'req')!==false) { ?>
                                    <td>
                                        <?php if(isset($value_contact[$key][$newkey]) && $value_contact[$key][$newkey] == 'on') { $checked = "checked=\"checked\""; } else { $checked = ""; } ?>

                                        <div class="normal-toggle-button">
                                            <input type="checkbox" id="<?php echo $newkey; ?>"  name="<?php echo $newkey; ?>" <?php echo $checked; ?> />
                                        </div></td>
                                    <?php $url = get_template_directory_uri();?>
                                    <td><input type="image" src="<?php echo($url).'/images/cross.png'; ?>" class="removefield" /></td>
                                    </tr>
                                <?php } ?>

                                <?php }
                            }

                        endif;
                        ?>

                        <tr>
                            <td colspan="4"><input type="button" value="<?php echo(__('Add New Field',"mytheme"));?>" class="newfield button-secondary" /></td>
                        </tr>

                        </tbody>
                    </table>
				</div>
		
<?php
    }*/

  /*  public function display_seoenable_section()
    {

        $this->sections['seoenable'] = __('SEO Enable', "Template");
        $this->sections['seohome'] = __('Home', "Template");
        $this->sections['seogeneral'] = __('SEO General', "Template");
        $this->sections['seosocial'] = __('Web/Social', "Template");
        $this->sections['seositemap'] = __('Sitemap', "Template");
        $this->sections['seoadvanced'] = __('Advanced', "Template");

        $tabs = array('seoenable' => __('SEO Enable', 'Template'), 'seohome' => __('Home', 'Template'), 'seogeneral' => __('SEO General', 'Template'), 'seosocial' => __('Web/Social', 'Template'), 'seositemap' => __('Sitemap', 'Template'), 'seoadvanced' => __('Advanced', 'Template'));

        echo '<div id="tabseo" class="ui-tabs">';
        echo '<ul class="ui-tabs-nav">';
        foreach ($tabs as $section_slug => $section)
            echo '<li><a href="#' . $section_slug . '">' . $section . '</a></li>';
        echo '</ul>';
        foreach ($tabs as $section_slug => $section) {
            echo '<h4>' . $section . '</h4>';
            echo '<table class="form-table">';
            do_settings_fields($_GET['page'], $section_slug);
            echo '</table>';

        }
        echo '</div>';

    }*/

    public function display_backuppage_section()
    {

        $this->sections['backupsettings'] = __('Settings', "mytheme");
        $this->sections['backup'] = __('Backup', "mytheme");
        $this->sections['restore'] = __('Restore', "mytheme");
        $tabs = array('backupsettings' => 'Settings', 'backup' => 'Backup', 'restore' => 'Restore');

        echo '<div id="tabbackup" class="ui-tabs">';
        echo '<ul class="ui-tabs-nav">';
        foreach ($tabs as $section_slug => $section)
            echo '<li><a href="#' . $section_slug . '">' . $section . '</a></li>';
        echo '</ul>';
        foreach ($tabs as $section_slug => $section) {
            echo '<h4>' . $section . '</h4>';
            echo '<table class="form-table">';
            do_settings_fields($_GET['page'], $section_slug);
            echo '</table>';
        }
        echo '</div>';

    }
	public function display_export_import_section()
	{
	?>
		<table class="form-table">
	    	<tbody>
	    		<tr>
		            <th>
		            <label><?php echo(__('Export theme options','mytheme'));?></label>
					</th><td>	            
	            	<input id="export_yes" class="button button-primary" type="button" name="exportt" value="<?php echo(__('Export Theme Option','mytheme'));?>" />	            	
	                </td>
	            </tr>
	            <tr>
	               <th>	               
	                <label id="select_file"><?php echo(__('Import theme options? ','mytheme'));?></label>
	                </th><td>
			       	<input type="file" name="importfile" id="importfile" style="height:auto;">
			       	<input id="import_options_yes" class="button button-primary" type="button" value="<?php echo(__('Import Theme Options','mytheme'));?>" name="import_options">			       
	                </td>
	            </tr>
			</tbody>
	    </table>
	<?php
	}
	
	 public function display_import_content_section()
	{
        $fileName = get_template_directory() . '/content/imports.php';
		if ( file_exists( $fileName ) ) {	?> 
        <table class="form-table">
	    	<tbody>   	
            	<tr>
                    <td>
                        <!-- Trigger/Open The Modal  form can not be created inside theme options -->
                        <input class="button button-primary" type="button" id="importContentButton" value="Import Content">
                            <div id='importContentBox'>
                                <div class='importContentModal'>
                                    <div class="importContentHeader">
                                        <span id='importContentClose'>&times;</span>
                                        <h4 style="margin:0px;color:#fff;letter-spacing: 0.5px;">Import Content</h4>
                                    </div>
                                    <div class="importContentBody">
                                        <div class="row" id='importContent'></div>
                                    </div>
                                    <div class="importContentFooter">
                                        <input id="import_yes" class="button button-primary" type="submit" value="  Import  " name="importt" style="float:right">
                                        <div style="clear:both"></div>
                                    </div>
                                </div>
                            </div>
                    </td>
                </tr>
                </tbody>
            </table>
         <?php }
     }
            
    /**
     * HTML output for text field
     *
     * @since 1.0
     */
    public function display_setting($args = array())
    {

        extract($args);
        
         $options = array();
       if (!get_option('Template_theme_options'))
		{
			foreach ($this->settings as $j => $settings) {
				$options[] = $settings;
				}
				
			if (!isset($options[$id]) && $type != 'heading')
            $options[$id] = $std;
            elseif (!isset($options[$id]))
            $options[$id] = 0;
		} 
	  else
		{
			$options = get_option('Template_theme_options');
			
			if (!isset($options[$id]) && $type != 'checkbox')
            $options[$id] = $std;
            elseif (!isset($options[$id]))
            $options[$id] = 0;
		}
	
        $field_class = '';
        if ($class != '')
            $field_class = ' ' . $class;

        switch ($type) {

            case 'checkbox':
                echo '<div class="normal-toggle-button">';
                echo '<input class="checkbox' . $field_class . '"id="' . $id . '" name="Template_theme_options[' . $id . ']" value="0" type="hidden"/> ';
                echo '<input class="checkbox' . $field_class . '" type="checkbox" id="' . $id . '" name="Template_theme_options[' . $id . ']" value="1" ' . checked($options[$id], 1, false) . ' /> ';
                echo '</div>';
                break;

            case 'select':
                echo '<select class="select' . $field_class . '" id="' . $id . '"  name="Template_theme_options[' . $id . ']">';

                foreach ($choices as $value => $label)
                    echo '<option value="' . esc_attr($label) . '"' . selected($options[$id], $label, false) . '>' . $label . '</option>';

                echo '</select>';

                if ($desc != '')
                    echo '<br /><span class="description">' . $desc . '</span>';

                break;

            case 'radio':
                $i = 0;
                foreach ($choices as $value => $label) {
                    echo '<input class="radio' . $field_class . '" type="radio" name="Template_theme_options[' . $id . ']" id="' . $id . $i . '" value="' . esc_attr($value) . '" ' . checked($options[$id], $value, false) . '> <label for="' . $id . $i . '">' . $label . '</label>';
                    if ($i < count($options) - 1)
                        echo '<br />';
                    $i++;
                }

                if ($desc != '')
                    echo '<br /><span class="description">' . $desc . '</span>';

                break;

            case 'textarea':
                echo '<textarea class="' . $field_class . '" id="' . $id . '" name="Template_theme_options[' . $id . ']" placeholder="' . $std . '" rows="5" cols="30">';

				if(get_bloginfo('version') >= '4.3')
				{
					echo format_for_editor($options[$id]);
					
				}
				
				echo '</textarea>';
				
                if ($desc != '')
                    echo '<br /><span class="description">' . $desc . '</span>';

                break;

            case 'password':
                echo '<input class="regular-text' . $field_class . '" type="password" id="' . $id . '" name="Template_theme_options[' . $id . ']" value="' . esc_attr($options[$id]) . '" />';

                if ($desc != '')
                    echo '<br /><span class="description">' . $desc . '</span>';

                break;

            case 'shortcode':
                echo '<span id="' . $id . '" ><b>' . $std . '</b></span>';

                if ($desc != '')
                    // echo '<br /><br /><span class="description">' . $desc . '</span>';

                    break;

            case 'colorpicker':
                $value = esc_attr($options[$id]);
                
                echo '<input class="colorwell" type="text" id="' . $id . '"name="Template_theme_options[' . $id . ']" placeholder="' . $std . '" value="' . $value . '" />';
                if ($desc != '')
                    echo '<br /><span class="description">' . $desc . '</span>';

                break;

            case 'media':

                echo '<input class="upload' . $field_class . '" type="text" id="' . $id . '" name="Template_theme_options[' . $id . ']" placeholder="' . $std . '" value="' . esc_attr($options[$id]) . '" />';
                echo '&nbsp;<input type="button" class="ttrbutton btn" value="' . __('Upload', "mytheme") . '"/>';
                if ($desc != '')
                    echo '<br /><span class="description">' . $desc . '</span>';

                break;

            case 'button':

                echo '<input class="button-secondary' . $field_class . '" type="button" id="' . $id . '" name="Template_theme_options[' . $id . ']" value="' . $std . '" onclick="' . $onclick . '"/> ';

                if ($desc != '')
                    echo '<br /><span class="description">' . $desc . '</span>';

                break;

            case 'file':

                echo '<input name="Template_theme_options[' . $id . ']" id="' . $id . '" type="file" />';

                if ($desc != '')
                    echo '<br /><span class="description">' . $desc . '</span>';

                break;

            /*case 'sitemaplink':
                Template_create_sitemap();
                echo '<a class="' . $field_class . '" href="../sitemap.xml"> <input class="button-secondary' . $field_class . '" type="button" value="' . esc_attr($options[$id]) . '"/></a>';

                if ($desc != '')
                    echo '<br /><span class="description">' . $desc . '</span>';

                break; */

            case 'text':
            default:
                if ($pattern != '') {
                    echo '<input class="regular-text' . $field_class . '" type="text" id="' . $id . '" name="Template_theme_options[' . $id . ']" placeholder="' . $std . '" value="' . esc_attr($options[$id]) . '" pattern="' . $pattern . '"/>';
                } else {
                    echo '<input class="regular-text' . $field_class . '" type="text" id="' . $id . '" name="Template_theme_options[' . $id . ']" placeholder="' . $std . '" value="' . esc_attr($options[$id]) . '"/>';
                }

                if ($desc != '')
                    echo '<br /><span class="description">' . $desc . '</span>';

                break;
        }

    }

    /* Settings and defaults */
    public function get_static_options()
    {

       /* Error page Settings
         ===========================================*/

        $this->settings['ttr_error_message_heading'] = array(
            'title' => __('Heading For Error Message', "mytheme"),
            'desc' => __('Change Text For Error Message', "mytheme"),
            'std' => __('This is somewhat embarrassing, isn&rsquo;t it?', "mytheme"),
            'type' => 'textarea',
            'section' => 'error'
        );
        $this->settings['ttr_error_message_content'] = array(
            'title' => __('Content For Error Message', "mytheme"),
            'desc' => __('Change text For Error Message', "mytheme"),
            'std' => __('It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching, or one of the links below, can help.', "mytheme"),
            'type' => 'textarea',
            'section' => 'error'
        );
        $this->settings['ttr_error_message'] = array(
            'title' => __('Enable Content', "mytheme"),
            'desc' => __('Hide/Show Error Message Text', "mytheme"),
            'std' => 1,
            'type' => 'checkbox',
            'section' => 'error'
        );
        $this->settings['ttr_error_search_box'] = array(
            'title' => __('Enable Search', "mytheme"),
            'desc' => __('Hide/Show Search Box', "mytheme"),
            'std' => 1,
            'type' => 'checkbox',
            'section' => 'error'
        );
        $this->settings['ttr_error_image'] = array(
            'title' => __('Content Image', "mytheme"),
            'desc' => __('Choose Error Image', "mytheme"),
            'std' => '',
            'type' => 'media',
            'section' => 'error'
        );
        $this->settings['ttr_error_image_enable'] = array(
            'title' => __('Enable Image In Content', "mytheme"),
            'desc' => __('Hide/Show error image', "mytheme"),
            'std' => 0,
            'type' => 'checkbox',
            'section' => 'error'
        );
        $this->settings['ttr_error_image_height'] = array(
            'title' => __('Content Image Height', "mytheme"),
            'desc' => __('Height Of The Error Image', "mytheme"),
            'std' => '',
            'pattern' => '\d+',
            'type' => 'text',
            'section' => 'error'
        );
        $this->settings['ttr_error_image_width'] = array(
            'title' => __('Content Image Width', "mytheme"),
            'desc' => __('Width of The Error Image', "mytheme"),
            'std' => '',
            'pattern' => '\d+',
            'type' => 'text',
            'section' => 'error'
        );
        $this->settings['ttr_error_home_redirect'] = array(
            'title' => __('Redirect To Home Page(If Error Page Occurs)', "mytheme"),
            'desc' => __('Redirect to Home Page While Error Occur', "mytheme"),
            'std' => 0,
            'type' => 'checkbox',
            'section' => 'error'
        );

        /* Maintenance Settings
        ==========================================*/

        $this->settings['ttr_mm_enable'] = array(
            'title' => __('Enable Maintenance Mode', "mytheme"),
            'desc' => __('Enable/Disable Maintenance Mode', "mytheme"),
            'std' => 0,
            'type' => 'checkbox',
            'section' => 'maintenance'
        );
        $this->settings['ttr_mm_title'] = array(
            'title' => __('Title for Maintenance Mode Page', "mytheme"),
            'desc' => __('Set the Title', "mytheme"),
            'std' => '',
            'type' => 'text',
            'section' => 'maintenance'
        );
        $this->settings['ttr_mm_content'] = array(
            'title' => __('Content for Maintenance Mode Page', "mytheme"),
            'desc' => __('Content for Maintenance Mode page', "mytheme"),
            'std' => '',
            'type' => 'textarea',
            'section' => 'maintenance'
        );
        $this->settings['ttr_mm_image'] = array(
            'title' => __('Background Image for Maintenance Mode', "mytheme"),
            'desc' => __('Select Image for Maintenance Mode Page', "mytheme"),
            'std' => '',
            'type' => 'media',
            'section' => 'maintenance'
        );

        /* GoogleMap Settings
        ==========================================*/

        $this->settings['ttr_googlemap_type'] = array(
            'title' => __('Choose the type of Google Map', "mytheme"),
            'desc' => __('Choose the type of Google Map', "mytheme"),
            'type' => 'select',
            'std' => 'Road',
            'choices' => array(
                'choice1' => __('ROAD', "mytheme"),
                'choice2' => __('SATELLITE', "mytheme"),
                'choice3' => __('HYBRID', "mytheme"),
                'choice4' => __('TERRAIN', "mytheme")),
            'section' => 'googlemap'
        );
       $this->settings['ttr_map_latitude'] = array(
            'title' => __('Set Latitude', "mytheme"),
            'desc' => __('Set Latitude', "mytheme"),
            'std' => '25.306944',
            'pattern' => '',
            'type' => 'text',
            'section' => 'googlemap'
        );

        $this->settings['ttr_map_longitude'] = array(
            'title' => __('Set Longitude', "mytheme"),
            'desc' => __('Set Longitude', "mytheme"),
            'std' => '-257.858333',
            'pattern' => '',
            'type' => 'text',
            'section' => 'googlemap'
        );
        $this->settings['ttr_map_width'] = array(
            'title' => __('Set Width of Map', "mytheme"),
            'desc' => __('Set Width of Map', "mytheme"),
            'std' => '400',
            'pattern' => '\d+',
            'type' => 'text',
            'section' => 'googlemap'
        );
        $this->settings['ttr_map_height'] = array(
            'title' => __('Set Height of Map', "mytheme"),
            'desc' => __('Set Height of Map', "mytheme"),
            'std' => '400',
            'pattern' => '\d+',
            'type' => 'text',
            'section' => 'googlemap'
        );
        $this->settings['ttr_marker_enable'] = array(
            'title' => __('Display Marker', "mytheme"),
            'desc' => __('Enable/Disable the Position Marker', "mytheme"),
            'std' => 1,
            'type' => 'checkbox',
            'section' => 'googlemap'
        );
        $this->settings['ttr_marker_text'] = array(
            'title' => __('Set Marker Label', "mytheme"),
            'desc' => __('Set Marker Label', "mytheme"),
            'std' => 'This is my location',
            'type' => 'text',
            'patter' => '\d+',
            'section' => 'googlemap'
        );
        $this->settings['ttr_googlemap_api'] = array(
            'title' => __('Set Google API Key', "mytheme"),
            'desc' => __('Set Google API Key', "mytheme"),
            'std' => '',
            'type' => 'text',
            'patter' => '\d+',
            'section' => 'googlemap'
        );

        /* Shortcodes Settings
           ==========================================*/

        $this->settings['ttr_google_docs_viewer_shortcode'] = array(
            'title' => __('Google Docs Viewer', "mytheme"),
            'desc' => __('To use Copy/Paste the above shortcode', "mytheme"),
            'type' => 'shortcode',
            'std' => '[pdf href="http://manuals.info.apple.com/en_US/Enterprise_Deployment_Guide.pdf"]Link text.[/pdf]',
            'section' => 'shortcodes'
        );
        $this->settings['ttr_displaying_recent_posts_shortcode'] = array(
            'title' => __('Displaying Related Posts', "mytheme"),
            'desc' => __('To use Copy/Paste the above shortcode', "mytheme"),
            'type' => 'shortcode',
            'std' => '[related_posts]',
            'section' => 'shortcodes'
        );
        $this->settings['ttr_google_adsense_shortcode'] = array(
            'title' => __('Google AdSense', "mytheme"),
            'desc' => __('To use Copy/Paste the above shortcode', "mytheme"),
            'type' => 'shortcode',
            'std' => '[adsense client="ca-pub-1234567890" slot="1234567" width=728 height=90]',
            'section' => 'shortcodes'
        );
        $this->settings['ttr_google_chart_shortcode'] = array(
            'title' => __('Google Chart', "mytheme"),
            'desc' => __('To use Copy/Paste the above shortcode', "mytheme"),
            'type' => 'shortcode',
            'std' => '[chart data="41.52,37.79,20.67,0.03" bg="F7F9FA" labels="Reffering+sites|Search+Engines|Direct+traffic|Other" colors="058DC7,50B432,ED561B,EDEF00" size="488x200" title="Traffic Sources" type="pie"]',
            'section' => 'shortcodes'
        );
        $this->settings['ttr_tweet_me_button_shortcode'] = array(
            'title' => __('Tweet Me Button', "mytheme"),
            'desc' => __('To use Copy/Paste the above shortcode', "mytheme"),
            'type' => 'shortcode',
            'std' => '[tweet]',
            'section' => 'shortcodes'
        );
        $this->settings['ttr_google_map_shortcode'] = array(
            'title' => __('Google Map', "mytheme"),
            'desc' => __('To use Copy/Paste the above shortcode', "mytheme"),
            'type' => 'shortcode',
            'std' => '[googlemap]',
            'section' => 'shortcodes'
        );
        $this->settings['ttr_youtube_shortcode'] = array(
            'title' => __('YouTube', "mytheme"),
            'desc' => __('To use Copy/Paste the above shortcode', "mytheme"),
            'type' => 'shortcode',
            'std' => '[youtube value="http://www.youtube.com/watch?v=1aBSPn2P9bg"]',
            'section' => 'shortcodes'
        );
        $this->settings['ttr_private_content_shortcode'] = array(
            'title' => __('Private Content', "mytheme"),
            'desc' => __('To use Copy/Paste the above shortcode', "mytheme"),
            'type' => 'shortcode',
            'std' => '[member]This text will be only displayed to registered users.[/member]',
            'section' => 'shortcodes'
        );
        $this->settings['ttr_paypal_shortcode'] = array(
            'title' => __('PayPal', "mytheme"),
            'desc' => __('To use Copy/Paste the above shortcode', "mytheme"),
            'type' => 'shortcode',
            'std' => '[donate account="paypal account" type="text" text="Donation"]',
            'section' => 'shortcodes'
        );
        /*$this->settings['ttr_contact_us_form_shortcode'] = array(
            'title' => __('Contact Us Form', "mytheme"),
            'desc' => __('To use Copy/Paste the above shortcode', "mytheme"),
            'type' => 'shortcode',
            'std' => '[contact_us_form]',
            'section' => 'shortcodes'
        );*/
        $this->settings['ttr_login_form_shortcode'] = array(
            'title' => __('Login Form', "mytheme"),
            'desc' => __('To use Copy/Paste the above shortcode', "mytheme"),
            'type' => 'shortcode',
            'std' => '[widget type="login_form" style="block" loginbutton="Log In" logoutbutton="Log Out"]',
            'section' => 'shortcodes'
        );
        $this->settings['ttr_custommenu_shortcode'] = array(
            'title' => __('Custom Menu', "mytheme"),
            'desc' => __('To use Copy/Paste the above shortcode', "mytheme"),
            'type' => 'shortcode',
            'std' => '[widget type="Custom_Menu" title="Menu" style="block" menustyle="hmenu" nav_menu="All Pages" alignment="nostyle" color1="#d80e0e" color2="#120ed8" color="#ecdd74"]',
            'section' => 'shortcodes'
        );
        $this->settings['ttr_archives_shortcode'] = array(
            'title' => __('Archives', "mytheme"),
            'desc' => __('To use Copy/Paste the above shortcode', "mytheme"),
            'type' => 'shortcode',
            'std' => '[widget type="WP_Widget_Archives"]',
            'section' => 'shortcodes'
        );
        $this->settings['ttr_calendar_shortcode'] = array(
            'title' => __('Calendar', "mytheme"),
            'desc' => __('To use Copy/Paste the above shortcode', "mytheme"),
            'type' => 'shortcode',
            'std' => '[widget type="WP_Widget_Calendar"]',
            'section' => 'shortcodes'
        );
        $this->settings['ttr_categories_shortcode'] = array(
            'title' => __('Categories', "mytheme"),
            'desc' => __('To use Copy/Paste the above shortcode', "mytheme"),
            'type' => 'shortcode',
            'std' => '[widget type="WP_Widget_Categories"]',
            'section' => 'shortcodes'
        );
        $this->settings['ttr_links_shortcode'] = array(
            'title' => __('Links', "mytheme"),
            'desc' => __('To use Copy/Paste the above shortcode', "mytheme"),
            'type' => 'shortcode',
            'std' => '[widget type="WP_Widget_Links"]',
            'section' => 'shortcodes'
        );
        $this->settings['ttr_meta_shortcode'] = array(
            'title' => __('Meta', "mytheme"),
            'desc' => __('To use Copy/Paste the above shortcode', "mytheme"),
            'type' => 'shortcode',
            'std' => '[widget type="WP_Widget_Meta"]',
            'section' => 'shortcodes'
        );
        $this->settings['ttr_pages_shortcode'] = array(
            'title' => __('Pages', "mytheme"),
            'desc' => __('To use Copy/Paste the above shortcode', "mytheme"),
            'type' => 'shortcode',
            'std' => '[widget type="WP_Widget_Pages"]',
            'section' => 'shortcodes'
        );
        $this->settings['ttr_recent_comments_shortcode'] = array(
            'title' => __('Recent Comments', "mytheme"),
            'desc' => __('To use Copy/Paste the above shortcode', "mytheme"),
            'type' => 'shortcode',
            'std' => '[widget type="WP_Widget_Recent_Comments"]',
            'section' => 'shortcodes'
        );
        $this->settings['ttr_recent_posts_shortcode'] = array(
            'title' => __('Recent Posts', "mytheme"),
            'desc' => __('To use Copy/Paste the above shortcode', "mytheme"),
            'type' => 'shortcode',
            'std' => '[widget type="WP_Widget_Recent_Posts"]',
            'section' => 'shortcodes'
        );
        $this->settings['ttr_rss_shortcode'] = array(
            'title' => __('RSS', "mytheme"),
            'desc' => __('To use Copy/Paste the above shortcode', "mytheme"),
            'type' => 'shortcode',
            'std' => '[widget type="WP_Widget_RSS"]',
            'section' => 'shortcodes'
        );
        $this->settings['ttr_search_shortcode'] = array(
            'title' => __('Search', "mytheme"),
            'desc' => __('To use Copy/Paste the above shortcode', "mytheme"),
            'type' => 'shortcode',
            'std' => '[widget type="WP_Widget_Search"]',
            'section' => 'shortcodes'
        );
        $this->settings['ttr_tagcloud_shortcode'] = array(
            'title' => __('Tag Cloud', "mytheme"),
            'desc' => __('To use Copy/Paste the above shortcode', "mytheme"),
            'type' => 'shortcode',
            'std' => '[widget type="WP_Widget_Tag_Cloud"]',
            'section' => 'shortcodes'
        );
        $this->settings['ttr_text_shortcode'] = array(
            'title' => __('Text', "mytheme"),
            'desc' => __('To use Copy/Paste the above shortcode', "mytheme"),
            'type' => 'shortcode',
            'std' => '[widget type="WP_Widget_Text"]',
            'section' => 'shortcodes'
        );

        /* SEO Enable Settings
        ===========================================*/

        /*$this->settings['ttr_seo_enable'] = array(
            'title' => __('Enable SEO Mode', "Template"),
            'desc' => __('Enable/Disable SEO Mode', "Template"),
            'std' => '',
            'type' => 'checkbox',
            'section' => 'seoenable'
        );*/

        /* SEO Home Settings
       ===========================================*/

       /* $this->settings['ttr_seo_home_title'] = array(
            'title' => __('Home Title', "Template"),
            'desc' => __('Set the title of home page ', "Template"),
            'std' => '',
            'type' => 'text',
            'section' => 'seohome'
        );

        $this->settings['ttr_seo_home_desc'] = array(
            'title' => __('Home Description', "Template"),
            'desc' => __('Set the description of home page', "Template"),
            'std' => '',
            'type' => 'textarea',
            'section' => 'seohome'
        );

        $this->settings['ttr_seo_home_keywords'] = array(
            'title' => __('Home Keywords (Comma Separated)', "Template"),
            'desc' => __('Set the keywords of home page', "Template"),
            'std' => '',
            'type' => 'textarea',
            'section' => 'seohome'
        );

        $this->settings['ttr_seo_rewrite_titles'] = array(
            'title' => __('Rewrite Titles Format', "Template"),
            'desc' => __('Enable/Disable Rewrite Titles Format', "Template"),
            'std' => 1,
            'type' => 'checkbox',
            'section' => 'seohome'
        );

        $this->settings['ttr_seo_capitalize_titles'] = array(
            'title' => __('Capitalize Titles', "Template"),
            'desc' => __('Enable/Disable for Capitalize Page Titles', "Template"),
            'std' => 1,
            'type' => 'checkbox',
            'section' => 'seohome'
        );
        $this->settings['ttr_seo_capitalize_category'] = array(
            'title' => __('Capitalize Category Titles', "Template"),
            'desc' => __('Enable/Disable for Capitalize Category Titles', "Template"),
            'std' => 1,
            'type' => 'checkbox',
            'section' => 'seohome'
        );
        $this->settings['ttr_seo_page_title'] = array(
            'title' => __('Page Title Format', "Template"),
            'desc' => __('Enable/Disable the Page Title Format', "Template"),
            'type' => 'select',
            'std' => '%page_title% | %blog_title%',
            'class' => 'ttr_seo_rewrite_titles_select',
            'choices' => array(
                'choice1' => __('%page_title%', "Template"),
                'choice2' => __('%blog_title%', "Template"),
                'choice3' => __('%page_title% | %blog_title%', "Template"),
                'choice4' => __('%blog_title% | %page_title%', "Template")),
            'section' => 'seohome'
        );
        $this->settings['ttr_seo_post_title'] = array(
            'title' => __('Post Title Format', "Template"),
            'desc' => __('Enable/Disable the Post Title Format', "Template"),
            'type' => 'select',
            'std' => '%page_title% | %blog_title%',
            'class' => 'ttr_seo_rewrite_titles_select',
            'choices' => array(
                'choice1' => __('%page_title%', "Template"),
                'choice2' => __('%blog_title%', "Template"),
                'choice3' => __('%page_title% | %blog_title%', "Template"),
                'choice4' => __('%blog_title% | %page_title%', "Template")),
            'section' => 'seohome'
        );

        $this->settings['ttr_seo_category_title'] = array(
            'title' => __('Category Title Format', "Template"),
            'desc' => __('Enable/Disable the Category Title Format', "Template"),
            'type' => 'select',
            'std' => '%category_title% | %blog_title%',
            'class' => 'ttr_seo_rewrite_titles_select',
            'choices' => array(
                'choice1' => __('%category_title%', "Template"),
                'choice2' => __('%blog_title%', "Template"),
                'choice3' => __('%category_title% | %blog_title%', "Template"),
                'choice4' => __('%blog_title% | %category_title%', "Template")),
            'section' => 'seohome'
        );
        $this->settings['ttr_seo_date_archive_title'] = array(
            'title' => __('Date Archive Title Format', "Template"),
            'desc' => __('Enable/Disable the Date Archive Title Format', "Template"),
            'type' => 'select',
            'std' => '%date% | %blog_title%',
            'class' => 'ttr_seo_rewrite_titles_select',
            'choices' => array(
                'choice1' => __('%date%', "Template"),
                'choice2' => __('%blog_title%', "Template"),
                'choice3' => __('%date% | %blog_title%', "Template"),
                'choice4' => __('%blog_title% | %date%', "Template")),
            'section' => 'seohome'
        );
        $this->settings['ttr_seo_anchor_archive_title'] = array(
            'title' => __('Author Archive Title Format', "Template"),
            'desc' => __('Enable/Disable the Author Archive Title Format', "Template"),
            'type' => 'select',
            'std' => '%author% | %blog_title%',
            'class' => 'ttr_seo_rewrite_titles_select',
            'choices' => array(
                'choice1' => __('%author%', "Template"),
                'choice2' => __('%blog_title%', "Template"),
                'choice3' => __('%author% | %blog_title%', "Template"),
                'choice4' => __('%blog_title% | %author%', "Template")),
            'section' => 'seohome'
        );
        $this->settings['ttr_seo_tag_title'] = array(
            'title' => __('Tag Title Format', "Template"),
            'desc' => __('Enable/Disable the Tag Title Format', "Template"),
            'type' => 'select',
            'std' => '%tag% | %blog_title%',
            'class' => 'ttr_seo_rewrite_titles_select',
            'choices' => array(
                'choice1' => __('%tag%', "Template"),
                'choice2' => __('%blog_title%', "Template"),
                'choice3' => __('%tag% | %blog_title%', "Template"),
                'choice4' => __('%blog_title% | %tag%', "Template")),
            'section' => 'seohome'
        );
        $this->settings['ttr_seo_search_title'] = array(
            'title' => __('Search Title Format', "Template"),
            'desc' => __('Enable/Disable the Search Title Format', "Template"),
            'type' => 'select',
            'std' => '%search% | %blog_title%',
            'class' => 'ttr_seo_rewrite_titles_select',
            'choices' => array(
                'choice1' => __('%search%', "Template"),
                'choice2' => __('%blog_title%', "Template"),
                'choice3' => __('%search% | %blog_title%', "Template"),
                'choice4' => __('%blog_title% | %search%', "Template")),
            'section' => 'seohome'
        );
        $this->settings['ttr_seo_404_title'] = array(
            'title' => __('404 Title Format', "Template"),
            'desc' => __('Enable/Disable the 404 Title Format', "Template"),
            'type' => 'select',
            'std' => 'Nothing found for %request_words%',
            'class' => 'ttr_seo_rewrite_titles_select',
            'choices' => array(
                'choice1' => __('%request_words%', "Template"),
                'choice2' => __('%blog_title%', "Template"),
                'choice3' => __('%request_words% | %blog_title%', "Template"),
                'choice4' => __('%blog_title% | %request_words%', "Template")),
            'section' => 'seohome'
        );*/
        /* SEO General Settings
       ===========================================*/


       /* $this->settings['ttr_seo_use_keywords'] = array(
            'title' => __('Use Meta Keywords', "Template"),
            'desc' => __('Enable/Disable Meta Keywords', "Template"),
            'std' => 1,
            'type' => 'checkbox',
            'section' => 'seogeneral'
        );
        $this->settings['ttr_seo_default_keywords'] = array(
            'title' => __('Set Default Keywords (Comma Separated)', "Template"),
            'desc' => __('Set Default Keywords', "Template"),
            'std' => '',
            'type' => 'text',
            'section' => 'seogeneral'
        );
        $this->settings['ttr_seo_categories_meta_keywords'] = array(
            'title' => __('Use Categories as Keywords', "Template"),
            'desc' => __('Enable/Disable use Categories as Keywords', "Template"),
            'std' => 1,
            'type' => 'checkbox',
            'class' => 'ttr_seo_use_keywords_select',
            'section' => 'seogeneral'
        );
        $this->settings['ttr_seo_tags_meta_keywords'] = array(
            'title' => __('Use Tags as Keywords', "Template"),
            'desc' => __('Enable/Disable use Tags as Keywords', "Template"),
            'std' => 1,
            'type' => 'checkbox',
            'class' => 'ttr_seo_use_keywords_select',
            'section' => 'seogeneral'
        );
        $this->settings['ttr_seo_autogenerate_description'] = array(
            'title' => __('Autogenerate Descriptions', "Template"),
            'desc' => __('Enable/Disable autogenerate descriptions', "Template"),
            'std' => '',
            'type' => 'checkbox',
            'section' => 'seogeneral'
        );
        $this->settings['ttr_seo_nonindex_post'] = array(
            'title' => __('Set No-Index for all Posts', "Template"),
            'desc' => __('Enable/Disable indexing for all post', "Template"),
            'std' => 1,
            'type' => 'checkbox',
            'section' => 'seogeneral'
        );
        $this->settings['ttr_seo_nonindex_page'] = array(
            'title' => __('Set No-Index for all Page', "Template"),
            'desc' => __('Enable/Disable indexing for all pages', "Template"),
            'std' => 1,
            'type' => 'checkbox',
            'section' => 'seogeneral'
        );
        $this->settings['ttr_seo_nofollow_post'] = array(
            'title' => __('Set No-Follow for all Posts', "Template"),
            'desc' => __('Enable/Disable No-Follow for all post', "Template"),
            'std' => 1,
            'type' => 'checkbox',
            'section' => 'seogeneral'
        );
        $this->settings['ttr_seo_nofollow_page'] = array(
            'title' => __('Set No-Follow for all Page', "Template"),
            'desc' => __('Enable/Disable No-Follow for all pages', "Template"),
            'std' => 1,
            'type' => 'checkbox',
            'section' => 'seogeneral'
        );*/

        /* SEO Web Social Settings
        ===========================================*/

       /* $this->settings['ttr_seo_google_webmaster'] = array(
            'title' => __('Google Webmaster Tools', "Template"),
            'desc' => __('For Google Webmaster Verification', "Template"),
            'std' => '',
            'type' => 'text',
            'section' => 'seosocial'
        );
        $this->settings['ttr_seo_bing_webmaster'] = array(
            'title' => __('Bing Webmaster Tools', "Template"),
            'desc' => __('For Bing Webmaster Verification', "Template"),
            'std' => '',
            'type' => 'text',
            'section' => 'seosocial'
        );
        $this->settings['ttr_seo_pinterst_webmaster'] = array(
            'title' => __('Pinterst Webmaster Tools', "Template"),
            'desc' => __('For Pinterst Webmaster Verification', "Template"),
            'std' => '',
            'type' => 'text',
            'section' => 'seosocial'
        );
        $this->settings['ttr_seo_google_plus'] = array(
            'title' => __('Google Plus Default Profile', "Template"),
            'desc' => __('For Google Analytics', "Template"),
            'std' => '',
            'type' => 'text',
            'section' => 'seosocial'
        );*/

        /* SEO Sitemap Settings
       ===========================================*/

        /*$this->settings['ttr_seo_include_page'] = array(
            'title' => __('Include Page types to Sitemap', "Template"),
            'desc' => __('Add/Remove Page types to sitemap', "Template"),
            'std' => 1,
            'type' => 'checkbox',
            'section' => 'seositemap'
        );
        $this->settings['ttr_seo_include_post'] = array(
            'title' => __('Include Post types to Sitemap', "Template"),
            'desc' => __('Add/Remove Post types to sitemap', "Template"),
            'std' => 1,
            'type' => 'checkbox',
            'section' => 'seositemap'
        );

        $this->settings['ttr_seo_view_sitemap'] = array(
            'title' => __('View the Sitemap', "Template"),
            'desc' => __('Click this button to view sitemap', "Template"),
            'std' => 'View Sitemap',
            'type' => 'sitemaplink',
            'section' => 'seositemap'
        );*/


        /* SEO Advanced Settings
       ===========================================*/

       /* $this->settings['ttr_seo_noindex_date_archive'] = array(
            'title' => __('Use No-index for Date Archive', "Template"),
            'desc' => __('Enable/Disable index for Date Archive', "Template"),
            'std' => 1,
            'type' => 'checkbox',
            'section' => 'seoadvanced'
        );
        $this->settings['ttr_seo_noindex_author_archive'] = array(
            'title' => __('Use No-index for Author Archive', "Template"),
            'desc' => __('Enable/Disable index for Author Archive', "Template"),
            'std' => 1,
            'type' => 'checkbox',
            'section' => 'seoadvanced'
        );
        $this->settings['ttr_seo_noindex_tag_archive'] = array(
            'title' => __('Use No-index for Tag Archive', "Template"),
            'desc' => __('Enable/Disable index for Tag Archive', "Template"),
            'std' => 1,
            'type' => 'checkbox',
            'section' => 'seoadvanced'
        );
        $this->settings['ttr_seo_noindex_categories'] = array(
            'title' => __('Use No-index for Categories Archive', "Template"),
            'desc' => __('Enable/Disable index for Categories Archive', "Template"),
            'std' => 1,
            'type' => 'checkbox',
            'section' => 'seoadvanced'
        );
        $this->settings['ttr_seo_nofollow_categories'] = array(
            'title' => __('Use No-follow for Categories Archive', "Template"),
            'desc' => __('Enable/Disable follow for Categories Archive', "Template"),
            'std' => 1,
            'type' => 'checkbox',
            'section' => 'seoadvanced'
        );

        $this->settings['ttr_seo_noindex_search'] = array(
            'title' => __('Use No-index for Search Archive', "Template"),
            'desc' => __('Enable/Disable index for Search Archive', "Template"),
            'std' => 1,
            'type' => 'checkbox',
            'section' => 'seoadvanced'
        );
        $this->settings['ttr_seo_nofollow_search'] = array(
            'title' => __('Use No-follow for Search Archive', "Template"),
            'desc' => __('Enable/Disable follow for Search Archive', "Template"),
            'std' => 1,
            'type' => 'checkbox',
            'section' => 'seoadvanced'
        );
        $this->settings['ttr_seo_additional_post_header'] = array(
            'title' => __('Additional Post Headers', "Template"),
            'desc' => __('Give any Additional Post Headers', "Template"),
            'std' => '',
            'type' => 'textarea',
            'section' => 'seoadvanced'
        );
        $this->settings['ttr_seo_additional_page_header'] = array(
            'title' => __('Additional Page Headers', "Template"),
            'desc' => __('Give any Additional Page Headers', "Template"),
            'std' => '',
            'type' => 'textarea',
            'section' => 'seoadvanced'
        );
        $this->settings['ttr_seo_additional_fpage_header'] = array(
            'title' => __('Additional Post Headers', "Template"),
            'desc' => __('Give any Additional Post Headers', "Template"),
            'std' => '',
            'type' => 'textarea',
            'section' => 'seoadvanced'
        );*/

        /* Backup Dashboard
       ===========================================*/

        $this->settings['ttr_ftp_server_address'] = array(
            'title' => __('FTP Server Address', "mytheme"),
            'desc' => __('Enter the FTP Address ', "mytheme"),
            'std' => '',
            'type' => 'text',
            'section' => 'backupsettings'
        );
        $this->settings['ttr_ftp_user_name'] = array(
            'title' => __('FTP User name', "mytheme"),
            'desc' => __('Enter the FTP username ', "mytheme"),
            'std' => '',
            'type' => 'text',
            'section' => 'backupsettings'
        );
        $this->settings['ttr_ftp_user_password'] = array(
            'title' => __('FTP User password', "mytheme"),
            'desc' => __('Enter the FTP password ', "mytheme"),
            'std' => '',
            'type' => 'password',
            'section' => 'backupsettings'
        );
        $this->settings['ttr_ftp_recipient_email'] = array(
            'title' => __('Email of recipient', "mytheme"),
            'desc' => __('Enter the name of email recipient ', "mytheme"),
            'std' => '',
            'type' => 'text',
            'section' => 'backupsettings'
        );
        $this->settings['ttr_ftp_check_connection'] = array(
            'title' => __('Check FTP Connection', "mytheme"),
            'desc' => __('Click to check the FTP Connection Status', "mytheme"),
            'std' => 'Test FTP Connection',
            'type' => 'button',
            'section' => 'backupsettings'
        );
        
        /* Backup Settings
       ===========================================*/

        $this->settings['ttr_manual_database_backup'] = array(
            'title' => __('Database Backup (.sql file)', "mytheme"),
            'desc' => __('Add/Remove database (.sql) file to Backup zip', "mytheme"),
            'std' => 1,
            'type' => 'checkbox',
            'section' => 'backup'
        );
        $this->settings['ttr_automatic_backup_recovery_enable'] = array(
            'title' => __('Enable Automatic Backup/Recovery Mode)', "mytheme"),
            'desc' => __('Enable/Disable Automatic Backup/Recovery Mode', "mytheme"),
            'std' => 1,
            'type' => 'checkbox',
            'section' => 'backup'
        );
        $this->settings['ttr_include_plugin_backup'] = array(
            'title' => __('Include Plugins', "mytheme"),
            'desc' => __('Include/Exclude plugins to Backup Zip', "mytheme"),
            'std' => 1,
            'type' => 'checkbox',
            'section' => 'backup'
        );
        $this->settings['ttr_include_theme_backup'] = array(
            'title' => __('Include Themes', "mytheme"),
            'desc' => __('Include/Exclude themes to Backup Zip', "mytheme"),
            'std' => 1,
            'type' => 'checkbox',
            'section' => 'backup'
        );
        $this->settings['ttr_include_uploads_backup'] = array(
            'title' => __('Include Uploads', "mytheme"),
            'desc' => __('Include/Exclude uploads to Backup Zip', "mytheme"),
            'std' => 1,
            'type' => 'checkbox',
            'section' => 'backup'
        );
        $this->settings['ttr_backup_folder_name'] = array(
            'title' => __('Backup Folder Name', "mytheme"),
            'desc' => __('Set the folder name to take the backup', "mytheme"),
            'std' => 'Backup',
            'type' => 'text',
            'section' => 'backup'
        );
        $this->settings['ttr_automatic_backup_interval'] = array(
            'title' => __('Automatic Backup intervals', "mytheme"),
            'desc' => __('Select the database interval to take backup', "mytheme"),
            'type' => 'select',
            'std' => '-Select-',
            'choices' => array(
                'choice1' => __('Every 10 mins', "mytheme"),
                'choice2' => __('Every hour', "mytheme"),
                'choice3' => __('Every 4 hours', "mytheme"),
                'choice4' => __('Every 8 hours', "mytheme"),
                'choice2' => __('Every 12 hours', "mytheme"),
                'choice3' => __('Daily', "mytheme"),
                'choice4' => __('Weekly', "mytheme")),
            'section' => 'backup'
        );
        $this->settings['ttr_storage_backup'] = array(
            'title' => __('Choose your Remote Storage', "mytheme"),
            'desc' => __('Set time to the backup', "mytheme"),
            'type' => 'select',
            'std' => '-Select-',
            'choices' => array(
                'choice1' => __('FTP', "mytheme"),
                'choice2' => __('Email', "mytheme")),
            'section' => 'backup'
        );
        $this->settings['ttr_backup_folder_name'] = array(
            'title' => __('Backup Folder Name', "mytheme"),
            'desc' => __('Set the folder name to take the backup', "mytheme"),
            'std' => 'Backup',
            'type' => 'text',
            'section' => 'backup'
        );


        /* Restore Settings
       ===========================================*/

        $this->settings['ttr_browse'] = array(
            'title' => __('Select Backup Folder', "mytheme"),
            'desc' => __('Select the folder(.zip file only)', "mytheme"),
            'std' => '',
            'type' => 'file',
            'section' => 'restore'
        );
        $this->settings['ttr_include_database_restore'] = array(
            'title' => __('Restore Database(.sql)', "mytheme"),
            'desc' => __('Enable/Disable to restore databases for Site', "mytheme"),
            'std' => 0,
            'type' => 'checkbox',
            'section' => 'restore'
        );
        $this->settings['ttr_include_theme_restore'] = array(
            'title' => __('Restore Themes', "mytheme"),
            'desc' => __('Enable/Disable to restore theme folder for Site', "mytheme"),
            'std' => 0,
            'type' => 'checkbox',
            'section' => 'restore'
        );
        $this->settings['ttr_include_plugins_restore'] = array(
            'title' => __('Restore Plugins', "mytheme"),
            'desc' => __('Enable/Disable to restore plugins folder for Site', "mytheme"),
            'std' => 0,
            'type' => 'checkbox',
            'section' => 'restore'
        );
        $this->settings['ttr_include_uploads_restore'] = array(
            'title' => __('Restore Uploads', "mytheme"),
            'desc' => __('Enable/Disable to restore uploads folder for Site', "mytheme"),
            'std' => 0,
            'type' => 'checkbox',
            'section' => 'restore'
        );
        $this->settings['ttr_include_uploads_restore'] = array(
            'title' => __('Restore Uploads', "mytheme"),
            'desc' => __('Enable/Disable to restore uploads folder for Site', "mytheme"),
            'std' => 0,
            'type' => 'checkbox',
            'section' => 'restore'
        );

    }

    /**
     * Initialize settings to their default values
     *
     * @since 1.0
     */
    public function initialize_settings()
    {

        $default_settings = array();
        foreach ($this->settings as $id => $setting) {
            if ($setting['type'] != 'heading')
                $default_settings[$id] = $setting['std'];
        }

        // update_option('Template_theme_options', $default_settings);
	}

    /**
     * Register settings
     *
     * @since 1.0
     */
    public function register_settings()
    {
       // settings validation callback array
		$args = array(
            'type' => 'array', 
            'sanitize_callback' => array(&$this,'validate_settings')
           );
    register_setting('Template_theme_options', 'Template_theme_options', $args);
        foreach ($this->sections as $slug => $title) {
            if ($slug == 'colors')
                add_settings_section($slug, $title, array(&$this, 'display_colors_section'), 'mytheme-options');
            /*elseif ($slug == 'contactus')
                add_settings_section($slug, $title, array(&$this, 'display_contactus_section'), 'mytheme-options');*/
			elseif ($slug == 'seo')
                add_settings_section($slug, $title, array(&$this, 'display_seoenable_section'), 'mytheme-options');
            elseif ($slug == 'backuppage')
                add_settings_section($slug, $title, array(&$this, 'display_backuppage_section'), 'mytheme-options');
            elseif ($slug == 'export_import')
                add_settings_section($slug, $title, array(&$this, 'display_export_import_section'), 'mytheme-options'); 
  			elseif ($slug == 'import_content')
                add_settings_section($slug, $title, array(&$this, 'display_import_content_section'), 'mytheme-options');                
            else
                add_settings_section($slug, $title, array(&$this, 'display_menu_section'), 'mytheme-options');
        }
        $this->get_static_options();

        foreach ($this->settings as $id => $setting) {
            $setting['id'] = $id;
            $this->create_setting($setting);
        }
    }

    public function scripts()
    {
        wp_register_script('uitabs', get_template_directory_uri() . '/js/uitabs.js', array('jquery', 'wp-color-picker'), '1.0.0', false);
        wp_enqueue_script('uitabs');
        $sections = array_merge($this->sections, array('seoenable' => 'SEO Enable', 'seohome' => 'Home', 'seogeneral' => 'SEO General', 'seosocial' => 'Web/Social', 'seositemap' => 'Sitemap', 'seoadvanced' => 'Advanced'), array('backupsettings' => 'Settings', 'backup' => 'Backup', 'restore' => 'Restore'));
        wp_localize_script('uitabs', 'pass_data', $sections);
        
        $translations = array_merge(array('tt_xmlerror' => (__('Please, select an xml file','mytheme')), 'tt_savechanges' => (__('No changes to export in Theme Options. Please save your changes properly.','mytheme'))));
        
        wp_register_script('import_export_js', get_template_directory_uri() . '/js/import-export.js', array(), 1.0, false);
		wp_localize_script('import_export_js', 'tt_ajax' , $translations);
		wp_enqueue_script('import_export_js');
		
		$fileName = get_template_directory() . '/content/imports.php';
		if ( file_exists( $fileName ) ) {	
		wp_register_script('import_content_js', get_template_directory_uri() . '/js/import-content-script.js', array(), 1.0, false);
		wp_localize_script(
			'import_content_js',
			'MyAjax',
			array(
				// URL to wp-admin/admin-ajax.php to process the request
				'ajaxurl'          => admin_url( 'admin-ajax.php' ),
				'loading_src'      => admin_url() . '/images/loading.gif',
			)
		);
		wp_enqueue_script('import_content_js');
		}

    }

    public function styles()
    {

        wp_enqueue_style('wp-color-picker');
    }


    /**
     * Validate settings
     *
     * @since 1.0
     */
    public function validate_settings($input)
    {

        //if (!isset($input['reset_theme'])) {
        $options = get_option('Template_theme_options');

        foreach ($this->checkboxes as $id) {
            if (isset($options[$id]) && !isset($input[$id]))
                unset($options[$id]);
        }
        
        foreach ($this->media as $id) {
            if (isset($options[$id]) && !isset($input[$id]))
                unset($options[$id]);
        }
        // check url http:// validate
        foreach ($this->settings as  $key => $value)
        {
 			if($key == 'ttr_designedby_link_url')
            {
               if(!wp_http_validate_url($input['ttr_designedby_link_url']) )
               { 
                 add_settings_error('Template_theme_options','errorMsg',__("Please enter a valid URL", "mytheme"),'error') ;
			     return $options;
              }             
            }
            
            if($key == 'ttr_google_analytics')
            {// escaped html tags before saving script in database
                $input[$key] = esc_html($input[$key]);
            }
        } 
        return $input;
    }
}

$theme_options = new Template_Theme_Options();
/* Remove contact from 
if ( isset($_POST['ttr_submit'])) {
	$Template_contact_form_option_update = Template_contact_form_option_update();
    $Template_themeoption_to_customizersetting = Template_themeoption_to_customizersetting();
    }
    
function Template_contact_form_option_update()
{

    $post_val=array();
    foreach($_POST as $key=>$i)
    {

        if($key=='ttr_submit' || $key=='Template_theme_options' || $key=='_wp_http_referer' || $key=='_wpnonce' || $key=='action' || $key=='option_page' || $key=='importfile')
            continue;
        if(strpos($key,'req') == false)
        {

            $post_val_new=array();
            $post_val_new[$key] = $_POST[$key];

            if (isset($_POST[$key.'req']))
            {
                $post_val_new[$key.'req'] = $_POST[$key.'req'];

            }
            else
                $post_val_new[$key.'req']='off';

            array_push($post_val,$post_val_new);
        }
    }
    update_option('contact_form', $post_val);
     return;
}*/

function Template_themeoption_to_customizersetting()
{
if(!session_id()) {
session_start();
}
$themeoption_arr = $_POST["Template_theme_options"];

$themeoption_arr = $_POST["Template_theme_options"];

// Theme option logo to customizer logo sinkin.
if(isset($themeoption_arr['ttr_logo'])){
$tt_logo = $themeoption_arr['ttr_logo'];
$id = attachment_url_to_postid($tt_logo) ;
if($id != 0){
set_theme_mod( 'custom_logo', $id );
}
}

// Theme option favicon to customizer site icon sinking.
if(isset($themeoption_arr['ttr_favicon_image'])){
$tt_favicon = $themeoption_arr['ttr_favicon_image'];
$id = attachment_url_to_postid($tt_favicon);
if($id != 0){
update_option( 'site_icon', $id, 'yes');
}
}

// custom css to additional css customizer
if ( isset($themeoption_arr["ttr_custom_style"]) && function_exists( 'wp_update_custom_css_post' ) ) {
$custom_css = $themeoption_arr["ttr_custom_style"];
wp_update_custom_css_post( $custom_css );
}

// Site title and slogan option sinking
$header_text_color = get_header_textcolor();
if($header_text_color != 'blank') {
$_SESSION['header_textcolor'] = $header_text_color;
}

if(isset($themeoption_arr["ttr_site_title_slogan_enable"])){
$tt_title_slogan = $themeoption_arr['ttr_site_title_slogan_enable'];
    if(isset($_SESSION['header_textcolor'])) {
    set_theme_mod( 'header_textcolor', $_SESSION['header_textcolor']);
    }
    else{
    set_theme_mod( 'header_textcolor', Template_theme_option('ttr_title'));
    }
}
else{
    set_theme_mod( 'header_textcolor', 'blank' );
}
return;
}


function getIDfromGUID( $guid ){
global $wpdb;
return $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE guid=%s", $guid ) );
}

?>