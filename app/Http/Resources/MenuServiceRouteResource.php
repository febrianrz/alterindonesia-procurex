<?php

namespace App\Http\Resources;

use App\Models\Service;
use Illuminate\Http\Resources\Json\JsonResource;

class MenuServiceRouteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $service = Service::find($this->pivot->service_id);
        return [
            'service_id'    => $this->pivot->service_id,
            'route'         => $this->pivot->route,
            'service_name'  => $service->name,
            'url'           => $service->url
        ];
    }
}
