<?php

/*
 * This file is part of the leonis/easysms-notification-channel.
 * (c) yangliulnn <yangliulnn@163.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Leonis\Notifications\EasySms;

use Illuminate\Support\ServiceProvider;
use Overtrue\EasySms\EasySms;

class EasySmsChannelServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/easysms.php' => config_path('easysms.php'),
            ]);
        }
    }

    /**
     * Register any application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/easysms.php', 'easysms');

        $this->app->singleton(EasySms::class, function () {
            $easySms = new EasySms(config('easysms'));
            $easySms->extend('errorlog', function ($gatewayConfig) {
                return new ErrorLogGateway($gatewayConfig);
            });

            return $easySms;
        });

        $this->app->alias(EasySms::class, 'easysms');
    }
}
