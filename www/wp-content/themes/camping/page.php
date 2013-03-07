<?php get_header(); ?>
	<div id="content">
		
		<div class="content_box">
			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
			<div class="cont_box">
				<?php 
					$title = am_get_custom_field('am_title', get_the_ID(), true);
					$subtitle = am_get_custom_field('am_subtitle', get_the_ID(), true);
				?>
				<h1><?php if(!empty($title)) : echo $title; else : the_title(); endif; ?></h1>
				<?php if(!empty($subtitle)): ?><h2><?php echo $subtitle; ?></h2><?php endif; ?>
				<div class="entry">
					<?php the_content(am_lang('read_more')); ?>
					<div class="clear"></div>
					<?php wp_link_pages( array( 'before' => '<div class="page-link"><span>' . am_lang('pages') . '</span>', 'after' => '</div>' ) ); ?>
					<?php edit_post_link(am_lang('edit'), '<br /><p>', '</p>'); ?>
				</div><!-- /entry -->
			</div><!-- /cont_box -->
			<?php endwhile; endif; ?>
			
		</div><!-- /content_box -->

	</div><!-- /content -->

<?php get_footer(); ?>