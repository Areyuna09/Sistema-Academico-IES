@echo off
echo ==========================================
echo   Worker de Cola - Sistema Academico IES
echo ==========================================
echo.
echo Iniciando worker para procesar correos...
echo Presiona Ctrl+C para detener el worker
echo.

php artisan queue:work --tries=3 --timeout=90
