#!/bin/bash

echo "========================================"
echo "    iCommerce Setup Script"
echo "========================================"
echo

echo "[1/4] Configurando Backend Laravel..."
cd backend
php artisan key:generate
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan migrate --force
echo "Backend configurado!"
echo

echo "[2/4] Iniciando servidor Laravel en puerto 8000..."
gnome-terminal -- php artisan serve 2>/dev/null || open -a Terminal . && php artisan serve &
echo

echo "[3/4] Configurando Frontend React..."
cd ../frontend
echo

echo "[4/4] Iniciando servidor React en puerto 3000..."
gnome-terminal -- npm start 2>/dev/null || open -a Terminal . && npm start &
echo

echo "========================================"
echo "    ¡Setup Completado!"
echo "========================================"
echo
echo "Frontend: http://localhost:3000"
echo "Backend:  http://localhost:8000"
echo "phpMyAdmin: http://localhost/phpmyadmin"
echo
echo "¡Asegúrate de que XAMPP esté ejecutándose!"