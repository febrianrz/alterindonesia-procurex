<?php

namespace App\Http\Resources;

use Alterindonesia\Procurex\Traits\HasActionTrait;
use App\Libraries\Auth;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ModuleResource extends JsonResource
{
    use HasActionTrait;
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array|Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            ...$this->resource->makeHidden('deleted_at', 'created_at', 'updated_at')->toArray(),
            "menus"     => MenuResource::collection($this->whenLoaded('menus')),
            "action"    => $this->action($request)
        ];
    }
}
