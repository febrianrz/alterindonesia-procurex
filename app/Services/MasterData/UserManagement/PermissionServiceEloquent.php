<?php


namespace App\Services\MasterData\UserManagement;

use App\Http\Resources\PermissionResource;
use App\Models\Permission;
use App\Services\MasterData\MasterDataServiceEloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class PermissionServiceEloquent extends MasterDataServiceEloquent
{
    /**
     * ModuleServiceEloquent constructor.
     *
     * @param Permission $model
     * @param string $resource
     */
    public function __construct(Permission $model, $resource = PermissionResource::class)
    {
        parent::__construct($model, $resource);
    }

    /**
     * @param Request $request
     * @return array
     */
    protected function generateNewData(Request $request): array
    {
        // return new data
        return [
            "name"          => $request->name,
            "guard_name"    => $request->guard_name
        ];
    }

    /**
     * @param Model $model
     * @param Request $request
     * @return Model
     */
    protected function setUpdatedData(Model $model, Request $request): Model
    {
        // set data
        $model->name = $request->name;
        $model->guard_name = $request->guard_name;

        return $model;
    }
}
