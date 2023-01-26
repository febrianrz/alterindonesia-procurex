<?php
namespace App\Traits;

use App\Http\Resources\UserResource;
use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Resources\Json\JsonResource;

trait HasResource {

    protected int $perPage = 25;
    /**
     * @param string $resource
     * @param Model $model
     * @return JsonResource
     */
    public function toTableList(string $resource, Model $model): JsonResource
    {
        return $resource::collection($model->paginate($this->perPage));
    }

    /**
     * @param string $resource
     * @param Model $model
     * @return JsonResource
     */
    public function toRow(string $resource, Model $model): JsonResource
    {
        return new $resource($model);
    }
}
