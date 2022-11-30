<?php

namespace App\Providers;

use App\Models\Contrat;
use Illuminate\Support\Facades\Gate;
use Laravel\Telescope\IncomingEntry;
use Laravel\Telescope\Telescope;
use Laravel\Telescope\TelescopeApplicationServiceProvider;

class TelescopeServiceProvider extends TelescopeApplicationServiceProvider
{
    public function register()
    {
        $this->hideSensitiveRequestDetails();

        Telescope::filter(function (IncomingEntry $entry) {
            if ($this->allowedRequest($entry)) {
                return true;
            }

            return $entry->isReportableException() ||
                $entry->isFailedRequest() ||
                $entry->isFailedJob() ||
                $entry->isScheduledTask() ||
                $entry->hasMonitoredTag();
        });
    }

    protected function hideSensitiveRequestDetails()
    {
        if ($this->app->environment('local')) {
            return;
        }

        Telescope::hideRequestParameters(['_token']);

        Telescope::hideRequestHeaders([
            'cookie',
            'x-csrf-token',
            'x-xsrf-token',
        ]);
    }

    protected function gate()
    {
        Gate::define('viewTelescope', fn ($user) => in_array($user->email, ['admin@yasser.com']));
    }

    public function allowedRequest(IncomingEntry $entry)
    {
        if (isset($entry->user['name'])) {
            $entry->tags([$entry->user['name']]);
        }

        if ($entry->type == 'request') {
            if (
                str_contains($entry->content['uri'], 'telescope-api/') ||
                str_contains($entry->content['uri'], '/api/common') ||
                str_contains($entry->content['uri'], '/api/notif') ||
                str_contains($entry->content['uri'], '/api/logs')
            ) {
                return false;
            }
        }

        return true;
    }
}
