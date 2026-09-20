@echo off
chcp 65001 >nul
title سنتر الدروس - شغال...
color 0A
echo ==========================================
echo   سنتر الدروس - جاري التشغيل...
echo ==========================================
echo.
cd /d "C:\Users\Mohamed Gamal\Desktop\center"

REM اقفل اي سيرفر قديم
taskkill /F /IM php.exe 2>nul >nul

REM شغل السيرفر على الشبكة كلها (للموبايل)
start "" /MIN php artisan serve --host=0.0.0.0 --port=8000

echo.
echo  انتظر 3 ثواني...
timeout /t 3 /nobreak >nul

echo.
echo  ==========================================
echo   افتح المتصفح على:
echo   الكمبيوتر: http://localhost:8000/login
echo   الموبايل : http://192.168.100.101:8000/login
echo  ==========================================
echo.

REM افتح المتصفح تلقائي
start http://localhost:8000/login

echo.
echo  سيب الشباك ده مفتوح طول ما انت شغال
echo  لما تخلص اقفله او دوس Ctrl+C
echo.
pause
