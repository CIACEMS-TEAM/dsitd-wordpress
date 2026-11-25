# Contexte du projet — Site de la DSITD (WordPress)

> Fichier de passation entre sessions Claude Code. À lire en premier et à tenir à jour
> à la fin de chaque étape (section « État d'avancement »).

## Le projet

Refonte du site de la **DSITD** (Direction des Systèmes d'Information et de la Transformation
Digitale) du **Ministère des Sports de Côte d'Ivoire**. Langue : **français** (échanges avec
l'utilisateur compris).

Documents de référence fournis par l'utilisateur (non versionnés, à redemander si besoin) :
- *Refonte du site de la DSITD — Cahier de mise en œuvre WordPress* (PDF, 10 pages).
- Maquettes HTML statiques : `index.html` (accueil), `ladsitd.html`, `plateformes.html`,
  `services.html`, `cybersecurite.html`, `astuces.html`, `transformationdigitale.html`,
  `bibliotheque.html`, `actualites.html`, `contact.html`, `espaceagents.html`.

### Points clés du cahier
- Publics : agents des directions centrales, DR/DD, fédérations/ligues/clubs, partenaires, grand public.
- Charte gouv.ci (CICG) : police **Inter** ; vert **#0F7705** (principal) ; orange **#EF7D00**
  réservé aux titres ≥ 24 px (contraste insuffisant en petit) ; bouton orange **#E98F30** avec
  texte foncé ; rouge **#D80000 / #B00000** (bandeau « Flash DSITD ») ; texte #212529 ;
  gris #525252 / #E8E8E8 / #F5F5F5 ; pied de page **#0B0B0B**. Logo DSITD conservé tel quel.
- Arborescence (menu) : Accueil · La DSITD · Plateformes · Services & assistance · Cybersécurité ·
  Astuces & tutos · Transformation digitale · Bibliothèque · Actualités · Contact
  (+ Espace agents, connexion).
- Extensions prévues (< 20) : PublishPress Statuses/Capabilities, Members, GLPI ou Fluent Support,
  The Events Calendar, Fluent Forms ou CF7, Download Monitor, MailPoet ou The Newsletter Plugin,
  WP Mail SMTP, Wordfence ou Solid Security + Two Factor, Rank Math ou Yoast, Matomo,
  Smash Balloon (flux Facebook SIGS), UpdraftPlus, Polylang (phase 2).
- Circuit éditorial : Brouillon → Soumis → Relu (Chef Com) → Validé SD → Publié (Directeur).
- 20 premiers contenus listés dans le cahier (§6) ; plan éditorial lundi/mercredi/vendredi.
- Décisions encore ouvertes : coordonnées institutionnelles, GLPI vs Fluent Support, allègement
  du circuit, accès Espace agents, armoiries officielles (à obtenir auprès du CICG).

## Infrastructure

| Élément | Valeur |
|---|---|
| Site de **test / préproduction** | https://ecab2.mds-dsi.org (VPS + Dokploy) |
| Site **final** | Autre VPS et autre domaine, plus tard (même dépôt, nouvelles variables) |
| Dépôt | `ciacems-dev/dsitd-wordpress` (transféré depuis `CIACEMS-TEAM`) |
| Déploiement | Dokploy, service Compose existant, fournisseur GitHub, branche `main`, `./docker-compose.yml`, déclenchement **On Push** |
| Variables Dokploy | `DB_NAME`, `DB_PASSWORD`, `WP_DOMAIN` |
| WordPress | 7.1.5, PHP 8.3 (Apache), MySQL 8.4, langue fr_FR |

- **Ne jamais créer un nouveau service Dokploy** pour ce site : les volumes `wp_app` / `wp_data`
  (base + médias) sont liés au service existant.
- Le domaine et HTTPS sont gérés dans l'onglet *Domains* de Dokploy (labels Traefik ajoutés par Dokploy).
- Le thème est copié dans l'image puis synchronisé dans le volume à chaque démarrage
  (`docker/dsitd-entrypoint.sh`). Modifier le thème **dans le code**, pas dans l'éditeur de site
  (les retouches de l'éditeur de site vivent en base et ne suivent pas le dépôt).

### Accès à l'API WordPress
- Utilisateur WordPress **`CC_sys`** (administrateur) + mot de passe d'application.
- L'identifiant est enregistré comme *network secret* (type **Basic**) sur l'environnement cloud
  « Default » pour l'hôte `ecab2.mds-dsi.org` : le proxy l'injecte, ne jamais demander le mot de passe
  en clair. Test : `curl -s https://ecab2.mds-dsi.org/wp-json/wp/v2/users/me?context=edit`.
- Historique de dépannage : `rest_not_logged_in` venait d'un mot de passe d'application absent sur
  `CC_sys` (résolu). L'en-tête Authorization passe bien jusqu'à PHP.

## Le thème `wp-content/themes/dsitd`

Thème en blocs (FSE), **sans étape de compilation**.
- `theme.json` : palette (slugs `vert`, `vert-fonce`, `orange`, `orange-bouton`, `rouge`, `texte`,
  `gris`, `gris-bordure`, `fond-gris`, `noir`…), Inter auto-hébergée (`assets/fonts`), tailles.
- `assets/css/dsitd.css` : tout le style fin (préfixe de classes `dsitd-`).
- `parts/header.html`, `parts/footer.html` → compositions `patterns/header.php`, `patterns/footer.php`.
- `templates/` : front-page, page, single, index (actualités), archive, search, 404.
- `patterns/accueil-*.php` : sections de l'accueil (une, actualites, astuces, plateformes,
  services, ressources, lettre).
- `blocks/*` : blocs dynamiques `dsitd/…` (block.json + render.php), aperçu éditeur via
  ServerSideRender (`assets/js/blocks-editor.js`) : date, flash, carrousel, plateforme-liens,
  plateforme-statut, duree, newsletter, agenda, bibliotheque, veille, fil-ariane.
- `inc/post-types.php` : CPT `dsitd_plateforme` (/plateformes/), `dsitd_alerte` (/alertes-cyber/),
  `dsitd_astuce` (/astuces/) + taxonomie `dsitd_format` ; méta `dsitd_statut`, `dsitd_public`,
  `dsitd_url_acces`, `dsitd_url_tutoriel`, `dsitd_duree`, `dsitd_flash_jours` (panneau
  « Informations DSITD » : `assets/js/meta-panel.js`).
- `inc/navigation.php` : rubrique courante surlignée (liens personnalisés du menu).
- `inc/securite.php` : XML-RPC off, version masquée, `/wp/v2/users` réservé aux connectés.
- `inc/reglages-api.php` : expose `permalink_structure` dans `/wp/v2/settings` (pour le script de
  structure) et régénère les règles de réécriture quand elle change.
- Les liens internes sont **relatifs** (`/contact/`, `/la-dsitd/`…) : le thème est portable.
- Slugs attendus par le thème : pages `la-dsitd`, `la-dsitd/annuaire`, `services`, `cybersecurite`,
  `cybersecurite/signaler-un-incident`, `transformation-digitale`, `bibliotheque`, `actualites`
  (page des articles), `actualites/agenda`, `contact`, `espace-agents`, `mentions-legales`,
  `donnees-personnelles`, `accessibilite`, `plan-du-site` ; catégories `vie-de-la-direction`,
  `plateformes`, `cybersecurite`, `transformation-digitale`, `interoperabilite`, `veille-sport-tech`.

### Tester en local
Docker n'est pas démarré par défaut : lancer `dockerd` en arrière-plan. Docker Hub renvoie
souvent 429 : utiliser `mirror.gcr.io/library/wordpress:php8.3-apache`, `mirror.gcr.io/library/mysql:8.4`,
`mirror.gcr.io/library/wordpress:cli` (et `docker tag … wordpress:php8.3-apache` pour builder le
Dockerfile). Les conteneurs n'ont pas d'accès Internet : télécharger depuis l'hôte (ex. pack
fr_FR) puis `docker cp`. Comparer avec les maquettes par captures Playwright (1366 px et 390 px).

## Script de structure `scripts/structure.py`

Python 3 sans dépendance, **idempotent** : crée les pages / catégories / formats manquants, corrige
titres, parents, ordre et réglages, sans écraser le contenu d'une page existante.
`WP_URL=https://… python3 scripts/structure.py [--dry-run]` (+ `WP_USER` / `WP_APP_PASSWORD` hors de
l'environnement Claude, où le proxy injecte l'identifiant). Toute évolution de la structure passe
par ce script (pour pouvoir la rejouer sur le site final).

## Conventions
- Branche par étape, pull request vers `main` à faire valider par l'utilisateur (la fusion
  déclenche le déploiement). Messages de commit en français.
- Ne pas inventer d'URL externes (réseaux sociaux, CI-CERT…) : laisser en texte jusqu'à
  confirmation.

## État d'avancement

- [x] Connexion API WordPress (CC_sys) et correction du domaine (siteurl/home).
- [x] **Étape 1** — Thème : socle, en-tête, pied de page, accueil fidèle à la maquette, CPT,
      Dockerfile/compose (PR #1), image d'aperçu + menu écrans moyens, v0.1.1 (PR #2).
      Thème déployé sur ecab2.mds-dsi.org.
- [x] Thème « DSITD » actif sur le site de test.
- [x] **Étape 2** — `scripts/structure.py` exécuté sur ecab2 : 16 pages (accueil statique, page des
      articles `actualites`, l'ancienne « Politique de confidentialité » devenue `donnees-personnelles`),
      6 catégories, 4 formats (Fiche pratique, Vidéo, Pas à pas, Infographie — à confirmer), titre,
      slogan, fuseau Africa/Abidjan, 9 articles par page, commentaires fermés par défaut, exemples
      WordPress à la corbeille. Thème v0.2.0 (`inc/reglages-api.php`).
      **Reste** : après fusion/déploiement du thème 0.2.0, relancer le script pour passer les
      permaliens de `/%year%/%monthnum%/%day%/%postname%/` à `/%postname%/`.
- [ ] **Étape 3** — Gabarits des rubriques (fiche plateforme, archive plateformes, alerte, astuce,
      document) d'après les maquettes, puis extensions du cahier.
- [ ] Contenus : armoiries officielles, coordonnées du pied de page, liens réseaux sociaux,
      20 contenus de lancement, mot du Directeur (texte dans le cahier §2 ter).
