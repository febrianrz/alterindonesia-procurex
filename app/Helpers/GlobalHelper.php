<?php
namespace App\Helpers;

use http\Client\Response;
use Illuminate\Http\Resources\Json\JsonResource;
use Yajra\DataTables\DataTableAbstract;

class GlobalHelper {
    public static function responseSuccess($message,$data=null,$code=200): \Illuminate\Http\JsonResponse
    {
        if($data instanceof DataTableAbstract){
            return response()->json([
                'meta' => [
                    'message' => $message,
                    'code' => $code,
                ],
                'draw' => isset($data->toArray()['draw']) ? $data->toArray()['draw'] : 0,
                'recordsTotal' => isset($data->toArray()['recordsTotal']) ? $data->toArray()['recordsTotal'] : 0,
                'recordsFiltered' => isset($data->toArray()['recordsFiltered']) ? $data->toArray()['recordsFiltered'] : 0
            ], $code);
        } else if($data instanceof JsonResource) {
            return response()->json([
                'meta' => [
                    'message' => $message,
                    'code' => $code,
                    'count' => $data->count(),
                    'total' => $data->total(),
                    'perPage'=> $data->perPage(),
                    'currentPage'   => $data->currentPage()
//                    'prev'  => $data->previousPageUrl(),
//                    'next'  => $data->nextPageUrl(),
//
                ],
                'data'  => $data
            ], $code);
        } else {
            return response()->json([
                'meta' => [
                    'message' => $message,
                    'code' => $code,
                ],
                'data'  => $data
            ], $code);
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
//            'number'    => $message->getLine(),
//            'action'    => $message->getFile()
        ];

        return response()->json([
            'meta'   => $meta,
            'data'  => $data
        ],$code);
    }
}
