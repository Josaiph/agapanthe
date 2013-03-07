<?php
	global $_POST, $errors;
?>
<form action="#" class="member_form" method="post" id="form_register">
<fieldset>
	<h1><?php echo am_lang('new_member'); ?></h1>
	<?php
		$error_class = '';
		$post_value = am_lang('first_name');
		$post_value_default = am_lang('first_name');
		$input_name = 'first_name';
		if(isset($errors[$input_name])){
			$error_class = ' error';
		}
		if(isset($_POST[$input_name])){
			$post_value = $_POST[$input_name];
		}
	?>
	<div class="form_block<?php echo $error_class; ?>">
		<input type="text" name="<?php echo $input_name; ?>" id="<?php echo $input_name; ?>" value="<?php echo $post_value; ?>" class="input_txt1" data-default="<?php echo $post_value_default; ?>" />
		<span class="required">- <?php echo $errors[$input_name]; ?></span>
	</div>
	<?php
		$error_class = '';
		$post_value = am_lang('last_name');
		$post_value_default = am_lang('last_name');
		$input_name = 'last_name';
		if(isset($errors[$input_name])){
			$error_class = ' error';
		}
		if(isset($_POST[$input_name])){
			$post_value = $_POST[$input_name];
		}
	?>
	<div class="form_block<?php echo $error_class; ?>">
		<input type="text" name="<?php echo $input_name; ?>" id="<?php echo $input_name; ?>" value="<?php echo $post_value; ?>" class="input_txt1" data-default="<?php echo $post_value_default; ?>" />
		<span class="required">- <?php echo $errors[$input_name]; ?></span>
	</div>
	<?php
		$error_class = '';
		$post_value = am_lang('register_phone_optional');
		$post_value_default = am_lang('register_phone_optional');
		$input_name = 'user_phone';
		if(isset($errors[$input_name])){
			$error_class = ' error';
		}
		if(isset($_POST[$input_name])){
			$post_value = $_POST[$input_name];
		}
	?>
	<div class="form_block<?php echo $error_class; ?>">
		<input type="text" name="<?php echo $input_name; ?>" id="<?php echo $input_name; ?>" value="<?php echo $post_value; ?>" class="input_txt1" data-default="<?php echo $post_value_default; ?>" />
	</div>
	<?php
		$error_class = '';
		$post_value = am_lang('regsiter_email_address');
		$post_value_default = am_lang('regsiter_email_address');
		$input_name = 'user_email';
		if(isset($errors[$input_name])){
			$error_class = ' error';
		}
		if(isset($_POST[$input_name])){
			$post_value = $_POST[$input_name];
		}
	?>
	<div class="form_block<?php echo $error_class; ?>">
		<input type="text" name="<?php echo $input_name; ?>" id="<?php echo $input_name; ?>" value="<?php echo $post_value; ?>" class="input_txt2" data-default="<?php echo $post_value_default; ?>" />
		<div class="form_note"><?php echo am_lang('email_to_confirm'); ?></div>
		<span class="required">- <?php echo $errors[$input_name]; ?></span>
	</div>	
	<?php
		$error_class = '';
		$post_value = am_lang('password');
		$post_value_default = am_lang('password');
		$input_name = 'user_pass';
		if(isset($errors[$input_name])){
			$error_class = ' error';
		}
		if(isset($_POST[$input_name])){
			$post_value = $_POST[$input_name];
		}
	?>
	<div class="form_block<?php echo $error_class; ?>">
		<div id="password_holder1">
			<input type="text" name="<?php echo $input_name; ?>" id="input_password" value="<?php echo $post_value; ?>" class="input_txt3" data-default="<?php echo $post_value_default; ?>" />
			<input type="password" name="<?php echo $input_name; ?>" id="input_password_real" value="<?php echo $post_value; ?>" class="input_txt3" style="display:none" data-default="<?php echo $post_value_default; ?>" />
		</div>
		<div class="checkbox">
			<input type="checkbox" id="show_pass" checked="checked" />
			<label for="show_pass"><?php echo am_lang('show_password'); ?></label>
		</div>
		<span class="required">- <?php echo $errors[$input_name]; ?></span>
	</div>
	<div class="form_para"><?php echo am_lang('register_description'); ?> <a href="#term_of_use" class="fancybox"><?php echo am_lang('terms_of_use'); ?></a></div>
	<div class="submit_btn"><input type="submit" value="<?php echo am_lang('join_us'); ?>" name="submit" class="btn_comm1"/></div>
</fieldset>
</form>	