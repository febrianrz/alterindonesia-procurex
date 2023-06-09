<?php


namespace App\Services\MasterData\UserManagement;

use Alterindonesia\Procurex\Facades\GlobalHelper;
use Alterindonesia\Procurex\Filters\FilterDate;
use App\Http\Resources\PermissionResource;
use App\Http\Resources\UserResource;
use App\Models\Permission;
use App\Models\Planner;
use App\Models\Role;
use App\Models\User;
use App\Services\MasterData\MasterDataServiceEloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

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

    public function list(Request $request): array
    {

        $plannerEmpNoList = $this->getPlannerEmpNoListByFilter($request);

        $query = QueryBuilder::for($this->model)
            ->allowedFields('id', ...$this->model->getFillable())
            ->allowedFilters($this->overrideAllowedFilters() ?? [
                AllowedFilter::custom('created_at', new FilterDate()),
                AllowedFilter::custom('updated_at', new FilterDate()),
                AllowedFilter::trashed(),
                ...$this->model->getFillable(),
                ...$this->defaultAllowedFilter,
            ])
            ->defaultSort($this->model->getKeyName())
            ->allowedSorts(
                ...$this->defaultAllowedSorts,
                ...$this->model->getFillable()
            )
            ->with('planner')
            ->allowedIncludes($this->defaultAllowedIncludes);

        if ($plannerEmpNoList) {
            $query->whereIn('username', $plannerEmpNoList);
        }

        $this->result['data'] = $query->paginate($request->query('perPage', $this->perPage));

        return $this->result;
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
            AllowedFilter::callback('planner_level', function (Builder $query, $value) {}),
            AllowedFilter::callback('planner_same_division_emp_no', function (Builder $query, $value) {}),
        ];
    }

    /** @noinspection UnknownColumnInspection */
    private function getPlannerEmpNoListByFilter(Request $request): ?Collection
    {
        if ($request->isNotFilled('filter.planner_same_division_emp_no', 'filter.planner_level')) {
            return null;
        }

        $plannerQuery = Planner::query();

        //region filter[planner_same_division_emp_no]
        $sameDivisionWithPlannerEmpNo = $request->input('filter.planner_same_division_emp_no');

        if ($sameDivisionWithPlannerEmpNo) {
            $sameDivisionWithPlanner = Planner::with('division')->firstWhere('emp_no', $sameDivisionWithPlannerEmpNo);

            if ($sameDivisionWithPlanner === null) {
                abort(
                    GlobalHelper::responseError("filter[planner_same_division_emp_no] is invalid: Planner not found"),
                    422,
                );
            }

            $sameDivisionWithPlanner->division->is_svp
                ? $plannerQuery->whereRelation('division', 'comp_code', $sameDivisionWithPlanner->division->comp_code)
                : $plannerQuery->where('division_id', $sameDivisionWithPlanner->division_id);

            $plannerQuery->where('emp_no', '!=', $sameDivisionWithPlannerEmpNo);
        }
        //endregion


        // filter[planner_same_division_emp_no]
        $plannerLevel = $request->input('filter.planner_level');

        if ($plannerLevel) {
            $plannerQuery->where('level', $plannerLevel);
        }


        return $plannerQuery->pluck('emp_no');
    }
}
