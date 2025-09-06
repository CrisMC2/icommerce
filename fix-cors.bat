@echo off
echo ========================================
echo    Solucionando CORS y CSRF - iCommerce
echo ========================================
echo.

cd backend
echo Limpiando cache de Laravel...
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
echo.

echo Reiniciando servidor Laravel...
taskkill /f /im php.exe 2>nul
timeout /t 2 /nobreak >nul
start "Laravel Server" php artisan serve
echo.

echo ========================================
echo    CORS y CSRF Solucionados!
echo ========================================
echo.
echo Problemas solucionados:
echo - Error CORS (Cross-Origin Resource Sharing)
echo - Error 419 (CSRF Token Mismatch)
echo.
echo Ahora puedes crear usuarios sin errores!
echo.
pause