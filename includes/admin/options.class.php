<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Hmh_Wpb_Options' ) ) {
	/**
	 * Hmh_Wpb_Options Class
	 *
	 * @since	1.0
	 */
	class Hmh_Wpb_Options {


		/**
		 * Constructor
		 *
		 * @return	void
		 * @since	1.0
		 */
		function __construct() {
			$this->init();
		}

		public function init() {
			add_action('admin_menu', array($this, 'menu'));
			add_filter('bb_register_options', array( $this, 'options'), 10, 1 );

			if(is_admin()) {
				add_action( 'admin_enqueue_scripts', array( $this, 'adminEnqueueScripts' ) );
			}
			
        }

		public function adminEnqueueScripts() {
			BestBug_Core_Class::adminEnqueueScripts();
			
			if(isset($_GET['page']) && ($_GET['page'] == HMH_WPB_SLUG || $_GET['page'] == HMH_WPB_SLUG_SETTINGS || $_GET['page'] == HMH_WPB_ALL_SLUG || $_GET['page'] == HMH_WPB_ADD_SLUG)) {
				BestBug_Core_Options::adminEnqueueScripts();
			}
		}

		public function menu()
		{
			$menu = array(
				'page_title' => esc_html('HMH Woo Product Bundle', 'hmh-woo-product-bundles'),
				'menu_title' => esc_html('HMH Woo Product Bundle', 'hmh-woo-product-bundles'),
				'capability' => 'manage_options',
				'menu_slug' => HMH_WPB_SLUG_SETTINGS,
				'icon' => '',
				'position' => 76,
			);
			add_menu_page(
				$menu['page_title'],
				$menu['menu_title'],
				$menu['capability'],
				$menu['menu_slug'],
				array(&$this, 'view'),
				$menu['icon'],
				$menu['position']
			);
		}

		public function view() {

		}
        
        public function options($options) {
			if( empty($options) ) {
				$options = array();
			}

			$prefix = HMH_WPB_PREFIX;
			$options[] = array(
				'type' => 'options_fields',
				'menu' => array(
					// add_submenu_page || add_menu_page
					'type' => 'add_submenu_page',
					'parent_slug' => HMH_WPB_SLUG_SETTINGS,
					'page_title' => esc_html('Settings', 'hmh-woo-product-bundles'),
					'menu_title' => esc_html('Settings', 'hmh-woo-product-bundles'),
					'capability' => 'manage_options',
					'menu_slug' => HMH_WPB_SLUG_SETTINGS,
				),
				'fields' => array(
					array(
						'type' => 'toggle',
						'heading' => esc_html__('Show prices', 'hmh-woo-product-bundles'),
						'param_name' => $prefix . 'show_prices',
						'value' => 'yes',
						'description' => esc_html__('', 'hmh-woo-product-bundles'),
					),
					array(
						'type' => 'toggle',
						'heading' => esc_html__('Show quantity', 'hmh-woo-product-bundles'),
						'param_name' => $prefix . 'show_quantity',
						'value' => 'yes',
						'description' => esc_html__('', 'hmh-woo-product-bundles'),
					),
					array(
						'type' => 'toggle',
						'heading' => esc_html__('Show thumbnails', 'hmh-woo-product-bundles'),
						'param_name' => $prefix . 'show_thumbnails',
						'value' => 'yes',
						'description' => esc_html__('', 'hmh-woo-product-bundles'),
					),
					 array(
                        'type'       => 'textfield',
                        'heading'    => esc_html__( 'Link to products', 'hmh-woo-product-bundles' ),
                        'param_name' => $prefix . 'link_products',
                        'value'      => '',
                        'description' => esc_html('', 'hmh-woo-product-bundles'),
                    ),
					array(
                        'type'        => 'dropdown',
                        'heading'     => esc_html__( 'Location displayed', 'hmh-woo-product-bundles' ),
                        'value'       => array(
                                '1'   => 'On the button add cart',
                                '2'   => 'Under the add cart button',
                                '3'   => 'No',
                        	),
                        'param_name'  => $prefix . 'location_displayed',
                                'admin_label' => true,
                                'save_always' => true,
                        'std'		  => '1',
	                ),
					array(
                        'type'        => 'dropdown',
                        'heading'     => esc_html__( 'Hide bundled products', 'hmh-woo-product-bundles' ),
                        'value'       => array(
                            'yes' 	  => 'Yes just show the main product',
                            'yes_text' => 'Yes but show bundled product names under the main product',
                        ),
                        'param_name'  => $prefix . 'hide_product_bundle',
                                'admin_label' => true,
                                'save_always' => true,
                        'std'		  => 'yes',
	                ),
				),
			);

			return $options;
        }
        
    }
	
	new Hmh_Wpb_Options();
}

