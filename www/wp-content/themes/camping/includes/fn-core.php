<?php

/**
 * Get ID of the page, if this is current page
 */
function am_get_page_id() {
	global $wp_query;

	$page_obj = $wp_query->get_queried_object();

	if ( isset( $page_obj->ID ) && $page_obj->ID >= 0 )
		return $page_obj->ID;

	return -1;
}

/**
 * Get custom field of the current page
 * $type = string|int
 */
function am_get_custom_field($filedname, $id = NULL, $single=true)
{
	global $post;
	
	if($id==NULL)
		$id = get_the_ID();
	
	if($id==NULL)
		$id = am_get_page_id();

	$value = get_post_meta($id, $filedname, $single);
	
	if($single && !is_array($value))
		return stripslashes($value);
	else
		return $value;
}

/**
 * Get Limited String
 * $output = string
 * $max_char = int
 */
function am_get_limited_string($output, $max_char=100, $end='...')
{
    $output = str_replace(']]>', ']]&gt;', $output);
    $output = am_strip_word_html($output,array());
    $output = strip_shortcodes($output);

  	if ((strlen($output)>$max_char) && ($espacio = strpos($output, " ", $max_char )))
	{
        $output = substr($output, 0, $espacio).$end;
		return $output;
   }
   else
   {
      return $output;
   }
}

function am_close_html_tags($html)
{
    #put all opened tags into an array
    preg_match_all ( "#<([a-z]+)( .*)?(?!/)>#iU", $html, $result );
    $openedtags = $result[1];

    #put all closed tags into an array
    preg_match_all ( "#</([a-z]+)>#iU", $html, $result );
    $closedtags = $result[1];
    $len_opened = count ( $openedtags );
    # all tags are closed
    if( count ( $closedtags ) == $len_opened )
    {
        return $html;
    }
    $openedtags = array_reverse ( $openedtags );
    # close tags
    for( $i = 0; $i < $len_opened; $i++ )
    {
        if ( !in_array ( $openedtags[$i], $closedtags ) )
        {
            $html .= "</" . $openedtags[$i] . ">";
        }
        else
        {
            unset ( $closedtags[array_search ( $openedtags[$i], $closedtags)] );
        }
    }
    return $html;
}


function am_strip_word_html($text, $allowed_tags = '<b><i><sup><sub><em><strong><u><br><a><li><ul><ol><table><td><tr><th><strike>') 
{ 
	$text = am_close_html_tags($text);
    mb_regex_encoding('UTF-8'); 
    //replace MS special characters first 
    $search = array('/&lsquo;/u', '/&rsquo;/u', '/&ldquo;/u', '/&rdquo;/u', '/&mdash;/u'); 
    $replace = array('\'', '\'', '"', '"', '-'); 
    $text = preg_replace($search, $replace, $text); 
    //make sure _all_ html entities are converted to the plain ascii equivalents - it appears 
    //in some MS headers, some html entities are encoded and some aren't 
    $text = html_entity_decode($text, ENT_QUOTES, 'UTF-8'); 
    //try to strip out any C style comments first, since these, embedded in html comments, seem to 
    //prevent strip_tags from removing html comments (MS Word introduced combination) 
    if(mb_stripos($text, '/*') !== FALSE){ 
        $text = mb_eregi_replace('#/\*.*?\*/#s', '', $text, 'm'); 
    } 
    //introduce a space into any arithmetic expressions that could be caught by strip_tags so that they won't be 
    //'<1' becomes '< 1'(note: somewhat application specific) 
    $text = preg_replace(array('/<([0-9]+)/'), array('< $1'), $text); 
    $text = strip_tags($text, $allowed_tags); 
    //eliminate extraneous whitespace from start and end of line, or anywhere there are two or more spaces, convert it to one 
    $text = preg_replace(array('/^\s\s+/', '/\s\s+$/', '/\s\s+/u'), array('', '', ' '), $text); 
    //strip out inline css and simplify style tags 
    $search = array('#<(strong|b)[^>]*>(.*?)</(strong|b)>#isu', '#<(em|i)[^>]*>(.*?)</(em|i)>#isu', '#<u[^>]*>(.*?)</u>#isu'); 
    $replace = array('<b>$2</b>', '<i>$2</i>', '<u>$1</u>'); 
    $text = preg_replace($search, $replace, $text); 
    //on some of the ?newer MS Word exports, where you get conditionals of the form 'if gte mso 9', etc., it appears 
    //that whatever is in one of the html comments prevents strip_tags from eradicating the html comment that contains 
    //some MS Style Definitions - this last bit gets rid of any leftover comments */ 
    $num_matches = preg_match_all("/\<!--/u", $text, $matches); 
    if($num_matches){ 
          $text = preg_replace('/\<!--(.)*--\>/isu', '', $text); 
    } 
    return $text; 
} 

/**
 * Tests if any of a post's assigned categories are descendants of target categories
 *
 * @param mixed $cats The target categories. Integer ID or array of integer IDs
 * @param mixed $_post The post
 * @return bool True if at least 1 of the post's categories is a descendant of any of the target categories
 * @see get_term_by() You can get a category by name or slug, then pass ID to this function
 * @uses get_term_children() Gets descendants of target category
 * @uses in_category() Tests against descendant categories
 * @version 2.7
 */
function am_post_is_in_descendant_category( $cats, $_post = null )
{
	foreach ( (array) $cats as $cat ) {
		// get_term_children() accepts integer ID only
		$descendants = get_term_children( (int) $cat, 'category');
		if ( $descendants && in_category( $descendants, $_post ) )
			return true;
	}
	return false;
}

/**
 * Parse twitters to array
 * $username = string
 * limit = int
 */
function am_twitter_feed($username, $limit = 5 ) { 
	
	add_filter( 'wp_feed_cache_transient_lifetime', create_function( '$a', 'return 3600;' ) );

	include_once(ABSPATH . WPINC . '/feed.php');
	$rss = fetch_feed('https://api.twitter.com/1/statuses/user_timeline.rss?screen_name='.$username);

	if ( is_wp_error( $rss ) )
		return '';
			
	$maxitems = $rss->get_item_quantity($limit);
	$rss_items = $rss->get_items(0, $maxitems);
	
	$html = '';
	if ($maxitems > 0) :
		foreach ( $rss_items as $item ) :
			$html .= '<li><a href="'.$item->get_permalink().'">'.$item->get_title().'</a> <span class="date">'.$item->get_date().'</span></li>';
		endforeach;
	endif;
	
	return $html;
}

/**
 * Custom comments for single or page templates
 */
function am_comments($comment, $args, $depth) {
   $GLOBALS['comment'] = $comment; ?>
   <li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
     <div id="comment-<?php comment_ID(); ?>">
      <div class="comment-author vcard">
         <?php echo get_avatar($comment,'38'); ?>

         <?php printf(__('<cite class="fn">%s</cite> <span class="says">says:</span>','am'), get_comment_author_link()) ?>
      </div>
      <?php if ($comment->comment_approved == '0') : ?>
         <em><?php _e('Your comment is awaiting moderation.','am') ?></em>
         <br />
      <?php endif; ?>

      <div class="comment-meta commentmetadata"><a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>"><?php printf(__('%1$s at %2$s','am'), get_comment_date(),  get_comment_time()) ?></a><?php edit_comment_link(__('(Edit)','am'),'  ','') ?></div>

      <div class="entry"><?php comment_text() ?></div>

      <div class="reply">
         <?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
      </div>
     </div>
<?php
}

/**
 * Browser detection body_class() output
 */
function am_browser_body_class($classes) {
	global $is_lynx, $is_gecko, $is_IE, $is_opera, $is_NS4, $is_safari, $is_chrome, $is_iphone;

	if($is_lynx) $classes[] = 'lynx';
	elseif($is_gecko) $classes[] = 'gecko';
	elseif($is_opera) $classes[] = 'opera';
	elseif($is_NS4) $classes[] = 'ns4';
	elseif($is_safari) $classes[] = 'safari';
	elseif($is_chrome) $classes[] = 'chrome';
	elseif($is_IE) $classes[] = 'ie';
	else $classes[] = 'unknown';

	if($is_iphone) $classes[] = 'iphone';
	return $classes;
}

/**
 * Show analytics code in footer
 */
function am_analytics(){
	
	$output = ot_get_option('general_google_analytics_code');

	if ( !empty($output) ) 
		echo stripslashes($output) . "\n";
}

/**
 * Filter for get_the_excerpt
 */
 
function am_get_the_excerpt($content){
	return str_replace(' [...]','',$content);
}

/**
 * Get the sidebar ID
 */
 
function am_get_sidebar_id($sidebar_id = 'sidebar-default'){
	global $post;
	if(isset($post->ID))
		if(is_active_sidebar('sidebar-'.$post->ID))
			$sidebar_id = 'sidebar-'.$post->ID;
	return $sidebar_id;
}

/**
 * Resize the image
 */
 
function am_image_resize($img_url,$width, $height) {
	global $am_option, $_SERVER;
	
	$image['url'] = $img_url;
	$image_path = explode($_SERVER['SERVER_NAME'], $image['url']);
	$image_path = $_SERVER['DOCUMENT_ROOT'] . $image_path[1];
	$image_info = @getimagesize($image_path);

	// If we cannot get the image locally, try for an external URL
	if (!$image_info)
		$image_info = @getimagesize($image['url']);

	$image['width'] = $image_info[0];
	$image['height'] = $image_info[1];
	if($img_url != "" && ($image['width'] > $width || $image['height'] > $height || !isset($image['width']))){
		$img_url = $am_option['url']['extensions_url']."/thumb.php?src=$img_url&amp;w=$width&amp;h=$height&amp;zc=1&amp;q=100";
	}
	
	return $img_url;
}

/**
 * Improved Wordpress page menu function
 */

function am_wp_page_menu( $args = array() ) {
	$defaults = array('sort_column' => 'menu_order, post_title', 'menu_id' => 'menu', 'menu_class' => 'menu', 'echo' => true, 'link_before' => '', 'link_after' => '', 'show_home' => 1);
	$args = wp_parse_args( $args, $defaults );
	$args = apply_filters( 'wp_page_menu_args', $args );

	$menu = '';

	$list_args = $args;

	// Show Home in the menu
	if ( ! empty($args['show_home']) ) {
		if ( true === $args['show_home'] || '1' === $args['show_home'] || 1 === $args['show_home'] )
			$text = __('Home','am');
		else
			$text = $args['show_home'];
		$class = '';
		if ( is_front_page() && !is_paged() )
			$class = 'class="current_page_item"';
		$menu .= '<li ' . $class . '><a href="' . home_url( '/' ) . '" title="' . esc_attr($text) . '">' . $args['link_before'] . $text . $args['link_after'] . '</a></li>';
		// If the front page is a page, add it to the exclude list
		if (get_option('show_on_front') == 'page') {
			if ( !empty( $list_args['exclude'] ) ) {
				$list_args['exclude'] .= ',';
			} else {
				$list_args['exclude'] = '';
			}
			$list_args['exclude'] .= get_option('page_on_front');
		}
	}

	$list_args['echo'] = false;
	$list_args['title_li'] = '';
	$menu .= str_replace( array( "\r", "\n", "\t" ), '', wp_list_pages($list_args) );

	if ( $menu )
		$menu = '<ul id="' . esc_attr($args['menu_id']) . '" class="' . esc_attr($args['menu_class']) . '">' . $menu . '</ul>';

	$menu = apply_filters( 'wp_page_menu', $menu, $args );
	if ( $args['echo'] )
		echo $menu;
	else
		return $menu;
}


function am_has_title($title){
	global $post;
	if($title == ''){
		return get_the_time(get_option( 'date_format' ));
	}else{
		return $title;
	}
}

/**
 * Disable Automatic Formatting on Posts
 *
 * @param string $content
 * @return string
 */
function am_formatter($content) {

	$new_content = '';
	
	# Matches the contents and the open and closing tags
	$pattern_full = '{(\[raw\].*?\[/raw\])}is';
	
	# Matches just the contents
	$pattern_contents = '{\[raw\](.*?)\[/raw\]}is';
	
	# Divide content into pieces
	$pieces = preg_split($pattern_full, $content, -1, PREG_SPLIT_DELIM_CAPTURE);

	# Loop over pieces
	foreach ($pieces as $piece) {
	
		# Look for presence of the shortcode
		if (preg_match($pattern_contents, $piece, $matches)) {
		
			# Append to content (no formatting)
			$new_content .= $matches[1];
		
		} else {
		
			# Format and append to content
			$new_content .= wptexturize(wpautop($piece));
		
		}
	}

	return $new_content;
}

function am_texturize_shortcode_before($content) {
	$content = preg_replace('/\]\[/im', "]\n[", $content);
	return $content;
}

function am_remove_wpautop( $content ) { 
	$content = do_shortcode( shortcode_unautop( $content ) ); 
	$content = preg_replace('#^<\/p>|^<br \/>|<p>$#', '', $content);
	return $content;
}


if ( !function_exists( 'ot_get_option' ) ) {
	function ot_get_option($option_id, $default = NULL ){
		return NULL;
	}
}

// unregister all default WP Widgets
function am_unregister_default_wp_widgets() {
    unregister_widget('WP_Widget_Pages');
    //unregister_widget('WP_Widget_Calendar');
    //unregister_widget('WP_Widget_Archives');
    //unregister_widget('WP_Widget_Links');
    //unregister_widget('WP_Widget_Meta');
    //unregister_widget('WP_Widget_Search');
    unregister_widget('WP_Widget_Text');
    //unregister_widget('WP_Widget_Categories');
    //unregister_widget('WP_Widget_Recent_Posts');
    //unregister_widget('WP_Widget_Recent_Comments');
    //unregister_widget('WP_Widget_RSS');
    //unregister_widget('WP_Widget_Tag_Cloud');
    //unregister_widget('WP_Nav_Menu_Widget');
}

/**
 * Add JS scripts
 */
function am_add_javascript( ) {

	if (is_singular() && get_option('thread_comments'))
		wp_enqueue_script('comment-reply');
		
	wp_enqueue_script('jquery');
	if( !is_admin() ) {
		wp_enqueue_script('jquery-ui-core');
		wp_enqueue_script('jquery-ui-widget');
		wp_enqueue_script('jquery-ui-position');
		wp_enqueue_script('am_fancybox', get_template_directory_uri().'/includes/js/fancybox/jquery.fancybox-1.3.4.pack.js', array( 'jquery' ),'1.0',true );
		wp_enqueue_script('am_cycle', get_template_directory_uri().'/includes/js/jquery.cycle.all.min.js', array( 'jquery' ),'1.0',true );
		wp_enqueue_script('am_selectmenu', get_template_directory_uri().'/includes/js/jquery.ui.selectmenu.js', array( 'jquery' ),'1.0',true );
		wp_enqueue_script('am_superfish', get_template_directory_uri().'/includes/js/superfish.js', array( 'jquery' ),'1.0',true );
		wp_enqueue_script('am_uniform', get_template_directory_uri().'/includes/js/jquery.uniform.min.js', array( 'jquery' ),'1.0',true );
		wp_enqueue_script('am_form', get_template_directory_uri().'/includes/js/jquery.form.js', array( 'jquery' ),'1.0',true );
		wp_enqueue_script('am_general', get_template_directory_uri().'/includes/js/general.js', array( 'jquery' ),'1.0',true );
	}
}

/**
 * Add CSS scripts
 */
function am_add_css( ) {
	wp_register_style('am_fancybox_css', get_template_directory_uri().'/includes/js/fancybox/jquery.fancybox-1.3.4.css');
    wp_enqueue_style( 'am_fancybox_css');
}

/**
 * Add specific IE styling/hacks to HEAD
 */
function am_IE_head() {/*
?>
	
	<!--[if IE 7]>
		<link rel="stylesheet" type="text/css" media="all" href="<?php echo get_template_directory_uri(); ?>/css/ie7.css" />
	<![endif]-->	

<?php*/
}

function am_ot_custom_style() {
  
  /* Register Style */
  wp_register_style( 'ot-custom-style', get_template_directory_uri() . '/css/ot-custom-style.css' );
 
  /* Enqueue Style */
  wp_enqueue_style( 'ot-custom-style' );
  
}

/**
 * Register widgetized areas
 */
function am_the_widgets_init() {
	
    /*if ( !function_exists('register_sidebars') )
        return;
    
    $before_widget = '<div id="%1$s" class="widget %2$s"><div class="widget_inner">';
    $after_widget = '</div></div>';
    $before_title = '<h3 class="widgettitle">';
    $after_title = '</h3>';

    register_sidebar(array('name' => __('Default','am'),'id' => 'sidebar-default','before_widget' => $before_widget,'after_widget' => $after_widget,'before_title' => $before_title,'after_title' => $after_title));
    
    if(ot_get_option('general_sidebars_page')=='yes') :
		$pages = get_pages('sort_column=post_title&sort_order=asc&hierarchical=0');
		if(is_array($pages) && count($pages)>0 ) :
			foreach ($pages as $page) :
	   			register_sidebar(array('name' => $page->post_title,'id' => 'sidebar-'.$page->ID,'before_widget' => $before_widget,'after_widget' => $after_widget,'before_title' => $before_title,'after_title' => $after_title));
			endforeach;
		endif;
	endif;*/
}

/**
 * Remove theme Option admin menu
 */

function am_remove_admin_menu_pages () {
	global $menu;
	$restricted = array(__('OptionTree'));
	end ($menu);
	while (prev($menu)){
		$value = explode(' ',$menu[key($menu)][0]);
		if(in_array($value[0] != NULL?$value[0]:"" , $restricted)){unset($menu[key($menu)]);}
	}
}
?>