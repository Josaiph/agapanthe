<?php
/*
Template Name: Help us
*/
	
	$errors = array();
	$message = '';
	$is_error = true;

	if(isset($_POST['j_send']))
	{
		foreach($_POST as $post_index=>$post_value){
			if(!is_array($post_value))
				$_POST[$post_index] = strip_tags(trim($post_value));
		}
		
		$j_email = $_POST['j_email'];
		
		$contact_email = ot_get_option('general_admin_email');
		$reply_email = $j_email;
		$reply_name = $j_name;
		$contact_subject = am_lang('help_horm');
		$contact_message = am_lang('your_mail_was_sent');
		$contact_message_error = am_lang('please_entry_all_fields');
	
		$valid_items = array(
						 "j_message"=>array("type"=>"title","min"=>1,"max"=>255,"name"=>am_lang('message')),
						 "j_email"=>array("type"=>"email","min"=>1,"max"=>255,"name"=>am_lang('email'))
						 );

		$errors =  checkdata($_POST, $valid_items);
		
		if($_POST['j_message']==am_lang('what_help_bring_community'))
			$errors['j_message'] = 'Error';
		
		$message = $contact_message_error;
			
		if(count($errors)==0)
		{
			
			$body = '<table>';
			
			foreach($valid_items as $item_index=>$item_value){
				$body .= '<tr><td>'.$item_value['name'].':</td><td>'.$_POST[$item_index].'</td></tr>';
			}
			$body .= '</table>';
			
		    $headers = 'From: '.$reply_email.' <'.$reply_email.'>' . "\r\n";
		    add_filter('wp_mail_content_type',create_function('', 'return "text/html";'));
		    wp_mail($contact_email, $contact_subject, $body, $headers);
	
			$message = $contact_message;
			$is_error = false;
			unset($_POST);
		}
	}

get_header(); ?>
	<div id="content">
		<?php if(!empty($message)) : ?><div class="reply_box <?php if($is_error) echo 'reply_box_color2'; else echo 'reply_box_color1'; ?>"><?php echo $message; ?></div><?php endif; ?>
		
		<div class="content_box">
			<div class="cont_box">
				<?php echo am_lang('help_header'); ?>
			</div><!-- /cont_box -->
			
			<div class="intro_box">
				<ul>
					<?php echo am_lang('help_list'); ?>
				</ul>
			</div>
			
			<div class="cont_box">
				<div class="step_row">
					<div class="step_box step_box1">
						<?php echo am_lang('help_block_1'); ?>
					</div>
					
					<div class="step_box step_box2">
						<?php echo am_lang('help_block_2'); ?>
					</div>
				</div><!-- /step_row -->
				
				<div class="step_row step_row2">
					<div class="step_box step_box3">
						<?php echo am_lang('help_block_3'); ?>
					</div>
					<form action="#" class="help_form" method="post">
						<fieldset>
							<?php
								$error_class = '';
								$post_value = am_lang('your_email');
								$post_value_default = am_lang('your_email');
								$input_name = 'j_email';
								if(isset($errors[$input_name])){
									$error_class = ' error';
								}
								if(isset($_POST[$input_name])){
									$post_value = $_POST[$input_name];
								}
							?>
							<input id="<?php echo $input_name; ?>" type="text" name="<?php echo $input_name; ?>" value="<?php echo $post_value; ?>" data-default="<?php echo $post_value_default; ?>" />
							<?php
								$error_class = '';
								$post_value = am_lang('what_help_bring_community');
								$post_value_default = am_lang('what_help_bring_community');
								$input_name = 'j_message';
								if(isset($errors[$input_name])){
									$error_class = ' error';
								}
								if(isset($_POST[$input_name])){
									$post_value = $_POST[$input_name];
								}
							?>
							<textarea id="<?php echo $input_name; ?>" name="<?php echo $input_name; ?>" data-default="<?php echo $post_value_default; ?>"><?php echo $post_value; ?></textarea>	
							<div class="submit_btn"><input type="submit" value="<?php echo am_lang('offer_your_help'); ?>" class="btn_comm1" name="j_send" /></div>
						</fieldset>
					</form>
				</div><!-- /step_row -->
			</div><!-- /cont_box -->
		</div><!-- /content_box -->
	</div><!-- /content -->


	<div id="bottom_content">
		<div class="bottom_para"><?php echo am_lang('follow_adventure_here'); ?></div>
		<a target="_blank" class="btn_comm2" href="<?php echo ot_get_option('general_blog_page_url'); ?>"><?php echo am_lang('discovery_our_blog'); ?></a>
	</div>

<?php get_footer(); ?>