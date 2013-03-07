<?php 

global $message;

$url_contact_host = '';

if ( !is_user_logged_in() ){
	$url_contact_host = get_permalink(ot_get_option('general_login_page')); 
}

$curauth = (get_query_var('author_name')) ? get_user_by('slug', get_query_var('author_name')) : get_userdata(get_query_var('author'));

get_header(); ?>
	
	
	<div id="content">
		<?php if(!empty($message)) : ?><div class="reply_box reply_box_color1"><?php echo $message; ?></div><?php endif; ?>
		<div class="main_content">
			<div class="person_intro">
				<?php 
					$img_url = am_the_author_image($curauth->ID, null, true);
					if(!empty($img_url))
						$img_url = '<img src="'.am_image_resize($img_url,150, 150).'" alt="'.$curauth->display_name.'" />';
				?>
				<div class="person_img"><?php echo $img_url; ?></div>
				<p><strong><?php echo $curauth->display_name; ?></strong> <?php if(!empty($curauth->user_city)) echo $curauth->user_city.', '; echo $curauth->user_country; ?></p>	
			</div><!-- /person_intro -->
			
			<div class="main_box">
				<div class="main_col1">
					<div class="block_box">
						<div class="member_since"><?php echo am_lang('member_since'); ?> <?php echo date('F d, Y',strtotime($curauth->user_registered)); ?></div>
						<?php echo wpautop($curauth->description); ?>
					</div>
					<?php if(!empty($curauth->user_languages)) : ?>
					<div class="line line2"></div>
					
					<div class="block_box">
						<h3><?php echo am_lang('languages'); ?></h3>
						<p><?php echo $curauth->user_languages; ?></p>
					</div>
					<?php endif; ?>
				</div><!-- /main_col1 -->
				
				<div class="main_col2">
					<?php if(empty($url_contact_host)) : ?>
					<a href="#write_to_host" class="btn_comm1 btn_contact fancybox"><?php echo am_lang('contact_me'); ?></a>
					<?php else : ?>
					<a href="<?php echo $url_contact_host; ?>" class="btn_comm1 btn_contact"><?php echo am_lang('contact_me'); ?></a>
					<?php endif; ?>
				</div><!-- /main_col2 -->
			</div><!-- /main_box -->
		</div><!-- /main_content -->
		
		<?php get_sidebar(); ?>
	</div><!-- /content2 -->

<?php get_footer(); ?>