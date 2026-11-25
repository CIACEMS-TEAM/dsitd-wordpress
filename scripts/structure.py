#!/usr/bin/env python3
"""Structure du site de la DSITD, via l'API REST de WordPress.

Script rejouable (idempotent) : il crée ce qui manque et remet d'aplomb les
titres, parents et réglages, sans jamais écraser le contenu d'une page déjà
existante. On peut donc le relancer sans risque, sur le site de test comme
sur le site final.

Ce qu'il met en place :
  - les pages de l'arborescence (slugs attendus par le thème) ;
  - la page d'accueil statique et la page des articles (Actualités) ;
  - les catégories d'articles et les formats d'astuces ;
  - le titre, le slogan, le fuseau horaire et les réglages de discussion ;
  - la structure des permaliens (nécessite le thème DSITD >= 0.2.0) ;
  - la mise à la corbeille du contenu d'exemple de WordPress.

Utilisation :
    WP_URL=https://ecab2.mds-dsi.org python3 scripts/structure.py [--dry-run]

Authentification (mot de passe d'application d'un administrateur) :
    WP_USER=…  WP_APP_PASSWORD="xxxx xxxx xxxx xxxx xxxx xxxx"
Sans ces variables, aucune en-tête n'est envoyée (cas de l'environnement
Claude Code, où le proxy injecte lui-même l'identifiant).

Aucune dépendance : Python 3.8+ et sa bibliothèque standard.
"""

from __future__ import annotations

import argparse
import base64
import html
import json
import os
import sys
import urllib.error
import urllib.parse
import urllib.request

# --------------------------------------------------------------------------
# Données
# --------------------------------------------------------------------------

REGLAGES = {
    "title": "DSITD — Ministère des Sports",
    "description": "Direction des Systèmes d'Information et de la Transformation Digitale",
    "timezone": "Africa/Abidjan",
    "date_format": "j F Y",
    "time_format": "G\\hi",
    "start_of_week": 1,
    "posts_per_page": 9,
    # Site institutionnel : pas de commentaires ni de rétroliens par défaut.
    "default_comment_status": "closed",
    "default_ping_status": "closed",
}

PERMALIENS = "/%postname%/"


def para(texte: str) -> str:
    return f"<!-- wp:paragraph -->\n<p>{texte}</p>\n<!-- /wp:paragraph -->"


EN_COURS = para("Cette page est en cours de rédaction.")

# (slug, titre, parent, ordre, contenu initial)
# « plateformes » et « astuces » ne sont pas des pages : ce sont les archives
# des types de contenu du thème. Ne pas créer de page portant ces slugs.
PAGES = [
    ("accueil", "Accueil", None, 0, ""),
    ("la-dsitd", "La DSITD", None, 10, EN_COURS),
    ("annuaire", "Annuaire", "la-dsitd", 11, EN_COURS),
    ("services", "Services & assistance", None, 20, EN_COURS),
    ("cybersecurite", "Cybersécurité", None, 30, EN_COURS),
    ("signaler-un-incident", "Signaler un incident", "cybersecurite", 31, EN_COURS),
    ("transformation-digitale", "Transformation digitale", None, 40, EN_COURS),
    ("bibliotheque", "Bibliothèque", None, 50, EN_COURS),
    ("actualites", "Actualités", None, 60, ""),
    ("agenda", "Agenda", "actualites", 61, EN_COURS),
    ("contact", "Contact", None, 70, EN_COURS),
    ("espace-agents", "Espace agents", None, 80, EN_COURS),
    ("mentions-legales", "Mentions légales", None, 90, EN_COURS),
    ("donnees-personnelles", "Données personnelles", None, 91, EN_COURS),
    ("accessibilite", "Accessibilité", None, 92, EN_COURS),
    ("plan-du-site", "Plan du site", None, 93,
     '<!-- wp:page-list {"className":"dsitd-plan-du-site"} /-->'),
]

PAGE_ACCUEIL = "accueil"
PAGE_ARTICLES = "actualites"

# La page de confidentialité créée par WordPress est réutilisée (elle reste
# ainsi liée au réglage « Confidentialité » de l'administration). Son texte
# modèle, rédigé pour un site générique, est remplacé lors du renommage.
RENOMMAGES = {"donnees-personnelles": "politique-de-confidentialite"}

# (slug, nom, description)
CATEGORIES = [
    ("vie-de-la-direction", "Vie de la direction", "Activités, missions et équipes de la DSITD."),
    ("plateformes", "Plateformes", "Nouveautés et évolutions des plateformes numériques du ministère."),
    ("cybersecurite", "Cybersécurité", "Bonnes pratiques, sensibilisation et alertes."),
    ("transformation-digitale", "Transformation digitale", "Projets de modernisation et de dématérialisation."),
    ("interoperabilite", "Interopérabilité", "Échanges de données entre les systèmes du ministère et de ses partenaires."),
    ("veille-sport-tech", "Veille Sport Tech", "Innovations numériques dans le monde du sport."),
]

# (slug, nom) — taxonomie dsitd_format des astuces & tutos.
FORMATS = [
    ("fiche-pratique", "Fiche pratique"),
    ("video", "Vidéo"),
    ("pas-a-pas", "Pas à pas"),
    ("infographie", "Infographie"),
]

# Contenu d'exemple installé par WordPress, mis à la corbeille.
EXEMPLES = [("pages", "page-d-exemple"), ("posts", "bonjour-tout-le-monde")]

# --------------------------------------------------------------------------
# Client REST minimal
# --------------------------------------------------------------------------


class ErreurAPI(Exception):
    pass


class API:
    def __init__(self, base: str, utilisateur: str | None, mot_de_passe: str | None, essai: bool):
        self.base = base.rstrip("/") + "/wp-json"
        self.essai = essai
        self.entetes = {"Accept": "application/json", "User-Agent": "dsitd-structure/1.0"}
        if utilisateur and mot_de_passe:
            jeton = base64.b64encode(f"{utilisateur}:{mot_de_passe}".encode()).decode()
            self.entetes["Authorization"] = f"Basic {jeton}"

    def requete(self, methode: str, chemin: str, donnees: dict | None = None, params: dict | None = None):
        url = self.base + chemin
        if params:
            url += "?" + urllib.parse.urlencode(params)
        corps = None
        entetes = dict(self.entetes)
        if donnees is not None:
            corps = json.dumps(donnees).encode()
            entetes["Content-Type"] = "application/json; charset=utf-8"
        req = urllib.request.Request(url, data=corps, headers=entetes, method=methode)
        try:
            with urllib.request.urlopen(req, timeout=60) as rep:
                return json.loads(rep.read().decode() or "null")
        except urllib.error.HTTPError as e:
            detail = e.read().decode(errors="replace")
            try:
                detail = json.loads(detail).get("message", detail)
            except (ValueError, AttributeError):
                pass
            raise ErreurAPI(f"{methode} {chemin} → HTTP {e.code} : {detail}") from None

    def get(self, chemin: str, **params):
        return self.requete("GET", chemin, params=params)

    def ecrire(self, methode: str, chemin: str, donnees: dict | None = None, params: dict | None = None):
        """Requête d'écriture (ignorée en mode --dry-run)."""
        if self.essai:
            return None
        return self.requete(methode, chemin, donnees, params)


# --------------------------------------------------------------------------
# Étapes
# --------------------------------------------------------------------------


def egal(actuel, souhait) -> bool:
    """Compare en ignorant l'échappement HTML appliqué par WordPress (&#039;…)."""
    if isinstance(actuel, str) and isinstance(souhait, str):
        return html.unescape(actuel) == souhait
    return actuel == souhait


def journal(symbole: str, message: str) -> None:
    print(f"  {symbole} {message}")


def verifier_acces(api: API) -> None:
    moi = api.get("/wp/v2/users/me", context="edit")
    if "administrator" not in moi.get("roles", []):
        raise ErreurAPI(f"L'utilisateur {moi.get('username')} n'est pas administrateur.")
    print(f"Connecté en tant que {moi.get('username')} sur {api.base}")


def chercher_page(api: API, slug: str, parent: int | None = None) -> dict | None:
    params = {"slug": slug, "status": "publish,draft,pending,private,future", "context": "edit",
              "_fields": "id,slug,status,parent,title,menu_order"}
    resultats = api.get("/wp/v2/pages", **params)
    if parent is not None:
        resultats = [p for p in resultats if p["parent"] == parent] or resultats
    return resultats[0] if resultats else None


def assurer_pages(api: API) -> dict[str, int]:
    print("Pages")
    ids: dict[str, int] = {}
    for slug, titre, parent_slug, ordre, contenu in PAGES:
        parent_id = ids.get(parent_slug, 0) if parent_slug else 0
        page = chercher_page(api, slug, parent_id)
        if page is None and slug in RENOMMAGES:
            page = chercher_page(api, RENOMMAGES[slug])
            if page:
                journal("~", f"{RENOMMAGES[slug]} → {slug} (page réutilisée)")
        chemin = f"{parent_slug}/{slug}" if parent_slug else slug
        souhait = {"slug": slug, "title": titre, "parent": parent_id, "menu_order": ordre, "status": "publish"}
        if page and page["slug"] != slug:
            souhait["content"] = contenu
            page["content"] = None
        if page is None:
            cree = api.ecrire("POST", "/wp/v2/pages", {**souhait, "content": contenu})
            ids[slug] = cree["id"] if cree else -1
            journal("+", f"/{chemin}/ créée")
            continue
        ids[slug] = page["id"]
        actuel = {"slug": page["slug"], "title": page["title"]["raw"], "parent": page["parent"],
                  "menu_order": page["menu_order"], "status": page["status"], "content": page.get("content")}
        ecarts = {k: v for k, v in souhait.items() if not egal(actuel[k], v)}
        if ecarts:
            api.ecrire("POST", f"/wp/v2/pages/{page['id']}", ecarts)
            journal("~", f"/{chemin}/ mise à jour ({', '.join(ecarts)})")
        else:
            journal("=", f"/{chemin}/")
    return ids


def assurer_termes(api: API, route: str, titre: str, termes: list[tuple]) -> None:
    print(titre)
    existants = {t["slug"]: t for t in api.get(route, per_page=100, hide_empty="false", context="edit",
                                               _fields="id,slug,name,description")}
    for slug, nom, *reste in termes:
        souhait = {"slug": slug, "name": nom}
        if reste:
            souhait["description"] = reste[0]
        terme = existants.get(slug)
        if terme is None:
            api.ecrire("POST", route, souhait)
            journal("+", f"{nom} ({slug})")
            continue
        ecarts = {k: v for k, v in souhait.items() if not egal(terme.get(k), v)}
        if ecarts:
            api.ecrire("POST", f"{route}/{terme['id']}", ecarts)
            journal("~", f"{nom} ({', '.join(ecarts)})")
        else:
            journal("=", nom)


def assurer_reglages(api: API, ids: dict[str, int]) -> None:
    print("Réglages")
    actuels = api.get("/wp/v2/settings")
    souhait = dict(REGLAGES)
    souhait.update({"show_on_front": "page", "page_on_front": ids[PAGE_ACCUEIL],
                    "page_for_posts": ids[PAGE_ARTICLES]})
    if "permalink_structure" in actuels:
        souhait["permalink_structure"] = PERMALIENS
    else:
        journal("!", "permaliens non modifiables par l'API : déployer le thème DSITD >= 0.2.0 "
                     "puis relancer (ou Réglages → Permaliens → « Titre de la publication »).")
    ecarts = {k: v for k, v in souhait.items() if not egal(actuels.get(k), v)}
    if not ecarts:
        journal("=", "aucun changement")
        return
    api.ecrire("POST", "/wp/v2/settings", ecarts)
    for cle, valeur in ecarts.items():
        journal("~", f"{cle} : {actuels.get(cle)!r} → {valeur!r}")


def nettoyer_exemples(api: API) -> None:
    print("Contenu d'exemple")
    for route, slug in EXEMPLES:
        for element in api.get(f"/wp/v2/{route}", slug=slug, status="publish,draft", context="edit",
                               _fields="id,slug"):
            api.ecrire("DELETE", f"/wp/v2/{route}/{element['id']}")
            journal("-", f"{slug} mis à la corbeille")


def main() -> int:
    parser = argparse.ArgumentParser(description=__doc__.split("\n\n")[0])
    parser.add_argument("--url", default=os.environ.get("WP_URL"), help="adresse du site (ou WP_URL)")
    parser.add_argument("--dry-run", action="store_true", help="affiche les changements sans les appliquer")
    args = parser.parse_args()
    if not args.url:
        parser.error("indiquer l'adresse du site avec --url ou WP_URL")

    api = API(args.url, os.environ.get("WP_USER"), os.environ.get("WP_APP_PASSWORD"), args.dry_run)
    try:
        verifier_acces(api)
        if args.dry_run:
            print("(essai à blanc : aucune modification ne sera faite)")
        ids = assurer_pages(api)
        assurer_termes(api, "/wp/v2/categories", "Catégories", CATEGORIES)
        assurer_termes(api, "/wp/v2/dsitd_format", "Formats d'astuces", FORMATS)
        assurer_reglages(api, ids)
        nettoyer_exemples(api)
    except ErreurAPI as e:
        print(f"Erreur : {e}", file=sys.stderr)
        return 1
    print("Terminé.")
    return 0


if __name__ == "__main__":
    sys.exit(main())
