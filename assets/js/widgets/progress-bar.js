/**
 * Free Widgets For Elementor — Progress / Skill Bar.
 * Vanilla JS width fill using IntersectionObserver. Respects reduced motion.
 */
( function () {
	'use strict';

	function clamp( value ) {
		if ( value < 0 ) {
			return 0;
		}
		if ( value > 100 ) {
			return 100;
		}
		return value;
	}

	function fill( el ) {
		var target = clamp( parseFloat( el.getAttribute( 'data-percent' ) ) || 0 );
		el.style.width = target + '%';
	}

	function process( el ) {
		if ( ! el || el.getAttribute( 'data-fwfe-done' ) ) {
			return;
		}
		el.setAttribute( 'data-fwfe-done', '1' );

		var reduce = window.matchMedia && window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;

		if ( reduce || ! ( 'IntersectionObserver' in window ) ) {
			fill( el );
			return;
		}

		var observer = new IntersectionObserver(
			function ( entries ) {
				entries.forEach( function ( entry ) {
					if ( entry.isIntersecting ) {
						fill( entry.target );
						observer.unobserve( entry.target );
					}
				} );
			},
			{ threshold: 0.4 }
		);
		observer.observe( el );
	}

	function init( root ) {
		var scope = root || document;
		var bars = scope.querySelectorAll( '.fwfe-progress-bar__fill' );
		Array.prototype.forEach.call( bars, process );
	}

	if ( 'loading' !== document.readyState ) {
		init();
	} else {
		document.addEventListener( 'DOMContentLoaded', function () {
			init();
		} );
	}

	// Elementor editor / frontend element ready.
	if ( window.elementorFrontend && window.elementorFrontend.hooks ) {
		window.elementorFrontend.hooks.addAction( 'frontend/element_ready/fwfe-progress-bar.default', function ( $scope ) {
			init( $scope && $scope[ 0 ] ? $scope[ 0 ] : document );
		} );
	}
}() );
