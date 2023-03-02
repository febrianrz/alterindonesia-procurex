<?php

namespace App\Http\Resources;

use App\Libraries\Auth;
use Illuminate\Http\Resources\Json\JsonResource;

class ModuleResource extends JsonResource
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
            ...$this->resource->makeHidden('deleted_at', 'created_at', 'updated_at')->toArray(),
            "menus"     => MenuResource::collection($this->whenLoaded('menus')),
            "action"    => $this->whenHas(
                'id',
                function () use ($request) {
                    // set action
                    $action = [
                        "edit"  => Auth::user()->can("update") ? route('api.module.update', $this->id) : null,
                        "delete"=> Auth::user()->can("destroy") ? route('api.module.destroy', $this->id) : null,
                        "restore"  => Auth::user()->can("destroy") ? route('api.module.restore', $this->id) : null
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

    public static function canCreate():bool
    {
        return true;
    }

}
