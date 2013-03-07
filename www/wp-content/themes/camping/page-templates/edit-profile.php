<?php
/*
Template Name: Edit Profile
*/

if ( !is_user_logged_in() ){
	wp_redirect(get_permalink(ot_get_option('general_login_page')));
	exit;
}

global $errors, $message;
		
$user_id = get_current_user_id();
	
$errors = array();
$message = '';

if(isset($_POST['profile_introduce'])){
	
	foreach($_POST as $post_index=>$post_value){
		if(!is_array($post_value))
			$_POST[$post_index] = sanitize_text_field(strip_tags(trim($post_value)));
	}
	
	$message_success = '';

	$valid_items = array(
					 "profile_country"=>array("type"=>"title","min"=>0,"max"=>255,"name"=>am_lang('country')),
					 "profile_city"=>array("type"=>"title","min"=>0,"max"=>255,"name"=>am_lang('city')),
					 "profile_languages"=>array("type"=>"title","min"=>0,"max"=>255,"name"=>am_lang('languages')),
					 "profile_introduce"=>array("type"=>"text","min"=>0,"max"=>10000,"name"=>am_lang('introduce'))
					 );

	$errors =  checkdata($_POST, $valid_items);
		
	if(count($errors)==0)
	{
		$message = $message_success;
		update_user_meta( $user_id, 'user_languages', $_POST['profile_languages']);
		update_user_meta( $user_id, 'user_country', $_POST['profile_country']);
	
		if($_POST['profile_city']!=am_lang('city')){
		
			update_user_meta( $user_id, 'user_city', $_POST['profile_city']);
		}
		
		if($_POST['profile_introduce']!=am_lang('introduce_text')){
			update_user_meta( $user_id, 'description', $_POST['profile_introduce']);
		}
		
		unset($_POST);
	}
}

get_header(); ?>
	
	<div id="content" class="content2">
		<h1><?php echo am_lang('introduce_title'); ?></h1>
		<div class="main_content">

            <div class="form_box">
			<fieldset>
                <form action="<?php echo $_SERVER['REQUEST_URI']?>" id="form_box_all" method="post">
				<div class="single_form">
					<div class="form_title"><b class="ico_situation"></b><?php echo am_lang('where_do_you_live'); ?></div>
					<?php
						$error_class = '';
						$post_value = get_user_meta($user_id,'user_country',true);
						$input_name = 'profile_country';
						if(isset($errors[$input_name])){
							$error_class = ' error';
						}
						if(isset($_POST[$input_name]) && !empty($_POST[$input_name])){
							$post_value = $_POST[$input_name];
						}
					?>
					<div class="form_row<?php echo $error_class; ?>">
						<?php echo am_get_countries_list($input_name,'select2 simu_select',$post_value); ?>
					</div>
					
					<?php
						$error_class = '';
						$post_value = get_user_meta($user_id,'user_city',true);
						$post_value_default = am_lang('city');
						if(empty($post_value))
							$post_value = am_lang('city');
						$input_name = 'profile_city';
						if(isset($errors[$input_name])){
							$error_class = ' error';
						}
						if(isset($_POST[$input_name]) && !empty($_POST[$input_name])){
							$post_value = $_POST[$input_name];
						}
					?>
					<div class="form_row<?php echo $error_class; ?>">
						<input type="text" name="<?php echo $input_name; ?>" id="<?php echo $input_name; ?>" value="<?php echo $post_value; ?>" class="input_txt3" data-default="<?php echo $post_value_default; ?>" />
					</div>
				</div><!-- /single_form -->
				
				<div class="line"></div>
				
				<div class="single_form">
					<div class="form_title"><b class="ico_chat"></b><?php echo am_lang('what_languages_speak'); ?></div>
					
					<?php
						$error_class = '';
						$post_value = get_user_meta($user_id,'user_languages',true);
						$post_value_default = am_lang('languages_example');
						if(empty($post_value))
							$post_value = am_lang('languages_example');
						$input_name = 'profile_languages';
						if(isset($errors[$input_name])){
							$error_class = ' error';
						}
						if(isset($_POST[$input_name]) && !empty($_POST[$input_name])){
							$post_value = $_POST[$input_name];
						}
					?>
					<div class="form_row<?php echo $error_class; ?>">
						<input type="text" name="<?php echo $input_name; ?>" id="<?php echo $input_name; ?>" value="<?php echo $post_value; ?>" class="input_txt2" data-default="<?php echo $post_value_default; ?>" />
					</div>
				</div><!-- /single_form -->
				
				<div class="line"></div>
				
				<div class="single_form">
					<div class="form_title">
						<b class="ico_who"></b>
						<?php echo am_lang('introduce_title'); ?>
					</div>
					
					<?php
						$error_class = '';
						$post_value = get_user_meta($user_id,'description',true);
						$post_value_default = am_lang('introduce_text');
						if(empty($post_value))
							$post_value = am_lang('introduce_text');
						$input_name = 'profile_introduce';
						if(isset($errors[$input_name])){
							$error_class = ' error';
						}
						if(isset($_POST[$input_name]) && !empty($_POST[$input_name])){
							$post_value = $_POST[$input_name];
						}
					?>
					<div class="form_row<?php echo $error_class; ?>">
						<textarea name="<?php echo $input_name; ?>" id="<?php echo $input_name; ?>" data-default="<?php echo $post_value_default; ?>"><?php echo $post_value; ?></textarea>	
					</div>
					
					<div class="form_note_box">
						<p><?php echo am_lang('profile_advice_1'); ?></p>
					</div>
				</div><!-- /single_form -->
                </form>

				<div class="line"></div>

                <form action="<?php echo $_SERVER['REQUEST_URI']?>" id="form_box_upload" method="post" enctype="multipart/form-data">
                    <input type='hidden' id="aj_action" name="aj_action" />
                    <div class="single_form">
                        <div class="form_title">
                            <b class="ico_picture"></b>
                            <?php echo am_lang('profile_add_photo'); ?>
                        </div>
                        <span class="required required_image" id="profile_required_image"></span>

                        <div class="upload_boxes">
                            <div class="upload_boxes_inside">
                                <div class="upload_box" id="uploadAvatar">
                                    <?php echo getAvatarBox($user_id); ?>
                                </div>
                            </div>
                        </div>

                        <div class="form_note_box">
                            <p><?php echo am_lang('profile_advice_2'); ?></p>
                        </div>
                    </div><!-- /single_form -->
				</form>

				<div class="single_form">
					<div class="submit_btn"><input type="submit" class="btn_comm1" id="f_submit" value="<?php echo am_lang('save'); ?>" name="profile_sent"></div>
				</div>
			</fieldset>
            </div>
		</div><!-- /main_content -->
	</div>
	<!-- /content2 -->

<?php get_footer(); ?>