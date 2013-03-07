<?php global $is_pass_error, $is_contact_error; ?>
	<footer>
		<div class="footer_inside">
			<?php get_template_part('templates/langs');  ?>
			<a href="#legal_mentions" class="link_legal fancybox"><?php echo am_lang('legal_mentions'); ?></a>
		</div>
	</footer><!-- /footer -->
	
	
	<div class="pop_boxes">
		<?php get_template_part('templates/popup_legal_mentions');  ?>
		<?php get_template_part('templates/popup_term_of_use');  ?>
		<?php if(is_page_template('page-templates/login.php')) get_template_part('templates/popup_forget_password');  ?>
		<?php if(is_single() || is_page_template('page-templates/how_it_works.php') || is_page_template('page-templates/author.php')) get_template_part('templates/popup_contact_host');  ?>
	</div><!-- /pop_boxes -->
	<?php wp_footer(); ?>
	<?php
	if($is_pass_error){
		?><script type="text/javascript">
			(function($) {
				$(document).ready(function() { 
					$(".member_form .fancybox").fancybox({
			'titlePosition'		: 'inside',
			'transitionIn'		: 'fade',
			'transitionOut'		: 'fade',
			'overlayOpacity'	: 0.7,
			'overlayColor'		: '#000',
			'padding'			: 0,
			'modal'				: false,
			'showCloseButton'	: false,
			'enableEscapeButton': true
		}).trigger('click');
			        
			    });
			})(jQuery);
		</script>
		<?php
	}
	if($is_contact_error){
		?><script type="text/javascript">
			(function($) {
				$(document).ready(function() { 
					$(".btn_contact.fancybox").fancybox({
			'titlePosition'		: 'inside',
			'transitionIn'		: 'fade',
			'transitionOut'		: 'fade',
			'overlayOpacity'	: 0.7,
			'overlayColor'		: '#000',
			'padding'			: 0,
			'modal'				: false,
			'showCloseButton'	: false,
			'enableEscapeButton': true
		}).trigger('click');
			        
			    });
			})(jQuery);
		</script>
		<?php
	}
	?>
</body>
</html>