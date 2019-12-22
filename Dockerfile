FROM wp-dev

# copy release files
COPY --chown="www-data:www-data" dist/ /srv/app/public/wp-content/plugins/enlighter