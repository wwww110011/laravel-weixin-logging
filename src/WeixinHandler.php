<?php

/*
 * This file is part of the nilsir/laravel-Weixin-logging.
 *
 * (c) nilsir <nilsir@qq.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Policy\LaravelWeixinLogging;

use GuzzleHttp\Client;
use Monolog\Handler\AbstractProcessingHandler;

/**
 * Class WeixinHandler.
 */
class WeixinHandler extends AbstractProcessingHandler
{
    protected $webhook;

    public function setWebhook(string $webhook): void
    {
        $this->webhook = $webhook;
    }

    protected function write(array $record): void
    {
        $title = $record['message'];
        unset($record['message'], $record['formatted']);

        $traces = $record['context']['exception']->getTrace();
        $contents = [];
        foreach ($traces as $item) {
            $contents[] = [
                'tag' => 'text',
                'text' => json_encode($item, JSON_PRETTY_PRINT + JSON_UNESCAPED_UNICODE + JSON_UNESCAPED_SLASHES),
            ];
        }

        $data = [
            'msgtype' => 'text',
            'text' => [
                "content" => 'title: ' . $title . "\r\n" . 'param: ' . $contents,
            ],
        ];

        $res = (new Client())->post($this->webhook, [
            'http_errors' => false,
            'headers' => ['Content-Type: application/json'],
            'body' => json_encode($data),
        ]);
    }
}
