<?php
	global $current_user;

if(is_user_logged_in()) {
	$author_id = get_current_user_id();

	$post_ad = am_get_user_ad($author_id);
	if(!empty($post_ad))
		$post_ad = get_post($post_ad);
?>
    <?php if($post_ad->post_status=='draft') : ?>
    <a href="<?php echo get_permalink(ot_get_option('general_list_garden_page')); ?>" class="btn_list"><?php echo am_lang('list_a_garden'); ?></a>
    <?php endif; ?>
    <div class="user_panel">
        <?php
            $author_img = am_the_author_image($author_id,null,true);
            if(empty($author_img))
                $author_img = '<span></span>';
            else
                $author_img = '<img src="'.am_image_resize($author_img,38,38).'" alr="" />';

        ?>
        <a href="#" class="btn_person btn_person_register"><?php echo $author_img; ?></a>
        <ul class="dropbox">
            <li><a href="<?php echo home_url().'/author/?host='.$author_id; ?>"><?php echo am_lang('my_profile'); ?></a></li>
            <li><a href="<?php echo home_url().'/edit-profile/'; ?>"><?php echo am_lang('edit_my_profile'); ?></a></li>
            <?php if(isset($post_ad->ID) && $post_ad->post_status=='publish') : ?>
            <li><a href="<?php echo get_permalink($post_ad->ID); ?>"><?php echo am_lang('my_ad'); ?></a></li>
            <li><a href="<?php echo home_url().'/edit-ad/'; ?>"><?php echo am_lang('edit_my_ad'); ?></a></li>
            <?php endif; ?>
            <li><a href="<?php echo wp_logout_url(home_url()); ?>"><?php echo am_lang('log_out'); ?></a></li>
        </ul>
    </div>
<?php } else { ?>
    <a href="<?php echo get_permalink(ot_get_option('general_list_garden_page')); ?>" class="btn_list<?php // btn_comm1 ?>"><?php echo am_lang('list_a_garden'); ?></a>
    <div class="user_panel">
        <a href="<?php echo get_permalink(ot_get_option('general_login_page')); ?>" class="btn_person_login"><span></span></a>
    </div>
<?php } ?>