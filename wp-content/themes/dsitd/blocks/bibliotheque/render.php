<?php
/**
 * Derniers documents : Download Monitor s'il est installé, sinon les PDF de la médiathèque.
 *
 * @package dsitd
 */

defined( 'ABSPATH' ) || exit;

$nombre = max( 1, min( 10, (int) ( $attributes['nombre'] ?? 5 ) ) );
$items  = array();

if ( post_type_exists( 'dlm_download' ) ) {
	foreach ( get_posts( array( 'post_type' => 'dlm_download', 'numberposts' => $nombre ) ) as $p ) {
		$items[] = array( get_the_title( $p ), get_permalink( $p ), 'PDF' );
	}
} else {
	$pdfs = get_posts(
		array(
			'post_type'      => 'attachment',
			'post_mime_type' => 'application/pdf',
			'post_status'    => 'inherit',
			'numberposts'    => $nombre,
		)
	);
	foreach ( $pdfs as $p ) {
		$items[] = array( get_the_title( $p ), wp_get_attachment_url( $p->ID ), 'PDF' );
	}
}
?>
<div <?php echo get_block_wrapper_attributes( array( 'class' => 'dsitd-liste' ) ); ?>>
	<?php if ( $items ) : ?>
		<?php foreach ( $items as list( $titre, $url, $format ) ) : ?>
			<a class="dsitd-liste__item" href="<?php echo esc_url( $url ); ?>">
				<span><?php echo esc_html( $titre ); ?></span>
				<span class="dsitd-liste__format"><?php echo esc_html( $format ); ?></span>
			</a>
		<?php endforeach; ?>
	<?php else : ?>
		<p class="dsitd-vide">Les documents de référence (décret, manuels, chartes) seront bientôt disponibles.</p>
	<?php endif; ?>
</div>
