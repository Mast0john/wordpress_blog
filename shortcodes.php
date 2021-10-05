<?php
/**
 * All the shordcode theme-options are using this file to execute the shortcode.
 * @package Template
 */
 
function Template_pdflink($attr, $content) {
    if ($attr['href']) {
        return '<a class="pdf" href="http://docs.google.com/viewer?url=' . $attr['href'] . '">'.$content.'</a>';
    } else {
        $src = str_replace("=", "", $attr[0]);
        return '<a class="pdf" href="http://docs.google.com/viewer?url=' . $src . '">'.$content.'</a>';
    }
}
add_shortcode('pdf', 'Template_pdflink');

function Template_related_posts_shortcode( $atts ) {
    extract(shortcode_atts(array(
        'limit' => '5',
    ), $atts));

    global $wpdb, $post, $table_prefix;

    if ($post->ID) {
        $retval = '<ul>';
        // Get tags
        $tags = wp_get_post_tags($post->ID);
        $tagsarray = array();
        foreach ($tags as $tag) {
            $tagsarray[] = $tag->term_id;
        }
        $tagslist = implode(',', $tagsarray);
        if ($tagslist != null)
        {
            // Do the query
            $q = "SELECT p.*, count(tr.object_id) as count
		FROM $wpdb->term_taxonomy AS tt, $wpdb->term_relationships AS tr, $wpdb->posts AS p WHERE
		tt.taxonomy ='post_tag' AND
		tt.term_taxonomy_id = tr.term_taxonomy_id AND
		tr.object_id  = p.ID AND
		tt.term_id IN ($tagslist) AND
		p.ID != $post->ID AND
		p.post_status = 'publish' AND
		p.post_date_gmt < NOW()
		GROUP BY tr.object_id
		ORDER BY count DESC, p.post_date_gmt DESC
		LIMIT $limit;";


            $related = $wpdb->get_results($q);
            if ( $related ) {
                foreach($related as $r) {
                    $retval .= '<li><a title="'.wptexturize($r->post_title).'" href="'.get_permalink($r->ID).'">'.wptexturize($r->post_title).'</a></li>';
                }
            }
        } else {
            $retval .= '
		<li>No related posts found</li>';
        }
        $retval .= '</ul>';
        return $retval;
    }
    return;
}
add_shortcode('related_posts', 'Template_related_posts_shortcode');

function Template_showads($atts) {
    extract(shortcode_atts(array(
        'client' => '',
        'slot' => '',
        'width' => '250',
        'height' => '250',
    ), $atts));
    return '<script type="text/javascript"><!--
	google_ad_client = "'.$client.'";
	google_ad_slot = "'.$slot.'";
	google_ad_width = '.$width.';
	google_ad_height = '.$height.';
	//-->
	</script>
	<script type="text/javascript"
	src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
	</script>
	';
}
add_shortcode('adsense', 'Template_showads');

function Template_chart_shortcode( $atts ) {
    extract(shortcode_atts(array(
        'data' => '',
        'colors' => '',
        'size' => '400x200',
        'bg' => 'ffffff',
        'title' => '',
        'labels' => '',
        'advanced' => '',
        'type' => 'pie'
    ), $atts));

    switch ($type) {
        case 'line' :
            $charttype = 'lc'; break;
        case 'xyline' :
            $charttype = 'lxy'; break;
        case 'sparkline' :
            $charttype = 'ls'; break;
        case 'meter' :
            $charttype = 'gom'; break;
        case 'scatter' :
            $charttype = 's'; break;
        case 'venn' :
            $charttype = 'v'; break;
        case 'pie' :
            $charttype = 'p3'; break;
        case 'pie2d' :
            $charttype = 'p'; break;
        default :
            $charttype = $type;
            break;
    }

    $string = '';
    if ($title) $string .= '&chtt='.$title.'';
    if ($labels) $string .= '&chl='.$labels.'';
    if ($colors) $string .= '&chco='.$colors.'';
    $string .= '&chs='.$size.'';
    $string .= '&chd=t:'.$data.'';
    $string .= '&chf='.$bg.'';

    return '<img title="'.$title.'" src="http://chart.apis.google.com/chart?cht='.$charttype.''.$string.$advanced.'" alt="'.$title.'" />';
}
add_shortcode('chart', 'Template_chart_shortcode');

function Template_tweetmeme(){
$twitter_url = "https://twitter.com/share"; 

    return '    <a href="' . $twitter_url . '" class="twitter-share-button" data-lang="en">Tweet</a>

    <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>';
}
add_shortcode('tweet', 'Template_tweetmeme');
/*map.js will enqueue here if the shortcode will be applied*/
function Template_googleMapShortcode($atts, $content = null)
{
    wp_enqueue_script('tt_googlemaps');
    $marker_text = Template_theme_option('ttr_marker_text');
    extract(shortcode_atts(array("id" => 'myMap', "type" => 'road', "latitude" => '36.394757', "longitude" => '-105.600586', "zoom" => '9', "message" => $marker_text, "width" => '300', "height" => '300'), $atts));
    $mapType = Template_theme_option('ttr_googlemap_type');
    $latitude =  Template_theme_option('ttr_map_latitude');
    $longitude = Template_theme_option('ttr_map_longitude');
    $width = Template_theme_option('ttr_map_width');
    $height = Template_theme_option('ttr_map_height');



    echo '<!-- Google Map -->
        <script type="text/javascript">
        jQuery(document).ready(function() {
          function initializeGoogleMap() {

              var myLatlng = new google.maps.LatLng('.$latitude.','.$longitude.');
              var myOptions = {
                center: myLatlng,
                zoom: '.$zoom.',
                mapTypeId: google.maps.MapTypeId.'.$mapType.'
              };
              var map = new google.maps.Map(document.getElementById("'.$id.'"), myOptions);

              var contentString = "'.$message.'";
              var infowindow = new google.maps.InfoWindow({
                  content: contentString
              });';
    if(Template_theme_option('ttr_marker_enable')):
        echo'
              var marker = new google.maps.Marker({
              position: myLatlng
              });

              google.maps.event.addListener(marker, "click", function() {
                  infowindow.open(map,marker);
              });

              marker.setMap(map);';
    endif;

    echo '}
          initializeGoogleMap();

        });
        </script>';


    return '<div id="'.$id.'" style="width:'.$width.'px; height:'.$height.'px;"  class="googleMap"></div>';
}


add_shortcode("googlemap", "Template_googleMapShortcode");


function Template_cwc_youtube($atts) {
    extract(shortcode_atts(array(
        "value" => 'http://',
        "width" => '475',
        "height" => '350',
        "name"=> 'movie',
        "allowFullScreen" => 'true',
        "allowScriptAccess"=>'always',
    ), $atts));
    $pos = strpos($value, "watch?v=");
    return '<object style="height: '.$height.'px; width: '.$width.'px"><param name="'.$name.'" value=http://youtube.com/v/'.substr($value, $pos+8).'"><param name="allowFullScreen" value="'.$allowFullScreen.'"></param><param name="allowScriptAccess" value="'.$allowScriptAccess.'"></param><embed src="http://youtube.com/v/'.substr($value, $pos+8).'" type="application/x-shockwave-flash" allowfullscreen="'.$allowFullScreen.'" allowScriptAccess="'.$allowScriptAccess.'" width="'.$width.'" height="'.$height.'"></embed></object>';
}
add_shortcode("youtube", "Template_cwc_youtube");

function Template_cwc_member_check_shortcode( $atts, $content = null ) {
    if ( is_user_logged_in() && !is_null( $content ) && !is_feed() )
        return $content;
    return '';
}

add_shortcode( 'member', 'Template_cwc_member_check_shortcode' );

function Template_donate_shortcode( $atts ) {
    extract(shortcode_atts(array(
        'text' => 'Make a donation',
        'account' => 'REPLACE ME',
        'for' => '',
    ), $atts));

    global $post;

    if (!$for) $for = str_replace(" ","+",$post->post_title);

    return '<a class="donateLink" href="https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&business='.$account.'&item_name=Donation+for+'.$for.'">'.$text.'</a>';
}
add_shortcode('donate', 'Template_donate_shortcode');
/* Remove contact form
function Template_contact_form($atts)
{
    ob_start();
	 extract( shortcode_atts( array(
        'styles' => 'padding:0px 0px 0px 0px;margin: 0 auto;',
		'btntitle' => 'Send Message',
		'btnclasses' => 'pull-left float-left btn btn-default' // Merged Bootstrap 3 & 4 classes.
    ), $atts ) );
    
    $value_contact=get_option('contact_form');
    ?>
<div class="contactformdiv">
    <form method="post" class="form-horizontal" id="ContactForm0" style="<?php echo $styles;?>">
      <?php  if (is_array($value_contact))
        { ?>
      <?php foreach($value_contact as $key=>$i)
        {
        foreach($value_contact[$key] as $newkey=>$j)
        {
        if($newkey == 'ttr_email' || $newkey == 'ttr_emailreq' || $newkey == 'ttr_captcha_public_key' || $newkey ==
        'ttr_captcha_public_keyreq' || $newkey == 'ttr_captcha_private_key' || $newkey == 'ttr_captcha_private_keyreq' ||
        $newkey == 'ttr_contact_us_error_message' || $newkey == 'ttr_contact_us_error_messagereq' || $newkey ==
        'ttr_contact_us_success_message' || $newkey == 'ttr_contact_us_success_messagereq')

        continue;
        if(strpos($newkey,'req')==false ) {
        if($value_contact[$key][$newkey]){ ?>


      <div class="form-group">
	  <label class="col-md-4 control-label <?php if(isset($value_contact[$key][$newkey.'req']) && $value_contact[$key][$newkey.'req'] == 'on'){ echo" required "; }  ?>">
	  <?php print_r($value_contact[$key][$newkey]); ?> :
        </label>

        <div class="col-md-8">
          <input type="text" class="form-control" name="<?php print_r($value_contact[$key][$newkey]); ?>"
          <?php if(isset($value_contact[$key][$newkey.'req']) && $value_contact[$key][$newkey.'req'] == 'on'){ ?>
          required<?php }  ?> />
        </div>
      </div>
      <?php
                            }
                        }
                    }
                }
           }
       ?>
      <div class="form-group">
        <label class="col-md-4 control-label required">
          <?php echo(__('Email:',"mytheme"));?>
        </label>

        <div class="col-md-8">
          <input type="text" name="message_email" class="form-control" />
        </div>
      </div>

      <div class="form-group">
        <label class="col-md-4 control-label">
          <?php echo(__('Message:',"mytheme"));?>
        </label>

        <div class="col-md-8">
          <textarea rows="4" name="message_text" class="form-control" ></textarea>
        </div>
      </div>

      
          <?php if(($value_contact[1]['ttr_captcha_public_key']) && ($value_contact[2]['ttr_captcha_private_key'])){?>
          	<div class="form-group">
       		 <div class="col-md-8">
       		  <div class="g-recaptcha" data-sitekey="<?php echo $value_contact[1]['ttr_captcha_public_key'];?>"></div>
          		<?php
		          		$captcha_enable = true;			// Google captcha enable if public and private keys are set
		          		$captcha_res = false;
		          		
		          		$recaptcha_secret = $value_contact[2]['ttr_captcha_private_key'];		// private key
		          		if (isset($_POST["g-recaptcha-response"])) {
		          		
		          		$check_version = get_bloginfo('version');
		          		if($check_version >= '4.3')
		          		{
		          		$response = $wp_filesystem->get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$recaptcha_secret."&response=".$_POST['g-recaptcha-response']);
		          		}
		          		
		          		$response = json_decode($response, true);
		          		if($response["success"] === true)
		          		{
		          		echo "Logged In Successfully";
		          		$captcha_res = true;
		          		}
		          		else
		          		{
		          		$captcha_res = false;
		          		}
		          		}
          		?>       
          	 	</div>
          	 	<script src='https://www.google.com/recaptcha/api.js'></script>
          	</div>
          <?php } else{
           $captcha_enable = false;
          } ?>
      
      <div class="form-group">
        <div class="col-md-8 col-md-offset-4 offset-md-4"> // Merged Bootstrap 3 & 4 classes.
          <input type="submit" value="<?php _e($btntitle , '','mytheme');?>" name="submit_values" class="<?php echo $btnclasses;?>">
        </div>
      </div>
      <div style="clear: both;"></div>
    </form>
</div>
	<div style="clear: both;"></div>

<?php

  if (!function_exists('Template_contact_form_generate_response'))
{
    function Template_contact_form_generate_response($type, $message){

        if($type == "success")
            echo "<div class='success'>{$message}</div>";
        else
            echo "<div class='error'>{$message}</div>";
    }
    //response messages
}
    $message_unsent  = $value_contact[3]['ttr_contact_us_error_message'];
    $message_sent    = $value_contact[4]['ttr_contact_us_success_message'];
	
	if (!function_exists('test_input'))
	{
	   	// escape and sanitize POST values
		function test_input($data) {
		  $data = trim($data);
		  $data = stripslashes($data);
		  $data = htmlspecialchars($data);
		  return $data;
		}
	}
    //user posted variables
    if(isset($_POST['submit_values']))
    {     	
      			
		$nameErr = $emailErr = "";
		$name = $email = $gender = $comment = $website = "";
		
		 if (isset($_POST["Name"])) {
		    $name = test_input($_POST["Name"]);
		    // check if name only contains letters and whitespace
		    if (!preg_match("/^[a-zA-Z ]*$/",$name)) {
		      $nameErr = "Only letters and white space allowed";
		    }
		  }
		  
		  if (empty($_POST["message_email"])) {
		    $emailErr = "Email is required";
		  } else {
		    $email = test_input($_POST["message_email"]);
		    // check if e-mail address is well-formed
		    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		      $emailErr = "Invalid email format";
		    }
		  }
		  
		  if (empty($_POST["message_text"])) {
		    $message = "";
		  } else {
		    $message = test_input($_POST["message_text"]);
		  }


        $check_mail=$value_contact[0]['ttr_email'];
        if($check_mail)
        {
            $to = $check_mail;
        }
        else
        {
            $to = get_option('admin_email');
        }
        if(isset($_POST['Subject']) && $_POST['Subject'])
        {
            $subject = $_POST['Subject'];
        }
        else
        {
            $subject = get_bloginfo().'-contact-form';
        }

		$sitename1 = strtolower( $_SERVER['SERVER_NAME'] );
		if ( substr( $sitename1, 0, 4 ) == 'www.' ) {
			$sitename1 = substr( $sitename1, 4 );
		}

        $headers = "From:".$name.' <wordpress@'.$sitename1.'>'."\n";
		$headers .= "Reply-to: $email\n";
		$sender = "From: $name\n";
		$sender .= "Reply-to: $email\n";


        foreach($value_contact as $key=>$i)
        {

            foreach($value_contact[$key] as $newkey=>$j)
            {
                if($newkey == 'ttr_email' || $newkey == 'ttr_emailreq' || $newkey == 'ttr_captcha_public_key' || $newkey == 'ttr_captcha_public_keyreq' || $newkey == 'ttr_captcha_private_key' || $newkey == 'ttr_captcha_private_keyreq' || $newkey == 'ttr_contact_us_error_message' || $newkey == 'ttr_contact_us_error_messagereq' || $newkey == 'ttr_contact_us_success_message' || $newkey == 'ttr_contact_us_success_messagereq')
                    continue;
                if(strpos($newkey,'req')==false) {

                    $first_var=$value_contact[$key][$newkey];
                    $replace_var=str_replace(' ','_',$first_var);

                    if(isset($_POST[$replace_var]) && !empty($_POST[$replace_var]))
                    {
                        $message .=$first_var.":".$_POST[$replace_var].' ';
                    }
                }
            }
        }
        $message .= __('Message:   ',"mytheme").$_POST['message_text'].' '.$sender;
        
        if(empty($nameErr) && empty($emailErr))
        {
	        if($captcha_enable){
		        if($captcha_res){
			        wp_mail($to, $subject, $message,$headers);
			        Template_contact_form_generate_response("success", $message_sent ); //message sent!
		        }
		        else{
		       		Template_contact_form_generate_response("error", "Robot verification failed, please try again.");
		        }
	        }
	        else{
		        wp_mail($to, $subject, $message,$headers);
		        Template_contact_form_generate_response("success", $message_sent ); //message sent!
	        }           
        }
        else
        {
        	if(!empty($nameErr)){
				 Template_contact_form_generate_response("error", $nameErr); //message wasn't sent
			}
        	elseif(!empty($emailErr)){
				Template_contact_form_generate_response("error", $emailErr); //message wasn't sent
			}			       
        }
    }
    return ob_get_clean();
}

add_shortcode('contact_us_form', 'Template_contact_form');
*/
?>