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
            "path"      => (string) $this->path,
            "order_no"  => (int) $this->order_no,
            "status"    => (string) $this->status,
            "submenus"  => SubMenuResource::collection($this->whenLoaded('submenus')),
            "action"    => $this->whenHas(
                'id',
                function () use ($request) {
                    // set action
                    $action = [
                        "edit"  => Auth::user()->can("update") ? route('api.menu.update', $this->id) : null,
                        "delete"=> Auth::user()->can("destroy") ? route('api.menu.destroy', $this->id) : null,
                        "restore"  => Auth::user()->can("destroy") ? route('api.menu.restore', $this->id) : null
                    ];

                    // check if trashed resource
                    if ($request->has("filter")
                        && array_key_exists("trashed", $request->filter)
                    ) {
                        unset($action["delete"]);
                    } else {
                        unset($action["restore"]);
                    }

                    return $action;
                }
            )
        ];
    }
}
