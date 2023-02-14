<?php

namespace App\Http\Resources;

use App\Http\Resources\Traits\WithMeta;
use App\Libraries\Auth;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class UserResource extends JsonResource
{
    use WithMeta;
    /**
     * @param $request
     * @return array|\JsonSerializable|\Illuminate\Contracts\Support\Arrayable
     */
    public function toArray($request): array|\JsonSerializable|\Illuminate\Contracts\Support\Arrayable
    {
        return [
            'id'    => $this->id,
            'name'  => $this->name,
            'email' => $this->email,
            'username'=> $this->username,
            'company_code'=> $this->company_code,
            'status'    => $this->status,
            'consumer_id'=> $this->consumer_id,
            'employee'=> $this->employee,
            'roles' => LoginRoleResource::collection($this->roles()->get()),
            "action"    => $this->whenHas(
                'id',
                function () use ($request) {
                    // set action
                    $action = [
                        "edit"  => Auth::user()->can("update") ? route('api.user.update', $this->id) : null,
                        "delete"=> Auth::user()->can("destroy") ? route('api.user.destroy', $this->id) : null,
                        "restore"  => Auth::user()->can("destroy") ? route('api.user.restore', $this->id) : null
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
