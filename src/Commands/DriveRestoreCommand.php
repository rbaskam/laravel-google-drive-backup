<?php

namespace RobertAskam\BackupGoogleDrive\Commands;

use RobertAskam\BackupGoogleDrive\Tasks\Restore\DriveRestoreJobFactory;

class DriveRestoreCommand extends BaseCommand
{
    /** @var string */
    protected $signature = 'driverestore:run';
    /** @var string */
    protected $description = 'Run the Google Drive restore.';
    
    public function handle()
    {
        $driveBackupJob = new DriveRestoreJobFactory;
        $files = $driveBackupJob->retrieveDatabaseFiles();
        $files = $files->toArray();

        $source = $this->choice(
            'Which backup would you like to get?', 
            $files
        );
        if ($source != '') {
            $driveBackupJob->retrieveDatabaseFileByName($source);
        }
    }
}
