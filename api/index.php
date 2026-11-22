<?php

// Ensure SQLite database is present and up-to-date in writeable /tmp on Vercel
$sourceDb = __DIR__.'/../database/database.sqlite';
$targetDb = '/tmp/database.sqlite';

if (file_exists($sourceDb)) {
    if (! file_exists($targetDb) || filesize($targetDb) !== filesize($sourceDb)) {
        copy($sourceDb, $targetDb);
    }
}

// Forward to Laravel public entrypoint
require __DIR__.'/../public/index.php';
