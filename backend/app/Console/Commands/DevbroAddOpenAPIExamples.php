<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class DevbroAddOpenAPIExamples extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'devbro:AddOpenAPIExamples';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'generates json file to be merged with openapi from testing.har';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Storage::delete('testing.har');
        $this->call('test');
        $this->call('l5-swagger:generate');

        $apiDocDisk = Storage::build([
            'driver' => 'local',
            'root' => config('l5-swagger.defaults.paths.docs'),
        ]);
        $apidoc = json_decode($apiDocDisk->get('api-docs.json'), true);

        // clear all examples
        foreach ($apidoc['paths'] as &$path) {
            foreach ($path as &$method) {
                foreach ($method['responses'] as &$code) {
                    // $code['content']['application/json']['examples'] = [];
                    unset($code['content']);
                }
            }
        }

        $handle = fopen(Storage::path(config('devbro.testRecordingFile')), 'r');
        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                $line_json = json_decode($line, true);

                $match_route = $this->matchRoute($line_json['uri'], array_keys($apidoc['paths']));
                // dd(array_keys($apidoc['paths']));
                if ($match_route) {
                    // print($line_json['response']['status_code'] . ' ' . $line_json['method'] . ' ' . $line_json['uri'] . "\n");
                    $a = &$apidoc['paths'][$match_route][strtolower($line_json['method'])]['responses'];
                    $a[$line_json['response']['status_code']] = $a[$line_json['response']['status_code']] ?? [];
                    $a = &$a[$line_json['response']['status_code']];
                    // $a['description'] = $a['description'] ?? "desc ". $line_json['response']['status_code'];
                    $a['content'] = $a['content'] ?? [];
                    $a['content'][$line_json['response']['content-type']] = $a['content'][$line_json['response']['content-type']] ?? ['schema' => []];

                    $a = &$a['content'][$line_json['response']['content-type']];
                    $a['examples'] = $a['examples'] ?? [];

                    $example = [];
                    $example['summary'] = 'Example '.count($a['examples']) + 1;
                    $example['value'] = json_decode($line_json['response']['content'], true);
                    $a['examples'][] = $example;

                    if (in_array(strtolower($line_json['method']), ['post', 'put']) && 200 == $line_json['response']['status_code']) {
                        $b = &$apidoc['paths'][$match_route][strtolower($line_json['method'])]['requestBody'];
                        $b['description'] = '';
                        $b['required'] = true;
                        $b = &$apidoc['paths'][$match_route][strtolower($line_json['method'])]['requestBody']['content']['multipart/form-data']['schema']['properties'];

                        foreach ($line_json['parameters'] as $k => $v) {
                            $b[$k] = $b[$k] ?? [];
                            $b[$k]['description'] = $b[$k]['description'] ?? $v;
                            $b[$k]['type'] = $b[$k]['type'] ?? 'string';
                            $b[$k]['format'] = $b[$k]['format'] ?? '';
                        }
                        $apidoc['paths'][$match_route][strtolower($line_json['method'])]['requestBody']['content']['multipart/form-data']['schema']['type'] = 'object';
                    }
                } else {
                    echo $line_json['response']['status_code'].' '.$line_json['method'].' '.$line_json['uri']."\n";
                }
            }

            fclose($handle);
        }

        $apiDocDisk->put('api-docs.json', json_encode($apidoc, JSON_PRETTY_PRINT));

        return 0;
    }

    public function matchRoute($uri, $routes)
    {
        if (false !== array_search($uri, $routes)) {
            return $uri;
        }
        if (false !== array_search($uri.'/', $routes)) {
            return substr($uri, 0, -1);
        }

        // fml we need to do partial matching
        foreach ($routes as $route) {
            $pattern = preg_replace(['/{.*}/U', '/\\//'], ['.*', '\\/'], $route);
            if (preg_match("/^{$pattern}$/", $uri)) {
                return $route;
            }
        }

        return false;
    }
}
