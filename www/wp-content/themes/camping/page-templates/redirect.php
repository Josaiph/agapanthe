<?php
/*
Template Name: Redirect
*/

/* 

USAGE INSTRUCTIONS:

1. Create a new page in WordPress
2. Add a title to the page (e.g. Amixstudio)
3. Add an URL to the content of the page (e.g. http://www.amixstudio.com OR amixstudio.com OR www.amixstudio.com)
4. Publish!

*/

if (have_posts()) : 

	the_post();
	
	$url = get_the_excerpt(); 
	
	if (!preg_match('/^http:\/\//', $url)) 
		$url = 'http://' . $url; 
		
	wp_redirect($url);
	
	exit;
endif;
?>