<?php
namespace RobertAskam\BackupGoogleDrive\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class BaseCommand extends Command
{
    
    public function run(InputInterface $input, OutputInterface $output)
    {
        return parent::run($input, $output);
    }
}