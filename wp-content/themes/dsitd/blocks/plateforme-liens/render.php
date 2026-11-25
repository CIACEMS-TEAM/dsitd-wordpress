<?php
/**
 * Liens « Accéder » / « Tutoriel » d'une plateforme.
 *
 * @package dsitd
 */

defined( 'ABSPATH' ) || exit;

$post_id = (int) ( $block->context['postId'] ?? get_the_ID() );
if ( ! $post_id ) {
	return;
}
$acces    = get_post_meta( $post_id, 'dsitd_url_acces', true );
$tutoriel = get_post_meta( $post_id, 'dsitd_url_tutoriel', true );
$titre    = get_the_title( $post_id );
?>
<div <?php echo get_block_wrapper_attributes( array( 'class' => 'dsitd-liens' ) ); ?>>
	<a href="<?php echo esc_url( $acces ?: get_permalink( $post_id ) ); ?>"<?php echo $acces ? ' target="_blank" rel="noopener"' : ''; ?>>
		Accéder<span class="screen-reader-text"> à <?php echo esc_html( $titre ); ?><?php echo $acces ? ' (nouvel onglet)' : ''; ?></span>
	</a>
	<a href="<?php echo esc_url( $tutoriel ?: get_permalink( $post_id ) ); ?>">
		Tutoriel<span class="screen-reader-text"> <?php echo esc_html( $titre ); ?></span>
	</a>
</div>
