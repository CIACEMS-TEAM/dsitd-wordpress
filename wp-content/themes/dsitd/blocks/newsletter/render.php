<?php
/**
 * Encart « La lettre numérique de la DSITD ».
 * Utilise MailPoet ou The Newsletter Plugin lorsqu'ils sont installés ;
 * sinon, le bouton renvoie vers la page Contact.
 *
 * @package dsitd
 */

defined( 'ABSPATH' ) || exit;

$formulaire = '';
if ( shortcode_exists( 'newsletter_form' ) ) {
	$formulaire = do_shortcode( '[newsletter_form]' );
} elseif ( shortcode_exists( 'mailpoet_form' ) ) {
	$formulaire = do_shortcode( '[mailpoet_form id="' . (int) apply_filters( 'dsitd_mailpoet_form_id', 1 ) . '"]' );
}
$contact = home_url( '/contact/' );
?>
<div <?php echo get_block_wrapper_attributes( array( 'class' => 'dsitd-newsletter' ) ); ?>>
	<div class="dsitd-newsletter__texte">
		<h2>La lettre numérique de la DSITD</h2>
		<p>Chaque mois : nouveautés, alertes et astuces, directement dans votre boîte.</p>
	</div>
	<div class="dsitd-newsletter__formulaire">
		<?php if ( $formulaire ) : ?>
			<?php echo $formulaire; // phpcs:ignore WordPress.Security.EscapeOutput -- sortie de l'extension. ?>
		<?php else : ?>
			<a class="dsitd-bouton" href="<?php echo esc_url( add_query_arg( 'objet', 'lettre', $contact ) ); ?>">S’abonner</a>
		<?php endif; ?>
	</div>
</div>
