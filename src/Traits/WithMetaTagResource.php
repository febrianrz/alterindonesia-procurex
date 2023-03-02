<?php

namespace Alterindonesia\Procurex\Traits;

use App\Libraries\Auth;
use Illuminate\Support\Facades\Route;

trait WithMetaTagResource
{
    protected int $code = 200;
    protected bool $canStore = false;

    public function with($request): array
    {
        return [
            'meta' => [
                'message' => 'Success',
                'code' => $this->code,
                'create'    => $this->canStore
            ],
        ];
    }

}
