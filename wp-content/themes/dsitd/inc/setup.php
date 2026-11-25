<?php
/**
 * Réglages du thème, feuilles de style et catégories de compositions.
 *
 * @package dsitd
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'after_setup_theme',
	function () {
		load_theme_textdomain( 'dsitd', DSITD_DIR . '/languages' );
		add_theme_support( 'editor-styles' );
		add_editor_style( 'assets/css/dsitd.css' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'responsive-embeds' );
		add_theme_support( 'custom-logo' );
		// Les compositions du thème remplacent celles de WordPress.org.
		remove_theme_support( 'core-block-patterns' );

		add_image_size( 'dsitd-carte', 640, 360, true );
		add_image_size( 'dsitd-une', 1280, 720, true );
	}
);

add_action(
	'wp_enqueue_scripts',
	function () {
		wp_enqueue_style( 'dsitd', DSITD_URI . '/assets/css/dsitd.css', array(), DSITD_VERSION );
	}
);

// Précharge la police principale (affichage plus rapide du texte).
add_action(
	'wp_head',
	function () {
		printf(
			'<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n",
			esc_url( DSITD_URI . '/assets/fonts/inter-latin-wght-normal.woff2' )
		);
	},
	1
);

add_action(
	'init',
	function () {
		register_block_pattern_category( 'dsitd', array( 'label' => __( 'DSITD', 'dsitd' ) ) );
		register_block_pattern_category( 'dsitd-accueil', array( 'label' => __( 'DSITD — Accueil', 'dsitd' ) ) );

		// Styles de blocs propres à la charte.
		register_block_style(
			'core/post-terms',
			array(
				'name'  => 'etiquette',
				'label' => __( 'Étiquette verte', 'dsitd' ),
			)
		);
		register_block_style(
			'core/button',
			array(
				'name'  => 'orange',
				'label' => __( 'Orange (texte foncé)', 'dsitd' ),
			)
		);
		register_block_style(
			'core/button',
			array(
				'name'  => 'blanc',
				'label' => __( 'Blanc sur fond vert', 'dsitd' ),
			)
		);
		register_block_style(
			'core/heading',
			array(
				'name'  => 'majuscules',
				'label' => __( 'Capitales', 'dsitd' ),
			)
		);
	}
);
