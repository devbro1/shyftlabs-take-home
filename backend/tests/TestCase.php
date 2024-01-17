<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Storage;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function call($method, $uri, $parameters = [], $cookies = [], $files = [], $server = [], $content = null)
    {
        $rc = parent::call($method, $uri, $parameters, $cookies, $files, $server, $content);

        if (config('devbro.recordTests')) {
            $response = [];
            $response['status_code'] = $rc->getStatusCode();
            $response['content'] = $rc->getContent();
            $response['content-type'] = $rc->headers->get('Content-Type');
            $output = json_encode(['method' => $method, 'uri' => $uri, 'parameters' => $parameters, 'response' => $response]);
            if ($output) {
                $output .= ',';
            } else {
                $output = '';
            }
            Storage::append(config('devbro.testRecordingFile'), $output);
        }

        return $rc;
    }
}
