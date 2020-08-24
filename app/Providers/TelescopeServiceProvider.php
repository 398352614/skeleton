<?php

namespace App\Providers;

use Laravel\Telescope\Telescope;
use Illuminate\Support\Facades\Gate;
use Laravel\Telescope\IncomingEntry;
use Laravel\Telescope\TelescopeApplicationServiceProvider;

class TelescopeServiceProvider extends TelescopeApplicationServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Telescope::night();

        $this->hideSensitiveRequestDetails();


        //任务 类型标签
        Telescope::tag(function (IncomingEntry $entry) {
            if ($entry->type === 'job') {
                return [$entry->recordedAt->format('H:i'),
                    $entry->content['data']['data'] ? $entry->content['data']['data'][0]['type'] : []];
            }
            return [];
        });

        //请求 报错
        Telescope::tag(function (IncomingEntry $entry) {
            if ($entry->type === 'request') {
                return [$entry->content['uri'],
                    $entry->recordedAt->format('H:i'),
                    $entry->content['response']['msg'],
                    'method:' . $entry->content['method']
                ];
            }
            return [];
        });

        Telescope::filter(function (IncomingEntry $entry) {
            if ($this->app->isLocal() || ($this->app->environment() === 'development')) {
                return true;
            }

            return $entry->isReportableException() ||
                $entry->isFailedRequest() ||
                $entry->isFailedJob() ||
                $entry->isScheduledTask() ||
                $entry->hasMonitoredTag();

        });
    }

    /**
     * Prevent sensitive request details from being logged by Telescope.
     *
     * @return void
     */
    protected function hideSensitiveRequestDetails()
    {
        if ($this->app->isLocal() || $this->app->environment() === 'development') {
            return;
        }

        Telescope::hideRequestParameters(['_token']);

        Telescope::hideRequestHeaders([
            'cookie',
            'x-csrf-token',
            'x-xsrf-token',
        ]);
    }

    /**
     * Register the Telescope gate.
     *
     * This gate determines who can access Telescope in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define('viewTelescope', function ($user) {
            return true;
        });
    }
}
