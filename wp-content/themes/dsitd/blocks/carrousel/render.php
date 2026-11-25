<?php
/**
 * Carrousel des actualités : articles épinglés, sinon les plus récents.
 *
 * @package dsitd
 */

defined( 'ABSPATH' ) || exit;

$nombre  = max( 1, min( 10, (int) ( $attributes['nombre'] ?? 5 ) ) );
$sticky  = get_option( 'sticky_posts' );
$args    = array(
	'post_type'           => 'post',
	'posts_per_page'      => $nombre,
	'ignore_sticky_posts' => true,
	'no_found_rows'       => true,
);
if ( $sticky ) {
	$args['post__in'] = $sticky;
}
$q = new WP_Query( $args );
if ( ! $q->have_posts() && $sticky ) {
	unset( $args['post__in'] );
	$q = new WP_Query( $args );
}
if ( ! $q->have_posts() ) {
	echo '<div ' . get_block_wrapper_attributes( array( 'class' => 'dsitd-carrousel is-vide' ) ) . '><p>Les actualités épinglées s’afficheront ici.</p></div>';
	return;
}
$total = $q->post_count;
?>
<div <?php echo get_block_wrapper_attributes( array( 'class' => 'dsitd-carrousel' ) ); ?> data-dsitd-carrousel aria-roledescription="carrousel" aria-label="Actualités à la une">
	<div class="dsitd-carrousel__piste">
		<?php
		$i = 0;
		while ( $q->have_posts() ) :
			$q->the_post();
			$cat = get_the_category();
			?>
			<article class="dsitd-carrousel__diapo<?php echo 0 === $i ? ' is-active' : ''; ?>" aria-roledescription="diapositive" aria-label="<?php echo esc_attr( ( $i + 1 ) . ' sur ' . $total ); ?>"<?php echo 0 === $i ? '' : ' hidden'; ?>>
				<?php if ( has_post_thumbnail() ) : ?>
					<?php the_post_thumbnail( 'dsitd-une', array( 'class' => 'dsitd-carrousel__image', 'loading' => 0 === $i ? 'eager' : 'lazy', 'alt' => '' ) ); ?>
				<?php endif; ?>
				<div class="dsitd-carrousel__legende">
					<?php if ( $cat ) : ?>
						<span class="dsitd-etiquette"><?php echo esc_html( $cat[0]->name ); ?></span>
					<?php endif; ?>
					<h2 class="dsitd-carrousel__titre"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
				</div>
			</article>
			<?php
			++$i;
		endwhile;
		wp_reset_postdata();
		?>
	</div>
	<?php if ( $total > 1 ) : ?>
		<button type="button" class="dsitd-carrousel__nav is-prec" aria-label="Actualité précédente">‹</button>
		<button type="button" class="dsitd-carrousel__nav is-suiv" aria-label="Actualité suivante">›</button>
		<div class="dsitd-carrousel__points">
			<?php for ( $j = 0; $j < $total; $j++ ) : ?>
				<button type="button" aria-label="<?php echo esc_attr( 'Afficher l’actualité ' . ( $j + 1 ) ); ?>"<?php echo 0 === $j ? ' aria-current="true"' : ''; ?>></button>
			<?php endfor; ?>
		</div>
	<?php endif; ?>
</div>
