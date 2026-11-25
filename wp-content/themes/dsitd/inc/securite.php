<?php
/**
 * Durcissement léger (section 8 du cahier de mise en œuvre).
 * Les mesures principales (2FA, limitation des connexions, déplacement de la
 * page de connexion) relèvent de l'extension de sécurité choisie.
 *
 * @package dsitd
 */

defined( 'ABSPATH' ) || exit;

// XML-RPC désactivé.
add_filter( 'xmlrpc_enabled', '__return_false' );
add_filter(
	'wp_headers',
	function ( array $headers ) {
		unset( $headers['X-Pingback'] );
		return $headers;
	}
);

// Ne pas divulguer la version de WordPress.
remove_action( 'wp_head', 'wp_generator' );
add_filter( 'the_generator', '__return_empty_string' );

// Liste des utilisateurs de l'API REST réservée aux personnes connectées.
add_filter(
	'rest_endpoints',
	function ( array $endpoints ) {
		if ( ! is_user_logged_in() ) {
			unset( $endpoints['/wp/v2/users'], $endpoints['/wp/v2/users/(?P<id>[\d]+)'] );
		}
		return $endpoints;
	}
);

// Empêche l'énumération des auteurs via ?author=N.
add_action(
	'template_redirect',
	function () {
		if ( ! is_admin() && isset( $_GET['author'] ) && ! is_user_logged_in() ) { // phpcs:ignore WordPress.Security.NonceVerification
			wp_safe_redirect( home_url( '/' ), 301 );
			exit;
		}
	}
);
