FROM richarvey/nginx-php-fpm:1.5.4

COPY ./ /var/www/php-task
RUN mkdir -p /var/www/html/conf/nginx && cp /var/www/php-task/nginx/default.conf /var/www/html/conf/nginx/nginx-site.conf

CMD ["/start.sh"]