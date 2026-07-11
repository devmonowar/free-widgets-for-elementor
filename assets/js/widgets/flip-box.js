/**
 * Free Widgets For Elementor — Flip Box (click trigger).
 *
 * Only the "click" trigger needs JS; the "hover" trigger is pure CSS. This
 * toggles `.is-flipped` on the box and keeps the interaction accessible:
 *  - a real <button> flips to the back (aria-expanded);
 *  - focus moves to the back face so screen-reader users follow the content;
 *  - the back-face button is only tab-reachable while flipped;
 *  - the non-visible face is aria-hidden;
 *  - Escape, click-outside-the-link, and focus leaving the card flip it back.
 * Reduced motion is handled in CSS (cross-fade, no spin).
 */
( function () {
	'use strict';

	function setState( box, flipped ) {
		box.classList.toggle( 'is-flipped', flipped );

		var toggle = box.querySelector( '.fwfe-flip-box__flip' );
		var front  = box.querySelector( '.fwfe-flip-box__front' );
		var back   = box.querySelector( '.fwfe-flip-box__back' );
		var link   = back ? back.querySelector( '.fwfe-flip-box__button' ) : null;

		if ( toggle ) {
			toggle.setAttribute( 'aria-expanded', flipped ? 'true' : 'false' );
		}
		if ( front ) {
			front.setAttribute( 'aria-hidden', flipped ? 'true' : 'false' );
		}
		if ( back ) {
			back.setAttribute( 'aria-hidden', flipped ? 'false' : 'true' );
		}
		if ( link ) {
			link.setAttribute( 'tabindex', flipped ? '0' : '-1' );
		}
	}

	function flipTo( box, flipped ) {
		setState( box, flipped );

		if ( flipped ) {
			var back = box.querySelector( '.fwfe-flip-box__back' );
			if ( back && back.focus ) {
				back.focus();
			}
		} else {
			var toggle = box.querySelector( '.fwfe-flip-box__flip' );
			if ( toggle && toggle.focus ) {
				toggle.focus();
			}
		}
	}

	function process( box ) {
		if ( ! box || box.getAttribute( 'data-fwfe-done' ) ) {
			return;
		}
		if ( 'click' !== box.getAttribute( 'data-trigger' ) ) {
			return;
		}
		box.setAttribute( 'data-fwfe-done', '1' );

		// Initialise aria / tabindex for the resting (front-facing) state.
		setState( box, false );

		var toggle = box.querySelector( '.fwfe-flip-box__flip' );
		var back   = box.querySelector( '.fwfe-flip-box__back' );

		if ( toggle ) {
			toggle.addEventListener( 'click', function () {
				flipTo( box, ! box.classList.contains( 'is-flipped' ) );
			} );
		}

		// Mouse users: clicking the back (but not the CTA link) flips it back.
		if ( back ) {
			back.addEventListener( 'click', function ( e ) {
				if ( e.target.closest && e.target.closest( '.fwfe-flip-box__button' ) ) {
					return;
				}
				flipTo( box, false );
			} );
		}

		// Keyboard: Escape returns to the front.
		box.addEventListener( 'keydown', function ( e ) {
			if ( ( 'Escape' === e.key || 'Esc' === e.key ) && box.classList.contains( 'is-flipped' ) ) {
				flipTo( box, false );
			}
		} );

		// Focus leaving the whole card flips it back.
		box.addEventListener( 'focusout', function ( e ) {
			if ( ! box.classList.contains( 'is-flipped' ) ) {
				return;
			}
			if ( ! e.relatedTarget || ! box.contains( e.relatedTarget ) ) {
				setState( box, false );
			}
		} );
	}

	function init( root ) {
		var scope = root || document;
		var boxes = scope.querySelectorAll( '.fwfe-flip-box--click' );
		Array.prototype.forEach.call( boxes, process );
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
		window.elementorFrontend.hooks.addAction( 'frontend/element_ready/fwfe-flip-box.default', function ( $scope ) {
			init( $scope && $scope[ 0 ] ? $scope[ 0 ] : document );
		} );
	}
}() );
