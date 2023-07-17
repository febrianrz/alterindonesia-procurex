<?php

namespace App\Http\Resources;

use Alterindonesia\Procurex\Traits\HasActionTrait;
use App\Libraries\Auth;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
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
            'id'    => $this->id,
            'name'  => $this->name,
            'url'   => $this->url,
            'is_active'=> $this->is_active,
            "action"    => $this->action($request)
        ];
    }
}
