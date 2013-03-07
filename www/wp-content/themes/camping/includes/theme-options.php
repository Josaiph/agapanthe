<?php
/**
 * Initialize the options before anything else.
 */
add_action( 'admin_init', 'custom_theme_options', 1 );

/**
 * Build the custom settings & update OptionTree.
 */
function custom_theme_options() {
  /**
   * Get a copy of the saved settings array. 
   */
  $saved_settings = get_option( 'option_tree_settings', array() );
  
  /**
   * Custom settings array that will eventually be 
   * passes to the OptionTree Settings API Class.
   */
  $custom_settings = array( 
    'contextual_help' => array( 
      'sidebar'       => ''
    ),
    'sections'        => array( 
      array(
        'id'          => 'general_section',
        'title'       => 'General'
      ),
    ),
    'settings'        => array( 
      array(
        'id'          => 'general_admin_email',
        'label'       => 'Admin Email',
        'desc'        => 'Entry a text',
        'std'         => 'joseph@gamping.com',
        'type'        => 'text',
        'section'     => 'general_section',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'class'       => ''
      ),
      array(
        'id'          => 'general_blog_page_url',
        'label'       => 'Blog URL',
        'desc'        => 'Entry a link',
        'std'         => 'http://blog.gamping.com',
        'type'        => 'text',
        'section'     => 'general_section',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'class'       => ''
      ),
      array(
        'id'          => 'general_google_map_api',
        'label'       => 'Google Map API',
        'desc'        => 'Entry a api key',
        'std'         => 'AIzaSyCH3d_uI1SckfpR2zmn4PNNHsDegeCOvZo',
        'type'        => 'text',
        'section'     => 'general_section',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'class'       => ''
      ),
      array(
        'id'          => 'general_desc_gamping_page',
        'label'       => 'Discover the Gamping Page',
        'desc'        => 'Select a page',
        'std'         => '',
        'type'        => 'page-select',
        'section'     => 'general_section',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'class'       => ''
      ),
      array(
        'id'          => 'general_list_garden_page',
        'label'       => 'List a Garden Page',
        'desc'        => 'Select a page',
        'std'         => '',
        'type'        => 'page-select',
        'section'     => 'general_section',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'class'       => ''
      ),
      array(
        'id'          => 'general_help_us_page',
        'label'       => 'Help Us Page',
        'desc'        => 'Select a page',
        'std'         => '',
        'type'        => 'page-select',
        'section'     => 'general_section',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'class'       => ''
      ),
      array(
        'id'          => 'general_login_page',
        'label'       => 'Login Page',
        'desc'        => 'Select a page',
        'std'         => '',
        'type'        => 'page-select',
        'section'     => 'general_section',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'class'       => ''
      ),
    )
  );
  
  /* allow settings to be filtered before saving */
  $custom_settings = apply_filters( 'option_tree_settings_args', $custom_settings );
  
  /* settings are not the same update the DB */
  if ( $saved_settings !== $custom_settings ) {
    update_option( 'option_tree_settings', $custom_settings ); 
  }
  
}