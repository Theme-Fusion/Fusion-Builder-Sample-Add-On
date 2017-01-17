<div class="fusion-form-radio-button-set ui-buttonset test-custom-param {{ param.param_name }}">
	<# var choice = option_value, index = 0; #>
	<input type="hidden" id="{{ param.param_name }}" name="{{ param.param_name }}" value="{{ choice }}" class="button-set-value" />
	<# _.each( param.value, function( src, value ) { #>
		<# index++; #>
		<# var selected = ( value == choice ) ? ' ui-state-active' : ''; #>
		<a href="#" class="ui-button buttonset-item{{ selected }}" data-value="{{ value }}" style="padding: 3px 3px 0px; margin-right: 4px; margin-bottom: 4px;"><img src="{{ src }}" style="width: 32px; height: 32px;"/></a>
	<# } ); #>
</div>
