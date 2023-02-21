<?php


namespace App\Services\MasterData\UserManagement;

use App\Http\Resources\RoleResource;
use App\Models\Role;
use App\Services\MasterData\MasterDataServiceEloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class RoleServiceEloquent extends MasterDataServiceEloquent
{
    /**
     * ModuleServiceEloquent constructor.
     *
     * @param Role $model
     * @param string $resource
     */
    public function __construct(Role $model, $resource = RoleResource::class)
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
            "guard_name"    => $request->guard_name,
            "code"          => $request->code,
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
        $model->code = $request->code;

        return $model;
    }
}
