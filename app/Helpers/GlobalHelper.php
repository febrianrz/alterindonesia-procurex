<?php
namespace App\Helpers;

use App\Http\Resources\AnonymousCollection;
use http\Client\Response;
use Illuminate\Http\Resources\Json\JsonResource;
use Yajra\DataTables\DataTableAbstract;

class GlobalHelper {
    public static function responseSuccess($message,$data=null,$code=200, $resource=null)
    {
        if($resource){
            return new AnonymousCollection(
                $data, $resource
            );
        } else if(is_array($data) || is_object($data)) {
            return response()->json([
                'meta'  => [
                    'message'   => $message,
                    'code'      => $code,
                ],
                'data'      => $data
            ]);
        } else {
            return response()->json($data);
        }
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
            'number'    => $message instanceof \Exception ? $message->getLine() : "",
            'action'    => $message instanceof \Exception ? $message->getFile() : ""
        ];

        return response()->json([
            'meta'   => $meta,
            'data'  => $data
        ],$code);
    }
}
