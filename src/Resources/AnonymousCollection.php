<?php

namespace Alterindonesia\Procurex\Resources;

use Alterindonesia\Procurex\Traits\WithMetaTagResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class AnonymousCollection extends ResourceCollection
{
    use WithMetaTagResource;

    public string $wrapper;

    public function __construct($resource, $wrapper, $canStore=false)
    {
        parent::__construct($resource);
        $this->wrapper = $wrapper;
        $this->canStore = $canStore;
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
