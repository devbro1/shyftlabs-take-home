<?php

namespace App\Actions\Leads;

use Lorisleiva\Actions\Concerns\AsAction;
use App\Models\Lead;
use App\Models\Appointment;
use App\Models\ActionWorkflowNode;
use Illuminate\Http\Request;

class BookAppointmentAction
{
    use AsAction;

    public function handle(Lead $lead, ActionWorkflowNode $actionWorkflowNode, Request|array|null $request)
    {
        $params = $request->validate([
            'appointment_id' => ['required', 'numeric', 'exists:appointments,id'],
        ]);

        $appointment_id = $params['appointment_id'];
        $appointment = Appointment::find($appointment_id);
        if (!$appointment) {
            return response()->json(['error' => 'Valid appointment_id is required'], 400);
        }
        if (!$lead->owners()->where('provider_id', $appointment->owner_id)->exists()) {
            return response()->json(['error' => 'appointment does not belong to a lead owner'], 400);
        }
        if (!in_array($lead->service_id, $appointment->services)) {
            return response()->json(['error' => 'service mismatch'], 400);
        }
        if (!in_array($lead->store_id, $appointment->stores)) {
            return response()->json(['error' => 'store mismatch'], 400);
        }

        $appointment->fill(['lead_id' => $lead->id]);
        $appointment->save();

        $actionWorkflowNode->postSuccessProcess($lead, 'Appointment Booked: '.$actionWorkflowNode->alternative_name, ['appointment_id' => $appointment_id]);

        return ['message' => 'Appointment was set successfully'];
    }
}
