FROM wpdev

# copy release files
COPY --chown="www-data:www-data" dist/ /srv/public/wp-content/plugins/enlighter