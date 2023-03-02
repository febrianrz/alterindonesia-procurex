<?php

namespace Alterindonesia\Procurex\Traits;

use App\Libraries\Auth;
use Illuminate\Support\Facades\Route;

trait WithMetaTagResource
{
    protected int $code = 200;

    public function with($request): array
    {
        return [
            'meta' => [
                'message' => 'Success',
                'code' => $this->code,
                'create'    => $this->canCreate()
            ],
        ];
    }

    public function withCode(int $code): static
    {
        $this->code = $code;

        return $this;
    }

    public function canCreate(): bool
    {

        return \Alterindonesia\Procurex\Facades\Auth::user()->can('store');
    }
}
