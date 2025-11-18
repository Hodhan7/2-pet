<?php
/**
 * Simple site path helper.
 *
 * Behavior:
 * - If environment variable SITE_BASE_PATH is set, it's used verbatim (can be '' or '/subdir' or full URL).
 * - Otherwise the helper computes the web path to the project directory relative to DOCUMENT_ROOT.
 *
 * Usage:
 *   echo path('pages/about.php'); // => '/pages/about.php' or '/your-subdir/pages/about.php'
 */

function site_base_path()
{
    // Prefer explicit environment variable
    $env = getenv('SITE_BASE_PATH');
    if ($env !== false && $env !== null && trim($env) !== '') {
        $p = $env;
        // If user supplied a full URL, extract path part
        if (preg_match('#^https?://#i', $p)) {
            $parts = parse_url($p);
            $p = $parts['path'] ?? '';
        }
        $p = '/' . trim($p, '/');
        return $p === '/' ? '' : $p;
    }

    // Compute relative path based on DOCUMENT_ROOT and project dir
    if (!isset($_SERVER['DOCUMENT_ROOT'])) {
        return '';
    }
    $docRoot = realpath($_SERVER['DOCUMENT_ROOT']);
    $projDir = realpath(__DIR__ . '/..');
    if ($docRoot === false || $projDir === false) {
        return '';
    }

    // Normalize separators
    $docRoot = str_replace('\\', '/', $docRoot);
    $projDir = str_replace('\\', '/', $projDir);

    if (strpos($projDir, $docRoot) === 0) {
        $webPath = substr($projDir, strlen($docRoot));
        $webPath = '/' . trim($webPath, '/');
        return $webPath === '/' ? '' : $webPath;
    }

    // Fallback to empty (served at root)
    return '';
}

function path($relative)
{
    $relative = ltrim($relative, '/');
    $base = site_base_path();
    if ($base === '') {
        return '/' . $relative;
    }
    return $base . '/' . $relative;
}

?>
