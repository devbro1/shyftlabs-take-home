<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class DevbroGenerateOpenAPIDocs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'devbro:generateOpenAPIDocs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'generates new openapi docs';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Storage::delete('testing.har');
        $this->call('test');
        $this->call('scribe:generate');

        Storage::disk('root')->copy('app/scribe/openapi.yaml', 'api-docs/api-docs.yaml');

        return 0;
    }
}
