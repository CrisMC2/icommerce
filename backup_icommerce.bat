@echo off

set DB_NAME=icommerce
set DB_USER=root
set DB_PASS=

set BACKUP_DIR=%~dp0backups
if not exist "%BACKUP_DIR%" (
    mkdir "%BACKUP_DIR%"
    echo Carpeta de backups creada en: %BACKUP_DIR%
)

:MENU
echo ==========================
echo BACKUP AUTOMATICO ICOMMERCE
echo ==========================
echo 1. Comenzar ahora
echo 2. Comenzar en X minutos
set /p OPTION="Elige una opcion (1 o 2): "

if "%OPTION%"=="1" set START_DELAY=0
if "%OPTION%"=="2" (
    set /p START_DELAY="Cuantos minutos esperara para el primer backup?: "
    set /a START_DELAY=%START_DELAY%*60
)

set /p INTERVAL_MIN="Cada cuantos minutos quieres hacer el backup?: "
set /a INTERVAL_SEC=%INTERVAL_MIN%*60

echo.
echo El primer backup se realizará en %START_DELAY% segundos.
timeout /t %START_DELAY%

:LOOP
:: Crear fecha actual (YYYY-MM-DD_HH-MM)
for /f "tokens=2-4 delims=/ " %%a in ('date /t') do set DATE=%c-%%a-%%b
for /f "tokens=1-2 delims=: " %%a in ('time /t') do set TIME=%%a-%%b

set FILE=%BACKUP_DIR%\%DB_NAME%_%DATE%_%TIME%.sql
set ZIP=%BACKUP_DIR%\%DB_NAME%_%DATE%_%TIME%.zip

:: Crear backup
"C:\xampp\mysql\bin\mysqldump.exe" -u %DB_USER% %DB_NAME% > %FILE%
powershell Compress-Archive -Path "%FILE%" -DestinationPath "%ZIP%" -Force
del "%FILE%"

echo Backup completado en %ZIP%
echo Esperando %INTERVAL_MIN% minutos para el siguiente backup...
timeout /t %INTERVAL_SEC%
goto LOOP
