#!/bin/bash
# Synchronise le thème livré dans l'image vers le volume WordPress,
# puis lance la commande demandée (par défaut l'entrypoint officiel).
set -e

SRC=/usr/src/dsitd/themes/dsitd
THEMES=/var/www/html/wp-content/themes
CIBLE="$THEMES/dsitd"
TEMP="$THEMES/dsitd.new"

sync_theme() {
	for _ in $(seq 1 120); do
		if [ -d "$THEMES" ]; then
			rm -rf -- "${TEMP:?}"
			cp -a "$SRC" "$TEMP"
			chown -R www-data:www-data "$TEMP"
			rm -rf -- "${CIBLE:?}"
			mv "$TEMP" "$CIBLE"
			echo "[dsitd] Thème synchronisé."
			return 0
		fi
		sleep 1
	done
	echo "[dsitd] wp-content introuvable : thème non synchronisé." >&2
}

if [ -d "$THEMES" ]; then
	sync_theme
else
	# Premier démarrage : l'entrypoint officiel copie d'abord WordPress.
	sync_theme &
fi

exec "$@"
