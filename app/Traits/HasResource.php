<?php
namespace App\Traits;

use App\Http\Resources\UserResource;
use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Resources\Json\JsonResource;

trait HasResource {

    /**
     * @param string $resource
     * @param Model $model
     * @return \Yajra\DataTables\DataTableAbstract
     * @throws \Exception
     */
    public function toDatatable(string $resource, Model $model): \Yajra\DataTables\DataTableAbstract
    {
        return DataTables::of($resource::collection($model->paginate(10)));
    }

    /**
     * @param string $resource
     * @param Model $model
     * @return JsonResource
     */
    public function toSingleResource(string $resource, Model $model): JsonResource
    {
        return new $resource($model);
    }
}
