# Site de la DSITD — WordPress

Site de la Direction des Systèmes d'Information et de la Transformation Digitale (DSITD) du Ministère des Sports de Côte d'Ivoire.

## Contenu du dépôt

| Chemin | Rôle |
|---|---|
| `wp-content/themes/dsitd/` | Thème en blocs sur mesure (charte gouv.ci : Inter, vert `#0F7705`, orange `#EF7D00`) |
| `Dockerfile` | Image `wordpress:php8.3-apache` + thème |
| `docker/dsitd-entrypoint.sh` | Copie le thème dans le volume WordPress à chaque démarrage |
| `docker-compose.yml` | Déploiement Dokploy (WordPress + MySQL 8.4) |
| `scripts/structure.py` | Structure du site (pages, catégories, formats, réglages) via l'API REST, rejouable |

## Le thème `dsitd`

- **`theme.json`** : couleurs, police Inter (auto-hébergée), tailles et espacements.
- **`parts/` et `patterns/header.php`, `patterns/footer.php`** : en-tête (armoiries, logo, menu vert, bandeau « Flash DSITD ») et pied de page noir.
- **`templates/`** : accueil, page, article, archives, recherche, 404.
- **`patterns/accueil-*.php`** : les sections de l'accueil, réutilisables dans l'éditeur (catégorie « DSITD — Accueil »).
- **`blocks/`** : blocs dynamiques rendus côté serveur (date, Flash DSITD, carrousel, liens de plateforme, durée, agenda, bibliothèque, veille, lettre d'information, fil d'Ariane). Aucune compilation n'est nécessaire.
- **`inc/post-types.php`** : types de contenu *Plateformes*, *Alertes cyber*, *Astuces & tutos* (+ taxonomie *Format*) et leurs champs, modifiables dans le panneau « Informations DSITD » de l'éditeur.

### Alimentation de l'accueil

| Section | Source |
|---|---|
| Flash DSITD | Dernière *Alerte cyber* publiée depuis moins de N jours (champ « Jours dans le bandeau », 7 par défaut). Masqué s'il n'y en a pas. |
| Carrousel | Articles **épinglés** (avec image à la une) ; à défaut, les plus récents. |
| Actualités | 3 derniers articles. |
| Astuce de la semaine / Tutoriels | Dernière *Astuce* / 4 suivantes (format + durée). |
| Nos plateformes | *Plateformes*, dans l'ordre du champ « Ordre ». |
| Bibliothèque | Download Monitor s'il est installé, sinon derniers PDF de la médiathèque. |
| Formations & agenda | The Events Calendar s'il est installé. |
| Veille Sport Tech | Articles de la catégorie `veille-sport-tech` (surtitre = première étiquette). |
| Lettre d'information | MailPoet ou The Newsletter Plugin s'ils sont installés, sinon lien vers Contact. |

Accès rapides, services et indicateurs sont des blocs statiques, modifiables dans l'éditeur de site (Apparence → Éditeur → Modèles → Page d'accueil).

## Déploiement (Dokploy)

Le service Compose existant de Dokploy est relié à ce dépôt (branche `main`). Chaque déploiement reconstruit l'image ; au démarrage, le thème est recopié dans le volume `wp_app`. La base (`wp_data`), les médias et les extensions sont conservés.

Variables d'environnement attendues dans Dokploy : `DB_NAME`, `DB_PASSWORD`, `WP_DOMAIN` (ex. `ecab2.mds-dsi.org`).

## Structure du site (`scripts/structure.py`)

Crée ou remet d'aplomb, via l'API REST, l'arborescence des pages, la page d'accueil statique et la
page Actualités, les catégories, les formats d'astuces, le titre, le fuseau horaire et les
permaliens. Le script est idempotent (il n'écrase pas le contenu des pages existantes) : on peut le
relancer à volonté, notamment pour préparer le site final.

```bash
WP_URL=https://exemple.ci WP_USER=admin WP_APP_PASSWORD="xxxx xxxx xxxx xxxx xxxx xxxx" \
  python3 scripts/structure.py --dry-run
```

Retirer `--dry-run` pour appliquer. Le mot de passe d'application se crée dans *Utilisateurs →
Profil*. Le réglage des permaliens par l'API nécessite le thème DSITD 0.2.0 ou plus.

## Travailler en local

```bash
docker compose -f docker-compose.yml build
```

Pour un environnement de test complet, monter `wp-content/themes/dsitd` dans un conteneur `wordpress` et activer le thème « DSITD ».
