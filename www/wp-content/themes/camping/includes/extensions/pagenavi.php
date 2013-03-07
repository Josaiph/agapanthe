<?php


/**
 *
 */
function am_pagenavi($before = '', $after = '', $custom_query = array() ) {
	global $wpdb, $wp_query, $paged;
	
	$out = '';
	if (!is_single()) {
		
		$pagenavi_options = array();
		$pagenavi_options['pages_text'] = '';
		$pagenavi_options['current_text'] = '%PAGE_NUMBER%';
		$pagenavi_options['page_text'] = '%PAGE_NUMBER%';
		$pagenavi_options['first_text'] = __('&laquo; First', 'am' );
		$pagenavi_options['last_text'] = __('Last &raquo;', 'am' );
		$pagenavi_options['next_text'] = __('Next', 'am' );
		$pagenavi_options['prev_text'] = __('Prev', 'am' );
		$pagenavi_options['dotright_text'] = __('...', 'am' );
		$pagenavi_options['dotleft_text'] = __('...', 'am' );
		$pagenavi_options['style'] = 1;
		$pagenavi_options['num_pages'] = 5;
		$pagenavi_options['always_show'] = 0;
		$pagenavi_options['num_larger_page_numbers'] = 3;
		$pagenavi_options['larger_page_numbers_multiple'] = 10;
		
		$request = $wp_query->request;
		$posts_per_page = intval(get_query_var('posts_per_page'));
		$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
		
		if( !empty( $custom_query ) ) {
			$numposts = $custom_query->found_posts;

			$max_page = $custom_query->max_num_pages;
			
		} else {
			$numposts = $wp_query->found_posts;
			$max_page = $wp_query->max_num_pages;
		}
		
		if(empty($paged) || $paged == 0) {
			$paged = 1;
		}
		$pages_to_show = intval($pagenavi_options['num_pages']);
		$larger_page_to_show = intval($pagenavi_options['num_larger_page_numbers']);
		$larger_page_multiple = intval($pagenavi_options['larger_page_numbers_multiple']);
		$pages_to_show_minus_1 = $pages_to_show - 1;
		$half_page_start = floor($pages_to_show_minus_1/2);
		$half_page_end = ceil($pages_to_show_minus_1/2);
		$start_page = $paged - $half_page_start;
		if($start_page <= 0) {
			$start_page = 1;
		}
		$end_page = $paged + $half_page_end;
		if(($end_page - $start_page) != $pages_to_show_minus_1) {
			$end_page = $start_page + $pages_to_show_minus_1;
		}
		if($end_page > $max_page) {
			$start_page = $max_page - $pages_to_show_minus_1;
			$end_page = $max_page;
		}
		if($start_page <= 0) {
			$start_page = 1;
		}
		$larger_per_page = $larger_page_to_show*$larger_page_multiple;
		$larger_start_page_start = (am_n_round($start_page, 10) + $larger_page_multiple) - $larger_per_page;
		$larger_start_page_end = am_n_round($start_page, 10) + $larger_page_multiple;
		$larger_end_page_start = am_n_round($end_page, 10) + $larger_page_multiple;
		$larger_end_page_end = am_n_round($end_page, 10) + ($larger_per_page);
		if($larger_start_page_end - $larger_page_multiple == $start_page) {
			$larger_start_page_start = $larger_start_page_start - $larger_page_multiple;
			$larger_start_page_end = $larger_start_page_end - $larger_page_multiple;
		}
		if($larger_start_page_start <= 0) {
			$larger_start_page_start = $larger_page_multiple;
		}
		if($larger_start_page_end > $max_page) {
			$larger_start_page_end = $max_page;
		}
		if($larger_end_page_end > $max_page) {
			$larger_end_page_end = $max_page;
		}
		if($max_page > 1 || intval($pagenavi_options['always_show']) == 1) {
			$pages_text = str_replace("%CURRENT_PAGE%", number_format_i18n($paged), $pagenavi_options['pages_text']);
			$pages_text = str_replace("%TOTAL_PAGES%", number_format_i18n($max_page), $pages_text);
			$out = $before.'<div class="wp-pagenavi">'."\n";
			switch(intval($pagenavi_options['style'])) {
				case 1:
					if($larger_page_to_show > 0 && $larger_start_page_start > 0 && $larger_start_page_end <= $max_page) {
						for($i = $larger_start_page_start; $i < $larger_start_page_end; $i+=$larger_page_multiple) {
							$page_text = str_replace("%PAGE_NUMBER%", number_format_i18n($i), $pagenavi_options['page_text']);
							$out .= '<a href="'.esc_url(get_pagenum_link($i)).'" class="pagenavi-page" title="'.$page_text.'">'.$page_text.'</a>';
						}
					}
					for($i = $start_page; $i  <= $end_page; $i++) {						
						if($i == $paged) {
							$current_page_text = str_replace("%PAGE_NUMBER%", number_format_i18n($i), $pagenavi_options['current_text']);
							$out .= '<span class="current">'.$current_page_text.'</span>';
						} else {
							$page_text = str_replace("%PAGE_NUMBER%", number_format_i18n($i), $pagenavi_options['page_text']);
							$out .= '<a href="'.esc_url(get_pagenum_link($i)).'" class="pagenavi-page" title="'.$page_text.'">'.$page_text.'</a>';
						}
					}
					if($larger_page_to_show > 0 && $larger_end_page_start < $max_page) {
						for($i = $larger_end_page_start; $i <= $larger_end_page_end; $i+=$larger_page_multiple) {
							$page_text = str_replace("%PAGE_NUMBER%", number_format_i18n($i), $pagenavi_options['page_text']);
							$out .= '<a href="'.esc_url(get_pagenum_link($i)).'" class="pagenavi-page" title="'.$page_text.'">'.$page_text.'</a>';
						}
					}
					break;
				case 2;
					$out .= '<form action="'.htmlspecialchars($_SERVER['PHP_SELF']).'" method="get">'."\n";
					$out .= '<select size="1" onchange="document.location.href = this.options[this.selectedIndex].value;">'."\n";
					for($i = 1; $i  <= $max_page; $i++) {
						$page_num = $i;
						if($page_num == 1) {
							$page_num = 0;
						}
						if($i == $paged) {
							$current_page_text = str_replace("%PAGE_NUMBER%", number_format_i18n($i), $pagenavi_options['current_text']);
							$out .= '<option value="'.esc_url(get_pagenum_link($page_num)).'" selected="selected" class="current">'.$current_page_text."</option>\n";
						} else {
							$page_text = str_replace("%PAGE_NUMBER%", number_format_i18n($i), $pagenavi_options['page_text']);
							$out .= '<option value="'.esc_url(get_pagenum_link($page_num)).'">'.$page_text."</option>\n";
						}
					}
					$out .= "</select>\n";
					$out .= "</form>\n";
					break;
			}
			$out .= '</div>'.$after."\n";
		}
	}
	return $out;
}


function am_n_round($num, $tonearest) {
   return floor($num/$tonearest)*$tonearest;
}
?>