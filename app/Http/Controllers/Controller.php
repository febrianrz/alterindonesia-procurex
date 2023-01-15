<?php

namespace App\Http\Controllers;

use App\Helpers\GlobalHelper;
use App\Http\Requests\TestRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function responseSuccess($message,$data=[],$code=200){
        return GlobalHelper::responseSuccess($message,$data,$code);
    }

    public function responseError($message,$data=[],$code=400){
        return GlobalHelper::responseError($message,$data,$code);
    }
}
