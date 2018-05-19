FROM php:7.2-cli

RUN apt-get update && apt-get install -y supervisor
RUN mkdir -p /var/log/supervisor

COPY config/supervisord.conf /etc/supervisord.conf
COPY config/crontab /var/spool/cron
RUN touch /var/spool/cron

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]