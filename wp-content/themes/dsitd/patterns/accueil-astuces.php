<?php
/**
 * Title: Accueil — Astuce de la semaine et tutoriels
 * Slug: dsitd/accueil-astuces
 * Categories: dsitd-accueil
 * Inserter: yes
 *
 * @package dsitd
 */

?>
<!-- wp:group {"align":"wide","className":"dsitd-section dsitd-astuces","layout":{"type":"default"}} -->
<div class="wp-block-group alignwide dsitd-section dsitd-astuces"><!-- wp:query {"queryId":12,"query":{"perPage":1,"pages":0,"offset":0,"postType":"dsitd_astuce","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"ignore","inherit":false},"className":"dsitd-astuce-semaine"} -->
<div class="wp-block-query dsitd-astuce-semaine"><!-- wp:post-template -->
<!-- wp:paragraph {"className":"dsitd-astuce-semaine__label"} -->
<p class="dsitd-astuce-semaine__label">Astuce de la semaine</p>
<!-- /wp:paragraph -->

<!-- wp:post-title {"level":3,"isLink":true} /-->

<!-- wp:post-excerpt {"excerptLength":35} /-->
<!-- /wp:post-template -->

<!-- wp:query-no-results -->
<!-- wp:paragraph {"className":"dsitd-astuce-semaine__label"} -->
<p class="dsitd-astuce-semaine__label">Astuce de la semaine</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>La première astuce sera publiée lundi.</p>
<!-- /wp:paragraph -->
<!-- /wp:query-no-results -->

<!-- wp:paragraph {"className":"dsitd-astuce-semaine__lien"} -->
<p class="dsitd-astuce-semaine__lien"><a href="/astuces/">Toutes les astuces</a></p>
<!-- /wp:paragraph --></div>
<!-- /wp:query -->

<!-- wp:group {"className":"dsitd-tutos","layout":{"type":"default"}} -->
<div class="wp-block-group dsitd-tutos"><!-- wp:heading -->
<h2 class="wp-block-heading">Tutoriels &amp; guides pratiques</h2>
<!-- /wp:heading -->

<!-- wp:query {"queryId":13,"query":{"perPage":4,"pages":0,"offset":1,"postType":"dsitd_astuce","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"ignore","inherit":false},"className":"dsitd-liste-tutos"} -->
<div class="wp-block-query dsitd-liste-tutos"><!-- wp:post-template -->
<!-- wp:group {"className":"dsitd-tuto","layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group dsitd-tuto"><!-- wp:post-terms {"term":"dsitd_format","className":"is-style-etiquette"} /-->

<!-- wp:post-title {"level":3,"isLink":true,"className":"dsitd-tuto__titre"} /-->

<!-- wp:dsitd/duree /--></div>
<!-- /wp:group -->
<!-- /wp:post-template -->

<!-- wp:query-no-results -->
<!-- wp:paragraph {"className":"dsitd-vide"} -->
<p class="dsitd-vide">Les fiches pratiques et vidéos courtes arrivent bientôt.</p>
<!-- /wp:paragraph -->
<!-- /wp:query-no-results --></div>
<!-- /wp:query --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
