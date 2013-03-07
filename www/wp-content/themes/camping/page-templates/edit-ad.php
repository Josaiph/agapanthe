<?php
/*
Template Name: Edit Ad
*/

/*if ( !is_user_logged_in() ){
	wp_redirect(get_permalink(ot_get_option('general_login_page')));
	exit;
}*/
global $am_option, $current_user;

$user_id = get_current_user_id();
$ad_post = am_get_user_ad($user_id);
$attachedImages = get_ad_images($ad_post);

$message = '';
$message_success = am_lang('your_ad_was_saved');
$message_success_added = am_lang('your_ad_was_added');
$message_error = am_lang('missing_information_required');

if(isset($_POST['submit_ad'])){
    $errors = array();

	foreach($_POST as $post_index=>$post_value){
		if(!is_array($post_value))
			$_POST[$post_index] = sanitize_text_field(strip_tags(trim($post_value)));
	}

	$valid_items = array(
					 "am_capacity"=>array("type"=>"title","min"=>1,"max"=>255,"name"=>am_lang('capacity')),
					 "am_country"=>array("type"=>"title","min"=>1,"max"=>255,"name"=>am_lang('country')),
					 "am_state"=>array("type"=>"title","min"=>1,"max"=>255,"name"=>am_lang('state')),
					 "am_address_1"=>array("type"=>"title","min"=>1,"max"=>255,"name"=>am_lang('address')),
					 "am_zip"=>array("type"=>"title","min"=>1,"max"=>255,"name"=>am_lang('zip')),
					 "am_city"=>array("type"=>"title","min"=>1,"max"=>255,"name"=>am_lang('city')),
					 "am_content"=>array("type"=>"text","min"=>1,"max"=>10000,"name"=>am_lang('content')),
					 "am_garden_rules"=>array("type"=>"text","min"=>0,"max"=>10000,"name"=>am_lang('rules')),
					 );

	$errors =  checkdata($_POST, $valid_items);
	
	if(!isset($_POST['am_allowed']) || count($_POST['am_allowed'])<=0){
		$errors['am_allowed'] = 'error';
	}
	else{
		$is_selected = false;
		foreach($_POST['am_allowed'] as $v){
			if(!empty($v)){
				$is_selected = true;
			}
		}
		if($is_selected==false)
			$errors['am_allowed'] = 'error';
	}
	
	if(!isset($_POST['am_situation']) || count($_POST['am_situation'])<=0){
		$errors['am_situation'] = 'error';
	}
	else{
		$is_selected = false;
		foreach($_POST['am_situation'] as $v){
			if(!empty($v)){
				$is_selected = true;
			}
		}
		if($is_selected==false)
			$errors['am_situation'] = 'error';
	}
	
	if(!isset($_POST['am_situation']))
		$_POST['am_situation'] = array();
	
	if(!isset($_POST['am_amenities']))
		$_POST['am_amenities'] = array();
	
	if(!isset($_POST['am_activities']))
		$_POST['am_activities'] = array();
		
	if($_POST['am_adult_price']==am_lang('price'))
		$errors['am_adult_price'] = 'error';
		
	if($_POST['am_address_1']==am_lang('address'))
		$errors['am_address_1'] = 'error';
		
	if($_POST['am_zip']==am_lang('zip_postal_code'))
		$errors['am_zip'] = 'error';
		
	if($_POST['am_city']==am_lang('city'))
		$errors['am_city'] = 'error';
		
	if($_POST['am_content']==am_lang('describe_your_garden'))
		$errors['am_content'] = 'error';


    if(count($attachedImages)==0) {
        $errors['am_images'] = 'error';
        wp_update_post( array('ID'=>$ad_post, 'post_status'=>'draft'));
        delete_post_meta($ad_post, 'am_ad_valid');
    }

	$message = $message_error;

	if(count($errors)==0)
	{
		if(!empty($ad_post))
			$post = get_post($ad_post);
			
		$am_post_allowed = am_create_hash_array($_POST['am_allowed']);
		$am_post_situation = am_create_hash_array($_POST['am_situation']);
		$am_post_amenities = am_create_hash_array($_POST['am_amenities']);		
		$am_post_activities = am_create_hash_array($_POST['am_activities']);
			
		//update
		if(isset($post->ID)){

            $updated_post = array();
            $updated_post['ID'] = $post->ID;
            $updated_post['post_content'] = wpautop($_POST['am_content']);

            if($user_id){
                $updated_post['post_status'] = 'publish';
            } else {
                $updated_post['post_status'] = 'draft';
                add_post_meta($post->ID, 'am_ad_valid', true);
            }

			if(wp_update_post( $updated_post )){
				delete_post_meta($post->ID,'am_allowed');
				foreach($am_post_allowed as $k=>$v){
					if(!empty($v)){
						add_post_meta($post->ID, 'am_allowed', $v,false);
					}
				}
				
				update_post_meta($post->ID, 'am_capacity', $_POST['am_capacity']);
				
				
				delete_post_meta($post->ID,'am_situation');
				foreach($am_post_situation as $k=>$v){
					if(!empty($v)){
						add_post_meta($post->ID, 'am_situation', $v,false);
					}
				}
				
				delete_post_meta($post->ID,'am_amenities');
				foreach($am_post_amenities as $k=>$v){
					if(!empty($v)){
						add_post_meta($post->ID, 'am_amenities', $v,false);
					}
				}
				
				delete_post_meta($post->ID,'am_activities');
				foreach($am_post_activities as $k=>$v){
					if(!empty($v)){
						add_post_meta($post->ID, 'am_activities', $v,false);
					}
				}
				
				update_post_meta($post->ID, 'am_currency', $_POST['am_currency']);
				update_post_meta($post->ID, 'am_adult_price', $_POST['am_adult_price']);

				if(empty($_POST['am_child_price']) || $_POST['am_child_price']==am_lang('price'))
					update_post_meta($post->ID, 'am_child_price', $_POST['am_adult_price']);
				else
					update_post_meta($post->ID, 'am_child_price', $_POST['am_child_price']);
				update_post_meta($post->ID, 'am_country', $_POST['am_country']);
				update_post_meta($post->ID, 'am_state', $_POST['am_state']);
				update_post_meta($post->ID, 'am_address_1', $_POST['am_address_1']);
				update_post_meta($post->ID, 'am_address_2', $_POST['am_address_2']);
				update_post_meta($post->ID, 'am_zip', $_POST['am_zip']);
				update_post_meta($post->ID, 'am_city', $_POST['am_city']);
				if($_POST['am_garden_rules']!=am_lang('are_there_rules'))
					update_post_meta($post->ID, 'am_garden_rules', $_POST['am_garden_rules']);

                if ( !is_user_logged_in() ){
                    $_SESSION['anon-ad-added'] = true;
                    wp_redirect(get_permalink(ot_get_option('general_login_page')));
                    die;
                } else {
                    $message = $message_success;
                }
			} else {
				$errors[] = 'Error';
			}
		} else {
            $errors[] = 'Error';
        }

		unset($_POST);
	}
}
if($ad_post) {
    $ad_post = get_post($ad_post);

    $adult_price = am_get_custom_field('am_adult_price', $ad_post->ID, true);
    $child_price = am_get_custom_field('am_child_price', $ad_post->ID, true);
    $garden_rules = am_get_custom_field('am_garden_rules', $ad_post->ID, true);
    $currency = am_get_custom_field('am_currency', $ad_post->ID, true);

    $allowed = am_get_custom_field('am_allowed', $ad_post->ID, false);
    $capacity = am_get_custom_field('am_capacity', $ad_post->ID, true);
    $situation = am_get_custom_field('am_situation', $ad_post->ID, false);
    $amenities = am_get_custom_field('am_amenities', $ad_post->ID, false);
    $activities = am_get_custom_field('am_activities', $ad_post->ID, false);
    $address_1 = am_get_custom_field('am_address_1', $ad_post->ID, true);
    $address_2 = am_get_custom_field('am_address_2', $ad_post->ID, true);
    $zip = am_get_custom_field('am_zip', $ad_post->ID, true);
    $city = am_get_custom_field('am_city', $ad_post->ID, true);
    $images = am_get_custom_field('am_images', $ad_post->ID, false);
    $garden_rules = am_get_custom_field('am_garden_rules', $ad_post->ID, true);
    $post_content = $ad_post->post_content;

    $country = am_get_custom_field('am_country', $ad_post->ID, true);
    $state = am_get_custom_field('am_state', $ad_post->ID, true);

    $postId = $ad_post->ID;
}
get_header(); ?>
	
	<div id="content" class="content2">
		<h1><?php echo am_lang('submit_your_garden'); ?></h1>
		<?php if(!empty($message)) : ?><div class="reply_box<?php if(count($errors)==0) echo ' reply_box_color1'; else echo ' reply_box_color2'; ?>"><?php echo $message; ?></div><?php endif; ?>
		<div class="main_content">
			<form action="<?php echo $_SERVER['REQUEST_URI']?>" class="form_box" method="post">
			<fieldset>
				<div class="single_form">
					<div class="form_title"><b class="ico_who"></b><?php echo am_lang('who_you_like_receive'); ?></div>
					<?php
						$error_class = '';
						$post_value = $allowed;
						$input_name = 'am_allowed';
						if(isset($errors[$input_name])){
							$error_class = ' error';
						}
						if(isset($_POST[$input_name]) && !empty($_POST[$input_name])){
							$post_value = $_POST[$input_name];
						}
					?>
					<div class="object_row<?php echo $error_class; ?>">
						<?php
							$allow_1 = sanitize_title($am_option['defaults']['allowed'][0]);
							$allow_2 = sanitize_title($am_option['defaults']['allowed'][1]);
							$allow_3 = sanitize_title($am_option['defaults']['allowed'][2]);
						?>
						<ul class="object_style">
							<li<?php if(in_array($allow_1, $post_value)) echo ' class="on"'; ?> rel="am_allowed1_ck" title="<?php echo $allow_1; ?>"><a href="#" class="object1"><b><?php echo am_lang($am_option['defaults']['allowed'][0]); ?></b></a></li>
							<li<?php if(in_array($allow_2, $post_value)) echo ' class="on"'; ?> rel="am_allowed2_ck" title="<?php echo $allow_2; ?>"><a href="#" class="object2"><b><?php echo am_lang($am_option['defaults']['allowed'][1]); ?></b></a></li>
							<li<?php if(in_array($allow_3, $post_value)) echo ' class="on"'; ?> rel="am_allowed3_ck" title="<?php echo $allow_3; ?>"><a href="#" class="object3"><b><?php echo am_lang($am_option['defaults']['allowed'][2]); ?></b></a></li>
						</ul>
						<span class="required"><?php echo am_lang('required_field'); ?></span>
						<input type="hidden" name="<?php echo $input_name; ?>[]" value="<?php if(in_array($allow_1, $post_value)) echo $allow_1; ?>" id="am_allowed1_ck" />
						<input type="hidden" name="<?php echo $input_name; ?>[]" value="<?php if(in_array($allow_2, $post_value)) echo $allow_2; ?>" id="am_allowed2_ck" />
						<input type="hidden" name="<?php echo $input_name; ?>[]" value="<?php if(in_array($allow_3, $post_value)) echo $allow_3; ?>" id="am_allowed3_ck" />
					</div>
					<?php
						$error_class = '';
						$post_value = $capacity;
						$input_name = 'am_capacity';
						if(isset($errors[$input_name])){
							$error_class = ' error';
						}
						if(isset($_POST[$input_name]) && !empty($_POST[$input_name])){
							$post_value = $_POST[$input_name];
						}
					?>
					<div class="form_row<?php echo $error_class; ?>">
						<?php echo am_get_capacity_list($input_name,'simu_select',$post_value); ?>
						<span class="required"><?php echo am_lang('required_field'); ?></span>
					</div>
				</div><!-- /single_form -->
				
				<div class="line"></div>
				
				<div class="single_form">
					<?php
						$error_class = '';
						$post_value = $situation;
						$input_name = 'am_situation';
						if(isset($errors[$input_name])){
							$error_class = ' error';
						}
						if(isset($_POST[$input_name]) && !empty($_POST[$input_name])){
							$post_value = $_POST[$input_name];
						}
					?>
					<div class="form_title<?php echo $error_class; ?>"><b class="ico_situation"></b><?php echo am_lang('situation'); ?> <strong class="required"><?php echo am_lang('required_field'); ?></strong></div>
					<?php echo am_get_checkboxes_list($input_name,'checkbox_list',$post_value,$am_option['defaults']['situation']); ?>
				</div><!-- /single_form -->
				
				
				<div class="line"></div>
				
				<div class="single_form">
					<?php
						$error_class = '';
						$post_value = $amenities;
						$input_name = 'am_amenities';
						if(isset($errors[$input_name])){
							$error_class = ' error';
						}
						if(isset($_POST[$input_name]) && !empty($_POST[$input_name])){
							$post_value = $_POST[$input_name];
						}
					?>
					<div class="form_title<?php echo $error_class; ?>"><b class="ico_amentities"></b><?php echo am_lang('amenities'); ?></div>
					<?php echo am_get_checkboxes_list($input_name,'checkbox_list checkbox_list2',$post_value,$am_option['defaults']['amenities']); ?>
					<div class="form_note_box">
						<p><?php echo am_lang('select_what_your_campers_access'); ?></p>
					</div>
				</div><!-- /single_form -->
				
				
				<div class="line"></div>
				
				<div class="single_form">
					<?php
						$error_class = '';
						$post_value = $activities;
						$input_name = 'am_activities';
						if(isset($errors[$input_name])){
							$error_class = ' error';
						}
						if(isset($_POST[$input_name]) && !empty($_POST[$input_name])){
							$post_value = $_POST[$input_name];
						}
					?>
					<div class="form_title<?php echo $error_class; ?>"><b class="ico_activities"></b><?php echo am_lang('activities'); ?></div>
					<?php echo am_get_checkboxes_list($input_name,'checkbox_list checkbox_list2',$post_value,$am_option['defaults']['activities']); ?>
					<div class="form_note_box">
						<p><?php echo am_lang('specify_possible_do_around'); ?></p>
					</div>
				</div><!-- /single_form -->
				
				
				<div class="line"></div>
				
				<div class="single_form">
					<?php
						$error_class = '';
						$post_value = $currency;
						$input_name = 'am_currency';
						if(isset($errors[$input_name])){
							$error_class = ' error';
						}
						if(isset($_POST[$input_name]) && !empty($_POST[$input_name])){
							$post_value = $_POST[$input_name];
						}
					?>
					<div class="form_title form_title_price<?php echo $error_class; ?>">
						<b class="ico_price"></b>
						<?php echo am_lang('prices'); ?>
						<?php echo am_get_select_list($input_name,'simu_select',$post_value,$am_option['defaults']['currency']); ?>
					</div>
					<?php
						$error_class = '';
						$post_value = $adult_price;
						$post_value_default = am_lang('price');
						if(empty($post_value))
							$post_value = am_lang('price');
						$input_name = 'am_adult_price';
						if(isset($errors[$input_name])){
							$error_class = ' error';
						}
						if(isset($_POST[$input_name]) && !empty($_POST[$input_name])){
							$post_value = $_POST[$input_name];
						}
					?>
					<div class="form_row<?php echo $error_class; ?>">
						<div class="ico_person_big"></div>
						<input type="text" name="<?php echo $input_name; ?>" value="<?php echo $post_value; ?>" data-default="<?php echo $post_value_default; ?>">
						<div class="para_night"><?php echo am_lang('per_night_form'); ?></div>
						<span class="required"><?php echo am_lang('required_field'); ?></span>
					</div>
					<?php
						$error_class = '';
						$post_value = $child_price;
						$post_value_default = am_lang('price');
						if(empty($post_value))
							$post_value = am_lang('price');
						$input_name = 'am_child_price';
						if(isset($errors[$input_name])){
							$error_class = ' error';
						}
						if(isset($_POST[$input_name]) && !empty($_POST[$input_name])){
							$post_value = $_POST[$input_name];
						}
					?>
					<div class="form_row<?php echo $error_class; ?>">
						<div class="ico_person_small"></div>
						<input type="text" name="<?php echo $input_name; ?>" value="<?php echo $post_value; ?>" data-default="<?php echo $post_value_default; ?>">
						<div class="para_night"><?php echo am_lang('per_night_form'); ?></div>
					</div>
					
					<div class="form_note_box">
						<p><?php echo am_lang('ideas_prices'); ?></p>
						<p><?php echo am_lang('ideas_prices_plot'); ?></p>
						<p><span><?php echo am_lang('ideas_prices_descr'); ?></span></p>
					</div>
				</div><!-- /single_form -->
				
				
				<div class="line"></div>
				
				<div class="single_form">
					<div class="form_title<?php if(isset($errors['am_images'])) echo ' error'; ?>">
						<b class="ico_picture"></b>
						<?php echo am_lang('pictures'); ?>
						<strong class="required"><?php echo am_lang('picture_required'); ?></strong>
                        <strong class="required" id="profile_required_image"></strong>
					</div>
					<div class="upload_boxes">
					<div class="upload_boxes_inside">
                        <?php for($i=1; $i<=6; $i++):?>
                            <div class="upload_box">
                                <?php if(isset($attachedImages[$i])) : ?>
                                    <img src="<?php echo am_image_resize($attachedImages[$i][0], 213, 213) ?>" alt="" title="" />
                                    <a href="#" class="btn_comm2 delete-ad-img" id="del-<?php echo $attachedImages[$i]['id']?>-<?php echo $i?>"><?php echo am_lang('delete'); ?></a>
                                <?php else :?>
                                    <span class="btn_comm2"><?php echo am_lang('add_image')?><input name="file" type="file" class="add-ad-img" id="add-<?php echo $i?>" /></span>
                                <?php endif;?>
                            </div>
                        <?php endfor;?>
					</div>
					</div>

					<div class="form_note_box">
						<p><?php echo am_lang('add_image_advice'); ?></p>
						<p><span><?php echo am_lang('add_image_advice2'); ?></span></p>
						<p><span><?php echo am_lang('add_image_advice3'); ?></span></p>
					</div>
				</div><!-- /single_form -->
				
				
				<div class="line"></div>
				
				<div class="single_form">
					<div class="form_title">
						<b class="ico_address"></b>
						<?php echo am_lang('address'); ?> <em><?php echo am_lang('not_visible_online'); ?></em>
					</div>
					
					<?php
						$error_class = '';
						$post_value = $country;
						$input_name = 'am_country';
						if(isset($errors[$input_name])){
							$error_class = ' error';
						}
						if(isset($_POST[$input_name]) && !empty($_POST[$input_name])){
							$post_value = $_POST[$input_name];
						}
						
						$error_class_s = '';
						$post_value_s = $state;
						$input_name_s = 'am_state';
						if(isset($errors[$input_name_s])){
							$error_class = ' error';
						}
						if(isset($_POST[$input_name_s])){
							$post_value_s = $_POST[$input_name_s];
						}
					?>
					<div class="form_row<?php echo $error_class; ?>">
						<?php echo am_get_countries_list($input_name,'select2 simu_select',$post_value); ?>
						<?php echo am_get_states_list($input_name_s, 'select2 simu_select', $post_value, $post_value_s); ?>
						<span class="required"><?php echo am_lang('required_field'); ?></span>
					</div>
					
					<?php
						$error_class = '';
						$post_value = $address_1;
						$post_value_default = am_lang('address');
						if(empty($post_value))
							$post_value = am_lang('address');
						$input_name = 'am_address_1';
						if(isset($errors[$input_name])){
							$error_class = ' error';
						}
						if(isset($_POST[$input_name]) && !empty($_POST[$input_name])){
							$post_value = $_POST[$input_name];
						}
					?>
					<div class="form_row<?php echo $error_class; ?>">
						<input type="text" name="<?php echo $input_name; ?>" value="<?php echo $post_value; ?>" class="input_txt2" data-default="<?php echo $post_value_default; ?>">
						<span class="required"><?php echo am_lang('required_field'); ?></span>	</div>
					
					<?php
						$error_class = '';
						$post_value = $address_2;
						$post_value_default = am_lang('address_optional');
						if(empty($post_value))
							$post_value = am_lang('address_optional');
						$input_name = 'am_address_2';
						if(isset($errors[$input_name])){
							$error_class = ' error';
						}
						if(isset($_POST[$input_name]) && !empty($_POST[$input_name])){
							$post_value = $_POST[$input_name];
						}
					?>
					<div class="form_row<?php echo $error_class; ?>">
						<input type="text" name="<?php echo $input_name; ?>" value="<?php echo $post_value; ?>" class="input_txt2" data-default="<?php echo $post_value_default; ?>">
					</div>
					
					<?php
						$error_class = '';
						$post_value = $zip;
						$post_value_default = am_lang('zip_postal_code');
						if(empty($post_value))
							$post_value = am_lang('zip_postal_code');
						$input_name = 'am_zip';
						if(isset($errors[$input_name])){
							$error_class = ' error';
						}
						if(isset($_POST[$input_name]) && !empty($_POST[$input_name])){
							$post_value = $_POST[$input_name];
						}
					?>
					<div class="form_row<?php echo $error_class; ?>">
						<input type="text" name="<?php echo $input_name; ?>" value="<?php echo $post_value; ?>" class="input_txt3" data-default="<?php echo $post_value_default; ?>">
						<span class="required"><?php echo am_lang('required_field'); ?></span>
					</div>
					
					<?php
						$error_class = '';
						$post_value = $city;
						$post_value_default = am_lang('city');
						if(empty($post_value))
							$post_value = am_lang('city');
						$input_name = 'am_city';
						if(isset($errors[$input_name])){
							$error_class = ' error';
						}
						if(isset($_POST[$input_name]) && !empty($_POST[$input_name])){
							$post_value = $_POST[$input_name];
						}
					?>
					<div class="form_row<?php echo $error_class; ?>">
						<input type="text" name="<?php echo $input_name; ?>" value="<?php echo $post_value; ?>" class="input_txt3" data-default="<?php echo $post_value_default; ?>">
						<span class="required"><?php echo am_lang('required_field'); ?></span>	</div>
					
					<div class="form_note_box">
						<p><?php echo am_lang('address_note'); ?></p>
					</div>
				</div><!-- /single_form -->
				
				
				<div class="line"></div>
				
				<div class="single_form">
					
					<?php
						$error_class = '';
						$post_value = strip_tags($post_content);
						$post_value_default = am_lang('describe_your_garden');
						if(empty($post_value))
							$post_value = am_lang('describe_your_garden');
						$input_name = 'am_content';
						if(isset($errors[$input_name])){
							$error_class = ' error';
						}
						if(isset($_POST[$input_name]) && !empty($_POST[$input_name])){
							$post_value = $_POST[$input_name];
						}
					?>
					<div class="form_title<?php echo $error_class; ?>">
						<b class="ico_property"></b>
						<?php echo am_lang('tell_about_property'); ?>
						<strong class="required"><?php echo am_lang('required_field'); ?></strong>
					</div>
					<div class="form_row">
						<textarea name="<?php echo $input_name; ?>" data-default="<?php echo $post_value_default; ?>"><?php echo $post_value; ?></textarea>	
					</div>
					
					<?php
						$error_class = '';
						$post_value = strip_tags($garden_rules);
						$post_value_default = am_lang('are_there_rules');
						if(empty($post_value))
							$post_value = am_lang('are_there_rules');
						$input_name = 'am_garden_rules';
						if(isset($errors[$input_name])){
							$error_class = ' error';
						}
						if(isset($_POST[$input_name]) && !empty($_POST[$input_name])){
							$post_value = $_POST[$input_name];
						}
					?>
					<div class="form_row">
						<textarea name="<?php echo $input_name; ?>" data-default="<?php echo $post_value_default; ?>"><?php echo $post_value; ?></textarea>		
					</div>
					
					<p><?php echo am_lang('by_clicking_continue'); ?> <a href="#term_of_use" class="fancybox"><?php echo am_lang('terms_of_use'); ?></a></p>
					<div class="submit_btn"><input type="submit" class="btn_comm1" value="<?php echo am_lang('continue'); ?>" name="submit_ad"></div>
					
					<div class="form_note_box">
						<p><?php echo am_lang('ad_advice_1'); ?></p>
						<div class="form_note_txt">
							<p><?php echo am_lang('ad_advice_2'); ?></p>
						</div>
					</div>
				</div><!-- /single_form -->
			</fieldset>
			</form>	
		</div><!-- /main_content -->
	</div>
	<!-- /content2 -->

<?php get_footer(); ?>