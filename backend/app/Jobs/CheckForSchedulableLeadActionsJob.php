<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Lead;

class CheckForSchedulableLeadActionsJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public $lead;

    /**
     * Create a new job instance.
     */
    public function __construct(Lead $lead)
    {
        // $this->onQueue('general');
        $this->lead = $lead;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        foreach ($this->lead->status->actions as $workflow_action) {
            if ('scheduled' === $workflow_action->action->type) {
                $action = $workflow_action->action;
                RunLeadActionsJob::dispatch($this->lead, $workflow_action)->delay(now()->addMinutes($workflow_action->variables['duration']));
            }
        }
    }
}
