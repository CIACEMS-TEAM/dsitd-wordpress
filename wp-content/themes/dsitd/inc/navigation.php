<?php
/**
 * Navigation : met en évidence la rubrique courante dans le menu principal,
 * y compris pour les liens personnalisés (URL saisies à la main).
 *
 * @package dsitd
 */

defined( 'ABSPATH' ) || exit;

/**
 * Chemin normalisé d'une URL (sans domaine, avec barre finale).
 */
function dsitd_chemin( string $url ): string {
	$chemin = (string) wp_parse_url( $url, PHP_URL_PATH );
	return '/' . trim( $chemin, '/' ) . ( trim( $chemin, '/' ) ? '/' : '' );
}

/**
 * Chemin de la rubrique courante (archive du type de contenu pour un contenu seul).
 */
function dsitd_chemin_courant(): string {
	if ( is_singular() && ! is_page() ) {
		$type = get_post_type();
		if ( 'post' === $type ) {
			return '/actualites/';
		}
		$archive = get_post_type_archive_link( $type );
		if ( $archive ) {
			return dsitd_chemin( $archive );
		}
	}
	if ( is_home() || is_category() || is_tag() ) {
		return '/actualites/';
	}
	return dsitd_chemin( (string) ( $_SERVER['REQUEST_URI'] ?? '/' ) );
}

add_filter(
	'render_block_core/navigation-link',
	function ( string $html, array $block ) {
		$url = $block['attrs']['url'] ?? '';
		if ( ! $url || str_contains( $html, 'current-menu-item' ) ) {
			return $html;
		}
		$lien    = dsitd_chemin( $url );
		$courant = dsitd_chemin_courant();
		$actif   = '/' === $lien ? '/' === $courant : str_starts_with( $courant, $lien );
		if ( ! $actif ) {
			return $html;
		}
		$p = new WP_HTML_Tag_Processor( $html );
		if ( $p->next_tag( 'li' ) ) {
			$p->add_class( 'current-menu-item' );
		}
		if ( $p->next_tag( 'a' ) ) {
			$p->set_attribute( 'aria-current', 'page' );
		}
		return $p->get_updated_html();
	},
	10,
	2
);
