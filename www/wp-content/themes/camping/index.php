<?php

if(isset($_SESSION['anon-ad-fail'])){
    unset($_SESSION['anon-ad-fail']);
    $message_class = ' reply_box_color2';
    $message = am_lang('you_already_has_ad');
}
?>

<?php get_header(); ?>
	
<div id="content">
    <?php if(!empty($message)) : ?><div class="reply_box<?php echo $message_class; ?>"><?php echo $message; ?></div><?php endif; ?>

	<div class="content_box">
		<div class="cont_box">
			<h1><?php echo am_lang('find_private_property_to_camp'); ?></h1>
			<div class="select_row">
				<form action="<?php echo home_url(); ?>/" method="get">
				<?php
					$selected_country = NULL;
					$selected_derpartment = NULL;
					if(isset($_GET['country']) && !empty($_GET['country'])){
						$selected_country = $_GET['country'];
						if(isset($_GET['derpartment']) && !empty($_GET['derpartment'])){
							$selected_derpartment = $_GET['derpartment'];
						}
					}
					$select = am_get_countries_list('country','simu_select',$selected_country,true);
					$select = preg_replace("#<select([^>]*)>#", "<select$1 onchange='return this.form.submit()'>", $select);
					echo $select;
				?>
				</form>
				<form action="<?php echo home_url(); ?>/" method="get">
				<?php
					if(!empty($selected_country)){
						$select = am_get_states_list('derpartment','simu_select',$selected_country,$selected_derpartment,true);
						$select = preg_replace("#<select([^>]*)>#", "<select$1 onchange='return this.form.submit()'>", $select);
						echo $select;
					}
				?>
				<input name="country" type="hidden" value="<?php echo $selected_country; ?>" />
				</form>
			</div>
		</div><!-- /cont_box -->
		
		<div class="gap_line"> </div>
		
		<div class="cont_box">
			<?php
				$args = array(
					'post_type'=>'ad',
					'paged'=>$paged
					
				);
				if(!empty($selected_country)){
					$args['meta_query'] = array(
						array(
							'key' => 'am_country',
							'value' => $selected_country,
							'compare' => '='
						)
					);
				}
				if(!empty($selected_derpartment)){
					$args['meta_query']['relation'] = 'AND';
					$args['meta_query'][] = array(
							'key' => 'am_state',
							'value' => $selected_derpartment,
							'compare' => '='
						);
				}
				query_posts($args);
			?>
			<?php if (have_posts()) : ?>
			<?php 
				$next_page = get_next_posts_link('<span>'.am_lang('previous').'</span>'); 
				$prev_pages = get_previous_posts_link('<span>'.am_lang('next').'</span>');
				if(!empty($next_page) || !empty($prev_pages)) :
			?>
			<div class="pager_box">
				<span class="btn_prev"><?php echo $prev_pages; ?></span>
				<div class="pagers">
					<?php echo am_pagenavi(); ?>
				</div>
				<span class="btn_next"><?php echo $next_page; ?></span>
			</div><!-- /pager_box -->
			<?php endif; ?>
			<div class="camping_list_box">
				<ul>
					<?php while (have_posts()) : the_post(); ?>
					<?php
						$images = am_get_custom_field('am_images', get_the_ID(), false);
						$thumbnail_id = null;
						if(isset($images[0]))
							$thumbnail_id = $images[0];
						$thumbnail = wp_get_attachment_image_src($thumbnail_id,'full');
						if(isset($thumbnail[0])) :
							$thumbnail = $thumbnail[0]; 
							$thumbnail = am_image_resize($thumbnail, 200, 200);
						else:
							$thumbnail = get_bloginfo('template_directory').'/images/camp_img.jpg';
						endif;
						$currency = am_get_custom_field('am_currency', get_the_ID(), true);
						$adult_price = am_get_custom_field('am_adult_price', get_the_ID(), true);
						$am_country = am_get_custom_field('am_country', get_the_ID(), true);
						$am_state = am_get_custom_field('am_state', get_the_ID(), true);
						$am_city = am_get_custom_field('am_city', get_the_ID(), true);
						
						$alt = array($am_city,$am_state,$am_country);
						$alt = implode(', ', $alt);
					?>
					<li><a href="<?php the_permalink(); ?>" title="<?php echo $alt; ?>"><img src="<?php echo $thumbnail; ?>" alt="<?php echo $alt; ?>" width="200" height="200" /><b><?php if(!empty($adult_price)) : ?><span></span><strong><?php echo $adult_price; ?> <?php echo $currency; ?></strong><?php endif; ?></b></a></li>
					<?php endwhile; ?>	
				</ul>
			</div><!-- /camping_list_box -->
			<?php 
				$next_page = get_next_posts_link('<span>'.am_lang('previous').'</span>'); 
				$prev_pages = get_previous_posts_link('<span>'.am_lang('next').'</span>');
				if(!empty($next_page) || !empty($prev_pages)) :
			?>
			<div class="pager_box">
				<span class="btn_prev"><?php echo $prev_pages; ?></span>
				<div class="pagers">
					<?php echo am_pagenavi(); ?>
				</div>
				<span class="btn_next"><?php echo $next_page; ?></span>
			</div><!-- /pager_box -->
			<?php endif; ?>
		
			<?php else : ?>
				<div class="nopost">
		        	<p><?php echo am_lang('sorry_no_posts'); ?></p>
		         </div><!-- /nopost -->
			<?php endif; wp_reset_query(); ?>
		</div><!-- /cont_box -->
	</div><!-- /content_box -->
</div><!-- /content -->

<?php get_footer(); ?>