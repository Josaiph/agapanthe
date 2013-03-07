<?php
/*
Template Name: How It Works
*/

global $is_contact_error, $message;

get_header(); ?>
	
	<div id="content">
		<?php if(!empty($message)) : ?><div class="reply_box<?php if($is_contact_error) echo ' reply_box_color2'; else echo ' reply_box_color1'; ?>"><?php echo $message; ?></div><?php endif; ?>
		
		<div class="content_box">
			<div class="cont_box">
				<?php echo am_lang('how_works_header'); ?>
				<div class="services_box">
					<div class="ser_col1">
						<?php echo am_lang('how_works_block_1'); ?>
					</div>
					
					<div class="ser_col2">
						<?php echo am_lang('how_works_block_2'); ?>
					</div>
				</div><!-- /services_box -->
			</div><!-- /cont_box -->
			
			<div class="title_row">
				<span><?php echo am_lang('join_first_gamping_comm'); ?></span>
				<a href="<?php echo get_permalink(ot_get_option('general_list_garden_page')); ?>" class="btn_comm1 btn_expandable"><?php echo am_lang('list_your_garden'); ?></a>
			</div>
			
			<div class="cont_box">
				<h3><?php echo am_lang('how_works_reasons_title'); ?></h3>
				<div class="reason_box">
				<ul>
					<?php echo am_lang('how_works_reasons'); ?>
				</ul>
				</div>
			</div><!-- /cont_box -->
			
			<div class="title_row">
				<span><?php echo am_lang('how_works_reasons_last'); ?></span>
				<a href="<?php echo get_permalink(ot_get_option('general_list_garden_page')); ?>" class="btn_comm1 btn_expandable"><?php echo am_lang('offer_my_land'); ?></a>
			</div>
			
			<div class="btn_row">
				<a href="#write_to_host" class="btn_comm2 btn_expandable btn_contact fancybox"><?php echo am_lang('questions'); ?></a>
			</div>
			
		</div><!-- /content_box -->

	</div><!-- /content -->

<?php get_footer(); ?>