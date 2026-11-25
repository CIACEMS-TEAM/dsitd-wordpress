<?php
/**
 * Title: Accueil — Nos plateformes
 * Slug: dsitd/accueil-plateformes
 * Categories: dsitd-accueil
 * Inserter: yes
 *
 * @package dsitd
 */

?>
<!-- wp:group {"align":"full","className":"dsitd-bande-grise","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull dsitd-bande-grise"><!-- wp:group {"align":"wide","layout":{"type":"default"}} -->
<div class="wp-block-group alignwide"><!-- wp:group {"className":"dsitd-section__entete","layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between","verticalAlignment":"bottom"}} -->
<div class="wp-block-group dsitd-section__entete"><!-- wp:heading -->
<h2 class="wp-block-heading">Nos plateformes</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"dsitd-lien-fort"} -->
<p class="dsitd-lien-fort"><a href="/plateformes/">Catalogue complet</a></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:query {"queryId":14,"query":{"perPage":4,"pages":0,"offset":0,"postType":"dsitd_plateforme","order":"asc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"ignore","inherit":false},"className":"dsitd-plateformes"} -->
<div class="wp-block-query dsitd-plateformes"><!-- wp:post-template {"layout":{"type":"grid","columnCount":4}} -->
<!-- wp:post-title {"level":3,"isLink":true,"className":"dsitd-plateforme__titre"} /-->

<!-- wp:post-excerpt {"excerptLength":25} /-->

<!-- wp:dsitd/plateforme-liens /-->
<!-- /wp:post-template -->

<!-- wp:query-no-results -->
<!-- wp:paragraph {"className":"dsitd-vide"} -->
<p class="dsitd-vide">Les fiches des plateformes (SIGS, JTS, EMCS-CI…) seront publiées ici.</p>
<!-- /wp:paragraph -->
<!-- /wp:query-no-results --></div>
<!-- /wp:query --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
