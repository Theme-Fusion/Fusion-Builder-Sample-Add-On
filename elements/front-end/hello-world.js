/* global fusionAllElements */
var FusionPageBuilder = FusionPageBuilder || {};

( function() {

	jQuery( document ).ready( function() {

		// Hell world view
		FusionPageBuilder.hello_world = FusionPageBuilder.ElementView.extend( {

			/**
			 * Runs after element is rendered on load.
			 *
			 * @since 2.0
			 * @returns {void}
			 */
			onRender: function() {
			},

			/**
			 * Runs before element is removed.
			 *
			 * @since 2.0
			 * @returns {void}
			 */
			beforeRemove: function() {
			},

			/**
			 * Runs after view DOM is patched.
			 *
			 * @since 2.0
			 * @returns {void}
			 */
			beforePatch: function() {
			},

			/**
			 * Runs after view DOM is patched, eg after option change.
			 *
			 * @since 2.0
			 * @returns {void}
			 */
			afterPatch: function() {
			},

			/**
			 * Modify template attributes.
			 *
			 * @since 2.0
			 * @param {Object} atts - The attributes.
			 * @returns {Object}
			 */
			filterTemplateAtts: function( atts ) {

				// Variables we will pass to the template.
				var templateVariables = {}

				// Validate values.
				this.validateValues( atts.values );

				// Unique ID for this particular element instance, can be useful.
				templateVariables.cid = this.model.get( 'cid' );

				// Attributes for our wrapping element.
				templateVariables.wrapperAttributes = this.buildWrapperAtts( atts.values );
				templateVariables.mainContent       = atts.values.element_content;

				return templateVariables;
			},

			/**
			 * Modify the values, making sure they have correct units etc.
			 *
			 * @since 2.0
			 * @param {Object} values - The values.
			 * @returns {void}
			 */
			validateValues: function( values ) {

				// Note, atts.values is the combination of the defaults and the params.
			},

			/**
			 * Builds attributes.
			 *
			 * @since 2.0
			 * @param {Object} values - The values.
			 * @returns {Object}
			 */
			buildWrapperAtts: function( values ) {
				var wrapperAttributes = {
						style: 'background-color:' + values.background + '; color:' + values.color
					};

				return wrapperAttributes;
			}
		} );
	} );
} ( jQuery ) );
