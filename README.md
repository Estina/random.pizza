Random Pizza Generator
======================

Generates random pizzas based on location and flavor preferences.

Live: http://pizza.estina.lt

Technologies
------------
* MySQL DB
* Symfony Framework
* JQuery 2
* Bootstrap 3

Apache Config
-------------
```
<VirtualHost *:80>

    ServerName pizza.estina.lt

    DocumentRoot /var/www/random.pizza/web

    <Directory /var/www/random.pizza/web>
        Options -Indexes +FollowSymLinks
        RewriteEngine On
        RewriteCond %{REQUEST_FILENAME} !-f 
        RewriteRule ^(.*) app.php [QSA,L]

        Require all granted
    </Directory>

    ErrorLog /var/log/apache2/random.pizza_error.log
    CustomLog /var/log/apache2/random.pizza_access.log combined

</VirtualHost>
```
