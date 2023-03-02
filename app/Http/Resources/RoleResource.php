<?php

namespace App\Http\Resources;

use Alterindonesia\Procurex\Traits\HasActionTrait;
use App\Libraries\Auth;
use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
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
            "id"            => (int) $this->id,
            "code"          => (string) $this->code,
            "name"          => (string) $this->name,
            "guard_name"    => (string) $this->guard_name,
            "action"        => $this->action($request)
        ];
    }
}
