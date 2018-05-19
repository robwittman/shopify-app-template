FROM ruby:2.3.0-alpine
MAINTAINER Maarten Huijsmans <maarten.huijsmans@gmail.com>

# API: 4567
# WebSocket: 8080
EXPOSE 4567 8080

# Inspiration: http://blog.codeship.com/build-minimal-docker-container-ruby-apps/
ENV BUILD_PACKAGES build-base

# Update and install all of the required packages.
# At the end, remove the apk cache
RUN apk update && \
    apk upgrade && \
    apk add $BUILD_PACKAGES && \
    rm -rf /var/cache/apk/*

# Install slanger
RUN gem install slanger

# Start the slanger server
CMD slanger --app_key ${APP_KEY} --secret ${SECRET} -r redis://redis:6379/0