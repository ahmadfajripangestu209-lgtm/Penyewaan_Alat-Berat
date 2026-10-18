# Run Spark reminder command and log output
$projectDir = "C:\xampp\htdocs\alat_berat"
$php = "C:\xampp\php\php.exe"  # Adjust if your php.exe is elsewhere (e.g., C:\xampp\php\php.exe)
$log = Join-Path $projectDir "writable\logs\rental_reminders.log"

Set-Location $projectDir
& $php "$projectDir\spark" rental:send-reminders 2>&1 | Out-File -FilePath $log -Append
