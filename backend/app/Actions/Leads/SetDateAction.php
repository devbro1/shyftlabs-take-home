<?php

namespace App\Actions\Leads;

use Lorisleiva\Actions\Concerns\AsAction;
use App\Models\Lead;
use App\Models\ActionWorkflowNode;
use Illuminate\Http\Request;

class SetDateAction
{
    use AsAction;

    public function handle(Lead $lead, ActionWorkflowNode $actionWorkflowNode, Request|array|null $request)
    {
        $request->merge(['date_type' => $actionWorkflowNode->variables['date_type'], 'lead_id' => $lead->id]);
        $lead_date = $lead->dates()->where('date_type', $actionWorkflowNode->variables['date_type'])->first() ?? new LeadDate(LeadDate::validate($request));
        $lead->dates()->save($lead_date);
        $actionWorkflowNode->postSuccessProcess($lead, 'Set Date: '.$actionWorkflowNode->alternative_name, ['date_type' => $actionWorkflowNode->variables['date_type']]);

        return ['message' => 'Confirmation was successful'];
    }
}
