<script type="text/html" id="tmpl-hello_world-shortcode">
	<div {{{ _.fusionGetAttributes( wrapperAttributes ) }}}>
		{{{ FusionPageBuilderApp.renderContent( mainContent, cid, false ) }}}
	</div>
	<# if ( repeaterBoxes ) { #>
		<div style="display: grid; grid-template-columns: 1fr 1fr 1fr;">
			<# _.each( repeaterBoxes, function( box ) { #>
			<div>
				<h3>{{{ box.box_title }}}</h3>
				<p>{{{ box.box_description }}}</p>
			</div>
			<# } ); #>
		</div>
	<# } #>
</script>
