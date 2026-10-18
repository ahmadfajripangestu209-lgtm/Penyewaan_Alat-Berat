<?php
// Convenience script to run the CodeIgniter CLI command from a web/cli PHP execution.
// Usage: php scripts/run_reminders.php
chdir(__DIR__ . '/..');
// Run the CodeIgniter 4 spark CLI command
if (PHP_SAPI !== 'cli') {
    echo "This script should be run from CLI.\n";
}
$spark = __DIR__ . '/../spark';
if (!file_exists($spark)) {
    // fallback: try 'spark' in PATH
    $spark = 'spark';
}
$cmd = escapeshellcmd(PHP_BINARY) . ' ' . escapeshellarg($spark) . ' rental:send-reminders';
passthru($cmd, $exit);
exit($exit);
