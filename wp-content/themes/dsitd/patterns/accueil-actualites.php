<?php
/**
 * Title: Accueil — Actualités
 * Slug: dsitd/accueil-actualites
 * Categories: dsitd-accueil
 * Inserter: yes
 *
 * @package dsitd
 */

?>
<!-- wp:group {"align":"wide","className":"dsitd-section","layout":{"type":"default"}} -->
<div class="wp-block-group alignwide dsitd-section"><!-- wp:group {"className":"dsitd-section__entete","layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between","verticalAlignment":"bottom"}} -->
<div class="wp-block-group dsitd-section__entete"><!-- wp:heading -->
<h2 class="wp-block-heading">Actualités</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"dsitd-filtres"} -->
<p class="dsitd-filtres"><a href="/category/vie-de-la-direction/">Vie de la direction</a> <a href="/category/plateformes/">Plateformes</a> <a href="/category/cybersecurite/">Cybersécurité</a> <a href="/category/transformation-digitale/">Transformation digitale</a> <a class="is-tout" href="/actualites/">Toutes les actualités</a></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:query {"queryId":11,"query":{"perPage":3,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"ignore","inherit":false},"className":"dsitd-actus"} -->
<div class="wp-block-query dsitd-actus"><!-- wp:post-template {"layout":{"type":"grid","columnCount":3}} -->
<!-- wp:post-featured-image {"isLink":true,"aspectRatio":"16/9","sizeSlug":"dsitd-carte","className":"dsitd-image-ou-defaut"} /-->

<!-- wp:post-terms {"term":"category","className":"is-style-etiquette"} /-->

<!-- wp:post-title {"level":3,"isLink":true,"className":"dsitd-titre-carte"} /-->

<!-- wp:post-excerpt {"excerptLength":22} /-->

<!-- wp:post-date {"format":"F Y"} /-->
<!-- /wp:post-template -->

<!-- wp:query-no-results -->
<!-- wp:paragraph {"className":"dsitd-vide"} -->
<p class="dsitd-vide">Aucune actualité publiée pour le moment.</p>
<!-- /wp:paragraph -->
<!-- /wp:query-no-results --></div>
<!-- /wp:query --></div>
<!-- /wp:group -->
