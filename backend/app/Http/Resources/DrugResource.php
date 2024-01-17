<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DrugResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public static $wrap = '';

    public function toArray($request): array
    {
        $rc = $this->resource->toArray();
        $disorder_ids = $this->resource->disorders->pluck('id')->toArray();
        sort($disorder_ids);
        $rc['disorders'] = $disorder_ids;
        $rc['disorders_names'] = $this->resource->disorders->pluck('name');

        return $rc;
    }
}
