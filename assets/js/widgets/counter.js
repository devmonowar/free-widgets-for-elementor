/**
 * Free Widgets For Elementor — Counter.
 * Vanilla JS count-up using IntersectionObserver. Respects reduced motion.
 */
( function () {
	'use strict';

	function format( value, useSeparator ) {
		var n = Math.round( value ).toString();
		if ( useSeparator ) {
			n = n.replace( /\B(?=(\d{3})+(?!\d))/g, ',' );
		}
		return n;
	}

	function animate( el ) {
		var from = parseFloat( el.getAttribute( 'data-from' ) ) || 0;
		var to = parseFloat( el.getAttribute( 'data-to' ) ) || 0;
		var duration = parseInt( el.getAttribute( 'data-duration' ), 10 ) || 2000;
		var useSeparator = '1' === el.getAttribute( 'data-separator' );
		var start = null;

		function step( timestamp ) {
			if ( null === start ) {
				start = timestamp;
			}
			var progress = Math.min( ( timestamp - start ) / duration, 1 );
			el.textContent = format( from + ( to - from ) * progress, useSeparator );
			if ( progress < 1 ) {
				window.requestAnimationFrame( step );
			}
		}

		window.requestAnimationFrame( step );
	}

	function process( el ) {
		if ( ! el || el.getAttribute( 'data-fwfe-done' ) ) {
			return;
		}
		el.setAttribute( 'data-fwfe-done', '1' );

		var reduce = window.matchMedia && window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;

		if ( reduce || ! ( 'IntersectionObserver' in window ) || ! window.requestAnimationFrame ) {
			el.textContent = format( parseFloat( el.getAttribute( 'data-to' ) ) || 0, '1' === el.getAttribute( 'data-separator' ) );
			return;
		}

		var observer = new IntersectionObserver(
			function ( entries ) {
				entries.forEach( function ( entry ) {
					if ( entry.isIntersecting ) {
						animate( entry.target );
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
		var numbers = scope.querySelectorAll( '.fwfe-counter__number' );
		Array.prototype.forEach.call( numbers, process );
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
		window.elementorFrontend.hooks.addAction( 'frontend/element_ready/fwfe-counter.default', function ( $scope ) {
			init( $scope && $scope[ 0 ] ? $scope[ 0 ] : document );
		} );
	}
}() );
