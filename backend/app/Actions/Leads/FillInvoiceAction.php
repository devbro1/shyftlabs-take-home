<?php

namespace App\Actions\Leads;

use Lorisleiva\Actions\Concerns\AsAction;
use App\Models\Lead;
use App\Models\Invoice;
use App\Models\ActionWorkflowNode;
use Illuminate\Http\Request;

class FillInvoiceAction
{
    use AsAction;

    public function handle(Lead $lead, ActionWorkflowNode $actionWorkflowNode, Request|array|null $request)
    {
        $rules = [];

        if ($actionWorkflowNode->variables['total_required']) {
            $rules['total'] = ['required', 'numeric', 'gt:0'];
        } else {
            $rules['total'] = ['numeric', 'gt:0'];
        }

        $fields = $actionWorkflowNode->variables['item_fields'];
        $rules['items.*'] = ['array:'.$fields];

        foreach (explode(',', $actionWorkflowNode->variables['item_fields']) as $v) {
            $rules['items.*.'.$v] = ['required'];
        }

        // TODO add rules for columns

        $params = $request->validate($rules);
        $invoice = Invoice::where('key', $actionWorkflowNode->variables['key'])->where('lead_id', $lead->id)->first();
        if (!$invoice) {
            $invoice = new Invoice();
            $invoice->lead_id = $lead->id;
            $invoice->key = $actionWorkflowNode->variables['key'];
        }
        $invoice->fill($params);
        $invoice->lead_id = $lead->id;
        $invoice->save();

        $actionWorkflowNode->postSuccessProcess($lead, 'Invoice was created/updated: '.$actionWorkflowNode->alternative_name, $params);
    }
}
