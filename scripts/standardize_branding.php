<?php

$root = __DIR__;
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root));
$patterns = ['/Mumias Mumias Vipers Academy(?!( Kenya| Admin| Blog))/', '/\bMumias Mumias Vipers Academy\b/'];
$replacements = ['Mumias Mumias Mumias Vipers Academy', 'Mumias Mumias Mumias Vipers Academy'];

$updatedFiles = [];
$skippedPaths = ['node_modules', 'vendor', 'storage', '.git', 'bootstrap/cache'];

foreach ($iterator as $file) {
    if ($file->isDir()) continue;

    $path = $file->getPathname();

    // Skip excluded directories
    $skip = false;
    foreach ($skippedPaths as $skipped) {
        if (strpos($path, $skipped) !== false) {
            $skip = true;
            break;
        }
    }
    if ($skip) continue;

    // Only process specific file extensions
    if (!preg_match('/\.(blade\.php|php|md|html)$/', $path)) continue;

    $original = file_get_contents($path);
    $modified = $original;

    // Replace each pattern
    foreach ($patterns as $i => $pattern) {
        $modified = preg_replace($pattern, $replacements[$i], $modified);
    }

    if ($modified !== $original) {
        file_put_contents($path, $modified);
        $updatedFiles[] = $path;
        echo "UPDATED: $path\n";
    }
}

echo "\n\nTotal files updated: " . count($updatedFiles) . "\n";
print_r($updatedFiles);
