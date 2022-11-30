<?php

namespace App\Console\Commands;

use App\Events\ExpiredNotificationEvent;
use App\Models\NotificationUser;
use App\Models\User;
use Exception;
use Illuminate\Console\Command;

class ExpiredNotifications extends Command
{
    protected $signature = 'notification:expired';

    protected $description = 'Archive expired notifications';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): void
    {
        $notifications = NotificationUser::where('archiver', false)->get();
        foreach ($notifications as $notification) {
            try {
                $date = date('Y-m-d H:i:s', strtotime($notification->created_at.' + '.$notification->temps_traitement.' days'));
                if ($date < date('Y-m-d H:i:s')) {
                    $notification->archiver = true;
                    $notification->save();

                    ExpiredNotificationEvent::dispatch(User::findOrFail($notification->user_id), $notification);

                    $this->info('- '.$notification->name);
                }
            } catch (Exception $e) {
                $this->error('- '.$notification->name.' => '.$e->getMessage());

                continue;
            }
        }
    }
}
