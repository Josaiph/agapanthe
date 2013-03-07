<?php
	global $post, $current_user, $is_contact_error, $curauth, $post_author, $errors;
	get_currentuserinfo();
	
	$from_email = '';
	$from_name = '';
	$to_id = '';
	
	$title = am_lang('write_to_host');
	
	if(isset($current_user->ID) && !empty($current_user->ID)){
		$from_email = $current_user->user_email;
		$from_name = am_get_short_author_name($current_user);
		$to_id = $current_user->ID;
	}
	
	if(is_single()){
		$author = get_the_author();
		$post_author = get_userdata(get_the_author_meta( 'ID' ));
		$to_email = get_the_author_meta('user_email');
		$to_name = am_get_short_author_name($post_author);
	}
	if(is_page_template('page-templates/author.php')){
		$to_email = $curauth->user_email;
		$to_name = am_get_short_author_name($curauth);
	}
	if(is_page_template('page-templates/how_it_works.php')){
		$to_email = ot_get_option('general_admin_email');
		$to_name = get_bloginfo('name');
		$title = am_lang('write_us');
	}
?>
<div class="pop_box" id="write_to_host">
	<h1><?php echo $title; ?></h1>
	<form action="#" method="post">
	<fieldset<?php if($is_contact_error) echo ' class="error"'; ?>>
		<?php if ( !is_user_logged_in() ): ?>
		<?php
			$error_class = '';
			$post_value = am_lang('your_email');
			$post_value_default = am_lang('your_email');
			$input_name = 'j_from_email';
			if(isset($errors[$input_name])){
				$error_class = ' class="error"';
			}
			if(isset($_POST[$input_name])){
				$post_value = $_POST[$input_name];
			}
		?>
		<input type="text" value="<?php echo $post_value; ?>" name="<?php echo $input_name; ?>"<?php echo $error_class; ?> data-default="<?php echo $post_value_default; ?>">
		<?php endif; ?>
		<?php
			$error_class = '';
			$post_value = am_lang('your_message');
			$post_value_default = am_lang('your_message');
			$input_name = 'j_message';
			if(isset($errors[$input_name])){
				$error_class = ' class="error"';
			}
			if(isset($_POST[$input_name])){
				$post_value = $_POST[$input_name];
			}
		?>
		<textarea name="<?php echo $input_name; ?>" data-default="<?php echo $post_value_default; ?>"><?php echo $post_value; ?></textarea>
		<div class="btns_row">
			<input type="submit" value="<?php echo am_lang('send'); ?>" class="btn_comm1" name="j_contact_host">
			<a href="#" class="btn_cancel close_pop"><?php echo am_lang('cancel'); ?></a>
			<?php if ( is_user_logged_in() ): ?>
			<input type="hidden" name="j_from_email" value="<?php echo $from_email; ?>" />
			<?php endif; ?>
			<input type="hidden" name="j_from_name" value="<?php echo $from_name; ?>" />
			<input type="hidden" name="j_to_email" value="<?php echo $to_email; ?>" />
			<input type="hidden" name="j_to_name" value="<?php echo $to_name; ?>" />
			<input type="hidden" name="j_to_id" value="<?php echo $to_id; ?>" />
		</div>
	</fieldset>
	</form>
</div><!-- /pop_box -->