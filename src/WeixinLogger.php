<?php

/*
 * This file is part of the nilsir/laravel-Weixin-logging.
 *
 * (c) nilsir <nilsir@qq.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Policy\LaravelWeixinLogging;

use Monolog\Formatter\NormalizerFormatter;
use Monolog\Logger;

/**
 * Class WeixinLogger.
 */
class WeixinLogger
{
    /**
     * Create a custom Monolog instance.
     */
    public function __invoke(array $config): Logger
    {
        $WeixinHandler = new WeixinHandler();
        $WeixinHandler->setBubble($config['bubble'] ?? true);
        $WeixinHandler->setLevel($config['level'] ?? 'debug');
        $dateFormat = $config['date_format'] ?? config('weixin-logger.date_format');
        $WeixinHandler->setFormatter(
            new NormalizerFormatter($dateFormat)
        );
        $token = $config['token'] ?? config('weixin-logger.token');
        $WeixinHandler->setWebhook('https://qyapi.weixin.qq.com/cgi-bin/webhook/send?key='.$token);

        return new Logger(config('app.name'),
            [$WeixinHandler]
        );
    }
}
