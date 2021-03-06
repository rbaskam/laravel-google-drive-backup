<?php

namespace RobertAskam\BackupGoogleDrive;

use Illuminate\Support\ServiceProvider;
use RobertAskam\BackupGoogleDrive\Commands\DriveBackupCommand;
use RobertAskam\BackupGoogleDrive\Commands\DriveRestoreCommand;

class DriveBackupServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/drivebackup.php' => config_path('drivebackup.php'),
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('command.drivebackup.run', DriveBackupCommand::class);
        $this->app->bind('command.driverestore.run', DriveRestoreCommand::class);

        $this->commands([
            'command.drivebackup.run' ,
            'command.driverestore.run' 
        ]);
    }
}
