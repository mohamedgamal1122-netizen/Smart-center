@echo off
REM نسخ احتياطي لقاعدة البيانات SQLite
set SRC=%~dp0database\database.sqlite
set DST=%~dp0backups\center_%date:~10,4%-%date:~4,2%-%date:~7,2%_%time:~0,2%-%time:~3,2%.sqlite
if not exist "%~dp0backups" mkdir "%~dp0backups"
copy "%SRC%" "%DST%"
echo تم النسخ الى %DST%
pause
