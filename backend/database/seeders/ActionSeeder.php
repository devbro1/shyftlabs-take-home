<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Action;

class ActionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        if (!Action::where('name', 'Test Action')->exists()) {
            $action = new Action();
            $action->fill([
                'action_class' => 'App\\Actions\\Leads\\TestAction',
                'frontend_uri' => 'test',
                'name' => 'Test Action',
                'active' => true,
            ]);
            $action->save();
            $vars = [];
            $vars[] = ['name' => 'var1', 'is_action_variable' => true, 'is_workflow_node_variable' => true];
            $vars[] = ['name' => 'var2', 'is_action_variable' => true, 'is_workflow_node_variable' => true];
            $vars[] = ['name' => 'var3', 'is_action_variable' => false, 'is_workflow_node_variable' => true];
            $vars[] = ['name' => 'var4', 'is_action_variable' => false, 'is_workflow_node_variable' => true];
            $vars[] = ['name' => 'var5', 'is_action_variable' => true, 'is_workflow_node_variable' => false];
            $vars[] = ['name' => 'var6', 'is_action_variable' => true, 'is_workflow_node_variable' => false];
            $action->variables()->sync($vars);
        }

        if (!Action::where('name', 'Book Appointment Action')->exists()) {
            $action = new Action();
            $action->fill([
                'action_class' => 'App\\Actions\\Leads\\BookAppointmentAction',
                'frontend_uri' => 'book-appointment',
                'name' => 'Book Appointment Action',
                'active' => true,
            ]);
            $action->save();
            $vars = [];
            $vars[] = ['name' => 'date_type', 'description' => 'What to save the date for', 'is_action_variable' => false, 'is_workflow_node_variable' => true];
            $vars[] = ['name' => 'can_add_appointment_on_spot', 'description' => 'If no appointments are available, user can add one on the spot', 'is_action_variable' => false, 'is_workflow_node_variable' => true, 'type' => 'boolean'];
            $action->variables()->sync($vars);
        }

        if (!Action::where('name', 'Confirm Action')->exists()) {
            $action = new Action();
            $action->fill([
                'action_class' => 'App\\Actions\\Leads\\ConfirmMessageAction',
                'frontend_uri' => 'confirm-message',
                'name' => 'Confirm Action',
                'active' => true,
            ]);
            $action->save();
            $vars = [];
            $vars[] = ['name' => 'confirmation_message', 'description' => 'Message to be shown as part of confirmation'];
            $vars[] = ['name' => 'force_action', 'description' => 'force user to do this action before they can view lead'];
            $action->variables()->sync($vars);
        }

        if (!Action::where('name', 'Quick Confirm Action')->exists()) {
            $action = new Action();
            $action->fill([
                'action_class' => 'App\\Actions\\Leads\\ConfirmMessageAction',
                'frontend_uri' => 'quick-confirm-message',
                'name' => 'Quick Confirm Action',
                'active' => true,
            ]);
            $action->save();
            $vars = [];
            $vars[] = ['name' => 'confirmation_message', 'description' => 'Message to be shown as part of confirmation'];
            $vars[] = ['name' => 'force_action', 'description' => 'force user to do this action before they can view lead'];
            $action->variables()->sync($vars);
        }

        if (!Action::where('name', 'Set Date')->exists()) {
            $action = new Action();
            $action->fill([
                'action_class' => 'App\\Actions\\Leads\\SetDateAction',
                'frontend_uri' => 'set-date',
                'name' => 'Set Date',
                'active' => true,
            ]);
            $action->save();
            $vars = [];
            $vars[] = ['name' => 'date_type', 'description' => 'What to save the date for', 'is_action_variable' => false, 'is_workflow_node_variable' => true];
            $vars[] = ['name' => 'has_time', 'description' => 'if this date include time too', 'is_action_variable' => false, 'is_workflow_node_variable' => true];
            $action->variables()->sync($vars);
        }

        if (!Action::where('name', 'Mark as Stale')->exists()) {
            $action = new Action();
            $action->fill([
                'backend_uri' => '',
                'frontend_uri' => '',
                'name' => 'Mark as Stale',
                'active' => true,
                'type' => 'scheduled',
                'action_class' => 'App\\Actions\\Leads\\MarkLeadAsStale',
            ]);
            $action->save();
            $vars = [];
            $vars[] = ['name' => 'duration', 'description' => 'minutes to wait before triggering action', 'is_action_variable' => true, 'is_workflow_node_variable' => true];
            $action->variables()->sync($vars);
        }

        if (!Action::where('name', 'Fill Invoice')->exists()) {
            $action = new Action();
            $action->fill([
                'backend_uri' => '',
                'frontend_uri' => 'fill-invoice',
                'name' => 'Fill Invoice',
                'active' => true,
                'type' => 'api',
                'action_class' => 'App\\Actions\\Leads\\FillInvoiceAction',
            ]);
            $action->save();
            $vars = [];
            $vars[] = ['name' => 'key', 'description' => 'how is this invoice referred to internally', 'is_action_variable' => false, 'is_workflow_node_variable' => true];
            $vars[] = ['name' => 'total_required', 'description' => 'does end user need to enter a total value', 'is_action_variable' => false, 'is_workflow_node_variable' => true];
            $vars[] = ['name' => 'item_fields', 'description' => 'list of fields for each row', 'is_action_variable' => false, 'is_workflow_node_variable' => true];
            $vars[] = ['name' => 'pre_fill', 'description' => 'If it is the first time filling this invoice, should we use another invoice to prefill', 'is_action_variable' => false, 'is_workflow_node_variable' => true];
            $action->variables()->sync($vars);
        }
    }
}
