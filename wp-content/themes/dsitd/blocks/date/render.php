<?php
/**
 * Date du jour, ex. « Côte d'Ivoire · mercredi 25 novembre 2026 ».
 *
 * @package dsitd
 */

defined( 'ABSPATH' ) || exit;
?>
<span <?php echo get_block_wrapper_attributes( array( 'class' => 'dsitd-date' ) ); ?>>
	<?php echo esc_html( "Côte d'Ivoire · " . wp_date( 'l j F Y' ) ); ?>
</span>
