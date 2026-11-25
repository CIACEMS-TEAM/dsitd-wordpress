<?php
/**
 * Thème DSITD — fonctions principales.
 *
 * @package dsitd
 */

defined( 'ABSPATH' ) || exit;

define( 'DSITD_VERSION', wp_get_theme( 'dsitd' )->get( 'Version' ) ?: '0.1.0' );
define( 'DSITD_DIR', get_template_directory() );
define( 'DSITD_URI', get_template_directory_uri() );

require DSITD_DIR . '/inc/setup.php';
require DSITD_DIR . '/inc/post-types.php';
require DSITD_DIR . '/inc/blocks.php';
require DSITD_DIR . '/inc/navigation.php';
require DSITD_DIR . '/inc/securite.php';
require DSITD_DIR . '/inc/reglages-api.php';
