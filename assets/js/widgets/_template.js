/**
 * WIDGET TEMPLATE — JS skeleton (only add a JS file if the widget needs it).
 *
 * Copy to  assets/js/widgets/<slug>.js  and rename `fwfe-_template`/
 * `fwfe-<slug>` throughout. Auto-loads only on pages using the widget.
 *
 * Rules (the whole plugin follows these):
 *   - vanilla JS, no jQuery, no external library
 *   - read config from data-* attributes set in render()
 *   - respect prefers-reduced-motion
 *   - guard against double-init with a data-fwfe-done flag
 *   - init on DOMContentLoaded AND on Elementor's element_ready hook, so it
 *     works on the live page and inside the Elementor editor preview
 */
( function () {
	'use strict';

	// Process one widget element.
	function process( el ) {
		if ( ! el || el.getAttribute( 'data-fwfe-done' ) ) {
			return; // already initialised
		}
		el.setAttribute( 'data-fwfe-done', '1' );

		var reduce = window.matchMedia && window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;

		// --- widget logic goes here ---
		// var value = el.getAttribute( 'data-something' );
		// if ( reduce ) { /* skip animation, show final state */ }
		void reduce;
	}

	// Find and init every instance within a scope (document or an editor scope).
	function init( root ) {
		var scope = root || document;
		var nodes = scope.querySelectorAll( '.fwfe-_template' );
		Array.prototype.forEach.call( nodes, process );
	}

	if ( 'loading' !== document.readyState ) {
		init();
	} else {
		document.addEventListener( 'DOMContentLoaded', function () {
			init();
		} );
	}

	// Elementor editor / frontend "element ready" — note the get_name() match.
	if ( window.elementorFrontend && window.elementorFrontend.hooks ) {
		window.elementorFrontend.hooks.addAction(
			'frontend/element_ready/fwfe-_template.default',
			function ( $scope ) {
				init( $scope && $scope[ 0 ] ? $scope[ 0 ] : document );
			}
		);
	}
}() );
