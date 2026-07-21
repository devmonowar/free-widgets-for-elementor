/**
 * Free Widgets For Elementor — Table of Contents.
 *
 * Vanilla JS. PHP renders an empty shell; this scans a content scope for
 * headings at runtime, builds the list, and wires smooth-scroll + scrollspy
 * (IntersectionObserver) + an optional collapsible toggle. No library.
 */
( function () {
	'use strict';

	/**
	 * Resolve the content scope to scan for headings.
	 *
	 * @param {Element} root         The .fwfe-toc element.
	 * @param {string}  selectorAttr Optional user-supplied CSS selector.
	 * @return {Element}
	 */
	function findScope( root, selectorAttr ) {
		if ( selectorAttr ) {
			try {
				var custom = document.querySelector( selectorAttr );
				if ( custom ) {
					return custom;
				}
			} catch ( e ) {
				// Invalid selector — fall through to auto-detect.
			}
		}
		var article = root.closest( 'article' );
		if ( article ) {
			return article;
		}
		var entry = document.querySelector( '.entry-content' );
		if ( entry ) {
			return entry;
		}
		return document.body;
	}

	/**
	 * Collect headings within scope, excluding anything inside the TOC itself.
	 *
	 * @param {Element} scope  Container to search.
	 * @param {Array}   levels Tag names to match, e.g. [ 'h2', 'h3' ].
	 * @param {Element} root   The .fwfe-toc element (excluded from results).
	 * @return {Array}
	 */
	function collectHeadings( scope, levels, root ) {
		var found = scope.querySelectorAll( levels.join( ',' ) );
		return Array.prototype.filter.call( found, function ( h ) {
			return ! root.contains( h );
		} );
	}

	/**
	 * Slugify heading text for a fallback id.
	 *
	 * @param {string} text
	 * @return {string}
	 */
	function slugify( text ) {
		var slug = text
			.toString()
			.trim()
			.toLowerCase()
			.replace( /[^a-z0-9\s-]/g, '' )
			.replace( /\s+/g, '-' )
			.replace( /-+/g, '-' )
			.replace( /^-+|-+$/g, '' );
		return slug || 'section';
	}

	/**
	 * Ensure a heading has a stable, unique id; assign one if missing.
	 *
	 * @param {Element} h
	 * @param {Object}  used Map of ids already handed out this pass.
	 * @return {string}
	 */
	function ensureId( h, used ) {
		if ( h.id ) {
			used[ h.id ] = true;
			return h.id;
		}
		var base = slugify( h.textContent );
		var id = base;
		var i = 2;
		while ( document.getElementById( id ) || used[ id ] ) {
			id = base + '-' + i++;
		}
		h.id = id;
		used[ id ] = true;
		return id;
	}

	/**
	 * Build the flat <li> list; indentation is driven by a --level custom
	 * property (see CSS), ranked by the heading's position among the
	 * widget's selected levels (not by literal h1..h6 distance).
	 *
	 * @param {Element} list    The <ol class="fwfe-toc__list">.
	 * @param {Array}   headings
	 * @param {Array}   levels  Selected level tag names, any order.
	 * @return {void}
	 */
	function buildList( list, headings, levels ) {
		var sortedLevels = levels.slice().sort( function ( a, b ) {
			return parseInt( a.charAt( 1 ), 10 ) - parseInt( b.charAt( 1 ), 10 );
		} );
		var used = {};
		var frag = document.createDocumentFragment();

		headings.forEach( function ( h ) {
			var id = ensureId( h, used );
			var level = sortedLevels.indexOf( h.tagName.toLowerCase() );
			if ( level < 0 ) {
				level = 0;
			}

			var li = document.createElement( 'li' );
			li.className = 'fwfe-toc__item';
			li.style.setProperty( '--level', level );

			var a = document.createElement( 'a' );
			a.className = 'fwfe-toc__link';
			a.href = '#' + id;
			a.textContent = h.textContent.trim();

			li.appendChild( a );
			frag.appendChild( li );
		} );

		list.innerHTML = '';
		list.appendChild( frag );
	}

	/**
	 * Move focus to a heading for keyboard/SR users, restoring its original
	 * tabindex (if any) once it loses focus again.
	 *
	 * @param {Element} target
	 * @return {void}
	 */
	function focusHeading( target ) {
		var hadTabIndex = target.hasAttribute( 'tabindex' );
		if ( ! hadTabIndex ) {
			target.setAttribute( 'tabindex', '-1' );
		}
		target.focus( { preventScroll: true } );
		if ( ! hadTabIndex ) {
			target.addEventListener( 'blur', function onBlur() {
				target.removeAttribute( 'tabindex' );
				target.removeEventListener( 'blur', onBlur );
			} );
		}
	}

	/**
	 * Run `callback` once the current scroll has settled. Focusing an element
	 * WHILE a smooth scroll is still animating cancels the animation in some
	 * browsers, so callers must wait for it to finish first.
	 *
	 * @param {Function} callback
	 * @return {void}
	 */
	function afterScrollSettles( callback ) {
		if ( 'onscrollend' in window ) {
			var done = false;
			var finish = function () {
				if ( done ) {
					return;
				}
				done = true;
				window.removeEventListener( 'scrollend', finish );
				callback();
			};
			window.addEventListener( 'scrollend', finish );
			setTimeout( finish, 1200 ); // Safety net if scrollend never fires.
		} else {
			setTimeout( callback, 600 );
		}
	}

	/**
	 * Scroll a heading into view, accounting for a sticky-header offset and
	 * `prefers-reduced-motion`, then move focus there for keyboard/SR users.
	 *
	 * @param {Element} target
	 * @param {number}  offset
	 * @param {boolean} smooth
	 * @return {void}
	 */
	function scrollToHeading( target, offset, smooth ) {
		var reduceMotion = window.matchMedia && window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;
		var useSmooth = smooth && ! reduceMotion;
		var top = target.getBoundingClientRect().top + window.pageYOffset - offset;

		window.scrollTo( { top: top, behavior: useSmooth ? 'smooth' : 'auto' } );

		if ( history.replaceState ) {
			history.replaceState( null, '', '#' + target.id );
		}

		if ( useSmooth ) {
			afterScrollSettles( function () {
				focusHeading( target );
			} );
		} else {
			focusHeading( target );
		}
	}

	/**
	 * Wire click-to-scroll on the list (event delegation).
	 *
	 * @param {Element} list
	 * @param {number}  offset
	 * @param {boolean} smooth
	 * @return {void}
	 */
	function setupClicks( list, offset, smooth ) {
		list.addEventListener( 'click', function ( e ) {
			var a = e.target.closest ? e.target.closest( 'a' ) : null;
			if ( ! a || ! list.contains( a ) ) {
				return;
			}
			var id = a.getAttribute( 'href' ).slice( 1 );
			var target = document.getElementById( id );
			if ( ! target ) {
				return;
			}
			e.preventDefault();
			scrollToHeading( target, offset, smooth );
		} );
	}

	/**
	 * Highlight the TOC link for whichever heading is currently in view.
	 *
	 * @param {Element} list
	 * @param {Array}   headings
	 * @return {void}
	 */
	function setupScrollspy( list, headings ) {
		if ( ! ( 'IntersectionObserver' in window ) ) {
			return;
		}

		var links = {};
		Array.prototype.forEach.call( list.querySelectorAll( '.fwfe-toc__link' ), function ( a ) {
			links[ a.getAttribute( 'href' ).slice( 1 ) ] = a;
		} );

		function setActive( link ) {
			Array.prototype.forEach.call( list.querySelectorAll( '.fwfe-toc__link--active' ), function ( a ) {
				a.classList.remove( 'fwfe-toc__link--active' );
			} );
			link.classList.add( 'fwfe-toc__link--active' );
		}

		var observer = new IntersectionObserver(
			function ( entries ) {
				entries.forEach( function ( entry ) {
					if ( ! entry.isIntersecting ) {
						return;
					}
					var link = links[ entry.target.id ];
					if ( link ) {
						setActive( link );
					}
				} );
			},
			{ rootMargin: '0px 0px -70% 0px', threshold: 0 }
		);

		headings.forEach( function ( h ) {
			observer.observe( h );
		} );
	}

	/**
	 * Wire the collapsible toggle button, if the widget has one.
	 *
	 * @param {Element} root
	 * @return {void}
	 */
	function setupToggle( root ) {
		var toggle = root.querySelector( '.fwfe-toc__toggle' );
		var list = root.querySelector( '.fwfe-toc__list' );
		if ( ! toggle || ! list ) {
			return;
		}
		toggle.addEventListener( 'click', function () {
			var expanded = 'true' === toggle.getAttribute( 'aria-expanded' );
			toggle.setAttribute( 'aria-expanded', expanded ? 'false' : 'true' );
			if ( expanded ) {
				list.setAttribute( 'hidden', '' );
			} else {
				list.removeAttribute( 'hidden' );
			}
		} );
	}

	/**
	 * Process one .fwfe-toc widget instance.
	 *
	 * @param {Element} root
	 * @return {void}
	 */
	function process( root ) {
		if ( ! root || root.getAttribute( 'data-fwfe-done' ) ) {
			return;
		}
		root.setAttribute( 'data-fwfe-done', '1' );

		var levels = ( root.getAttribute( 'data-levels' ) || 'h2,h3' )
			.split( ',' )
			.map( function ( s ) {
				return s.trim().toLowerCase();
			} )
			.filter( Boolean );
		var min = parseInt( root.getAttribute( 'data-min' ), 10 ) || 1;
		var selectorAttr = root.getAttribute( 'data-selector' ) || '';
		var smooth = '1' === root.getAttribute( 'data-smooth' );
		var offset = parseInt( root.getAttribute( 'data-offset' ), 10 ) || 0;

		var list = root.querySelector( '.fwfe-toc__list' );
		var emptyMsg = root.querySelector( '.fwfe-toc__empty' );
		if ( ! list ) {
			return;
		}

		var scope = findScope( root, selectorAttr );
		var headings = collectHeadings( scope, levels, root );

		var isEdit = !! ( window.elementorFrontend && window.elementorFrontend.isEditMode && window.elementorFrontend.isEditMode() );

		if ( ! headings.length ) {
			if ( isEdit ) {
				if ( emptyMsg ) {
					emptyMsg.removeAttribute( 'hidden' );
				}
			} else {
				root.setAttribute( 'hidden', '' );
			}
			return;
		}

		if ( headings.length < min && ! isEdit ) {
			root.setAttribute( 'hidden', '' );
			return;
		}

		buildList( list, headings, levels );
		setupClicks( list, offset, smooth );
		setupScrollspy( list, headings );
		setupToggle( root );
	}

	/**
	 * Process every .fwfe-toc instance within scope.
	 *
	 * @param {Element} [root]
	 * @return {void}
	 */
	function init( root ) {
		var scope = root || document;
		var widgets = scope.querySelectorAll( '.fwfe-toc' );
		Array.prototype.forEach.call( widgets, process );
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
		window.elementorFrontend.hooks.addAction( 'frontend/element_ready/fwfe-table-of-contents.default', function ( $scope ) {
			init( $scope && $scope[ 0 ] ? $scope[ 0 ] : document );
		} );
	}
}() );
