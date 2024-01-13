# Utiliser l'image PHP 8.2
FROM php:8.2-cli

# Définir le répertoire de travail
WORKDIR /var/www/html

# Installer les extensions nécessaires (ajoutez les extensions supplémentaires si nécessaire)
RUN apt-get update && \
    apt-get install -y libpq-dev && \
    docker-php-ext-install pdo pdo_pgsql

# Copier le code source dans l'image
COPY . /var/www/html

# Exposer le port 8080
EXPOSE 8080

# Commande pour lancer le serveur PHP
CMD ["php", "-S", "0.0.0.0:8080", "-t", "public_html"]
