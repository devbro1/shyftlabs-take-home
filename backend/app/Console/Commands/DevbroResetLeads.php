<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Lead;
use App\Jobs\CheckForSchedulableLeadActionsJob;

class DevbroResetLeads extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'devbro:resetLeads';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $leads = Lead::withoutGlobalScopes()->get();

        $leads->each(function ($lead, $key) {
            $lead->status_id = $lead->workflow->getStartNode()->id;
            $lead->save();
            CheckForSchedulableLeadActionsJob::dispatch($lead);
            $this->info("Lead {$lead->id} changed to ".$lead->workflow->getStartNode()->label);
        });

        return 0;
    }
}
