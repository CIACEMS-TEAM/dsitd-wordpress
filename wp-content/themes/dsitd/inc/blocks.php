<?php
/**
 * Blocs dynamiques du thème (rendus côté serveur, sans étape de compilation).
 *
 * Chaque dossier de /blocks contient un block.json et un render.php.
 * Dans l'éditeur, ils sont affichés grâce à ServerSideRender.
 *
 * @package dsitd
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'init',
	function () {
		wp_register_script(
			'dsitd-blocks-editor',
			DSITD_URI . '/assets/js/blocks-editor.js',
			array( 'wp-blocks', 'wp-element', 'wp-server-side-render', 'wp-block-editor', 'wp-components', 'wp-i18n' ),
			DSITD_VERSION,
			true
		);
		wp_register_script(
			'dsitd-carrousel',
			DSITD_URI . '/assets/js/carrousel.js',
			array(),
			DSITD_VERSION,
			array(
				'in_footer' => true,
				'strategy'  => 'defer',
			)
		);

		$noms = array();
		foreach ( glob( DSITD_DIR . '/blocks/*/block.json' ) as $fichier ) {
			$type = register_block_type( dirname( $fichier ) );
			if ( $type ) {
				$noms[] = $type->name;
			}
		}
		wp_add_inline_script( 'dsitd-blocks-editor', 'window.dsitdBlocks = ' . wp_json_encode( $noms ) . ';', 'before' );
	}
);

// Panneau « Informations DSITD » dans la barre latérale de l'éditeur.
add_action(
	'enqueue_block_editor_assets',
	function () {
		$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
		if ( ! $screen || ! in_array( $screen->post_type, array( 'dsitd_plateforme', 'dsitd_astuce', 'dsitd_alerte' ), true ) ) {
			return;
		}
		wp_enqueue_script(
			'dsitd-meta-panel',
			DSITD_URI . '/assets/js/meta-panel.js',
			array( 'wp-plugins', 'wp-editor', 'wp-element', 'wp-components', 'wp-data', 'wp-core-data' ),
			DSITD_VERSION,
			true
		);
	}
);

// Image de remplacement pour les cartes d'actualité sans image à la une.
add_filter(
	'render_block_core/post-featured-image',
	function ( string $html, array $block ) {
		if ( '' !== trim( $html ) || ! str_contains( $block['attrs']['className'] ?? '', 'dsitd-image-ou-defaut' ) ) {
			return $html;
		}
		return '<figure class="wp-block-post-featured-image dsitd-image-defaut" aria-hidden="true"></figure>';
	},
	10,
	2
);

/**
 * Formate une date en français, en majuscules pour le badge de l'agenda.
 */
function dsitd_badge_date( int $timestamp ): array {
	return array(
		'jour' => wp_date( 'j', $timestamp ),
		'mois' => wp_date( 'M', $timestamp ),
	);
}
