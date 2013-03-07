<?php 
global $am_option, $message; get_header();


$url_contact_host = '';

if ( !is_user_logged_in() ){
	$url_contact_host = get_permalink(ot_get_option('general_login_page')); 
}

?>

<script type="text/javascript">
	var geocoder;
      var map;
      function am_initialize() {
        geocoder = new google.maps.Geocoder();
        var latlng = new google.maps.LatLng(-34.397, 150.644);
        var mapOptions = {
          zoom: 10,
          center: latlng,
          mapTypeId: google.maps.MapTypeId.ROADMAP
        }
        map = new google.maps.Map(document.getElementById('map_canvas'), mapOptions);
        am_codeAddress();
      }

      function am_codeAddress() {
        var address = document.getElementById('map_address').value;
        geocoder.geocode( { 'address': address}, function(results, status) {
          if (status == google.maps.GeocoderStatus.OK) {
            map.setCenter(results[0].geometry.location);
            var image = new google.maps.MarkerImage(
              "<?php bloginfo('template_directory'); ?>/images/marker.png",
              new google.maps.Size(120, 120),// size of the image
              new google.maps.Point(0, 0),   // origin, in this case top-left corner
              new google.maps.Point(60, 60)  // anchor, i.e. the point half-way along the bottom of the image
            );

            var marker = new google.maps.Marker({
                map: map,
                position: results[0].geometry.location,
                icon: image
            });
          }
        });
      }
		
	window.onload = am_initialize;
</script>
<div id="content">
	<?php if(!empty($message)) : ?><div class="reply_box reply_box_color1"><?php echo $message; ?></div><?php endif; ?>
	<div class="main_content">
		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<?php
			$adult_price = am_get_custom_field('am_adult_price', get_the_ID(), true);
			$child_price = am_get_custom_field('am_child_price', get_the_ID(), true);
			$garden_rules = am_get_custom_field('am_garden_rules', get_the_ID(), true);
			$currency = am_get_custom_field('am_currency', get_the_ID(), true);
			
			$allowed = am_get_custom_field('am_allowed', get_the_ID(), false);
			$capacity = am_get_custom_field('am_capacity', get_the_ID(), true);
			$situation = am_get_custom_field('am_situation', get_the_ID(), false);
			$amenities = am_get_custom_field('am_amenities', get_the_ID(), false);
			$activities = am_get_custom_field('am_activities', get_the_ID(), false);
			$address_1 = am_get_custom_field('am_address_1', get_the_ID(), true);
			$address_2 = am_get_custom_field('am_address_2', get_the_ID(), true);
			$zip = am_get_custom_field('am_zip', get_the_ID(), true);
			$city = am_get_custom_field('am_city', get_the_ID(), true);
			$images = am_get_custom_field('am_images', get_the_ID(), false);
			
			$country = am_get_custom_field('am_country', get_the_ID(), true);
			$state = am_get_custom_field('am_state', get_the_ID(), true);
			
			$map_address = $city.' '.$state.' '.$country;
			
			if(!empty($state))
				$country .= ' ('.$state.')';
			if(!empty($city))
				$full_address = array($city,$country);
			else
				$full_address = array($country);
		?>
		<input id="map_address" name="map_address" type="hidden" value="<?php echo $map_address; ?>" />
		<div class="top_box">
			<div id="ad_slider">
				<div class="ad_slider">
					<?php if(count($images)>0) : ?>
					<ul>
						<?php $count = 0; foreach($images as $img) : ?>
						<?php
							$thumbnail = wp_get_attachment_image_src($img,'full');
							if(isset($thumbnail[0])) :
								$count++;
								$thumbnail = $thumbnail[0]; 
								$thumbnail = am_image_resize($thumbnail, 716, 287);
								?><li><img src="<?php echo $thumbnail; ?>" width="716" height="287" alt="" /></li><?php
							endif;
						?>
						<?php endforeach; ?>
					</ul>
					<?php if($count>1): ?>
					<div class="sli_dots"></div>
					<a href="#" class="sli_prev"><?php echo am_lang('previous'); ?></a>
					<a href="#" class="sli_next"><?php echo am_lang('next'); ?></a>
					<?php endif; ?>
					<?php else: ?>
					<ul><li><img src="<?php echo get_template_directory_uri(); ?>/images/slide1.jpg" width="716" height="287" alt="" /></li></ul>
					<?php endif; ?>
				</div>
				<a href="#" class="btn_switch"><span class="ico_map"></span><?php echo am_lang('map'); ?></a>
			</div>
			<div id="ad_map">
				<div class="map" id="map_canvas"></div>
				<a href="#" class="btn_switch"><span class="ico_picture"></span><?php echo am_lang('pictures'); ?></a>
			</div>
		</div><!-- /top_box -->
		
		<div class="main_box">
			<div class="main_col1">
				<div class="block_box">
					<div class="allowed_row">
						<strong><?php echo am_lang('allowed'); ?></strong>
						<?php
							$allow_1 = sanitize_title($am_option['defaults']['allowed'][0]);
							$allow_2 = sanitize_title($am_option['defaults']['allowed'][1]);
							$allow_3 = sanitize_title($am_option['defaults']['allowed'][2]);
						?>
						<span id="allow1"<?php if(in_array($allow_1, $allowed)) echo ' class="on"';  ?>></span>
						<span id="allow2"<?php if(in_array($allow_2, $allowed)) echo ' class="on"';  ?>></span>
						<span id="allow3"<?php if(in_array($allow_3, $allowed)) echo ' class="on"';  ?>></span>
					</div>
					<div class="entry">
						<?php the_content(am_lang('read_more')); ?>
						<div class="clear"></div>
					</div>
					<div class="map_direct"><?php echo implode(', ', $full_address); ?></div>
				</div>
				
				<div class="line line2"></div>
				
				<div class="block_box">
					<h3><?php echo am_lang('amenities'); ?></h3>
					<ul class="radio_box">
						<?php foreach($am_option['defaults']['amenities'] as $k=>$v): ?>
						<li<?php if(in_array(sanitize_title($v), $amenities)) echo ' class="valid"'; ?>><?php echo am_lang($v); ?></li>
						<?php endforeach; ?>
					</ul>
				</div>
				
				<div class="line line2"></div>
				
				<div class="block_box">
					<h3><?php echo am_lang('activities'); ?></h3>
					<ul class="radio_box">
						<?php foreach($am_option['defaults']['activities'] as $k=>$v): ?>
						<li<?php if(in_array(sanitize_title($v), $activities)) echo ' class="valid"'; ?>><?php echo am_lang($v); ?></li>
						<?php endforeach; ?>
					</ul>
				</div>
				
				<?php if(!empty($garden_rules)) : ?>
				<div class="line line2"></div>
				
				<div class="block_box">
					<h3><?php echo am_lang('gardens_rules'); ?></h3>
					<div class="entry"><?php echo wpautop($garden_rules); ?></div>
				</div>
				<?php endif; ?>
			</div><!-- /main_col1 -->
			
			<div class="main_col2">
				<?php if(!empty($adult_price) || !empty($child_price)) : ?>
				<div class="price_row">
					<?php if(!empty($adult_price)) : ?><div class="price_item1"><?php echo $adult_price.$currency; ?><?php echo am_lang('per_night'); ?></div><?php endif; ?>
					<?php if(!empty($child_price)) : ?><div class="price_item2"><?php echo $child_price.$currency; ?><?php echo am_lang('per_night'); ?></div><?php endif; ?>
				</div>
				<?php endif; ?>
				
				<?php if(empty($url_contact_host)) : ?>
				<a href="#write_to_host" class="btn_comm1 btn_contact fancybox"><?php echo am_lang('contact_host'); ?></a>
				<?php else : ?>
				<a href="<?php echo $url_contact_host; ?>" class="btn_comm1 btn_contact"><?php echo am_lang('contact_host'); ?></a>
				<?php endif; ?>
				
				<div class="line line2"></div>
				<?php
					$post_author = get_userdata(get_the_author_meta( 'ID' ));
				?>
				<a href="<?php echo home_url().'/author/?host='.$post_author->ID; //echo get_author_posts_url(get_the_author_meta( 'ID' )); ?>" class="person_info">
					<?php
						$author_img = am_the_author_image($post_author->ID,null,true);
						if(!empty($author_img))
							$author_img = '<img src="'.am_image_resize($author_img,55,55).'" alr="" />';
						else
							$author_img = '<img src="'.get_template_directory_uri().'/images/pic_user_default.png" alr="" />';
					?>
					<?php echo $author_img; ?>
					<div class="inner"><strong><?php echo am_get_short_author_name($post_author); ?></strong><?php echo am_lang('your_host'); ?></div>
				</a>
			</div><!-- /main_col2 -->
		</div><!-- /main_box -->
		<?php endwhile; endif; ?>
	</div><!-- /main_content -->
	<?php get_sidebar(); ?>
</div><!-- /content2 -->

<?php get_footer(); ?>