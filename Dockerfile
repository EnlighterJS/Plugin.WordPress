FROM wp-dev:latest

# copy release files
COPY --chown="www-data:www-data" dist/ /srv/app/wp-content/plugins/enlighter