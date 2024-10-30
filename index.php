<?php
/*
Plugin Name: HMH WooCommerce Product Bundles
Description: WooCommerce Product Bundles
Author: Hameha
Version: 1.0
Author URI: https://codecanyon.net/user/bestbug/portfolio
Text Domain: hmh-woo-product-bundles
Domain Path: /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

defined( 'HMH_WPB_URL' ) or define('HMH_WPB_URL', plugins_url( '/', __FILE__ ));
defined( 'HMH_WPB_PATH' ) or define('HMH_WPB_PATH', basename( dirname( __FILE__ )));
defined( 'HMH_WPB_FULLPATH' ) or define('HMH_WPB_FULLPATH', plugins_url( '/', __FILE__ ));
defined( 'HMH_WPB_TEXTDOMAIN' ) or define('HMH_WPB_TEXTDOMAIN', plugins_url( '/', __FILE__ ));

defined( 'HMH_WPB_PREFIX' ) or define('HMH_WPB_PREFIX', 'Hmh_Wpb_');
defined( 'HMH_WPB_VERSION' ) or define('HMH_WPB_VERSION', '1.0');

// POSTTYPES
defined( 'HMH_WPB_POSTTYPE_SECTION' ) or define('HMH_WPB_POSTTYPE_SECTION', 'Hmh_Wpb_section');
defined( 'HMH_WPB_POSTTYPE' ) or define('HMH_WPB_POSTTYPE', 'Hmh_Wpb');

// PAGE SLUG
defined( 'HMH_WPB_SLUG' ) or define('HMH_WPB_SLUG', 'Hmh_Wpb');
defined( 'HMH_WPB_SLUG_SETTINGS' ) or define('HMH_WPB_SLUG_SETTINGS', 'Hmh_Wpb_settings');
defined( 'HMH_WPB_ALL_SLUG' ) or define('HMH_WPB_ALL_SLUG', 'Hmh_Wpb_all');
defined( 'HMH_WPB_ADD_SLUG' ) or define('HMH_WPB_ADD_SLUG', 'Hmh_Wpb_add');

// SHORTCODES
defined( 'HMH_WPB_SHORTCODE' ) or define('HMH_WPB_SHORTCODE', 'Hmh_Wpb');

include_once 'includes/functions.php';

if ( ! class_exists( 'Hmh_Wpb_Class' ) ) {
	/**
	 * Hmh_Wpb_Class Class
	 *
	 * @since	1.0
	 */
	class Hmh_Wpb_Class {
		/**
		 * Constructor
		 *
		 * @return	void
		 * @since	1.0
		 */
		function __construct() {
			// Load core
			if(!class_exists('BestBug_Core_Class')) {
				include_once 'bestbugcore/index.php';
			}
			BestBug_Core_Class::support('options');
			BestBug_Core_Class::support('posttypes');
			
			include_once 'includes/index.php';
			
			if(is_admin()) {
				include_once 'includes/admin/index.php';
			}
			
			include_once 'includes/shortcodes/index.php';
			
            add_action( 'init', array( $this, 'init' ) );
		}

		public function init() {
			
			// Load enqueueScripts
			if(is_admin()) {
				add_action( 'admin_enqueue_scripts', array( $this, 'adminEnqueueScripts' ) );
			}
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueueScripts' ) );
        }
		public function adminEnqueueScripts() {
			wp_enqueue_script( 'hmh-bundle-product', HMH_WPB_URL . '/assets/admin/js/script.js', array( 'jquery' ), '1.0', true );
		}
		public function enqueueScripts() {
			wp_enqueue_style( 'hmh-bundle-product-css', HMH_WPB_URL. '/assets/css/style.css' );
			wp_enqueue_script( 'hmh-bundle-product-js', HMH_WPB_URL. '/assets/js/frontend.js' );
		}
		public function loadTextDomain() {
			load_plugin_textdomain( HMH_WPB_TEXTDOMAIN, false, HMH_WPB_PATH . '/languages/' );
		}
	}
	new Hmh_Wpb_Class();
}
