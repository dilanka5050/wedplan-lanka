@echo off
echo ==========================================
echo   WedPlan Lanka - Setup Script
echo ==========================================
echo.
echo Make sure MySQL is running and .env DB credentials are correct.
echo.
pause

echo.
echo [1/3] Running migrations...
php artisan migrate --force
if %errorlevel% neq 0 (
    echo.
    echo ERROR: Migration failed. Check your .env database settings.
    pause
    exit /b 1
)
echo.

echo [2/3] Seeding database...
php artisan db:seed --force
echo.

echo [3/3] Creating storage link...
php artisan storage:link 2>nul
echo.

echo ==========================================
echo   Setup Complete!
echo ==========================================
echo.
echo   App:         http://localhost:8000
echo   Admin Panel: http://localhost:8000/admin
echo.
echo   Admin Login:
echo     Email: admin@wedplanlanka.com
echo     Password: password
echo.
echo   Demo Couple Login:
echo     Email: demo@wedplanlanka.com
echo     Password: password
echo.
echo   Start server: php artisan serve
echo ==========================================
pause
