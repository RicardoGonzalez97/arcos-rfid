#!/bin/bash

# Detener si algo falla
set -e

echo "Starting Laravel Queue Worker..."

php artisan queue:work --verbose --tries=3 --timeout=90