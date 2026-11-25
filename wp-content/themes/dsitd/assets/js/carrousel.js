/**
 * Carrousel des actualités : boutons, points, clavier, défilement automatique
 * (suspendu au survol, au focus et si l'utilisateur préfère réduire les animations).
 */
( function () {
	var reduire = window.matchMedia && window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;

	document.querySelectorAll( '[data-dsitd-carrousel]' ).forEach( function ( racine ) {
		var diapos = racine.querySelectorAll( '.dsitd-carrousel__diapo' );
		var points = racine.querySelectorAll( '.dsitd-carrousel__points button' );
		if ( diapos.length < 2 ) {
			return;
		}
		var courant = 0;
		var minuteur = null;

		function afficher( n ) {
			courant = ( n + diapos.length ) % diapos.length;
			diapos.forEach( function ( d, i ) {
				var actif = i === courant;
				d.classList.toggle( 'is-active', actif );
				d.hidden = ! actif;
			} );
			points.forEach( function ( p, i ) {
				if ( i === courant ) {
					p.setAttribute( 'aria-current', 'true' );
				} else {
					p.removeAttribute( 'aria-current' );
				}
			} );
		}
		function demarrer() {
			if ( reduire || minuteur ) {
				return;
			}
			minuteur = setInterval( function () {
				afficher( courant + 1 );
			}, 7000 );
		}
		function arreter() {
			clearInterval( minuteur );
			minuteur = null;
		}

		racine.querySelector( '.is-prec' ).addEventListener( 'click', function () {
			afficher( courant - 1 );
		} );
		racine.querySelector( '.is-suiv' ).addEventListener( 'click', function () {
			afficher( courant + 1 );
		} );
		points.forEach( function ( p, i ) {
			p.addEventListener( 'click', function () {
				afficher( i );
			} );
		} );
		racine.addEventListener( 'keydown', function ( e ) {
			if ( e.key === 'ArrowLeft' ) {
				afficher( courant - 1 );
			} else if ( e.key === 'ArrowRight' ) {
				afficher( courant + 1 );
			}
		} );
		racine.addEventListener( 'mouseenter', arreter );
		racine.addEventListener( 'mouseleave', demarrer );
		racine.addEventListener( 'focusin', arreter );
		racine.addEventListener( 'focusout', demarrer );
		demarrer();
	} );
} )();
