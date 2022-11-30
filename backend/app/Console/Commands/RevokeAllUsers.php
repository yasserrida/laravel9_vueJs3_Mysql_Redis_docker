<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class RevokeAllUsers extends Command
{
    protected $signature = 'user:logout';

    protected $description = 'Logout all users from session';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): void
    {
        foreach (User::all() as $user) {
            if (! empty($user->tokens)) {
                $this->info('User : '.$user->name);
                $user->tokens->each(function ($token) {
                    $token->revoke();
                });
            }
        }
        $this->info('Users are logged out');
    }
}
