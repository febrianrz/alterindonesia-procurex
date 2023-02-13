<?php


namespace App\Services\MasterData\UserManagement;

use App\Http\Resources\PermissionResource;
use App\Http\Resources\UserResource;
use App\Models\Permission;
use App\Models\User;
use App\Services\MasterData\MasterDataServiceEloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class UserServiceEloquent extends MasterDataServiceEloquent
{
    /**
     * ModuleServiceEloquent constructor.
     *
     * @param User $model
     * @param string $resource
     */
    public function __construct(User $model, $resource = UserResource::class)
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
        $data = $request->only(['name','email','status','company_code','username']);
        $data['password'] = bcrypt($request->password);
        return $data;
    }

    /**
     * @param Model $model
     * @param Request $request
     * @return Model
     */
    protected function setUpdatedData(Model $model, Request $request): Model
    {
        // set data
        $data = $request->only(['name','email','status','company_code','username']);
        if($request->has('password')) $data['password'] = bcrypt($request->password);
        $model->fill($data);

        return $model;
    }
}
