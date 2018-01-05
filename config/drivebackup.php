<?php

return [

    /*
    |--------------------------------------------------------------------------
    | BACKUP_NAME
    |--------------------------------------------------------------------------
    |
    | This is the name that will prefix the backup
    |
    */
    'backup_name' => env('BACKUP_NAME', 'backup'),

    /*
    |--------------------------------------------------------------------------
    | BACKUP_DAYS_TO_STORE
    |--------------------------------------------------------------------------
    |
    | The amount of days that a backuop should be stored
    |
    */
    'backup_days' => env('BACKUP_DAYS', '5'),

    /*
    |--------------------------------------------------------------------------
    | COMPRESS_BACKUP
    |--------------------------------------------------------------------------
    |
    | GZIP the backup
    |
    */
    'compress_backup' => env('COMPRESS_BACKUP', false),
    
    /*
    |--------------------------------------------------------------------------
    | ALERT_SUCCESS
    |--------------------------------------------------------------------------
    |
    | Send an email if the backup is successful
    | True/False
    |
    */
    'alert_success' => env('ALERT_SUCCESS', false),
    
    /*
    |--------------------------------------------------------------------------
    | ALERT_FAIL
    |--------------------------------------------------------------------------
    |
    | Send an email if the backup fails
    | True/False
    |
    */
    'alert_fail' => env('ALERT_FAIL', false),
    
    /*
    |--------------------------------------------------------------------------
    | ALERT_EMAIL
    |--------------------------------------------------------------------------
    |
    | The email address you want the email to go to
    |
    */
    'alert_email' => env('ALERT_EMAIL', ''),
];