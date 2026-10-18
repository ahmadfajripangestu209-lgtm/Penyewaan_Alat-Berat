@echo off
REM Run spark reminder and append to log
set PROJECT_DIR=C:\xampp\htdocs\alat_berat
set PHP=C:\xampp\php\php.exe
set LOG=%PROJECT_DIR%\writable\logs\rental_reminders.log
cd /d %PROJECT_DIR%
"%PHP%" "%PROJECT_DIR%\spark" rental:send-reminders >> "%LOG%" 2>&1
