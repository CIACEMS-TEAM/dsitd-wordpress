<?php
/**
 * Title: En-tête DSITD
 * Slug: dsitd/header
 * Categories: header
 * Block Types: core/template-part/header
 * Inserter: no
 *
 * @package dsitd
 */

$img = esc_url( get_theme_file_uri( 'assets/images/' ) );
?>
<!-- wp:group {"tagName":"div","className":"dsitd-topbar","layout":{"type":"constrained"}} -->
<div class="wp-block-group dsitd-topbar"><!-- wp:group {"align":"wide","layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between"}} -->
<div class="wp-block-group alignwide"><!-- wp:dsitd/date /-->

<!-- wp:group {"className":"dsitd-topbar__liens","layout":{"type":"flex","flexWrap":"wrap"}} -->
<div class="wp-block-group dsitd-topbar__liens"><!-- wp:paragraph -->
<p><a href="/la-dsitd/annuaire/">Annuaire</a></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"is-incident"} -->
<p class="is-incident"><a href="/cybersecurite/signaler-un-incident/">Signaler un incident</a></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"is-agents"} -->
<p class="is-agents"><a href="/espace-agents/">Espace agents</a></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"is-reseaux"} -->
<p class="is-reseaux">Facebook · X · YouTube</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:group {"tagName":"div","className":"dsitd-entete","layout":{"type":"constrained"}} -->
<div class="wp-block-group dsitd-entete"><!-- wp:group {"align":"wide","layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between"}} -->
<div class="wp-block-group alignwide"><!-- wp:group {"className":"dsitd-identite","layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group dsitd-identite"><!-- wp:image {"width":"60px","height":"60px","scale":"contain","sizeSlug":"full","linkDestination":"custom","className":"dsitd-armoiries"} -->
<figure class="wp-block-image size-full is-resized dsitd-armoiries"><a href="/"><img src="<?php echo $img; ?>armoiries-emplacement.svg" alt="Armoiries de la République de Côte d'Ivoire" style="object-fit:contain;width:60px;height:60px"/></a></figure>
<!-- /wp:image -->

<!-- wp:paragraph {"className":"dsitd-intitule"} -->
<p class="dsitd-intitule"><a href="/">République de Côte d'Ivoire<br>Ministère des Sports</a></p>
<!-- /wp:paragraph -->

<!-- wp:image {"width":"auto","height":"66px","sizeSlug":"full","linkDestination":"custom","className":"dsitd-logo"} -->
<figure class="wp-block-image size-full is-resized dsitd-logo"><a href="/"><img src="<?php echo $img; ?>logo-dsitd.webp" alt="DSITD — Direction des Systèmes d'Information et de la Transformation Digitale, accueil" style="width:auto;height:66px"/></a></figure>
<!-- /wp:image --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"dsitd-entete__actions","layout":{"type":"flex","flexWrap":"wrap"}} -->
<div class="wp-block-group dsitd-entete__actions"><!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-orange"} -->
<div class="wp-block-button is-style-orange"><a class="wp-block-button__link wp-element-button" href="/contact/">Écrire à la DSITD</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->

<!-- wp:search {"label":"Rechercher sur le site","showLabel":false,"placeholder":"Rechercher…","buttonText":"Lancer la recherche","buttonPosition":"button-inside","buttonUseIcon":true,"className":"dsitd-recherche"} /--></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:group {"tagName":"div","className":"dsitd-menu","layout":{"type":"constrained"}} -->
<div class="wp-block-group dsitd-menu"><!-- wp:navigation {"textColor":"blanc","overlayBackgroundColor":"vert","overlayTextColor":"blanc","overlayMenu":"mobile","align":"wide","ariaLabel":"Navigation principale","style":{"spacing":{"blockGap":"0px"}},"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"left"}} -->
<!-- wp:navigation-link {"label":"Accueil","url":"/","kind":"custom"} /-->
<!-- wp:navigation-link {"label":"La DSITD","url":"/la-dsitd/","kind":"custom"} /-->
<!-- wp:navigation-link {"label":"Plateformes","url":"/plateformes/","kind":"custom"} /-->
<!-- wp:navigation-link {"label":"Services & assistance","url":"/services/","kind":"custom"} /-->
<!-- wp:navigation-link {"label":"Cybersécurité","url":"/cybersecurite/","kind":"custom"} /-->
<!-- wp:navigation-link {"label":"Astuces & tutos","url":"/astuces/","kind":"custom"} /-->
<!-- wp:navigation-link {"label":"Transformation digitale","url":"/transformation-digitale/","kind":"custom"} /-->
<!-- wp:navigation-link {"label":"Bibliothèque","url":"/bibliotheque/","kind":"custom"} /-->
<!-- wp:navigation-link {"label":"Actualités","url":"/actualites/","kind":"custom"} /-->
<!-- wp:navigation-link {"label":"Contact","url":"/contact/","kind":"custom"} /-->
<!-- /wp:navigation --></div>
<!-- /wp:group -->

<!-- wp:group {"tagName":"div","className":"dsitd-flash-zone","layout":{"type":"constrained"}} -->
<div class="wp-block-group dsitd-flash-zone"><!-- wp:dsitd/flash {"align":"wide"} /--></div>
<!-- /wp:group -->
