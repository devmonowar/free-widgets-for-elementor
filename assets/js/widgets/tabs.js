/**
 * Free Widgets For Elementor — Tabs.
 * WAI-ARIA tabs pattern: roving tabindex, Arrow/Home/End keyboard support.
 */
( function () {
	'use strict';

	function setup( container ) {
		if ( ! container || container.getAttribute( 'data-fwfe-done' ) ) {
			return;
		}
		container.setAttribute( 'data-fwfe-done', '1' );

		var tabs = Array.prototype.slice.call(
			container.querySelectorAll( '.fwfe-tabs__tab' )
		);
		if ( ! tabs.length ) {
			return;
		}

		function select( tab, setFocus ) {
			tabs.forEach( function ( item ) {
				var selected = item === tab;
				item.setAttribute( 'aria-selected', selected ? 'true' : 'false' );
				item.setAttribute( 'tabindex', selected ? '0' : '-1' );

				var panel = document.getElementById( item.getAttribute( 'aria-controls' ) );
				if ( panel ) {
					if ( selected ) {
						panel.removeAttribute( 'hidden' );
					} else {
						panel.setAttribute( 'hidden', '' );
					}
				}
			} );

			if ( setFocus ) {
				tab.focus();
			}
		}

		tabs.forEach( function ( tab, index ) {
			tab.addEventListener( 'click', function () {
				select( tab, false );
			} );

			tab.addEventListener( 'keydown', function ( event ) {
				var key = event.key;
				var target = null;

				if ( 'ArrowRight' === key || 'ArrowDown' === key ) {
					target = tabs[ index + 1 ] || tabs[ 0 ];
				} else if ( 'ArrowLeft' === key || 'ArrowUp' === key ) {
					target = tabs[ index - 1 ] || tabs[ tabs.length - 1 ];
				} else if ( 'Home' === key ) {
					target = tabs[ 0 ];
				} else if ( 'End' === key ) {
					target = tabs[ tabs.length - 1 ];
				}

				if ( target ) {
					event.preventDefault();
					select( target, true );
				}
			} );
		} );
	}

	function init( root ) {
		var scope = root || document;
		Array.prototype.forEach.call(
			scope.querySelectorAll( '.fwfe-tabs' ),
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
		window.elementorFrontend.hooks.addAction( 'frontend/element_ready/fwfe-tabs.default', function ( $scope ) {
			init( $scope && $scope[ 0 ] ? $scope[ 0 ] : document );
		} );
	}
}() );
