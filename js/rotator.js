/**
 * jquery.cbpQTRotator.js v1.0.0
 * http://www.codrops.com
 *
 * Licensed under the MIT license.
 * http://www.opensource.org/licenses/mit-license.php
 *
 * Copyright 2013, Codrops
 * http://www.codrops.com
 */
;( function( $, window, undefined ) {

	'use strict';

	// Global
	var Modernizr = window.Modernizr,
		logError;

	$.CBPQTRotator = function( options, element ) {
		this.$el = $( element );
		this._init( options );
	};

	// The options
	$.CBPQTRotator.defaults = {
		speed: 700,     // Default transition speed (ms).
		easing: 'ease', // Default transition easing.
		interval: 8000  // Rotator interval (ms).
	};

	$.CBPQTRotator.prototype = {
		_init: function( options ) {

			// Options.
			this.options = $.extend( true, {}, $.CBPQTRotator.defaults, options );

			// Cache some elements and initialize some variables.
			this._config();

			// Show current item.
			this.$items.eq( this.current ).addClass( 'cbp-qtcurrent' );

			// Set the transition to the items.
			if ( this.support ) {
				this._setTransition();
			}

			// Start rotating the items.
			this._startRotator();

		},
		_config: function() {

			// The content items.
			this.$items = this.$el.children( 'div.cbp-qtcontent' );

			// Total items.
			this.itemsCount = this.$items.length;

			// Current item´s index.
			this.current = 0;

			// Support for CSS Transitions.
			this.support = Modernizr.csstransitions;

			// Add the progress bar.
			if ( this.support ) {
				this.$progress = $( '<span class="cbp-qtprogress"></span>' ).appendTo( this.$el );
			}

		},
		_setTransition: function() {
			setTimeout( $.proxy( function() {
				this.$items.css( 'transition', 'opacity ' + this.options.speed + 'ms ' + this.options.easing );
			}, this ), 25 );
		},
		_startRotator: function() {

			if ( this.support ) {
				this._startProgress();
			}

			setTimeout( $.proxy( function() {
				if ( this.support ) {
					this._resetProgress();
				}
				this._next();
				this._startRotator();
			}, this ), this.options.interval );

		},
		_next: function() {

			// Hide previous item.
			this.$items.eq( this.current ).removeClass( 'cbp-qtcurrent' );

			// Update current value.
			this.current = this.current < this.itemsCount - 1 ? this.current + 1 : 0;

			// Show next item.
			this.$items.eq( this.current ).addClass( 'cbp-qtcurrent' );

		},
		_startProgress: function() {

			setTimeout( $.proxy( function() {
				this.$progress.css( { transition: 'width ' + this.options.interval + 'ms linear', width: '100%' } );
			}, this ), 25 );

		},
		_resetProgress: function() {
			this.$progress.css( { transition: 'none', width: '0%' } );
		},
		destroy: function() {
			if ( this.support ) {
				this.$items.css( 'transition', 'none' );
				this.$progress.remove();
			}
			this.$items.removeClass( 'cbp-qtcurrent' ).css( {
				'position': 'relative',
				'z-index': 100,
				'pointer-events': 'auto',
				'opacity': 1
			} );
		}
	};

	logError = function( message ) {
		if ( window.console ) {
			window.console.error( message );
		}
	};

	$.fn.cbpQTRotator = function( options ) {

		var args;

		if ( 'string' === typeof options ) {
			args = Array.prototype.slice.call( arguments, 1 );
			this.each( function() {
				var instance = $.data( this, 'cbpQTRotator' );
				if ( ! instance ) {
					logError( 'cannot call methods on cbpQTRotator prior to initialization; ' +
					'attempted to call method "' + options + '"' );
					return;
				}
				if ( ! $.isFunction( instance[options] ) || '_' === options.charAt( 0 ) ) {
					logError( 'no such method "' + options + '" for cbpQTRotator instance' );
					return;
				}
				instance[ options ].apply( instance, args );
			} );
		} else {
			this.each( function() {
				var instance = $.data( this, 'cbpQTRotator' );
				if ( instance ) {
					instance._init();
				} else {
					instance = $.data( this, 'cbpQTRotator', new $.CBPQTRotator( options, this ) );
				}
			} );
		}
		return this;
	};

} ( jQuery, window ) );
jQuery( '.cbp-qtrotator' ).cbpQTRotator();
