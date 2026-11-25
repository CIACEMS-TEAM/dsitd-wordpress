<?php
/**
 * Durée d'une astuce ou d'un tutoriel (en minutes).
 *
 * @package dsitd
 */

defined( 'ABSPATH' ) || exit;

$post_id = (int) ( $block->context['postId'] ?? get_the_ID() );
$duree   = $post_id ? (int) get_post_meta( $post_id, 'dsitd_duree', true ) : 0;
if ( $duree < 1 ) {
	return;
}
?>
<span <?php echo get_block_wrapper_attributes( array( 'class' => 'dsitd-duree' ) ); ?>><?php echo esc_html( $duree . ' min' ); ?></span>
