<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Actions\MarkLeadAsStale;
use App\Models\Lead;

class RunLeadActionsJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public $lead;
    public $action;

    /**
     * Create a new job instance.
     *
     * @param mixed $action
     */
    public function __construct(Lead $lead, $action)
    {
        $this->lead = $lead;
        $this->action = $action;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        "{$this->action->action->action_class}"::run($this->lead, $this->action, null);
        // MarkLeadAsStale::run($this->lead,null);
    }
}
