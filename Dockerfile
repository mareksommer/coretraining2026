FROM wordpress:php8.3-apache

ARG COOKIE_NOTICE_VERSION=2.4.17
ARG YOAST_SEO_VERSION=24.1

# Install unzip (needed for plugin extraction) + less (used by wp-cli for paging)
RUN apt-get update && apt-get install -y --no-install-recommends unzip less \
    && rm -rf /var/lib/apt/lists/*

# Install wp-cli (used for one-off ops: theme activation, rewrite flush, plugin install, ...)
RUN curl -fsSL https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar \
        -o /usr/local/bin/wp \
    && chmod +x /usr/local/bin/wp

# Download and install Cookie Notice plugin (pinned version for reproducible builds)
RUN curl -fsSL \
        "https://downloads.wordpress.org/plugin/cookie-notice.${COOKIE_NOTICE_VERSION}.zip" \
        -o /tmp/cookie-notice.zip \
    && unzip -q /tmp/cookie-notice.zip \
        -d /var/www/html/wp-content/plugins/ \
    && rm /tmp/cookie-notice.zip

# Download and install Yoast SEO plugin (pinned version for reproducible builds)
RUN curl -fsSL \
        "https://downloads.wordpress.org/plugin/wordpress-seo.${YOAST_SEO_VERSION}.zip" \
        -o /tmp/wordpress-seo.zip \
    && unzip -q /tmp/wordpress-seo.zip \
        -d /var/www/html/wp-content/plugins/ \
    && rm /tmp/wordpress-seo.zip

# Copy custom theme
COPY wp-content/themes/coretraining/ /var/www/html/wp-content/themes/coretraining/
