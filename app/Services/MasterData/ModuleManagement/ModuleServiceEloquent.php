<?php


namespace App\Services\MasterData\ModuleManagement;

use App\Http\Resources\ModuleResource;
use App\Libraries\Auth;
use App\Models\Module;
use App\Services\MasterData\MasterDataServiceEloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ModuleServiceEloquent extends MasterDataServiceEloquent
{
    /**
     * ModuleServiceEloquent constructor.
     *
     * @param Module $model
     * @param string $resource
     */
    public function __construct(Module $model, $resource = ModuleResource::class)
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
            "icon"          => $request->icon,
            "status"        => $request->status,
            "created_by"    => Auth::user()->id
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
        $model->icon = $request->icon;
        $model->status = $request->status;
        $model->updated_by = Auth::user()->id;

        return $model;
    }
}
