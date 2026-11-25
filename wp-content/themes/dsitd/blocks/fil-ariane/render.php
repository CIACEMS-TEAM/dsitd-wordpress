<?php
/**
 * Fil d'Ariane accessible.
 *
 * @package dsitd
 */

defined( 'ABSPATH' ) || exit;

if ( is_front_page() ) {
	return;
}

$items = array( array( 'Accueil', home_url( '/' ) ) );

if ( is_singular() ) {
	$post = get_queried_object();
	if ( 'post' === $post->post_type ) {
		$page_articles = (int) get_option( 'page_for_posts' );
		$items[]       = array( 'Actualités', $page_articles ? get_permalink( $page_articles ) : home_url( '/actualites/' ) );
	} elseif ( 'page' !== $post->post_type ) {
		$pto = get_post_type_object( $post->post_type );
		if ( $pto && $pto->has_archive ) {
			$items[] = array( $pto->labels->name, get_post_type_archive_link( $post->post_type ) );
		}
	}
	foreach ( array_reverse( get_post_ancestors( $post ) ) as $ancetre ) {
		$items[] = array( get_the_title( $ancetre ), get_permalink( $ancetre ) );
	}
	$items[] = array( get_the_title( $post ), '' );
} elseif ( is_post_type_archive() ) {
	$items[] = array( post_type_archive_title( '', false ), '' );
} elseif ( is_home() ) {
	$items[] = array( 'Actualités', '' );
} elseif ( is_category() || is_tag() || is_tax() ) {
	$items[] = array( single_term_title( '', false ), '' );
} elseif ( is_search() ) {
	$items[] = array( 'Recherche', '' );
} elseif ( is_404() ) {
	$items[] = array( 'Page introuvable', '' );
} elseif ( is_archive() ) {
	$items[] = array( wp_strip_all_tags( get_the_archive_title() ), '' );
}
?>
<nav <?php echo get_block_wrapper_attributes( array( 'class' => 'dsitd-fil-ariane', 'aria-label' => 'Fil d’Ariane' ) ); ?>>
	<ol>
		<?php foreach ( $items as $i => list( $libelle, $url ) ) : ?>
			<li>
				<?php if ( $url && $i < count( $items ) - 1 ) : ?>
					<a href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( $libelle ); ?></a>
				<?php else : ?>
					<span aria-current="page"><?php echo esc_html( $libelle ); ?></span>
				<?php endif; ?>
			</li>
		<?php endforeach; ?>
	</ol>
</nav>
