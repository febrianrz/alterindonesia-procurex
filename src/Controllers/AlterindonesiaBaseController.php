<?php

namespace Alterindonesia\Procurex\Controllers;

use Alterindonesia\Procurex\Facades\Auth;
use Alterindonesia\Procurex\Facades\GlobalHelper;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class AlterindonesiaBaseController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function responseSuccess($message, $data=[], $code=200, $resource=null)
    {
        return GlobalHelper::responseSuccess($message, $data, $code, $resource);
    }

    public function responseError($message, $data=[], $code=400)
    {
        return GlobalHelper::responseError($message, $data, $code);
    }

    public function can($permissionName) : bool
    {
        return Auth::user()->hasPermission($permissionName);
    }

    public function canWithThrow($permissionName)
    {
        if (!$this->can($permissionName)) {
            throw new \Exception("Forbidden",403);
        }
    }
}
