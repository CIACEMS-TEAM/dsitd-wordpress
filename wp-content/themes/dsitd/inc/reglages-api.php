<?php
/**
 * Réglages supplémentaires exposés dans l'API REST (/wp/v2/settings), pour
 * que le script de structure (scripts/structure.py) puisse configurer un site
 * sans passer par l'administration. L'écriture reste réservée aux
 * administrateurs (capacité manage_options exigée par l'API).
 *
 * @package dsitd
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'rest_api_init',
	function () {
		register_setting(
			'permalink',
			'permalink_structure',
			array(
				'type'              => 'string',
				'description'       => 'Structure des permaliens (ex. /%postname%/).',
				'show_in_rest'      => true,
				'sanitize_callback' => 'dsitd_valider_permaliens',
			)
		);
	}
);

/**
 * Valide la structure : vide (liens simples) ou chemin contenant une balise %…%.
 */
function dsitd_valider_permaliens( $valeur ): string {
	$valeur = trim( (string) $valeur );
	if ( '' === $valeur ) {
		return '';
	}
	if ( ! str_contains( $valeur, '%' ) ) {
		return (string) get_option( 'permalink_structure' );
	}
	return '/' . trim( preg_replace( '#/+#', '/', $valeur ), '/' ) . '/';
}

// Les règles de réécriture sont régénérées à la requête suivante.
add_action(
	'update_option_permalink_structure',
	function () {
		delete_option( 'rewrite_rules' );
	}
);
