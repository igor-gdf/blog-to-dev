#!/bin/bash
set -e

# Fix permissions for CakePHP tmp directories
mkdir -p /var/www/html/app/tmp/cache/persistent
mkdir -p /var/www/html/app/tmp/cache/models
mkdir -p /var/www/html/app/tmp/cache/views
mkdir -p /var/www/html/app/tmp/logs

chmod -R 777 /var/www/html/app/tmp

# Wait for PostgreSQL to be ready
echo "Waiting for PostgreSQL..."
until pg_isready -h "${DB_HOST:-db}" -p "${DB_PORT:-5432}" -U "${DB_USERNAME:-bloguser}" > /dev/null 2>&1; do
  echo "PostgreSQL is unavailable - sleeping"
  sleep 2
done

echo "PostgreSQL is up - checking database"

# Check if schema exists, if not apply it
TABLE_COUNT=$(PGPASSWORD="${DB_PASSWORD:-blogpassword}" psql -h "${DB_HOST:-db}" -p "${DB_PORT:-5432}" -U "${DB_USERNAME:-bloguser}" -d "${DB_DATABASE:-blogdb}" -t -c "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema='public' AND table_type='BASE TABLE';" 2>/dev/null || echo "0")

if [ "$TABLE_COUNT" -lt 2 ]; then
  echo "Database is empty - applying schema..."
  PGPASSWORD="${DB_PASSWORD:-blogpassword}" psql -h "${DB_HOST:-db}" -p "${DB_PORT:-5432}" -U "${DB_USERNAME:-bloguser}" -d "${DB_DATABASE:-blogdb}" -f /var/www/html/db/schema_postgres.sql
  echo "Schema applied successfully"
else
  echo "Database already initialized with $TABLE_COUNT tables"
fi

# Start Apache
exec apache2-foreground
