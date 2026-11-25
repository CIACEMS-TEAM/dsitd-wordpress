<?php
/**
 * Types de contenu : Plateformes, Alertes cyber, Astuces & tutos.
 *
 * Ces types sont déclarés dans le thème pour l'étape 1. Ils pourront être
 * déplacés dans une extension « dsitd-contenus » pour survivre à un
 * changement de thème.
 *
 * @package dsitd
 */

defined( 'ABSPATH' ) || exit;

/**
 * Libellés standard d'un type de contenu.
 */
function dsitd_cpt_labels( string $singulier, string $pluriel, bool $feminin = false ): array {
	$nouveau = $feminin ? 'Nouvelle' : 'Nouveau';
	$aucun   = $feminin ? 'Aucune' : 'Aucun';
	return array(
		'name'               => $pluriel,
		'singular_name'      => $singulier,
		'add_new'            => 'Ajouter',
		'add_new_item'       => "Ajouter : $singulier",
		'edit_item'          => "Modifier : $singulier",
		'new_item'           => "$nouveau : $singulier",
		'view_item'          => "Voir : $singulier",
		'search_items'       => "Rechercher : $pluriel",
		'not_found'          => "$aucun élément trouvé",
		'not_found_in_trash' => "$aucun élément dans la corbeille",
		'all_items'          => "Tous les éléments",
		'menu_name'          => $pluriel,
	);
}

add_action(
	'init',
	function () {
		register_post_type(
			'dsitd_plateforme',
			array(
				'labels'       => dsitd_cpt_labels( 'Plateforme', 'Plateformes', true ),
				'public'       => true,
				'show_in_rest' => true,
				'menu_icon'    => 'dashicons-cloud',
				'menu_position' => 21,
				'has_archive'  => 'plateformes',
				'rewrite'      => array( 'slug' => 'plateformes', 'with_front' => false ),
				'supports'     => array( 'title', 'editor', 'excerpt', 'thumbnail', 'page-attributes', 'custom-fields', 'revisions' ),
			)
		);

		register_post_type(
			'dsitd_alerte',
			array(
				'labels'       => dsitd_cpt_labels( 'Alerte cyber', 'Alertes cyber', true ),
				'public'       => true,
				'show_in_rest' => true,
				'menu_icon'    => 'dashicons-shield-alt',
				'menu_position' => 22,
				'has_archive'  => 'alertes-cyber',
				'rewrite'      => array( 'slug' => 'alertes-cyber', 'with_front' => false ),
				'supports'     => array( 'title', 'editor', 'excerpt', 'thumbnail', 'custom-fields', 'revisions', 'author' ),
			)
		);

		register_post_type(
			'dsitd_astuce',
			array(
				'labels'       => dsitd_cpt_labels( 'Astuce ou tuto', 'Astuces & tutos', true ),
				'public'       => true,
				'show_in_rest' => true,
				'menu_icon'    => 'dashicons-lightbulb',
				'menu_position' => 23,
				'has_archive'  => 'astuces',
				'rewrite'      => array( 'slug' => 'astuces', 'with_front' => false ),
				'supports'     => array( 'title', 'editor', 'excerpt', 'thumbnail', 'custom-fields', 'revisions', 'author' ),
			)
		);

		register_taxonomy(
			'dsitd_format',
			array( 'dsitd_astuce' ),
			array(
				'labels'            => array(
					'name'          => 'Formats',
					'singular_name' => 'Format',
					'add_new_item'  => 'Ajouter un format',
					'menu_name'     => 'Formats',
				),
				'hierarchical'      => true,
				'show_in_rest'      => true,
				'show_admin_column' => true,
				'rewrite'           => array( 'slug' => 'astuces/format', 'with_front' => false ),
			)
		);

		// Métadonnées exposées à l'éditeur et à l'API REST.
		$meta = array(
			'dsitd_plateforme' => array(
				'dsitd_statut'      => array( 'string', 'Statut affiché (ex. « En production », « Phase pilote », « Accès réservé »)' ),
				'dsitd_public'      => array( 'string', 'Public visé' ),
				'dsitd_url_acces'   => array( 'string', 'Adresse d\'accès à la plateforme' ),
				'dsitd_url_tutoriel' => array( 'string', 'Lien vers le tutoriel' ),
			),
			'dsitd_astuce'     => array(
				'dsitd_duree' => array( 'integer', 'Durée de lecture ou de visionnage, en minutes' ),
			),
			'dsitd_alerte'     => array(
				'dsitd_flash_jours' => array( 'integer', 'Nombre de jours d\'affichage dans le bandeau « Flash DSITD » (0 = pas de bandeau)' ),
			),
		);
		foreach ( $meta as $post_type => $keys ) {
			foreach ( $keys as $key => list( $type, $description ) ) {
				register_post_meta(
					$post_type,
					$key,
					array(
						'type'              => $type,
						'description'       => $description,
						'single'            => true,
						'show_in_rest'      => true,
						'sanitize_callback' => str_starts_with( $key, 'dsitd_url' ) ? 'esc_url_raw' : null,
						'auth_callback'     => fn() => current_user_can( 'edit_posts' ),
						'default'           => 'dsitd_flash_jours' === $key ? 7 : ( 'integer' === $type ? 0 : '' ),
					)
				);
			}
		}
	}
);

// Les plateformes s'affichent dans l'ordre défini par l'attribut « Ordre ».
add_action(
	'pre_get_posts',
	function ( WP_Query $q ) {
		if ( ! is_admin() && $q->is_main_query() && $q->is_post_type_archive( 'dsitd_plateforme' ) ) {
			$q->set( 'orderby', array( 'menu_order' => 'ASC', 'title' => 'ASC' ) );
			$q->set( 'posts_per_page', 50 );
		}
	}
);
add_filter(
	'query_loop_block_query_vars',
	function ( array $query ) {
		if ( ( $query['post_type'] ?? '' ) === 'dsitd_plateforme' ) {
			$query['orderby'] = array( 'menu_order' => 'ASC', 'title' => 'ASC' );
		}
		return $query;
	}
);

// Recharge les règles de réécriture une seule fois après activation/mise à jour du thème.
add_action(
	'init',
	function () {
		if ( get_option( 'dsitd_rewrite_version' ) !== DSITD_VERSION ) {
			flush_rewrite_rules( false );
			update_option( 'dsitd_rewrite_version', DSITD_VERSION );
		}
	},
	99
);
