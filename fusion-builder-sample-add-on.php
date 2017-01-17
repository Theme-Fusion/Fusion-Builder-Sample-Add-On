<?php
/**
 * Plugin Name: Fusion Builder Sample Addon
 * Plugin URI: https://github.com/Theme-Fusion/Fusion-Builder-Sample-Add-On
 * Description: Adds quotes rotator element using this sample addon for fusion builder.
 * Version: 1.0
 * Author: ThemeFusion
 * Author URI: https://www.theme-fusion.com
 *
 * @package Sample Addon for Fusion Builder
 */

// Plugin Folder Path.
if ( ! defined( 'SAMPLE_ADDON_PLUGIN_DIR' ) ) {
	define( 'SAMPLE_ADDON_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}

register_activation_hook( __FILE__, array( 'SampleAddonFB', 'activation' ) );

if ( ! class_exists( 'SampleAddonFB' ) ) {

	/**
	 * The main plugin class.
	 */
	class SampleAddonFB {

		/**
		 * The one, true instance of this object.
		 *
		 * @static
		 * @access private
		 * @since 1.0
		 * @var object
		 */
		private static $instance;

		/**
		 * Creates or returns an instance of this class.
		 *
		 * @static
		 * @access public
		 * @since 1.0
		 */
		public static function get_instance() {

			// If an instance hasn't been created and set to $instance create an instance and set it to $instance.
			if ( null === self::$instance ) {
				self::$instance = new SampleAddonFB();
			}
			return self::$instance;
		}

		/**
		 * Constructor.
		 *
		 * @since 1.0
		 */
		public function __construct() {

			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

			add_shortcode( 'fusion_quotes', array( $this, 'fusion_quotes' ) );
			add_shortcode( 'fusion_quote', array( $this, 'fusion_quote' ) );

			// Add new settings field to Fusion Builder.
			add_filter( 'fusion_builder_fields', array( $this, 'add_new_field' ) );

		}

		/**
		 * Add new radio_image setting field to Fusion Builder.
		 *
		 * @access public
		 * @since 1.0
		 * @param array $fields The array of fields added with filter.
		 * @return array
		 */
		public function add_new_field( $fields ) {
			$fields[] = array( 'radio_image', SAMPLE_ADDON_PLUGIN_DIR . 'fields/radio_image.php' );
			return $fields;
		}

		/**
		 * Enqueue scripts & styles.
		 *
		 * @access public
		 * @since 1.0
		 */
		public function enqueue_scripts() {

			wp_enqueue_script( 'modernizr-js', plugins_url( 'js/modernizr.custom.js', __FILE__ ) );
			wp_enqueue_script( 'rotator-js', plugins_url( 'js/rotator.js', __FILE__ ), array( 'jquery' ), '', true );
			wp_enqueue_style( 'rotator-css', plugins_url( 'css/rotator.css', __FILE__ ) );

		}

		/**
		 * Returns the content.
		 *
		 * @access public
		 * @since 1.0
		 * @param array  $atts    The attributes array.
		 * @param string $content The content.
		 * @return string
		 */
		public function fusion_quotes( $atts, $content ) {

			$unique_class = 'cbp-' . rand();
			$html = '<style type="text/css">';
			$html .= '.' . $unique_class . ' .cbp-qtprogress { background-color: ' . $atts['color_progress_bar'] . '; }';
			$html .= '.' . $unique_class . ' footer { color: ' . $atts['color_quote_title'] . '; }';
			$html .= '.' . $unique_class . ' .blockquote p { color: ' . $atts['color_quote_text'] . '; }';
			if ( isset( $atts['bg_pattern'] ) && '' !== $atts['bg_pattern'] ) {
				$html .= '.' . $unique_class . '.cbp-qtrotator { background: url(' . plugins_url( '\/img/' . $atts['bg_pattern'] . '.png', __FILE__ ) . '); }';
				$html .= '.' . $unique_class . '.cbp-qtrotator .cbp-qtcontent { padding-left: 15px; padding-right: 15px; }';
			}
			$html .= '</style>';
			$html .= '<div class="cbp-qtrotator ' . $unique_class . '">';
			$html .= do_shortcode( $content );
			$html .= '</div>';

			return $html;
		}

		/**
		 * Returns the content.
		 *
		 * @access public
		 * @since 1.0
		 * @param array  $atts    The attributes array.
		 * @param string $content The content.
		 * @return string
		 */
		public function fusion_quote( $atts, $content ) {

			$html = '<div class="cbp-qtcontent">';
			$html .= '<img src="' . $atts['image'] . '" />';
			$html .= '<div class="blockquote">';
			$html .= do_shortcode( $content );
			$html .= '<footer>' . $atts['title'] . '</footer>';
			$html .= '</div>';
			$html .= '</div>';

			return $html;

		}

		/**
		 * Processes that must run when the plugin is activated.
		 *
		 * @static
		 * @access public
		 * @since 1.0
		 */
		public static function activation() {
			if ( ! class_exists( 'FusionBuilder' ) ) {
				$message = '<style>#error-page > p{display:-webkit-flex;display:flex;}#error-page img {height: 120px;margin-right:25px;}.fb-heading{font-size: 1.17em; font-weight: bold; display: block; margin-bottom: 15px;}.fb-link{display: inline-block;margin-top:15px;}.fb-link:focus{outline:none;box-shadow:none;}</style>';
				$message .= '<span><span class="fb-heading">Sample Addon for Fusion Builder could not be activated</span>';
				$message .= '<span>Sample Addon for Fusion Builder can only be activated if Fusion Builder 1.0 or higher is activated. Click the link below to install/activate Fusion Builder, then you can activate this plugin.</span>';
				$message .= '<a class="fb-link" href="' . admin_url( 'admin.php?page=avada-plugins' ) . '">' . esc_attr__( 'Go to the Avada plugin installation page', 'Avada' ) . '</a></span>';
				wp_die( wp_kses_post( $message ) );
			} else {
				// Example of adding custom saved elements to the library on plugin activation.
				require_once( 'saved-templates/saved-elements.php' );
			}
		}
	}

	/**
	 * Instantiate SampleAddonFB class.
	 */
	function sample_addon_activate() {
		SampleAddonFB::get_instance();
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
					'heading'    	=> __( 'Progress Bar Color', 'fusion-builder' ),
					'param_name'    => 'color_progress_bar',
					'value'         => '#47a3da',
					'description'   => __( 'Set the progress bar color.', 'fusion-builder' ),
				),
				array(
					'type'          => 'colorpicker',
					'heading'    	=> __( 'Quote Text Color', 'fusion-builder' ),
					'param_name'    => 'color_quote_text',
					'value'         => '#666666',
					'description'   => __( 'Set the quote text color.', 'fusion-builder' ),
				),
				array(
					'type'          => 'colorpicker',
					'heading'    	=> __( 'Quote Title Color', 'fusion-builder' ),
					'param_name'    => 'color_quote_title',
					'value'         => '#47a3da',
					'description'   => __( 'Set the quote title color.', 'fusion-builder' ),
				),
				array(
					'type'        => 'radio_image',
					'heading'     => esc_attr__( 'Background Pattern', 'fusion-builder' ),
					'description' => esc_attr__( 'Select the image to be used for background pattern.', 'fusion-builder' ),
					'param_name'  => 'bg_pattern',
					'default'     => '',
					'value'       => array(
						'pattern1' 	=> plugins_url( '/img/pattern1.png', __FILE__ ),
						'pattern2' 	=> plugins_url( '/img/pattern2.png', __FILE__ ),
						'pattern3' 	=> plugins_url( '/img/pattern3.png', __FILE__ ),
						'pattern4' 	=> plugins_url( '/img/pattern4.png', __FILE__ ),
						'pattern5' 	=> plugins_url( '/img/pattern5.png', __FILE__ ),
						'pattern6' 	=> plugins_url( '/img/pattern6.png', __FILE__ ),
						'pattern7' 	=> plugins_url( '/img/pattern7.png', __FILE__ ),
						'pattern8' 	=> plugins_url( '/img/pattern8.png', __FILE__ ),
						'pattern9' 	=> plugins_url( '/img/pattern9.png', __FILE__ ),
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
