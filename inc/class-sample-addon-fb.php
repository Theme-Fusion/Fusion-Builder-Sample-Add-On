<?php
/**
 * The main plugin class Sample_Addon_FB
 *
 * @since 1.2
 * @package Sample Addon for Fusion Builder
 */

/**
 * The main plugin class.
 */
class Sample_Addon_FB {

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
			self::$instance = new Sample_Addon_FB();
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
	 * @since 1.1
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
			echo '<style type="text/css">#error-page > p{display:-webkit-flex;display:flex;}#error-page img {height: 120px;margin-right:25px;}.fb-heading{font-size: 1.17em; font-weight: bold; display: block; margin-bottom: 15px;}.fb-link{display: inline-block;margin-top:15px;}.fb-link:focus{outline:none;box-shadow:none;}</style>';
			$message = '<span><span class="fb-heading">Sample Addon for Fusion Builder could not be activated</span>';
			$message .= '<span>Sample Addon for Fusion Builder can only be activated if Fusion Builder 1.0 or higher is activated. Click the link below to install/activate Fusion Builder, then you can activate this plugin.</span>';
			$message .= '<a class="fb-link" href="' . admin_url( 'admin.php?page=avada-plugins' ) . '">' . esc_attr__( 'Go to the Avada plugin installation page', 'Avada' ) . '</a></span>';
			wp_die( wp_kses_post( $message ) );
		} else {

			// Example of adding custom saved elements to the library on plugin activation.
			require_once wp_normalize_path( SAMPLE_ADDON_PLUGIN_DIR . '/saved-templates/saved-elements.php' );
		}
	}
}
