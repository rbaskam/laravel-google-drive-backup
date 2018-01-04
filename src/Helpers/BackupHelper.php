<?php

namespace RobertAskam\BackupGoogleDrive\Helpers;

use Config;

class BackupHelper
{
    public function getBackupName()
    {
        $backupName = Config::get('drivebackup.backup_name');

        if(isset($backupName)) {
            return $backupName;
        }

        die('No Backup name set');
    }

    public function compressBackup()
    {
        return Config::get('drivebackup.compress_backup');
    }

    protected function getAlertEmail()
    {
        $alertEmail = Config::get('drivebackup.alert_email');

        if($alertEmail != '') {
            return $alertEmail;
        }

        return false;
    }

    public function getFailAlertEmail()
    {
        if(Config::get('drivebackup.alert_fail')) {
            return $this->getAlertEmail();
        }
    }

    public function getSuccessAlertEmail()
    {
        if(Config::get('drivebackup.alert_success')) {
            return $this->getAlertEmail();
        }
    }
}
