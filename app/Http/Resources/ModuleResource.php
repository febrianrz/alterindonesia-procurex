<?php

namespace App\Http\Resources;

use App\Http\Resources\Traits\WithMeta;
use App\Libraries\Auth;
use Illuminate\Http\Resources\Json\JsonResource;

class ModuleResource extends JsonResource
{

    use WithMeta;
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            ...$this->resource->makeHidden('deleted_at','created_at','updated_at')->toArray(),
            "menus"     => MenuResource::collection($this->whenLoaded('menus')),
            "action"    => $this->whenHas('id', fn () => [
                "edit"  => Auth::user()->can("update") ? route('api.module.update', $this->id) : null,
                "delete"=> Auth::user()->can("destroy") ? route('api.module.destroy', $this->id) : null,
            ]),
        ];
    }





}
