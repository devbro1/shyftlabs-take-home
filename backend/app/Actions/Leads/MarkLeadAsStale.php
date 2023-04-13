<?php

namespace App\Actions\Leads;

use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Http\Request;
use App\Models\Lead;
use App\Models\ActionWorkflowNode;

class MarkLeadAsStale
{
    use AsAction;

    public function handle(Lead $lead, ActionWorkflowNode $actionWorkflowNode, Request|array|null $request)
    {
        $lead->stale = true;
        $lead->save();
    }
}
