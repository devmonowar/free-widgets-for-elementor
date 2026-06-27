/**
 * Free Widgets For Elementor — Accordion.
 * Accessible toggle with keyboard support (Enter/Space native; Arrow/Home/End).
 */
( function () {
	'use strict';

	function setup( accordion ) {
		if ( ! accordion || accordion.getAttribute( 'data-fwfe-done' ) ) {
			return;
		}
		accordion.setAttribute( 'data-fwfe-done', '1' );

		var triggers = Array.prototype.slice.call(
			accordion.querySelectorAll( '.fwfe-accordion__trigger' )
		);

		function toggle( trigger ) {
			var expanded = 'true' === trigger.getAttribute( 'aria-expanded' );
			var panel = document.getElementById( trigger.getAttribute( 'aria-controls' ) );
			trigger.setAttribute( 'aria-expanded', expanded ? 'false' : 'true' );
			if ( panel ) {
				if ( expanded ) {
					panel.setAttribute( 'hidden', '' );
				} else {
					panel.removeAttribute( 'hidden' );
				}
			}
		}

		triggers.forEach( function ( trigger, index ) {
			trigger.addEventListener( 'click', function () {
				toggle( trigger );
			} );

			trigger.addEventListener( 'keydown', function ( event ) {
				var key = event.key;
				var target = null;

				if ( 'ArrowDown' === key ) {
					target = triggers[ index + 1 ] || triggers[ 0 ];
				} else if ( 'ArrowUp' === key ) {
					target = triggers[ index - 1 ] || triggers[ triggers.length - 1 ];
				} else if ( 'Home' === key ) {
					target = triggers[ 0 ];
				} else if ( 'End' === key ) {
					target = triggers[ triggers.length - 1 ];
				}

				if ( target ) {
					event.preventDefault();
					target.focus();
				}
			} );
		} );
	}

	function init( root ) {
		var scope = root || document;
		Array.prototype.forEach.call(
			scope.querySelectorAll( '.fwfe-accordion' ),
			setup
		);
	}

	if ( 'loading' !== document.readyState ) {
		init();
	} else {
		document.addEventListener( 'DOMContentLoaded', function () {
			init();
		} );
	}

	if ( window.elementorFrontend && window.elementorFrontend.hooks ) {
		window.elementorFrontend.hooks.addAction( 'frontend/element_ready/fwfe-accordion.default', function ( $scope ) {
			init( $scope && $scope[ 0 ] ? $scope[ 0 ] : document );
		} );
	}
}() );
