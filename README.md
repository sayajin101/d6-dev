# D6 Dev Challenge - Invoice

## Overview
This is a simple invoice application, built using PHP, MySQL, VueJS, and Bootstrap 5.

## Quick Explanation as to how it works
I decided to use a MVC style approach, written from scratch, with no frameworks. I used VueJS for the front end, and Bootstrap 5 for the styling. I used MySQL for the database, and PHP for the backend.

## Installation
if using Apache, you will need to rewrite the URL to index.php
To do this, add the following to your .htaccess file:

    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ index.php [L]

If you are using Nginx, you will need to add the following to your server block:

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

## Configuration
To import the database & data from the d6.sql file, you will need to create a database called "d6" and import the file, using the command line:

    mysql -u root -p d6 < d6.sql

You will need to configure the database connection in the app/Helper/Database.php file.

