<?php
	global $_POST, $errors;
?>
<form method="post" action="#" class="member_form member_form2">
<fieldset>
	<h1><?php echo am_lang('already_member'); ?></h1>
	<?php
		$error_class = '';
		$post_value = am_lang('regsiter_email_address');
		$post_value_default = am_lang('regsiter_email_address');
		$input_name = 'lg_user_login';
		if(isset($errors[$input_name])){
			$error_class = ' error';
		}
		if(isset($_POST[$input_name]) && !empty($_POST[$input_name])){
			$post_value = $_POST[$input_name];
		}
	?>
	<div class="form_block<?php echo $error_class; ?>">
		<input type="text" name="<?php echo $input_name; ?>" id="<?php echo $input_name; ?>" value="<?php echo $post_value; ?>" class="input_txt2" data-default="<?php echo $post_value_default; ?>" />
		<span class="required">- <?php echo $errors[$input_name]; ?></span>
	</div>
	<?php
		$error_class = '';
		$post_value = am_lang('password');
		$post_value_default = am_lang('password');
		$input_name = 'lg_user_pass';
		if(isset($errors[$input_name])){
			$error_class = ' error';
		}
		if(isset($_POST[$input_name]) && !empty($_POST[$input_name])){
			$post_value = $_POST[$input_name];
		}
	?>
	<div class="form_block<?php echo $error_class; ?>">
		<input type="password" name="<?php echo $input_name; ?>" id="<?php echo $input_name; ?>" value="<?php echo $post_value; ?>" class="input_txt3" data-default="<?php echo $post_value_default; ?>" />
		<a href="#receive_mail" class="btn_password fancybox"><?php echo am_lang('password_lost'); ?></a>
		<span class="required">- <?php echo $errors[$input_name]; ?></span>
	</div>
	<div class="submit_btn"><input type="submit" value="<?php echo am_lang('login'); ?>" class="btn_comm1" name="wp-submit" /></div>
</fieldset>
</form>