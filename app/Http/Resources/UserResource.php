<?php

namespace App\Http\Resources;

use Alterindonesia\Procurex\Traits\HasActionTrait;
use App\Libraries\Auth;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class UserResource extends JsonResource
{
    use HasActionTrait;
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
            "action"    => $this->action($request)
        ];
    }
}
