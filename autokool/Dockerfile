FROM php:8.2-apache

# Включаем модуль mod_rewrite для работы роутинга через .htaccess
RUN a2enmod rewrite

# Устанавливаем необходимые расширения для работы с MySQL
RUN docker-php-ext-install pdo pdo_mysql

# Разрешаем Apache читать и применять директивы из .htaccess
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Копируем проект в директорию веб-сервера
WORKDIR /var/www/html
COPY . /var/www/html/

# Назначаем права для веб-сервера Apache
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
