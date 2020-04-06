<?php
/**
 * Plugin Name: Fusion Builder Front-End Sample Addon
 * Plugin URI: https://github.com/Theme-Fusion/Fusion-Builder-Sample-Add-On
 * Description: Adds simple hello world example of front-end API.
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

if ( ! class_exists( 'Sample_Addon_FB' ) ) {

	// Init the elements.
	function my_init_elements() {
		include_once wp_normalize_path( SAMPLE_ADDON_PLUGIN_DIR . '/elements/hello-world.php' );
	}
	add_action( 'fusion_builder_shortcodes_init', 'my_init_elements', 10 );


	function my_enqueue_scripts() {
		Fusion_Dynamic_CSS::enqueue_style( SAMPLE_ADDON_PLUGIN_DIR . 'css/my-elements.css', SAMPLE_ADDON_PLUGIN_URL . 'css/my-elements.css' );
		/*
		Using the above call will combined into the compiled CSS.  Alternatively you can enqueue separately with:
		wp_enqueue_style( 'my-elements', SAMPLE_ADDON_PLUGIN_URL . 'css/my-elements.css', false, false );

		In that case also change hook below from wp to wp_enqueue_scripts.
		*/
	}
	add_action( 'wp', 'my_enqueue_scripts', 10 );
}
