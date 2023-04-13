<?php

namespace App\Actions\Leads;

use Lorisleiva\Actions\Concerns\AsAction;
use App\Models\Lead;
use App\Models\ActionWorkflowNode;
use Illuminate\Http\Request;

class TestAction
{
    use AsAction;

    public function handle(Lead $lead, ActionWorkflowNode $actionWorkflowNode, Request|array|null $request)
    {
        $actionWorkflowNode->postSuccessProcess($lead, 'Test Action', []);

        return ['message' => 'Action was successful'];
    }
}
