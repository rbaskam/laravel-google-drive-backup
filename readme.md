# This is a Service Account Backup of your MySql DB to Google Drive
## This will not be visible in your Google Drive UI

### Setup
```php
php artisan vendor:publish --provider="RobertAskam\BackupGoogleDrive\DriveBackupServiceProvider"
```
Go to config/drivebackup and set the necessary vars

### Google API
* Go to https://console.cloud.google.com
* Click APIS & Services
* Click Library and Enable Google Drive
* Click Credentials
* Open the drop down in the middle that says create credientials and click Service Account Key
* Create new service account
* Role->Select Project then Owner
* Make sure JSON is selected
* Click create and copy the downloaded file into you Storage Folder and call it secret.json

### Backup
To run manually use
```php
php artisan drivebackup:run
```
Or to Schedule just add the following to your Kernel

```php
$schedule->command('drivebackup:run --force')->daily();
```
### Restore
To run manually use the following command, select the backup name you want, this will save it in Storage/app database name
```php
php artisan driverestore:run
```
