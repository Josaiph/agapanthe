<?php
session_start();
global $am_option;

$am_option['shortname'] = "am";
$am_option['textdomain'] = "am";

$am_option['url']['includes_path'] = 'includes';
$am_option['url']['includes_url'] = get_template_directory_uri().'/'.$am_option['url']['includes_path'];
$am_option['url']['extensions_path'] = $am_option['url']['includes_path'].'/extensions';
$am_option['url']['extensions_url'] = get_template_directory_uri().'/'.$am_option['url']['extensions_path'];

require_once($am_option['url']['includes_path'].'/fn-data.php');

// Options
require_once($am_option['url']['includes_path'].'/theme-options.php');

// Functions
require_once($am_option['url']['includes_path'].'/fn-core.php');
require_once($am_option['url']['includes_path'].'/fn-custom.php');

// Extensions
require_once($am_option['url']['extensions_path'].'/breadcrumb-trail.php');
require_once($am_option['url']['extensions_path'].'/pagenavi.php');

/* Theme Init */
require_once ($am_option['url']['includes_path'].'/theme-langs.php');
require_once($am_option['url']['includes_path'].'/theme-init.php');
require_once($am_option['url']['includes_path'].'/theme-metaboxes.php');

$langs = getenv("HTTP_ACCEPT_LANGUAGE");
$set_langs = explode(',', $langs);

if(!isset($_COOKIE['lang'])){
	
	if(isset($set_langs[0]) && in_array($set_langs[0], array('fr')))
		setcookie("lang", $set_langs[0],time()+3600*24*31*365,'/');
	else
		setcookie("lang", 'us',time()+3600*24*31*365,'/');

    if(isset($_SERVER['HTTP_REFERER'])) wp_redirect($_SERVER['HTTP_REFERER']);
    else wp_redirect(home_url());

    die;
}

if(isset($_GET['lang']) && !empty($_GET['lang'])){
	setcookie("lang", $_GET['lang'],time()+3600*24*31*365,'/');
    if(isset($_SERVER['HTTP_REFERER'])) wp_redirect($_SERVER['HTTP_REFERER']);
    else wp_redirect(home_url());
    die;
}

global $is_contact_error, $message;

if(isset($_POST['j_contact_host'])){
	
	foreach($_POST as $post_index=>$post_value){
		if(!is_array($post_value))
			$_POST[$post_index] = sanitize_text_field(strip_tags(trim($post_value)));
	}
	
	$is_contact_error = true;
	
	if(!isValidEmail($_POST['j_from_email'])){
		$errors['j_from_email'] = 'error';
	}
	if(!empty($_POST['j_message']) && $_POST['j_message']!=am_lang('your_message') && !isset($errors['j_from_email'])){
	
		$contact_subject = am_lang('contact_host');
		
		$body = $_POST['j_message'].'
		<br /><br />
		'.$_POST['j_from_name'].'
		';
		
		$html_profile_link = '';
	
		if(!empty($_POST['j_to_id'])){
			$post_author = get_userdata($_POST['j_to_id']);
			$html_profile_link = home_url().'/author/?host='.$post_author->ID;
		}
		
		if(!empty($html_profile_link))
			$html_profile_link = '<br><br>'.$html_profile_link;
		
		$body .= $html_profile_link;
		
		
		$headers = 'From: '.$_POST['j_from_name'].' <'.$_POST['j_from_email'].'>' . "\r\n";

		add_filter('wp_mail_content_type',create_function('', 'return "text/html";'));
		wp_mail($_POST['j_to_email'], $contact_subject, $body, $headers);
		$is_contact_error = false;
		$message = am_lang('your_mail_was_sent');
		unset($_POST);
	}
}

define('myAJAX', ((isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH']=='XMLHttpRequest') || isset($_POST['aj_action']))?true:false);
add_action('init', 'ajax');
////////////////////////////////////////////////////
// AJAX request
//--------------------------------------------------
function ajax(){
    global $am_option;

	if(myAJAX && !is_admin() && isset($_POST['aj_action'])){
		global $current_user;
		get_currentuserinfo();

        switch($_POST['aj_action']){
            case 'addAvatar':
                if(isset($_FILES['file'])){
                    $res = uploadAvatar($_FILES['file']);
                    if($res === true) {
                        echo json_encode(array('succ'=>getAvatarbox($current_user->ID)));
                    } else {
                        echo json_encode(array('fail'=>$res));
                    }
                }
                break;
            case 'delAvatar':
                $res = deleteAvatar($current_user->ID);
                if($res) {
                    echo json_encode(array('succ'=>getAvatarbox($current_user->ID)));
                }
                break;
            case 'delAd':
                $imgId = (int)$_POST['id'];
                $pos = (int)$_POST['pos'];

                get_currentuserinfo();
                $user_id = get_current_user_id();
                $postId = am_get_user_ad($user_id);

                if(!$imgId || !$postId) die;

                deleteAdImg($postId, $imgId);

                $attachedImages = get_ad_images($postId);
                if(count($attachedImages)==0) {
                    $errors['am_images'] = 'error';
                    wp_update_post( array('ID'=>$postId, 'post_status'=>'draft'));
                    delete_post_meta($postId, 'am_ad_valid');
                }

                echo '<span class="btn_comm2">'.am_lang('add_image').'<input name="file" type="file" class="add-ad-img" id="add-'.$postId.'-'.$pos.'" /></span>';
                break;
            case 'addAd':

                if(isset($_FILES['file'])){
                    $pos = (int)$_POST['pos'];
                    $file = $_FILES['file'];

                    get_currentuserinfo();
                    $user_id = get_current_user_id();
                    $postId = am_get_user_ad($user_id);

                    $err = false;
                    $type = $file['type'];
                    $mimes=array('image/jpeg', 'image/pjpeg', 'image/png');

                    if(!in_array($type, $mimes)){ $err = am_lang('you_can_upload_only_jpg_png_files'); }
                    if($file['size']>5000000){ $err = am_lang('try_to_upload_smaller_image'); }
                    if($err){
                        echo json_encode(array('fail'=>$err));
                        die;
                    }

                    $attachedImage = addAdImg($postId, $pos, $file);
                    if($attachedImage){
                        $succ = '<img src="'. am_image_resize($attachedImage['url'], 213, 213) .'" alt="" title="" /><a href="#" class="btn_comm2 delete-ad-img" id="del-'. $postId .'-'. $attachedImage['id'].'-'. $pos.'">'. am_lang('delete') .'</a>';
                    }
                    echo json_encode(array('succ'=>$succ));
                }
                break;
            case 'changeCountry':
                $res = '<option value="">'.am_lang('select').'</option>';
                $countryName = $_POST['country'];
                foreach($am_option['defaults']['countries'] as $countryData){
                    if($countryData['name'] == $countryName){
                        foreach($countryData['states'] as $state){
                            $res .= '<option value="'.$state.'">'.$state.'</option>';
                        }
                        echo $res;
                        die;
                    }
                }

                break;
        }

		die();
	}
}

function addAdImg($post_id, $order, $file){
    $field_id = 'am_images';

    if ( ! function_exists( 'wp_handle_upload' ) ) require_once( ABSPATH . 'wp-admin/includes/file.php' );
    if ( ! function_exists( 'wp_generate_attachment_metadata' ) ) require_once( ABSPATH . 'wp-admin/includes/image.php' );

    $file_attr  = wp_handle_upload( $file, array( 'test_form' => false ) );

    $attachment = array(
        'guid'           => $file_attr['url'],
        'post_mime_type' => $file_attr['type'],
        'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $file['name'] ) ),
        'post_content'   => '',
        'post_status'    => 'inherit',
    );

    $id = wp_insert_attachment( $attachment, $file_attr['file'], $post_id );
    if ( ! is_wp_error( $id ) )
    {
        wp_update_attachment_metadata( $id, wp_generate_attachment_metadata( $id, $file_attr['file'] ) );

        add_post_meta( $post_id, $field_id, $id, false );
        wp_update_post(array( 'ID' => $id, 'menu_order' => $order));

        $res = array('id' => $id, 'url'=>$file_attr['url']);
        return $res;
    }
}

function deleteAdImg($postId, $adId){
    delete_post_meta( $postId, 'am_images', $adId );
    wp_delete_attachment( $adId );
}

////////////////////////////////////////////////////
// Upload file
//--------------------------------------------------
function uploadAvatar($file){
	global $current_user;
	get_currentuserinfo();

	$type = $file['type'];
    $mimes=array('image/jpeg', 'image/pjpeg', 'image/png');
    $err = false;

	if(!in_array($type, $mimes)){ $err = am_lang('you_can_upload_only_jpg_png_files'); }
	if($file['size']>5000000){ $err = am_lang('try_to_upload_smaller_image'); }
	if($err)
        return $err;

	$dir='wp-content/authors/';
	$filename = $current_user->user_login.'-'.$current_user->ID.'-'.time().'.'.getFileFormat($file['name']);
	$done = copy($file['tmp_name'], $dir.$filename);

	if($done){
		$is_avatar = get_user_meta($current_user->ID, 'author_image', true);
		if($is_avatar) update_user_meta($current_user->ID, 'author_image', $filename);
		else{
			delete_user_meta($current_user->ID, 'author_image');
			add_user_meta($current_user->ID, 'author_image', $filename);
		}
	}

	return $done?true:false;
}


////////////////////////////////////////////////////
// Delete avatar
//--------------------------------------------------
function deleteAvatar($userID){
	$avatar = get_user_meta($userID, 'author_image', true);
	if(!$avatar) return false;
	
	$done = unlink(WP_CONTENT_DIR.'/authors/'.$avatar);
	if($done){ delete_user_meta($userID, 'author_image'); }

	return $done?true:false;
}


////////////////////////////////////////////////////
// Get avatar box
//--------------------------------------------------
function getAvatarBox($userID){
    $html='';

	if(!myAJAX) $err = uploadNoAjax();

	$avatarName = get_user_meta($userID, 'author_image', true);


	if($avatarName){
		$avatar = site_url().'/wp-content/authors/'.$avatarName;
		$html .= '<img src="'.am_image_resize($avatar, 230, 230).'" alt="'.$avatarName.'" title="'.$avatarName.'" />';
		$html .= '<a class="btn_comm2" href="#" id="delAvatar">'.am_lang('delete').'</a>';
	}else{
		$html .= '<span class="btn_comm2">'.am_lang('add_image').'<input name="file" type="file" id="uploatAvatar" /></span>';
	}

	return $html;
}




////////////////////////////////////////////////////
// Upload user avatar when JS is disabled
//--------------------------------------------------
function uploadNoAjax(){
	if($_GET['action']=='deleteAvatar'){
		global $current_user;
		get_currentuserinfo();
		deleteAvatar($current_user->ID);
		return false;
	}

	if(!isset($_FILES['file'])) return false;

	$res = uploadAvatar($_FILES['file']);
	return $res;
}

////////////////////////////////////////////////////
// Get file format
//--------------------------------------------------
function getFileFormat($filename){ return end(explode(".", $filename)); }

?>