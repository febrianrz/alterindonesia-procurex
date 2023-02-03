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
            "id"        => (int) $this->id,
            "name"      => (string) $this->name,
            "icon"      => (string) $this->icon,
            "status"    => (string) $this->status,
            "path"      => (string) $this->path,
            "order_no"  => (int) $this->order_no,
            "is_show_on_dashboard"=> (boolean) $this->is_show_on_dashboard,
            "action"    => [
                "edit"  => Auth::user()->can("update") ? route('api.module.update', $this->id) : null,
                "delete"=> Auth::user()->can("destroy") ? route('api.module.destroy', $this->id) : null,
            ],
        ];
    }





}
