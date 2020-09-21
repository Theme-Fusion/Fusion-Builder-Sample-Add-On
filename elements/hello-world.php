<?php
/**
 * Add an element to fusion-builder.
 *
 * @package fusion-builder
 * @since 1.0
 */

if ( fusion_is_element_enabled( 'hello_world' ) ) {

	if ( ! class_exists( 'MyHelloWorld' ) && class_exists( 'Fusion_Element' ) ) {
		/**
		 * Shortcode class.
		 *
		 * @since 1.0
		 */
		class MyHelloWorld extends Fusion_Element {

			/**
			 * An array of the shortcode arguments.
			 *
			 * @access protected
			 * @since 1.0
			 * @var array
			 */
			protected $args;

			/**
			 * Constructor.
			 *
			 * @access public
			 * @since 1.0
			 */
			public function __construct() {
				parent::__construct();
				add_filter( 'fusion_attr_hello-main-wrapper', [ $this, 'attr' ] );
				add_shortcode( 'hello_world', [ $this, 'render' ] );
			}

			/**
			 * Gets the default values.
			 *
			 * @static
			 * @access public
			 * @since 2.0.0
			 * @return array
			 */
			public static function get_element_defaults() {
				$fusion_settings = fusion_get_fusion_settings();

				return [
					'color'      => $fusion_settings->get( 'hello_color' ),
					'background' => $fusion_settings->get( 'hello_background' ),
				];
			}

			/**
			 * Maps settings to param variables.
			 *
			 * @static
			 * @access public
			 * @since 2.0.0
			 * @return array
			 */
			public static function settings_to_params() {
				return [
					'hello_color'      => 'color',
					'hello_background' => 'background',
				];
			}

			/**
			 * Used to set any other variables for use on front-end editor template.
			 *
			 * @static
			 * @access public
			 * @since 2.0.0
			 * @return array
			 */
			public static function get_element_extras() {
				return [];
			}

			/**
			 * Maps settings to extra variables.
			 *
			 * @static
			 * @access public
			 * @since 2.0.0
			 * @return array
			 */
			public static function settings_to_extras() {
				return [];
			}

			/**
			 * Render the shortcode
			 *
			 * @access public
			 * @since 1.0
			 * @param  array  $args    Shortcode parameters.
			 * @param  string $content Content between shortcode.
			 * @return string          HTML output.
			 */
			public function render( $args, $content = '' ) {

				$defaults   = FusionBuilder::set_shortcode_defaults( self::get_element_defaults(), $args, 'hello_world' );
				$this->args = $defaults;

				$html  = '<div ' . FusionBuilder::attributes( 'hello-main-wrapper' ) . '>';
				$html .= wpautop( $content, false );
				$html .= '</div>';

				return $html;

			}

			/**
			 * Builds the attributes array.
			 *
			 * @access public
			 * @since 1.0
			 * @return array
			 */
			public function attr() {
				$attr = [
					'class' => 'my-hello-world',
					'style' => 'color: ' . $this->args['color'] . '; background-color:' . $this->args['background'],
				];

				return $attr;
			}

			/**
			 * Adds settings to element options panel.
			 *
			 * @access public
			 * @since 1.1.6
			 * @return array $sections Blog settings.
			 */
			public function add_options() {
				return [
					'hello_world_shortcode_section' => [
						'label'       => esc_attr__( 'Hello World', 'hello-world' ),
						'description' => '',
						'id'          => 'hello_world_shortcode_section',
						'default'     => '',
						'icon'        => 'fusiona-exclamation-triangle',
						'type'        => 'accordion',
						'fields'      => [
							'hello_color' => [
								'label'       => esc_attr__( 'Hello World Text Color', 'hello-world' ),
								'description' => esc_attr__( 'Set the text color global for hello world.', 'hello-world' ),
								'id'          => 'hello_color',
								'default'     => '#333333',
								'type'        => 'color-alpha',
								'transport'   => 'postMessage',
							],
							'hello_background' => [
								'label'       => esc_attr__( 'Hello World Background Color', 'hello-world' ),
								'description' => esc_attr__( 'Set the background color global for hello world.', 'hello-world' ),
								'id'          => 'hello_background',
								'default'     => '#ffa737',
								'type'        => 'color-alpha',
								'transport'   => 'postMessage',
							],
						],
					],
				];
			}

			/**
			 * Sets the necessary scripts.
			 *
			 * @access public
			 * @since 1.1
			 * @return void
			 */
			public function add_scripts() {

				/* For example.
				Fusion_Dynamic_JS::enqueue_script(
					'fusion-date-picker',
					FUSION_BUILDER_PLUGIN_URL . 'assets/js/library/flatpickr.js',
					FUSION_BUILDER_PLUGIN_URL . 'assets/js/library/flatpickr.js',
					[ 'jquery' ],
					'1',
					true
				);
				*/
			}

			/**
			 * Load element base CSS.
			 *
			 * @access public
			 * @since 3.0
			 * @return void
			 */
			public function add_css_files() {
				FusionBuilder()->add_element_css( SAMPLE_ADDON_PLUGIN_DIR . 'css/my-elements.css' );
			}
		}
	}

	new MyHelloWorld();
}

/**
 * Map shortcode to Fusion Builder
 *
 * @since 1.0
 */
function hello_world_map() {

	$fusion_settings = fusion_get_fusion_settings();

	fusion_builder_map(
		fusion_builder_frontend_data(

			// Class reference.
			'MyHelloWorld',
			[
				'name'                     => esc_attr__( 'Hello World', 'hello-world' ),
				'shortcode'                => 'hello_world',
				'icon'                     => 'fusiona-exclamation-triangle',

				// View used on front-end.
				'front_end_custom_settings_view_js' => SAMPLE_ADDON_PLUGIN_URL . 'elements/front-end/hello-world.js',

				// Template that is used on front-end.
				'front-end'                         => SAMPLE_ADDON_PLUGIN_DIR . '/elements/front-end/hello-world.php',

				'allow_generator'          => false,

				// Allows inline editor.
				'inline_editor'            => true,
				'inline_editor_shortcodes' => true,

				'params'                   => [
					[
						'type'        => 'tinymce',
						'heading'     => esc_attr__( 'Content', 'hello-world' ),
						'description' => esc_attr__( 'Enter some content for this textblock.', 'hello-world' ),
						'param_name'  => 'element_content',
						'value'       => esc_attr__( 'Click edit button to change this text.', 'hello-world' ),
						'placeholder' => true,
					],
					[
						'type'        => 'colorpickeralpha',
						'heading'     => esc_attr__( 'Text Color', 'hello-world' ),
						'description' => esc_attr__( 'Set the text color for the hello.', 'hello-world' ),
						'param_name'  => 'color',
						'default'     => $fusion_settings->get( 'hello_color' ) ? $fusion_settings->get( 'hello_color' ) : '#fff',
					],
					[
						'type'        => 'colorpickeralpha',
						'heading'     => esc_attr__( 'Background Color', 'hello-world' ),
						'description' => esc_attr__( 'Set the background color for the hello.', 'hello-world' ),
						'param_name'  => 'background',
						'default'     => $fusion_settings->get( 'hello_background' ) ? $fusion_settings->get( 'hello_background' ) : '#333',
					],
				],
			]
		)
	);
}
add_action( 'fusion_builder_before_init', 'hello_world_map' );
