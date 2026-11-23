<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Ensure Vercel serverless environment has SQLite database initialized
        $dbPath = config('database.connections.sqlite.database');
        $bundledDb = database_path('database.sqlite');

        if ($dbPath && str_starts_with($dbPath, '/tmp/') && file_exists($bundledDb)) {
            if (! file_exists($dbPath) || filesize($dbPath) < 50000) {
                copy($bundledDb, $dbPath);
                @chmod($dbPath, 0666);
            }
        }
    }
}
