<?php
namespace RobertAskam\BackupGoogleDrive\Tasks\Backup;

use Illuminate\Support\Collection;
use RobertAskam\BackupGoogleDrive\Helpers\BackupHelper;
use Carbon;
use Storage;
use DB;
use Mail;
use Google_Client; 
use Google_Service_Drive;
use Google_Service_Drive_DriveFile;

class DriveBackupJobFactory
{

    protected $backup;
    protected $client;
    protected $service;

    public function __construct()
    {
        //For large DB
        ini_set('memory_limit', '-1');
        $this->backup = new BackupHelper();
        //This is saved by the user from Google Drive API Service creator
        $storageLocation = storage_path() . '/secret.json';
        putenv('GOOGLE_APPLICATION_CREDENTIALS='.$storageLocation);

        $this->client = new Google_Client();
        $this->client->addScope(Google_Service_Drive::DRIVE);
        $this->client->useApplicationDefaultCredentials();

        $this->service = new Google_Service_Drive($this->client);
    }


    public function backupDatabase()
    {
        //If Compression is set change the file name ending
        $fileNameEnding = '.sql';

        if($this->backup->compressBackup()) {
            $fileNameEnding = '.sql.gz';
        }

        //Get the File Name and assign the date of backup
        $filename = $this->backup->getBackupName().":" . Carbon\Carbon::now()->format('Y-m-d_H-i-s') . $fileNameEnding;

        //mysqldump command with account credentials from .env file. storage_path() adds default local storage path
        $command = "mysqldump --user=" . env('DB_USERNAME') ." --password=" . env('DB_PASSWORD') . " --host=" . env('DB_HOST') . " " . env('DB_DATABASE') . "  > " . storage_path() . "/app/" . $filename;
        
        //If Compression is set change the command
        if($this->backup->compressBackup()) {
            //GZIP
            $command = "mysqldump --user=" . env('DB_USERNAME') ." --password=" . env('DB_PASSWORD') . " --host=" . env('DB_HOST') . " " . env('DB_DATABASE') . "  | gzip > " . storage_path() . "/app/" . $filename;
        }
        $returnVar = NULL;
        $output  = NULL;

        //exec command allows you to run terminal commands from php 
        exec($command, $output, $returnVar);
        
        //Check if completed successfully and move to Google Drive
        if(!$returnVar){
            //get mysqldump output file from local storage
            $getFile = Storage::disk('local')->get($filename);

            // Send the File to Drive
            $this->sendBackupToDrive($filename);

            // Delete the local backup
            Storage::disk('local')->delete($filename); 
            
            //Check for old backups to delete
            $this->checkAndRemoveOldBackup();
        }else{
            $failedEmailAddress = $this->backup->getFailAlertEmail();
            
            if ($failedEmailAddress) {
                // if there is an error send an email to the specfied email address
                Mail::raw('There has been an issue during the backup of the database..', function ($message) use ($failedEmailAddress) {
                    $message->to($failedEmailAddress, env('APP_NAME', 'Laravel'))->subject("Backup Failed");
                });
            }
            
        }
    }

    public function sendBackupToDrive($filename)
    {   
        $file = new Google_Service_Drive_DriveFile();
        $file->setName($filename);
        $file->setDescription('Backup of DB');
        $file->setMimeType('application/sql');

        $data = Storage::disk('local')->get($filename);

        $createdFile = $this->service->files->create($file, array(
            'data' => $data,
            'mimeType' => 'application/sql',
            'uploadType' => 'multipart'
            ));

        if ($createdFile) {
            $successEmailAddress = $this->backup->getSuccessAlertEmail();
            
            if ($successEmailAddress) {
                // if there is an error send an email to the specfied email address
                Mail::raw('You have successfully backed up your database to Google Drive', function ($message) use ($successEmailAddress) {
                    $message->to($successEmailAddress, env('APP_NAME', 'Laravel'))->subject("Backup Successful");
                });
            }
        }
    }

    public function checkAndRemoveOldBackup()
    {
        $filesList = $this->service->files->listFiles(array())->getFiles();
        $numberOfDaysToSaveBackups = $this->backup->getNumberOfBackupDays();
        
        foreach ($filesList as $item) {
            $fileId = $item['id'];
            $fileName = explode(':', $item['name']);
            if (isset($fileName[1])) {
                $fileName = explode('_', $fileName[1]);
                $dateNow = Carbon\Carbon::now();
                $end = Carbon\Carbon::parse($fileName[0]);
                $length = $end->diffInDays($dateNow);
                
                if ($length > $numberOfDaysToSaveBackups) {
                    $this->service->files->delete($fileId);
                }
            }
            
        }
    }
}