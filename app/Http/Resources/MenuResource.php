<?php

namespace App\Http\Resources;

use App\Libraries\Auth;
use Illuminate\Http\Resources\Json\JsonResource;

class MenuResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "id"        => (int) $this->id,
            "module"    => [
                "id"    => (int) $this->module->id,
                "name"  => (string) $this->module->name
            ],
            "name"      => (string) $this->name,
            "icon"      => (string) $this->icon,
            "status"    => (string) $this->status,
            "path"      => (string) $this->path,
            "order_no"  => (int) $this->order_no,
            "submenus"     => SubMenuResource::collection($this->whenLoaded('submenus')),
            "action"    => $this->whenHas('id', fn () => [
                "edit"  => Auth::user()->can("update") ? route('api.module.update', $this->id) : null,
                "delete"=> Auth::user()->can("destroy") ? route('api.module.destroy', $this->id) : null,
            ]),
        ];
    }
}
