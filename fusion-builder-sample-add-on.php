<?php
/**
 * Plugin Name: Fusion Builder Sample Addon
 * Plugin URI: https://github.com/Theme-Fusion/Fusion-Builder-Sample-Add-On
 * Description: Adds quotes rotator element using this sample addon for fusion builder.
 * Version: 1.2
 * Author: ThemeFusion
 * Author URI: https://www.theme-fusion.com
 *
 * @package Sample Addon for Fusion Builder
 */

// Plugin Folder Path.
if ( ! defined( 'SAMPLE_ADDON_PLUGIN_DIR' ) ) {
	define( 'SAMPLE_ADDON_PLUGIN_DIR', wp_normalize_path( plugin_dir_path( __FILE__ ) ) );
}

// Plugin Folder URL.
if ( ! defined( 'SAMPLE_ADDON_PLUGIN_URL' ) ) {
	define( 'SAMPLE_ADDON_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

register_activation_hook( __FILE__, array( 'Sample_Addon_FB', 'activation' ) );

if ( ! class_exists( 'Sample_Addon_FB' ) ) {

	// Init the elements.
	function init_elements() {
		include_once wp_normalize_path( SAMPLE_ADDON_PLUGIN_DIR . '/elements/hello-world.php' );
	}
	add_action( 'init', 'init_elements', 10 );
}
