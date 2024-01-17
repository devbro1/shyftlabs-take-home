<?php

namespace App\Docs\Strategies;

use Knuckles\Camel\Extraction\ExtractedEndpointData;
use Knuckles\Scribe\Extracting\ParamHelpers;
use Knuckles\Scribe\Extracting\Strategies\Strategy;
use Illuminate\Support\Facades\Storage;

class UseTestResultsbodyParameters extends Strategy
{
    /**
     * Trait containing some helper methods for dealing with "parameters",
     * such as generating examples and casting values to types.
     * Useful if your strategy extracts information about parameters or generates examples.
     */
    use ParamHelpers;
    private static $samples;

    /**
     * @see https://scribe.knuckles.wtf/laravel/advanced/plugins
     *
     * @param ExtractedEndpointData $endpointData The endpoint we are currently processing.
     *                                            Contains details about httpMethods, controller, method, route, url, etc, as well as already extracted data.
     * @param array                 $routeRules   Array of rules for the ruleset which this route belongs to.
     *
     * See the documentation linked above for more details about writing custom strategies.
     */
    public function __invoke(ExtractedEndpointData $endpointData, array $routeRules = []): ?array
    {
        $rc = [];
        $this->loadData();

        $matches = $this->findMatches($endpointData->route->uri, $endpointData->httpMethods[0]);

        if (!count($matches)) {
            return [];
        }

        if (count($endpointData->httpMethods) > 1) {
            dd($endpointData->httpMethods);
        }

        foreach ($matches as $match) {
            foreach ($match['parameters'] as $pname => $pvalue) {
                $rc[$pname] = [
                    'type' => 'string',
                    'description' => '',
                    'example' => $pvalue,
                ];
            }
        }

        return $rc;
    }

    private function loadData()
    {
        if (!self::$samples) {
            $har = Storage::get(config('devbro.testRecordingFile'));
            $har = substr($har, 0, -1); // remove excess ','
            $har = '['.$har.']';
            self::$samples = json_decode($har, true);
        }
    }

    private function findMatches($uri, $method)
    {
        $rc = [];
        foreach (self::$samples as $match) {
            if ($match['method'] == $method && $match['uri'] == '/'.$uri) {
                $rc[] = $match;
            } elseif ($match['method'] == $method && $match['uri'] == '/'.$uri.'/') {
                $rc[] = $match;
            }
        }

        return $rc;
    }
}
