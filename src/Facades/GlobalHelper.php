<?php
namespace Alterindonesia\Procurex\Facades;

use App\Http\Resources\AnonymousCollection;
use Illuminate\Http\JsonResponse;
use function response;

class GlobalHelper
{
    public static function responseSuccess($message, $data=null, $code=200, $resource=null)
    {
        if ($resource) {
            return new AnonymousCollection(
                $data,
                $resource
            );
        } elseif (is_array($data) || is_object($data)) {
            return response()->json([
                'meta'  => [
                    'message'   => $message,
                    'code'      => $code,
                ],
                'data'      => $data
            ], $code);
        } else {
            return response()->json($data);
        }
    }

    public static function responseError($message, $data=[], $code=400): JsonResponse
    {
        if ($code == 401) {
            return response()->json([
                'message'   => 'Unauthenticated'
            ], $code);
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
        ], $code);
    }
}