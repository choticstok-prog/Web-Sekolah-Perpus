<?php

// Ensure SQLite database is present and non-empty in writeable /tmp on Vercel
$sourceDb = __DIR__.'/../database/database.sqlite';
$targetDb = '/tmp/database.sqlite';

if (file_exists($sourceDb)) {
    // If target doesn't exist, is empty (0 bytes), or differs in size from tracked repository DB, copy it
    if (! file_exists($targetDb) || filesize($targetDb) === 0 || filesize($targetDb) !== filesize($sourceDb)) {
        copy($sourceDb, $targetDb);
        chmod($targetDb, 0666);
    }
}

// Forward to Laravel public entrypoint
require __DIR__.'/../public/index.php';
