<?php

namespace App\Http\Resources\Traits;

use App\Libraries\Auth;
use Illuminate\Support\Facades\Route;

trait WithMeta
{
    protected int $code = 200;

    public function with($request): array
    {
        $routeName = str_replace(".index",".store",Route::currentRouteName());
        return [
            'meta' => [
                'message' => 'Success',
                'code' => $this->code,
                // 'create'=> Auth::user()->can($routeName) ? route($routeName) : null
                'create'    => true
            ],
        ];
    }

    public function withCode(int $code): static
    {
        $this->code = $code;

        return $this;
    }
}
