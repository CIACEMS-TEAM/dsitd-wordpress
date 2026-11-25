<?php
/**
 * Bandeau « Flash DSITD » : alerte cyber la plus récente encore dans sa durée d'affichage.
 * Rien n'est affiché s'il n'y a pas d'alerte en cours.
 *
 * @package dsitd
 */

defined( 'ABSPATH' ) || exit;

$alerte = null;
$candidates = get_posts(
	array(
		'post_type'        => 'dsitd_alerte',
		'posts_per_page'   => 5,
		'post_status'      => 'publish',
		'date_query'       => array( array( 'after' => '-60 days' ) ),
		'suppress_filters' => false,
	)
);
foreach ( $candidates as $candidate ) {
	$jours = (int) get_post_meta( $candidate->ID, 'dsitd_flash_jours', true );
	if ( '' === get_post_meta( $candidate->ID, 'dsitd_flash_jours', true ) ) {
		$jours = 7;
	}
	if ( $jours > 0 && get_post_timestamp( $candidate ) >= time() - $jours * DAY_IN_SECONDS ) {
		$alerte = $candidate;
		break;
	}
}

if ( ! $alerte ) {
	// Aperçu dans l'éditeur uniquement.
	if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
		echo '<div ' . get_block_wrapper_attributes( array( 'class' => 'dsitd-flash is-vide' ) ) . '><span class="dsitd-flash__label">Flash DSITD</span><span class="dsitd-flash__texte">Aucune alerte en cours : le bandeau est masqué sur le site.</span></div>';
	}
	return;
}

$texte = has_excerpt( $alerte ) ? get_the_excerpt( $alerte ) : get_the_title( $alerte );
?>
<div <?php echo get_block_wrapper_attributes( array( 'class' => 'dsitd-flash', 'role' => 'region', 'aria-label' => 'Flash DSITD' ) ); ?>>
	<span class="dsitd-flash__label">Flash DSITD</span>
	<a class="dsitd-flash__texte" href="<?php echo esc_url( get_permalink( $alerte ) ); ?>">
		<strong>Alerte cyber :</strong> <?php echo esc_html( wp_strip_all_tags( $texte ) ); ?>
	</a>
</div>
