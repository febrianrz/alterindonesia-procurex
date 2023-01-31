<?php

namespace App\Http\Resources;

use App\Http\Resources\Traits\WithMeta;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class AnonymousCollection extends ResourceCollection
{
    use WithMeta;

    public $wrapper;

    public function __construct($resource, $wrapper)
    {
        parent::__construct($resource);
        $this->wrapper = $wrapper;
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'data'  => $this->wrapper::collection($this->collection)
        ];
    }
}
