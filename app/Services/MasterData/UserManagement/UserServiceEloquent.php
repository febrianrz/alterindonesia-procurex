<?php


namespace App\Services\MasterData\UserManagement;

use App\Http\Resources\PermissionResource;
use App\Http\Resources\UserResource;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Services\MasterData\MasterDataServiceEloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;

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
     * @param Request $request
     * @return array
     */
    public function create(Request $request): array
    {
        $result = parent::create($request);
        foreach ($request->input('roles') as $role_id){
            $role = Role::find($role_id);
            if($role) $result['data']->assignRole($role->name);
        }
       return $result;
    }

    /**
     * @param string $id
     * @param Request $request
     * @return array
     */
    public function update(string $id, Request $request): array
    {
        $result = parent::update($id,$request);

        // Revoke All Roles
        $role_names = $result['data']->roles()->pluck('name');
        foreach($role_names as $role_name) {
            $result['data']->removeRole($role_name);
        }

        foreach ($request->input('roles') as $role_id){
            $role = Role::find($role_id);
            if($role) $result['data']->assignRole($role->name);
        }
        return $result;
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

    protected function overrideAllowedFilters(): ?array
    {
        return [
            'username',
            'name',
            'email',
            'company_code',
            AllowedFilter::callback('is_planner', function (Builder $query, $value) {
                $value = (bool) $value;

                if ($value) {
                    $query->has('planner');
                }
            })
        ];
    }
}
