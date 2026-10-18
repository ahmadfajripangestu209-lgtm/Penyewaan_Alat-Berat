<#
Simple PowerShell wrapper to run the reminders command via PHP CLI.
Use in Windows Task Scheduler with action: Program/script = powershell.exe
and argument like: -File "C:\xampp\htdocs\alat_berat\run_reminders.ps1"
#>
$projectRoot = Split-Path -Parent $MyInvocation.MyCommand.Definition
Push-Location $projectRoot
try {
    $php = "${env:PHP_HOME}\php.exe"
    if (-not (Test-Path $php)) {
        # fallback to bundled php in xampp
        $php = "C:\\xampp\\php\\php.exe"
    }
    if (-not (Test-Path $php)) {
        Write-Error "PHP executable not found. Set PHP_HOME or ensure C:\xampp\php\php.exe exists."
        exit 2
    }
    $spark = Join-Path $projectRoot 'spark'
    if (-not (Test-Path $spark)) {
        $spark = 'spark'
    }
    & $php $spark rental:send-reminders
    exit $LASTEXITCODE
} finally {
    Pop-Location
}
