<?php
namespace RobertAskam\BackupGoogleDrive\Tasks\Restore;

use Illuminate\Support\Collection;
use RobertAskam\BackupGoogleDrive\Helpers\RestoreHelper;
use Carbon;
use Storage;
use DB;
use Mail;
use Google_Client; 
use Google_Service_Drive;
use Google_Service_Drive_DriveFile;

class DriveRestoreJobFactory
{

    public function __construct()
    {
        
    }

    public function retrieveDatabaseAndStore()
    {
        $storageLocation = storage_path() . '/secret.json';
        putenv('GOOGLE_APPLICATION_CREDENTIALS='.$storageLocation);

        $client = new Google_Client();
        $client->addScope(Google_Service_Drive::DRIVE);
        $client->useApplicationDefaultCredentials();
        $service = new Google_Service_Drive($client);
        
        $files_list = $service->files->listFiles(array())->getFiles();
        dd($files_list);
    }
}