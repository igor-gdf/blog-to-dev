#!/bin/bash
set -e

# Fix permissions for CakePHP tmp directories
mkdir -p /var/www/html/app/tmp/cache/persistent
mkdir -p /var/www/html/app/tmp/cache/models
mkdir -p /var/www/html/app/tmp/cache/views
mkdir -p /var/www/html/app/tmp/logs

chmod -R 777 /var/www/html/app/tmp

# Start Apache
apache2-foreground
