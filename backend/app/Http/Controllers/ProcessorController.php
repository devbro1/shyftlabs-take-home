<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * @group Batch Jobs and Processors
 */
class ProcessorController extends Controller
{
    private $options = [
        ['class' => 'ChangeRequestProcessors\\AdjustAll', 'name' => 'Adjust All', 'description' => 'make as many changes as possible'],
        ['class' => 'ChangeRequestProcessors\\DeleteAllPending', 'name' => 'Delete All Pendings', 'description' => 'deletes all pending ChangeRequests'],
        ['class' => 'ChangeRequestProcessors\\DeclineAllPending', 'name' => 'Decline All Pendings', 'description' => 'Declines pending ChangeRequests'],
        ['class' => 'ChangeRequestProcessors\\AcceptAllPending', 'name' => 'Decline All Pendings', 'description' => 'Accept pending ChangeRequests'],
        ['class' => 'ChangeRequestProcessors\\MassApproval', 'name' => 'Mass Approval', 'description' => 'Approves all given ChangeRequest IDs', 'fields' => ['ids']],
        ['class' => 'DrugProcessors\\MonthlyCleanup', 'name' => 'Monthly Cleanup', 'description' => 'Clear actions'],
    ];

    public function index()
    {
        return $this->options;
    }

    public function store(Request $request)
    {
        $classes = [];
        foreach ($this->options as $opt) {
            $classes[] = $opt['class'];
        }
        $rules = [];
        $rules['action_class'] = ['required', 'in:'.implode(',', $classes)];
        $values = $request->validate($rules);
        $changes = "App\\Actions\\{$values['action_class']}"::run($values);

        return ['message' => 'Processor finished successfully', 'data' => $changes];
    }
}
