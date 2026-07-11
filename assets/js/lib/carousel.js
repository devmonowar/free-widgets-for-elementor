/**
 * Free Widgets For Elementor — shared Carousel engine.
 *
 * Dependency-free (no jQuery, no external library). Auto-initialises every
 * `.fwfe-carousel` element and reads its options from data-* attributes.
 *
 * Markup contract (the widget MUST match this):
 *   .fwfe-carousel[data-*]
 *     .fwfe-carousel__viewport
 *       .fwfe-carousel__track
 *         .fwfe-carousel__slide   (one per item)
 *     .fwfe-carousel__arrow.fwfe-carousel__arrow--prev   (optional)
 *     .fwfe-carousel__arrow.fwfe-carousel__arrow--next   (optional)
 *     .fwfe-carousel__dots        (optional, filled by this engine)
 *
 * Options (data-* on .fwfe-carousel):
 *   data-per-view, data-per-view-tablet, data-per-view-mobile,
 *   data-gap (px), data-autoplay ('1'/'0'), data-speed (ms),
 *   data-loop ('1'/'0'), data-arrows ('1'/'0'), data-dots ('1'/'0').
 */
( function () {
	'use strict';

	var TABLET_MAX = 1024;
	var MOBILE_MAX = 767;

	function toInt( value, fallback ) {
		var n = parseInt( value, 10 );
		return isNaN( n ) ? fallback : n;
	}

	function isOn( value ) {
		return '1' === value || 'yes' === value || 'true' === value;
	}

	function prefersReducedMotion() {
		return window.matchMedia && window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;
	}

	// Live instances + a SINGLE shared resize handler (avoids one window
	// listener per carousel, and drops instances whose DOM was removed — e.g.
	// on Elementor editor re-render).
	var instances = [];
	var resizeTimer = null;
	window.addEventListener( 'resize', function () {
		if ( resizeTimer ) {
			window.clearTimeout( resizeTimer );
		}
		resizeTimer = window.setTimeout( function () {
			instances = instances.filter( function ( inst ) {
				return inst.root && inst.root.isConnected;
			} );
			instances.forEach( function ( inst ) {
				inst.layout();
				inst.syncAutoplay();
			} );
		}, 150 );
	} );

	/**
	 * A single carousel instance.
	 *
	 * @param {HTMLElement} root The `.fwfe-carousel` element.
	 */
	function Carousel( root ) {
		this.root = root;
		this.viewport = root.querySelector( '.fwfe-carousel__viewport' );
		this.track = root.querySelector( '.fwfe-carousel__track' );

		if ( ! this.viewport || ! this.track ) {
			return;
		}

		this.slides = Array.prototype.slice.call(
			this.track.querySelectorAll( '.fwfe-carousel__slide' )
		);
		this.total = this.slides.length;
		if ( 0 === this.total ) {
			return;
		}

		this.opts = {
			perView: Math.max( 1, toInt( root.getAttribute( 'data-per-view' ), 1 ) ),
			perViewTablet: Math.max( 1, toInt( root.getAttribute( 'data-per-view-tablet' ), 2 ) ),
			perViewMobile: Math.max( 1, toInt( root.getAttribute( 'data-per-view-mobile' ), 1 ) ),
			gap: Math.max( 0, toInt( root.getAttribute( 'data-gap' ), 0 ) ),
			autoplay: isOn( root.getAttribute( 'data-autoplay' ) ),
			speed: Math.max( 1000, toInt( root.getAttribute( 'data-speed' ), 3000 ) ),
			loop: isOn( root.getAttribute( 'data-loop' ) ),
			arrows: isOn( root.getAttribute( 'data-arrows' ) ),
			dots: isOn( root.getAttribute( 'data-dots' ) )
		};

		this.reduce = prefersReducedMotion();
		this.index = 0; // Leftmost visible slide index.
		this.perView = this.opts.perView;
		this.timer = null;

		this.prevBtn = root.querySelector( '.fwfe-carousel__arrow--prev' );
		this.nextBtn = root.querySelector( '.fwfe-carousel__arrow--next' );
		this.dotsWrap = root.querySelector( '.fwfe-carousel__dots' );

		this.init();
	}

	Carousel.prototype.currentPerView = function () {
		var w = window.innerWidth || document.documentElement.clientWidth;
		if ( w <= MOBILE_MAX ) {
			return Math.min( this.opts.perViewMobile, this.total );
		}
		if ( w <= TABLET_MAX ) {
			return Math.min( this.opts.perViewTablet, this.total );
		}
		return Math.min( this.opts.perView, this.total );
	};

	Carousel.prototype.maxIndex = function () {
		return Math.max( 0, this.total - this.perView );
	};

	Carousel.prototype.pageCount = function () {
		return Math.ceil( this.total / this.perView ) || 1;
	};

	Carousel.prototype.currentPage = function () {
		return Math.round( this.index / this.perView );
	};

	Carousel.prototype.slideMetrics = function () {
		var gap = this.opts.gap;
		var vpWidth = this.viewport.clientWidth;
		var width = ( vpWidth - gap * ( this.perView - 1 ) ) / this.perView;
		return { width: Math.max( 0, width ), gap: gap };
	};

	Carousel.prototype.layout = function () {
		this.perView = this.currentPerView();

		var m = this.slideMetrics();
		this.track.style.gap = m.gap + 'px';

		this.slides.forEach( function ( slide ) {
			slide.style.flex = '0 0 ' + m.width + 'px';
			slide.style.maxWidth = m.width + 'px';
		} );

		if ( this.index > this.maxIndex() ) {
			this.index = this.maxIndex();
		}

		this.buildDots();
		this.update();
	};

	Carousel.prototype.update = function () {
		var m = this.slideMetrics();
		var offset = this.index * ( m.width + m.gap );

		this.track.style.transition = this.reduce ? 'none' : 'transform 0.4s ease';
		this.track.style.transform = 'translateX(' + ( -offset ) + 'px)';

		this.updateArrows();
		this.updateDots();
	};

	Carousel.prototype.updateArrows = function () {
		if ( ! this.opts.arrows ) {
			return;
		}
		var atStart = this.index <= 0;
		var atEnd = this.index >= this.maxIndex();

		if ( this.prevBtn ) {
			this.prevBtn.disabled = ! this.opts.loop && atStart;
		}
		if ( this.nextBtn ) {
			this.nextBtn.disabled = ! this.opts.loop && atEnd;
		}
	};

	Carousel.prototype.buildDots = function () {
		if ( ! this.opts.dots || ! this.dotsWrap ) {
			return;
		}
		this.dotsWrap.innerHTML = '';
		var pages = this.pageCount();
		var self = this;

		for ( var p = 0; p < pages; p++ ) {
			( function ( page ) {
				var dot = document.createElement( 'button' );
				dot.type = 'button';
				dot.className = 'fwfe-carousel__dot';
				dot.setAttribute( 'aria-label', 'Go to slide group ' + ( page + 1 ) );
				dot.addEventListener( 'click', function () {
					self.goToPage( page );
					self.restartAutoplay();
				} );
				self.dotsWrap.appendChild( dot );
			}( p ) );
		}
	};

	Carousel.prototype.updateDots = function () {
		if ( ! this.opts.dots || ! this.dotsWrap ) {
			return;
		}
		var current = this.currentPage();
		var dots = this.dotsWrap.querySelectorAll( '.fwfe-carousel__dot' );
		Array.prototype.forEach.call( dots, function ( dot, i ) {
			if ( i === current ) {
				dot.classList.add( 'is-active' );
				dot.setAttribute( 'aria-current', 'true' );
			} else {
				dot.classList.remove( 'is-active' );
				dot.removeAttribute( 'aria-current' );
			}
		} );
	};

	Carousel.prototype.goToIndex = function ( index ) {
		var max = this.maxIndex();
		if ( index < 0 ) {
			index = this.opts.loop ? max : 0;
		} else if ( index > max ) {
			index = this.opts.loop ? 0 : max;
		}
		this.index = index;
		this.update();
	};

	Carousel.prototype.goToPage = function ( page ) {
		this.goToIndex( page * this.perView );
	};

	Carousel.prototype.next = function () {
		var target = this.index + this.perView;
		if ( target > this.maxIndex() ) {
			target = this.opts.loop ? 0 : this.maxIndex();
			// Non-loop but already at end during autoplay: rewind to start.
			if ( ! this.opts.loop && this.index >= this.maxIndex() ) {
				target = 0;
			}
		}
		this.goToIndex( target );
	};

	Carousel.prototype.prev = function () {
		var target = this.index - this.perView;
		this.goToIndex( target );
	};

	Carousel.prototype.startAutoplay = function () {
		if ( ! this.opts.autoplay || this.reduce || this.total <= this.perView ) {
			return;
		}
		var self = this;
		this.stopAutoplay();
		this.timer = window.setInterval( function () {
			// Self-terminate if the element was removed from the DOM (editor).
			if ( ! self.root.isConnected ) {
				self.stopAutoplay();
				return;
			}
			self.next();
		}, this.opts.speed );
	};

	Carousel.prototype.stopAutoplay = function () {
		if ( this.timer ) {
			window.clearInterval( this.timer );
			this.timer = null;
		}
	};

	Carousel.prototype.restartAutoplay = function () {
		if ( this.timer ) {
			this.stopAutoplay();
			this.startAutoplay();
		}
	};

	// Start or stop autoplay to match current eligibility (e.g. after a resize
	// changes how many slides are visible).
	Carousel.prototype.syncAutoplay = function () {
		var eligible = this.opts.autoplay && ! this.reduce && this.total > this.perView;
		if ( eligible && ! this.timer ) {
			this.startAutoplay();
		} else if ( ! eligible && this.timer ) {
			this.stopAutoplay();
		}
	};

	Carousel.prototype.init = function () {
		var self = this;

		if ( this.opts.arrows ) {
			if ( this.prevBtn ) {
				this.prevBtn.addEventListener( 'click', function () {
					self.prev();
					self.restartAutoplay();
				} );
			}
			if ( this.nextBtn ) {
				this.nextBtn.addEventListener( 'click', function () {
					self.next();
					self.restartAutoplay();
				} );
			}
		}

		// Pause on hover / focus.
		this.root.addEventListener( 'mouseenter', function () {
			self.stopAutoplay();
		} );
		this.root.addEventListener( 'mouseleave', function () {
			self.startAutoplay();
		} );
		this.root.addEventListener( 'focusin', function () {
			self.stopAutoplay();
		} );
		this.root.addEventListener( 'focusout', function () {
			self.startAutoplay();
		} );

		// Responsive relayout on resize is handled by the shared module-level
		// listener above (one listener for all instances, self-cleaning).

		this.layout();
		this.startAutoplay();
		instances.push( this );
	};

	/**
	 * Initialise every not-yet-initialised carousel within a scope.
	 *
	 * @param {HTMLElement|Document} scope Root to search within.
	 */
	function initCarousels( scope ) {
		var root = scope || document;
		var nodes = root.querySelectorAll ? root.querySelectorAll( '.fwfe-carousel' ) : [];
		Array.prototype.forEach.call( nodes, function ( el ) {
			if ( el.getAttribute( 'data-fwfe-done' ) ) {
				return;
			}
			el.setAttribute( 'data-fwfe-done', '1' );
			// eslint-disable-next-line no-new
			new Carousel( el );
		} );
	}

	window.FWFE = window.FWFE || {};
	window.FWFE.initCarousels = initCarousels;

	if ( 'loading' !== document.readyState ) {
		initCarousels( document );
	} else {
		document.addEventListener( 'DOMContentLoaded', function () {
			initCarousels( document );
		} );
	}

	// Elementor editor / frontend: init any widget that renders a carousel.
	if ( window.elementorFrontend && window.elementorFrontend.hooks ) {
		window.elementorFrontend.hooks.addAction( 'frontend/element_ready/global', function ( $scope ) {
			initCarousels( $scope && $scope[ 0 ] ? $scope[ 0 ] : document );
		} );
	}
}() );
