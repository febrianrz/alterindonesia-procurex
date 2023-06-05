<?php

namespace App\Http\Resources;

use Alterindonesia\Procurex\Traits\HasActionTrait;
use App\Libraries\Auth;
use App\Models\Menu;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
{
    use HasActionTrait;
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return $this->resource->toArray();
    }
}
