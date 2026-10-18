Scheduling the rental expiry notification job

Windows (Task Scheduler):
- Create a Basic Task -> Trigger: daily or every X minutes (use Advanced trigger for minutes)
- Action: Start a program
  - Program/script: powershell.exe
  - Add arguments: -File "C:\xampp\htdocs\alat_berat\run_reminders.ps1"

Ensure PHP is either in PATH or set environment variable PHP_HOME to the folder containing php.exe.

Linux (cron):
- Edit crontab: crontab -e
- Add a line to run every 5 minutes (example):
  */5 * * * * cd /var/www/alat_berat && /usr/bin/php index.php rental:send-reminders >> /var/log/alat_berat_reminders.log 2>&1

Alternatively use the helper script:
  php scripts/run_reminders.php

Notes:
- The command implemented is `App\Commands\SendRentalReminders` (name: rental:send-reminders).
- It will create in-app notifications (table `notifikasi`) and mark `pembayaran.rental_expiry_notified` when available.
