@echo off
title SI-ASET Super Launcher - Digital Cube
color 0b

echo =========================================================
echo       MENGAKTIFKAN INFRASTRUKTUR SI-ASET
echo =========================================================
echo.

:: 1. Menjalankan MySQL menggunakan script bawaan XAMPP
echo [1/5] Menjalankan MySQL Database...
start /min "" "C:\xampp\mysql_start.bat"

:: 2. Menjalankan Apache menggunakan script bawaan XAMPP
echo [2/5] Menjalankan Apache Web Server...
start /min "" "C:\xampp\apache_start.bat"

:: Beri jeda 5 detik agar service benar-benar "Ready"
timeout /t 5 /nobreak > nul

:: 3. Menjalankan Laravel Backend
echo [3/5] Menjalankan PHP Artisan Serve...
start "Laravel Server" cmd /k "php artisan serve"

:: 4. Menjalankan Vite (Frontend Assets)
echo [4/5] Menjalankan NPM Run Dev...
start "Vite Assets" cmd /k "npm run dev"

:: Beri jeda sebelum buka browser
timeout /t 3 /nobreak > nul

:: 5. Membuka Browser
echo [5/5] Membuka SI-ASET...
start http://127.0.0.1:8000

echo.
echo =========================================================
echo   STATUS: PROSES SELESAI
echo =========================================================
pause