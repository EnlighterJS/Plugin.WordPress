FROM localhost/wp-dev

# copy release files
COPY --chown="www-data:www-data" dist/ /srv/app/public/wp-content/plugins/enlighter

#COPY --chown="root:root" .credentials/wpmu.lighttpd.conf /etc/lighttpd/app.conf