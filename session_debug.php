<?php
// Disable showing detailed errors to users
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');

// Enable error logging
ini_set('log_errors', '1');
$logDir = __DIR__ . '/logs';
$logFile = $logDir . '/php_errors.log';

// Ensure logs directory exists and is writable
if (!is_dir($logDir)) {
    @mkdir($logDir, 0775, true);
}
if (!file_exists($logFile)) {
    @touch($logFile);
}
if (!is_writable($logFile)) {
    @chmod($logFile, 0664);
}
ini_set('error_log', $logFile);

// Convert PHP errors to ErrorException so they can be caught by the exception handler
set_error_handler(function ($severity, $message, $file, $line) {
    if (!(error_reporting() & $severity)) {
        return false;
    }
    throw new ErrorException($message, 0, $severity, $file, $line);
});

// Make mysqli throw exceptions (if using mysqli)
if (function_exists('mysqli_report')) {
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
}

// Simple exception handler to log and show a friendly message
set_exception_handler(function ($e) {
    error_log("Uncaught exception: " . $e->getMessage() . "\n" . $e->getTraceAsString());
    http_response_code(500);
    echo "An internal error occurred. Please contact the administrator.";
    exit;
});

// ...existing code...