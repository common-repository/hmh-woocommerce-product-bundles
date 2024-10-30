<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Hmh_Wpb_Posttypes' ) ) {
	/**
	 * Hmh_Wpb_Posttypes Class
	 *
	 * @since	1.0
	 */
	class Hmh_Wpb_Posttypes {


		/**
		 * Constructor
		 *
		 * @return	void
		 * @since	1.0
		 */
		function __construct() {
			add_filter( 'bb_register_posttypes', array( $this, 'register_posttypes' ), 10, 1 );
		}
        
		public function register_posttypes($posttypes) {

			if( empty($posttypes) ) {
				$posttypes = array();
			}

			$labels = array(
				'name'               => esc_html_x( 'Section', 'Section', 'hmh-woo-product-bundles' ),
				'singular_name'      => esc_html_x( 'Section', 'Section', 'hmh-woo-product-bundles' ),
				'menu_name'          => esc_html__( 'Section', 'hmh-woo-product-bundles' ),
				'name_admin_bar'     => esc_html__( 'Section', 'hmh-woo-product-bundles' ),
				'parent_item_colon'  => esc_html__( 'Parent Menu:', 'hmh-woo-product-bundles' ),
				'all_items'          => esc_html__( 'All Sections', 'hmh-woo-product-bundles' ),
				'add_new_item'       => esc_html__( 'Add New Section', 'hmh-woo-product-bundles' ),
				'add_new'            => esc_html__( 'Add New', 'hmh-woo-product-bundles' ),
				'new_item'           => esc_html__( 'New Section', 'hmh-woo-product-bundles' ),
				'edit_item'          => esc_html__( 'Edit Section', 'hmh-woo-product-bundles' ),
				'update_item'        => esc_html__( 'Update Section', 'hmh-woo-product-bundles' ),
				'view_item'          => esc_html__( 'View Section', 'hmh-woo-product-bundles' ),
				'search_items'       => esc_html__( 'Search Section', 'hmh-woo-product-bundles' ),
				'not_found'          => esc_html__( 'Not found', 'hmh-woo-product-bundles' ),
				'not_found_in_trash' => esc_html__( 'Not found in Trash', 'hmh-woo-product-bundles' ),
			);
			$args   = array(
				'label'               => esc_html__( 'Section', 'hmh-woo-product-bundles' ),
				'description'         => esc_html__( 'Section', 'hmh-woo-product-bundles' ),
				'labels'              => $labels,
				'supports'            => array( 'title', 'editor', ),
				'hierarchical' => false,
				'public' => true,
				'show_ui' => true,
				'show_in_menu' => true,
				'menu_position' => 21,
				'menu_icon' => 'dashicons-slides',
				'show_in_admin_bar' => true,
				'show_in_nav_menus' => true,
				'can_export' => true,
				'has_archive' => false,
				'exclude_from_search' => true,
				'publicly_queryable' => true,
				'rewrite' => false,
				'capability_type' => 'page',
			);

			$posttypes[HMH_WPB_POSTTYPE_SECTION] = $args;

			// FullPage
			$labels = array(
				'name' => esc_html_x('PostType_Name', 'PostType_Name', 'hmh-woo-product-bundles'),
				'singular_name' => esc_html_x('FullPage', 'FullPage', 'hmh-woo-product-bundles'),
				'menu_name' => esc_html__('FullPage', 'hmh-woo-product-bundles'),
				'name_admin_bar' => esc_html__('FullPage', 'hmh-woo-product-bundles'),
				'parent_item_colon' => esc_html__('Parent Menu:', 'hmh-woo-product-bundles'),
				'all_items' => esc_html__('All PostType_Name', 'hmh-woo-product-bundles'),
				'add_new_item' => esc_html__('Add New FullPage', 'hmh-woo-product-bundles'),
				'add_new' => esc_html__('Add New', 'hmh-woo-product-bundles'),
				'new_item' => esc_html__('New FullPage', 'hmh-woo-product-bundles'),
				'edit_item' => esc_html__('Edit FullPage', 'hmh-woo-product-bundles'),
				'update_item' => esc_html__('Update FullPage', 'hmh-woo-product-bundles'),
				'view_item' => esc_html__('View FullPage', 'hmh-woo-product-bundles'),
				'search_items' => esc_html__('Search FullPage', 'hmh-woo-product-bundles'),
				'not_found' => esc_html__('Not found', 'hmh-woo-product-bundles'),
				'not_found_in_trash' => esc_html__('Not found in Trash', 'hmh-woo-product-bundles'),
			);
			$args = array(
				'label' => esc_html__('FullPage', 'hmh-woo-product-bundles'),
				'description' => esc_html__('FullPage', 'hmh-woo-product-bundles'),
				'labels' => $labels,
				'supports' => array('title', 'editor', ),
				'hierarchical' => false,
				'public' => false,
				'show_ui' => false,
				'show_in_menu' => false,
				'menu_position' => 13,
				'menu_icon' => HMH_WPB_URL . 'assets/images/FullPage.png',
				'show_in_admin_bar' => false,
				'show_in_nav_menus' => false,
				'can_export' => true,
				'has_archive' => false,
				'exclude_from_search' => true,
				'publicly_queryable' => false,
				'rewrite' => false,
				'capability_type' => 'page',
			);
			$posttypes[HMH_WPB_POSTTYPE_FULLPAGE] = $args;

			return $posttypes;
		}
        
    }
	
	new Hmh_Wpb_Posttypes();
}
