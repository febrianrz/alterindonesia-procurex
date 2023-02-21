<?php

namespace App\Http\Resources;

use App\Libraries\Auth;
use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
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
            "id"            => (int) $this->id,
            "code"          => (string) $this->code,
            "name"          => (string) $this->name,
            "guard_name"    => (string) $this->guard_name,
            "action"        => $this->whenHas(
                'id',
                function () use ($request) {
                    // set action
                    $action = [
                        "edit"  => Auth::user()->can("update") ? route('api.role.update', $this->id) : null,
                        "delete"=> Auth::user()->can("destroy") ? route('api.role.destroy', $this->id) : null,
                        "restore"  => Auth::user()->can("destroy") ? route('api.role.restore', $this->id) : null
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
