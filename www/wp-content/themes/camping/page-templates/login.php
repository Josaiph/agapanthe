<?php
/*
Template Name: Login
*/

if ( is_user_logged_in() ){
	wp_redirect(home_url());
	exit;
}

global $errors, $message, $is_pass_error;

$errors = array();
$message = '';
$message_class = ' reply_box_color1';

if(isset($_POST['submit'])){
	foreach($_POST as $post_index=>$post_value){
		if(!is_array($post_value))
			$_POST[$post_index] = sanitize_text_field(strip_tags(trim($post_value)));
	}
	
	$message_success = am_lang('register_text');
	$message_error = am_lang('register_error');
	
	$message = $message_error;
	$message_class = ' reply_box_color2';

	$valid_items = array(
					 "first_name"=>array("type"=>"title","min"=>1,"max"=>255,"name"=>am_lang('first_name')),
					 "last_name"=>array("type"=>"title","min"=>1,"max"=>255,"name"=>am_lang('last_name')),
					 "user_phone"=>array("type"=>"title","min"=>0,"max"=>255,"name"=>am_lang('phone')),
					 "user_email"=>array("type"=>"email","min"=>1,"max"=>255,"name"=>am_lang('email')),
					 "user_pass"=>array("type"=>"title","min"=>1,"max"=>255,"name"=>am_lang('password'))
					 );

	$errors =  checkdata($_POST, $valid_items);
	
	$_POST['user_login'] = $_POST['user_email'];
	
	if($_POST['first_name']==am_lang('first_name')){
		$errors['first_name'] = am_lang('required_field_clean');
	}
	
	if($_POST['last_name']==am_lang('last_name')){
		$errors['last_name'] = am_lang('required_field_clean');
	}
	
	if($_POST['user_pass']==am_lang('password')){
		$errors['user_pass'] = am_lang('required_field_clean');
	}
	
	if ( username_exists( $_POST['user_login'] ) ){
		$errors['user_login'] = am_lang('register_username_in_use');
	}
	
	if(isset($errors['user_email']))
		$errors['user_email'] = am_lang('email_incorrect');
	
	if ( email_exists( $_POST['user_email'] ) ){
		$errors['user_email'] = am_lang('register_email_in_use');
	}
		
	if(count($errors)==0)
	{
		$message = $message_success;
		$message_class = ' reply_box_color1';
		
		$user_phone = $_POST['user_phone'];
		if($user_phone==am_lang('register_phone_optional')){
			$user_phone= '';
		}
		
		$user = array(
			'user_login' => $_POST['user_login'],
			'user_email' => $_POST['user_email'],
			'user_pass' => $_POST['user_pass'],
			'first_name' => $_POST['first_name'],
			'last_name' => $_POST['last_name'],
			'display_name' => $_POST['first_name'].' '.$_POST['last_name']
		);
		$user_id = wp_insert_user( $user );


        add_user_meta( $user_id, 'user_phone', $user_phone);

        $hash = wp_generate_password(12, false);
        add_user_meta( $user_id, 'user_inactive', $hash);


        $contact_subject = am_lang('activate_email_subject');

		$body = am_lang('need_to_activate_account').' <a href="'.site_url('login/?key='.$hash).'">'.am_lang('click_here').'</a>'.
		'<br /><br />
		'.am_lang('activate_account_link_not_work').' '.site_url('login/?key='.$hash).'
		<br /><br />
		'.get_bloginfo('name').'
		';
		
		$headers = 'From: '.get_bloginfo('name').' <'.ot_get_option('general_admin_email').'>' . "\r\n";
		add_filter('wp_mail_content_type',create_function('', 'return "text/html";'));
		wp_mail($_POST['user_email'], $contact_subject, $body, $headers);

		//add draft post
        if(isset($_SESSION['anon-ad']) && get_post($_SESSION['anon-ad'])) {
            $post_id = (int)$_SESSION['anon-ad'];
            $updated_post = array();
            $updated_post['ID'] = $post_id;
            $updated_post['post_author'] = $user_id;
            $updated_post['post_title'] = 'Ad: '.am_get_short_author_name(get_userdata($user_id));
            $updated_post['post_name'] = sanitize_title($updated_post['post_title']);
            wp_update_post( $updated_post );
        } else {
            am_addNewAd($user_id);
        }
		unset($_POST);
	}
}

if(isset($_POST['wp-submit'])){
	foreach($_POST as $post_index=>$post_value){
		if(!is_array($post_value))
			$_POST[$post_index] = sanitize_text_field(strip_tags(trim($post_value)));
	}
	
	$message_success = '';
	$message_error = am_lang('login_error');

	$valid_items = array(
					 "lg_user_login"=>array("type"=>"email","min"=>1,"max"=>255,"name"=>am_lang('regsiter_email_address')),
					 "lg_user_pass"=>array("type"=>"title","min"=>1,"max"=>255,"name"=>am_lang('password')),
					 );
	$errors =  checkdata($_POST, $valid_items);
	
	if($_POST['lg_user_login']==am_lang('regsiter_email_address')){
		$errors['lg_user_login'] = am_lang('required_field_clean');
	}
	
	if($_POST['lg_user_pass']==am_lang('password')){
		$errors['lg_user_pass'] = am_lang('required_field_clean');
	}
	
	if(isset($errors['lg_user_login']))
		$errors['lg_user_login'] = am_lang('email_incorrect');
		
	if(count($errors)==0)
	{
	    // log in automatically
        $user = get_user_by('login', $_POST['lg_user_login']);
        if(!$user) {
            $message = $message_error;
            $message_class = ' reply_box_color2';
            return false;
        }
        $inactive = delete_user_meta($user->ID, 'user_inactive');

        if($user && !$inactive){
        	if(wp_check_password($_POST['lg_user_pass'],$user->user_pass,$user->ID)){

                if(isset($_SESSION['anon-ad'])) {
                    $post_id = am_get_user_ad($user->ID);
                    if(get_post_meta($post_id, 'am_ad_valid', true)){
                        wp_update_post( array('ID'=>$post_id, 'post_status'=>'publish'));
                        delete_post_meta($post_id, 'am_ad_valid');
                    }

                    if($post_id != (int)$_SESSION['anon-ad']){
                        wp_delete_post((int)$_SESSION['anon-ad'], true);
                        $_SESSION['anon-ad-fail'] = true;
                    }
                    unset($_SESSION['anon-ad']);
                }
		        wp_set_current_user( $user->ID, $_POST['lg_user_login'] );
		        wp_set_auth_cookie( $user->ID );
		        do_action( 'wp_login', $_POST['lg_user_login'] );

                if(user_can($user->ID, 'administrator')){
                    am_deleteOldAD();
                }
				wp_redirect(home_url());
				exit;
        	}
	        else{
		        $message = $message_error;
		        $message_class = ' reply_box_color2';
	        }
        }
        else{
		    $message = $message_error;
		    $message_class = ' reply_box_color2';
        }
	}
}

if(isset($_POST['j_pass_resend'])){
	$is_pass_error = true;
	if(!empty($_POST['j_email'])){
		$user = get_user_by('login', $_POST['j_email']);  get_user_by('login', $_POST['j_email']);
		if(isset($user->ID) && $user->ID>0){
			$passwrod = am_generate_password ($length = 8);
			wp_set_password( $passwrod, $user->ID );
			
			$contact_subject = am_lang('forgot_pass_form');
			
			$body = am_lang('your_new_passwrod').' '.$passwrod.'
			<br /><br />
			'.am_lang('you_can_login_here').' '.get_permalink(ot_get_option('general_login_page')).'
			<br /><br />
			'.get_bloginfo('name').'
			';
			
			$headers = 'From: '.get_bloginfo('name').' <'.ot_get_option('general_admin_email').'>' . "\r\n";
			add_filter('wp_mail_content_type',create_function('', 'return "text/html";'));
			wp_mail($user->user_email, $contact_subject, $body, $headers);
			$is_pass_error = false;
			$message = am_lang('forgot_pass_sent');
		}
	}
}

if(isset($_GET['key'])){
    $users = get_users(array('meta_key' => 'user_inactive', 'meta_value' => $_GET['key']));

    if(is_array($users) && isset($users[0])){
        $user = $users[0];
        delete_user_meta($user->ID, 'user_inactive');

        $contact_subject = am_lang('login_email_subject');
        $body = am_lang('you_can_login_here').' '.get_permalink(ot_get_option('general_login_page')).
            '<br /><br />'.get_bloginfo('name');

        $headers = 'From: '.get_bloginfo('name').' <'.ot_get_option('general_admin_email').'>' . "\r\n";
        add_filter('wp_mail_content_type',create_function('', 'return "text/html";'));
        wp_mail($user->user_email, $contact_subject, $body, $headers);

        $message = am_lang('user_activated');
    }
}
if(isset($_SESSION['anon-ad-added'])){
    unset($_SESSION['anon-ad-added']);
    $message_class = ' reply_box_color1';
    $message = am_lang('ann_save_ad');
}

get_header(); ?>
	
<div id="content">
	<?php if(!empty($message)) : ?><div class="reply_box<?php echo $message_class; ?>"><?php echo $message; ?></div><?php endif; ?>
	<div class="content_box login_box">
		<div class="cont_box">
			<?php get_template_part('templates/register');  ?>
			
			<?php get_template_part('templates/login');  ?>	
		</div><!-- /cont_box -->
	</div><!-- /content_box -->
</div><!-- /content -->

<?php get_footer(); ?>