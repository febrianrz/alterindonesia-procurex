<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LoginResource extends JsonResource
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
            'id'    => $this->id,
            'name'  => $this->name,
            'username'=> $this->username,
            'email' => $this->email,
            'roles' => LoginRoleResource::collection($this->roles()->get()),
            'company'=> new LoginCompanyResource($this->company),
            'employee'=> $this->employee
        ];
    }
}
