# Fusion Builder Front-End Sample Add-On
This is a simple example of a front-end editable element.


# WIP API details.
This is a general guide on extending an existing FB element to function on the front-end.

# The PHP
## Making the defaults available
A new function needs added to the element class - ``get_element_defaults``.  This should return an array of the element defaults.  To avoid duplication, the render function of the element should also use this.  For example:
```php
$defaults = FusionBuilder::set_shortcode_defaults( self::get_element_defaults(), $args );
```

For regular elements the function should be in the following format:
```php
public static function get_element_defaults() {

    $fusion_settings = fusion_get_fusion_settings();

    return array(
        'parameter'  => $fusion_settings->get( 'value' ),
        'parameter_2' => 'something',
    );
}
```

For parent/child elements this requires an extra $context variable.  If not passed, both the parent and child defaults should be returned.  If it is set, only the appropriate array of defaults should be returned.  For example:
```php
public static function get_element_defaults( $context = '' ) {

	$fusion_settings = fusion_get_fusion_settings();

	$parent = array(
		'paremeter'             => 'value',
	);

	$child = array(
		'paremeter'             => 'value',
	);

	if ( 'parent' === $context ) {
		return $parent;
	} elseif ( 'child' === $context ) {
		return $child;
	} else {
		return array(
			'parent' => $parent,
			'child'  => $child,
		);
	}
}
```


## Making extra variables available
Similar to defaults, some elements will need extra values passed on for use in a template.  These are things which  are not element defaults but can change and are used in the render.  For example, the portfolio element render function uses the button_shape value which is set in the Theme Options.  So this needs passed on for the template.  This is done using a function in the same format as the defaults:

```php
public static function get_element_extras() {
	$fusion_settings = fusion_get_fusion_settings();
	return array(
		'button_shape' => strtolower( $fusion_settings->get( 'button_shape' ) ),
	);
}
```

## Updating defaults when a Theme Option changes
Using the button element as an example.  The button has a parameter - "size", the default for which is the lower case value of the button size set in the Theme Options ``strtolower( $fusion_settings->get( 'button_size' ) )``.  If you are on the front-end builder and have a button which does not have a size set (its using the default value), the template will be using the value set in get_element_defaults.  However, these values can be changed.  If a user changes the Theme Options on the front-end, the button size default will change and the button element needs to update to take this into account.

This is done by using a function ``settings_to_params``.  Here we return an array linking Theme Options to element parameters.  We can also include a callback if it not a direct swap.  For the button example that means:

```php
public static function settings_to_params() {
	return array(
		'button_size'                        => array(
			'param'    => 'size',
			'callback' => 'toLowerCase',
		),
	);
}
```

This ensures that if the button_size Theme Option is changed, the default size parameter for buttons will become the lowercase of the new value.  It also triggers button elements to re-render so that this change becomes visible.  Finally, for color picker and slider options it also ensures the default value updates.  If you were to change the button top gradient color in the Theme Option and then edit a button element, the preview color for the default top gradient color should change to reflect the new value.

## Updating extras when a Theme Option changes
This is done in the same format as the above except with the function name ``settings_to_extras``.  In the case of our earlier portfolio example, that would be:
```php
public static function settings_to_extras() {

	return array(
		'button_shape'                  => 'button_shape',
	);
}
```

## Making all of this available to the front-end
To make all this extra data available, it needs added to the Fusion Builder map.  The easiest way to do that is to use the helper function ``fusion_builder_frontend_data``.  The parameters for which are:
```php
* @param  string $class_name class for shortcode.
* @param  array  $map     Array map for shortcode.
* @param  string $context Parent or child level.
```

For example, the button element becomes:
```php
fusion_builder_map( fusion_builder_frontend_data(
	'FusionSC_Button', array(
		'name'       => esc_attr__( 'Button', 'fusion-builder' ),
		'shortcode'  => 'fusion_button',
		'icon'       => 'fusiona-check-empty',
		'preview'    => FUSION_BUILDER_PLUGIN_DIR . 'inc/templates/previews/fusion-button-preview.php',
		'preview_id' => 'fusion-builder-block-module-button-preview-template',
		'params'     => array(
			array(
				'type'        => 'link_selector',
				'heading'     => esc_attr__( 'Button URL', 'fusion-builder' ),
				'param_name'  => 'link',
				'value'       => '',
				'description' => esc_attr__( "Add the button's url ex: http://example.com.", 'fusion-builder' ),
			),
		),
	)
) );
```
## Settings a front-end template path
This is added in the same way as the back-end template path.  Instead of adding "preview" to the builder map, this  is currently added as "front-end".  For example:
```php
array(
	'name'       => esc_attr__( 'Portfolio', 'fusion-core' ),
	'shortcode'  => 'fusion_portfolio',
	'icon'       => 'fusiona-insertpicture',
	'preview'    => FUSION_CORE_PATH . '/shortcodes/previews/fusion-portfolio-preview.php',
	'preview_id' => 'fusion-builder-block-module-portfolio-preview-template',
	'front-end'  => FUSION_CORE_PATH . '/shortcodes/previews/front-end/fusion-portfolio.php',
	'params'     => array(),
);
```

## Adding callbacks to an element or specific option
You can add callbacks to an element or only specific options of an element.  If added to an element, this will trigger on first-edit.  This is particularly useful for element which require extra data on first change.  For example, they need post information/images to be retrieved.

An example of this would be the portfolio element which uses the ``fusion_ajax`` callback on first change (more information about that later).  Example of how this is defined:
```php
fusion_builder_map( fusion_builder_frontend_data(
	'FusionSC_Portfolio', array(
		'name'       => esc_attr__( 'Portfolio', 'fusion-core' ),
		'shortcode'  => 'fusion_portfolio',
		'icon'       => 'fusiona-insertpicture',
		'preview'    => FUSION_CORE_PATH . '/shortcodes/previews/fusion-portfolio-preview.php',
		'preview_id' => 'fusion-builder-block-module-portfolio-preview-template',
		'front-end'  => FUSION_CORE_PATH . '/shortcodes/previews/front-end/fusion-portfolio.php',
		'params'     => array(),
		'callback' => array(
			'function' => 'fusion_ajax',
			'action' => 'get_fusion_portfolio',
			'ajax'     => true,
		),
	)
) );
```

Adding a callback to an option means whenever this option changes the callback will be fired.  This can be used for ajax callback such as the above.  For example if changing the number of portfolio posts an ajax callback can be added to make sure these new posts are fetched.  It can also be used for non-ajax callbacks.

An example of that is the container background color.  To avoid re-rendering the entire container on color change we can instead use a callback to target a specific element and update a property.  For example:
```php
array(
	'type'        => 'colorpickeralpha',
	'heading'     => esc_attr__( 'Container Background Color', 'fusion-builder' ),
	'param_name'  => 'background_color',
	'value'       => '',
	'description' => esc_attr__( 'Controls the background color of the container element.', 'fusion-builder' ),
	'group'       => esc_attr__( 'Background', 'fusion-builder' ),
	'default'     => $fusion_settings->get( 'full_width_bg_color' ),
	'callback'    => array(
		'function'  => 'fusion_preview',
		'args'      => array(
			'selector' => '.fusion-fullwidth',
			'property' => 'background-color',
		),
	),
),
```

## Returning query data
If you have added an ajax callback using the ``fusion_ajax`` function, you also need to add a PHP function to return the data you need.  This then needs to be tied to the action defined in the callback, for example ``get_fusion_portfolio``.

### Add the action
To the constructor of the class we can add the action for the ajax and make it fire a ``query`` function:
```php
add_action( 'wp_ajax_nopriv_get_fusion_portfolio', array( $this, 'query' ) );
add_action( 'wp_ajax_get_fusion_portfolio', array( $this, 'query' ) );
```

### Writing the query function
The first thing is to try to avoid duplication where possible.  The means its often best to copy all the query related code from the current render function and move it to the query function.  Then this can be referenced from the render function which is used for regular front-end load.

As a simplified example that would be in the following format.  From the render function:
```php
$portfolio_query = $this->query();
```

This is then used as normal.

For the query function itself, this can check if the request is coming from ajax or not.  If it is not we return the query early and render function uses that as normal.  If it is we use the query to construct whatever data we need for the template.  Example:
```php
public function query() {
	$live_request = false;

	// If set this is ajax request.
	if ( isset( $_POST['model'] ) ) {
		$return_data  = array();
		$live_request = true;
	}

	// Initialize the query array.
	$args = array(
		'post_type'      => 'avada_portfolio',
	);

	$portfolio_query = FusionCore_Plugin::fusion_core_cached_query( apply_filters( 'fusion_portfolio_query_args', $args ) );

	// Not live request, return the query only.
	if ( ! $live_request ) {
		return $portfolio_query;
	}

	if ( $portfolio_query->have_posts() ) {
		while ( $portfolio_query->have_posts() ) {
			$portfolio_query->the_post();
			$return_data['portfolios'][] = array(
				'title'                 => get_the_title(),
			);
		}
		wp_reset_postdata();
	}
	echo wp_json_encode( $return_data );
	die();
}
```
In the above example there is simple query for portfolio post types.  If it is not a live request (coming from ajax) we just return the query.  If it is a live request we fetch the post type for each and return that for use in the template.  In the above example each portfolio post can be used in the underscore template like so:
```js
_.each( query_data.portfolios, function( portfolio ) {
    portfolio.title
});
```

Important part to note is that the data is always accessible from the variable query_data if using the fusion_ajax callback.

## Changing an element tag on front-end
This is particularly necessary for child elements.  For example, in order for the styling of the checklist items to be correct, the element need to be a li item not a div (div is standard).  To change that the builder map needs the addition of:
```php
    'tag_name' => 'li',
```

That is all for the PHP portion of creating a front-end element.  The next step is the creation of the underscore template which is used to render the element.

# The JS
## Custom JS View
So this is when the good stuff happens.  The approach is pretty simple.  All the variables and data for an element are collected.  The attributes and validation are performed.  Then the resulting variables are passed to an underscore template to render.  So for you element to work, you will combine a custom view with an underscore template.


### Other variables
This was also covered in the PHP section.  If you are using the ``fusion_ajax`` callback the query data can be accessed with the variable - **query_data**.

Another useful variable is - **cid**.  This is the unique ID for the element.  So if your element involves CSS blocks for that specific element only the cid variable can be used to make sure only the specific element is targeted.  For example:
```js
styling = '<style type="text/css">.fusion-portfolio-wrapper#fusion-portfolio-' + cid + ' .fusion-portfolio-content{ background: red }</style>';
```

### Helper functions
This was touched on earlier.  There are many helper functions which can be used for things that use functions in PHP. This can also be extended to add in your own template helpers.  As an example:
```js
 _.fusionValidateAttrValue( values.margin_top, 'px' );
```

Is the JS equivalent of:
```php
FusionBuilder::validate_shortcode_attr_value( $defaults['margin_top'], 'px' );
```

## Creating the underscore template
### Template naming
The template should be added in the following format:
```html
<script type="text/html" id="tmpl-SHORTCODE_NAME-shortcode"></script>
```

So in the case of the fusion_highlight shortcode, the ID for the script tag would be ``tmpl-fusion_highlight-shortcode``.  This takes the element variables, attributes and anything you create in the view and renders them to the screen.  So you can mix in HTML, JS variables and even PHP here.

### General underscore syntax
Opening and closing for JS code are <# and #>

For rendering variables, this is done using {{ variable }}
For rendering HTML contents, this is done using {{{ variable }}}

### Parent/child templates
For parent and child elements, each has a separate template.  Each child element is rendered and then added inside the parent element.  The location for where the children are added is determined by the class.  To make sure the elements are added inside a specific tag, the CSS class fusion-child-element should be added.

If you require access to the parent element values from the child template you can do so by using a variable - "parent".  For example:
```js
parentModel = FusionPageBuilderElements.find( function( model ) {
	return model.get( 'cid' ) == parent;
} );

parentValues = jQuery.extend( true, {}, fusionAllElements.fusion_flip_boxes.defaults, FusionApp.templateHelper.cleanParameters( parentModel.get( 'params' ) ) )
```

You can also change the selectors/styling of the child element wrapping element from within the child template itself.  For example:
```js
parentSelectors = {
	class: 'fusion-li-item'
};
thisModel = parentModel.children.models.find( function( model ) {
	return model.get( 'cid' ) == cid;
} );
thisModel.set( 'selectors', parentSelectors );
```

This can be necessary for the markup to be equal to the regular page load.  It is also usually used on combination with the tag_name change (see PHP section).  For example, the above JS plus the tag_name change to li ensures that the markup becomes:
```html
<ul class="fusion-checklist">
	<li class="fusion-builder-live-child-element fusion-builder-data-cid fusion-li-item" data-cid="123" data-parent-cid="121">
		<div class="fusion-builder-module-controls-container"> Editing controls here</div>
		<div class="fusion-builder-child-element-content"> Content Here</div>
	</li>
</ul>
```

With the list item as a direct child of the unordered list.

## JS events for render update
Once you have your template and PHP set-up you may find that on initial load the render is correct but when you change an option extra JS needs to run.  For example, you have a countdown element but when you change the number the JS init for the countdown mechanism needs to fire again.  Otherwise you just end up with the number stuck at 0.

This can be re-triggered by using an event as a listener.  For example, say you have the following code for your shortcode right now:
```js
jQuery( window ).on( 'load', function( $ ) {
	jQuery( '.fusion-counter-box' ).not( '.fusion-modal .fusion-counter-box' ).each( function() {
		var $offset = getWaypointOffset( jQuery( this ) );

		jQuery( this ).waypoint( function() {
			jQuery( this ).find( '.display-counter' ).each( function() {
				jQuery( this ).$fusionBoxCounting();
			});
		}, {
			triggerOnce: true,
			offset: $offset
		});
	});
});
```

When the window loads you want to trigger the countdown mechanism for each countdown element on the page.  Rather than writing completely new JS and duplicating this, we can add an extra listener and an extra variable check:

```js
jQuery( window ).on( 'load fusion-element-render-fusion_counters_box', function( $, cid ) {
	var $targetEl = 'undefined' !== typeof cid ? jQuery( 'div[data-cid="' + cid + '"]' ).find( '.fusion-counter-box' ).not( '.fusion-modal .fusion-counter-box' ) : jQuery( '.fusion-counter-box' ).not( '.fusion-modal .fusion-counter-box' );

	$targetEl.each( function() {
		var $offset = getWaypointOffset( jQuery( this ) );

		jQuery( this ).waypoint( function() {
			jQuery( this ).find( '.display-counter' ).each( function() {
				jQuery( this ).$fusionBoxCounting();
			});
		}, {
			triggerOnce: true,
			offset: $offset
		});
	});
});
```

On regular page load the window load still fires.  Since the cid variable will not be set the $targetEl is set to the identical selector as before.  So this works in the same way for regular load.

The additional part however is that when the counter box element is re-rendered in the builder it will fire ``fusion-element-render-fusion_counters_box`` along with a cid.  Since the cid variable is set this time it will set $targetEl to:
```js
jQuery( 'div[data-cid="' + cid + '"]' ).find( '.fusion-counter-box' ).not( '.fusion-modal .fusion-counter-box' );
```

Which means it will only target one specific element in the builder (the one we have re-rendered).  This means you don't need to duplicate lots of JS, regular page load still fires as before and on re-render the element will work correctly.

The event is written in the format - fusion-element-render-SHORTCODE_NAME


