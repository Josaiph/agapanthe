<?php
/**
 * Registering meta boxes
 *
 * All the definitions of meta boxes are listed below with comments.
 * Please read them CAREFULLY.
 *
 * You also should read the changelog to know what has been changed before updating.
 *
 * For more information, please visit:
 * @link http://www.deluxeblogtips.com/meta-box/
 */

/********************* META BOX DEFINITIONS ***********************/

/**
 * Prefix of meta keys (optional)
 * Use underscore (_) at the beginning to make keys hidden
 * Alt.: You also can make prefix empty to disable it
 */
// Better has an underscore as last sign
$prefix = 'am_';

global $meta_boxes, $am_option;

$meta_boxes = array();

// 1st meta box
$meta_boxes[] = array(
	// Meta box id, UNIQUE per meta box. Optional since 4.1.5
	'id' => 'settings',

	// Meta box title - Will appear at the drag and drop handle bar. Required.
	'title' => 'Settings',

	// Post types, accept custom post types as well - DEFAULT is array('post'). Optional.
	'pages' => array( 'page' ),

	// Where the meta box appear: normal (default), advanced, side. Optional.
	'context' => 'normal',

	// Order of meta box: high (default), low. Optional.
	'priority' => 'high',

	// List of meta fields
	'fields' => array(
		// TEXT
		array(
			// Field name - Will be used as label
			'name'  => 'Title',
			// Field ID, i.e. the meta key
			'id'    => "{$prefix}title",
			// Field description (optional)
			'desc'  => 'Custom title',
			'type'  => 'text',
			// Default value (optional)
			'std'   => '',
		),
		// TEXT
		array(
			// Field name - Will be used as label
			'name'  => 'Subtitle',
			// Field ID, i.e. the meta key
			'id'    => "{$prefix}subtitle",
			// Field description (optional)
			'desc'  => 'Custom subtitle',
			'type'  => 'text',
			// Default value (optional)
			'std'   => '',
		),
	)
);

// 1st meta box
$meta_boxes[] = array(
	// Meta box id, UNIQUE per meta box. Optional since 4.1.5
	'id' => 'settings',

	// Meta box title - Will appear at the drag and drop handle bar. Required.
	'title' => 'Settings',

	// Post types, accept custom post types as well - DEFAULT is array('post'). Optional.
	'pages' => array( 'ad' ),

	// Where the meta box appear: normal (default), advanced, side. Optional.
	'context' => 'normal',

	// Order of meta box: high (default), low. Optional.
	'priority' => 'high',

	// List of meta fields
	'fields' => array(
		// TEXT
		array(
			// Field name - Will be used as label
			'name'  => 'Adult Price',
			// Field ID, i.e. the meta key
			'id'    => "{$prefix}adult_price",
			// Field description (optional)
			'desc'  => '',
			'type'  => 'text',
			// Default value (optional)
			'std'   => '',
		),
		// TEXT
		array(
			// Field name - Will be used as label
			'name'  => 'Child Price',
			// Field ID, i.e. the meta key
			'id'    => "{$prefix}child_price",
			// Field description (optional)
			'desc'  => '',
			'type'  => 'text',
			// Default value (optional)
			'std'   => '',
		),
		// SELECT BOX
		array(
			'name'     => 'Currency',
			'id'       => "{$prefix}currency",
			'type'     => 'select',
			// Array of 'value' => 'Label' pairs for select box
			'options'  => $am_option['defaults']['currency'],
			// Select multiple values, optional. Default is false.
			'multiple' => false,
		),
		// SELECT BOX
		array(
			'name'     => 'Allowed',
			'id'       => "{$prefix}allowed",
			'type'     => 'checkbox_list',
			// Array of 'value' => 'Label' pairs for select box
			'options'  => am_create_hash_array($am_option['defaults']['allowed']),
		),
		// SELECT BOX
		array(
			'name'     => 'Capacity',
			'id'       => "{$prefix}capacity",
			'type'     => 'select',
			// Array of 'value' => 'Label' pairs for select box
			'options'  => range(1, $am_option['defaults']['capacity'], 1),
			// Select multiple values, optional. Default is false.
			'multiple' => false,
		),
		array(
			'name'     => 'Situation',
			'id'       => "{$prefix}situation",
			'type'     => 'checkbox_list',
			// Array of 'value' => 'Label' pairs for select box
			'options'  => am_create_hash_array($am_option['defaults']['situation']),
		),
		array(
			'name'     => 'Amenities',
			'id'       => "{$prefix}amenities",
			'type'     => 'checkbox_list',
			// Array of 'value' => 'Label' pairs for select box
			'options'  => am_create_hash_array($am_option['defaults']['amenities']),
		),
		array(
			'name'     => 'Activities',
			'id'       => "{$prefix}activities",
			'type'     => 'checkbox_list',
			// Array of 'value' => 'Label' pairs for select box
			'options'  => am_create_hash_array($am_option['defaults']['activities']),
		),
		array(
			'name'     => 'Country',
			'id'       => "{$prefix}country",
			'type'     => 'select',
			// Array of 'value' => 'Label' pairs for select box
			'options'  => am_get_countries_array($am_option['defaults']['countries']),
			// Select multiple values, optional. Default is false.
			'multiple' => false,
		),
		array(
			'name'     => 'State',
			'id'       => "{$prefix}state",
			'type'     => 'select',
			// Array of 'value' => 'Label' pairs for select box
			'options'  => am_get_state_array($am_option['defaults']['countries']),
			// Select multiple values, optional. Default is false.
			'multiple' => false,
		),
		// TEXT
		array(
			// Field name - Will be used as label
			'name'  => 'Address 1',
			// Field ID, i.e. the meta key
			'id'    => "{$prefix}address_1",
			// Field description (optional)
			'desc'  => '',
			'type'  => 'text',
			// Default value (optional)
			'std'   => '',
		),
		// TEXT
		array(
			// Field name - Will be used as label
			'name'  => 'Address 2',
			// Field ID, i.e. the meta key
			'id'    => "{$prefix}address_2",
			// Field description (optional)
			'desc'  => '',
			'type'  => 'text',
			// Default value (optional)
			'std'   => '',
		),
		// TEXT
		array(
			// Field name - Will be used as label
			'name'  => 'Zip / Post Code',
			// Field ID, i.e. the meta key
			'id'    => "{$prefix}zip",
			// Field description (optional)
			'desc'  => '',
			'type'  => 'text',
			// Default value (optional)
			'std'   => '',
		),
		// TEXT
		array(
			// Field name - Will be used as label
			'name'  => 'City',
			// Field ID, i.e. the meta key
			'id'    => "{$prefix}city",
			// Field description (optional)
			'desc'  => '',
			'type'  => 'text',
			// Default value (optional)
			'std'   => '',
		),
		// TEXT
		array(
			// Field name - Will be used as label
			'name'  => 'Garden\'s Rules',
			// Field ID, i.e. the meta key
			'id'    => "{$prefix}garden_rules",
			// Field description (optional)
			'desc'  => '',
			'type' => 'wysiwyg',
			'std'  => '',

			// Editor settings, see wp_editor() function: look4wp.com/wp_editor
			'options' => array(
				'textarea_rows' => 4,
				'teeny'         => true,
				'media_buttons' => false,
			),
		),
		// PLUPLOAD IMAGE UPLOAD (WP 3.3+)
		array(
			'name'             => 'Images',
			'id'               => "{$prefix}images",
			'type'             => 'thickbox_image',
			'max_file_uploads' => 1,
		),
	)
);

/********************* META BOX REGISTERING ***********************/

/**
 * Register meta boxes
 *
 * @return void
 */
function am_register_meta_boxes()
{
	global $am_option;
	// Make sure there's no errors when the plugin is deactivated or during upgrade
	if ( !class_exists( 'RW_Meta_Box' ) )
		return;

	global $meta_boxes;
	foreach ( $meta_boxes as $meta_box )
	{
		new RW_Meta_Box( $meta_box );
	}
}
// Hook to 'admin_init' to make sure the meta box class is loaded before
// (in case using the meta box class in another plugin)
// This is also helpful for some conditionals like checking page template, categories, etc.
add_action( 'admin_init', 'am_register_meta_boxes' );