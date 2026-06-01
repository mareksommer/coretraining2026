FROM wordpress:php8.3-apache

ARG COOKIE_NOTICE_VERSION=2.4.17

# Install unzip (needed for plugin extraction)
RUN apt-get update && apt-get install -y --no-install-recommends unzip \
    && rm -rf /var/lib/apt/lists/*

# Download and install Cookie Notice plugin (pinned version for reproducible builds)
RUN curl -fsSL \
        "https://downloads.wordpress.org/plugin/cookie-notice.${COOKIE_NOTICE_VERSION}.zip" \
        -o /tmp/cookie-notice.zip \
    && unzip -q /tmp/cookie-notice.zip \
        -d /var/www/html/wp-content/plugins/ \
    && rm /tmp/cookie-notice.zip

# Copy custom theme
COPY wp-content/themes/arenacallup/ /var/www/html/wp-content/themes/arenacallup/
