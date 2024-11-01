<?php

/*
Plugin Name: WPT - Demo Importer
Plugin URI: http://wptrees.com/downloads/wpt-demo-importer/
Description: Import WPT themes Demos
Version: 1.0.5
Author: wptrees
Author URI: https://wptrees.com
License: GPL3
License URI: http://www.gnu.org/licenses/gpl.html
Text Domain: pt-PADEPRE
*/

// Block direct access to the main plugin file.
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/**
 * Main plugin class with initialization tasks.
 */
class PADEPRE_Plugin {
	/**
	 * Constructor for this class.
	 */
	public function __construct() {
		/**
		 * Display admin error message if PHP version is older than 5.3.2.
		 * Otherwise execute the main plugin class.
		 */
		if ( version_compare( phpversion(), '5.3.2', '<' ) ) {
			add_action( 'admin_notices', array( $this, 'old_php_admin_error_notice' ) );
		}
		else {
			// Set plugin constants.
			$this->set_plugin_constants();

			// Composer autoloader.
			require_once PT_PADEPRE_PATH . 'vendor/autoload.php';

			// Instantiate the main plugin class *Singleton*.
			$pt_one_click_demo_import = PADEPRE\PrimaAppDemoImport::get_instance();

			// Register WP CLI commands
			if ( defined( 'WP_CLI' ) && WP_CLI ) {
				WP_CLI::add_command( 'PADEPRE list', array( 'PADEPRE\WPCLICommands', 'list_predefined' ) );
				WP_CLI::add_command( 'PADEPRE import', array( 'PADEPRE\WPCLICommands', 'import' ) );
			}
		}
	}


	/**
	 * Display an admin error notice when PHP is older the version 5.3.2.
	 * Hook it to the 'admin_notices' action.
	 */
	public function old_php_admin_error_notice() {
		$message = sprintf( esc_html__( 'The %2$sWPT Demo Importer%3$s plugin requires %2$sPHP 5.3.2+%3$s to run properly. Please contact your hosting company and ask them to update the PHP version of your site to at least PHP 5.3.2.%4$s Your current version of PHP: %2$s%1$s%3$s', 'pt-PADEPRE' ), phpversion(), '<strong>', '</strong>', '<br>' );

		printf( '<div class="notice notice-error"><p>%1$s</p></div>', wp_kses_post( $message ) );
	}


	/**
	 * Set plugin constants.
	 *
	 * Path/URL to root of this plugin, with trailing slash and plugin version.
	 */
	private function set_plugin_constants() {
		// Path/URL to root of this plugin, with trailing slash.
		if ( ! defined( 'PT_PADEPRE_PATH' ) ) {
			define( 'PT_PADEPRE_PATH', plugin_dir_path( __FILE__ ) );
		}
		if ( ! defined( 'PT_PADEPRE_URL' ) ) {
			define( 'PT_PADEPRE_URL', plugin_dir_url( __FILE__ ) );
		}

		// Action hook to set the plugin version constant.
		add_action( 'admin_init', array( $this, 'set_plugin_version_constant' ) );
	}


	/**
	 * Set plugin version constant -> PT_PADEPRE_VERSION.
	 */
	public function set_plugin_version_constant() {
		if ( ! defined( 'PT_PADEPRE_VERSION' ) ) {
			$plugin_data = get_plugin_data( __FILE__ );
			define( 'PT_PADEPRE_VERSION', $plugin_data['Version'] );
		}
	}
}

	function PADEPRE_import_files() {

		//update 1.1 removed
		//if ( method_exists( '\Elementor\Compatibility', 'on_wxr_importer_pre_process_post_meta' ) ) {
		//	remove_action( 'wxr_importer.pre_process.post_meta', array( 'Elementor\Compatibility', 'on_wxr_importer_pre_process_post_meta' ) );
		//}


	$import_file_name = '';
	$import_file_url = '';
	$import_customizer_file_url = '';
	$import_preview_image_url = '';
	$preview_url = '';

	$theme = wp_get_theme();
	if ( 'PrimaApp' == $theme->name || 'PrimaApp' == $theme->parent_theme ) {
		$import_file_name = 'PrimaApp Demo Import';
		$import_file_url = PT_PADEPRE_URL . 'settings/WPT-Demo-PrimaApp/primaapp.xml';
		$import_customizer_file_url = PT_PADEPRE_URL . 'settings/WPT-Demo-PrimaApp/primaapp.dat';
		$import_preview_image_url = PT_PADEPRE_URL . 'settings/WPT-Demo-PrimaApp/ptdemom.png';
		$preview_url = 'https://wptrees.com/primaapp/';

	}
	    return array(
	      array(
	        'import_file_name'           => $import_file_name,
	        'categories'                 => array( '' ),
	        'import_file_url'            => $import_file_url,
	        'import_widget_file_url'     => '',
	        'import_customizer_file_url' => $import_customizer_file_url,
	        'import_redux'               => array(
	          array(
	            'file_url'    => '',
	            'option_name' => '',
	          ),
	        ),
	        'import_preview_image_url'   => $import_preview_image_url,
	        'import_notice'              => '',
	        'preview_url'                => $preview_url,
	      ),
	    );
  }

  add_filter( 'pt-PADEPRE/import_files', 'PADEPRE_import_files' );

  function PADEPRE_after_import_setup() {

  	$theme = wp_get_theme();
  	if ( 'PrimaApp' == $theme->name || 'PrimaApp' == $theme->parent_theme ) {

          //set menues //
          $main_menu = get_term_by( 'name', 'Menu 1', 'nav_menu' );
          $footer_menu = get_term_by( 'name', 'Social menu', 'nav_menu' );
          set_theme_mod( 'nav_menu_locations', array(
                  'primary' => $main_menu->term_id, // replace 'main-menu' here with the menu location identifier from register_nav_menu() function
                  'footer_social' => $footer_menu->term_id, // replace 'main-menu' here with the menu location identifier from register_nav_menu() function
              )
          );
          //set front page//
          $frontp = get_page_by_title( 'Front Page PrimaApp' );  // add front page ID
          update_option( 'show_on_front', 'page' );
          update_option( 'page_on_front', $frontp->ID );
          //set blog page//
          $blog   = get_page_by_title( 'Blog' );   // add blog page ID
          update_option( 'page_for_posts', $blog->ID );
          $new_option_value_pa = get_option( 'theme_mods_primaapp' );
          //array_walk_recursive($new_option_value_pa, "removeMy");
          update_option('theme_mods_primaapp',$new_option_value_pa);

      }
  }
  add_action( 'pt-PADEPRE/after_import', 'PADEPRE_after_import_setup' );


// Instantiate the plugin class.
$PADEPRE_plugin = new PADEPRE_Plugin();

