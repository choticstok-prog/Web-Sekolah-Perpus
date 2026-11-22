<?php

// Ensure SQLite database is present in writeable /tmp on Vercel
$sourceDb = __DIR__.'/../database/database.sqlite';
$targetDb = '/tmp/database.sqlite';

if (file_exists($sourceDb) && (! file_exists($targetDb) || filesize($targetDb) === 0)) {
    copy($sourceDb, $targetDb);
}

// Forward to Laravel public entrypoint
require __DIR__.'/../public/index.php';
