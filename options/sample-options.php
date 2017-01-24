<?php
if ( class_exists( 'Fusion_Element' ) ) {

  /**
   * Sample options class.
   *
   * @package Fusion-Builder-Sample-Add-On
   * @since 1.1
   */
  class SampleElementOptions extends Fusion_Element {

    /**
     * Constructor.
     *
     * @access public
     * @since 1.1
     */
    public function __construct() {
      parent::__construct();
    }

    /**
     * Adds settings to element options panel.
     *
     * @access public
     * @since 1.1
     * @return array $sections Sample settings.
     */
    public function add_options() {
      return array(
        'sample_shortcode_section' => array(
          'label'       => esc_html__( 'Sample Element', 'fusion-builder' ),
          'description' => '',
          'id'          => 'sample_shortcode_section',
          'type'        => 'sub-section',
          'fields'      => array(
            'sample_dates_box_color' => array(
              'label'       => esc_html__( 'Sample Color Picker', 'fusion-builder' ),
              'description' => esc_html__( 'This is just an example of color picker setting field.', 'fusion-builder' ),
              'id'          => 'sample_dates_box_color',
              'default'     => '#eef0f2',
              'type'        => 'color-alpha',
            ),
            'dropdown_field' => array(
              'label'       => esc_html__( 'Sample Dropdown Field', 'fusion-builder' ),
              'description' => esc_html__( 'This is just an example of drpdown setting field.', 'fusion-builder' ),
              'id'          => 'dropdown_field',
              'default'     => '1',
              'type'        => 'select',
              'choices'     => array(
                '1' => esc_html__( 'Option 1', 'fusion-builder' ),
                '2' => esc_html__( 'Option 2 with "Quotes"', 'fusion-builder' ),
                '3' => esc_html__( 'Option 3', 'fusion-builder' ),
              ),
            ),
          ),
        ),
      );
    }
  }
  new SampleElementOptions;
}
