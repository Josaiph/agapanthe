<!DOCTYPE html>
<!--[if IE 7]> <html class="ie7 oldie" <?php language_attributes(); ?> ><![endif]-->
<!--[if IE 8]> <html class="ie8 oldie" <?php language_attributes(); ?> ><![endif]-->
<!--[if IE 9]> <html class="ie9" <?php language_attributes(); ?> ><![endif]-->
<!--[if gt IE 9]><!--> <html <?php language_attributes(); ?>> <!--<![endif]-->
<head>
	<meta charset="<?php bloginfo('charset'); ?>">

	<title><?php
	if (is_category()) {
		echo am_lang('category_head'); wp_title(''); echo ' - ';
	
	} elseif (function_exists('is_tag') && is_tag()) {
		single_tag_title(am_lang('tag_archive_head')); echo '&quot; - ';
	
	} elseif (is_archive()) {
		wp_title(''); echo am_lang('archive_head');
	
	} elseif (is_page() && !(is_home()) && !(is_front_page())) {
		echo wp_title(''); echo ' - ';
	
	} elseif (is_search()) {
		echo am_lang('search_head').esc_html($s).'&quot; - ';
	
	} elseif (!(is_404()) && (is_single()) || (is_page()) && !(is_home()) && !(is_front_page())) {
		wp_title(''); echo ' - ';
	
	} elseif (is_404()) {
		echo am_lang('not_found_head');
	
	} bloginfo('name');
	?></title>
	
	<meta name="viewport" content="width=1000px;">
	<link rel="icon" type="image/icon"  href="<?php echo get_template_directory_uri(); ?>/images/faveicon.ico" />
	<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="all" />
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
	<!--[if lt IE 9]>
		<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&key=<?php echo ot_get_option('general_google_map_api'); ?>"></script>
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
	<header>
		<a id="logo" href="<?php echo home_url(); ?>/"><span><?php echo am_lang('slogan'); ?></span></a>
		<?php if(is_home()) : ?>
		<a href="<?php echo get_permalink(ot_get_option('general_desc_gamping_page')); ?>" class="btn_comm2 btn_discover"><?php echo am_lang('discover_the_gamping'); ?></a>
		<?php else: ?>
		<?php
            if(isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], home_url()) ){
                $back_title = am_lang('retour');
                $back_link = $_SERVER['HTTP_REFERER'];
            } else {
                $back_title = am_lang('home');
                $back_link = home_url();
            }
		?>
		<a href="<?php echo $back_link; ?>" class="btn_retour"><span><?php echo $back_title; ?></span></a>
		<?php endif; ?>
		<?php get_template_part('templates/usermenu');  ?>
	</header><!-- /header -->