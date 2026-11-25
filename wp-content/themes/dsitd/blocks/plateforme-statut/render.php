<?php
/**
 * Étiquette de statut d'une plateforme.
 *
 * @package dsitd
 */

defined( 'ABSPATH' ) || exit;

$post_id = (int) ( $block->context['postId'] ?? get_the_ID() );
$statut  = $post_id ? get_post_meta( $post_id, 'dsitd_statut', true ) : '';
if ( ! $statut ) {
	return;
}
$classes = array(
	'En production' => 'is-vert',
	'Phase pilote'  => 'is-orange',
	'Accès réservé' => 'is-gris',
	'En projet'     => 'is-gris',
);
?>
<span <?php echo get_block_wrapper_attributes( array( 'class' => 'dsitd-etiquette ' . ( $classes[ $statut ] ?? 'is-vert' ) ) ); ?>><?php echo esc_html( $statut ); ?></span>
