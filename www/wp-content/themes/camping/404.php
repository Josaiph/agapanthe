<?php get_header(); ?>
	<div id="content">
		
		<div class="content_box">
			<div class="cont_box">
				<?php 
					$title = am_lang('error_404');
					$subtitle = am_lang('page_not_found');
					$text = am_lang('error_content');
				?>
				<h1><?php echo $title; ?></h1>
				<h2><?php echo $subtitle; ?></h2>
				<div class="entry"><p><?php echo $text; ?></p></div>
			
			</div><!-- /cont_box -->
			
		</div><!-- /content_box -->

	</div><!-- /content -->

<?php get_footer(); ?>