<?php

add_action('init', 'am_create_ads');
function am_create_ads() {
	$labels = array(
		'name' => _x('Ads', 'am'),
		'singular_name' => _x('Ad', 'am'),
		'add_new' => _x('Add Ad', 'am'),
		'add_new_item' => __('Add Ad', 'am'),
		'edit_item' => __('Edit Ads', 'am'),
		'new_item' => __('New Ad', 'am'),
		'view_item' => __('View Ad', 'am'),
		'search_items' => __('Search Ads', 'am'),
		'not_found' =>  __('No Ads found', 'am'),
		'not_found_in_trash' => __('No Ads found in Trash', 'am'), 
		'parent_item_colon' => ''
	);
	$args = array(
		'labels' => $labels,
		'public' => true,
		'publicly_queryable' => true,
		'exclude_from_search' => false,
		'show_ui' => true, 
		'query_var' => true,
		'capability_type' => 'post',
		'hierarchical' => false,
		'menu_position' => null,
		'supports' => array('title','thumbnail','editor','author'),
		'rewrite' => array(
			'slug' => 'ad'
		)
	);
	
	register_post_type('ad',$args);
}

function am_the_author_image($author_id=null, $instance=null, $is_url = false){
	if(class_exists('author_image')){
		if($is_url)
			return author_image::get_url($author_id,$instance);
		else
			return author_image::get($author_id,$instance);
	}
	else
		return '';
}

function am_create_hash_array($ar){
	$hash = array();
	
	if(count($ar)<=0)
		return $hash;
	
	foreach($ar as $item){
		$hash[sanitize_title($item)] = $item;
	}
	return $hash;
}

function am_get_number_ad_country($country){
	$number = 0;
	
	$args = array(
		'showposts' => -1,
		'post_type' => 'ad',
		'meta_query' => array(
			array(
				'key' => 'am_country',
				'value' => $country,
				'compare' => '='
			)
		)
	);
	$query = new WP_Query( $args );
	$number = $query->post_count;
	wp_reset_query();
	
	return $number;
}

function am_get_number_ad_state($country, $state){
	$number = 0;
	
	$args = array(
		'showposts' => -1,
		'post_type' => 'ad',
		'meta_query' => array(
			'relation' => 'AND',
			array(
				'key' => 'am_country',
				'value' => $country,
				'compare' => '='
			),
			array(
				'key' => 'am_state',
				'value' => $state,
				'compare' => '='
			)
		)
	);
	$query = new WP_Query( $args );
	$number = $query->post_count;
	wp_reset_query();
	
	return $number;
}

function am_get_countries_list($name,$class,$current,$show_added = false){
	global $am_option;
	
	$country = '<select class="'.$class.'" name="'.$name.'" id="'.$name.'">';
	$country .= '<option value="">'.am_lang('all_countries').'</option>';
	
	$array = am_get_countries_array($am_option['defaults']['countries']);
	
	foreach($array as $v){
		$current_opt = '';
		if($v==$current)
			$current_opt = ' selected="selected"';
		$number = am_get_number_ad_country($v);
		$html_option = '<option value="'.$v.'"'.$current_opt.'>'.$v.'</option>';
		if($show_added){
			if($number<=0)
				$html_option = '';
		}
		$country .= $html_option;
	}
	$country .= '</select>';
	
	return $country;
}

function am_get_states_list($name,$class,$country,$current,$show_added = false){
	global $am_option;
	
	$html = '<select class="'.$class.'" name="'.$name.'" id="'.$name.'">';
	$html .= '<option value="">'.am_lang('all_regions').'</option>';
	
	$array = am_get_state_array_template($am_option['defaults']['countries'], $country);
	
	foreach($array as $v){
		$current_opt = '';
		if($v==$current)
			$current_opt = ' selected="selected"';
			
		$number = am_get_number_ad_state($country, $v);
		$html_option = '<option value="'.$v.'"'.$current_opt.'>'.$v.'</option>';
		if($show_added){
			if($number<=0)
				$html_option = '';
		}
		$html .= $html_option;
	}
	$html .= '</select>';
	
	return $html;
}

function am_get_countries_array($array){
	
	$ar = array();
	for($i=0;$i<count($array);$i++){
		$ar[$array[$i]['name']] = $array[$i]['name'];
	}
	
	asort($ar);
	
	return $ar;
}

function am_get_state_array($array){
	global $_GET;
	$ar = array();
	
	if(isset($_GET['post']) && $_GET['post']>0){
		$country = am_get_custom_field('am_country', $_GET['post'], true);
		if(!empty($country)){
			for($i=0;$i<count($array);$i++){
				if(isset($array[$i]['states']) && count($array[$i]['states'])>0 && $array[$i]['name']==$country){
					foreach($array[$i]['states'] as $state)
						$ar[$state] = $state;
				}
			}
		}
	}
	
	return $ar;
}

function am_get_state_array_template($array,$country){
	$ar = array();
	
	if(!empty($country)){
		for($i=0;$i<count($array);$i++){
			if(isset($array[$i]['states']) && count($array[$i]['states'])>0 && $array[$i]['name']==$country){
				foreach($array[$i]['states'] as $state)
					$ar[$state] = $state;
			}
		}
	}
	
	return $ar;
}


define("prints_format", '/^[\x20-\x7E\x80-\xFF]+$/');

define("name_format", '/^[\w\s]+$/');

define("email_format", "/^([a-zA-Z0-9])+([\.a-zA-Z0-9_-])*@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-]+)+/");

define("digits_format", '/^[0-9]+$/');

define("chars_format", '/^[0-9A-Za-z_]+$/');

define("symbols_format", '/^[\x21-\x7E]+$/');

define("texts_format", '/^[\x09\x0A\x0D\x20-\x7E\x80-\xFF]+$/');

define("zip_format", '/^[0-9][0-9][0-9][0-9][0-9](-[0-9][0-9][0-9][0-9])?$/');

define("phone_format", '/^(\(( )?\d{2,6}( )?\))?( )?(\d{2,18}(( |\-|( \- )))?){0,8}(\d{1,18}){1,18}$/');

define("float_format", '/^[0-9]+(\\.[0-9]+)?$/');

define("url_format", "~^(?:(?:https?|ftp|telnet)://(?:[a-z0-9_-]{1,32}".

   "(?::[a-z0-9_-]{1,32})?@)?)?(?:(?:[a-z0-9-]{1,128}\.)+(?:com|net|".

   "org|mil|edu|arpa|gov|biz|info|aero|inc|name|[a-z]{2})|(?!0)(?:(?".

   "!0[^.]|255)[0-9]{1,3}\.){3}(?!0|255)[0-9]{1,3})(?:/[a-z0-9.,_@%&".

   "?+=\~/-]*)?(?:#[^ '\"&<>]*)?$~i");



$formats = array(
	'*' => prints_format, 
	"password" => symbols_format,
	"identifier" => chars_format,
	"number" => digits_format, 
	"title" => prints_format,
	"name" => name_format,
	"description" => prints_format,
	"text" => texts_format,
	"email" => email_format,
	"checkbox" => chars_format,
	"url" => url_format, 
	"path" => symbols_format,
	"phone" => phone_format,
	"zip" => zip_format,
	"float" => float_format
);

function checkdata(&$form, $items,$type=1){
	global $formats;
	$errorList = array();

		function setError(&$arr,$msg,$key=""){

			if ($key!=""){

				$arr[$key] = $msg;

			}else{

				$arr[] = $msg;

			}

		}

		foreach($items as $key => $def) {

		//echo $key."<br>";
		if ($type==2) $std = ""; else $std = $key;

			$value = $form[$key];

				$def = $items["$key"];

				if(isset($formats[$def["type"]])){

					

					$curformat = $formats[$def["type"]];

					if ($value=="" && $def['min']>0){

						 setError($errorList,$def['name']." is empty",$std);

					}elseif (strlen($value) < $def['min']) {

            			 setError($errorList,$def['name']."is too short (min ".$def['min']." number of characters)",$std);

        			} elseif (strlen($value) > $def['max']) {

			             setError($errorList,$def['name']." is too long (max ".$def['max']." number of characters)",$std);

			        }

			        elseif ($value != "" && !preg_match($curformat,$value)) {

			             setError($errorList,"Inadmissible ".$def['name'],$std);

			        }

					elseif (($def["type"]=="number"||$def["type"]=="float")&&(isset($def['minv'])&& $value<$def['minv'])) {

			             setError($errorList,$def['name']." must be >=".$def['minv'],$std);

			        }

					elseif (($def["type"]=="number"||$def["type"]=="float")&&(isset($def['maxv'])&& $value>$def['maxv'])) {

			             setError($errorList,$def['name']." must be <=".$def['maxv'],$std);

			        }

			        else {

			        	$value = trim($value," ");

						$form[$key] = $value;

			        }
			}	
		}
		return $errorList;
}

function am_possibly_redirect(){
  global $pagenow;
  if( 'wp-login.php' == $pagenow ) {
    if ( isset( $_POST['wp-submit'] ) ||   // in case of LOGIN
      ( isset($_GET['action']) && $_GET['action']=='logout') ||   // in case of LOGOUT
      ( isset($_GET['checkemail']) && $_GET['checkemail']=='confirm') ||   // in case of LOST PASSWORD
      ( isset($_GET['checkemail']) && $_GET['checkemail']=='registered') ) return;    // in case of REGISTER
    else wp_redirect(home_url('/login'));
    exit();
  }
}

add_action( 'show_user_profile', 'my_show_extra_profile_fields' );
add_action( 'edit_user_profile', 'my_show_extra_profile_fields' );

function my_show_extra_profile_fields( $user ) { ?>

	<h3>Extra profile information</h3>

	<table class="form-table">

		<tr>
			<th><label for="user_phone">Phone</label></th>

			<td>
				<input type="text" name="user_phone" id="user_phone" value="<?php echo esc_attr( get_the_author_meta( 'user_phone', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description">Please enter your phone number.</span>
			</td>
		</tr>

		<tr>
			<th><label for="user_country">Country</label></th>

			<td>
				<input type="text" name="user_country" id="user_country" value="<?php echo esc_attr( get_the_author_meta( 'user_country', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description">Please enter your country.</span>
			</td>
		</tr>

		<tr>
			<th><label for="user_city">City</label></th>

			<td>
				<input type="text" name="user_city" id="user_city" value="<?php echo esc_attr( get_the_author_meta( 'user_city', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description">Please enter your city.</span>
			</td>
		</tr>

		<tr>
			<th><label for="user_languages">Languages</label></th>

			<td>
				<input type="text" name="user_languages" id="user_languages" value="<?php echo esc_attr( get_the_author_meta( 'user_languages', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description">Please enter your user_languages.</span>
			</td>
		</tr>

	</table>
<?php }
add_action( 'personal_options_update', 'my_save_extra_profile_fields' );
add_action( 'edit_user_profile_update', 'my_save_extra_profile_fields' );

function my_save_extra_profile_fields( $user_id ) {

	if ( !current_user_can( 'edit_user', $user_id ) )
		return false;

	/* Copy and paste this line for additional fields. Make sure to change 'twitter' to the field ID. */
    update_user_meta( $user_id, 'user_phone', $_POST['user_phone'] );
    update_user_meta( $user_id, 'user_languages', $_POST['user_languages'] );
    update_user_meta( $user_id, 'user_country', $_POST['user_country'] );
    update_user_meta( $user_id, 'user_city', $_POST['user_city'] );
}

function am_get_user_ad($user_id){
    if($user_id){
        $args = array(
            'author' => $user_id, // Set this value!
            'showposts' => 1,
            'post_type'=>'ad',
            'post_status'=>array('publish','pending','draft','future')
        );
        $post_item = null;
        $query = new WP_Query($args);
        if( $query->have_posts() ) {
            while ($query->have_posts()) : $query->the_post();
                $post_item = get_the_ID();
                break;
            endwhile;
        }
        wp_reset_query();
        if(!$post_item) $post_item = am_addNewAd($user_id);
    } else {
        $post_item = am_addNewAd();
    }



	return $post_item;
}


function am_addNewAd($user_id=null){
    global $wpdb;

    $anon = false;
    $new_post = array();
    if($user_id){
        $title = am_get_short_author_name(get_userdata($user_id));
    } else {
        if(isset( $_SESSION['anon-ad'])) {
            $ad_post = get_post($_SESSION['anon-ad']);
            if($ad_post) {
                return (int)$_SESSION['anon-ad'];
            }
        }

        $title='Unregistered';
//        $user_id = ot_get_option('general_system_user');
        $anon = true;
    }

//    if(!$user_id) return false;

    $new_post['post_author'] = $user_id;
    $new_post['post_title'] = 'Ad: '.$title;
    $new_post['post_content'] = '';
    $new_post['comment_status'] = 'closed';
    $new_post['ping_status'] = 'closed';
    $new_post['post_type'] = 'ad';
    $new_post['post_name'] = sanitize_title($new_post['post_title']);
    $new_post['post_status'] = 'draft';


    $post_id = wp_insert_post( $new_post);
    if($post_id > 0){
        $updated_post = array();
        $updated_post['ID'] = $post_id;
        $updated_post['post_name'] = 'ad-'.$post_id;

        wp_update_post( $updated_post );
        if($anon){
            $_SESSION['anon-ad'] = $post_id;
        }

        return $post_id;
    }
    return false;
}

function am_get_capacity_list($name,$class,$current){
	global $am_option;
	
	$html = '<select class="'.$class.'" name="'.$name.'" id="'.$name.'">';
	
	$array = range(1, $am_option['defaults']['capacity'], 1);
	
	foreach($array as $v){
		$current_opt = '';
		if(($v-1)==$current)
			$current_opt = ' selected="selected"';
		if($v==1)
			$title = am_lang('guest');
		else
			$title = am_lang('guests');
		$html .= '<option value="'.($v-1).'"'.$current_opt.'>'.$v.' '.$title.'</option>';
	}
	$html .= '</select>';
	
	return $html;
}

function am_get_checkboxes_list($name,$class,$current,$ar){
	
	$html = '<ul class="'.$class.'">';
	
	$array = am_create_hash_array($ar);
	
	foreach($array as $k=>$v){
		$current_opt = '';
		if(in_array($k, $current))
			$current_opt = ' checked="checked"';
		$html .= '<li><input'.$current_opt.' type="checkbox" id="'.$name.'-'.$k.'" name="'.$name.'[]" value="'.$k.'"><label for="'.$name.'-'.$k.'">'.am_lang($v).'</label></li>';
	}
	$html .= '</ul>';
	
	return $html;
}

function am_get_select_list($name,$class,$current,$ar){
	global $am_option;
	
	$country = '<select class="'.$class.'" name="'.$name.'" id="'.$name.'">';
	//$country .= '<option value="">'.am_lang('select').'</option>';
	
	$array = $ar;
	
	foreach($array as $v){
		$current_opt = '';
		if($v==$current)
			$current_opt = ' selected="selected"';
		$country .= '<option value="'.$v.'"'.$current_opt.'>'.$v.'</option>';
	}
	$country .= '</select>';
	
	return $country;
}

function am_generate_password ($length = 8)
{
    $password = "";

    // define possible characters - any character in this string can be
    // picked for use in the password, so if you want to put vowels back in
    // or add special characters such as exclamation marks, this is where
    // you should do it
    $possible = "2346789bcdfghjkmnpqrtvwxyzBCDFGHJKLMNPQRTVWXYZ";

    // we refer to the length of $possible a few times, so let's grab it now
    $maxlength = strlen($possible);
  
    // check for length overflow and truncate if necessary
    if ($length > $maxlength) {
      $length = $maxlength;
    }
	
    // set up a counter for how many characters are in the password so far
    $i = 0; 
    
    // add random characters to $password until $length is reached
    while ($i < $length) { 

      // pick a random character from the possible ones
      $char = substr($possible, mt_rand(0, $maxlength-1), 1);
        
      // have we already used this character in $password?
      if (!strstr($password, $char)) { 
        // no, so it's OK to add it onto the end of whatever we've already got...
        $password .= $char;
        // ... and increase the counter by one
        $i++;
      }

    }

    // done!
    return $password;

}

function am_lang($slug){
	global $_COOKIE, $am_option;
	
	if(isset($am_option['lang'][$_COOKIE['lang']][$slug]))
		return $am_option['lang'][$_COOKIE['lang']][$slug];
	else
		return '';
}

function get_ad_images($postId){
    global $wpdb;
    if(!$postId) return array();

    $meta = get_post_meta( $postId, "am_images", false);
    if ( empty( $meta ) ) return array();

    $meta = implode( ',' , $meta );

    $meta = $wpdb->get_results( "
				SELECT ID, menu_order FROM {$wpdb->posts}
				WHERE post_type = 'attachment'
				AND ID in ({$meta})
				ORDER BY menu_order ASC
			" );

    $srcs = array();
    foreach($meta as $image){

        $order = $image->menu_order;
        if(!$order) $order++;
        while(isset($srcs[$order])) $order++;

        $srcs[$order] = array_merge(array('id'=>$image->ID), wp_get_attachment_image_src( $image->ID, 'size-full' ));
    }

    return $srcs;
}

function am_get_short_author_name($author){
	return ucwords($author->first_name.' '.$author->last_name[0].'.');
}

function isValidEmail($email){
	return eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email);
}

function am_redirect_dashboard(){
	if ( is_admin() && !current_user_can( 'update_core' ) && !current_user_can( 'list_users' ) ){
		wp_redirect(home_url());
		exit;
	} 
}
function am_wp_title( $title, $sep ) {
	global $paged, $page;

	if ( is_feed() )
		return $title;

	// Add the site name.
	$title_new = am_lang(trim($title));
	if(!empty($title_new))
		$title = $title_new;

	return $title;
}

function am_deleteOldAD(){
    $args = array(
        'suppress_filters' => false,
        'posts_per_page' => -1,
        'post_type'=>'ad',
        'post_status'=>array('draft')
    );
    add_filter( 'posts_where', 'am_filter_where_date' );
    $posts = get_posts($args);
    remove_filter( 'posts_where', 'am_filter_where_date' );
    foreach($posts as $post) {
        wp_delete_post($post->ID, true);
    }
    wp_reset_query();
}
function am_filter_where_date( $where = '' ) {
    global $wpdb;
    $where .= $wpdb->prepare( " AND post_date < %s", date( 'Y-m-d', strtotime('-2 days') ) );
    return $where;
}

?>