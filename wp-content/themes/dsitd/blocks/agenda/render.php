<?php
/**
 * Prochains évènements (The Events Calendar). Sans l'extension : message d'attente.
 *
 * @package dsitd
 */

defined( 'ABSPATH' ) || exit;

$nombre     = max( 1, min( 10, (int) ( $attributes['nombre'] ?? 3 ) ) );
$evenements = array();
if ( function_exists( 'tribe_get_events' ) ) {
	$evenements = tribe_get_events(
		array(
			'posts_per_page' => $nombre,
			'start_date'     => 'now',
		)
	);
}
?>
<div <?php echo get_block_wrapper_attributes( array( 'class' => 'dsitd-agenda' ) ); ?>>
	<?php if ( $evenements ) : ?>
		<?php
		foreach ( $evenements as $ev ) :
			$ts   = strtotime( tribe_get_start_date( $ev, false, 'Y-m-d H:i:s' ) );
			$date = dsitd_badge_date( $ts );
			$lieu = function_exists( 'tribe_get_venue' ) ? tribe_get_venue( $ev->ID ) : '';
			?>
			<a class="dsitd-agenda__item" href="<?php echo esc_url( get_permalink( $ev ) ); ?>">
				<span class="dsitd-agenda__date"><strong><?php echo esc_html( $date['jour'] ); ?></strong><span><?php echo esc_html( $date['mois'] ); ?></span></span>
				<span><strong class="dsitd-agenda__titre"><?php echo esc_html( get_the_title( $ev ) ); ?></strong>
				<?php if ( $lieu ) : ?>
					<span class="dsitd-agenda__lieu"><?php echo esc_html( $lieu ); ?></span>
				<?php endif; ?></span>
			</a>
		<?php endforeach; ?>
	<?php else : ?>
		<p class="dsitd-vide">Aucune session programmée pour le moment. Le calendrier des formations sera publié ici.</p>
	<?php endif; ?>
</div>
