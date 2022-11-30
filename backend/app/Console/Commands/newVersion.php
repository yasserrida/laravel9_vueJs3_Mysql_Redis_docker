<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\newVersion as newVersionNotif;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class newVersion extends Command
{
    protected $signature = 'notification:newVersion {version}';

    protected $description = 'Notifications for new version';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): void
    {
        $users = User::all();
        Notification::send($users, new newVersionNotif($this->argument('version')));
    }
}
