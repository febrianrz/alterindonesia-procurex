<?php
namespace App\Helpers;

use http\Client\Response;

class GlobalHelper {
    public static function responseSuccess($message,$data=[],$code=200): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'meta'   => [
                'message'   => $message,
                'code'      => $code,
            ],
            'data'  => $data
        ],$code);
    }

    public static function responseError($message,$data=[],$code=400): \Illuminate\Http\JsonResponse
    {
        if($code == 401) {
            return response()->json([
                'message'   => 'Unauthenticated'
            ],$code);
        }
        $meta = [
            'message'   => $message instanceof \Exception ? $message->getMessage() : $message,
            'code'      => $code,
            'number'    => $message->getLine(),
            'action'    => $message->getFile()
        ];
        return response()->json([
            'meta'   => $meta,
            'data'  => $data
        ],$code);
    }
}
