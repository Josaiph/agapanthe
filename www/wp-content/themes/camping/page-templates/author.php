<?php 
/*
Template Name: Author
*/

global $message;

$url_contact_host = '';

if ( !is_user_logged_in() ){
	$url_contact_host = get_permalink(ot_get_option('general_login_page')); 
}

if(!isset($_GET['host']) || $_GET['host']<=0 ){
	wp_redirect('Location: '.home_url().'/');
	exit;
}else{
	$curauth = get_userdata($_GET['host']);
	if(!isset($curauth->ID)){
		wp_redirect('Location: '.home_url().'/');
		exit;
	}
}

get_header(); ?>
	
	
	<div id="content">
		<?php if(!empty($message)) : ?><div class="reply_box reply_box_color1"><?php echo $message; ?></div><?php endif; ?>
		<div class="main_content">
			<div class="person_intro">
				<?php 
					$img_url = am_the_author_image($curauth->ID, null, true);
					if(!empty($img_url))
						$img_url = '<img src="'.am_image_resize($img_url,150, 150).'" alt="'.$curauth->display_name.'" />';
					else
						$img_url = '<img src="'.get_template_directory_uri().'/images/pic_user_default_big.png" alr="" />';
					
				?>
				<div class="person_img"><?php echo $img_url; ?></div>
				<p><strong><?php echo am_get_short_author_name($curauth); ?></strong> <?php if(!empty($curauth->user_city)) echo $curauth->user_city.', '; echo $curauth->user_country; ?></p>	
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