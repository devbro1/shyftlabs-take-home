<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\LeadOwner;
use App\Models\Service;
use App\Models\Store;
use App\Models\Workflow;
use App\Models\Customer;
use App\Models\PostalCode;
use App\Models\ServiceAvailability;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Jobs\CheckForSchedulableLeadActionsJob;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class RelationFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        $query->where($property, 'ilike', $value.'%');
    }
}

class LeadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $q = Lead::from('leads as lead')->select('lead.*')
            ->join('customers as customer', 'lead.customer_id', '=', 'customer.id')
            ->join('services as service', 'lead.service_id', '=', 'service.id')
            ->join('workflow_nodes as status', 'lead.status_id', '=', 'status.id')
        ;

        return QueryBuilder::for($q)
            ->with('customer')
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::custom('customer.first_name', new RelationFilter()),
                AllowedFilter::custom('customer.last_name', new RelationFilter()),
                AllowedFilter::custom('status.label', new RelationFilter()),
                AllowedFilter::custom('service.name', new RelationFilter()),
            ])
            ->allowedSorts(['id', 'customer.first_name', 'customer.last_name', 'status.label', 'service.name'])
            ->jsonPaginate()
        ;
    }

    /**
     * @OA\Post(
     *     tags={"Lead Management"},
     *     path="/api/v1/leads",
     *     summary="Create a new Lead",
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found"
     *     ),
     *     @OA\RequestBody(
     *         description="Information needed to create a new lead",
     *         required=true, @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 allOf={@OA\Schema(
     *                     ref="#/components/schemas/Customer"
     *                 ),
     *                 @OA\Schema(
     *                     ref="#/components/schemas/Lead"
     *                 ),
     *             },
     *             @OA\Property(
     *                 description="ID for stores, if unknown user sends store_id it is ignored",
     *                 property="store_id",
     *                 type="integer",
     *                 format="int32",
     *
     *             ),
     *             @OA\Property(
     *                 description="ID for service, must be active",
     *                 property="service_id",
     *                 type="integer",
     *                 format="int32",
     *
     *             )
     *         )
     *     )
     * )
     * )
     */
    public function store(Request $request)
    {
        $lead = new Lead();
        $customer = new Customer();
        $store = null;
        $request->merge(['postal_code' => str_replace(' ', '', strtoupper($request->get('postal_code', '')))]);

        $request->validate([
            'service_id' => ['required', 'numeric', 'exists:services,id'],
            'store_id' => ['nullable', 'numeric', 'exists:stores,id'],
            'company_id' => ['nullable', 'numeric', 'exists:companies,id'],
        ]);

        $service = Service::where('id', (int) $request->get('service_id'))->where('active', 1)->firstOrFail();
        $customer->fill(Customer::validate($request));
        $customer->country_code = 'CA';

        $postal_code = PostalCode::find($request->get('postal_code'));

        if (!$request->User()) {
            $request->merge(['source' => 'WEB']);
            $request->request->remove('store_id');
            $request->request->remove('company_id');
        } else {
            $lead->source = $request->get('source', 'USER');
            $request->merge(['source' => $lead->source]);
        }

        if ($request->get('store_id', false)) {
            $store = Store::find($request->get('store_id', false));
        }

        $sa = ServiceAvailability::findClosestStore(['postal_code' => $postal_code, 'service_id' => $service->id, 'store_id' => $request->get('store_id', null)]);
        if ($sa) {
            $store = $sa->store;
            $workflow = workflow::find($sa->workflow_id);
            $store = Store::find($sa->store_id);
        } else {
            throw new \Exception('The request service is not available for your location.');
        }

        $lead->store_id = $store->id;
        $request->merge(['store_id' => $store->id ?? null]);

        $lead->fill(Lead::validate($request));

        DB::beginTransaction();
        $customer->save();
        $lead->customer_id = $customer->id;
        $lead->service_id = $service->id;
        $lead->workflow_id = $workflow->id;
        $lead->status_id = $workflow->getStartNode()->id;

        $lead->save();
        $lead->owners()->saveMany([new LeadOwner(['provider_id' => $sa->company->owners()->first()->id, 'main_provider' => true])]);
        $lead->load('customer');
        CheckForSchedulableLeadActionsJob::dispatch($lead);

        DB::commit();

        return ['message' => 'Lead was created successfully', 'data' => $lead];
    }

    /**
     * @OA\Get(
     *     tags={"Lead Management"},
     *     path="/api/v1/leads/{lead_id}",
     *     summary="get a lead",
     *     @OA\Parameter(
     *         name="lead_id",
     *         in="path",
     *         required=true, @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found"
     *     )
     * )
     */
    public function show(Lead $lead)
    {
        // $lead->load('workflow');
        $lead->load('store');
        $lead->load('owners');
        $lead->load('invoices');
        $lead->load('service');
        $lead->load('status');
        $lead->load('customer');
        foreach ($lead->owners->all() as $owner) {
            $owner->provider->load('companies');
        }

        return $lead;
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Lead $lead)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Lead $lead)
    {
    }

    /**
     * @OA\Get(
     *     tags={"Lead Management"},
     *     path="/api/v1/leads/{lead_id}/history",
     *     summary="history of actions for the lead",
     *     @OA\Parameter(
     *         name="lead_id",
     *         in="path",
     *         required=true, @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found"
     *     )
     * )
     */
    public function getHistory(Request $request, Lead $lead)
    {
        return $lead->history;
    }

    /**
     * @OA\Post(
     *     tags={"Lead Management"},
     *     path="/api/v1/leads/{lead_id}/notes",
     *     summary="Adds a note to current lead's note",
     *     @OA\Parameter(
     *         name="lead_id",
     *         in="path",
     *         required=true, @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found"
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="note",
     *                     example="",
     *                     type="string",
     *                 ),
     *             )
     *         )
     *     )
     * )
     */
    public function addNote(Request $request, Lead $lead)
    {
        $note = $request->validate([
            'note' => ['required', 'string', 'max:255'],
        ])['note'];

        $time = \Carbon\Carbon::now()->toString();
        $header = $time.' '.$request()->user->username.":\n";
        $lead->notes += $header.$note."\n-----------\n\n";

        return ['message' => 'Note was added successfully'];
    }
}
