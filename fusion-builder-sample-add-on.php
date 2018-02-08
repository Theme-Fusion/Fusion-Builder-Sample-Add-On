<?php
/**
 * Plugin Name: Fusion Builder Sample Addon
 * Plugin URI: https://github.com/Theme-Fusion/Fusion-Builder-Sample-Add-On
 * Description: Adds quotes rotator element using this sample addon for fusion builder.
 * Version: 1.1
 * Author: ThemeFusion
 * Author URI: https://www.theme-fusion.com
 *
 * @package Sample Addon for Fusion Builder
 */

// Plugin Folder Path.
if ( ! defined( 'SAMPLE_ADDON_PLUGIN_DIR' ) ) {
	define( 'SAMPLE_ADDON_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}

register_activation_hook( __FILE__, array( 'Sample_Addon_FB', 'activation' ) );

if ( ! class_exists( 'Sample_Addon_FB' ) ) {

	// Include the main plugin class.
	include_once wp_normalize_path( SAMPLE_ADDON_PLUGIN_DIR . '/inc/class-sample-addon-fb.php' );

	// Instantiate Sample_Addon_FB class.
	function sample_addon_activate() {
		Sample_Addon_FB::get_instance();
	}

	add_action( 'wp_loaded', 'sample_addon_activate', 10 );
}

/**
 * Map shortcode to Fusion Builder.
 *
 * @since 1.0
 */
function map_sample_addon_with_fb() {

	// Map settings for parent shortcode.
	fusion_builder_map(
		array(
			'name'          => esc_attr__( 'Quotes Rotator ( Sample Addon )', 'fusion-builder' ),
			'shortcode'     => 'fusion_quotes',
			'multi'         => 'multi_element_parent',
			'element_child' => 'fusion_quote',
			'icon'          => 'fa fa-quote-left',
			'preview'       => SAMPLE_ADDON_PLUGIN_DIR . 'js/preview/sample-addon-preview.php',
			'preview_id'    => 'fusion-builder-block-module-sample-addon-preview-template',
			'params'        => array(
				array(
					'type'        => 'tinymce',
					'heading'     => esc_attr__( 'Content', 'fusion-builder' ),
					'description' => esc_attr__( 'Enter some content for this quote.', 'fusion-builder' ),
					'param_name'  => 'element_content',
					'value'       => '[fusion_quote title="Quote 1"]Your Content Goes Here[/fusion_quote]',
				),
				array(
					'type'          => 'colorpicker',
					'heading'       => __( 'Progress Bar Color', 'fusion-builder' ),
					'param_name'    => 'color_progress_bar',
					'value'         => '#47a3da',
					'description'   => __( 'Set the progress bar color.', 'fusion-builder' ),
				),
				array(
					'type'          => 'colorpicker',
					'heading'       => __( 'Quote Text Color', 'fusion-builder' ),
					'param_name'    => 'color_quote_text',
					'value'         => '#666666',
					'description'   => __( 'Set the quote text color.', 'fusion-builder' ),
					'group'       => esc_attr__( 'Design', 'fusion-builder' ),
				),
				array(
					'type'          => 'colorpicker',
					'heading'       => __( 'Quote Title Color', 'fusion-builder' ),
					'param_name'    => 'color_quote_title',
					'value'         => '#47a3da',
					'description'   => __( 'Set the quote title color.', 'fusion-builder' ),
					'group'       => esc_attr__( 'Design', 'fusion-builder' ),
				),
				array(
					'type'        => 'radio_image',
					'heading'     => esc_attr__( 'Background Pattern', 'fusion-builder' ),
					'description' => esc_attr__( 'Select the image to be used for background pattern.', 'fusion-builder' ),
					'param_name'  => 'bg_pattern',
					'default'     => '',
					'value'       => array(
						'pattern1'  => plugins_url( '/img/pattern1.png', __FILE__ ),
						'pattern2'  => plugins_url( '/img/pattern2.png', __FILE__ ),
						'pattern3'  => plugins_url( '/img/pattern3.png', __FILE__ ),
						'pattern4'  => plugins_url( '/img/pattern4.png', __FILE__ ),
						'pattern5'  => plugins_url( '/img/pattern5.png', __FILE__ ),
						'pattern6'  => plugins_url( '/img/pattern6.png', __FILE__ ),
						'pattern7'  => plugins_url( '/img/pattern7.png', __FILE__ ),
						'pattern8'  => plugins_url( '/img/pattern8.png', __FILE__ ),
						'pattern9'  => plugins_url( '/img/pattern9.png', __FILE__ ),
						'pattern10' => plugins_url( '/img/pattern10.png', __FILE__ ),
					),
				),
			),
		)
	);

	// Map settings for child shortcode.
	fusion_builder_map(
		array(
			'name'              => esc_attr__( 'Quote', 'fusion-builder' ),
			'shortcode'         => 'fusion_quote',
			'hide_from_builder' => true,
			'allow_generator'   => true,
			'params'            => array(
				array(
					'heading'     => __( 'Image', 'fusion-builder' ),
					'description' => __( 'Upload the image you would like to use for this quote.', 'fusion-builder' ),
					'value'       => '',
					'type'        => 'upload',
					'param_name'  => 'image',
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Quote Title', 'fusion-builder' ),
					'description' => esc_attr__( 'Title of the quote.', 'fusion-builder' ),
					'param_name'  => 'title',
					'value'       => 'Your Content Goes Here',
					'placeholder' => true,
				),
				array(
					'type'        => 'tinymce',
					'heading'     => esc_attr__( 'Quote Content', 'fusion-builder' ),
					'description' => esc_attr__( 'Add content for the quote.', 'fusion-builder' ),
					'param_name'  => 'element_content',
					'value'       => 'Your Content Goes Here',
					'placeholder' => true,
				),
			),
		)
	);

	// Example of how to add or modify options to existing element in Fusion Builder.
	if ( function_exists( 'fusion_builder_update_element' ) ) {
		fusion_builder_update_element( 'fusion_button', 'color', array( 'cyan' => esc_attr__( 'New - Cyan', 'fusion-builder' ) ) );
		fusion_builder_update_element( 'fusion_button', 'color', array( 'black' => esc_attr__( 'New - Black', 'fusion-builder' ) ) );
		fusion_builder_update_element( 'fusion_button', 'element_content', 'Sample Button' );
	}

}

add_action( 'fusion_builder_before_init', 'map_sample_addon_with_fb', 11 );

/**
 * Include options from options folder.
 *
 * @access public
 * @since 1.1
 * @return void
 */
function fusion_init_sample_options() {

	// Early exit if the Fusion_Element class does not exist.
	if ( ! class_exists( 'Fusion_Element' ) ) {
		return;
	}

	// Include the file.
	require_once 'options/class-sample-element-options.php';

	// Instantiate the object.
	new Sample_Element_Options();
}

add_action( 'fusion_builder_shortcodes_init', 'fusion_init_sample_options', 1 );
