<?php

namespace RobertAskam\BackupGoogleDrive\Commands;

use RobertAskam\BackupGoogleDrive\Tasks\Backup\DriveBackupJobFactory;

class DriveBackupCommand extends BaseCommand
{
    /** @var string */
    protected $signature = 'drivebackup:run';
    /** @var string */
    protected $description = 'Run the Google Drive backup.';
    
    public function handle()
    {
        $driveBackupJob = new DriveBackupJobFactory;
        $driveBackupJob->backupDatabase();
    }
}
