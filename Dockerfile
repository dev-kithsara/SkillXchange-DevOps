# Step 1: Base image එක විදිහට PHP 8.2 FPM (FastCGI) තෝරාගැනීම
FROM php:8.2-fpm

# Step 2: MySQL / MariaDB සඳහා අවශ්‍ය PHP extensions install කිරීම
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Step 3: Working directory එක set කිරීම
WORKDIR /var/www/html

# Step 4: Application Code එක Image එක ඇතුළට Copy කිරීම (Self-contained)
COPY . /var/www/html

# Step 5: Upload folders හදලා www-data user ට ownership දීම
RUN mkdir -p /var/www/html/public/uploads/org_certs \
             /var/www/html/public/uploads/profile_pictures \
    && chown -R www-data:www-data /var/www/html

# Step 6: PHP-FPM default port එක 9000
EXPOSE 9000

# Step 7: PHP-FPM foreground එකේ start කිරීම
CMD ["php-fpm"]