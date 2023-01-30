<?php

namespace App\Http\Resources;

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
            "status"    => (string) $this->status,
            "action"    => $this->getActions()
        ];
    }
}
