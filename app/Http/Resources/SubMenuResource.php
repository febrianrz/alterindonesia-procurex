<?php

namespace App\Http\Resources;

use Alterindonesia\Procurex\Traits\HasActionTrait;
use App\Libraries\Auth;
use Illuminate\Http\Resources\Json\JsonResource;

class SubMenuResource extends JsonResource
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
            "menu"      => [
                "id"    => (int) $this->menu->id,
                "name"  => (string) $this->menu->name
            ],
            "menu_id"   => (int) $this->menu->id,
            "name"      => (string) $this->name,
            "icon"      => (string) $this->icon,
            "status"    => (string) $this->status,
            "path"      => (string) $this->path,
            "order_no"  => (int) $this->order_no,
            "routes"    => MenuServiceRouteResource::collection($this->services),
            "action"    => $this->action($request)
        ];
    }
}
