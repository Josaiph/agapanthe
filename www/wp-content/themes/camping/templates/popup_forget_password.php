<?php global $is_pass_error; ?>
<div class="pop_box" id="receive_mail">
	<h1><?php echo am_lang('you_will_receive_by_email'); ?></h1>
	<form action="#" method="post">
	<fieldset<?php if($is_pass_error) echo ' class="error"'; ?>>
		<input type="text" value="<?php echo am_lang('your_email'); ?>" name="j_email">
		<div class="btns_row">
			<input type="submit" value="<?php echo am_lang('send'); ?>" data-default="<?php echo am_lang('send'); ?>" class="btn_comm1" name="j_pass_resend">
			<a href="#" class="btn_cancel close_pop"><?php echo am_lang('cancel'); ?></a>
		</div>
	</fieldset>
	</form>
</div><!-- /pop_box -->