# Image WordPress de la DSITD : image officielle + thème « dsitd ».
FROM wordpress:php8.3-apache

# Le thème est copié dans l'image puis synchronisé dans le volume
# /var/www/html à chaque démarrage (voir docker/dsitd-entrypoint.sh).
COPY wp-content/themes/dsitd /usr/src/dsitd/themes/dsitd
COPY docker/dsitd-entrypoint.sh /usr/local/bin/dsitd-entrypoint.sh
RUN chmod +x /usr/local/bin/dsitd-entrypoint.sh

ENTRYPOINT ["dsitd-entrypoint.sh"]
CMD ["docker-entrypoint.sh", "apache2-foreground"]
