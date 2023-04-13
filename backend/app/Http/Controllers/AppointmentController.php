<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class AppointmentController extends Controller
{
    /**
     * @OA\GET(
     *     path="/api/v1/appointments",
     *     summary="get all appointments",
     *     tags={"Appointments"},
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     ),
     * )
     */
    public function index(Request $request)
    {
        return QueryBuilder::for(Appointment::class)
            ->allowedFilters([
                AllowedFilter::exact('owner_id'),
                AllowedFilter::scope('available'),
                AllowedFilter::scope('covers'),
            ])
            ->allowedSorts(['owner_id', 'available', 'covers'])
            ->jsonPaginate()
        ;
    }

    /**
     * @OA\POST(
     *     path="/api/v1/appointments",
     *     summary="get all appointments",
     *     tags={"Appointments"},
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     ),
     * )
     */
    public function store(Request $request)
    {
        $request->merge(['owner_id' => auth()->user()->id]);
        $range_values = $request->validate([
            'date_start' => ['required', 'date'],
            'date_end' => ['required', 'date'],
            'time_start' => ['required', 'regex:/^\d\d:\d\d$/'],
            'time_end' => ['required', 'regex:/^\d\d:\d\d$/'],
            'appointment_duration' => ['required', 'regex:/^\d\d:\d\d$/'],
            'appointment_padding' => ['required', 'regex:/^\d\d:\d\d$/'],
        ]);

        $date_start = new \DateTime($range_values['date_start']);
        $date_end = new \DateTime($range_values['date_end']);
        $time_start = new \DateInterval(preg_replace('/^(\d\d):(\d\d)$/', 'PT${1}H${2}M', $range_values['time_start']));
        $time_end = new \DateInterval(preg_replace('/^(\d\d):(\d\d)$/', 'PT${1}H${2}M', $range_values['time_end']));
        $appointment_duration = new \DateInterval(preg_replace('/^(\d\d):(\d\d)$/', 'PT${1}H${2}M', $range_values['appointment_duration']));
        $appointment_padding = new \DateInterval(preg_replace('/^(\d\d):(\d\d)$/', 'PT${1}H${2}M', $range_values['appointment_padding']));

        $appointments = [];
        for ($i = $date_start; $i <= $date_end; $i->modify('+1 day')) {
            $start = (clone $i)->add($time_start);
            $end = (clone $i)->add($time_end);

            while ($start < $end) { // time
                $appointment_data = $request->all();
                $appointment_data['created_by'] = $request->user()->id;
                $appointment_data['dt_start'] = clone $start;
                $appointment_data['dt_end'] = (clone $start)->add($appointment_duration);

                if (!Appointment::Covers($appointment_data['dt_start'], $appointment_data['dt_end'])->where('owner_id', $appointment_data['owner_id'])->first()) {
                    $values = Appointment::validate($appointment_data);
                    $appointment = new Appointment();
                    $appointment->fill($values);
                    $appointment->save();
                    $appointments[] = $appointment;
                }

                $start->add($appointment_duration);
                $start->add($appointment_padding);
            }
        }

        return ['message' => 'Appointment was created successfully', 'data' => $appointments];
    }

    /**
     * @OA\Get(
     *     tags={"Appointments"},
     *     path="/api/v1/appointments/{id}",
     *     summary="shows an appointment",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true, @OA\Schema(
     *             type="int"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     ),
     * )
     */
    public function show(Appointment $appointment)
    {
        return $appointment;
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Appointment $appointment)
    {
    }

    /**
     * @OA\Delete(
     *     tags={"Appointments"},
     *     path="/api/v1/appointments/{id}",
     *     summary="delete an appointment",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true, @OA\Schema(
     *             type="int"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     ),
     * )
     */
    public function destroy(Appointment $appointment)
    {
        $appointment->delete();

        return ['message' => 'Appointment was deleted successfully'];
    }

    /**
     * @OA\Get(
     *     tags={"Appointments"},
     *     path="/api/v1/users/{id}/appointments",
     *     summary="shows appointments for a user",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true, @OA\Schema(
     *             type="int"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     ),
     * )
     */
    public function getUserAppointments(Request $request, User $user)
    {
        return QueryBuilder::for(Appointment::class)
            ->allowedFilters([
                AllowedFilter::exact('owner_id'),
                AllowedFilter::scope('available'),
                AllowedFilter::scope('covers'),
                AllowedFilter::scope('on'),
            ])
            ->where('owner_id', $user->id)->jsonPaginate();
    }
}
