<?php

namespace App\Http\Resources;

use Alterindonesia\Procurex\Traits\HasActionTrait;
use App\Libraries\Auth;
use App\Models\Menu;
use Illuminate\Http\Resources\Json\JsonResource;

class MenuResource extends JsonResource
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

        return [
            "id"        => (int) $this->id,
            "module"    => [
                "id"    => (int) $this->module->id,
                "name"  => (string) $this->module->name
            ],
            "module_id" => (int) $this->module->id,
            "name"      => (string) $this->name,
            "icon"      => (string) $this->icon,
            "path"      => (string) $this->path,
            "order_no"  => (int) $this->order_no,
            "status"    => (string) $this->status,
            "submenus"  => SubMenuResource::collection($this->whenLoaded('submenus')),
            "routes"    => MenuServiceRouteResource::collection($this->services),
            "action"    => $this->action($request)
        ];
    }
}
