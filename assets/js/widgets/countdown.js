/**
 * Free Widgets For Elementor — Countdown Timer.
 * Vanilla JS. Reads a UTC due timestamp (seconds) from data-due, ticks every
 * second, and reveals the optional expired message at zero. No library.
 */
( function () {
	'use strict';

	var DAY = 86400;
	var HOUR = 3600;
	var MINUTE = 60;

	function pad( n ) {
		return n < 10 ? '0' + n : '' + n;
	}

	function paint( root, remaining ) {
		var numbers = root.querySelectorAll( '.fwfe-countdown__number' );
		var days = Math.floor( remaining / DAY );
		var hours = Math.floor( ( remaining % DAY ) / HOUR );
		var minutes = Math.floor( ( remaining % HOUR ) / MINUTE );
		var seconds = Math.floor( remaining % MINUTE );
		var map = { days: days, hours: hours, minutes: minutes, seconds: seconds };

		Array.prototype.forEach.call( numbers, function ( el ) {
			var unit = el.getAttribute( 'data-unit' );
			if ( map.hasOwnProperty( unit ) ) {
				el.textContent = pad( map[ unit ] );
			}
		} );
	}

	function expire( root ) {
		var units = root.querySelector( '.fwfe-countdown__units' );
		var message = root.querySelector( '.fwfe-countdown__expired' );
		if ( units ) {
			units.setAttribute( 'hidden', '' );
		}
		if ( message ) {
			message.removeAttribute( 'hidden' );
		}
	}

	function process( root ) {
		if ( ! root || root.getAttribute( 'data-fwfe-done' ) ) {
			return;
		}
		root.setAttribute( 'data-fwfe-done', '1' );

		var due = parseInt( root.getAttribute( 'data-due' ), 10 ) || 0;
		if ( due <= 0 ) {
			return;
		}

		function stop() {
			if ( root._fwfeTimer ) {
				clearInterval( root._fwfeTimer );
				root._fwfeTimer = null;
			}
		}

		function tick() {
			// Self-terminate if the element was removed from the DOM (editor).
			if ( ! root.isConnected ) {
				stop();
				return;
			}
			var remaining = due - Math.floor( Date.now() / 1000 );
			if ( remaining <= 0 ) {
				paint( root, 0 );
				expire( root );
				stop();
				return;
			}
			paint( root, remaining );
		}

		// Paint once now; only run an interval if there is time left.
		if ( due - Math.floor( Date.now() / 1000 ) <= 0 ) {
			paint( root, 0 );
			expire( root );
			return;
		}
		tick();
		root._fwfeTimer = setInterval( tick, 1000 );
	}

	function init( root ) {
		var scope = root || document;
		var timers = scope.querySelectorAll( '.fwfe-countdown' );
		Array.prototype.forEach.call( timers, process );
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
		window.elementorFrontend.hooks.addAction( 'frontend/element_ready/fwfe-countdown.default', function ( $scope ) {
			init( $scope && $scope[ 0 ] ? $scope[ 0 ] : document );
		} );
	}
}() );
