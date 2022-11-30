<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;

class ClearLogs extends Command
{
    protected $signature = 'logs:clear';

    protected $description = 'Clear Log Files';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): void
    {
        try {
            $paths = [
                'laravel',
                'contrats',
                'souscripteurs',
                'users',
                'notifications',
                'reclamations',
            ];

            foreach ($paths as $path) {
                $this->info('Clear Logs: '.$path);
                // exec('gzip -c '.storage_path('logs/').$path.'.log > '.storage_path('logs/').$path.'-'.date('d-m-Y').'.tar.gz');
                exec('cat /dev/null > '.storage_path('logs/').$path.'.log');
            }

            $this->comment('Logs have been cleared!');
        } catch (Exception $exception) {
            $this->error($exception->getMessage());
        }
    }
}
