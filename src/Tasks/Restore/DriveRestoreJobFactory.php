<?php
namespace RobertAskam\BackupGoogleDrive\Tasks\Restore;

use Illuminate\Support\Collection;
use Storage;
use Google_Client; 
use Google_Service_Drive;
use Google_Service_Drive_DriveFile;

class DriveRestoreJobFactory
{
    protected $client;
    protected $service;

    public function __construct()
    {
        $storageLocation = storage_path() . '/secret.json';
        putenv('GOOGLE_APPLICATION_CREDENTIALS='.$storageLocation);

        $this->client = new Google_Client();
        $this->client->addScope(Google_Service_Drive::DRIVE);
        $this->client->useApplicationDefaultCredentials();
        $this->service = new Google_Service_Drive($this->client);
    }

    public function retrieveDatabaseFilesList()
    {        
        return $this->service->files->listFiles(array())->getFiles();
    }

    public function retrieveDatabaseFiles()
    {
        $filesList = $this->retrieveDatabaseFilesList();
        
        $backups = array();
        
        foreach ($filesList as $item) {
            array_push($backups, $item['name']);
        }
        
        $collection = collect($backups);
        $flattened = $collection->flatten();
        $flattened->all();
        
        return $flattened;
    }

    public function retrieveDatabaseFileByName($name)
    {
        $filesList = $this->retrieveDatabaseFilesList();
        $fileId = '';

        foreach ($filesList as $item) {
            if ($item['name'] == $name) {
                $fileId = $item['id'];
            }
        }

        if ($fileId != '') {
            $response = $this->service->files->get($fileId, array(
                'alt' => 'media'));
            $content = $response->getBody()->getContents();
            
            $restoredFile = Storage::disk('local')->put($name, $content);
            if ($restoredFile) {
                echo 'File Restored Successfully';
            } else {
                echo 'File Restore Failed';
            }
        }   
    }
}