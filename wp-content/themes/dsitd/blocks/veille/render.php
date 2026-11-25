<?php
/**
 * Derniers articles de la catégorie « veille-sport-tech ».
 * Le surtitre reprend la première étiquette de l'article.
 *
 * @package dsitd
 */

defined( 'ABSPATH' ) || exit;

$nombre = max( 1, min( 10, (int) ( $attributes['nombre'] ?? 3 ) ) );
$posts  = get_posts(
	array(
		'post_type'     => 'post',
		'numberposts'   => $nombre,
		'category_name' => 'veille-sport-tech',
	)
);
?>
<div <?php echo get_block_wrapper_attributes( array( 'class' => 'dsitd-liste dsitd-veille' ) ); ?>>
	<?php if ( $posts ) : ?>
		<?php
		foreach ( $posts as $p ) :
			$tags = get_the_tags( $p->ID );
			?>
			<a class="dsitd-liste__item is-colonne" href="<?php echo esc_url( get_permalink( $p ) ); ?>">
				<span class="dsitd-surtitre"><?php echo esc_html( $tags ? $tags[0]->name : 'Veille' ); ?></span>
				<span class="dsitd-liste__titre"><?php echo esc_html( get_the_title( $p ) ); ?></span>
			</a>
		<?php endforeach; ?>
	<?php else : ?>
		<p class="dsitd-vide">Les articles de veille Sport Tech apparaîtront ici.</p>
	<?php endif; ?>
</div>
