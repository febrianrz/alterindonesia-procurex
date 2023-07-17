<?php

namespace App\Http\Resources;

use App\Models\Role;
use App\Models\Service;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

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
        $state = false;
        if($request->has('role_code') && $request->input('role_code')){
            $state = (bool)DB::table('role_permission_procurex')
                ->where('role_code', $request->input('role_code'))
                ->where('permission_name', $this->pivot->route)
                ->first();
        }
        return [
            'service_id'    => $this->pivot->service_id,
            'route'         => $this->pivot->route,
            'service_name'  => $service->name,
            'url'           => $service->url,
            'state'         => $state
        ];
    }
}
