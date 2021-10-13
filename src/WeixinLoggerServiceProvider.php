<?php

/*
 * This file is part of the nilsir/laravel-Weixin-logging.
 *
 * (c) nilsir <nilsir@qq.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace wwww110011\LaravelWeixinLogging;

use Illuminate\Foundation\Application as LaravelApplication;
use Illuminate\Support\ServiceProvider;
use Laravel\Lumen\Application as LumenApplication;

/**
 * Class WeixinLoggerServiceProvider.
 */
class WeixinLoggerServiceProvider extends ServiceProvider
{
    /**
     * Register application services.
     */
    public function register()
    {
        $path = __DIR__ . '/config/weixin-logger.php';
        $this->mergeConfigFrom($path, 'weixin-logger');
    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $path = __DIR__ . '/config/weixin-logger.php';
        if ($this->app instanceof LaravelApplication && $this->app->runningInConsole()) {
            $this->publishes([$path => config_path('weixin-logger.php')], 'weixin-logger.php');
        } elseif ($this->app instanceof LumenApplication) {
            $this->app->configure('weixin-logger');
        }
        $this->mergeConfigFrom($path, 'weixin-logger');
    }
}
