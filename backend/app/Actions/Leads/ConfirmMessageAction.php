<?php

namespace App\Actions\Leads;

use Lorisleiva\Actions\Concerns\AsAction;
use App\Models\Lead;
use App\Models\ActionWorkflowNode;
use Illuminate\Http\Request;

class ConfirmMessageAction
{
    use AsAction;

    public function handle(Lead $lead, ActionWorkflowNode $actionWorkflowNode, Request|array|null $request)
    {
        $actionWorkflowNode->postSuccessProcess($lead, 'Confirmed details '.$actionWorkflowNode->alternative_name, ['message' => $actionWorkflowNode->variables['confirmation_message']]);

        return ['message' => 'Confirmation was successful'];
    }
}
